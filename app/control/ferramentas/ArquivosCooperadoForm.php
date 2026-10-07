<?php

class ArquivosCooperadoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'ArquivosCooperado';
    private static $primaryKey = 'id';
    private static $formName = 'form_ArquivosCooperadoForm';

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
        $this->form->setFormTitle("Cadastro de arquivos cooperado");

        $criteria_arquivos_status_id = new TCriteria();
        $criteria_cooperado_id = new TCriteria();
        $criteria_arquivos_id = new TCriteria();

        $id = new TEntry('id');
        $data_criacao = new TDateTime('data_criacao');
        $data_alteracao = new TDateTime('data_alteracao');
        $arquivos_status_id = new TDBCombo('arquivos_status_id', 'databaserede', 'ArquivosStatus', 'id', '{nome}','nome asc' , $criteria_arquivos_status_id );
        $cooperado_id = new TDBCombo('cooperado_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperado_id );
        $arquivos_id = new TDBCombo('arquivos_id', 'databaserede', 'Arquivos', 'id', '{nome_arquivo}','nome_arquivo asc' , $criteria_arquivos_id );

        $data_criacao->addValidation("Data criacao", new TRequiredValidator()); 
        $arquivos_status_id->addValidation("Status", new TRequiredValidator()); 
        $cooperado_id->addValidation("Cooperado", new TRequiredValidator()); 
        $arquivos_id->addValidation("Arquivo", new TRequiredValidator()); 

        $id->setEditable(false);
        $data_criacao->setMask('dd/mm/yyyy hh:ii');
        $data_alteracao->setMask('dd/mm/yyyy hh:ii');

        $data_criacao->setValue('current_timestamp');
        $data_alteracao->setValue('current_timestamp()');

        $data_criacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_alteracao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $arquivos_id->enableSearch();
        $cooperado_id->enableSearch();
        $arquivos_status_id->enableSearch();

        $id->setSize(100);
        $data_criacao->setSize(150);
        $data_alteracao->setSize(150);
        $arquivos_id->setSize('100%');
        $cooperado_id->setSize('100%');
        $arquivos_status_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Data criacao:", '#ff0000', '14px', null, '100%'),$data_criacao]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Data alteracao:", null, '14px', null, '100%'),$data_alteracao],[new TLabel("Status:", '#ff0000', '14px', null, '100%'),$arquivos_status_id]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Cooperado:", '#ff0000', '14px', null, '100%'),$cooperado_id],[new TLabel("Arquivo:", '#ff0000', '14px', null, '100%'),$arquivos_id]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ArquivosCooperadoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Cadastro de arquivos cooperado"]));
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

            $object = new ArquivosCooperado(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ArquivosCooperadoList', 'onShow', $loadPageParam); 

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

                $object = new ArquivosCooperado($key); // instantiates the Active Record 

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

