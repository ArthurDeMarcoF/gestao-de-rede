<?php

class MedicosPfList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPf';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    use BuilderDatagridTrait;

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
        $this->form->setFormTitle("Listagem de consultórios de especialidades");
        $this->limit = 20;

        $criteria_estado_civil = new TCriteria();
        $criteria_sexo = new TCriteria();
        $criteria_especialidades = new TCriteria();

        $filterVar = "cooperados";
        $criteria_estado_civil->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "estado_civil";
        $criteria_estado_civil->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_sexo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "sexo";
        $criteria_sexo->add(new TFilter('atributo', '=', $filterVar)); 

        $nome = new TEntry('nome');
        $crm = new TEntry('crm');
        $data_nascimento = new TDate('data_nascimento');
        $cpf = new TEntry('cpf');
        $rg = new TEntry('rg');
        $data_contrato = new TDate('data_contrato');
        $estado_civil = new TDBCombo('estado_civil', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_estado_civil );
        $sexo = new TDBCombo('sexo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_sexo );
        $ativo = new TRadioGroup('ativo');
        $data_encerramento_contrato_inicio = new TDate('data_encerramento_contrato_inicio');
        $data_encerramento_contrato_fim = new TDate('data_encerramento_contrato_fim');
        $especialidades = new TDBCombo('especialidades', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades );


        $ativo->addItems(["S"=>"Sim","N"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setUseButton();
        $sexo->enableSearch();
        $estado_civil->enableSearch();
        $especialidades->enableSearch();

        $rg->setMaxLength(20);
        $crm->setMaxLength(20);
        $cpf->setMaxLength(14);
        $nome->setMaxLength(150);

        $data_contrato->setDatabaseMask('yyyy-mm-dd');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $data_encerramento_contrato_fim->setDatabaseMask('yyyy-mm-dd');
        $data_encerramento_contrato_inicio->setDatabaseMask('yyyy-mm-dd');

        $rg->setMask('99.999.999-9');
        $cpf->setMask('999.999.999-99');
        $data_contrato->setMask('dd/mm/yyyy');
        $data_nascimento->setMask('dd/mm/yyyy');
        $data_encerramento_contrato_fim->setMask('dd/mm/yyyy');
        $data_encerramento_contrato_inicio->setMask('dd/mm/yyyy');

        $rg->setSize('100%');
        $crm->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $ativo->setSize('100%');
        $data_contrato->setSize(110);
        $data_nascimento->setSize(110);
        $estado_civil->setSize('100%');
        $especialidades->setSize('100%');
        $data_encerramento_contrato_fim->setSize(110);
        $data_encerramento_contrato_inicio->setSize(110);

        $row1 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("CRM:", null, '14px', null, '100%'),$crm],[new TLabel("Data de nascimento:", null, '14px', null, '100%'),$data_nascimento]);
        $row1->layout = ['col-sm-6','col-sm-3','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("CPF:", null, '14px', null, '100%'),$cpf],[new TLabel("RG:", null, '14px', null, '100%'),$rg],[new TLabel("Data do contrato:", null, '14px', null, '100%'),$data_contrato]);
        $row2->layout = ['col-sm-3','col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Estado civil:", null, '14px', null, '100%'),$estado_civil],[new TLabel("Sexo:", null, '14px', null, '100%'),$sexo]);
        $row3->layout = ['col-sm-4','col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Ativo:", null, '14px', null, '100%'),$ativo],[new TLabel("Data encerramento do contrato:", null, '14px', null, '100%'),$data_encerramento_contrato_inicio,new TLabel("até", null, '14px', null),$data_encerramento_contrato_fim]);
        $row4->layout = ['col-sm-2',' col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Especialidades:", null, '14px', null, '100%'),$especialidades]);
        $row5->layout = [' col-sm-8'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->enableUserProperties('fa fa-cog', 'btn btn-default', new TAction([$this, 'setDatagridProperties']));
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_path_foto_transformed = new TDataGridColumn('path_foto', "Foto", 'left');
        $column_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_data_nascimento_transformed = new TDataGridColumn('data_nascimento', "Idade", 'left');
        $column_crm = new TDataGridColumn('crm', "CRM", 'left');
        $column_cpf = new TDataGridColumn('cpf', "CPF", 'left');
        $column_data_contrato_transformed = new TDataGridColumn('data_contrato', "Data contrato", 'left');
        $column_medicos_pf_especialidades_especialidades_to_string = new TDataGridColumn('medicos_pf_especialidades_especialidades_to_string', "Especialidades", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');

        $column_path_foto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if ($value)
                return "<img src=\"{$value}\" style=\" width: 50px; height: auto; border-radius: 10px; border: 1px solid #8694B0; \">";

            return '';

        });

        $column_data_nascimento_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $aux_data = new DateTime($value);
            $aux_hoje = new DateTime(date('Y-m-d'));
            $aux_diff = $aux_data->diff($aux_hoje);
            $aux_param = ParamService::valor('format_data_nasc');

            if ($aux_param == 1) // Data (dd/mm/aaaa)
                $aux_expr = $aux_data->format(DMService::obterMask('dm_format_data', 'D', false));

            elseif ($aux_param == 2) // Idade (X anos)
                $aux_expr = ExpService::montar(25, // {$1} anos 
                                               $aux_diff->format('%Y'));

            elseif ($aux_param == 3) // Data + Idade (dd/mm/aaaa, X anos)
                $aux_expr = ExpService::montar(26, // {$1}, {$2} anos 
                                               $aux_data->format(DMService::obterMask('dm_format_data', 'D', false)),
                                               $aux_diff->format('%Y'));
            else
                $aux_expr = 'Erro';

            if ($aux_data->format('%m-%d') == $aux_hoje->format('%m-%d'))
                $aux_expr = "<span style='white-space: nowrap'><i class='fas fa-birthday-cake' aria-hidden=''true''></i> $aux_expr</span>";

            return $aux_expr;

        });

        $column_data_contrato_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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
        });

        $column_ativo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            return DMService::obterMask('dm_sim_nao', $value);

        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_path_foto_transformed);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento_transformed);
        $this->datagrid->addColumn($column_crm);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_data_contrato_transformed);
        $this->datagrid->addColumn($column_medicos_pf_especialidades_especialidades_to_string);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_id);

        $action_onEdit = new TDataGridAction(array('MedicosPfForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('MedicosPfList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de consultórios de especialidades");
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

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['MedicosPfForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['MedicosPfList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['MedicosPfList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['MedicosPfList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['MedicosPfList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['MedicosPfList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['MedicosPfList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['MedicosPfList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($button_cadastrar);
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
            $container->add(TBreadCrumb::create(["Cadastros","Consultórios de especialidades"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new MedicosPf($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
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
            $page->setProperty('page-name', 'MedicosPfListSearch');
            $page->setProperty('page_name', 'MedicosPfListSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

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

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if (isset($data->crm) AND ( (is_scalar($data->crm) AND $data->crm !== '') OR (is_array($data->crm) AND (!empty($data->crm)) )) )
        {

            $filters[] = new TFilter('crm', 'like', "%{$data->crm}%");// create the filter 
        }

        if (isset($data->data_nascimento) AND ( (is_scalar($data->data_nascimento) AND $data->data_nascimento !== '') OR (is_array($data->data_nascimento) AND (!empty($data->data_nascimento)) )) )
        {

            $filters[] = new TFilter('data_nascimento', '=', $data->data_nascimento);// create the filter 
        }

        if (isset($data->cpf) AND ( (is_scalar($data->cpf) AND $data->cpf !== '') OR (is_array($data->cpf) AND (!empty($data->cpf)) )) )
        {

            $filters[] = new TFilter('cpf', 'like', "%{$data->cpf}%");// create the filter 
        }

        if (isset($data->rg) AND ( (is_scalar($data->rg) AND $data->rg !== '') OR (is_array($data->rg) AND (!empty($data->rg)) )) )
        {

            $filters[] = new TFilter('rg', 'like', "%{$data->rg}%");// create the filter 
        }

        if (isset($data->data_contrato) AND ( (is_scalar($data->data_contrato) AND $data->data_contrato !== '') OR (is_array($data->data_contrato) AND (!empty($data->data_contrato)) )) )
        {

            $filters[] = new TFilter('data_contrato', '=', $data->data_contrato);// create the filter 
        }

        if (isset($data->estado_civil) AND ( (is_scalar($data->estado_civil) AND $data->estado_civil !== '') OR (is_array($data->estado_civil) AND (!empty($data->estado_civil)) )) )
        {

            $filters[] = new TFilter('estado_civil', '=', $data->estado_civil);// create the filter 
        }

        if (isset($data->sexo) AND ( (is_scalar($data->sexo) AND $data->sexo !== '') OR (is_array($data->sexo) AND (!empty($data->sexo)) )) )
        {

            $filters[] = new TFilter('sexo', '=', $data->sexo);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->data_encerramento_contrato_inicio) AND ( (is_scalar($data->data_encerramento_contrato_inicio) AND $data->data_encerramento_contrato_inicio !== '') OR (is_array($data->data_encerramento_contrato_inicio) AND (!empty($data->data_encerramento_contrato_inicio)) )) )
        {

            $filters[] = new TFilter('data_encerramento_contrato', '>=', $data->data_encerramento_contrato_inicio);// create the filter 
        }

        if (isset($data->data_encerramento_contrato_fim) AND ( (is_scalar($data->data_encerramento_contrato_fim) AND $data->data_encerramento_contrato_fim !== '') OR (is_array($data->data_encerramento_contrato_fim) AND (!empty($data->data_encerramento_contrato_fim)) )) )
        {

            $filters[] = new TFilter('data_encerramento_contrato', '<=', $data->data_encerramento_contrato_fim);// create the filter 
        }

        if (isset($data->especialidades) AND ( (is_scalar($data->especialidades) AND $data->especialidades !== '') OR (is_array($data->especialidades) AND (!empty($data->especialidades)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT medicos_pf_id FROM medicos_pf_especialidades WHERE especialidades_id = '{$data->especialidades}')");// create the filter 
        }

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

            // creates a repository for MedicosPf
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
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
                if($countFilters > 0) {
                    $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
                }
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

        $object = new MedicosPf($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

