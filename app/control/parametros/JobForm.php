<?php

class JobForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Job';
    private static $primaryKey = 'id';
    private static $formName = 'form_JobForm';

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
        $this->form->setFormTitle("Cadastro de job");

        $criteria_dm_situacao = new TCriteria();
        $criteria_dm_tipo = new TCriteria();
        $criteria_periodo = new TCriteria();
        $criteria_dm_tipo_req = new TCriteria();

        $filterVar = "dm_job_situacao";
        $criteria_dm_situacao->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "dm_job_tipo";
        $criteria_dm_tipo->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "dm_job_periodo";
        $criteria_periodo->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "dm_job_tipo_req";
        $criteria_dm_tipo_req->add(new TFilter('codigo', '=', $filterVar)); 

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $dm_situacao = new TDBCombo('dm_situacao', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_dm_situacao );
        $dm_tipo = new TDBCombo('dm_tipo', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_dm_tipo );
        $intervalo = new TSpinner('intervalo');
        $periodo = new TDBCombo('periodo', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_periodo );
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_termino = new TDateTime('dt_termino');
        $request = new TText('request');
        $dm_tipo_req = new TDBCombo('dm_tipo_req', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_dm_tipo_req );
        $params_req = new TMultiEntry('params_req');

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $dm_situacao->addValidation("Situação", new TRequiredValidator()); 
        $dm_tipo_req->addValidation("tipo do request", new TRequiredValidator()); 

        $id->setEditable(false);
        $nome->setMaxLength(150);
        $dm_situacao->setValue('A');
        $intervalo->setRange(1, 2000, 1);
        $dt_inicio->setMask('dd/mm/yyyy hh:ii');
        $dt_termino->setMask('dd/mm/yyyy hh:ii');

        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_termino->setDatabaseMask('yyyy-mm-dd hh:ii');

        $dm_tipo->enableSearch();
        $periodo->enableSearch();
        $dm_situacao->enableSearch();
        $dm_tipo_req->enableSearch();

        $id->setSize('100%');
        $nome->setSize('100%');
        $dm_tipo->setSize('100%');
        $periodo->setSize('100%');
        $intervalo->setSize('100%');
        $dt_inicio->setSize('100%');
        $dt_termino->setSize('100%');
        $dm_situacao->setSize('100%');
        $request->setSize('100%', 70);
        $dm_tipo_req->setSize('100%');
        $params_req->setSize('100%', 35);


        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome],[new TLabel("Situação: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_situacao]);
        $row1->layout = [' col-sm-2',' col-sm-7',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo],[new TLabel("Intervalo:", null, '14px', null, '100%'),$intervalo],[new TLabel("periodo:", null, '14px', null, '100%'),$periodo],[new TLabel("Data de início: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dt_inicio],[new TLabel("Data de término:", null, '14px', null, '100%'),$dt_termino]);
        $row2->layout = [' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Request: <font color=\"red\">*</font>", null, '14px', null, '100%'),$request]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Tipo do request: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo_req],[new TLabel("Parâmetros do request:", null, '14px', null, '100%'),$params_req]);
        $row4->layout = ['col-sm-2','col-sm-10'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['VJobList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Parâmetros","Cadastro de job"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Job(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->params_req = implode(';', $data->params_req); 

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
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

                $object = new Job($key); // instantiates the Active Record 

                if($object->params_req)
                {
                    $object->params_req = explode(';', $object->params_req);
                } 

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

