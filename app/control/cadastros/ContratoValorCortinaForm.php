<?php

class ContratoValorCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'ContratoValor';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContratoValorCortinaForm';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Cadastro de valor do contrato");


        $id = new THidden('id');
        $contrato_id = new THidden('contrato_id');
        $dt_pagamento = new TDate('dt_pagamento');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $nr_nota = new TEntry('nr_nota');
        $path_arquivo = new TFile('path_arquivo');

        $dt_pagamento->addValidation("Data", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 

        $contrato_id->setValue($param["contrato_id"] ?? "");
        $dt_pagamento->setMask('dd/mm/yyyy');
        $dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $nr_nota->setMaxLength(20);
        $path_arquivo->enableFileHandling();
        $id->setSize(200);
        $valor->setSize('100%');
        $nr_nota->setSize('100%');
        $contrato_id->setSize(200);
        $dt_pagamento->setSize('100%');
        $path_arquivo->setSize('100%');

        $row1 = $this->form->addFields([$id,$contrato_id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Data: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dt_pagamento],[new TLabel("Valor: <font color=\"red\">*</font>", null, '14px', null, '100%'),$valor],[new TLabel("NF:", null, '14px', null, '100%'),$nr_nota]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Anexo:", null, '14px', null, '100%'),$path_arquivo]);
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

            $object = new ContratoValor(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $path_arquivo_dir = 'arquivos/docs_contratos/valores';  

            if (empty($object->contrato_id))
                $object->contrato_id = TSession::getValue('ContratoForm_key');

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'path_arquivo', $path_arquivo_dir);
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
            TApplication::loadPage('ContratoValorCortinaList', 'onShow', $loadPageParam); 

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

                $object = new ContratoValor($key); // instantiates the Active Record 

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

