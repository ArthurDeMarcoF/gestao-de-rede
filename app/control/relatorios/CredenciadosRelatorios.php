<?php

class CredenciadosRelatorios extends TPage
{
    private static $database = 'databaserede';
    private static $formName = 'form_CredenciadosRelatorios';

    public function __construct($param = null)
    {
        parent::__construct();

        if (in_array($_REQUEST['method'] ?? '', [
            'onAtualizarAjax',
            'onRelatorioCredenciadosAjax',
            'onRelatorioContatosAjax',
            'onExportarCredenciadosExcel',
            'onExportarContatosExcel',
        ], true)) {
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
            $panel->class .= ' credenciados-relatorios-host-panel';
            $panel->style = 'border: none; box-shadow: none; background: transparent;';
            $panel->getBody()->class .= ' credenciados-relatorios-panel-body';
            $panel->add($pagina);

            $container = new TVBox();
            $container->style = 'width: 100%;';
            $container->class = 'credenciados-relatorios-host-container';

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

    public function onRelatorioCredenciadosAjax($param = null)
    {
        $this->responderRelatorioAjax('credenciados');
    }

    public function onRelatorioContatosAjax($param = null)
    {
        $this->responderRelatorioAjax($this->valorEscalar($_POST, 'relatorio_contatos'));
    }

    public function onExportarCredenciadosExcel($param = null)
    {
        $this->exportarRelatorioExcel('credenciados');
    }

    public function onExportarContatosExcel($param = null)
    {
        $this->exportarRelatorioExcel($this->valorEscalar($_POST, 'relatorio_contatos'));
    }

    private function responderRelatorioAjax(string $relatorio): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: private, no-store');
        $configuracao = null;

        try {
            $configuracao = $this->configuracaoRelatorio($relatorio);
            $filtros = $this->prepararFiltros($_POST);

            TTransaction::open(self::$database);
            $dados = $relatorio === 'credenciados'
                ? $this->buscarDadosRelatorioCredenciados($filtros)
                : $this->buscarDadosRelatorioContatos($filtros, $relatorio);
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
                : 'Não foi possível carregar o ' . mb_strtolower($configuracao['titulo'] ?? 'relatório', 'UTF-8') . '. Tente novamente.';

            echo json_encode(
                ['sucesso' => false, 'erro' => $mensagem],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            );
            exit;
        }
    }

    private function exportarRelatorioExcel(string $relatorio): void
    {
        $arquivoTemporario = null;

        try {
            $configuracao = $this->configuracaoRelatorio($relatorio);
            $filtros = $this->prepararFiltros($_POST);

            TTransaction::open(self::$database);
            $dados = $relatorio === 'credenciados'
                ? $this->buscarDadosRelatorioCredenciados($filtros)
                : $this->buscarDadosRelatorioContatos($filtros, $relatorio);
            TTransaction::close();

            $arquivoTemporario = tempnam(sys_get_temp_dir(), $configuracao['prefixo_temporario']);

            if ($arquivoTemporario === false) {
                throw new RuntimeException('Não foi possível preparar o arquivo Excel.');
            }

            $this->gerarExcelRelatorio($dados, $arquivoTemporario, $configuracao);

            if (!is_file($arquivoTemporario) || filesize($arquivoTemporario) === 0) {
                throw new RuntimeException('O arquivo Excel não pôde ser gerado.');
            }

            $nomeArquivo = $configuracao['prefixo_arquivo'] . date('Y-m-d') . '.xlsx';

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"; filename*=UTF-8\'\'' . rawurlencode($nomeArquivo));
            header('Content-Length: ' . filesize($arquivoTemporario));
            header('Cache-Control: private, no-store, no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('X-Content-Type-Options: nosniff');

            readfile($arquivoTemporario);
            unlink($arquivoTemporario);
            $arquivoTemporario = null;
            exit;
        } catch (Throwable $e) {
            $this->rollback();

            if ($arquivoTemporario !== null && is_file($arquivoTemporario)) {
                unlink($arquivoTemporario);
            }

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            http_response_code(500);
            header('Content-Type: application/json; charset=UTF-8');
            header('Cache-Control: private, no-store');

            echo json_encode(
                [
                    'sucesso' => false,
                    'erro' => 'Não foi possível gerar o arquivo Excel. Tente novamente.',
                ],
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
             FROM credenciados c
             WHERE {$where}",
            $parametrosKpi
        );

        // A expressão precisa ser repetida no GROUP BY: o alias "nome" conflita
        // com credenciados.nome e faria o MySQL criar um grupo para cada credenciado.
        $situacao = $this->consultar(
            "SELECT
                CASE {$situacaoSql}
                    WHEN 'ATIVO' THEN 'Ativos'
                    WHEN 'INATIVO' THEN 'Inativos'
                    ELSE 'Não informado'
                END AS nome,
                COUNT(*) AS total
             FROM credenciados c
             WHERE {$where}
             GROUP BY {$situacaoSql}
             ORDER BY CASE {$situacaoSql}
                        WHEN 'ATIVO' THEN 1
                        WHEN 'INATIVO' THEN 2
                        ELSE 3
                      END",
            $parametros
        );

        $tiposBruto = $this->consultar(
            "SELECT COALESCE(NULLIF(TRIM(c.dm_tipo), ''), '__NAO_INFORMADO__') AS codigo, COUNT(*) AS total
             FROM credenciados c WHERE {$where}
             GROUP BY COALESCE(NULLIF(TRIM(c.dm_tipo), ''), '__NAO_INFORMADO__')
             ORDER BY total DESC, codigo",
            $parametros
        );
        $mapaTipos = $this->mapearOpcoes($this->buscarOpcoesDominioCol('dm_tipo'));
        $totaisTipo = [];
        foreach ($tiposBruto as $linha) {
            $codigo = (string) ($linha['codigo'] ?? '');
            $nome = $codigo === '__NAO_INFORMADO__'
                ? 'Não informado'
                : $this->normalizarRotuloGrafico($mapaTipos[$codigo] ?? $codigo, $codigo);
            $totaisTipo[$nome] = ($totaisTipo[$nome] ?? 0) + (int) $linha['total'];
        }
        arsort($totaisTipo);
        $tipos = [];
        foreach ($totaisTipo as $nome => $totalTipo) {
            $tipos[] = ['nome' => $nome, 'total' => $totalTipo];
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
             FROM credenciados c
             INNER JOIN credenciados_especialidades ce ON ce.credenciados_id = c.id
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
             FROM credenciados c
             INNER JOIN credenciados_enderecos ec ON ec.credenciados_id = c.id
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
                'tipo' => $tipos,
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
    private function buscarDadosRelatorioCredenciados(array $filtros): array
    {
        [$where, $parametros] = $this->montarFiltrosSql($filtros);
        $situacaoSql = $this->expressaoSituacaoSql();
        $linhas = $this->consultar(
            "SELECT c.id, c.nome, c.cnpj, c.cnes, c.inscricao_estadual, c.codigo_prestador,
                    c.data_inicio, c.data_contrato, c.dt_descredenciamento, c.ativo,
                    c.enquadramento_tributario, c.dm_tipo, c.dm_reaj_contr,
                    c.nr_dias_aviso_reaj, c.flg_dias_padrao, {$situacaoSql} AS situacao_codigo
             FROM credenciados c WHERE {$where} ORDER BY c.nome ASC, c.id ASC",
            $parametros
        );
        $especialidades = [];
        $cidades = [];
        if ($linhas) {
            $especialidades = $this->agruparRelacionamentosRelatorio($this->consultar(
                "SELECT c.id AS credenciado_id, e.especialidade AS nome
                 FROM credenciados c INNER JOIN credenciados_especialidades ce ON ce.credenciados_id = c.id
                 INNER JOIN especialidades e ON e.id = ce.especialidades_id
                 WHERE {$where} AND NULLIF(TRIM(e.especialidade), '') IS NOT NULL ORDER BY c.id, e.especialidade",
                $parametros
            ));
            $cidades = $this->agruparRelacionamentosRelatorio($this->consultar(
                "SELECT c.id AS credenciado_id, ci.cidade AS nome
                 FROM credenciados c INNER JOIN credenciados_enderecos ec ON ec.credenciados_id = c.id
                 INNER JOIN cidades ci ON ci.id = ec.cidades_id
                 WHERE {$where} AND NULLIF(TRIM(ci.cidade), '') IS NOT NULL ORDER BY c.id, ci.cidade",
                $parametros
            ));
        }
        $tipos = $this->mapearOpcoes($this->buscarOpcoesDominioCol('dm_tipo'));
        $tributos = $this->mapearOpcoes($this->buscarOpcoesEnquadramento());
        $reajustes = $this->mapearOpcoes($this->buscarOpcoesDominioCol('dm_reaj_contr'));
        $diasPadrao = $this->mapearOpcoes($this->buscarOpcoesDominioCol('flg_dias_padrao'));
        $registros = [];
        foreach ($linhas as $linha) {
            $id = (int) ($linha['id'] ?? 0);
            $situacao = $linha['situacao_codigo'] === 'ATIVO' ? 'Ativo' : ($linha['situacao_codigo'] === 'INATIVO' ? 'Inativo' : 'Não informado');
            $registros[] = [
                'nome' => $this->valorRelatorio($linha['nome'] ?? null),
                'codigo_prestador' => $this->valorRelatorio($linha['codigo_prestador'] ?? null),
                'cnpj' => $this->formatarCnpjRelatorio($linha['cnpj'] ?? null),
                'cnes' => $this->valorRelatorio($linha['cnes'] ?? null),
                'inscricao_estadual' => $this->valorRelatorio($linha['inscricao_estadual'] ?? null),
                'situacao' => $situacao,
                'dm_tipo' => $this->formatarDominioRelatorio($linha['dm_tipo'] ?? null, $tipos),
                'enquadramento_tributario' => $this->formatarDominioRelatorio($linha['enquadramento_tributario'] ?? null, $tributos),
                'especialidades' => $this->valorRelatorio($especialidades[$id] ?? null),
                'cidades' => $this->valorRelatorio($cidades[$id] ?? null),
                'data_inicio' => $this->formatarDataRelatorio($linha['data_inicio'] ?? null),
                'data_contrato' => $this->formatarDataRelatorio($linha['data_contrato'] ?? null),
                'dt_descredenciamento' => $this->formatarDataRelatorio($linha['dt_descredenciamento'] ?? null),
                'dm_reaj_contr' => $this->formatarDominioRelatorio($linha['dm_reaj_contr'] ?? null, $reajustes),
                'nr_dias_aviso_reaj' => $this->valorRelatorio($linha['nr_dias_aviso_reaj'] ?? null),
                'flg_dias_padrao' => $this->formatarDominioRelatorio($linha['flg_dias_padrao'] ?? null, $diasPadrao),
            ];
        }
        return [
            'total' => count($registros),
            'filtros_aplicados' => $this->descreverFiltrosAplicados($filtros),
            'colunas' => [
                ['chave' => 'nome', 'rotulo' => 'Nome'],
                ['chave' => 'codigo_prestador', 'rotulo' => 'Código do prestador'],
                ['chave' => 'cnpj', 'rotulo' => 'CNPJ'],
                ['chave' => 'cnes', 'rotulo' => 'CNES'],
                ['chave' => 'inscricao_estadual', 'rotulo' => 'Inscrição estadual'],
                ['chave' => 'situacao', 'rotulo' => 'Situação'],
                ['chave' => 'dm_tipo', 'rotulo' => 'Tipo'],
                ['chave' => 'enquadramento_tributario', 'rotulo' => 'Enquadramento tributário'],
                ['chave' => 'especialidades', 'rotulo' => 'Especialidades'],
                ['chave' => 'cidades', 'rotulo' => 'Cidade(s)'],
                ['chave' => 'data_inicio', 'rotulo' => 'Credenciamento'],
                ['chave' => 'data_contrato', 'rotulo' => 'Data do contrato'],
                ['chave' => 'dt_descredenciamento', 'rotulo' => 'Descredenciamento'],
                ['chave' => 'dm_reaj_contr', 'rotulo' => 'Reajuste contratual'],
                ['chave' => 'nr_dias_aviso_reaj', 'rotulo' => 'Dias de aviso'],
                ['chave' => 'flg_dias_padrao', 'rotulo' => 'Dias padrão'],
            ],
            'registros' => $registros,
        ];
    }
    private function buscarDadosRelatorioContatos(array $filtros, string $relatorio): array
    {
        $configuracao = $this->configuracaoRelatorio($relatorio);
        [$where, $parametros] = $this->montarFiltrosSql($filtros);
        $idsTiposContato = implode(',', array_map('intval', $configuracao['tipos_contatos']));

        $linhas = $this->consultar(
            "SELECT
                c.nome,
                tc.tipo_contato,
                TRIM(cc.contato) AS contato
             FROM credenciados c
             INNER JOIN credenciados_contatos cc ON cc.credenciados_id = c.id
             INNER JOIN tipos_contatos tc ON tc.id = cc.tipos_contatos_id
             WHERE {$where}
               AND cc.tipos_contatos_id IN ({$idsTiposContato})
               AND cc.contato IS NOT NULL
               AND TRIM(cc.contato) <> ''
             ORDER BY c.nome ASC, tc.tipo_contato ASC, cc.contato ASC",
            $parametros
        );

        $registros = [];

        foreach ($linhas as $linha) {
            $registros[] = [
                'nome' => $this->valorRelatorio($linha['nome'] ?? null),
                'tipo_contato' => $this->valorRelatorio($linha['tipo_contato'] ?? null),
                'contato' => trim((string) ($linha['contato'] ?? '')),
            ];
        }

        return [
            'total' => count($registros),
            'filtros_aplicados' => $this->descreverFiltrosAplicados($filtros),
            'colunas' => [
                ['chave' => 'nome', 'rotulo' => 'Nome do credenciado'],
                ['chave' => 'tipo_contato', 'rotulo' => 'Tipo de contato'],
                ['chave' => 'contato', 'rotulo' => $configuracao['rotulo_contato']],
            ],
            'registros' => $registros,
        ];
    }

    private function configuracaoRelatorio(string $relatorio): array
    {
        $configuracoes = [
            'credenciados' => [
                'titulo' => 'Relatório de Credenciados',
                'aba' => 'Credenciados',
                'rotulo_total' => 'Total de credenciados',
                'prefixo_temporario' => 'relatorio_credenciados_',
                'prefixo_arquivo' => 'relatorio_credenciados_',
                'assunto' => 'Relatório cadastral de credenciados',
            ],
            'telefones' => [
                'titulo' => 'Relatório de Telefones',
                'aba' => 'Telefones',
                'rotulo_total' => 'Total de telefones',
                'rotulo_contato' => 'Telefone',
                'tipos_contatos' => [1, 3, 4, 5, 9],
                'prefixo_temporario' => 'relatorio_telefones_',
                'prefixo_arquivo' => 'relatorio_telefones_',
                'assunto' => 'Relatório de telefones dos credenciados',
            ],
            'emails' => [
                'titulo' => 'Relatório de E-mails',
                'aba' => 'E-mails',
                'rotulo_total' => 'Total de e-mails',
                'rotulo_contato' => 'E-mail',
                'tipos_contatos' => [2, 6, 8, 10],
                'prefixo_temporario' => 'relatorio_emails_',
                'prefixo_arquivo' => 'relatorio_emails_',
                'assunto' => 'Relatório de e-mails dos credenciados',
            ],
        ];

        if (!isset($configuracoes[$relatorio])) {
            throw new InvalidArgumentException('O tipo de relatório informado é inválido.');
        }

        return $configuracoes[$relatorio];
    }

    private function gerarExcelRelatorio(array $dados, string $caminhoArquivo, array $configuracao): void
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            throw new RuntimeException('A biblioteca de geração de planilhas não está disponível.');
        }

        $colunas = is_array($dados['colunas'] ?? null) ? $dados['colunas'] : [];
        $registros = is_array($dados['registros'] ?? null) ? $dados['registros'] : [];

        if (!$colunas) {
            throw new RuntimeException('As colunas do relatório não estão disponíveis.');
        }

        $planilha = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        try {
            $aba = $planilha->getActiveSheet();
            $aba->setTitle($configuracao['aba']);
            $aba->setShowGridlines(false);
            $planilha->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
            $aba->getDefaultRowDimension()->setRowHeight(20);

            $ultimaColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($colunas));
            $corPrimaria = '008F57';
            $corPrimariaEscura = '006F45';
            $corPrimariaSuave = 'E7F7EF';
            $corTexto = '172333';
            $corTextoSuave = '697789';
            $corBorda = 'DFE6EC';
            $corLinhaAlternada = 'F8FAFB';

            $aba->mergeCells("A1:{$ultimaColuna}1");
            $aba->setCellValueExplicit('A1', $configuracao['titulo'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $aba->getRowDimension(1)->setRowHeight(28);
            $aba->getStyle("A1:{$ultimaColuna}1")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => $corPrimariaEscura],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['rgb' => $corPrimaria],
                    ],
                ],
            ]);

            $total = (int) ($dados['total'] ?? count($registros));
            $aba->mergeCells("A2:{$ultimaColuna}2");
            $aba->setCellValueExplicit(
                'A2',
                $configuracao['rotulo_total'] . ': ' . number_format($total, 0, ',', '.'),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $aba->getStyle("A2:{$ultimaColuna}2")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $corTexto]],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            $aba->mergeCells("A4:{$ultimaColuna}4");
            $aba->setCellValueExplicit('A4', 'Filtros aplicados', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $aba->getStyle("A4:{$ultimaColuna}4")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $corPrimariaEscura]],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $corPrimariaSuave],
                ],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            $filtrosAplicados = [];

            foreach ((array) ($dados['filtros_aplicados'] ?? []) as $filtro) {
                $rotulo = trim((string) ($filtro['rotulo'] ?? ''));
                $valor = trim((string) ($filtro['valor'] ?? ''));

                if ($rotulo !== '' && $valor !== '') {
                    $filtrosAplicados[] = $rotulo . ': ' . $valor;
                }
            }

            if (!$filtrosAplicados) {
                $filtrosAplicados[] = 'Nenhum filtro aplicado';
            }

            $linhaFiltro = 5;

            foreach ($filtrosAplicados as $filtroAplicado) {
                $aba->mergeCells("A{$linhaFiltro}:{$ultimaColuna}{$linhaFiltro}");
                $aba->setCellValueExplicit(
                    "A{$linhaFiltro}",
                    $filtroAplicado,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                $aba->getStyle("A{$linhaFiltro}:{$ultimaColuna}{$linhaFiltro}")->applyFromArray([
                    'font' => ['color' => ['rgb' => $corTextoSuave]],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                $linhaFiltro++;
            }

            $linhaCabecalho = $linhaFiltro + 1;

            foreach ($colunas as $indice => $coluna) {
                $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indice + 1);
                $aba->setCellValueExplicit(
                    $letraColuna . $linhaCabecalho,
                    (string) ($coluna['rotulo'] ?? $coluna['chave'] ?? ''),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
            }

            $aba->getRowDimension($linhaCabecalho)->setRowHeight(32);
            $aba->getStyle("A{$linhaCabecalho}:{$ultimaColuna}{$linhaCabecalho}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $corPrimaria],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['rgb' => $corPrimariaEscura],
                    ],
                ],
            ]);

            $chavesTextoObrigatorio = ['cnpj', 'cnes', 'inscricao_estadual', 'codigo_prestador', 'contato'];
            $chavesData = ['data_inicio', 'data_contrato', 'dt_descredenciamento'];
            $linhaDadosInicial = $linhaCabecalho + 1;

            foreach ($registros as $indiceRegistro => $registro) {
                $linha = $linhaDadosInicial + $indiceRegistro;

                foreach ($colunas as $indiceColuna => $coluna) {
                    $chave = (string) ($coluna['chave'] ?? '');
                    $valor = trim((string) ($registro[$chave] ?? '-'));
                    $valor = $valor !== '' ? $valor : '-';
                    $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indiceColuna + 1);
                    $celula = $letraColuna . $linha;

                    if (in_array($chave, $chavesData, true) && $valor !== '-') {
                        $data = \DateTimeImmutable::createFromFormat('!d/m/Y', $valor);

                        if ($data !== false) {
                            $aba->setCellValueExplicit(
                                $celula,
                                \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($data),
                                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC
                            );
                            $aba->getStyle($celula)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                            continue;
                        }
                    }

                    if ($chave === 'nr_dias_aviso_reaj' && $valor !== '-' && ctype_digit($valor)) {
                        $aba->setCellValueExplicit($celula, (int) $valor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        continue;
                    }

                    $aba->setCellValueExplicit($celula, $valor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                    if (in_array($chave, $chavesTextoObrigatorio, true)) {
                        $aba->getStyle($celula)->getNumberFormat()->setFormatCode('@');
                    }
                }

                $aba->getStyle("A{$linha}:{$ultimaColuna}{$linha}")->getBorders()->getBottom()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->getColor()->setRGB($corBorda);

                if ($indiceRegistro % 2 === 1) {
                    $aba->getStyle("A{$linha}:{$ultimaColuna}{$linha}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($corLinhaAlternada);
                }
            }

            $ultimaLinhaDados = max($linhaCabecalho, $linhaCabecalho + count($registros));

            if ($registros) {
                $aba->getStyle("A{$linhaDadosInicial}:{$ultimaColuna}{$ultimaLinhaDados}")->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            }

            $larguras = [
                'nome' => 44,
                'codigo_prestador' => 23,
                'cnpj' => 23,
                'cnes' => 18,
                'inscricao_estadual' => 23,
                'situacao' => 18,
                'dm_tipo' => 26,
                'enquadramento_tributario' => 30,
                'especialidades' => 44,
                'cidades' => 32,
                'data_inicio' => 20,
                'data_contrato' => 20,
                'dt_descredenciamento' => 22,
                'dm_reaj_contr' => 28,
                'nr_dias_aviso_reaj' => 20,
                'flg_dias_padrao' => 18,
                'tipo_contato' => 27,
                'contato' => 42,
            ];

            foreach ($colunas as $indice => $coluna) {
                $letraColuna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indice + 1);
                $chave = (string) ($coluna['chave'] ?? '');
                $aba->getColumnDimension($letraColuna)->setWidth($larguras[$chave] ?? 18);

                if (in_array($chave, ['nome', 'especialidades', 'cidades', 'enquadramento_tributario', 'contato'], true) && $registros) {
                    $aba->getStyle("{$letraColuna}{$linhaDadosInicial}:{$letraColuna}{$ultimaLinhaDados}")
                        ->getAlignment()->setWrapText(true);
                }
            }

            $aba->freezePane('A' . ($linhaCabecalho + 1));
            $aba->setAutoFilter("A{$linhaCabecalho}:{$ultimaColuna}{$ultimaLinhaDados}");
            $aba->setSelectedCell('A' . $linhaDadosInicial);
            $aba->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setFitToWidth(1)
                ->setFitToHeight(0);
            $aba->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($linhaCabecalho, $linhaCabecalho);
            $aba->getPageMargins()->setTop(0.4)->setRight(0.3)->setBottom(0.4)->setLeft(0.3);
            $planilha->getProperties()
                ->setCreator('Gestão de Rede')
                ->setTitle($configuracao['titulo'])
                ->setSubject($configuracao['assunto']);

            $escritor = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($planilha);
            $escritor->save($caminhoArquivo);
        } finally {
            $planilha->disconnectWorksheets();
        }
    }

    private function montarFiltrosSql(array $filtros): array
    {
        $condicoes = ['1 = 1'];
        $parametros = [];
        if ($filtros['nome'] !== '') {
            $condicoes[] = 'c.nome LIKE :nome';
            $parametros['nome'] = '%' . $filtros['nome'] . '%';
        }
        foreach (['cnpj','cnes','inscricao_estadual','codigo_prestador'] as $campo) {
            if ($filtros[$campo] !== '') {
                $condicoes[] = "c.{$campo} LIKE :{$campo}";
                $parametros[$campo] = '%' . $filtros[$campo] . '%';
            }
        }
        if ($filtros['situacao'] !== '') {
            $condicoes[] = $this->expressaoSituacaoSql() . ' = :situacao';
            $parametros['situacao'] = $filtros['situacao'];
        }
        foreach (['dm_tipo','enquadramento_tributario','dm_reaj_contr','flg_dias_padrao'] as $campo) {
            if ($filtros[$campo] !== '') {
                $condicoes[] = "c.{$campo} = :{$campo}";
                $parametros[$campo] = $filtros[$campo];
            }
        }
        foreach ([
            'data_inicio_inicio' => ['data_inicio','>='],
            'data_inicio_fim' => ['data_inicio','<='],
            'data_contrato_inicio' => ['data_contrato','>='],
            'data_contrato_fim' => ['data_contrato','<='],
            'dt_descredenciamento_inicio' => ['dt_descredenciamento','>='],
            'dt_descredenciamento_fim' => ['dt_descredenciamento','<='],
        ] as $chave => $definicao) {
            if ($filtros[$chave] !== null) {
                $condicoes[] = "c.{$definicao[0]} {$definicao[1]} :{$chave}";
                $parametros[$chave] = $filtros[$chave];
            }
        }
        if ($filtros['especialidade_id'] !== null) {
            $condicoes[] = 'EXISTS (SELECT 1 FROM credenciados_especialidades cef WHERE cef.credenciados_id = c.id AND cef.especialidades_id = :especialidade_id)';
            $parametros['especialidade_id'] = $filtros['especialidade_id'];
        }
        if ($filtros['cidade_id'] !== null) {
            $condicoes[] = 'EXISTS (SELECT 1 FROM credenciados_enderecos ecf WHERE ecf.credenciados_id = c.id AND ecf.cidades_id = :cidade_id)';
            $parametros['cidade_id'] = $filtros['cidade_id'];
        }
        if ($filtros['vigente_final'] !== null) {
            $condicoes[] = 'c.data_inicio <= :vigente_final';
            $parametros['vigente_final'] = $filtros['vigente_final'];
        }
        if ($filtros['vigente_inicial'] !== null) {
            $condicoes[] = '(c.dt_descredenciamento IS NULL OR c.dt_descredenciamento >= :vigente_inicial)';
            $parametros['vigente_inicial'] = $filtros['vigente_inicial'];
        }
        return [implode("\n AND ", $condicoes), $parametros];
    }

    /**
     * Credenciado ativo = S, inativo = N; demais valores tratados como não informados.
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
        $condicoes = ['c.data_inicio IS NOT NULL'];
        $parametros = [];
        if ($filtros['data_inicio_inicio'] !== null) {
            $condicoes[] = 'c.data_inicio >= :novos_data_inicio';
            $parametros['novos_data_inicio'] = $filtros['data_inicio_inicio'];
        }
        if ($filtros['data_inicio_fim'] !== null) {
            $condicoes[] = 'c.data_inicio <= :novos_data_fim';
            $parametros['novos_data_fim'] = $filtros['data_inicio_fim'];
        } else {
            $condicoes[] = 'c.data_inicio <= CURDATE()';
        }
        if ($filtros['data_inicio_inicio'] === null && $filtros['data_inicio_fim'] === null) {
            $condicoes[] = 'c.data_inicio >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)';
            $rotulo = 'Últimos 12 meses';
        } else {
            $inicio = $filtros['data_inicio_inicio'] ? $this->formatarData($filtros['data_inicio_inicio']) : 'início dos registros';
            $fim = $filtros['data_inicio_fim'] ? $this->formatarData($filtros['data_inicio_fim']) : 'hoje';
            $rotulo = $inicio . ' a ' . $fim;
        }
        return ['condicao' => implode(' AND ', $condicoes), 'parametros' => $parametros, 'rotulo' => $rotulo];
    }
    private function buscarOpcoesFiltros(): array
    {
        return [
            'tipos' => $this->buscarOpcoesDominioCol('dm_tipo'),
            'enquadramentos' => $this->buscarOpcoesEnquadramento(),
            'reajustes' => $this->buscarOpcoesDominioCol('dm_reaj_contr'),
            'dias_padrao' => $this->buscarOpcoesDominioCol('flg_dias_padrao'),
            'especialidades' => $this->consultar(
                "SELECT id, especialidade AS nome FROM especialidades WHERE NULLIF(TRIM(especialidade), '') IS NOT NULL ORDER BY especialidade"
            ),
            'cidades' => $this->consultar(
                "SELECT DISTINCT ci.id, ci.cidade AS nome FROM cidades ci INNER JOIN credenciados_enderecos ec ON ec.cidades_id = ci.id WHERE NULLIF(TRIM(ci.cidade), '') IS NOT NULL ORDER BY ci.cidade"
            ),
        ];
    }

    private function buscarOpcoesEnquadramento(): array
    {
        $linhas = $this->consultar(
            "SELECT valor AS id, mascara AS nome
             FROM v_dominio_valor_col
             WHERE objeto = :objeto
               AND atributo = :atributo
             ORDER BY sequencia, mascara",
            ['objeto' => 'credenciados', 'atributo' => 'enquadramento_tributario']
        );

        // O cadastro usa o domínio global dm_enquadr_trib. Mantemos esse
        // caminho como fallback para bases antigas sem o relacionamento por coluna.
        if (!$linhas) {
            $linhas = $this->consultar(
                "SELECT valor AS id, mascara AS nome FROM v_dominio_valor WHERE codigo = :codigo ORDER BY sequencia, mascara",
                ['codigo' => 'dm_enquadr_trib']
            );
        }
        $opcoes = [];
        foreach ($linhas as $linha) {
            $id = trim((string) ($linha['id'] ?? ''));
            if ($id !== '') {
                $opcoes[] = ['id' => $id, 'nome' => $this->normalizarRotuloGrafico($linha['nome'] ?? '', $id)];
            }
        }
        return $opcoes;
    }
    private function buscarOpcoesDominioCol(string $atributo): array
    {
        $linhas = $this->consultar(
            "SELECT valor AS id, mascara AS nome
             FROM v_dominio_valor_col
             WHERE objeto = :objeto
               AND atributo = :atributo
             ORDER BY sequencia, mascara",
            ['objeto' => 'credenciados', 'atributo' => $atributo]
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
        $datas = [];
        foreach ([
            'data_inicio_inicio' => 'data inicial do credenciamento',
            'data_inicio_fim' => 'data final do credenciamento',
            'data_contrato_inicio' => 'data inicial do contrato',
            'data_contrato_fim' => 'data final do contrato',
            'dt_descredenciamento_inicio' => 'data inicial do descredenciamento',
            'dt_descredenciamento_fim' => 'data final do descredenciamento',
            'vigente_inicial' => 'início da vigência',
            'vigente_final' => 'fim da vigência',
        ] as $campo => $rotulo) {
            $datas[$campo] = $this->validarData($this->valorEscalar($entrada, $campo), $rotulo);
        }
        $this->validarIntervalo($datas['data_inicio_inicio'], $datas['data_inicio_fim'], 'credenciamento');
        $this->validarIntervalo($datas['data_contrato_inicio'], $datas['data_contrato_fim'], 'contrato');
        $this->validarIntervalo($datas['dt_descredenciamento_inicio'], $datas['dt_descredenciamento_fim'], 'descredenciamento');
        $this->validarIntervalo($datas['vigente_inicial'], $datas['vigente_final'], 'vigência');
        $resultados = [
            'nome' => $this->validarTexto($entrada, 'nome', 100, 'nome'),
            'cnpj' => $this->validarTexto($entrada, 'cnpj', 18, 'CNPJ'),
            'cnes' => $this->validarTexto($entrada, 'cnes', 15, 'CNES'),
            'inscricao_estadual' => $this->validarTexto($entrada, 'inscricao_estadual', 15, 'inscrição estadual'),
            'codigo_prestador' => $this->validarTexto($entrada, 'codigo_prestador', 15, 'código do prestador'),
            'situacao' => $situacao,
            'dm_tipo' => $this->validarTexto($entrada, 'dm_tipo', 50, 'tipo'),
            'enquadramento_tributario' => $this->validarTexto($entrada, 'enquadramento_tributario', 50, 'enquadramento tributário'),
            'dm_reaj_contr' => $this->validarTexto($entrada, 'dm_reaj_contr', 50, 'reajuste'),
            'flg_dias_padrao' => $this->validarFlag($this->valorEscalar($entrada, 'flg_dias_padrao'), 'Dias padrão'),
            'especialidade_id' => $this->validarId($this->valorEscalar($entrada, 'especialidade_id'), 'especialidade'),
            'cidade_id' => $this->validarId($this->valorEscalar($entrada, 'cidade_id'), 'cidade'),
        ];
        return array_merge($resultados, $datas);
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
            $credenciadoId = (int) ($linha['credenciado_id'] ?? 0);
            $nome = $this->normalizarRotuloGrafico($linha['nome'] ?? '', '');

            if ($credenciadoId > 0 && $nome !== '') {
                $agrupados[$credenciadoId][$nome] = true;
            }
        }

        $resultado = [];

        foreach ($agrupados as $credenciadoId => $nomesIndexados) {
            $nomes = array_keys($nomesIndexados);
            natcasesort($nomes);
            $resultado[$credenciadoId] = implode(', ', $nomes);
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

    private function descreverFiltrosAplicados(array $filtros): array
    {
        $descricoes = [];
        $tipos = $this->mapearOpcoes($this->buscarOpcoesDominioCol('dm_tipo'));
        $tributos = $this->mapearOpcoes($this->buscarOpcoesEnquadramento());
        $reajustes = $this->mapearOpcoes($this->buscarOpcoesDominioCol('dm_reaj_contr'));
        $diasPadrao = $this->mapearOpcoes($this->buscarOpcoesDominioCol('flg_dias_padrao'));
        $campos = [
            'nome' => 'Nome',
            'cnpj' => 'CNPJ',
            'cnes' => 'CNES',
            'inscricao_estadual' => 'Inscrição estadual',
            'codigo_prestador' => 'Código do prestador',
            'dm_tipo' => 'Tipo',
            'enquadramento_tributario' => 'Enquadramento tributário',
            'dm_reaj_contr' => 'Reajuste',
            'flg_dias_padrao' => 'Dias padrão',
            'situacao' => 'Situação',
            'data_inicio_inicio' => 'Credenciamento inicial',
            'data_inicio_fim' => 'Credenciamento final',
            'data_contrato_inicio' => 'Contrato inicial',
            'data_contrato_fim' => 'Contrato final',
            'dt_descredenciamento_inicio' => 'Descredenciamento inicial',
            'dt_descredenciamento_fim' => 'Descredenciamento final',
            'vigente_inicial' => 'Vigência inicial',
            'vigente_final' => 'Vigência final',
        ];
        foreach ($campos as $campo => $rotulo) {
            $valor = $filtros[$campo] ?? null;
            if ($valor === null || $valor === '') {
                continue;
            }
            if (isset($tipos[$valor]) && $campo === 'dm_tipo') {
                $valor = $tipos[$valor];
            } elseif ($campo === 'enquadramento_tributario') {
                $valor = $tributos[$valor] ?? $valor;
            } elseif ($campo === 'dm_reaj_contr') {
                $valor = $reajustes[$valor] ?? $valor;
            } elseif ($campo === 'situacao') {
                $valor = $valor === 'ATIVO' ? 'Ativo' : 'Inativo';
            } elseif ($campo === 'flg_dias_padrao') {
                $valor = $diasPadrao[$valor] ?? $valor;
            } elseif (preg_match('/^(data_|dt_|vigente_)/', $campo)) {
                $valor = $this->formatarDataRelatorio($valor);
            }
            $descricoes[] = ['rotulo' => $rotulo, 'valor' => (string) $valor];
        }
        foreach (['especialidade_id' => ['Especialidade','especialidades','especialidade'], 'cidade_id' => ['Cidade','cidades','cidade']] as $campo => $dados) {
            if ($filtros[$campo] === null) {
                continue;
            }
            $coluna = $dados[2];
            $linha = $this->consultarUmaLinha("SELECT {$coluna} AS nome FROM {$dados[1]} WHERE id = :id", ['id' => $filtros[$campo]]);
            $descricoes[] = ['rotulo' => $dados[0], 'valor' => $this->normalizarRotuloGrafico($linha['nome'] ?? '', 'Não encontrado')];
        }
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

    private function formatarCnpjRelatorio($valor): string
    {
        $texto = trim((string) ($valor ?? ''));
        $digitos = preg_replace('/\D+/', '', $texto) ?? '';

        if (strlen($digitos) === 14) {
            return substr($digitos, 0, 2) . '.'
                . substr($digitos, 2, 3) . '.'
                . substr($digitos, 5, 3) . '/'
                . substr($digitos, 8, 4) . '-'
                . substr($digitos, 12, 2);
        }

        return $this->valorRelatorio($texto);
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

    private function montarPagina(array $dados, array $opcoes): string
    {
        $total = $this->numero($dados['kpis']['total']);
        $ativos = $this->numero($dados['kpis']['ativos']);
        $inativos = $this->numero($dados['kpis']['inativos']);
        $novos = $this->numero($dados['kpis']['novos']);
        $periodoNovos = $this->h($dados['kpis']['periodo_novos']);
        $atualizadoEm = $this->h($dados['atualizado_em']);
        $optionsTipos = $this->montarOptions($opcoes['tipos'], 'Todos');
        $optionsEnquadramentos = $this->montarOptions($opcoes['enquadramentos'], 'Todos');
        $optionsReajustes = $this->montarOptions($opcoes['reajustes'], 'Todos');
        $mapaDiasPadrao = $this->mapearOpcoes($opcoes['dias_padrao']);
        $rotuloDiasPadraoSim = $this->h($mapaDiasPadrao['S'] ?? 'Sim');
        $rotuloDiasPadraoNao = $this->h($mapaDiasPadrao['N'] ?? 'Não');
        $optionsEspecialidades = $this->montarOptions($opcoes['especialidades'], 'Todas');
        $optionsCidades = $this->montarOptions($opcoes['cidades'], 'Todas');
        $config = json_encode(
            ['dados' => $dados],
            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        return <<<HTML
        <link rel="stylesheet" href="app/lib/include/css/credenciadosRelatorios.css">

        <main class="credenciados-relatorios-page" id="credenciadosRelatoriosPage">
            <header class="credenciados-relatorios-hero">
                <div class="credenciados-relatorios-hero-title">
                    <span class="credenciados-relatorios-hero-icon" aria-hidden="true">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    <div>
                        <span class="credenciados-relatorios-kicker">GESTÃO DE CREDENCIADOS</span>
                        <h1>Relatórios de Credenciados</h1>
                        <p>Indicadores, análises e relatórios gerenciais dos credenciados</p>
                    </div>
                </div>
            </header>

            <section class="credenciados-relatorios-panel credenciados-relatorios-filters" aria-labelledby="credenciadosRelatoriosFiltrosTitulo">
                <div class="credenciados-relatorios-filter-toolbar">
                    <div class="credenciados-relatorios-section-heading">
                        <span class="credenciados-relatorios-kicker">REFINAR VISÃO</span>
                        <h2 id="credenciadosRelatoriosFiltrosTitulo">Filtros de credenciados</h2>
                        <p>Os indicadores e gráficos são recalculados somente ao aplicar os filtros.</p>
                    </div>

                    <button type="button" class="credenciados-relatorios-filter-toggle" data-cr-action="toggle-filters" aria-expanded="false" aria-controls="credenciadosRelatoriosFiltrosPainel">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        <span data-cr-filter-toggle-label>Filtros</span>
                        <span class="credenciados-relatorios-filter-badge" data-cr-filter-count hidden>0</span>
                        <i class="fas fa-chevron-down credenciados-relatorios-filter-chevron" data-cr-filter-chevron aria-hidden="true"></i>
                    </button>
                </div>

                <div class="credenciados-relatorios-filter-collapse" id="credenciadosRelatoriosFiltrosPainel" aria-hidden="true">
                    <div class="credenciados-relatorios-filter-collapse-inner">
                        <form id="form_CredenciadosRelatorios" name="form_CredenciadosRelatorios" autocomplete="off">
                    <div class="credenciados-relatorios-filter-groups">
                        <section class="credenciados-relatorios-filter-group" aria-labelledby="crGrupoPrincipais">
                            <div class="credenciados-relatorios-filter-group-heading">
                                <span><i class="fas fa-id-card" aria-hidden="true"></i></span>
                                <div><h3 id="crGrupoPrincipais">Dados principais</h3><p>Identificação e dados cadastrais do credenciado.</p></div>
                            </div>
                            <div class="credenciados-relatorios-filter-grid">
                                <div class="credenciados-relatorios-field is-wide"><label for="crNome">Nome</label><input type="text" id="crNome" name="nome" maxlength="100" placeholder="Buscar parte do nome"></div>
                                <div class="credenciados-relatorios-field"><label for="crCnpj">CNPJ</label><input type="text" id="crCnpj" name="cnpj" maxlength="18" placeholder="Buscar CNPJ"></div>
                                <div class="credenciados-relatorios-field"><label for="crCnes">CNES</label><input type="text" id="crCnes" name="cnes" maxlength="15" placeholder="Buscar CNES"></div>
                                <div class="credenciados-relatorios-field"><label for="crCodigoPrestador">Código do prestador</label><input type="text" id="crCodigoPrestador" name="codigo_prestador" maxlength="15" placeholder="Buscar código"></div>
                                <div class="credenciados-relatorios-field"><label for="crInscricaoEstadual">Inscrição estadual</label><input type="text" id="crInscricaoEstadual" name="inscricao_estadual" maxlength="15" placeholder="Buscar inscrição estadual"></div>
                            </div>
                        </section>
                        <section class="credenciados-relatorios-filter-group" aria-labelledby="crGrupoClassificacao">
                            <div class="credenciados-relatorios-filter-group-heading">
                                <span><i class="fas fa-clipboard-list" aria-hidden="true"></i></span>
                                <div><h3 id="crGrupoClassificacao">Classificação e localidade</h3><p>Tipo, regime tributário, especialidades e cidade.</p></div>
                            </div>
                            <div class="credenciados-relatorios-filter-grid">
                                <div class="credenciados-relatorios-field"><label for="crTipo">Tipo</label><select id="crTipo" name="dm_tipo" class="credenciados-relatorios-searchable" data-placeholder="Todos">{$optionsTipos}</select></div>
                                <div class="credenciados-relatorios-field"><label for="crEnquadramento">Enquadramento tributário</label><select id="crEnquadramento" name="enquadramento_tributario" class="credenciados-relatorios-searchable" data-placeholder="Todos">{$optionsEnquadramentos}</select></div>
                                <div class="credenciados-relatorios-field"><label for="crEspecialidade">Especialidade</label><select id="crEspecialidade" name="especialidade_id" class="credenciados-relatorios-searchable" data-placeholder="Todas">{$optionsEspecialidades}</select></div>
                                <div class="credenciados-relatorios-field"><label for="crCidade">Cidade</label><select id="crCidade" name="cidade_id" class="credenciados-relatorios-searchable" data-placeholder="Todas">{$optionsCidades}</select></div>
                                <div class="credenciados-relatorios-field"><label for="crReajuste">Reajuste contratual</label><select id="crReajuste" name="dm_reaj_contr" class="credenciados-relatorios-searchable" data-placeholder="Todos">{$optionsReajustes}</select></div>
                            </div>
                        </section>
                        <section class="credenciados-relatorios-filter-group" aria-labelledby="crGrupoDatas">
                            <div class="credenciados-relatorios-filter-group-heading">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i></span>
                                <div><h3 id="crGrupoDatas">Datas</h3><p>Períodos de credenciamento, contrato e vigência.</p></div>
                            </div>
                            <div class="credenciados-relatorios-filter-grid">
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-date-field"><legend>Data do credenciamento</legend><div class="credenciados-relatorios-date-range"><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="data_inicio_inicio" aria-label="Credenciamento inicial"></span><span class="credenciados-relatorios-date-separator">até</span><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="data_inicio_fim" aria-label="Credenciamento final"></span></div></fieldset>
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-date-field"><legend>Data do contrato</legend><div class="credenciados-relatorios-date-range"><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="data_contrato_inicio" aria-label="Contrato inicial"></span><span class="credenciados-relatorios-date-separator">até</span><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="data_contrato_fim" aria-label="Contrato final"></span></div></fieldset>
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-date-field"><legend>Data do descredenciamento</legend><div class="credenciados-relatorios-date-range"><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="dt_descredenciamento_inicio" aria-label="Descredenciamento inicial"></span><span class="credenciados-relatorios-date-separator">até</span><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="dt_descredenciamento_fim" aria-label="Descredenciamento final"></span></div></fieldset>
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-date-field"><legend>Vigentes no período</legend><div class="credenciados-relatorios-date-range"><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="vigente_inicial" aria-label="Vigente desde"></span><span class="credenciados-relatorios-date-separator">até</span><span class="credenciados-relatorios-date-control"><i class="far fa-calendar-alt" aria-hidden="true"></i><input type="date" name="vigente_final" aria-label="Vigente até"></span></div></fieldset>
                            </div>
                        </section>
                        <section class="credenciados-relatorios-filter-group" aria-labelledby="crGrupoSituacoes">
                            <div class="credenciados-relatorios-filter-group-heading">
                                <span><i class="fas fa-toggle-on" aria-hidden="true"></i></span>
                                <div><h3 id="crGrupoSituacoes">Situações e flags</h3><p>Status cadastral e configuração do reajuste.</p></div>
                            </div>
                            <div class="credenciados-relatorios-filter-grid is-flags">
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-flag-field"><legend>Ativo</legend><div class="credenciados-relatorios-toggle-group">
                                    <input type="radio" id="crAtivoTodos" name="situacao" value="" checked><label for="crAtivoTodos">Todos</label>
                                    <input type="radio" id="crAtivoSim" name="situacao" value="ATIVO"><label for="crAtivoSim">Sim</label>
                                    <input type="radio" id="crAtivoNao" name="situacao" value="INATIVO"><label for="crAtivoNao">Não</label>
                                </div></fieldset>
                                <fieldset class="credenciados-relatorios-field credenciados-relatorios-flag-field"><legend>Dias padrão de aviso</legend><div class="credenciados-relatorios-toggle-group">
                                    <input type="radio" id="crDiasTodos" name="flg_dias_padrao" value="" checked><label for="crDiasTodos">Todos</label>
                                    <input type="radio" id="crDiasSim" name="flg_dias_padrao" value="S"><label for="crDiasSim">{$rotuloDiasPadraoSim}</label>
                                    <input type="radio" id="crDiasNao" name="flg_dias_padrao" value="N"><label for="crDiasNao">{$rotuloDiasPadraoNao}</label>
                                </div></fieldset>
                            </div>
                        </section>
                    </div>

                    <div class="credenciados-relatorios-filter-actions">
                        <button type="button" class="credenciados-relatorios-btn is-secondary" data-cr-action="limpar">
                            <i class="fas fa-eraser" aria-hidden="true"></i>
                            Limpar
                        </button>
                        <button type="submit" class="credenciados-relatorios-btn is-primary" data-cr-action="aplicar">
                            <i class="fas fa-filter" aria-hidden="true"></i>
                            Aplicar filtros
                        </button>
                    </div>
                        </form>
                    </div>
                </div>
            </section>

            <section class="credenciados-relatorios-kpis" aria-label="Indicadores de credenciados">
                <article class="credenciados-relatorios-kpi is-total">
                    <div class="credenciados-relatorios-kpi-top">
                        <span>Total de Credenciados</span>
                        <i class="fas fa-users" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="total">{$total}</strong>
                    <small>No recorte selecionado</small>
                </article>
                <article class="credenciados-relatorios-kpi is-active">
                    <div class="credenciados-relatorios-kpi-top">
                        <span>Credenciados Ativos</span>
                        <i class="fas fa-user-check" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="ativos">{$ativos}</strong>
                    <small>Cadastros com situação ativa</small>
                </article>
                <article class="credenciados-relatorios-kpi is-inactive">
                    <div class="credenciados-relatorios-kpi-top">
                        <span>Credenciados Inativos</span>
                        <i class="fas fa-user-slash" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="inativos">{$inativos}</strong>
                    <small>Cadastros com situação inativa</small>
                </article>
                <article class="credenciados-relatorios-kpi is-new">
                    <div class="credenciados-relatorios-kpi-top">
                        <span>Novos Credenciados</span>
                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                    </div>
                    <strong data-cr-kpi="novos">{$novos}</strong>
                    <small data-cr-periodo>{$periodoNovos}</small>
                </article>
            </section>

            <section class="credenciados-relatorios-analysis" aria-labelledby="credenciadosRelatoriosVisaoTitulo">
                <div class="credenciados-relatorios-section-heading">
                    <span class="credenciados-relatorios-kicker">VISÃO GERENCIAL</span>
                    <h2 id="credenciadosRelatoriosVisaoTitulo">Distribuição e perfil dos credenciados</h2>
                    <p>Leituras rápidas de situação, tipo, especialidades e localidades.</p>
                </div>

                <div class="credenciados-relatorios-chart-grid">
                    <article class="credenciados-relatorios-chart-card">
                        <div class="credenciados-relatorios-chart-heading">
                            <div>
                                <h3>Credenciados por situação</h3>
                                <p>Ativos e inativos no recorte atual</p>
                            </div>
                            <span class="credenciados-relatorios-chart-icon is-green"><i class="fas fa-user-check"></i></span>
                        </div>
                        <div class="credenciados-relatorios-chart" id="crChartSituacao" role="img" aria-label="Gráfico de credenciados por situação"></div>
                    </article>

                    <article class="credenciados-relatorios-chart-card">
                        <div class="credenciados-relatorios-chart-heading">
                            <div>
                                <h3>Credenciados por tipo</h3>
                                <p>Composição cadastral dos credenciados</p>
                            </div>
                            <span class="credenciados-relatorios-chart-icon is-purple"><i class="fas fa-clinic-medical"></i></span>
                        </div>
                        <div class="credenciados-relatorios-chart" id="crChartTipo" role="img" aria-label="Gráfico de credenciados por tipo"></div>
                    </article>

                    <article class="credenciados-relatorios-chart-card">
                        <div class="credenciados-relatorios-chart-heading">
                            <div>
                                <h3>Credenciados por especialidade</h3>
                                <p>Distribuição dos credenciados por especialidade</p>
                            </div>
                            <span class="credenciados-relatorios-chart-icon is-blue"><i class="fas fa-stethoscope"></i></span>
                        </div>
                        <div class="credenciados-relatorios-chart is-horizontal" id="crChartEspecialidades" role="img" aria-label="Gráfico de credenciados por especialidade"></div>
                    </article>

                    <article class="credenciados-relatorios-chart-card">
                        <div class="credenciados-relatorios-chart-heading">
                            <div>
                                <h3>Credenciados por cidade</h3>
                                <p>Distribuição dos credenciados por cidade</p>
                            </div>
                            <span class="credenciados-relatorios-chart-icon is-orange"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <div class="credenciados-relatorios-chart is-horizontal" id="crChartCidades" role="img" aria-label="Gráfico de credenciados por cidade"></div>
                    </article>
                </div>
            </section>

            <section class="credenciados-relatorios-reports" aria-labelledby="credenciadosRelatoriosRelatoriosTitulo">
                <div class="credenciados-relatorios-section-heading">
                    <span class="credenciados-relatorios-kicker">CONSULTAS E EXPORTAÇÕES</span>
                    <h2 id="credenciadosRelatoriosRelatoriosTitulo">Relatórios</h2>
                    <p>Selecione o relatório que deseja consultar ou exportar.</p>
                </div>

                <div class="credenciados-relatorios-report-grid">
                    <article class="credenciados-relatorios-report-card">
                        <span class="credenciados-relatorios-report-icon is-users"><i class="fas fa-users"></i></span>
                        <div class="credenciados-relatorios-report-content">
                            <span class="credenciados-relatorios-report-eyebrow">CADASTRO</span>
                            <h3>Credenciados</h3>
                            <p>Dados cadastrais e informações gerais dos credenciados.</p>
                            <button type="button" class="credenciados-relatorios-btn is-report" data-cr-report="credenciados">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                Visualizar relatório
                            </button>
                        </div>
                    </article>

                    <article class="credenciados-relatorios-report-card">
                        <span class="credenciados-relatorios-report-icon is-contacts"><i class="fas fa-address-book"></i></span>
                        <div class="credenciados-relatorios-report-content">
                            <span class="credenciados-relatorios-report-eyebrow">CONTATOS</span>
                            <h3>Contatos Credenciados</h3>
                            <p>Relação de contatos cadastrados para os credenciados.</p>
                            <div class="credenciados-relatorios-report-actions">
                                <button type="button" class="credenciados-relatorios-btn is-report" data-cr-report="telefones">
                                    <i class="fas fa-phone" aria-hidden="true"></i>
                                    Telefones
                                </button>
                                <button type="button" class="credenciados-relatorios-btn is-report" data-cr-report="emails">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                    E-mails
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <div class="credenciados-relatorios-footer-status">
                <i class="fas fa-clock" aria-hidden="true"></i>
                Atualizado em <span data-cr-atualizado>{$atualizadoEm}</span>
            </div>

            <div class="credenciados-relatorios-report-modal" data-cr-credenciados-modal aria-hidden="true" hidden>
                <div class="credenciados-relatorios-report-backdrop" data-cr-report-close></div>
                <section class="credenciados-relatorios-report-dialog" role="dialog" aria-modal="true" aria-labelledby="crRelatorioCredenciadosTitulo" tabindex="-1">
                    <header class="credenciados-relatorios-report-dialog-header">
                        <div class="credenciados-relatorios-report-dialog-title">
                            <span class="credenciados-relatorios-report-dialog-icon" aria-hidden="true"><i class="fas fa-users" data-cr-report-icon></i></span>
                            <div>
                                <span class="credenciados-relatorios-kicker" data-cr-report-kicker>RELATÓRIO CADASTRAL</span>
                                <h2 id="crRelatorioCredenciadosTitulo" data-cr-report-title>Relatório de Credenciados</h2>
                                <p data-cr-report-total aria-live="polite">0 credenciados encontrados</p>
                            </div>
                        </div>
                        <div class="credenciados-relatorios-report-dialog-actions">
                            <button type="button" class="credenciados-relatorios-report-excel" data-cr-report-excel aria-label="Baixar relatório em Excel" title="Baixar em Excel">
                                <i class="fas fa-file-excel" data-cr-report-excel-icon aria-hidden="true"></i>
                                <span data-cr-report-excel-label>Excel</span>
                            </button>
                            <button type="button" class="credenciados-relatorios-report-close" data-cr-report-close aria-label="Fechar relatório" title="Fechar">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </header>

                    <div class="credenciados-relatorios-report-dialog-body">
                        <section class="credenciados-relatorios-report-filters" aria-labelledby="crRelatorioFiltrosTitulo">
                            <h3 id="crRelatorioFiltrosTitulo"><i class="fas fa-filter" aria-hidden="true"></i> Filtros aplicados</h3>
                            <div class="credenciados-relatorios-report-filter-list" data-cr-report-filters></div>
                        </section>

                        <div class="credenciados-relatorios-report-empty" data-cr-report-empty hidden>
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <strong data-cr-report-empty-text>Nenhum credenciado encontrado para os filtros selecionados.</strong>
                        </div>

                        <div class="credenciados-relatorios-report-table-wrap" data-cr-report-table-wrap hidden>
                            <table class="credenciados-relatorios-report-table">
                                <thead data-cr-report-head></thead>
                                <tbody data-cr-report-body></tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="credenciados-relatorios-loading" aria-hidden="true">
                <span class="credenciados-relatorios-spinner"></span>
                <span data-cr-loading-text>Atualizando indicadores...</span>
            </div>
            <div class="credenciados-relatorios-toast" role="status" aria-live="polite"></div>

            <script type="application/json" id="credenciadosRelatoriosConfig">{$config}</script>
            <script src="app/lib/include/js/credenciadosRelatorios.js"></script>
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
