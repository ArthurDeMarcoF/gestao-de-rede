<?php

class CooperadosDocumentacoesVencidasList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosDocumentacoesVencidasList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Documentações a vencer/vencidas");
        $this->limit = 20;

        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_cooperados_id = new TCriteria();

        $emissao_de = new TDate('emissao_de');
        $emissao_ate = new TDate('emissao_ate');
        $validade_de = new TDate('validade_de');
        $validade_ate = new TDate('validade_ate');
        $data_alerta_de = new TDate('data_alerta_de');
        $data_alerta_ate = new TDate('data_alerta_ate');
        $ativo = new TCombo('ativo');
        $entregue = new TCombo('entregue');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $documentacoes_id = new TCombo('documentacoes_id');
        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $entregue->addItems(["Sim"=>"Sim","Não"=>"Não"]);

        $ativo->enableSearch();
        $entregue->enableSearch();
        $cooperados_id->enableSearch();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $emissao_de->setMask('dd/mm/yyyy');
        $emissao_ate->setMask('dd/mm/yyyy');
        $validade_de->setMask('dd/mm/yyyy');
        $validade_ate->setMask('dd/mm/yyyy');
        $data_alerta_de->setMask('dd/mm/yyyy');
        $data_alerta_ate->setMask('dd/mm/yyyy');

        $emissao_de->setDatabaseMask('yyyy-mm-dd');
        $emissao_ate->setDatabaseMask('yyyy-mm-dd');
        $validade_de->setDatabaseMask('yyyy-mm-dd');
        $validade_ate->setDatabaseMask('yyyy-mm-dd');
        $data_alerta_de->setDatabaseMask('yyyy-mm-dd');
        $data_alerta_ate->setDatabaseMask('yyyy-mm-dd');

        $ativo->setSize('100%');
        $emissao_de->setSize(110);
        $emissao_ate->setSize(110);
        $validade_de->setSize(110);
        $entregue->setSize('100%');
        $validade_ate->setSize(110);
        $data_alerta_de->setSize(110);
        $data_alerta_ate->setSize(110);
        $cooperados_id->setSize('100%');
        $documentacoes_id->setSize('100%');
        $tipos_documentacoes_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Data de emissão entre:", null, '14px', null, '100%'),$emissao_de,$emissao_ate],[new TLabel("Data de validade entre:", null, '14px', null, '100%'),$validade_de,$validade_ate],[new TLabel("Data de alerta entre:", null, '14px', null, '100%'),$data_alerta_de,$data_alerta_ate]);
        $row1->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Ativo:", null, '14px', null, '100%'),$ativo],[new TLabel("Entregue:", null, '14px', null, '100%'),$entregue],[]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Tipos de documentação:", null, '14px', null, '100%'),$tipos_documentacoes_id],[new TLabel("Documentação:", null, '14px', null, '100%'),$documentacoes_id]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Cooperado:", null, '14px', null, '100%'),$cooperados_id]);
        $row4->layout = [' col-sm-12'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $filterVar = NULL;
        $this->filter_criteria->add(new TFilter('validade', 'is not', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_tipos_documentacoes_tipo_documento = new TDataGridColumn('tipos_documentacoes->tipo_documento', "Tipo", 'left');
        $column_documentacoes_descricao = new TDataGridColumn('documentacoes->descricao', "Documento", 'left');
        $column_cooperados_nome = new TDataGridColumn('cooperados->nome', "Cooperado", 'left');
        $column_emissao = new TDataGridColumn('emissao', "Emissão", 'left');
        $column_validade = new TDataGridColumn('validade', "Validade", 'left');
        $column_data_alerta = new TDataGridColumn('data_alerta', "Data de alerta", 'left');
        $column_ativo = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_entregue = new TDataGridColumn('entregue', "Entregue", 'left');

        $this->datagrid->addColumn($column_tipos_documentacoes_tipo_documento);
        $this->datagrid->addColumn($column_documentacoes_descricao);
        $this->datagrid->addColumn($column_cooperados_nome);
        $this->datagrid->addColumn($column_emissao);
        $this->datagrid->addColumn($column_validade);
        $this->datagrid->addColumn($column_data_alerta);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_entregue);

        $action_onShow = new TDataGridAction(array('CredenciadosDocumentacoesForm', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Editar");
        $action_onShow->setImage('far:edit #478fca');
        $action_onShow->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onShow);

        $action_onBaixar = new TDataGridAction(array('CooperadosDocumentacoesVencidasList', 'onBaixar'));
        $action_onBaixar->setUseButton(false);
        $action_onBaixar->setButtonClass('btn btn-default btn-sm');
        $action_onBaixar->setLabel("");
        $action_onBaixar->setImage('fas:cloud-download-alt #03A9F4');
        $action_onBaixar->setField(self::$primaryKey);
        $action_onBaixar->setDisplayCondition('CooperadosDocumentacoesVencidasList::onExibirBaixar');

        $this->datagrid->addAction($action_onBaixar);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        $this->pageNavigation->keepLastPagination(__CLASS__);

        $panel = new TPanelGroup("Documentações a vencer/vencidas");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;

        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($headerActions);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['CooperadosDocumentacoesVencidasList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['CooperadosDocumentacoesVencidasList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['CooperadosDocumentacoesVencidasList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['CooperadosDocumentacoesVencidasList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['CooperadosDocumentacoesVencidasList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['CooperadosDocumentacoesVencidasList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['CooperadosDocumentacoesVencidasList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Documentações a vencer/vencidas"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public static function onChangetipos_documentacoes_id($param)
    {
        try
        {

            if (isset($param['tipos_documentacoes_id']) && $param['tipos_documentacoes_id'])
            { 
                $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipos_documentacoes_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'id asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'documentacoes_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onBaixar($param = null) 
    {
        try 
        {
            TTransaction::open('databaserede');
            $objeto = CooperadosDocumentacoes::find( $param['key'] );
            TTransaction::close();

            TPage::openFile($objeto->path_arquivo);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onExibirBaixar($object)
    {
        try 
        {
            if(!empty($object->path_arquivo))
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onExportCsv($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.csv';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    $handler = fopen($output, 'w');
                    TTransaction::open(self::$database);

                    foreach ($objects as $object)
                    {
                        $row = [];
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();

                            if (isset($object->$column_name))
                            {
                                $row[] = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $row[] = $object->render($column_name);
                            }
                        }

                        fputcsv($handler, $row);
                    }

                    fclose($handler);
                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXls($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xls';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $widths = [];
                $titles = [];

                foreach ($this->datagrid->getColumns() as $column)
                {
                    $titles[] = $column->getLabel();
                    $width    = 100;

                    if (is_null($column->getWidth()))
                    {
                        $width = 100;
                    }
                    else if (strpos((string)$column->getWidth(), '%') !== false)
                    {
                        $width = ((int) $column->getWidth()) * 5;
                    }
                    else if (is_numeric($column->getWidth()))
                    {
                        $width = $column->getWidth();
                    }

                    $widths[] = $width;
                }

                $table = new \TTableWriterXLS($widths);
                $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                $table->addStyle('data',   'Helvetica', '10', '',  '#000000', '#FFFFFF', 'LR');

                $table->addRow();

                foreach ($titles as $title)
                {
                    $table->addCell($title, 'center', 'title');
                }

                $this->limit = 0;
                $objects = $this->onReload();

                TTransaction::open(self::$database);
                if ($objects)
                {
                    foreach ($objects as $object)
                    {
                        $table->addRow();
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $value = '';
                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                            }

                            $transformer = $column->getTransformer();
                            if ($transformer)
                            {
                                $value = strip_tags((string)call_user_func($transformer, $value, $object, null));
                            }

                            $table->addCell($value, 'center', 'data');
                        }
                    }
                }
                $table->save($output);
                TTransaction::close();

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('iframe');
                $object->src  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $window->add($object);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXml($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xml';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    TTransaction::open(self::$database);

                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->{'formatOutput'} = true;
                    $dataset = $dom->appendChild( $dom->createElement('dataset') );

                    foreach ($objects as $object)
                    {
                        $row = $dataset->appendChild( $dom->createElement( self::$activeRecord ) );

                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $column_name_raw = str_replace(['(','{','->', '-','>','}',')', ' '], ['','','_','','','','','_'], $column_name);

                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                                $row->appendChild($dom->createElement($column_name_raw, $value)); 
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                                $row->appendChild($dom->createElement($column_name_raw, $value));
                            }
                        }
                    }

                    $dom->save($output);

                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public static function onShowCurtainFilters($param = null) 
    {
        try 
        {
            //code here

                        $filter = new self([]);

            $btnClose = new TButton('closeCurtain');
            $btnClose->class = 'btn btn-sm btn-default';
            $btnClose->style = 'margin-right:10px;';
            $btnClose->onClick = "Template.closeRightPanel();";
            $btnClose->setLabel("Fechar");
            $btnClose->setImage('fas:times');

            $filter->form->addHeaderWidget($btnClose);

            $page = new TPage();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('page-name', 'CooperadosDocumentacoesVencidasListSearch');
            $page->setProperty('page_name', 'CooperadosDocumentacoesVencidasListSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

            $style = new TStyle('right-panel > .container-part[page-name=CooperadosDocumentacoesVencidasListSearch]');
            $style->width = '50% !important';
            $style->show(true);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->emissao_de) AND ( (is_scalar($data->emissao_de) AND $data->emissao_de !== '') OR (is_array($data->emissao_de) AND (!empty($data->emissao_de)) )) )
        {

            $filters[] = new TFilter('emissao', '>=', $data->emissao_de);// create the filter 
        }

        if (isset($data->emissao_ate) AND ( (is_scalar($data->emissao_ate) AND $data->emissao_ate !== '') OR (is_array($data->emissao_ate) AND (!empty($data->emissao_ate)) )) )
        {

            $filters[] = new TFilter('emissao', '<=', $data->emissao_ate);// create the filter 
        }

        if (isset($data->validade_de) AND ( (is_scalar($data->validade_de) AND $data->validade_de !== '') OR (is_array($data->validade_de) AND (!empty($data->validade_de)) )) )
        {

            $filters[] = new TFilter('validade', '>=', $data->validade_de);// create the filter 
        }

        if (isset($data->validade_ate) AND ( (is_scalar($data->validade_ate) AND $data->validade_ate !== '') OR (is_array($data->validade_ate) AND (!empty($data->validade_ate)) )) )
        {

            $filters[] = new TFilter('validade', '<=', $data->validade_ate);// create the filter 
        }

        if (isset($data->data_alerta_de) AND ( (is_scalar($data->data_alerta_de) AND $data->data_alerta_de !== '') OR (is_array($data->data_alerta_de) AND (!empty($data->data_alerta_de)) )) )
        {

            $filters[] = new TFilter('data_alerta', '>=', $data->data_alerta_de);// create the filter 
        }

        if (isset($data->data_alerta_ate) AND ( (is_scalar($data->data_alerta_ate) AND $data->data_alerta_ate !== '') OR (is_array($data->data_alerta_ate) AND (!empty($data->data_alerta_ate)) )) )
        {

            $filters[] = new TFilter('data_alerta', '<=', $data->data_alerta_ate);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', 'like', "%{$data->ativo}%");// create the filter 
        }

        if (isset($data->entregue) AND ( (is_scalar($data->entregue) AND $data->entregue !== '') OR (is_array($data->entregue) AND (!empty($data->entregue)) )) )
        {

            $filters[] = new TFilter('entregue', 'like', "%{$data->entregue}%");// create the filter 
        }

        if (isset($data->tipos_documentacoes_id) AND ( (is_scalar($data->tipos_documentacoes_id) AND $data->tipos_documentacoes_id !== '') OR (is_array($data->tipos_documentacoes_id) AND (!empty($data->tipos_documentacoes_id)) )) )
        {

            $filters[] = new TFilter('tipos_documentacoes_id', '=', $data->tipos_documentacoes_id);// create the filter 
        }

        if (isset($data->documentacoes_id) AND ( (is_scalar($data->documentacoes_id) AND $data->documentacoes_id !== '') OR (is_array($data->documentacoes_id) AND (!empty($data->documentacoes_id)) )) )
        {

            $filters[] = new TFilter('documentacoes_id', '=', $data->documentacoes_id);// create the filter 
        }

        if (isset($data->cooperados_id) AND ( (is_scalar($data->cooperados_id) AND $data->cooperados_id !== '') OR (is_array($data->cooperados_id) AND (!empty($data->cooperados_id)) )) )
        {

            $filters[] = new TFilter('cooperados_id', '=', $data->cooperados_id);// create the filter 
        }

        $this->fireEvents($data);

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'databaserede'
            TTransaction::open(self::$database);

            // creates a repository for CooperadosDocumentacoes
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'validade';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            //</blockLine><btnShowCurtainFiltersAutoCode>
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                if ($countFilters != 0)
                    $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }
            //</blockLine></btnShowCurtainFiltersAutoCode>

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
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

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new CooperadosDocumentacoes($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

