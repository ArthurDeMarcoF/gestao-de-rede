<?php

class DocumentacoesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Documentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_DocumentacoesCortinaForm';

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
        $this->form->setFormTitle("Cadastro de documentações");

        $criteria_tipos_documentacoes_id = new TCriteria();

        $id = new THidden('id');
        $form_name = new THidden('form_name');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $descricao = new TEntry('descricao');
        $ativo = new TRadioGroup('ativo');

        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $descricao->addValidation("Descrição ", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 

        $tipos_documentacoes_id->enableSearch();
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setUseButton();
        $ativo->setBreakItems(2);
        $ativo->setValue('Sim');
        $form_name->setValue($param["form_name"] ?? "");
        $tipos_documentacoes_id->setValue($param["tipos_documentacoes_id"] ?? "");

        $id->setSize(200);
        $ativo->setSize('100%');
        $form_name->setSize(200);
        $descricao->setSize('100%');
        $tipos_documentacoes_id->setSize('100%');

        $row1 = $this->form->addFields([$id,$form_name]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Descrição: <font color=\"red\">*</font>", null, '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo]);
        $row4->layout = ['col-sm-3'];

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

            $object = new Documentacoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 
            $this->form->setData($data); // fill form data

            $aux_obj = new stdClass();
            #$aux_obj->documentacoes_id = $object->id;
            $aux_obj->tipos_documentacoes_id = $object->tipos_documentacoes_id;
            TDBCombo::reloadFromModel($param['form_name'], 'tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc');
            TForm::sendData($param['form_name'], $aux_obj);

            $aux_obj = new stdClass();
            $aux_obj->documentacoes_id = $object->id;
            $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipos_documentacoes_id']]);
            TDBCombo::reloadFromModel($param['form_name'], 'documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            TForm::sendData($param['form_name'], $aux_obj);

            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

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

                $object = new Documentacoes($key); // instantiates the Active Record 

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

