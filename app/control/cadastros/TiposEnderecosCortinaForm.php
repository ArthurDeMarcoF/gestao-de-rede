<?php

class TiposEnderecosCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'TiposEnderecos';
    private static $primaryKey = 'id';
    private static $formName = 'form_TiposEnderecosCortinaForm';

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
        $this->form->setFormTitle("Cadastro de tipo de endereço");


        $id = new THidden('id');
        $form_name = new THidden('form_name');
        $tipo_endereco = new TEntry('tipo_endereco');

        $tipo_endereco->addValidation("Tipo de endereço ", new TRequiredValidator()); 

        $form_name->setValue($param["form_name"] ?? "");
        $tipo_endereco->setMaxLength(30);
        $id->setSize(200);
        $form_name->setSize(200);
        $tipo_endereco->setSize('100%');

        $row1 = $this->form->addFields([$id,$form_name]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Tipo de endereço:", null, '14px', null, '100%'),$tipo_endereco]);
        $row2->layout = [' col-sm-12'];

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

            $object = new TiposEnderecos(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $aux_obj = new stdClass();
            $aux_obj->tipos_enderecos_id = $object->id;
            TDBCombo::reloadFromModel($param['form_name'], 'tipos_enderecos_id', 'databaserede', 'TiposEnderecos', 'id', '{tipo_endereco}','tipo_endereco asc');
            TForm::sendData($param['form_name'], $aux_obj);

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

                $object = new TiposEnderecos($key); // instantiates the Active Record 

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

        if (!empty($param['_field_data'])) {
            parse_str($param['_field_data'], $queryParams);

            if (!empty($queryParams['quick_register_value'])) {
                $tipo_endereco = $queryParams['quick_register_value'];

                try {
                    TTransaction::open(self::$database);

                    $object = new TiposEnderecos(); 
                    $object->tipo_endereco = $tipo_endereco;
                    $object->store();

                    TCombo::reload('form_MedicosPfEnderecosForm', 'tipos_enderecos_id', TiposEnderecos::getIndexedArray('id', 'tipo_endereco'));

                    $data = new stdClass();
                    $data->tipos_enderecos_id = $object->id;
                    TForm::sendData('form_MedicosPfEnderecosForm', $data);

                    TTransaction::close();

                    TToast::show('success', 'Registro salvo com sucesso', 'topRight', 'far:check-circle');
                    // TScript::create('Template.closeRightPanel();');
                } catch (Exception $e) {
                    TTransaction::rollback(); // Reverte a transação em caso de erro
                    TToast::show('error', 'Erro ao salvar o registro: ' . $e->getMessage(), 'topRight', 'far:times-circle');
                }
            }
        }

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

