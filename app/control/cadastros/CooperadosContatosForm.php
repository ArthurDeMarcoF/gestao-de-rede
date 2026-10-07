<?php

class CooperadosContatosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosContatos';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosContatosForm';

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
        $this->form->setFormTitle("Cadastro de contatos do cooperado");

        $criteria_cooperados_id = new TCriteria();
        $criteria_tipos_contatos_id = new TCriteria();

        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $id = new THidden('id');
        $tipos_contatos_id = new TDBCombo('tipos_contatos_id', 'databaserede', 'TiposContatos', 'id', '{tipo_contato}','tipo_contato asc' , $criteria_tipos_contatos_id );
        $contato = new TEntry('contato');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $cooperados_id->addValidation("Cooperados id", new TRequiredValidator()); 
        $tipos_contatos_id->addValidation("Tipos contatos id", new TRequiredValidator()); 
        $imprime_guia_medico->addValidation("imprime no guia médico", new TRequiredValidator()); 

        $contato->setMaxLength(100);
        $imprime_guia_medico->addItems(["S"=>"Sim","N"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('N');
        $imprime_guia_medico->setUseButton();
        $cooperados_id->enableSearch();
        $tipos_contatos_id->enableSearch();

        $id->setSize(200);
        $contato->setSize('100%');
        $cooperados_id->setSize('100%');
        $imprime_guia_medico->setSize(80);
        $tipos_contatos_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cooperado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cooperados_id,$id],[new TLabel("Tipo de contato: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_contatos_id],[new TLabel("Contato:", null, '14px', null, '100%'),$contato]);
        $row1->layout = [' col-sm-5',' col-sm-3',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Imprime no guia médico?", null, '14px', null, '100%'),$imprime_guia_medico],[],[]);
        $row2->layout = [' col-sm-3',' col-sm-5',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosContatosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de contatos do cooperado"]));
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

            $object = new CooperadosContatos(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

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

                $object = new CooperadosContatos($key); // instantiates the Active Record 

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

