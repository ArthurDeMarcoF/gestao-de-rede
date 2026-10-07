<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RelatorioCredenciadosForm extends TPage
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


        $row1 = $this->form->addFields([new TLabel("Filtros:", null, '14px', null, '100%')]);
        $row1->layout = ['col-sm-3'];


        // create the form actions
        $btn_onpdfcredenciadosresponsaveis = $this->form->addAction("Relatório (PDF)", new TAction([$this, 'onPdfCredenciadosResponsaveis']), 'far:file-pdf #03A9F4');
        $this->btn_onpdfcredenciadosresponsaveis = $btn_onpdfcredenciadosresponsaveis;

        $btn_gerarxlsxresponsaveis = $this->form->addAction("Relatório (XLS)", new TAction([$this, 'gerarXlsxResponsaveis']), 'far:file-excel #F44336');
        $this->btn_gerarxlsxresponsaveis = $btn_gerarxlsxresponsaveis;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Relatórios","Relatório de Credenciados "]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onPdfCredenciadosResponsaveis($param = null) 
    {
        try {
            $dados = $this->buscarDadosCredenciadosResponsaveis();
            TTransaction::open(self::$database);

            if (!$dados) {
                new TMessage('info', 'Nenhum registro encontrado.');
                return;
            }

            // ===== HEADER / FOOTER (copiado do teu método atual) =====
            TTransaction::open('permission');
            $user = SystemUsers::find(TSession::getValue('userid'));
            TTransaction::close();

            $usuario = $user ? $user->name : 'Usuário não identificado';
            $data_geracao = date('d/m/Y H:i');

            $rel_img = RelImagem::find(2);
            $logo = $rel_img->img;
            $img_src = 'data:image/png;base64,' . base64_encode(file_get_contents($logo));

            $mpdf = new \Mpdf\Mpdf([
                'format' => 'A4',
                'margin_top' => 40,
                'margin_bottom' => 25,
                'tempDir' => '/var/www/html/gestao_rede/tmp/mpdf'
            ]);

            $mpdf->SetHTMLHeader("
                <div style='
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:20px;
                    border-bottom:2px solid #00995D;
                    padding-bottom:6px;
                '>
                    <img src='{$img_src}' width='180'>
                    <div style='
                        font-size:18px;
                        font-weight:bold;
                        color:#00995D;
                        text-align:right;
                    '>
                        RELATÓRIO DE CREDENCIADOS
                    </div>
                </div>
            ");

            $mpdf->SetHTMLFooter("
                <div style='font-size:10px'>
                    <table width='100%'>
                        <tr>
                            <td>Gerado por: <b>{$usuario}</b></td>
                            <td align='center'>Página {PAGENO}/{nbpg}</td>
                            <td align='right'>{$data_geracao}</td>
                        </tr>
                    </table>
                </div>
            ");

            // ===== CSS (o mesmo que você já usa) =====
            $mpdf->WriteHTML("
                <style>
                    body { font-family: Arial; font-size: 11px; }
                    table { border-collapse: collapse; width: 100%; margin-top:10px; }
                    th, td { border:1px solid #ccc; padding:6px; }
                    th { background:#e9f7ef; color:#145a32; }
                    tr:nth-child(even) { background:#f9f9f9; }
                </style>
            ");

            // ===== TABELA =====
            $mpdf->WriteHTML("
                <table>
                    <thead>
                        <tr>
                            <th>Credenciado</th>
                            <th>Cód. Prestador</th>
                            <th>Categoria</th>
                            <th>Responsável</th>
                            <th>Cooperado</th>
                        </tr>
                    </thead>
                    <tbody>
            ");

            foreach ($dados as $d) {
                $mpdf->WriteHTML("
                    <tr>
                        <td>{$d['credenciado']}</td>
                        <td>{$d['codigo_prestador']}</td>
                        <td>{$d['categoria']}</td>
                        <td>{$d['responsavel']}</td>
                        <td>{$d['cooperado']}</td>
                    </tr>
                ");
            }

            $mpdf->WriteHTML("</tbody></table>");

            $file = 'app/output/relatorio_responsaveis_' . uniqid() . '.pdf';
            $mpdf->Output($file, \Mpdf\Output\Destination::FILE);
            TTransaction::close();

            parent::openFile($file);
            TToast::show('success', 'PDF gerado com sucesso!', 'topRight');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

    }

    public function gerarXlsxResponsaveis($param = null) 
    {
        try 
        {
            $dados = $this->buscarDadosCredenciadosResponsaveis();

            if (!$dados) {
                new TMessage('info', 'Nenhum registro encontrado.');
                return;
            }

            $cabecalhos = [
                'Credenciado',
                'Código Prestador',
                'Categoria',
                'Responsável',
                'Cooperado'
            ];

            $linhas = [];
            foreach ($dados as $d) {
                $linhas[] = [
                    $d['credenciado'],
                    $d['codigo_prestador'],
                    $d['categoria'],
                    $d['responsavel'],
                    $d['cooperado']
                ];
            }

            $arquivo = $this->gerarXlsxBase(
                $cabecalhos,
                $linhas,
                'relatorio_responsaveis.xlsx'
            );

            parent::openFile($arquivo);
            TToast::show('success', 'XLS gerado com sucesso!', 'topRight');

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

    private function buscarDadosCredenciadosResponsaveis()
    {
        TTransaction::open(self::$database);
        $conn = TTransaction::get();

        $sql = "
            SELECT 
                c.nome            AS credenciado,
                c.codigo_prestador,
                cat.nome          AS categoria,
                cr.nome           AS responsavel,
                cr.cooperado
            FROM credenciados c
            JOIN credenciados_responsaveis cr  
                ON c.id = cr.credenciados_id
            JOIN categoria_responsavel cat 
                ON cat.id = cr.categoria_responsavel_id
            ORDER BY c.nome, cat.nome, cr.nome
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        TTransaction::close();
        return $dados;
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

}

