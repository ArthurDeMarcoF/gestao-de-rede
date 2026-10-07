<?php

class MedicosPfContatosCortinaForm extends TPage
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
        $this->form->setFormTitle("Contatos de consultórios de especialidades  ");

        $criteria_tipos_contatos_id = new TCriteria();

        $tipos_contatos_id = new TDBCombo('tipos_contatos_id', 'databaserede', 'TiposContatos', 'id', '{tipo_contato}','tipo_contato asc' , $criteria_tipos_contatos_id );
        $id = new THidden('id');
        $medicos_pf_id = new THidden('medicos_pf_id');
        $contato = new TEntry('contato');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $tipos_contatos_id->addValidation("Tipos de contato", new TRequiredValidator()); 
        $imprime_guia_medico->addValidation("Imprime guia medico", new TRequiredValidator()); 

        $tipos_contatos_id->enableSearch();
        $contato->setMaxLength(100);
        $imprime_guia_medico->addItems(["S"=>"Sim","N"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('N');
        $imprime_guia_medico->setUseButton();
        $id->setSize(200);
        $contato->setSize('100%');
        $medicos_pf_id->setSize(200);
        $tipos_contatos_id->setSize('100%');
        $imprime_guia_medico->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipos de contato: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_contatos_id,$id,$medicos_pf_id],[new TLabel("Contato:", null, '14px', null, '100%'),$contato]);
        $row1->layout = [' col-sm-4',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Imprime guia medico: <font color=\"red\">*</font>", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row2->layout = ['col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=MedicosPfContatosCortinaForm]');
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
            TApplication::loadPage('MedicosPfContatosCortinaList', 'onShow', $loadPageParam); 

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

        if (isset($param['medicos_pf_id'])) {
            $data = new stdClass;
            $data->medicos_pf_id = $param['medicos_pf_id'];
            $this->form->setData($data);
        }
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

