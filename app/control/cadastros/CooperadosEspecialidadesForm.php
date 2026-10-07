<?php

class CooperadosEspecialidadesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosEspecialidades';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosEspecialidadesForm';

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
        $this->form->setFormTitle("Cadastro de especialidades do cooperados");

        $criteria_cooperados_id = new TCriteria();
        $criteria_especialidades_id = new TCriteria();

        $filterVar = "Sim";
        $criteria_cooperados_id->add(new TFilter('ativo', '=', $filterVar)); 

        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $id = new THidden('id');
        $especialidades_id = new TDBCombo('especialidades_id', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades_id );
        $rqe = new TEntry('rqe');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $cooperados_id->addValidation("Cooperados id", new TRequiredValidator()); 
        $especialidades_id->addValidation("Especialidades id", new TRequiredValidator()); 

        $imprime_guia_medico->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('Sim');
        $imprime_guia_medico->setUseButton();
        $cooperados_id->enableSearch();
        $especialidades_id->enableSearch();

        $id->setSize(200);
        $rqe->setSize('100%');
        $cooperados_id->setSize('100%');
        $especialidades_id->setSize('100%');
        $imprime_guia_medico->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cooperados: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cooperados_id,$id],[new TLabel("Especialidades: <font color=\"red\">*</font>", null, '14px', null, '100%'),$especialidades_id],[new TLabel("RQE :", null, '14px', null, '100%'),$rqe],[new TLabel("Imprime guia médico :", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosEspecialidadesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de especialidades do cooperados"]));
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

            $object = new CooperadosEspecialidades(); // create an empty object 

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

                $object = new CooperadosEspecialidades($key); // instantiates the Active Record 

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

