<?php

class CredenciadosMovimentacoesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosMovimentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosMovimentacoesForm';

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
        $this->form->setFormTitle("Cadastro de movimentação");


        $assunto = new TEntry('assunto');
        $descricao = new TText('descricao');
        $id = new THidden('id');

        $assunto->addValidation("Assunto", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 

        $id->setSize(200);
        $assunto->setSize('100%');
        $descricao->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Assunto: <font color='red'>*<font>", null, '14px', null, '100%'),$assunto]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Descrição: <font color='red'>*<font>", null, '14px', null, '100%'),$descricao,$id]);
        $row2->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CredenciadosMovimentacoesList', 'onShow']), 'fas:arrow-left #000000');
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

    }

    public function onSave($param = null) 
    {
        try
        {
            $id_credenciado = TSession::getValue('id_credenciado');

            if (empty($id_credenciado)) {
                throw new Exception('Cooperado não identificado para salvar a movimentação.');
            }

            $usuarioNome = null;

            TTransaction::open('permission');
            $system_users = new SystemUsers(TSession::getValue('userid'));
            $usuarioNome = $system_users->name;
            TTransaction::close();

            TTransaction::open(self::$database);

            $this->form->validate();

            $data = $this->form->getData();

            $object = new CredenciadosMovimentacoes(); // create an empty object 

            $object->fromArray( (array) $data);

            $object->credenciados_id = $id_credenciado;
            $object->data_registro = date('Y-m-d H:i:s');
            $object->usuario = $usuarioNome;

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
            TApplication::loadPage('CredenciadosMovimentacoesList', 'onShow', $loadPageParam); 

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

                $object = new CredenciadosMovimentacoes($key); // instantiates the Active Record 

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

