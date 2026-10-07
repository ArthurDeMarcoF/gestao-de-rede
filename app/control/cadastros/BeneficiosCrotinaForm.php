<?php

class BeneficiosCrotinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Beneficios';
    private static $primaryKey = 'id';
    private static $formName = 'form_BeneficiosCrotinaForm';

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
        $this->form->setFormTitle("Cadastro de benefícios");

        $criteria_tipo_beneficio_id = new TCriteria();

        $tipo_beneficio_id = new TDBCombo('tipo_beneficio_id', 'databaserede', 'TiposBeneficios', 'id', '{tipo_beneficio}','tipo_beneficio asc' , $criteria_tipo_beneficio_id );
        $id = new THidden('id');
        $beneficio = new TEntry('beneficio');
        $ativo = new TRadioGroup('ativo');
        $identificador = new TEntry('identificador');
        $valor = new TEntry('valor');
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');

        $tipo_beneficio_id->addValidation("Tipo de benefício", new TRequiredValidator()); 

        $tipo_beneficio_id->enableSearch();
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('Sim');
        $ativo->setUseButton();
        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $ativo->setSize('100%');
        $valor->setSize('100%');
        $data_final->setSize(110);
        $beneficio->setSize('100%');
        $data_inicial->setSize(110);
        $identificador->setSize('100%');
        $tipo_beneficio_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo do benefício: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipo_beneficio_id,$id],[new TLabel("Benefício:", null, '14px', null, '100%'),$beneficio],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo]);
        $row1->layout = [' col-sm-4',' col-sm-6',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Identificador:", null, '14px', null, '100%'),$identificador],[new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final]);
        $row2->layout = [' col-sm-5',' col-sm-3',' col-sm-2',' col-sm-2'];

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

        // if(!empty($param['pessoas_id']))
        // {
        //     TSession::setValue('form_EnderecosCortinaForm_PessoasId', $param['pessoas_id']);
        // }

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=BeneficiosCrotinaForm]');
        $style->width = '55% !important';   
        $style->show(true);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Beneficios(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->cooperados_id = TSession::getValue('form_CooperadoContatosList_Cooperado_id');

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
            TApplication::loadPage('BeneficiosCortinaList', 'onShow', $loadPageParam); 

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

                $object = new Beneficios($key); // instantiates the Active Record 

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

