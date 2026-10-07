<?php

class CredenciadosEspecialidadesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosEspecialidades';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosEspecialidadesCortinaForm';

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
        $this->form->setFormTitle("Cadastro de especialidades do credenciado");

        $criteria_especialidades_id = new TCriteria();
        $criteria_imprime_guia_medico = new TCriteria();

        $filterVar = "dm_sim_nao";
        $criteria_imprime_guia_medico->add(new TFilter('codigo', '=', $filterVar)); 

        $especialidades_id = new TDBCombo('especialidades_id', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades_id );
        $id = new THidden('id');
        $rqe = new TEntry('rqe');
        $imprime_guia_medico = new TDBRadioGroup('imprime_guia_medico', 'databaserede', 'VDominioValor', 'valor', '{mascara}','sequencia asc' , $criteria_imprime_guia_medico );

        $especialidades_id->addValidation("Especialidades id", new TRequiredValidator()); 

        $especialidades_id->enableSearch();
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue(DMService::obterValPadrao('credenciados_especialidades','imprime_guia_medico'));
        $imprime_guia_medico->setUseButton();
        $imprime_guia_medico->setBreakItems(2);
        $id->setSize(200);
        $rqe->setSize('100%');
        $especialidades_id->setSize('100%');
        $imprime_guia_medico->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Especialidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$especialidades_id,$id],[new TLabel("RQE:", null, '14px', null, '100%'),$rqe],[new TLabel("Imprime guia médico:", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row1->layout = [' col-sm-5',' col-sm-4',' col-sm-3'];

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

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new CredenciadosEspecialidades(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->credenciados_id = TSession::getValue('form_CredenciadoForm_Credenciado_id');

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
            TApplication::loadPage('CredenciadosEspecialidadesCortinaList', 'onShow', $loadPageParam); 

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

                $object = new CredenciadosEspecialidades($key); // instantiates the Active Record 

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

