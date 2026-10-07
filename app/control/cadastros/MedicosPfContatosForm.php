<?php

class MedicosPfContatosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPfContatos';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfContatosForm';

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
        $this->form->setFormTitle("Cadastro contatos de médicos PF");

        $criteria_medicos_pf_id = new TCriteria();
        $criteria_tipos_contatos_id = new TCriteria();

        $medicos_pf_id = new TDBCombo('medicos_pf_id', 'databaserede', 'MedicosPf', 'id', '{id}','id asc' , $criteria_medicos_pf_id );
        $id = new THidden('id');
        $tipos_contatos_id = new TDBCombo('tipos_contatos_id', 'databaserede', 'TiposContatos', 'id', '{tipo_contato}','tipo_contato asc' , $criteria_tipos_contatos_id );
        $contato = new TEntry('contato');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $medicos_pf_id->addValidation("Médicos PF", new TRequiredValidator()); 
        $tipos_contatos_id->addValidation("Tipos de contato", new TRequiredValidator()); 
        $imprime_guia_medico->addValidation("Imprime guia medico", new TRequiredValidator()); 

        $contato->setMaxLength(100);
        $imprime_guia_medico->addItems(["S"=>"Sim","N"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('N');
        $imprime_guia_medico->setUseButton();
        $medicos_pf_id->enableSearch();
        $tipos_contatos_id->enableSearch();

        $id->setSize(200);
        $contato->setSize('100%');
        $medicos_pf_id->setSize('100%');
        $tipos_contatos_id->setSize('100%');
        $imprime_guia_medico->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Médicos PF: <font color=\"red\">*</font>", null, '14px', null, '100%'),$medicos_pf_id,$id],[new TLabel("Tipos de contato: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_contatos_id]);
        $row1->layout = ['col-sm-4','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Contato:", null, '14px', null, '100%'),$contato],[new TLabel("Imprime guia medico: <font color=\"red\">*</font>", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row2->layout = [' col-sm-5','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MedicosPfContatosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro contatos de médicos PF"]));
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

            $object = new MedicosPfContatos(); // create an empty object 

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
            TApplication::loadPage('MedicosPfContatosList', 'onShow', $loadPageParam); 

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

                $object = new MedicosPfContatos($key); // instantiates the Active Record 

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

