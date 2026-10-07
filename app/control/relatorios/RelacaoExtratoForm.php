<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RelacaoExtratoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosCapital';
    private static $primaryKey = 'id';
    private static $formName = 'form_RelacaoExtratoForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Relação extrato");

        $criteria_cooperados_id = new TCriteria();
        $criteria_dm_capital_social = new TCriteria();

        $filterVar = "DM_CAPITAL_SOCIAL";
        $criteria_dm_capital_social->add(new TFilter('atributo', '=', $filterVar)); 

        $data_aquisicao_de = new BDateRange('data_aquisicao_de', 'data_aquisicao_ate');
        $cooperados_id = new BDBSelectCheck('cooperados_id', 'databaserede', 'Cooperados', 'id', '{crm} - {nome}','nome asc' , $criteria_cooperados_id );
        $dm_capital_social = new BDBSelectCheck('dm_capital_social', 'databaserede', 'VDominioValorCol', 'valor', '{valor} - {mascara}','valor asc' , $criteria_dm_capital_social );
        $btn100a106 = new TButton('btn100a106');
        $btn200a203 = new TButton('btn200a203');


        $data_aquisicao_de->setMask('dd/mm/yyyy');
        $data_aquisicao_de->setDatabaseMask('yyyy-mm-dd');
        $btn100a106->setAction(new TAction([$this, 'selecionar100a105']), "100-106");
        $btn200a203->setAction(new TAction([$this, 'selecionar200a203']), "200-203");

        $btn100a106->addStyleClass('btn-default');
        $btn200a203->addStyleClass('btn-default');

        $btn100a106->setImage('fas:filter #28A745');
        $btn200a203->setImage('fas:filter #E74C3C');

        $cooperados_id->setSize('100%');
        $dm_capital_social->setSize('60%');
        $data_aquisicao_de->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Data aquisição:", null, '14px', null, '100%'),$data_aquisicao_de],[new TLabel("Cooperado:", null, '14px', null, '100%'),$cooperados_id]);
        $row1->layout = [' col-sm-3',' col-sm-8'];

        $row2 = $this->form->addFields([new TLabel("Grupo:", '#000000', '14px', null, '100%'),$dm_capital_social,$btn100a106,$btn200a203]);
        $row2->layout = [' col-sm-8'];

        // create the form actions
        $btn_onpdfcompleto = $this->form->addAction("Analítico (PDF)", new TAction([$this, 'onPdfCompleto']), 'far:file-pdf #03A9F4');
        $this->btn_onpdfcompleto = $btn_onpdfcompleto;

        $btn_onpdfresumido = $this->form->addAction("Sintético (PDF)", new TAction([$this, 'onPdfResumido']), 'far:file-pdf #F44336');
        $this->btn_onpdfresumido = $btn_onpdfresumido;

        $btn_gerarxlsxanalitico = $this->form->addAction("Analítico (XLS)", new TAction([$this, 'gerarXlsxAnalitico']), 'far:file-excel #03A9F4');
        $this->btn_gerarxlsxanalitico = $btn_gerarxlsxanalitico;

        $btn_gerarxlsxsintetico = $this->form->addAction("Sintético (XLS)", new TAction([$this, 'gerarXlsxSintetico']), 'far:file-excel #F44336');
        $this->btn_gerarxlsxsintetico = $btn_gerarxlsxsintetico;

        $btn_onclearfilters = $this->form->addAction("Limpar", new TAction([$this, 'onClearFilters']), 'fas:eraser #B03A2E');
        $this->btn_onclearfilters = $btn_onclearfilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Relatórios","Relação extrato"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onPdfCompleto($param = null) 
    {
        $data = $this->form->getData();
        TSession::setValue('rel_extrato_filtros', $data);

        $this->gerarRelatorio((array) $data, 'completo');

    }

    public function onPdfResumido($param = null) 
    {
        $data = $this->form->getData();
        TSession::setValue('rel_extrato_filtros', $data);

        $this->gerarRelatorio((array) $data, 'resumido');

    }

    public function gerarXlsxAnalitico($param = null) 
    {
        try 
        {
            $data = $this->form->getData();
            TSession::setValue('rel_extrato_filtros', $data);

            $dados = $this->buscarDadosParaXlsx($param, 'completo');

            if (!$dados) {
                new TMessage('info', 'Nenhum registro encontrado.');
                return;
            }

            $cabecalhos = [
                'CRM',
                'Nome',
                'Grupo',
                'Parcela',
                'Data Aquisição',
                'Valor'
            ];

            $linhas = [];
            $total = 0;
            foreach ($dados as $d) {
                $linhas[] = [
                    $d['crm'],
                    $d['nome'],
                    $d['nome_grupo'],
                    $d['nr_parcela'],
                    $d['data_aquisicao'],
                    $d['valor']
                ];
                $total += $d['valor'];
            }

            $arquivo = $this->gerarXlsxBase(
                $cabecalhos,
                $linhas,
                'relatorio_analitico.xlsx',
                "R$ " . number_format($total, 2, ',', '.')
            );

            parent::openFile($arquivo);

            TApplication::loadPage('RelacaoExtratoForm', 'onShow');
            TToast::show('success', 'XLS gerado com sucesso!', 'top right', 3000);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function gerarXlsxSintetico($param = null) 
    {
        try 
        {
            $data = $this->form->getData();
            TSession::setValue('rel_extrato_filtros', $data);
            $dados = $this->buscarDadosParaXlsx($param, 'resumido');

            if (!$dados) {
                new TMessage('info', 'Nenhum registro encontrado.');
                return;
            }

            $cabecalhos = [
                'CRM',
                'Nome',
                'Valor Total'
            ];

            $linhas = [];
            $total = 0;
            foreach ($dados as $d) {
                $linhas[] = [
                    $d['crm'],
                    $d['nome'],
                    $d['valor_total']
                ];
                $total += $d['valor_total'];
            }

            $arquivo = $this->gerarXlsxBase(
                $cabecalhos,
                $linhas,
                'relatorio_sintetico.xlsx',
                "R$ " . number_format($total, 2, ',', '.')
            );

            parent::openFile($arquivo);

            TApplication::loadPage('RelacaoExtratoForm', 'onShow');
            TToast::show('success', 'XLS gerado com sucesso!', 'top right', 3000);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onClearFilters($param = null) 
    {
        try 
        {
            $this->form->clear(true);
            TSession::setValue('rel_extrato_filtros', null);
            TToast::show('info', 'Filtros limpos!', 'top right', 3000);
            TApplication::loadPage('RelacaoExtratoForm', 'onShow');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new CooperadosCapital($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->form->clear(true);
        TSession::setValue('rel_extrato_filtros', null);

    }

    public function onShow($param = null)
    {

        $filtros = TSession::getValue('rel_extrato_filtros');

        if ($filtros) {
            $this->form->setData($filtros);
        }

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    private function gerarRelatorio($param, $tipo)
    {
        try {
            TTransaction::open(self::$database);
            $conn = TTransaction::get();

            $params_bind = [];
            $where_sql = self::montarClausulaWherePedidos($param, $params_bind);
            $where_sql = $where_sql ? "WHERE $where_sql" : "";

            // === SQL base ===
            if ($tipo === 'completo') {
                $sql = "
                    SELECT
                        c.crm,
                        c.nome,
                        CONCAT(cc.dm_capital_social, ' - ', v.mascara) AS nome_grupo,
                        cc.nr_parcela,
                        cc.data_aquisicao,
                        CASE
                            WHEN COALESCE(FIND_IN_SET(cc.dm_capital_social, f_obter_valor_param('listdm_grupos_capital_negativos'))) > 0 THEN cc.valor * -1
                            ELSE cc.valor
                        END valor
                    FROM cooperados_capital cc
                    LEFT JOIN cooperados c ON c.id = cc.cooperados_id
                    LEFT JOIN dominio_valor v ON v.valor = cc.dm_capital_social
                    $where_sql
                    ORDER BY c.nome, cc.data_aquisicao
                ";
            } else {
                $sql = "
                    SELECT
                        c.crm,
                        c.nome,
                        SUM(
                            CASE
                                WHEN COALESCE(FIND_IN_SET(cc.dm_capital_social, f_obter_valor_param('listdm_grupos_capital_negativos'))) > 0 
                                    THEN cc.valor * -1
                                ELSE cc.valor
                            END
                        ) AS valor
                    FROM cooperados_capital cc
                    LEFT JOIN cooperados c ON c.id = cc.cooperados_id
                    $where_sql
                    GROUP BY c.crm, c.nome
                    ORDER BY c.nome
                ";
            }

            $stmt = $conn->prepare($sql);
            foreach ($params_bind as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$dados) {
                new TMessage('info', 'Nenhum registro encontrado.');
                TTransaction::close();
                return;
            }

            // === Cabeçalho / Rodapé ===
            TTransaction::open('permission');
            $user = SystemUsers::find(TSession::getValue('userid'));
            TTransaction::close();

            $usuario = $user ? $user->name : 'Usuário não identificado';
            $data_geracao = date('d/m/Y H:i');

            $rel_img = RelImagem::find(2);
            $logo = $rel_img->img;
            if (file_exists($logo)) {
                $img_data = base64_encode(file_get_contents($logo));
                $img_type = pathinfo($logo, PATHINFO_EXTENSION);
                $img_src = 'data:image/' . $img_type . ';base64,' . $img_data;
            } else {
                throw new Exception("Imagem não encontrada: " . $logo);
            }

            // Define se o relatório é analítico ou sintético
            $tipo_titulo = ($tipo === 'completo') ? 'ANALÍTICO' : 'SINTÉTICO';

            // === Monta texto dos filtros aplicados ===
            $filtros_aplicados = [];

            if (!empty($param['data_aquisicao_de']) || !empty($param['data_aquisicao_ate'])) {
                $de = !empty($param['data_aquisicao_de']) ? date('d/m/Y', strtotime($param['data_aquisicao_de'])) : '';
                $ate = !empty($param['data_aquisicao_ate']) ? date('d/m/Y', strtotime($param['data_aquisicao_ate'])) : '';
                $filtros_aplicados[] = "<strong>Período:</strong> {$de} a {$ate}";
            }

            // Buscar total de cooperados existentes
            $repoTotal = new TRepository('Cooperados');
            $totalCooperados = $repoTotal->count();

            if (!empty($param['cooperados_id'])) {
                $idsSelecionados = $param['cooperados_id'];
                $qtdSelecionados = count($idsSelecionados);

                if ($qtdSelecionados == $totalCooperados) {
                    $filtros_aplicados[] = "<strong>Cooperados:</strong> Todos";

                } else {
                    $repo = new TRepository('Cooperados');
                    $criteria = new TCriteria;
                    $criteria->add(new TFilter('id', 'IN', $idsSelecionados));
                    $objects = $repo->load($criteria);

                    $nomes = [];
                    if ($objects) {
                        foreach ($objects as $obj) {
                            $nomes[] = $obj->nome;
                        }
                    }
                    $filtros_aplicados[] = "<strong>Cooperados:</strong> " . implode(', ', $nomes);
                }
            } else {
                $filtros_aplicados[] = "<strong>Cooperados:</strong> Todos";
            }

            // Buscar total de grupos disponíveis
            $repoTotal = new TRepository('DominioValor');
            $criteriaTotal = new TCriteria;
            $criteriaTotal->add(new TFilter('dominio_id ', '=', 22));
            $totalGrupos = $repoTotal->count($criteriaTotal);

            if (!empty($param['dm_capital_social'])) {
                $gruposSelecionados = $param['dm_capital_social'];
                $qtdSelecionados = count($gruposSelecionados);

                if ($qtdSelecionados == $totalGrupos) {
                    $filtros_aplicados[] = "<strong>Grupos:</strong> Todos";
                } else {
                    $repo = new TRepository('DominioValor');
                    $criteria = new TCriteria;
                    $criteria->add(new TFilter('valor', 'IN', $gruposSelecionados));
                    $criteria->add(new TFilter('dominio_id ', '=', 22));
                    $objects = $repo->load($criteria);
                    $desc = [];

                    if ($objects) {
                        foreach ($objects as $obj) {
                            $desc[] = $obj->valor . ' - ' . $obj->mascara;
                        }
                    }

                    $filtros_aplicados[] = "<strong>Grupos:</strong> " . implode(', ', $desc);
                }

            } else {
                $filtros_aplicados[] = "<strong>Grupos:</strong> Todos";
            }

            $html_filtros = "
                <div style='background:#f8f9f9; border-left:4px solid #00995D; padding:8px 10px; margin-top:8px; font-size:11px; border-radius:5px;'>
                    <strong>Filtros Aplicados</strong><br>" . implode('<br>', $filtros_aplicados) . "
                </div>
            ";

            // Caminho temporário fixo (pra evitar erro de permissão)
            $mpdf = new Mpdf([
                'format' => 'A4',
                'margin_top' => 40,
                'margin_bottom' => 25,
                'tempDir' => '/var/www/html/gestao_rede/tmp/mpdf'
            ]);

            $mpdf->SetHTMLHeader("
                <div style='display:flex; align-items:center; justify-content:space-between; border-bottom:2px solid #00995D; padding:0 0 3px 0; margin:0;'>
                    <img src='{$img_src}' width='200' style='margin:0; padding:0;'>
                    <div style='flex:1; text-align:right; font-weight:bold; font-size:18px; color:#00995D; margin:0; padding:0;'>
                        RELATÓRIO DE CAPITAL SOCIAL ({$tipo_titulo})
                    </div>
                </div>
            ");

            // === Rodapé fixo ===
            $mpdf->SetHTMLFooter("
                <div style='width:100%; font-size:10px; color:#555; padding-top:5px;'>
                    <table width='100%' style='border:none !important; border-collapse:collapse; border-spacing:0;'>
                        <tr>
                            <td style='text-align:left; border:none !important;'>Gerado por: <b>{$usuario}</b></td>
                            <td style='text-align:center; border:none !important;'>Página {PAGENO}/{nbpg}</td>
                            <td style='text-align:right; border:none !important;'>Data: {$data_geracao}</td>
                        </tr>
                    </table>
                </div>
            ");

            // === CSS ===
            $css = "
                <style>
                    body { font-family: Arial, sans-serif; font-size: 11px; color:#333; }
                    table { border-collapse: collapse; width: 100%; margin-top:10px; }
                    th, td { border: 1px solid #d5dbdb; padding: 6px; }
                    th { background-color: #e9f7ef; color: #145a32; font-weight:bold; text-align:center; font-size:12px; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    tr:hover td { background-color: #eafaf1; }
                    td.valor { text-align: right; }
                    .linha-total td { background-color: #d1f2eb; color: #145a32; font-weight: bold; text-align:right; }
                </style>
            ";

            // === Início da tabela ===
            $mpdf->WriteHTML($css);
            $mpdf->WriteHTML($html_filtros, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML("<table><thead>");

            if ($tipo === 'completo') {
                $mpdf->WriteHTML("
                    <tr>
                        <th>CRM</th>
                        <th>Cooperado</th>
                        <th>Grupo</th>
                        <th>Parcela</th>
                        <th>Data</th>
                        <th>Valor</th>
                    </tr>
                    </thead><tbody>", \Mpdf\HTMLParserMode::DEFAULT_MODE);

                $total_geral = 0;
                foreach ($dados as $row) {
                    $valor = number_format($row['valor'], 2, ',', '.');
                    $data = !empty($row['data_aquisicao']) ? date('d/m/Y', strtotime($row['data_aquisicao'])) : '';

                    $htmlLinha = "
                        <tr>
                            <td>{$row['crm']}</td>
                            <td>{$row['nome']}</td>
                            <td>{$row['nome_grupo']}</td>
                            <td align='center'>{$row['nr_parcela']}</td>
                            <td align='center'>{$data}</td>
                            <td class='valor'>R$ {$valor}</td>
                        </tr>";
                    $mpdf->WriteHTML($htmlLinha, \Mpdf\HTMLParserMode::HTML_BODY);

                    $total_geral += $row['valor'];
                }

                $mpdf->WriteHTML("
                    <tr class='linha-total'>
                        <td colspan='5'>Total Geral:</td>
                        <td class='valor'>R$ " . number_format($total_geral, 2, ',', '.') . "</td>
                    </tr>
                </tbody></table>", \Mpdf\HTMLParserMode::HTML_BODY);

            } else {
                $mpdf->WriteHTML("
                    <tr>
                        <th>CRM</th>
                        <th>Cooperado</th>
                        <th>Total Capital Integralizado</th>
                    </tr>
                    </thead><tbody>", \Mpdf\HTMLParserMode::DEFAULT_MODE);

                $total_geral = 0;
                foreach ($dados as $row) {
                    $valor = number_format($row['valor'], 2, ',', '.');

                    $htmlLinha = "
                        <tr>
                            <td>{$row['crm']}</td>
                            <td>{$row['nome']}</td>
                            <td class='valor'>R$ {$valor}</td>
                        </tr>";
                    $mpdf->WriteHTML($htmlLinha, \Mpdf\HTMLParserMode::HTML_BODY);

                    $total_geral += $row['valor'];
                }

                $mpdf->WriteHTML("
                    <tr class='linha-total'>
                        <td colspan='2'>Total Geral:</td>
                        <td class='valor'>R$ " . number_format($total_geral, 2, ',', '.') . "</td>
                    </tr>
                </tbody></table>", \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // === Gera PDF ===
            $file = 'app/output/relatorio_' . uniqid() . '.pdf';
            $mpdf->Output($file, \Mpdf\Output\Destination::FILE);

            parent::openFile($file);
            TToast::show('info', 'Relatório gerado com sucesso!', 'top right', '3000');
            TTransaction::close();
            TApplication::loadPage('RelacaoExtratoForm', 'onShow');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    private static function montarClausulaWherePedidos($param, &$params_bind)
    {
        $filtros = [];

        if (!empty($param['cooperados_id'])) {
            $ids = implode(',', array_map('intval', $param['cooperados_id']));
            $filtros[] = "cc.cooperados_id IN ($ids)";
        }

        if (!empty($param['dm_capital_social'])) {
            $grupos = implode(',', array_map('intval', $param['dm_capital_social']));
            $filtros[] = "cc.dm_capital_social IN ($grupos)";
        }

        if (!empty($param['data_aquisicao_de'])) {
            $filtros[] = 'DATE(cc.data_aquisicao) >= :data_aquisicao_de';
            $params_bind[':data_aquisicao_de'] = self::converterData($param['data_aquisicao_de']);
        }

        if (!empty($param['data_aquisicao_ate'])) {
            $filtros[] = 'DATE(cc.data_aquisicao) <= :data_aquisicao_ate';
            $params_bind[':data_aquisicao_ate'] = self::converterData($param['data_aquisicao_ate']);
        }

        return implode(' AND ', $filtros);
    }

    private static function converterData($data)
    {
        $date = DateTime::createFromFormat('d/m/Y', $data);
        return $date ? $date->format('Y-m-d') : $data;
    }

    public function selecionar100a105($param)
    {
        $data = $this->form->getData();
        $data->dm_capital_social = [100, 101, 102, 103, 104, 105, 106];
        $this->form->setData($data);
    }

    public function selecionar200a203($param)
    {
        $data = $this->form->getData();
        $data->dm_capital_social = [200, 201, 202, 203];
        $this->form->setData($data);
    }

    private function gerarXlsxBase($cabecalhos, $dados, $nomeArquivo = 'relatorio.xlsx', $totalGeral = null)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === Cabeçalho ===
        $col = 'A';
        foreach ($cabecalhos as $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $col++;
        }

        $ultimaCol = chr(ord('A') + count($cabecalhos) - 1);

        // Estilo cabeçalho
        $sheet->getStyle("A1:{$ultimaCol}1")->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '00995D']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // === Dados ===
        $linha = 2;
        foreach ($dados as $item) {
            $col = 'A';
            foreach ($item as $valor) {
                $sheet->setCellValue($col . $linha, $valor);
                $col++;
            }
            $linha++;
        }

        // === Linha de TOTAL (se existir) ===
        if ($totalGeral !== null) {

            $sheet->setCellValue("A{$linha}", "Total Geral:");
            $sheet->mergeCells("A{$linha}:" . chr(ord($ultimaCol)-1) . "{$linha}");

            $sheet->setCellValue($ultimaCol . $linha, $totalGeral);

            // Estilizar total
            $sheet->getStyle("A{$linha}:{$ultimaCol}{$linha}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'D1F2EB']
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '145A32']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'right'
                ]
            ]);

            $linha++;
        }

        // === Bordas gerais ===
        $sheet->getStyle("A1:{$ultimaCol}" . ($linha - 1))->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'inside'  => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ]);

        // Auto size
        foreach (range('A', $ultimaCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // === Gera arquivo ===
        $path = "tmp/{$nomeArquivo}";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    private function buscarDadosParaXlsx($param, $tipo)
    {
        TTransaction::open(self::$database);
        $conn = TTransaction::get();

        $params_bind = [];
        $where_sql = self::montarClausulaWherePedidos($param, $params_bind);
        $where_sql = $where_sql ? "WHERE $where_sql" : "";

        if ($tipo === 'completo') {
            $sql = "
                SELECT
                    c.crm,
                    c.nome,
                    CONCAT(cc.dm_capital_social, ' - ', v.mascara) AS nome_grupo,
                    cc.nr_parcela,
                    cc.data_aquisicao,
                    CASE
                        WHEN COALESCE(FIND_IN_SET(cc.dm_capital_social, f_obter_valor_param('listdm_grupos_capital_negativos'))) > 0 
                            THEN cc.valor * -1
                        ELSE cc.valor
                    END valor
                FROM cooperados_capital cc
                LEFT JOIN cooperados c ON c.id = cc.cooperados_id
                LEFT JOIN dominio_valor v ON v.valor = cc.dm_capital_social
                $where_sql
                ORDER BY c.nome, cc.data_aquisicao
            ";
        } else {
            $sql = "
                SELECT
                    c.crm,
                    c.nome,
                    SUM(
                        CASE
                            WHEN COALESCE(FIND_IN_SET(cc.dm_capital_social, f_obter_valor_param('listdm_grupos_capital_negativos'))) > 0 
                                THEN cc.valor * -1
                            ELSE cc.valor
                        END
                    ) AS valor_total
                FROM cooperados_capital cc
                LEFT JOIN cooperados c ON c.id = cc.cooperados_id
                $where_sql
                GROUP BY c.crm, c.nome
                ORDER BY c.nome
            ";
        }

        $stmt = $conn->prepare($sql);
        foreach ($params_bind as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        TTransaction::close();
        return $dados;
    }

}

