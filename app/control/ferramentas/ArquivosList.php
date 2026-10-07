<?php

class ArquivosList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'Arquivos';
    private static $primaryKey = 'id';
    private static $formName = 'form_ArquivosList';
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
        $this->form->setFormTitle("");
        $this->limit = 0;


        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

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

        $column_nome_arquivo = new TDataGridColumn('nome_arquivo', "Nome Arquivo", 'left');
        $column_tipos_documentacoes_tipo_documento = new TDataGridColumn('tipos_documentacoes->tipo_documento', "Tipo Documento", 'left');
        $column_documentacoes_descricao = new TDataGridColumn('documentacoes->descricao', "Documento", 'left');
        $column_ano_base_transformed = new TDataGridColumn('ano_base', "Ano Base", 'left');
        $column_data_emissao = new TDataGridColumn('data_emissao', "Data emissão", 'left');
        $column_arquivos_status_nome_transformed = new TDataGridColumn('arquivos_status->nome', "Status", 'left');

        $column_ano_base_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_arquivos_status_nome_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if (empty($object->arquivos_status)) {
                return "<span class='label label-default'>Status não definido</span>";
            }

            $nome = $object->arquivos_status->nome;
            $cor  = $object->arquivos_status->cor ?? '#999';

            return "<span class='label text-white' style='background-color: {$cor}'>{$nome}</span>";
        });        

        $this->datagrid->addColumn($column_nome_arquivo);
        $this->datagrid->addColumn($column_tipos_documentacoes_tipo_documento);
        $this->datagrid->addColumn($column_documentacoes_descricao);
        $this->datagrid->addColumn($column_ano_base_transformed);
        $this->datagrid->addColumn($column_data_emissao);
        $this->datagrid->addColumn($column_arquivos_status_nome_transformed);

        $action_onVerAquivos = new TDataGridAction(array('ArquivosList', 'onVerAquivos'));
        $action_onVerAquivos->setUseButton(false);
        $action_onVerAquivos->setButtonClass('btn btn-default btn-sm');
        $action_onVerAquivos->setLabel("Visualizar");
        $action_onVerAquivos->setImage('far:eye #475ECA');
        $action_onVerAquivos->setField(self::$primaryKey);

        $action_onVerAquivos->setParameter('arquivo_id', '{id}');

        $this->datagrid->addAction($action_onVerAquivos);

        $action_onDelete = new TDataGridAction(array('ArquivosList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $action_onDelete->setDisplayCondition('ArquivosList::OnEsconderCancelar');

        $this->datagrid->addAction($action_onDelete);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de Documentos Gerados");
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

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ArquivosList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $head_left_actions->add($button_atualizar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Documentos Gerados"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onVerAquivos($param = null) 
    {
        try 
        {
            if($param['arquivo_id']) {
                TSession::setValue('arquivo_id', $param['arquivo_id']);
            }

            $loadPageParam = $param;

            TApplication::loadPage('ArquivosCooperadoList', 'onShow', $loadPageParam); 

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param['key'];

                TTransaction::open(self::$database);

                $arquivo = new Arquivos($key);
                $arquivo->arquivos_status_id = 3; // Cancelado
                $arquivo->store();

                $criteria = new TCriteria;
                $criteria->add(new TFilter('arquivos_id', '=', $key));

                $repository = new TRepository('ArquivosCooperado');
                $cooperados = $repository->load($criteria);

                if ($cooperados)
                {
                    foreach ($cooperados as $coop)
                    {
                        $coop->arquivos_status_id = 3;
                        $coop->store();
                    }
                }

                TTransaction::close();

                $this->onReload($param);

                new TMessage('info', 'Arquivo cancelado com sucesso');
            }
            catch (Exception $e)
            {
                TTransaction::rollback();
                new TMessage('error', $e->getMessage());
            }
        }
        else
        {
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param);
            $action->setParameter('delete', 1);

            new TQuestion('Deseja realmente cancelar este arquivo e todos os registros vinculados?', $action);
        }
/*
                $object = new Arquivos($key, FALSE); 
*/

    }
    public static function OnEsconderCancelar($object)
    {
       try 
        {
            if (!$object) {
                return false;
            }

            if ($object->arquivos_status_id != 1) {
                return false;
            }

            TTransaction::open(self::$database);

            $criteria = new TCriteria;
            $criteria->add(new TFilter('arquivos_id', '=', $object->id));

            $repository = new TRepository('ArquivosCooperado');
            $cooperados = $repository->load($criteria);

            $todosFinalizados = true;

            if ($cooperados)
            {
                foreach ($cooperados as $coop)
                {
                    if ($coop->arquivos_status_id == 1)
                    {
                        $todosFinalizados = false;
                        break;
                    }
                }
            }

            if ($todosFinalizados)
            {
                $arquivo = new Arquivos($object->id);
                $arquivo->arquivos_status_id = 2; // Concluído
                $arquivo->store();

                TTransaction::close();
                return false; // não mostra botão mais
            }

            TTransaction::close();

            return true; // ainda tem pendente, mostra botão
        }
        catch (Exception $e) 
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
            return false;
        }
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

            // creates a repository for Arquivos
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

        $object = new Arquivos($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

