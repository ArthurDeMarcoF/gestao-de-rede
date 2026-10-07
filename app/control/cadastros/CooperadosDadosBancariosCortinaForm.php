<?php

class CooperadosDadosBancariosCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosDadosBancarios';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosDadosBancariosCortinaForm';

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
        $this->form->setFormTitle("Cadastro de dados bancários dos cooperados");

        $criteria_banco_id = new TCriteria();

        $banco_id = new TDBCombo('banco_id', 'databaserede', 'Bancos', 'id', '{banco}','banco asc' , $criteria_banco_id );
        $id = new THidden('id');
        $agencia = new TEntry('agencia');
        $conta = new TEntry('conta');
        $conta_descricao = new TEntry('conta_descricao');
        $ativo = new TRadioGroup('ativo');
        $desativado = new TDate('desativado');
        $observacao = new TText('observacao');

        $banco_id->addValidation("Banco id", new TRequiredValidator()); 
        $agencia->addValidation("Agência ", new TRequiredValidator()); 
        $conta->addValidation("Conta ", new TRequiredValidator()); 

        $banco_id->enableSearch();
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('Sim');
        $ativo->setUseButton();
        $desativado->setMask('dd/mm/yyyy');
        $desativado->setDatabaseMask('yyyy-mm-dd');
        $id->setSize(200);
        $conta->setSize('100%');
        $ativo->setSize('100%');
        $agencia->setSize('100%');
        $desativado->setSize(110);
        $banco_id->setSize('100%');
        $observacao->setSize('100%', 70);
        $conta_descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Banco: <font color=\"red\">*</font>", null, '14px', null, '100%'),$banco_id,$id],[new TLabel("Agência: <font color=\"red\">*</font>", null, '14px', null, '100%'),$agencia],[new TLabel("Conta:  <font color=\"red\">*</font>", null, '14px', null, '100%'),$conta]);
        $row1->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Descrição da conta:", null, '14px', null, '100%'),$conta_descricao],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo],[new TLabel("Desativado em:", null, '14px', null, '100%'),$desativado]);
        $row2->layout = [' col-sm-6',' col-sm-3',' col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row3->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosDadosBancariosCortinaList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=CooperadosDadosBancariosCortinaForm]');
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

            $object = new CooperadosDadosBancarios(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->cooperados_id = TSession::getValue('form_CooperadoContatosList_Cooperado_id');

            if ($object->ativo == 'Não' && empty($object->desativado))
            {
                throw new Exception('Necessário informar a data de dasativação');
            }

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
            TApplication::loadPage('CooperadosDadosBancariosCortinaList', 'onShow', $loadPageParam); 

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

                $object = new CooperadosDadosBancarios($key); // instantiates the Active Record 

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

