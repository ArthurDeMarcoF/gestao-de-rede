<?php

class ContratoServicoDetalheList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'databaserede';
    private static $activeRecord = 'ContratoServico';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ContratoServico';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 20;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_servico = new TDataGridColumn('servico', "Serviço", 'left');
        $column_dt_inicio_transformed = new TDataGridColumn('dt_inicio', "Início", 'left');
        $column_dt_termino_transformed = new TDataGridColumn('dt_termino', "Término", 'left');
        $column_dm_situacao_transformed = new TDataGridColumn('dm_situacao', "Situação", 'left');

        $column_dt_inicio_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if (empty($value))
                return '';

            $date = new DateTime($value);
            return $date->format(DMService::obterMask('dm_format_data', 'D', false));

        });

        $column_dt_termino_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if (empty($value))
                return '';

            $date = new DateTime($value);
            return $date->format(DMService::obterMask('dm_format_data', 'D', false));

        });

        $column_dm_situacao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            return DMService::obterMask('dm_situacao', $value);

        });        

        $this->datagrid->addColumn($column_servico);
        $this->datagrid->addColumn($column_dt_inicio_transformed);
        $this->datagrid->addColumn($column_dt_termino_transformed);
        $this->datagrid->addColumn($column_dm_situacao_transformed);

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
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Serviços do contrato"]));
        }
        $container->add($panel);

        parent::add($container);

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

            // creates a repository for ContratoServico
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["contrato_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_contrato_id', $param["contrato_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_contrato_id');
            $criteria->add(new TFilter('contrato_id', '=', $filterVar));

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
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
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

        $object = new ContratoServico($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

