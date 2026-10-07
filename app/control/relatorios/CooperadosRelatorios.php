<?php

class CooperadosRelatorios extends TPage
{
    private static $database = 'databaserede';
    private static $formName = 'form_CooperadosRelatorios';

    public function __construct($param = null)
    {
        parent::__construct();

        if (in_array($_REQUEST['method'] ?? '', ['onAtualizarAjax', 'onRelatorioCooperadosAjax'], true)) {
            return;
        }

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        try {
            TTransaction::open(self::$database);

            $filtros = $this->prepararFiltros([]);
            $dados = $this->buscarDadosDashboard($filtros);
            $opcoes = $this->buscarOpcoesFiltros();
            $pagina = $this->montarPagina($dados, $opcoes);

            TTransaction::close();

            $panel = new TPanelGroup();
            $panel->class .= ' cooperados-relatorios-host-panel';
            $panel->style = 'border: none; box-shadow: none; background: transparent;';
            $panel->getBody()->class .= ' cooperados-relatorios-panel-body';
            $panel->add($pagina);

            $container = new TVBox();
            $container->style = 'width: 100%;';
            $container->class = 'cooperados-relatorios-host-container';

            $container->add($panel);
            parent::add($container);
        } catch (Throwable $e) {
            $this->rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {
    }

    public function onAtualizarAjax($param = null)
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: private, no-store');

        try {
            $filtros = $this->prepararFiltros($_POST);

            TTransaction::open(self::$database);
            $dados = $this->buscarDadosDashboard($filtros);
            TTransaction::close();

            echo json_encode(
                ['sucesso' => true, 'dados' => $dados],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            );
            exit;
        } catch (Throwable $e) {
            $this->rollback();
            http_response_code(500);

            echo json_encode(
                ['sucesso' => false, 'erro' => $e->getMessage()],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            );
            exit;
        }
    }

    public function onRelatorioCooperadosAjax($param = null)
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: private, no-store');

        try {
            $filtros = $this->prepararFiltros($_POST);

            TTransaction::open(self::$database);
            $dados = $this->buscarDadosRelatorioCooperados($filtros);
            TTransaction::close();

            echo json_encode(
                ['sucesso' => true, 'dados' => $dados],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            );
            exit;
        } catch (Throwable $e) {
            $this->rollback();
            http_response_code(500);

            $mensagem = $e instanceof InvalidArgumentException
                ? $e->getMessage()
                : 'Não foi possível carregar o relatório de cooperados. Tente novamente.';

            echo json_encode(
                ['sucesso' => false, 'erro' => $mensagem],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            );
            exit;
        }
    }

    private function buscarDadosDashboard(array $filtros): array
    {
        [$where, $parametros] = $this->montarFiltrosSql($filtros);
        $periodoNovos = $this->montarPeriodoNovos($filtros);
        $parametrosKpi = array_merge($parametros, $periodoNovos['parametros']);
        $situacaoSql = $this->expressaoSituacaoSql();

        $resumo = $this->consultarUmaLinha(
            "SELECT
                COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN {$situacaoSql} = 'ATIVO' THEN 1 ELSE 0 END), 0) AS ativos,
                COALESCE(SUM(CASE WHEN {$situacaoSql} = 'INATIVO' THEN 1 ELSE 0 END), 0) AS inativos,
                COALESCE(SUM(CASE WHEN {$situacaoSql} = 'NAO_INFORMADO' THEN 1 ELSE 0 END), 0) AS nao_informados,
                COALESCE(SUM(CASE WHEN {$periodoNovos['condicao']} THEN 1 ELSE 0 END), 0) AS novos
             FROM cooperados c
             WHERE {$where}",
            $parametrosKpi
        );

        // A expressão precisa ser repetida no GROUP BY: o alias "nome" conflita
        // com cooperados.nome e faria o MySQL criar um grupo para cada cooperado.
        $situacao = $this->consultar(
            "SELECT
                CASE {$situacaoSql}
                    WHEN 'ATIVO' THEN 'Ativos'
                    WHEN 'INATIVO' THEN 'Inativos'
                    ELSE 'Não informado'
                END AS nome,
                COUNT(*) AS total
             FROM cooperados c
             WHERE {$where}
             GROUP BY {$situacaoSql}
             ORDER BY CASE {$situacaoSql}
                        WHEN 'ATIVO' THEN 1
                        WHEN 'INATIVO' THEN 2
                        ELSE 3
                      END",
            $parametros
        );

        $sexoBruto = $this->consultar(
            "SELECT
                COALESCE(NULLIF(TRIM(c.sexo), ''), '__NAO_INFORMADO__') AS codigo,
                COUNT(*) AS total
             FROM cooperados c
             WHERE {$where}
             GROUP BY COALESCE(NULLIF(TRIM(c.sexo), ''), '__NAO_INFORMADO__')
             ORDER BY total DESC, codigo",
            $parametros
        );

        $mapaSexos = $this->buscarMapaSexos();
        $sexoAcumulado = [];

        foreach ($sexoBruto as $linha) {
            $codigo = (string) $linha['codigo'];
            $codigoNormalizado = mb_strtoupper(trim($codigo), 'UTF-8');

            if ($codigo === '__NAO_INFORMADO__') {
                $nome = 'Não informado';
            } else {
                $fallback = $codigo;

                if (in_array($codigoNormalizado, ['M', 'MASCULINO'], true)) {
                    $fallback = 'Masculino';
                } elseif (in_array($codigoNormalizado, ['F', 'FEMININO'], true)) {
                    $fallback = 'Feminino';
                }

                $nome = $this->normalizarRotuloGrafico($mapaSexos[$codigo] ?? $fallback, $fallback);

                if (in_array(mb_strtoupper($nome, 'UTF-8'), ['M', 'MASCULINO'], true)) {
                    $nome = 'Masculino';
                } elseif (in_array(mb_strtoupper($nome, 'UTF-8'), ['F', 'FEMININO'], true)) {
                    $nome = 'Feminino';
                }
            }

            $sexoAcumulado[$nome] = ($sexoAcumulado[$nome] ?? 0) + (int) $linha['total'];
        }

        arsort($sexoAcumulado);
        $sexo = [];

        foreach ($sexoAcumulado as $nome => $totalSexo) {
            $sexo[] = ['nome' => $nome, 'total' => $totalSexo];
        }

        $parametrosEspecialidade = $parametros;
        $filtroGraficoEspecialidade = '';

        if ($filtros['especialidade_id'] !== null) {
            $filtroGraficoEspecialidade = ' AND ce.especialidades_id = :grafico_especialidade_id';
            $parametrosEspecialidade['grafico_especialidade_id'] = $filtros['especialidade_id'];
        }

        $especialidades = $this->consultar(
            "SELECT
                e.especialidade AS nome,
                COUNT(DISTINCT c.id) AS total
             FROM cooperados c
             INNER JOIN cooperados_especialidades ce ON ce.cooperados_id = c.id
             INNER JOIN especialidades e ON e.id = ce.especialidades_id
             WHERE {$where}{$filtroGraficoEspecialidade}
               AND NULLIF(TRIM(e.especialidade), '') IS NOT NULL
             GROUP BY e.id, e.especialidade
             ORDER BY total DESC, e.especialidade",
            $parametrosEspecialidade
        );

        $parametrosCidade = $parametros;
        $filtroGraficoCidade = '';

        if ($filtros['cidade_id'] !== null) {
            $filtroGraficoCidade = ' AND ec.cidades_id = :grafico_cidade_id';
            $parametrosCidade['grafico_cidade_id'] = $filtros['cidade_id'];
        }

        $cidades = $this->consultar(
            "SELECT
                ci.cidade AS nome,
                COUNT(DISTINCT c.id) AS total
             FROM cooperados c
             INNER JOIN enderecos_cooperados ec ON ec.cooperados_id = c.id
             INNER JOIN cidades ci ON ci.id = ec.cidades_id
             WHERE {$where}{$filtroGraficoCidade}
               AND NULLIF(TRIM(ci.cidade), '') IS NOT NULL
             GROUP BY ci.id, ci.cidade
             ORDER BY total DESC, ci.cidade",
            $parametrosCidade
        );

        return [
            'kpis' => [
                'total' => (int) ($resumo['total'] ?? 0),
                'ativos' => (int) ($resumo['ativos'] ?? 0),
                'inativos' => (int) ($resumo['inativos'] ?? 0),
                'nao_informados' => (int) ($resumo['nao_informados'] ?? 0),
                'novos' => (int) ($resumo['novos'] ?? 0),
                'periodo_novos' => $periodoNovos['rotulo'],
            ],
            'graficos' => [
                'situacao' => $this->normalizarAgrupamento($situacao, false),
                'sexo' => $sexo,
                'especialidades' => $this->normalizarAgrupamento($especialidades),
                'cidades' => $this->normalizarAgrupamento($cidades),
            ],
            'atualizado_em' => date('d/m/Y H:i'),
        ];
    }

    /**
     * Fonte única do relatório cadastral. O retorno estruturado pode ser
     * reutilizado posteriormente pelos exportadores PDF, CSV e XLS/XLSX.
     */
    private function buscarDadosRelatorioCooperados(array $filtros): array
    {
        [$where, $parametros] = $this->montarFiltrosSql($filtros);
        $situacaoSql = $this->expressaoSituacaoSql();
        $cooperados = $this->consultar(
            "SELECT
                c.id,
                c.nome,
                c.crm,
                c.cpf,
                c.rg,
                c.sexo,
                c.data_nascimento,
                c.estado_civil,
                c.data_filiacao,
                c.dt_desfiliacao,
                {$situacaoSql} AS situacao_codigo,
                c.forma_integralizacao,
                c.cnis,
                c.inss,
                c.flg_recolhe_inss,
                c.flg_retem_ir,
                c.flg_declara_dep,
                c.numero_filhos
             FROM cooperados c
             WHERE {$where}
             ORDER BY c.nome ASC, c.id ASC",
            $parametros
        );

        $especialidadesPorCooperado = [];
        $cidadesPorCooperado = [];

        if ($cooperados) {
            $especialidadesPorCooperado = $this->agruparRelacionamentosRelatorio(
                $this->consultar(
                    "SELECT c.id AS cooperado_id, e.especialidade AS nome
                     FROM cooperados c
                     INNER JOIN cooperados_especialidades ce ON ce.cooperados_id = c.id
                     INNER JOIN especialidades e ON e.id = ce.especialidades_id
                     WHERE {$where}
                       AND NULLIF(TRIM(e.especialidade), '') IS NOT NULL
                     ORDER BY c.id, e.especialidade",
                    $parametros
                )
            );

            $cidadesPorCooperado = $this->agruparRelacionamentosRelatorio(
                $this->consultar(
                    "SELECT c.id AS cooperado_id, ci.cidade AS nome
                     FROM cooperados c
                     INNER JOIN enderecos_cooperados ec ON ec.cooperados_id = c.id
                     INNER JOIN cidades ci ON ci.id = ec.cidades_id
                     WHERE {$where}
                       AND NULLIF(TRIM(ci.cidade), '') IS NOT NULL
                     ORDER BY c.id, ci.cidade",
                    $parametros
                )
            );
        }

        $mapaSexos = $this->buscarMapaSexos();
        $mapaEstadosCivis = $this->mapearOpcoes($this->buscarOpcoesDominioCol('estado_civil'));
        $registros = [];

        foreach ($cooperados as $cooperado) {
            $id = (int) ($cooperado['id'] ?? 0);
            $situacaoCodigo = (string) ($cooperado['situacao_codigo'] ?? '');

            if ($situacaoCodigo === 'ATIVO') {
                $situacao = 'Ativo';
            } elseif ($situacaoCodigo === 'INATIVO') {
                $situacao = 'Inativo';
            } else {
                $situacao = 'Não informado';
            }

            $registros[] = [
                'nome' => $this->valorRelatorio($cooperado['nome'] ?? null),
                'crm' => $this->valorRelatorio($cooperado['crm'] ?? null),
                'cpf' => $this->formatarCpfRelatorio($cooperado['cpf'] ?? null),
                'rg' => $this->valorRelatorio($cooperado['rg'] ?? null),
                'sexo' => $this->formatarSexoRelatorio($cooperado['sexo'] ?? null, $mapaSexos),
                'data_nascimento' => $this->formatarDataRelatorio($cooperado['data_nascimento'] ?? null),
                'estado_civil' => $this->formatarDominioRelatorio($cooperado['estado_civil'] ?? null, $mapaEstadosCivis),
                'data_filiacao' => $this->formatarDataRelatorio($cooperado['data_filiacao'] ?? null),
                'dt_desfiliacao' => $this->formatarDataRelatorio($cooperado['dt_desfiliacao'] ?? null),
                'situacao' => $situacao,
                'especialidades' => $this->valorRelatorio($especialidadesPorCooperado[$id] ?? null),
                'cidades' => $this->valorRelatorio($cidadesPorCooperado[$id] ?? null),
                'cnis' => $this->valorRelatorio($cooperado['cnis'] ?? null),
                'inss' => $this->valorRelatorio($cooperado['inss'] ?? null),
                'flg_recolhe_inss' => $this->formatarSimNaoRelatorio($cooperado['flg_recolhe_inss'] ?? null),
                'flg_retem_ir' => $this->formatarSimNaoRelatorio($cooperado['flg_retem_ir'] ?? null),
                'flg_declara_dep' => $this->formatarSimNaoRelatorio($cooperado['flg_declara_dep'] ?? null),
                'numero_filhos' => ($cooperado['numero_filhos'] ?? '') === '' || $cooperado['numero_filhos'] === null
                    ? '-'
                    : (string) ((int) $cooperado['numero_filhos']),
                'forma_integralizacao' => $this->valorRelatorio($cooperado['forma_integralizacao'] ?? null),
            ];
        }

        return [
            'total' => count($registros),
            'filtros_aplicados' => $this->descreverFiltrosAplicados($filtros, $mapaSexos, $mapaEstadosCivis),
            'colunas' => [
                ['chave' => 'nome', 'rotulo' => 'Nome'],
                ['chave' => 'crm', 'rotulo' => 'CRM'],
                ['chave' => 'cpf', 'rotulo' => 'CPF'],
                ['chave' => 'rg', 'rotulo' => 'RG'],
                ['chave' => 'sexo', 'rotulo' => 'Sexo'],
                ['chave' => 'data_nascimento', 'rotulo' => 'Nascimento'],
                ['chave' => 'estado_civil', 'rotulo' => 'Estado civil'],
                ['chave' => 'data_filiacao', 'rotulo' => 'Filiação'],
                ['chave' => 'dt_desfiliacao', 'rotulo' => 'Desfiliação'],
                ['chave' => 'situacao', 'rotulo' => 'Situação'],
                ['chave' => 'especialidades', 'rotulo' => 'Especialidades'],
                ['chave' => 'cidades', 'rotulo' => 'Cidade(s)'],
                ['chave' => 'cnis', 'rotulo' => 'CNIS'],
                ['chave' => 'inss', 'rotulo' => 'INSS'],
                ['chave' => 'flg_recolhe_inss', 'rotulo' => 'Recolhe INSS'],
                ['chave' => 'flg_retem_ir', 'rotulo' => 'Retém IR'],
                ['chave' => 'flg_declara_dep', 'rotulo' => 'Declara dependentes'],
                ['chave' => 'numero_filhos', 'rotulo' => 'Nº dependentes'],
                ['chave' => 'forma_integralizacao', 'rotulo' => 'Forma de integralização'],
            ],
            'registros' => $registros,
        ];
    }

    private function montarFiltrosSql(array $filtros): array
    {
        $condicoes = ['1 = 1'];
        $parametros = [];

        if ($filtros['nome'] !== '') {
            $condicoes[] = 'c.nome LIKE :nome';
            $parametros['nome'] = '%' . $filtros['nome'] . '%';
        }

        foreach (['crm', 'cpf', 'rg', 'cnis'] as $campo) {
            if ($filtros[$campo] !== '') {
                $condicoes[] = "c.{$campo} = :{$campo}";
                $parametros[$campo] = $filtros[$campo];
            }
        }

        if ($filtros['inss'] !== '') {
            $condicoes[] = 'c.inss LIKE :inss';
            $parametros['inss'] = '%' . $filtros['inss'] . '%';
        }

        if ($filtros['situacao'] !== '') {
            $condicoes[] = $this->expressaoSituacaoSql() . ' = :situacao';
            $parametros['situacao'] = $filtros['situacao'];
        }

        if ($filtros['data_filiacao_inicio'] !== null) {
            $condicoes[] = 'c.data_filiacao >= :data_filiacao_inicio';
            $parametros['data_filiacao_inicio'] = $filtros['data_filiacao_inicio'];
        }

        if ($filtros['data_filiacao_fim'] !== null) {
            $condicoes[] = 'c.data_filiacao <= :data_filiacao_fim';
            $parametros['data_filiacao_fim'] = $filtros['data_filiacao_fim'];
        }

        if ($filtros['sexo'] !== '') {
            $condicoes[] = 'c.sexo = :sexo';
            $parametros['sexo'] = $filtros['sexo'];
        }

        if ($filtros['estado_civil'] !== '') {
            $condicoes[] = 'c.estado_civil = :estado_civil';
            $parametros['estado_civil'] = $filtros['estado_civil'];
        }

        if ($filtros['data_nascimento'] !== null) {
            $condicoes[] = 'c.data_nascimento = :data_nascimento';
            $parametros['data_nascimento'] = $filtros['data_nascimento'];
        }

        if ($filtros['dt_desfiliacao_inicio'] !== null) {
            $condicoes[] = 'c.dt_desfiliacao >= :dt_desfiliacao_inicio';
            $parametros['dt_desfiliacao_inicio'] = $filtros['dt_desfiliacao_inicio'];
        }

        if ($filtros['dt_desfiliacao_fim'] !== null) {
            $condicoes[] = 'c.dt_desfiliacao <= :dt_desfiliacao_fim';
            $parametros['dt_desfiliacao_fim'] = $filtros['dt_desfiliacao_fim'];
        }

        foreach (['flg_recolhe_inss', 'flg_retem_ir', 'flg_declara_dep'] as $campo) {
            if ($filtros[$campo] !== '') {
                $condicoes[] = "c.{$campo} = :{$campo}";
                $parametros[$campo] = $filtros[$campo];
            }
        }

        if ($filtros['especialidade_id'] !== null) {
            $condicoes[] = 'EXISTS (
                SELECT 1
                FROM cooperados_especialidades cef
                WHERE cef.cooperados_id = c.id
                  AND cef.especialidades_id = :especialidade_id
            )';
            $parametros['especialidade_id'] = $filtros['especialidade_id'];
        }

        if ($filtros['cidade_id'] !== null) {
            $condicoes[] = 'EXISTS (
                SELECT 1
                FROM enderecos_cooperados ecf
                WHERE ecf.cooperados_id = c.id
                  AND ecf.cidades_id = :cidade_id
            )';
            $parametros['cidade_id'] = $filtros['cidade_id'];
        }

        if ($filtros['vigente_final'] !== null) {
            $condicoes[] = 'c.data_filiacao <= :vigente_final';
            $parametros['vigente_final'] = $filtros['vigente_final'];
        }

        if ($filtros['vigente_inicial'] !== null) {
            $condicoes[] = '(c.dt_desfiliacao IS NULL OR c.dt_desfiliacao >= :vigente_inicial)';
            $parametros['vigente_inicial'] = $filtros['vigente_inicial'];
        }

        return [implode("\n AND ", $condicoes), $parametros];
    }

    /**
     * Normaliza cooperados.ativo para que KPIs, filtros e gráficos usem a mesma regra.
     * O schema persiste CHAR(1) e o padrão do sistema é S/N; as demais formas são
     * toleradas somente para absorver possíveis dados legados.
     */
    private function expressaoSituacaoSql(): string
    {
        $valor = "UPPER(TRIM(COALESCE(c.ativo, '')))";

        return "CASE
                    WHEN {$valor} IN ('S', 'SIM', '1') THEN 'ATIVO'
                    WHEN {$valor} IN ('N', 'NAO', 'NÃO', '0') THEN 'INATIVO'
                    ELSE 'NAO_INFORMADO'
                END";
    }

    private function montarPeriodoNovos(array $filtros): array
    {
        $condicoes = ["c.data_filiacao IS NOT NULL"];
        $parametros = [];

        if ($filtros['data_filiacao_inicio'] !== null) {
            $condicoes[] = 'c.data_filiacao >= :novos_data_inicio';
            $parametros['novos_data_inicio'] = $filtros['data_filiacao_inicio'];
        }

        if ($filtros['data_filiacao_fim'] !== null) {
            $condicoes[] = 'c.data_filiacao <= :novos_data_fim';
            $parametros['novos_data_fim'] = $filtros['data_filiacao_fim'];
        } else {
            $condicoes[] = 'c.data_filiacao <= CURDATE()';
        }

        if ($filtros['data_filiacao_inicio'] === null && $filtros['data_filiacao_fim'] === null) {
            $condicoes[] = 'c.data_filiacao >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)';
            $rotulo = 'Últimos 12 meses';
        } else {
            $inicio = $filtros['data_filiacao_inicio'] ? $this->formatarData($filtros['data_filiacao_inicio']) : 'início dos registros';
            $fim = $filtros['data_filiacao_fim'] ? $this->formatarData($filtros['data_filiacao_fim']) : 'hoje';
            $rotulo = $inicio . ' a ' . $fim;
        }

        return [
            'condicao' => implode(' AND ', $condicoes),
            'parametros' => $parametros,
            'rotulo' => $rotulo,
        ];
    }

    private function buscarOpcoesFiltros(): array
    {
        $sexos = [];

        foreach ($this->buscarMapaSexos() as $valor => $mascara) {
            $sexos[] = ['id' => $valor, 'nome' => $mascara];
        }

        return [
            'sexos' => $sexos,
            'estados_civis' => $this->buscarOpcoesDominioCol('estado_civil'),
            'especialidades' => $this->consultar(
                "SELECT id, especialidade AS nome
                 FROM especialidades
                 WHERE NULLIF(TRIM(especialidade), '') IS NOT NULL
                 ORDER BY especialidade"
            ),
            'cidades' => $this->consultar(
                "SELECT DISTINCT ci.id, ci.cidade AS nome
                 FROM cidades ci
                 INNER JOIN enderecos_cooperados ec ON ec.cidades_id = ci.id
                 WHERE NULLIF(TRIM(ci.cidade), '') IS NOT NULL
                 ORDER BY ci.cidade"
            ),
        ];
    }

    private function buscarOpcoesDominioCol(string $atributo): array
    {
        $linhas = $this->consultar(
            "SELECT valor AS id, mascara AS nome
             FROM v_dominio_valor_col
             WHERE objeto = :objeto
               AND atributo = :atributo
             ORDER BY sequencia, mascara",
            ['objeto' => 'cooperados', 'atributo' => $atributo]
        );
        $opcoes = [];

        foreach ($linhas as $linha) {
            $id = trim((string) ($linha['id'] ?? ''));

            if ($id !== '') {
                $opcoes[] = [
                    'id' => $id,
                    'nome' => $this->normalizarRotuloGrafico($linha['nome'] ?? '', $id),
                ];
            }
        }

        return $opcoes;
    }

    private function buscarMapaSexos(): array
    {
        $linhas = $this->consultar(
            "SELECT valor, mascara
             FROM v_dominio_valor_col
             WHERE objeto = 'cooperados'
               AND atributo = 'sexo'
             ORDER BY sequencia, mascara"
        );
        $mapa = [];

        foreach ($linhas as $linha) {
            $valor = trim((string) ($linha['valor'] ?? ''));

            if ($valor !== '') {
                $mapa[$valor] = $this->normalizarRotuloGrafico($linha['mascara'] ?? '', $valor);
            }
        }

        if (!$mapa) {
            foreach ($this->consultar(
                "SELECT DISTINCT sexo AS valor
                 FROM cooperados
                 WHERE NULLIF(TRIM(sexo), '') IS NOT NULL
                 ORDER BY sexo"
            ) as $linha) {
                $valor = trim((string) $linha['valor']);
                $mapa[$valor] = $valor;
            }
        }

        return $mapa;
    }

    private function consultar(string $sql, array $parametros = []): array
    {
        $stmt = TTransaction::get()->prepare($sql);

        foreach ($parametros as $nome => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(':' . $nome, $valor, $tipo);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function consultarUmaLinha(string $sql, array $parametros = []): array
    {
        return $this->consultar($sql, $parametros)[0] ?? [];
    }

    private function prepararFiltros(array $entrada): array
    {
        $situacao = $this->valorEscalar($entrada, 'situacao');

        if (!in_array($situacao, ['', 'ATIVO', 'INATIVO'], true)) {
            throw new InvalidArgumentException('A situação informada é inválida.');
        }

        $dataInicio = $this->validarData($this->valorEscalar($entrada, 'data_filiacao_inicio'), 'data inicial de filiação');
        $dataFim = $this->validarData($this->valorEscalar($entrada, 'data_filiacao_fim'), 'data final de filiação');
        $desfiliacaoInicio = $this->validarData($this->valorEscalar($entrada, 'dt_desfiliacao_inicio'), 'data inicial de desfiliação');
        $desfiliacaoFim = $this->validarData($this->valorEscalar($entrada, 'dt_desfiliacao_fim'), 'data final de desfiliação');
        $vigenteInicio = $this->validarData($this->valorEscalar($entrada, 'vigente_inicial'), 'data inicial de vigência');
        $vigenteFim = $this->validarData($this->valorEscalar($entrada, 'vigente_final'), 'data final de vigência');

        $this->validarIntervalo($dataInicio, $dataFim, 'filiação');
        $this->validarIntervalo($desfiliacaoInicio, $desfiliacaoFim, 'desfiliação');
        $this->validarIntervalo($vigenteInicio, $vigenteFim, 'vigência');

        $sexo = $this->valorEscalar($entrada, 'sexo');

        if (mb_strlen($sexo) > 50) {
            throw new InvalidArgumentException('O filtro de sexo é inválido.');
        }

        $estadoCivil = $this->valorEscalar($entrada, 'estado_civil');

        if (mb_strlen($estadoCivil) > 50) {
            throw new InvalidArgumentException('O filtro de estado civil é inválido.');
        }

        return [
            'nome' => $this->validarTexto($entrada, 'nome', 150, 'nome'),
            'crm' => $this->validarTexto($entrada, 'crm', 20, 'CRM'),
            'cpf' => $this->validarTexto($entrada, 'cpf', 14, 'CPF'),
            'rg' => $this->validarTexto($entrada, 'rg', 20, 'RG'),
            'cnis' => $this->validarTexto($entrada, 'cnis', 20, 'CNIS'),
            'inss' => $this->validarTexto($entrada, 'inss', 15, 'INSS'),
            'situacao' => $situacao,
            'data_filiacao_inicio' => $dataInicio,
            'data_filiacao_fim' => $dataFim,
            'dt_desfiliacao_inicio' => $desfiliacaoInicio,
            'dt_desfiliacao_fim' => $desfiliacaoFim,
            'data_nascimento' => $this->validarData($this->valorEscalar($entrada, 'data_nascimento'), 'data de nascimento'),
            'vigente_inicial' => $vigenteInicio,
            'vigente_final' => $vigenteFim,
            'sexo' => $sexo,
            'estado_civil' => $estadoCivil,
            'especialidade_id' => $this->validarId($this->valorEscalar($entrada, 'especialidade_id'), 'especialidade'),
            'cidade_id' => $this->validarId($this->valorEscalar($entrada, 'cidade_id'), 'cidade'),
            'flg_recolhe_inss' => $this->validarFlag($this->valorEscalar($entrada, 'flg_recolhe_inss'), 'Recolhe INSS'),
            'flg_retem_ir' => $this->validarFlag($this->valorEscalar($entrada, 'flg_retem_ir'), 'Retém IR'),
            'flg_declara_dep' => $this->validarFlag($this->valorEscalar($entrada, 'flg_declara_dep'), 'Declara dependentes'),
        ];
    }

    private function validarTexto(array $entrada, string $chave, int $limite, string $campo): string
    {
        $valor = $this->valorEscalar($entrada, $chave);

        if (mb_strlen($valor) > $limite) {
            throw new InvalidArgumentException("O filtro de {$campo} é inválido.");
        }

        return $valor;
    }

    private function validarFlag(string $valor, string $campo): string
    {
        if (!in_array($valor, ['', 'S', 'N'], true)) {
            throw new InvalidArgumentException("O filtro {$campo} é inválido.");
        }

        return $valor;
    }

    private function validarIntervalo(?string $inicio, ?string $fim, string $campo): void
    {
        if ($inicio !== null && $fim !== null && $inicio > $fim) {
            throw new InvalidArgumentException("A data inicial de {$campo} não pode ser posterior à data final.");
        }
    }

    private function valorEscalar(array $entrada, string $chave): string
    {
        $valor = $entrada[$chave] ?? '';

        return is_scalar($valor) ? trim((string) $valor) : '';
    }

    private function validarData(string $valor, string $campo): ?string
    {
        if ($valor === '') {
            return null;
        }

        $data = DateTime::createFromFormat('!Y-m-d', $valor);
        $erros = DateTime::getLastErrors();

        if (!$data || ($erros && ($erros['warning_count'] > 0 || $erros['error_count'] > 0)) || $data->format('Y-m-d') !== $valor) {
            throw new InvalidArgumentException('A ' . $campo . ' informada é inválida.');
        }

        return $valor;
    }

    private function validarId(string $valor, string $campo): ?int
    {
        if ($valor === '') {
            return null;
        }

        if (!ctype_digit($valor) || (int) $valor <= 0) {
            throw new InvalidArgumentException('O filtro de ' . $campo . ' é inválido.');
        }

        return (int) $valor;
    }

    private function normalizarAgrupamento(array $linhas, bool $ordenarPorTotal = true): array
    {
        $totais = [];

        foreach ($linhas as $linha) {
            $nome = $this->normalizarRotuloGrafico($linha['nome'] ?? '', 'Não informado');
            $total = (int) ($linha['total'] ?? 0);

            if ($total <= 0) {
                continue;
            }

            $totais[$nome] = ($totais[$nome] ?? 0) + $total;
        }

        if ($ordenarPorTotal) {
            arsort($totais, SORT_NUMERIC);
        }

        $resultado = [];

        foreach ($totais as $nome => $total) {
            $resultado[] = ['nome' => $nome, 'total' => $total];
        }

        return $resultado;
    }

    private function normalizarRotuloGrafico($valor, string $fallback): string
    {
        $texto = html_entity_decode((string) $valor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $texto = strip_tags($texto);
        $texto = preg_replace('/\s+/u', ' ', $texto) ?? '';
        $texto = trim($texto);

        return $texto !== '' ? $texto : $fallback;
    }

    private function agruparRelacionamentosRelatorio(array $linhas): array
    {
        $agrupados = [];

        foreach ($linhas as $linha) {
            $cooperadoId = (int) ($linha['cooperado_id'] ?? 0);
            $nome = $this->normalizarRotuloGrafico($linha['nome'] ?? '', '');

            if ($cooperadoId > 0 && $nome !== '') {
                $agrupados[$cooperadoId][$nome] = true;
            }
        }

        $resultado = [];

        foreach ($agrupados as $cooperadoId => $nomesIndexados) {
            $nomes = array_keys($nomesIndexados);
            natcasesort($nomes);
            $resultado[$cooperadoId] = implode(', ', $nomes);
        }

        return $resultado;
    }

    private function mapearOpcoes(array $opcoes): array
    {
        $mapa = [];

        foreach ($opcoes as $opcao) {
            $id = trim((string) ($opcao['id'] ?? ''));

            if ($id !== '') {
                $mapa[$id] = $this->normalizarRotuloGrafico($opcao['nome'] ?? '', $id);
            }
        }

        return $mapa;
    }

    private function descreverFiltrosAplicados(array $filtros, array $mapaSexos, array $mapaEstadosCivis): array
    {
        $descricoes = [];
        $adicionar = static function (string $rotulo, $valor) use (&$descricoes): void {
            if ($valor !== null && trim((string) $valor) !== '') {
                $descricoes[] = ['rotulo' => $rotulo, 'valor' => (string) $valor];
            }
        };

        $adicionar('Nome', $filtros['nome']);
        $adicionar('CRM', $filtros['crm']);
        $adicionar('CPF', $filtros['cpf']);
        $adicionar('RG', $filtros['rg']);
        $adicionar('Sexo', $filtros['sexo'] !== '' ? $this->formatarSexoRelatorio($filtros['sexo'], $mapaSexos) : null);
        $adicionar('Estado civil', $filtros['estado_civil'] !== '' ? $this->formatarDominioRelatorio($filtros['estado_civil'], $mapaEstadosCivis) : null);
        $adicionar('CNIS', $filtros['cnis']);
        $adicionar('INSS', $filtros['inss']);
        $adicionar('Filiação inicial', $filtros['data_filiacao_inicio'] ? $this->formatarDataRelatorio($filtros['data_filiacao_inicio']) : null);
        $adicionar('Filiação final', $filtros['data_filiacao_fim'] ? $this->formatarDataRelatorio($filtros['data_filiacao_fim']) : null);
        $adicionar('Nascimento', $filtros['data_nascimento'] ? $this->formatarDataRelatorio($filtros['data_nascimento']) : null);
        $adicionar('Desfiliação inicial', $filtros['dt_desfiliacao_inicio'] ? $this->formatarDataRelatorio($filtros['dt_desfiliacao_inicio']) : null);
        $adicionar('Desfiliação final', $filtros['dt_desfiliacao_fim'] ? $this->formatarDataRelatorio($filtros['dt_desfiliacao_fim']) : null);

        if ($filtros['situacao'] !== '') {
            $adicionar('Situação', $filtros['situacao'] === 'ATIVO' ? 'Ativo' : 'Inativo');
        }

        if ($filtros['especialidade_id'] !== null) {
            $especialidade = $this->consultarUmaLinha(
                'SELECT especialidade AS nome FROM especialidades WHERE id = :id',
                ['id' => $filtros['especialidade_id']]
            );
            $adicionar('Especialidade', $this->normalizarRotuloGrafico($especialidade['nome'] ?? '', 'Não encontrada'));
        }

        if ($filtros['cidade_id'] !== null) {
            $cidade = $this->consultarUmaLinha(
                'SELECT cidade AS nome FROM cidades WHERE id = :id',
                ['id' => $filtros['cidade_id']]
            );
            $adicionar('Cidade', $this->normalizarRotuloGrafico($cidade['nome'] ?? '', 'Não encontrada'));
        }

        if ($filtros['flg_recolhe_inss'] !== '') {
            $adicionar('Recolhe INSS', $this->formatarSimNaoRelatorio($filtros['flg_recolhe_inss']));
        }

        if ($filtros['flg_retem_ir'] !== '') {
            $adicionar('Retém IR', $this->formatarSimNaoRelatorio($filtros['flg_retem_ir']));
        }

        if ($filtros['flg_declara_dep'] !== '') {
            $adicionar('Declara dependentes', $this->formatarSimNaoRelatorio($filtros['flg_declara_dep']));
        }

        $adicionar('Vigente inicial', $filtros['vigente_inicial'] ? $this->formatarDataRelatorio($filtros['vigente_inicial']) : null);
        $adicionar('Vigente final', $filtros['vigente_final'] ? $this->formatarDataRelatorio($filtros['vigente_final']) : null);

        return $descricoes;
    }

    private function valorRelatorio($valor): string
    {
        $texto = trim((string) ($valor ?? ''));

        return $texto !== '' ? $texto : '-';
    }

    private function formatarDataRelatorio($valor): string
    {
        $texto = trim((string) ($valor ?? ''));

        if ($texto === '') {
            return '-';
        }

        $data = DateTime::createFromFormat('!Y-m-d', substr($texto, 0, 10));

        return $data ? $data->format('d/m/Y') : $this->valorRelatorio($texto);
    }

    private function formatarCpfRelatorio($valor): string
    {
        $texto = trim((string) ($valor ?? ''));
        $digitos = preg_replace('/\D+/', '', $texto) ?? '';

        if (strlen($digitos) === 11) {
            return substr($digitos, 0, 3) . '.'
                . substr($digitos, 3, 3) . '.'
                . substr($digitos, 6, 3) . '-'
                . substr($digitos, 9, 2);
        }

        return $this->valorRelatorio($texto);
    }

    private function formatarSexoRelatorio($valor, array $mapaSexos): string
    {
        $codigo = trim((string) ($valor ?? ''));

        if ($codigo === '') {
            return '-';
        }

        if (isset($mapaSexos[$codigo])) {
            return $this->normalizarRotuloGrafico($mapaSexos[$codigo], '-');
        }

        $normalizado = mb_strtoupper($codigo, 'UTF-8');

        if (in_array($normalizado, ['M', 'MASCULINO'], true)) {
            return 'Masculino';
        }

        if (in_array($normalizado, ['F', 'FEMININO'], true)) {
            return 'Feminino';
        }

        return 'Não informado';
    }

    private function formatarDominioRelatorio($valor, array $mapa): string
    {
        $codigo = trim((string) ($valor ?? ''));

        if ($codigo === '') {
            return '-';
        }

        return isset($mapa[$codigo])
            ? $this->normalizarRotuloGrafico($mapa[$codigo], '-')
            : 'Não informado';
    }

    private function formatarSimNaoRelatorio($valor): string
    {
        $normalizado = mb_strtoupper(trim((string) ($valor ?? '')), 'UTF-8');

        if (in_array($normalizado, ['S', 'SIM', '1'], true)) {
            return 'Sim';
        }

        if (in_array($normalizado, ['N', 'NAO', 'NÃO', '0'], true)) {
            return 'Não';
        }

        return '-';
    }

    private function montarPagina(array $dados, array $opcoes): string
    {
        $total = $this->numero($dados['kpis']['total']);
        $ativos = $this->numero($dados['kpis']['ativos']);
        $inativos = $this->numero($dados['kpis']['inativos']);
        $novos = $this->numero($dados['kpis']['novos']);
        $periodoNovos = $this->h($dados['kpis']['periodo_novos']);
        $atualizadoEm = $this->h($dados['atualizado_em']);
        $optionsSexos = $this->montarOptions($opcoes['sexos'], 'Todos');
        $optionsEstadosCivis = $this->montarOptions($opcoes['estados_civis'], 'Todos');
        $optionsEspecialidades = $this->montarOptions($opcoes['especialidades'], 'Todas');
        $optionsCidades = $this->montarOptions($opcoes['cidades'], 'Todas');
        $config = json_encode(
            ['dados' => $dados],
            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        return <<<HTML
        <link rel="stylesheet" href="app/lib/include/css/cooperadosRelatorios.css">

        <main class="cooperados-relatorios-page" id="cooperadosRelatoriosPage">
            <header class="cooperados-relatorios-hero">
                <div class="cooperados-relatorios-hero-title">
                    <span class="cooperados-relatorios-hero-icon" aria-hidden="true">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    <div>
                        <span class="cooperados-relatorios-kicker">GESTÃO DE COOPERADOS</span>
                        <h1>Relatórios de Cooperados</h1>
                        <p>Indicadores, análises e relatórios gerenciais dos cooperados</p>
                    </div>
                </div>
            </header>

            <section class="cooperados-relatorios-panel cooperados-relatorios-filters" aria-labelledby="cooperadosRelatoriosFiltrosTitulo">
                <div class="cooperados-relatorios-filter-toolbar">
                    <div class="cooperados-relatorios-section-heading">
                        <span class="cooperados-relatorios-kicker">REFINAR VISÃO</span>
                        <h2 id="cooperadosRelatoriosFiltrosTitulo">Filtros de cooperados</h2>
                        <p>Os indicadores e gráficos são recalculados somente ao aplicar os filtros.</p>
                    </div>

                    <button type="button" class="cooperados-relatorios-filter-toggle" data-cr-action="toggle-filters" aria-expanded="false" aria-controls="cooperadosRelatoriosFiltrosPainel">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        <span data-cr-filter-toggle-label>Filtros</span>
                        <span class="cooperados-relatorios-filter-badge" data-cr-filter-count hidden>0</span>
                        <i class="fas fa-chevron-down cooperados-relatorios-filter-chevron" data-cr-filter-chevron aria-hidden="true"></i>
                    </button>
                </div>

                <div class="cooperados-relatorios-filter-collapse" id="cooperadosRelatoriosFiltrosPainel" aria-hidden="true">
                    <div class="cooperados-relatorios-filter-collapse-inner">
                        <form id="form_CooperadosRelatorios" name="form_CooperadosRelatorios" autocomplete="off">
                    <div class="cooperados-relatorios-filter-groups">
                        <section class="cooperados-relatorios-filter-group" aria-labelledby="crGrupoPrincipais">
                            <div class="cooperados-relatorios-filter-group-heading">
                                <span><i class="fas fa-id-card" aria-hidden="true"></i></span>
                                <div>
                                    <h3 id="crGrupoPrincipais">Dados principais</h3>
                                    <p>Identificação e dados cadastrais do cooperado.</p>
                                </div>
                            </div>
                            <div class="cooperados-relatorios-filter-grid">
                                <div class="cooperados-relatorios-field is-wide">
                                    <label for="crNome">Nome</label>
                                    <input type="text" id="crNome" name="nome" maxlength="150" placeholder="Buscar parte do nome">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crCrm">CRM</label>
                                    <input type="text" id="crCrm" name="crm" maxlength="20" placeholder="CRM exato">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crCpf">CPF</label>
                                    <input type="text" id="crCpf" name="cpf" maxlength="14" inputmode="numeric" placeholder="CPF exato">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crRg">RG</label>
                                    <input type="text" id="crRg" name="rg" maxlength="20" placeholder="RG exato">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crSexo">Sexo</label>
                                    <select id="crSexo" name="sexo" class="cooperados-relatorios-searchable" data-placeholder="Todos">
                                        {$optionsSexos}
                                    </select>
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crEstadoCivil">Estado civil</label>
                                    <select id="crEstadoCivil" name="estado_civil" class="cooperados-relatorios-searchable" data-placeholder="Todos">
                                        {$optionsEstadosCivis}
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="cooperados-relatorios-filter-group" aria-labelledby="crGrupoComplementares">
                            <div class="cooperados-relatorios-filter-group-heading">
                                <span><i class="fas fa-clipboard-list" aria-hidden="true"></i></span>
                                <div>
                                    <h3 id="crGrupoComplementares">Dados complementares</h3>
                                    <p>Documentos previdenciários, especialidade e localidade.</p>
                                </div>
                            </div>
                            <div class="cooperados-relatorios-filter-grid">
                                <div class="cooperados-relatorios-field">
                                    <label for="crCnis">CNIS</label>
                                    <input type="text" id="crCnis" name="cnis" maxlength="20" placeholder="CNIS exato">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crInss">INSS</label>
                                    <input type="text" id="crInss" name="inss" maxlength="15" placeholder="Buscar INSS">
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crEspecialidade">Especialidade</label>
                                    <select id="crEspecialidade" name="especialidade_id" class="cooperados-relatorios-searchable" data-placeholder="Todas">
                                        {$optionsEspecialidades}
                                    </select>
                                </div>
                                <div class="cooperados-relatorios-field">
                                    <label for="crCidade">Cidade</label>
                                    <select id="crCidade" name="cidade_id" class="cooperados-relatorios-searchable" data-placeholder="Todas">
                                        {$optionsCidades}
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="cooperados-relatorios-filter-group" aria-labelledby="crGrupoDatas">
                            <div class="cooperados-relatorios-filter-group-heading">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i></span>
                                <div>
                                    <h3 id="crGrupoDatas">Datas</h3>
                                    <p>Períodos cadastrais e vigência do vínculo.</p>
                                </div>
                            </div>
                            <div class="cooperados-relatorios-filter-grid">
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-date-field">
                                    <legend>Data de filiação</legend>
                                    <div class="cooperados-relatorios-date-range">
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crDataInicio" name="data_filiacao_inicio" aria-label="Data inicial de filiação"></span>
                                        <span class="cooperados-relatorios-date-separator">até</span>
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crDataFim" name="data_filiacao_fim" aria-label="Data final de filiação"></span>
                                    </div>
                                </fieldset>
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-date-field">
                                    <legend>Data de desfiliação</legend>
                                    <div class="cooperados-relatorios-date-range">
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crDesfiliacaoInicio" name="dt_desfiliacao_inicio" aria-label="Data inicial de desfiliação"></span>
                                        <span class="cooperados-relatorios-date-separator">até</span>
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crDesfiliacaoFim" name="dt_desfiliacao_fim" aria-label="Data final de desfiliação"></span>
                                    </div>
                                </fieldset>
                                <div class="cooperados-relatorios-field">
                                    <label for="crDataNascimento">Data de nascimento</label>
                                    <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crDataNascimento" name="data_nascimento"></span>
                                </div>
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-date-field">
                                    <legend>Vigentes no período</legend>
                                    <div class="cooperados-relatorios-date-range">
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crVigenteInicio" name="vigente_inicial" aria-label="Início do período de vigência"></span>
                                        <span class="cooperados-relatorios-date-separator">até</span>
                                        <span class="cooperados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" id="crVigenteFim" name="vigente_final" aria-label="Fim do período de vigência"></span>
                                    </div>
                                </fieldset>
                            </div>
                        </section>

                        <section class="cooperados-relatorios-filter-group" aria-labelledby="crGrupoSituacoes">
                            <div class="cooperados-relatorios-filter-group-heading">
                                <span><i class="fas fa-toggle-on" aria-hidden="true"></i></span>
                                <div>
                                    <h3 id="crGrupoSituacoes">Situações e flags</h3>
                                    <p>Condição cadastral e opções tributárias.</p>
                                </div>
                            </div>
                            <div class="cooperados-relatorios-filter-grid is-flags">
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-flag-field">
                                    <legend>Ativo</legend>
                                    <div class="cooperados-relatorios-toggle-group">
                                        <input type="radio" id="crAtivoTodos" name="situacao" value="" checked><label for="crAtivoTodos">Todos</label>
                                        <input type="radio" id="crAtivoSim" name="situacao" value="ATIVO"><label for="crAtivoSim">Sim</label>
                                        <input type="radio" id="crAtivoNao" name="situacao" value="INATIVO"><label for="crAtivoNao">Não</label>
                                    </div>
                                </fieldset>
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-flag-field">
                                    <legend>Recolhe INSS</legend>
                                    <div class="cooperados-relatorios-toggle-group">
                                        <input type="radio" id="crRecolheTodos" name="flg_recolhe_inss" value="" checked><label for="crRecolheTodos">Todos</label>
                                        <input type="radio" id="crRecolheSim" name="flg_recolhe_inss" value="S"><label for="crRecolheSim">Sim</label>
                                        <input type="radio" id="crRecolheNao" name="flg_recolhe_inss" value="N"><label for="crRecolheNao">Não</label>
                                    </div>
                                </fieldset>
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-flag-field">
                                    <legend>Retém IR</legend>
                                    <div class="cooperados-relatorios-toggle-group">
                                        <input type="radio" id="crRetemTodos" name="flg_retem_ir" value="" checked><label for="crRetemTodos">Todos</label>
                                        <input type="radio" id="crRetemSim" name="flg_retem_ir" value="S"><label for="crRetemSim">Sim</label>
                                        <input type="radio" id="crRetemNao" name="flg_retem_ir" value="N"><label for="crRetemNao">Não</label>
                                    </div>
                                </fieldset>
                                <fieldset class="cooperados-relatorios-field cooperados-relatorios-flag-field">
                                    <legend>Declara dependentes</legend>
                                    <div class="cooperados-relatorios-toggle-group">
                                        <input type="radio" id="crDeclaraTodos" name="flg_declara_dep" value="" checked><label for="crDeclaraTodos">Todos</label>
                                        <input type="radio" id="crDeclaraSim" name="flg_declara_dep" value="S"><label for="crDeclaraSim">Sim</label>
                                        <input type="radio" id="crDeclaraNao" name="flg_declara_dep" value="N"><label for="crDeclaraNao">Não</label>
                                    </div>
                                </fieldset>
                            </div>
                        </section>
                    </div>

                    <div class="cooperados-relatorios-filter-actions">
                        <button type="button" class="cooperados-relatorios-btn is-secondary" data-cr-action="limpar">
                            <i class="fas fa-eraser" aria-hidden="true"></i>
                            Limpar
                        </button>
                        <button type="submit" class="cooperados-relatorios-btn is-primary" data-cr-action="aplicar">
                            <i class="fas fa-filter" aria-hidden="true"></i>
                            Aplicar filtros
                        </button>
                    </div>
                        </form>
                    </div>
                </div>
            </section>

            <section class="cooperados-relatorios-kpis" aria-label="Indicadores de cooperados">
                <article class="cooperados-relatorios-kpi is-total">
                    <div class="cooperados-relatorios-kpi-top">
                        <span>Total de Cooperados</span>
                        <i class="fas fa-users" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="total">{$total}</strong>
                    <small>No recorte selecionado</small>
                </article>
                <article class="cooperados-relatorios-kpi is-active">
                    <div class="cooperados-relatorios-kpi-top">
                        <span>Cooperados Ativos</span>
                        <i class="fas fa-user-check" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="ativos">{$ativos}</strong>
                    <small>Cadastros com situação ativa</small>
                </article>
                <article class="cooperados-relatorios-kpi is-inactive">
                    <div class="cooperados-relatorios-kpi-top">
                        <span>Cooperados Inativos</span>
                        <i class="fas fa-user-slash" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="inativos">{$inativos}</strong>
                    <small>Cadastros com situação inativa</small>
                </article>
                <article class="cooperados-relatorios-kpi is-new">
                    <div class="cooperados-relatorios-kpi-top">
                        <span>Novos Cooperados</span>
                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="novos">{$novos}</strong>
                    <small data-cr-periodo>{$periodoNovos}</small>
                </article>
            </section>

            <section class="cooperados-relatorios-analysis" aria-labelledby="cooperadosRelatoriosVisaoTitulo">
                <div class="cooperados-relatorios-section-heading">
                    <span class="cooperados-relatorios-kicker">VISÃO GERENCIAL</span>
                    <h2 id="cooperadosRelatoriosVisaoTitulo">Distribuição e perfil dos cooperados</h2>
                    <p>Leituras rápidas de situação, sexo, especialidades e localidades.</p>
                </div>

                <div class="cooperados-relatorios-chart-grid">
                    <article class="cooperados-relatorios-chart-card">
                        <div class="cooperados-relatorios-chart-heading">
                            <div>
                                <h3>Cooperados por situação</h3>
                                <p>Ativos e inativos no recorte atual</p>
                            </div>
                            <span class="cooperados-relatorios-chart-icon is-green"><i class="fas fa-user-check"></i></span>
                        </div>
                        <div class="cooperados-relatorios-chart" id="crChartSituacao" role="img" aria-label="Gráfico de cooperados por situação"></div>
                    </article>

                    <article class="cooperados-relatorios-chart-card">
                        <div class="cooperados-relatorios-chart-heading">
                            <div>
                                <h3>Cooperados por sexo</h3>
                                <p>Composição cadastral dos cooperados</p>
                            </div>
                            <span class="cooperados-relatorios-chart-icon is-purple"><i class="fas fa-venus-mars"></i></span>
                        </div>
                        <div class="cooperados-relatorios-chart" id="crChartSexo" role="img" aria-label="Gráfico de cooperados por sexo"></div>
                    </article>

                    <article class="cooperados-relatorios-chart-card">
                        <div class="cooperados-relatorios-chart-heading">
                            <div>
                                <h3>Cooperados por especialidade</h3>
                                <p>Distribuição dos cooperados por especialidade</p>
                            </div>
                            <span class="cooperados-relatorios-chart-icon is-blue"><i class="fas fa-stethoscope"></i></span>
                        </div>
                        <div class="cooperados-relatorios-chart is-horizontal" id="crChartEspecialidades" role="img" aria-label="Gráfico de cooperados por especialidade"></div>
                    </article>

                    <article class="cooperados-relatorios-chart-card">
                        <div class="cooperados-relatorios-chart-heading">
                            <div>
                                <h3>Cooperados por cidade</h3>
                                <p>Distribuição dos cooperados por cidade</p>
                            </div>
                            <span class="cooperados-relatorios-chart-icon is-orange"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <div class="cooperados-relatorios-chart is-horizontal" id="crChartCidades" role="img" aria-label="Gráfico de cooperados por cidade"></div>
                    </article>
                </div>
            </section>

            <section class="cooperados-relatorios-reports" aria-labelledby="cooperadosRelatoriosRelatoriosTitulo">
                <div class="cooperados-relatorios-section-heading">
                    <span class="cooperados-relatorios-kicker">CONSULTAS E EXPORTAÇÕES</span>
                    <h2 id="cooperadosRelatoriosRelatoriosTitulo">Relatórios</h2>
                    <p>Selecione o relatório que deseja consultar ou exportar.</p>
                </div>

                <div class="cooperados-relatorios-report-grid">
                    <article class="cooperados-relatorios-report-card">
                        <span class="cooperados-relatorios-report-icon is-users"><i class="fas fa-users"></i></span>
                        <div class="cooperados-relatorios-report-content">
                            <span class="cooperados-relatorios-report-eyebrow">CADASTRO</span>
                            <h3>Cooperados</h3>
                            <p>Dados cadastrais e informações gerais dos cooperados.</p>
                            <button type="button" class="cooperados-relatorios-btn is-report" data-cr-report="cooperados">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                Visualizar relatório
                            </button>
                        </div>
                    </article>

                    <article class="cooperados-relatorios-report-card">
                        <span class="cooperados-relatorios-report-icon is-contacts"><i class="fas fa-address-book"></i></span>
                        <div class="cooperados-relatorios-report-content">
                            <span class="cooperados-relatorios-report-eyebrow">CONTATOS</span>
                            <h3>Contatos Cooperados</h3>
                            <p>Relação de contatos cadastrados para os cooperados.</p>
                            <button type="button" class="cooperados-relatorios-btn is-report" data-cr-report="contatos">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                Visualizar relatório
                            </button>
                        </div>
                    </article>
                </div>
            </section>

            <div class="cooperados-relatorios-footer-status">
                <i class="fas fa-clock" aria-hidden="true"></i>
                Atualizado em <span data-cr-atualizado>{$atualizadoEm}</span>
            </div>

            <div class="cooperados-relatorios-report-modal" data-cr-cooperados-modal aria-hidden="true" hidden>
                <div class="cooperados-relatorios-report-backdrop" data-cr-report-close></div>
                <section class="cooperados-relatorios-report-dialog" role="dialog" aria-modal="true" aria-labelledby="crRelatorioCooperadosTitulo" tabindex="-1">
                    <header class="cooperados-relatorios-report-dialog-header">
                        <div class="cooperados-relatorios-report-dialog-title">
                            <span class="cooperados-relatorios-report-dialog-icon" aria-hidden="true"><i class="fas fa-users"></i></span>
                            <div>
                                <span class="cooperados-relatorios-kicker">RELATÓRIO CADASTRAL</span>
                                <h2 id="crRelatorioCooperadosTitulo">Relatório de Cooperados</h2>
                                <p data-cr-report-total aria-live="polite">0 cooperados encontrados</p>
                            </div>
                        </div>
                        <button type="button" class="cooperados-relatorios-report-close" data-cr-report-close aria-label="Fechar relatório" title="Fechar">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </header>

                    <div class="cooperados-relatorios-report-dialog-body">
                        <section class="cooperados-relatorios-report-filters" aria-labelledby="crRelatorioFiltrosTitulo">
                            <h3 id="crRelatorioFiltrosTitulo"><i class="fas fa-filter" aria-hidden="true"></i> Filtros aplicados</h3>
                            <div class="cooperados-relatorios-report-filter-list" data-cr-report-filters></div>
                        </section>

                        <div class="cooperados-relatorios-report-empty" data-cr-report-empty hidden>
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <strong>Nenhum cooperado encontrado para os filtros selecionados.</strong>
                        </div>

                        <div class="cooperados-relatorios-report-table-wrap" data-cr-report-table-wrap hidden>
                            <table class="cooperados-relatorios-report-table">
                                <thead data-cr-report-head></thead>
                                <tbody data-cr-report-body></tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="cooperados-relatorios-loading" aria-hidden="true">
                <span class="cooperados-relatorios-spinner"></span>
                <span data-cr-loading-text>Atualizando indicadores...</span>
            </div>
            <div class="cooperados-relatorios-toast" role="status" aria-live="polite"></div>

            <script type="application/json" id="cooperadosRelatoriosConfig">{$config}</script>
            <script src="app/lib/include/js/cooperadosRelatorios.js"></script>
        </main>
        HTML;
    }

    private function montarOptions(array $opcoes, string $placeholder): string
    {
        $html = '<option value="">' . $this->h($placeholder) . '</option>';

        foreach ($opcoes as $opcao) {
            $id = $this->h($opcao['id'] ?? '');
            $nome = $this->h($opcao['nome'] ?? '');
            $html .= '<option value="' . $id . '">' . $nome . '</option>';
        }

        return $html;
    }

    private function numero($valor): string
    {
        return number_format((int) $valor, 0, ',', '.');
    }

    private function formatarData(string $valor): string
    {
        return DateTime::createFromFormat('!Y-m-d', $valor)->format('d/m/Y');
    }

    private function h($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function rollback(): void
    {
        if (TTransaction::get()) {
            TTransaction::rollback();
        }
    }
}
