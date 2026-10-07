<?php

class CredenciadosMovimentacoesList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosMovimentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosMovimentacoesList';
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
        $this->form->setFormTitle("");
        $this->limit = 20;


        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        if(!empty($param['id_credenciado']))
        {
            TSession::setValue(__CLASS__.'load_filter_credenciados_id', $param['id_credenciado']);
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_credenciados_id');
        $this->filter_criteria->add(new TFilter('credenciados_id', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_usuario = new TDataGridColumn('usuario', "Usuário ", 'left');
        $column_data_registro_transformed = new TDataGridColumn('data_registro', "Data", 'left');
        $column_assunto = new TDataGridColumn('assunto', "Assunto", 'left');
        $column_descricao_transformed = new TDataGridColumn('descricao', "Descrição", 'left');

        $column_data_registro_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_descricao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $limite = 150;

            $textoCurto = mb_substr($value, 0, $limite, 'UTF-8') . '...';

            $textoCurtoSeguro = htmlspecialchars($textoCurto, ENT_QUOTES, 'UTF-8');

            return $textoCurtoSeguro;

        });        

        if(!empty($param['id_credenciado']))
        {
           TSession::setValue('id_credenciado', $param['id_credenciado']);
        }

        $this->datagrid->addColumn($column_usuario);
        $this->datagrid->addColumn($column_data_registro_transformed);
        $this->datagrid->addColumn($column_assunto);
        $this->datagrid->addColumn($column_descricao_transformed);

        $action_onVisualizar = new TDataGridAction(array('CredenciadosMovimentacoesList', 'onVisualizar'));
        $action_onVisualizar->setUseButton(false);
        $action_onVisualizar->setButtonClass('btn btn-default btn-sm');
        $action_onVisualizar->setLabel("Visualizar");
        $action_onVisualizar->setImage('fas:eye #0D6B95');
        $action_onVisualizar->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onVisualizar);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
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
        $button_cadastrar->setAction(new TAction(['CredenciadosMovimentacoesForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $head_left_actions->add($button_cadastrar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Credenciados movimentações"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onVisualizar($param = null) 
    {
        try 
        {
            if (empty($param['id'])) {
                throw new Exception('Registro não informado.');
            }

            TTransaction::open(self::$database);

            $object = new CredenciadosMovimentacoes($param['id']);

            $dataRegistro = '';

            if (!empty($object->data_registro)) {
                $dataRegistro = (new DateTime($object->data_registro))->format('d/m/Y H:i');
            }

            $descricao = nl2br(htmlspecialchars((string) $object->descricao));
            $usuario   = htmlspecialchars((string) $object->usuario);
            $assunto   = htmlspecialchars((string) $object->assunto);

            TTransaction::close();

            $window = TWindow::create('Detalhes da movimentação', 0.55, 0.55);

            $html = new TElement('div');
            $html->style = '
                padding: 20px;
                font-family: Arial, sans-serif;
                color: #333;
            ';

            $html->add("
                <div style='
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 12px;
                    margin-bottom: 16px;
                '>
                    <div style='
                        background: #f8f9fa;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 12px;
                    '>
                        <div style='font-size: 12px; color: #777; margin-bottom: 4px;'>Data</div>
                        <div style='font-weight: bold;'>{$dataRegistro}</div>
                    </div>

                    <div style='
                        background: #f8f9fa;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 12px;
                    '>
                        <div style='font-size: 12px; color: #777; margin-bottom: 4px;'>Usuário</div>
                        <div style='font-weight: bold;'>{$usuario}</div>
                    </div>
                </div>

                <div style='
                        background: #f8f9fa;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 12px;
                        margin-bottom: 16px;
                    '>
                        <div style='font-size: 12px; color: #777; margin-bottom: 4px;'>Assunto</div>
                        <div style='font-weight: bold;'>{$assunto}</div>
                </div>

                <div style='
                        background: #f8f9fa;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 12px;
                '>
                    <div style='font-size: 12px; color: #777; margin-bottom: 8px;'>Descrição</div>
                    <div style='line-height: 1.5; white-space: normal;'>{$descricao}</div>
                </div>
            ");

            $window->add($html);
            $window->show();
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
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

            // creates a repository for CredenciadosMovimentacoes
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

        if(!empty($param['id_credenciado']))
        {
           TSession::setValue('id_credenciado', $param['id_credenciado']);
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

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new CredenciadosMovimentacoes($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

