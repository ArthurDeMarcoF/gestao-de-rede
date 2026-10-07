<?php

class CidadesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Cidades';
    private static $primaryKey = 'id';
    private static $formName = 'form_CidadesCortinaForm';

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
        $this->form->setFormTitle("Cadastro de cidades");

        $criteria_estados_id = new TCriteria();

        $id = new THidden('id');
        $form_name = new THidden('form_name');
        $estados_id = new TDBCombo('estados_id', 'databaserede', 'Estados', 'id', '{estado}','estado asc' , $criteria_estados_id );
        $cod_ibge = new TEntry('cod_ibge');
        $cidade = new TEntry('cidade');

        $estados_id->addValidation("Estados id", new TRequiredValidator()); 
        $cidade->addValidation("Cidade ", new TRequiredValidator()); 

        $estados_id->enableSearch();
        $form_name->setValue($param["form_name"] ?? "");
        $estados_id->setValue($param["cidades_estados_id"] ?? "");

        $cidade->setMaxLength(150);
        $cod_ibge->setMaxLength(20);

        $id->setSize(200);
        $form_name->setSize(200);
        $cidade->setSize('100%');
        $cod_ibge->setSize('100%');
        $estados_id->setSize('100%');

        $row1 = $this->form->addFields([$id,$form_name]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Estado:", null, '14px', null, '100%'),$estados_id],[new TLabel("Cód. IBGE:", null, '14px', null, '100%'),$cod_ibge]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null, '100%'),$cidade]);
        $row3->layout = [' col-sm-12'];

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

            $object = new Cidades(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

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

                $object = new Cidades($key); // instantiates the Active Record 

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

