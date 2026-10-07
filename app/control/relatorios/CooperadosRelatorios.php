<?php

class CooperadosRelatorios extends TPage
{
    private static $database = 'databaserede';
    private static $formName = 'form_CooperadosRelatorios';
    private const LIMITE_AGRUPAMENTOS = 8;

    public function __construct($param = null)
    {
        parent::__construct();

        if (($_REQUEST['method'] ?? '') === 'onAtualizarAjax') {
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
            $panel->style = 'border: none; box-shadow: none; background: transparent;';
            $panel->getBody()->class .= ' cooperados-relatorios-panel-body';
            $panel->add($pagina);

            $container = new TVBox();
            $container->style = 'width: 100%;';

            if (empty($param['target_container'])) {
                $container->add(TBreadCrumb::create(['Relatórios', 'Cooperados']));
            }

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

    private function buscarDadosDashboard(array $filtros): array
    {
        [$where, $parametros] = $this->montarFiltrosSql($filtros);
        $periodoNovos = $this->montarPeriodoNovos($filtros);
        $parametrosKpi = array_merge($parametros, $periodoNovos['parametros']);

        $resumo = $this->consultarUmaLinha(
            "SELECT
                COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN c.ativo = 'Sim' THEN 1 ELSE 0 END), 0) AS ativos,
                COALESCE(SUM(CASE WHEN c.ativo = 'Não' THEN 1 ELSE 0 END), 0) AS inativos,
                COALESCE(SUM(CASE WHEN {$periodoNovos['condicao']} THEN 1 ELSE 0 END), 0) AS novos
             FROM cooperados c
             WHERE {$where}",
            $parametrosKpi
        );

        $situacao = $this->consultar(
            "SELECT
                CASE
                    WHEN c.ativo = 'Sim' THEN 'Ativos'
                    WHEN c.ativo = 'Não' THEN 'Inativos'
                    ELSE 'Não informado'
                END AS nome,
                COUNT(*) AS total
             FROM cooperados c
             WHERE {$where}
             GROUP BY nome
             ORDER BY total DESC, nome",
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
            $nome = $codigo === '__NAO_INFORMADO__' ? 'Não informado' : ($mapaSexos[$codigo] ?? $codigo);
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
             ORDER BY total DESC, e.especialidade
             LIMIT " . self::LIMITE_AGRUPAMENTOS,
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
             ORDER BY total DESC, ci.cidade
             LIMIT " . self::LIMITE_AGRUPAMENTOS,
            $parametrosCidade
        );

        return [
            'kpis' => [
                'total' => (int) ($resumo['total'] ?? 0),
                'ativos' => (int) ($resumo['ativos'] ?? 0),
                'inativos' => (int) ($resumo['inativos'] ?? 0),
                'novos' => (int) ($resumo['novos'] ?? 0),
                'periodo_novos' => $periodoNovos['rotulo'],
            ],
            'graficos' => [
                'situacao' => $this->normalizarAgrupamento($situacao),
                'sexo' => $sexo,
                'especialidades' => $this->normalizarAgrupamento($especialidades),
                'cidades' => $this->normalizarAgrupamento($cidades),
            ],
            'atualizado_em' => date('d/m/Y H:i'),
        ];
    }

    private function montarFiltrosSql(array $filtros): array
    {
        $condicoes = ['1 = 1'];
        $parametros = [];

        if ($filtros['situacao'] !== '') {
            $condicoes[] = 'c.ativo = :situacao';
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

        return [implode("\n AND ", $condicoes), $parametros];
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
                $mapa[$valor] = trim((string) ($linha['mascara'] ?? '')) ?: $valor;
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

        if (!in_array($situacao, ['', 'Sim', 'Não'], true)) {
            throw new InvalidArgumentException('A situação informada é inválida.');
        }

        $dataInicio = $this->validarData($this->valorEscalar($entrada, 'data_filiacao_inicio'), 'data inicial');
        $dataFim = $this->validarData($this->valorEscalar($entrada, 'data_filiacao_fim'), 'data final');

        if ($dataInicio !== null && $dataFim !== null && $dataInicio > $dataFim) {
            throw new InvalidArgumentException('A data inicial não pode ser posterior à data final.');
        }

        $sexo = $this->valorEscalar($entrada, 'sexo');

        if (mb_strlen($sexo) > 50) {
            throw new InvalidArgumentException('O filtro de sexo é inválido.');
        }

        return [
            'situacao' => $situacao,
            'data_filiacao_inicio' => $dataInicio,
            'data_filiacao_fim' => $dataFim,
            'sexo' => $sexo,
            'especialidade_id' => $this->validarId($this->valorEscalar($entrada, 'especialidade_id'), 'especialidade'),
            'cidade_id' => $this->validarId($this->valorEscalar($entrada, 'cidade_id'), 'cidade'),
        ];
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

    private function normalizarAgrupamento(array $linhas): array
    {
        return array_map(
            static function (array $linha): array {
                return [
                    'nome' => (string) ($linha['nome'] ?? 'Não informado'),
                    'total' => (int) ($linha['total'] ?? 0),
                ];
            },
            $linhas
        );
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
                <div class="cooperados-relatorios-hero-actions">
                    <button type="button" class="cooperados-relatorios-btn is-secondary" data-cr-action="limpar">
                        <i class="fas fa-eraser" aria-hidden="true"></i>
                        Limpar filtros
                    </button>
                    <button type="button" class="cooperados-relatorios-btn is-primary" data-cr-action="atualizar">
                        <i class="fas fa-sync-alt" aria-hidden="true"></i>
                        Atualizar indicadores
                    </button>
                </div>
            </header>

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

            <section class="cooperados-relatorios-panel cooperados-relatorios-filters" aria-labelledby="cooperadosRelatoriosFiltrosTitulo">
                <div class="cooperados-relatorios-section-heading">
                    <span class="cooperados-relatorios-kicker">REFINAR VISÃO</span>
                    <h2 id="cooperadosRelatoriosFiltrosTitulo"><i class="fas fa-filter" aria-hidden="true"></i> Filtros</h2>
                    <p>Os indicadores e gráficos são recalculados conforme os filtros selecionados.</p>
                </div>

                <form id="form_CooperadosRelatorios" name="form_CooperadosRelatorios">
                    <div class="cooperados-relatorios-filter-grid">
                        <div class="cooperados-relatorios-field">
                            <label for="crSituacao">Situação</label>
                            <select id="crSituacao" name="situacao">
                                <option value="">Todos</option>
                                <option value="Sim">Ativo</option>
                                <option value="Não">Inativo</option>
                            </select>
                        </div>

                        <fieldset class="cooperados-relatorios-field cooperados-relatorios-date-field">
                            <legend>Período de filiação</legend>
                            <div class="cooperados-relatorios-date-range">
                                <input type="date" id="crDataInicio" name="data_filiacao_inicio" aria-label="Data inicial de filiação">
                                <span>até</span>
                                <input type="date" id="crDataFim" name="data_filiacao_fim" aria-label="Data final de filiação">
                            </div>
                        </fieldset>

                        <div class="cooperados-relatorios-field">
                            <label for="crSexo">Sexo</label>
                            <select id="crSexo" name="sexo">
                                {$optionsSexos}
                            </select>
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
                                <p>Até 8 especialidades com mais cooperados</p>
                            </div>
                            <span class="cooperados-relatorios-chart-icon is-blue"><i class="fas fa-stethoscope"></i></span>
                        </div>
                        <div class="cooperados-relatorios-chart is-horizontal" id="crChartEspecialidades" role="img" aria-label="Gráfico de cooperados por especialidade"></div>
                    </article>

                    <article class="cooperados-relatorios-chart-card">
                        <div class="cooperados-relatorios-chart-heading">
                            <div>
                                <h3>Cooperados por cidade</h3>
                                <p>Até 8 cidades com mais cooperados</p>
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

            <div class="cooperados-relatorios-loading" aria-hidden="true">
                <span class="cooperados-relatorios-spinner"></span>
                <span>Atualizando indicadores...</span>
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
