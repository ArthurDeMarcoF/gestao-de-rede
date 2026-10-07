<?php

class JobFormView extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Job';
    private static $primaryKey = 'id';
    private static $formName = 'form_JobFormView';

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
        $this->form->setFormTitle("Dados JOB");

        $criteria_dm_situacao = new TCriteria();
        $criteria_dm_tipo = new TCriteria();

        $filterVar = "job";
        $criteria_dm_situacao->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_situacao";
        $criteria_dm_situacao->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "job";
        $criteria_dm_tipo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo";
        $criteria_dm_tipo->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $dm_situacao = new TDBCombo('dm_situacao', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_situacao );
        $dm_tipo = new TDBCombo('dm_tipo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo );
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_termino = new TDateTime('dt_termino');

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $dm_situacao->addValidation("Situação", new TRequiredValidator()); 

        $id->setEditable(false);
        $nome->setMaxLength(150);
        $dm_situacao->setValue('A');
        $dm_tipo->enableSearch();
        $dm_situacao->enableSearch();

        $dt_inicio->setMask('dd/mm/yyyy hh:ii');
        $dt_termino->setMask('dd/mm/yyyy hh:ii');

        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_termino->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $nome->setSize('100%');
        $dt_inicio->setSize(150);
        $dm_tipo->setSize('100%');
        $dt_termino->setSize(150);
        $dm_situacao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Nome:", null, '14px', null, '100%'),$nome]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel("Situação:", null, '14px', null, '100%'),$dm_situacao],[new TLabel("Tipo:", null, '14px', null, '100%'),$dm_tipo],[new TLabel("Data de início:", null, '14px', null, '100%'),$dt_inicio],[new TLabel("Data de término:", null, '14px', null, '100%'),$dt_termino]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Dados JOB"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Job($key); // instantiates the Active Record 

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

    }

    public function onShow($param = null)
    {

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

