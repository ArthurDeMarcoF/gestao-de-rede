<?php

class MedicosPfEspecialidadesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPfEspecialidades';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfEspecialidadesForm';

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
        $this->form->setFormTitle("Cadastro de médicos PF especialidades");

        $criteria_medicos_pf_id = new TCriteria();
        $criteria_especialidades_id = new TCriteria();

        $medicos_pf_id = new TDBCombo('medicos_pf_id', 'databaserede', 'MedicosPf', 'id', '{id}','id asc' , $criteria_medicos_pf_id );
        $id = new THidden('id');
        $especialidades_id = new TDBCombo('especialidades_id', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades_id );
        $rqe = new TEntry('rqe');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $medicos_pf_id->addValidation("Médicos PF", new TRequiredValidator()); 
        $especialidades_id->addValidation("Especialidades", new TRequiredValidator()); 

        $imprime_guia_medico->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('Sim');
        $imprime_guia_medico->setUseButton();
        $medicos_pf_id->enableSearch();
        $especialidades_id->enableSearch();

        $id->setSize(200);
        $rqe->setSize('100%');
        $medicos_pf_id->setSize('100%');
        $especialidades_id->setSize('100%');
        $imprime_guia_medico->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Médicos PF: <font color=\"red\">*</font>", null, '14px', null, '100%'),$medicos_pf_id,$id],[new TLabel("Especialidades: <font color=\"red\">*</font>", null, '14px', null, '100%'),$especialidades_id],[new TLabel("RQE:", null, '14px', null, '100%'),$rqe],[new TLabel("Imprime guia medico:", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MedicosPfEspecialidadesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=MedicosPfEspecialidadesForm]');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new MedicosPfEspecialidades(); // create an empty object 

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
            TApplication::loadPage('MedicosPfEspecialidadesList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 
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

                $object = new MedicosPfEspecialidades($key); // instantiates the Active Record 

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

