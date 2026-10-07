<?php

class CatalogoEnderecoCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CatalogoEndereco';
    private static $primaryKey = 'id';
    private static $formName = 'form_CatalogoEnderecoCortinaForm';

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
        $this->form->setFormTitle("Cadastro de endereço");

        $criteria_cidades_id = new TCriteria();

        $id = new THidden('id');
        $catalogo_id = new THidden('catalogo_id');
        $cidades_id = new TDBCombo('cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidades_id );
        $bairro = new TEntry('bairro');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $cep = new TEntry('cep');

        $cidades_id->addValidation("Cidade", new TRequiredValidator()); 
        $logradouro->addValidation("Logradouro", new TRequiredValidator()); 
        $cep->addValidation("CEP", new TRequiredValidator()); 

        $catalogo_id->setValue($param["catalogo_id"] ?? "");
        $cidades_id->enableSearch();
        $cep->setMask('#####-###');
        $bairro->setMaxLength(50);
        $numero->setMaxLength(10);
        $logradouro->setMaxLength(50);

        $id->setSize(200);
        $cep->setSize('100%');
        $bairro->setSize('100%');
        $numero->setSize('100%');
        $catalogo_id->setSize(200);
        $cidades_id->setSize('100%');
        $logradouro->setSize('100%');

        $row1 = $this->form->addFields([$id,$catalogo_id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Cidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id],[new TLabel("Bairro:", null, '14px', null, '100%'),$bairro]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Logradouro: <font color=\"red\">*</font>", null, '14px', null, '100%'),$logradouro],[new TLabel("Número:", null, '14px', null, '100%'),$numero],[new TLabel("CEP: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cep]);
        $row3->layout = [' col-sm-6',' col-sm-3',' col-sm-3'];

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

            $object = new CatalogoEndereco(); // create an empty object 

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
            TApplication::loadPage('CatalogoEnderecoCortinaList', 'onShow', $loadPageParam); 

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

                $object = new CatalogoEndereco($key); // instantiates the Active Record 

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

