<?php

class VCooperadosCapitalReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'databaserede';
    private static $activeRecord = 'VCooperadosCapital';
    private static $primaryKey = 'id';
    private static $formName = 'form_VCooperadosCapitalReport';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Relação extrato");

        $criteria_dm_capital_social_de = new TCriteria();
        $criteria_dm_capital_social_ate = new TCriteria();
        $criteria_id = new TCriteria();

        $filterVar = "cooperados_capital";
        $criteria_dm_capital_social_de->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_capital_social";
        $criteria_dm_capital_social_de->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados_capital";
        $criteria_dm_capital_social_ate->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_capital_social";
        $criteria_dm_capital_social_ate->add(new TFilter('atributo', '=', $filterVar)); 

        $dm_capital_social_de = new TDBCombo('dm_capital_social_de', 'databaserede', 'VDominioValorCol', 'valor', '{valor} - {mascara}','sequencia asc' , $criteria_dm_capital_social_de );
        $dm_capital_social_ate = new TDBCombo('dm_capital_social_ate', 'databaserede', 'VDominioValorCol', 'valor', '{valor} - {mascara}','sequencia asc' , $criteria_dm_capital_social_ate );
        $data_aquisicao_de = new BDateRange('data_aquisicao_de', 'data_aquisicao_ate');
        $id = new BDBSelectCheck('id', 'databaserede', 'Cooperados', 'id', '{crm} - {nome}','nome asc' , $criteria_id );

        $data_aquisicao_de->setMask('dd/mm/yyyy');
        $data_aquisicao_de->setDatabaseMask('yyyy-mm-dd');
        $dm_capital_social_de->enableSearch();
        $dm_capital_social_ate->enableSearch();

        $id->setSize('100%');
        $data_aquisicao_de->setSize('100%');
        $dm_capital_social_de->setSize('100%');
        $dm_capital_social_ate->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Grupo de:", null, '14px', null, '100%'),$dm_capital_social_de],[new TLabel("Grupo até:", null, '14px', null, '100%'),$dm_capital_social_ate]);
        $row1->layout = ['col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Data aquisicao:", null, '14px', null, '100%'),$data_aquisicao_de],[new TLabel("Cooperado:", null, '14px', null, '100%'),$id]);
        $row2->layout = [' col-sm-4','col-sm-8'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onpopularpdf1 = $this->form->addAction("Gerar PDF", new TAction([$this, 'onPopularPdf1']), 'far:file-pdf #d44734');
        $this->btn_onpopularpdf1 = $btn_onpopularpdf1;

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');
        $this->btn_ongeneratexls = $btn_ongeneratexls;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Relatórios","Relação extrato"]));
        $container->add($this->form);

        parent::add($container);

    }

    public function onPopularPdf1($param = null) 
    {
        try
        {
            // Abre a transação com o banco
            TTransaction::open(self::$database);

            $filters = $this->getFilters();
            $param = [];

            // Cria repositório e critérios
            $repository = new TRepository(self::$activeRecord);
            $criteria   = new TCriteria;

            $param['order'] = 'nome, linha';
            $param['direction'] = 'asc';
            $criteria->setProperties($param);

            if ($filters) {
                foreach ($filters as $filter) {
                    $criteria->add($filter);
                }
            }

            // Carrega os dados
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                // Largura das colunas (ajuste se quiser)
                $widths = [70, 300, 200, 50, 70, 120];

                // Gera PDF diretamente
                $tr = new TTableWriterPDF($widths, 'L', 'A4');

                // Estilos
                $tr->addStyle('title', 'Helvetica', '10', 'B', '#000000', '#dbdbdb');
                $tr->addStyle('datap', 'Arial', '10', '', '#333333', '#f0f0f0');
                $tr->addStyle('datai', 'Arial', '10', '', '#333333', '#ffffff');
                $tr->addStyle('break', 'Helvetica', '10', 'B', '#ffffff', '#9a9a9a');
                $tr->addStyle('total', 'Helvetica', '10', 'I', '#000000', '#c7c7c7');
                $tr->addStyle('breakTotal', 'Helvetica', '10', 'I', '#000000', '#c6c8d0');

                // Cabeçalho
                $tr->addRow();
                $tr->addCell("CRM", 'left', 'title');
                $tr->addCell("Nome", 'left', 'title');
                $tr->addCell("Grupo", 'left', 'title');
                $tr->addCell("Parcela", 'center', 'title');
                $tr->addCell("Data", 'center', 'title');
                $tr->addCell("Valor", 'right', 'title');

                $grandTotal = 0;
                $breakTotal = 0;
                $breakValue = null;
                $firstRow   = true;
                $colour     = false;

                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';

                    // Detecta troca de cooperado
                    if ($object->nome !== $breakValue)
                    {
                        if (!$firstRow)
                        {
                            // Total por cooperado
                            $tr->addRow();
                            $tr->addCell('', 'center', 'breakTotal', 5);
                            $tr->addCell("R$ " . number_format($breakTotal, 2, ',', '.'), 'right', 'breakTotal');
                        }

                        // Nome do cooperado (título do grupo)
                        $tr->addRow();
                        $tr->addCell($object->nome, 'left', 'break', 6);
                        $breakTotal = 0;
                    }

                    $breakValue = $object->nome;

                    // Conversão simples de valores
                    $valor = (float) $object->valor;
                    $grandTotal += $valor;
                    $breakTotal += $valor;

                    // Formata data
                    $data_aquisicao = '';
                    if (!empty($object->data_aquisicao)) {
                        try {
                            $data_aquisicao = (new DateTime($object->data_aquisicao))->format('d/m/Y');
                        } catch (Exception $e) {
                            $data_aquisicao = $object->data_aquisicao;
                        }
                    }

                    // Linha normal
                    $tr->addRow();
                    $tr->addCell($object->crm, 'left', $style);
                    $tr->addCell($object->nome, 'left', $style);
                    $tr->addCell($object->dm_capital_social, 'left', $style);
                    $tr->addCell($object->nr_parcela, 'center', $style);
                    $tr->addCell($data_aquisicao, 'center', $style);
                    $tr->addCell("R$ " . number_format($valor, 2, ',', '.'), 'right', $style);

                    $colour = !$colour;
                    $firstRow = false;
                }

                // Último total de cooperado
                $tr->addRow();
                $tr->addCell('', 'center', 'breakTotal', 5);
                $tr->addCell("R$ " . number_format($breakTotal, 2, ',', '.'), 'right', 'breakTotal');

                // Total geral
                $tr->addRow();
                $tr->addCell('', 'center', 'total', 5);
                $tr->addCell("R$ " . number_format($grandTotal, 2, ',', '.'), 'right', 'total');

                // Salva o arquivo
                $file = 'report_' . uniqid() . '.pdf';
                $path = "app/output/{$file}";

                if (!file_exists($path) || is_writable($path)) {
                    $tr->save($path);
                } else {
                    throw new Exception('Permissão negada: ' . $path);
                }

                parent::openFile($path);
                new TMessage('info', 'Relatório PDF gerado com sucesso!');

            } else {
                new TMessage('error', 'Nenhum registro encontrado.');
            }

            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onGenerateXls($param = null) 
    {
        $this->onGenerate('xls');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
    {
        // get the search form data
        $data = $this->form->getData();

        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->dm_capital_social_de) AND ( (is_scalar($data->dm_capital_social_de) AND $data->dm_capital_social_de !== '') OR (is_array($data->dm_capital_social_de) AND (!empty($data->dm_capital_social_de)) )) )
        {

            $filters[] = new TFilter('dm_capital_social', '>=', $data->dm_capital_social_de);// create the filter 
        }
        if (isset($data->dm_capital_social_ate) AND ( (is_scalar($data->dm_capital_social_ate) AND $data->dm_capital_social_ate !== '') OR (is_array($data->dm_capital_social_ate) AND (!empty($data->dm_capital_social_ate)) )) )
        {

            $filters[] = new TFilter('dm_capital_social', '<=', $data->dm_capital_social_ate);// create the filter 
        }
        if (isset($data->data_aquisicao_ate) AND ( (is_scalar($data->data_aquisicao_ate) AND $data->data_aquisicao_ate !== '') OR (is_array($data->data_aquisicao_ate) AND (!empty($data->data_aquisicao_ate)) )) )
        {

            $filters[] = new TFilter('data_aquisicao', '<=', $data->data_aquisicao_ate);// create the filter 
        }
        if (isset($data->data_aquisicao_de) AND ( (is_scalar($data->data_aquisicao_de) AND $data->data_aquisicao_de !== '') OR (is_array($data->data_aquisicao_de) AND (!empty($data->data_aquisicao_de)) )) )
        {

            $filters[] = new TFilter('data_aquisicao', '>=', $data->data_aquisicao_de);// create the filter 
        }
        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', 'in', $data->id);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'databaserede'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for VCooperadosCapital
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'nome,linha';
            $param['direction'] = 'asc';

            $criteria->setProperties($param);

            if ($filters)
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                $widths = array(70,300,200,50,70,120);
                $reportExtension = 'pdf';
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        $reportExtension = 'html';
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        $reportExtension = 'xls';
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L', 'A4');
                        $reportExtension = 'pdf';
                        break;
                    case 'htmlPdf':
                        $reportExtension = 'pdf';
                        $tr = new BTableWriterHtmlPDF($widths, 'L', 'A4');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $reportExtension = 'rtf';
                        $tr = new TTableWriterRTF($widths, 'L', 'A4');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Crm", 'left', 'title');
                    $tr->addCell("Nome", 'left', 'title');
                    $tr->addCell("Grupo", 'left', 'title');
                    $tr->addCell("Parcela", 'center', 'title');
                    $tr->addCell("Data", 'center', 'title');
                    $tr->addCell("Valor", 'right', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->nome !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_valor = array_sum($breakTotal['valor']);

                                $breakTotal_valor = call_user_func(function($value)
                                {
                                    if(!$value)
                                        $value = 0;

                                    if(is_numeric($value)) {
                                        if ($value > 0)
                                            return "R$ " . number_format($value, 2, ",", ".");
                                        return "R$ (" . str_replace('-', '', number_format($value, 2, ",", ".")) . ')';
                                    } else {
                                        return $value;
                                    }

                                }, $breakTotal_valor); 

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_valor, 'right', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{nome}'), 'left', 'break', 6);
                            $breakTotal = [];
                        }
                        $breakValue = $object->nome;

                        $grandTotal['valor'][] = $object->valor;
                        $breakTotal['valor'][] = $object->valor;

                        $firstRow = false;

                        $object->dm_capital_social = call_user_func(function($value, $object, $row)
                        {

                            return DMService::obterMask('dm_capital_social', $value, false);

                        }, $object->dm_capital_social, $object, null);

                        $object->data_aquisicao = call_user_func(function($value, $object, $row) 
                        {
                            if(!empty(trim((string) $value)))
                            {
                                try
                                {
                                    $date = new DateTime($value);
                                    return $date->format('d/m/Y');
                                }
                                catch (Exception $e)
                                {
                                    return $value;
                                }
                            }
                        }, $object->data_aquisicao, $object, null);

                        $object->valor = call_user_func(function($value, $object, $row)
                        {
                            if(!$value)
                                $value = 0;

                            if(is_numeric($value)) {
                                if ($value > 0)
                                    return "R$ " . number_format($value, 2, ",", ".");
                                return "R$ (" . str_replace('-', '', number_format($value, 2, ",", ".")) . ')';
                            } else {
                                return $value;
                            }

                        }, $object->valor, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->crm, 'left', $style);
                        $tr->addCell($object->nome, 'left', $style);
                        $tr->addCell($object->dm_capital_social, 'left', $style);
                        $tr->addCell($object->nr_parcela, 'center', $style);
                        $tr->addCell($object->data_aquisicao, 'center', $style);
                        $tr->addCell($object->valor, 'right', $style);

                        $colour = !$colour;

                    }

                    $tr->addRow();

                    $breakTotal_valor = array_sum($breakTotal['valor']);

                    $breakTotal_valor = call_user_func(function($value)
                    {
                        if(!$value)
                            $value = 0;

                        if(is_numeric($value)) {
                            if ($value > 0)
                                return "R$ " . number_format($value, 2, ",", ".");
                            return "R$ (" . str_replace('-', '', number_format($value, 2, ",", ".")) . ')';
                        } else {
                            return $value;
                        }

                    }, $breakTotal_valor); 

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_valor, 'right', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_valor = array_sum($grandTotal['valor']);

                    $grandTotal_valor = call_user_func(function($value)
                    {
                        if(!$value)
                            $value = 0;

                        if(is_numeric($value)) {
                            if ($value > 0)
                                return "R$ " . number_format($value, 2, ",", ".");
                            return "R$ (" . str_replace('-', '', number_format($value, 2, ",", ".")) . ')';
                        } else {
                            return $value;
                        }

                    }, $grandTotal_valor); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_valor, 'right', 'total');

                    $file = 'report_'.uniqid().".{$reportExtension}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }


}

