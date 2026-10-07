<?php

class ArquivosCooperadoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'ArquivosCooperado';
    private static $primaryKey = 'id';
    private static $formName = 'form_ArquivosCooperadoList';
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
        $this->form->setFormTitle("Listagem de Documentos por Cooperado");
        $this->limit = 0;

        $criteria_cooperado_id = new TCriteria();
        $criteria_arquivos_status_id = new TCriteria();

        $cooperado_id = new TDBCombo('cooperado_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperado_id );
        $cooperado_crm = new TEntry('cooperado_crm');
        $arquivos_status_id = new TDBCombo('arquivos_status_id', 'databaserede', 'ArquivosStatus', 'id', '{nome}','nome asc' , $criteria_arquivos_status_id );


        $arquivos_status_id->setValue(1);
        $cooperado_id->enableSearch();
        $arquivos_status_id->enableSearch();

        $cooperado_id->setSize('100%');
        $cooperado_crm->setSize('100%');
        $arquivos_status_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cooperado:", null, '14px', null, '100%'),$cooperado_id],[new TLabel("CRM:", null, '14px', null, '100%'),$cooperado_crm],[new TLabel("Status:", null, '14px', null, '100%'),$arquivos_status_id]);
        $row1->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

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

        $filterVar = TSession::getValue('arquivo_id');;
        $this->filter_criteria->add(new TFilter('arquivos_id', '=', $filterVar));

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_cooperado_nome = new TDataGridColumn('cooperado->nome', "Cooperado", 'left');
        $column_cooperado_crm = new TDataGridColumn('cooperado->crm', "CRM", 'left');
        $column_arquivos_nome_arquivo = new TDataGridColumn('arquivos->nome_arquivo', "Arquivo", 'left');
        $column_arquivos_status_nome_transformed = new TDataGridColumn('arquivos_status->nome', "Status", 'left');

        $column_arquivos_status_nome_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if (empty($object->arquivos_status)) {
                return "<span class='label label-default'>Status não definido</span>";
            }

            $nome = $object->arquivos_status->nome;
            $cor  = $object->arquivos_status->cor ?? '#999';

            return "<span class='label text-white' style='background-color: {$cor}'>{$nome}</span>";
        });        

        $this->builder_datagrid_check_all = new TCheckButton('builder_datagrid_check_all');
        $this->builder_datagrid_check_all->setIndexValue('on');
        $this->builder_datagrid_check_all->onclick = "Builder.checkAll(this)";
        $this->builder_datagrid_check_all->style = 'cursor:pointer';
        $this->builder_datagrid_check_all->setProperty('class', 'filled-in');
        $this->builder_datagrid_check_all->id = 'builder_datagrid_check_all';

        $label = new TLabel('');
        $label->style = 'margin:0';
        $label->class = 'checklist-label';
        $this->builder_datagrid_check_all->after($label);
        $label->for = 'builder_datagrid_check_all';

        $this->builder_datagrid_check = $this->datagrid->addColumn( new TDataGridColumn('builder_datagrid_check', $this->builder_datagrid_check_all, 'center',  '1%') );

        $this->datagrid->addColumn($column_cooperado_nome);
        $this->datagrid->addColumn($column_cooperado_crm);
        $this->datagrid->addColumn($column_arquivos_nome_arquivo);
        $this->datagrid->addColumn($column_arquivos_status_nome_transformed);

        $action_onVisualizar = new TDataGridAction(array('ArquivosCooperadoList', 'onVisualizar'));
        $action_onVisualizar->setUseButton(false);
        $action_onVisualizar->setButtonClass('btn btn-default btn-sm');
        $action_onVisualizar->setLabel("");
        $action_onVisualizar->setImage('fas:print #03A9F4');
        $action_onVisualizar->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onVisualizar);

        $action_onCooperado = new TDataGridAction(array('ArquivosCooperadoList', 'onCooperado'));
        $action_onCooperado->setUseButton(false);
        $action_onCooperado->setButtonClass('btn btn-default btn-sm');
        $action_onCooperado->setLabel("");
        $action_onCooperado->setImage('fas:user #08810D');
        $action_onCooperado->setField(self::$primaryKey);
        $action_onCooperado->setDisplayCondition('ArquivosCooperadoList::onteste');

        $this->datagrid->addAction($action_onCooperado);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        $this->pageNavigation->keepLastPagination(__CLASS__);

        $panel = new TPanelGroup("Listagem de Documentos por Cooperado");
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

        $button_voltar = new TButton('button_button_voltar');
        $button_voltar->setAction(new TAction(['ArquivosList', 'onShow']), "Voltar");
        $button_voltar->addStyleClass('btn-default');
        $button_voltar->setImage('fas:arrow-left #000000');

        $this->datagrid_form->addField($button_voltar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['ArquivosCooperadoList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['ArquivosCooperadoList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ArquivosCooperadoList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_remover = new TButton('button_button_remover');
        $button_remover->setAction(new TAction(['ArquivosCooperadoList', 'onRemover']), "Remover");
        $button_remover->addStyleClass('btn-danger');
        $button_remover->setImage('far:trash-alt #FFFFFF');

        $this->datagrid_form->addField($button_remover);

        $button_salvar = new TButton('button_button_salvar');
        $button_salvar->setAction(new TAction(['ArquivosCooperadoList', 'onSalvarDocumento']), "Salvar");
        $button_salvar->addStyleClass('btn-success');
        $button_salvar->setImage('fas:file-signature #FFFFFF');

        $this->datagrid_form->addField($button_salvar);

        $head_left_actions->add($button_voltar);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_remover);
        $head_left_actions->add($button_salvar);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Arquivos cooperados"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onVisualizar($param = null) 
    {
        try
        {
            TTransaction::open(self::$database);

            if (empty($param['id'])) {
                throw new Exception('Registro do arquivo cooperado não informado');
            }

            $arquivoCoop = new ArquivosCooperado($param['id']);
            $arquivo     = new Arquivos($arquivoCoop->arquivos_id);

            $mpdf = $this->gerarRelatorioExtrato($param['id']);

            $tmp = 'app/output/preview_extrato_'.$arquivo->id.'.pdf';
            $mpdf->Output($tmp,'F');

            TTransaction::close();

            $win = TWindow::create('Pré-visualização do Extrato',0.9,0.9);
            $iframe = new TElement('iframe');
            $iframe->src = $tmp;
            $iframe->style = 'width:100%;height:100%';
            $iframe->frameborder = '0';
            $win->add($iframe);
            $win->show();
            //</autoCode>
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onCooperado($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            if (empty($param['id'])) {
                throw new Exception('Registro não informado');
            }

            $arquivoCoop = new ArquivosCooperado($param['id']);
            $cooperado = new Cooperados($arquivoCoop->cooperado_id);

            TSession::setValue('cooperado_form_origem', 'ArquivosCooperadoList');

            TApplication::loadPage(
                'CooperadosForm',
                'onEdit',
                ['key' => $cooperado->id]
            );

            TTransaction::close();
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onteste($object)
    {
        try 
        {
            if($object)
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
            $page->setProperty('page-name', 'ArquivosCooperadoListSearch');
            $page->setProperty('page_name', 'ArquivosCooperadoListSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

            $style = new TStyle('right-panel > .container-part[page-name=ArquivosCooperadoListSearch]');
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

        if(!empty($this->form))
        {
            $this->form->clear();
        }

        if(!empty($this->datagrid_form))
        {
            $this->datagrid_form->clear();
        }

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }
    public function onRemover($param = null) 
    {
        $ids = TSession::getValue(__CLASS__.'builder_datagrid_check') ?? [];

        if (empty($ids)) {
            new TMessage('warning', 'Nenhum registro selecionado');
            return;
        }

        $action = new TAction([$this, 'confirmarRemocao']);
        new TQuestion('Confirma a exclusão dos registros selecionados?', $action);
            //</autoCode>

    }
    public function onSalvarDocumento($param = null) 
    {
       $ids = TSession::getValue(__CLASS__.'builder_datagrid_check') ?? [];

        if (empty($ids)) {
            new TMessage('warning', 'Nenhum registro selecionado');
            return;
        }

        $action = new TAction([$this, 'confirmarSalvarDocumento']);
        new TQuestion('Confirma a geração definitiva dos documentos?', $action);
            //</autoCode>

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

        if (isset($data->cooperado_id) AND ( (is_scalar($data->cooperado_id) AND $data->cooperado_id !== '') OR (is_array($data->cooperado_id) AND (!empty($data->cooperado_id)) )) )
        {

            $filters[] = new TFilter('cooperado_id', '=', $data->cooperado_id);// create the filter 
        }

        if (isset($data->cooperado_crm) AND ( (is_scalar($data->cooperado_crm) AND $data->cooperado_crm !== '') OR (is_array($data->cooperado_crm) AND (!empty($data->cooperado_crm)) )) )
        {

            $filters[] = new TFilter('cooperado_id', 'in', "(SELECT id FROM cooperados WHERE crm = '{$data->cooperado_crm}')");// create the filter 
        }

        if (isset($data->arquivos_status_id) AND ( (is_scalar($data->arquivos_status_id) AND $data->arquivos_status_id !== '') OR (is_array($data->arquivos_status_id) AND (!empty($data->arquivos_status_id)) )) )
        {

            $filters[] = new TFilter('arquivos_status_id', '=', $data->arquivos_status_id);// create the filter 
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

            // creates a repository for ArquivosCooperado
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = '(SELECT cooperados.nome FROM cooperados WHERE cooperados.id = arquivos_cooperado.cooperado_id)';    
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
            $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

/*
            //</blockLine><btnShowCurtainFiltersAutoCode>
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }
            //</blockLine></btnShowCurtainFiltersAutoCode>
*/
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                if ($countFilters > 0) {
                    $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    $check = new TCheckGroup('builder_datagrid_check');
                    $check->addItems([$object->id => '']);
                    $check->getButtons()[$object->id]->onclick = 'event.stopPropagation()';

                    if(!$this->datagrid_form->getField('builder_datagrid_check[]'))
                    {
                        $this->datagrid_form->setFields([$check]);
                    }

                    $check->setChangeAction(new TAction([$this, 'builderSelectCheck']));
                    $object->builder_datagrid_check = $check;

                    if(!empty($session_checks[$object->id]))
                    {
                        $object->builder_datagrid_check->setValue([$object->id=>$object->id]);
                    }

                    if ($object->arquivos_status_id != 1)
                    {
                        $object->builder_datagrid_check = '';
                        unset($session_checks[$object->id]);
                    }
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

        if (!TSession::getValue(__CLASS__.'_filters'))
        {
            $defaultStatus = 1;

            $data = new stdClass;
            $data->arquivos_status_id = $defaultStatus;

            $this->form->setData($data);

            $filters = [];
            $filters[] = new TFilter('arquivos_status_id', '=', $defaultStatus);

            TSession::setValue(__CLASS__.'_filter_data', $data);
            TSession::setValue(__CLASS__.'_filters', $filters);
        }
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

    public static function builderSelectCheck($param)
    {
        $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

        $valueOn = null;
        if(!empty($param['_field_data_json']))
        {
            $obj = json_decode($param['_field_data_json']);
            if($obj)
            {
                $valueOn = $obj->valueOn;
            }
        }

        $key = empty($param['key']) ? $valueOn : $param['key'];

        if(empty($param['builder_datagrid_check']) && !empty($session_checks[$key]))
        {
            unset($session_checks[$key]);
        }
        elseif(!empty($param['builder_datagrid_check']) && !in_array($key, $param['builder_datagrid_check']) && !empty($session_checks[$key]))
        {
            unset($session_checks[$key]);
        }
        elseif(!empty($param['builder_datagrid_check']) && in_array($key, $param['builder_datagrid_check']))
        {
            $session_checks[$key] = $key;
        }

        TSession::setValue(__CLASS__.'builder_datagrid_check', $session_checks);
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new ArquivosCooperado($id);

        $session_checks = TSession::getValue(__CLASS__.'builder_datagrid_check');

        $check = new TCheckGroup('builder_datagrid_check');
        $check->addItems([$object->id => '']);
        $check->getButtons()[$object->id]->onclick = 'event.stopPropagation()';

        if(!$list->datagrid_form->getField('builder_datagrid_check[]'))
        {
            $list->datagrid_form->setFields([$check]);
        }

        $check->setChangeAction(new TAction([$list, 'builderSelectCheck']));
        $object->builder_datagrid_check = $check;

        if(!empty($session_checks[$object->id]))
        {
            $object->builder_datagrid_check->setValue([$object->id=>$object->id]);
        }

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    public function confirmarRemocao($param)
    {
        try {
            TTransaction::open(self::$database);

            $ids = TSession::getValue(__CLASS__.'builder_datagrid_check') ?? [];

            foreach ($ids as $id) {
                $obj = new ArquivosCooperado($id);
                $obj->arquivos_status_id = 3;
                $obj->store();
            }

            TSession::setValue(__CLASS__.'builder_datagrid_check', []);
            TTransaction::close();

            new TMessage('info', 'Registros removidos com sucesso');

            $this->onReload();
        }
        catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function confirmarSalvarDocumento($param)
    {
        try
        {
            TTransaction::open(self::$database);

            $ids = TSession::getValue(__CLASS__.'builder_datagrid_check') ?? [];

            if (empty($ids)) {
                throw new Exception('Nenhum registro selecionado');
            }

            foreach ($ids as $id)
            {
                $arquivoCoop = new ArquivosCooperado($id);
                $arquivo     = new Arquivos($arquivoCoop->arquivos_id);
                $cooperadoId = $arquivoCoop->cooperado_id;

                $baseDir = 'arquivos/docs_cooperados/' . $cooperadoId;

                if (!is_dir($baseDir)) {
                    mkdir($baseDir, 0775, true);
                }

                $fileName = $arquivo->nome_arquivo . '-' . $arquivo->ano_base . '.pdf';

                $finalPath = $baseDir . '/' . $fileName;

                $tmpFile = 'app/output/tmp_' . uniqid() . '.pdf';

                $mpdf = $this->gerarRelatorioExtrato($id);
                $mpdf->Output($tmpFile, 'F');

                if (!file_exists($tmpFile)) {
                    throw new Exception('Erro ao gerar arquivo temporário');
                }

                if (!rename($tmpFile, $finalPath)) {
                    unlink($tmpFile);
                    throw new Exception('Erro ao mover arquivo para destino final');
                }

                $doc = new CooperadosDocumentacoes;
                $doc->cooperados_id          = $cooperadoId;
                $doc->tipos_documentacoes_id = $arquivo->tipos_documentacoes_id;
                $doc->documentacoes_id       = $arquivo->documentacoes_id;
                $doc->emissao                = date('Y-m-d');
                $doc->entregue               = 'Sim';
                $doc->ativo                  = 'Sim';
                $doc->path_arquivo           = $finalPath; // caminho relativo
                $doc->store();

                $arquivoCoop->arquivos_status_id = 2;
                $arquivoCoop->store();
            }

            TSession::setValue(__CLASS__.'builder_datagrid_check', []);

            TTransaction::close();

            new TMessage('info', 'Documentos gerados com sucesso');

            $this->onReload();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    private function gerarRelatorioExtrato($arquivoCoopId)
    {
        $arquivoCoop = new ArquivosCooperado($arquivoCoopId);
        $arquivo     = new Arquivos($arquivoCoop->arquivos_id);
        $cooperado   = new Cooperados($arquivoCoop->cooperado_id);

        $conn = TTransaction::get();

        $cooperado_id = (int) $cooperado->id;
        $data = $arquivo->ano_base . '-12-31';
        $data_anterior = ($arquivo->ano_base - 1) . '-12-31';

        // Saldo anterior conta
        $sql_saldo_anterior_conta = "
            SELECT SUM(
                CASE
                    WHEN cc.dm_capital_social IN (105,106,202,203)
                        THEN cc.valor * -1
                    ELSE cc.valor
                END
            ) AS valor_total
            FROM cooperados_capital cc
            JOIN dominio_valor dv ON dv.valor = cc.dm_capital_social
            WHERE dv.dominio_id = 22
            AND cc.dm_capital_social IN (100,101,102,103,104,105,106)
            AND cc.cooperados_id = :cooperado_id
            AND cc.data_aquisicao <= :data
        ";

        $stmt = $conn->prepare($sql_saldo_anterior_conta);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data_anterior
        ]);
        $saldo_anterior_conta = (float) $stmt->fetch(PDO::FETCH_ASSOC)['valor_total'];

        // Movimentações conta
        $sql_capital_social_conta = "
            SELECT
                dv.mascara,
                MAX(cc.data_aquisicao) AS data_mov,
                SUM(
                    CASE
                        WHEN cc.dm_capital_social IN (105,106,202,203)
                            THEN cc.valor * -1
                        ELSE cc.valor
                    END
                ) AS valor
            FROM cooperados_capital cc
            JOIN dominio_valor dv ON dv.valor = cc.dm_capital_social
            WHERE dv.dominio_id = 22
            AND cc.dm_capital_social IN (100,101,102,103,104,105,106)
            AND cc.cooperados_id = :cooperado_id
            AND cc.data_aquisicao > :data_anterior 
            AND cc.data_aquisicao <= :data
            GROUP BY dv.mascara
        ";

        $stmt = $conn->prepare($sql_capital_social_conta);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data,
            ':data_anterior'=>$data_anterior
        ]);

        $linhasContaMov = '';
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $linhasContaMov .= "
                <tr class='linha-branca'>
                    <td>{$row['mascara']}</td>
                    <td class='data'>".date('d/m/Y', strtotime($row['data_mov']))."</td>
                    <td class='valor'>R$ ".number_format($row['valor'],2,',','.')."</td>
                </tr>
            ";
        }

        // Saldo atualizado conta
        $stmt = $conn->prepare($sql_saldo_anterior_conta);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data
        ]);
        $saldo_atualizado_conta = (float) $stmt->fetch(PDO::FETCH_ASSOC)['valor_total'];

        // Saldo anterior sub
        $sql_saldo_anterior_sub = "
            SELECT SUM(
                CASE
                    WHEN cc.dm_capital_social IN (105,106,202,203)
                        THEN cc.valor * -1
                    ELSE cc.valor
                END
            ) AS valor_total
            FROM cooperados_capital cc
            JOIN dominio_valor dv ON dv.valor = cc.dm_capital_social
            WHERE dv.dominio_id = 22
            AND cc.dm_capital_social IN (200,201,202,203)
            AND cc.cooperados_id = :cooperado_id
            AND cc.data_aquisicao <= :data
        ";

        $stmt = $conn->prepare($sql_saldo_anterior_sub);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data_anterior
        ]);
        $saldo_anterior_sub = (float) $stmt->fetch(PDO::FETCH_ASSOC)['valor_total'];

        // Movimentações sub
        $sql_capital_social_sub = "
            SELECT
                dv.mascara,
                MAX(cc.data_aquisicao) AS data_mov,
                SUM(
                    CASE
                        WHEN cc.dm_capital_social IN (105,106,202,203)
                            THEN cc.valor * -1
                        ELSE cc.valor
                    END
                ) AS valor
            FROM cooperados_capital cc
            JOIN dominio_valor dv ON dv.valor = cc.dm_capital_social
            WHERE dv.dominio_id = 22
            AND cc.dm_capital_social IN (200,201,202,203)
            AND cc.cooperados_id = :cooperado_id
            AND cc.data_aquisicao > :data_anterior 
            AND cc.data_aquisicao <= :data
            GROUP BY dv.mascara
        ";

        $stmt = $conn->prepare($sql_capital_social_sub);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data,
            ':data_anterior'=>$data_anterior
        ]);

        $linhasSubContaMov = '';
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $linhasSubContaMov .= "
                <tr class='linha-branca'>
                    <td>{$row['mascara']}</td>
                    <td class='data'>".date('d/m/Y', strtotime($row['data_mov']))."</td>
                    <td class='valor'>R$ ".number_format($row['valor'],2,',','.')."</td>
                </tr>
            ";
        }

        // Saldo atualizado sub
        $stmt = $conn->prepare($sql_saldo_anterior_sub);
        $stmt->execute([
            ':cooperado_id'=>$cooperado_id,
            ':data'=>$data
        ]);
        $saldo_atualizado_sub = (float) $stmt->fetch(PDO::FETCH_ASSOC)['valor_total'];

        $saldo_total = $saldo_atualizado_sub + $saldo_atualizado_conta;

        $html = file_get_contents('app/resources/extrato_cota_capital.html');
        $dataAtualizacao = '31/12/'.$arquivo->ano_base;

        $linhaConta = "
            <tr class='linha-cinza'>
                <td>SALDO ANTERIOR</td>
                <td class='data'>31/12/".($arquivo->ano_base-1)."</td>
                <td class='valor'>R$ ".number_format($saldo_anterior_conta,2,',','.')."</td>
            </tr>
            {$linhasContaMov}
            <tr class='linha-cinza'>
                <td>SALDO ATUALIZADO</td>
                <td class='data'>{$dataAtualizacao}</td>
                <td class='valor'>R$ ".number_format($saldo_atualizado_conta,2,',','.')."</td>
            </tr>
        ";

        $linhaSubconta = "
            <tr class='linha-cinza'>
                <td>SALDO ANTERIOR</td>
                <td class='data'>31/12/".($arquivo->ano_base-1)."</td>
                <td class='valor'>R$ ".number_format($saldo_anterior_sub,2,',','.')."</td>
            </tr>
            {$linhasSubContaMov}
            <tr class='linha-cinza'>
                <td>SALDO ATUALIZADO</td>
                <td class='data'>{$dataAtualizacao}</td>
                <td class='valor'>R$ ".number_format($saldo_atualizado_sub,2,',','.')."</td>
            </tr>
        ";

        $html = str_replace(
            ['{$NOME_COOPERADO}','{$CRM}','{$DATA_ATUALIZACAO}','{$LINHAS_CONTA}','{$LINHAS_SUBCONTA}','{$SALDO_TOTAL}'],
            [
                strtoupper($cooperado->nome),
                $cooperado->crm,
                $dataAtualizacao,
                $linhaConta,
                $linhaSubconta,
                'R$ '.number_format($saldo_total,2,',','.')
            ],
            $html
        );

        $meses = [1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
                7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro'];

        $hoje = new DateTime();
        $data_extenso = sprintf(
            'Joaçaba/SC, %d de %s de %d',
            $hoje->format('d'),
            $meses[(int)$hoje->format('m')],
            $hoje->format('Y')
        );

        $mpdf = new \Mpdf\Mpdf([
            'mode'=>'utf-8',
            'format'=>'A4',
            'margin_top'=>40,
            'margin_bottom'=>35,
            'margin_left'=>15,
            'margin_right'=>15
        ]);

        $imgHeader = realpath('app/lib/include/imagens/cabecalho_relatorio.png');
        $imgFooter = realpath('app/lib/include/imagens/rodape_relatorio.png');

        $mpdf->SetHTMLHeader("
            <div class='header-cooperado'>
                <img src='{$imgHeader}' class='imagem-cabecalho-cooperado'>
                <div class='data-extenso-header'>
                    {$data_extenso}
                </div>
            </div>
        ");

        $mpdf->SetHTMLFooter("<img src='{$imgFooter}' style='width:100%'>");

        $mpdf->WriteHTML($html);

        return $mpdf;
    }

}

