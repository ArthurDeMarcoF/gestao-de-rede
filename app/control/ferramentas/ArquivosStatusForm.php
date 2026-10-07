<?php

class ArquivosStatusForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'ArquivosStatus';
    private static $primaryKey = 'id';
    private static $formName = 'form_ArquivosStatusForm';

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
        $this->form->setFormTitle("Cadastro de arquivos status");


        $nome = new TEntry('nome');
        $id = new THidden('id');
        $cor = new TColor('cor');
        $ativo = new TRadioGroup('ativo');
        $descricao = new TText('descricao');

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $cor->addValidation("Cor", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 

        $nome->setMaxLength(30);
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('Sim');
        $ativo->setUseButton();
        $id->setSize(200);
        $cor->setSize(110);
        $nome->setSize('100%');
        $ativo->setSize('100%');
        $descricao->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Nome: <font color='Red'>*</font>", null, '14px', null, '100%'),$nome,$id],[new TLabel("Cor: <font color='Red'>*</font>", null, '14px', null, '100%'),$cor],[new TLabel("Ativo: <font color='Red'>*</font>", null, '14px', null, '100%'),$ativo]);
        $row1->layout = ['col-sm-6','col-sm-2',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição: <font color='Red'>*</font>", null, '14px', null, '100%'),$descricao]);
        $row2->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ArquivosStatusList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Cadastro de arquivos status"]));
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

            $object = new ArquivosStatus(); // create an empty object 

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
            TApplication::loadPage('ArquivosStatusList', 'onShow', $loadPageParam); 

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

                $object = new ArquivosStatus($key); // instantiates the Active Record 

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

