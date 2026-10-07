<?php

class CooperadosDocumentacoesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosDocumentacoesForm';

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
        $this->form->setFormTitle("Cadastro de documentações dos cooperados");

        $criteria_cooperados_id = new TCriteria();
        $criteria_tipos_documentacoes_id = new TCriteria();

        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $id = new THidden('id');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_1 = new TButton('button_1');
        $entregue = new TRadioGroup('entregue');
        $ativo = new TRadioGroup('ativo');
        $emissao = new TDate('emissao');
        $data_alerta = new TDate('data_alerta');
        $validade = new TDate('validade');
        $conteudo = new THtmlEditor('conteudo');
        $path_arquivo = new TFile('path_arquivo');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $cooperados_id->addValidation("Cooperado", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentacoes id", new TRequiredValidator()); 

        $entregue->setBreakItems(2);
        $path_arquivo->enableFileHandling();
        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName,"tipos_documentacoes_id" => $param["tipos_documentacoes_id"] ?? ""]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $entregue->addItems(["Sim"=>"Sim","Não"=>"Não"]);

        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue('Sim');
        $entregue->setValue('Sim');

        $ativo->setUseButton();
        $entregue->setUseButton();

        $cooperados_id->enableSearch();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');
        $data_alerta->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');
        $data_alerta->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $ativo->setSize('100%');
        $emissao->setSize('100%');
        $entregue->setSize('100%');
        $validade->setSize('100%');
        $data_alerta->setSize('100%');
        $path_arquivo->setSize('100%');
        $cooperados_id->setSize('100%');
        $conteudo->setSize('100%', 200);
        $documentacoes_id->setSize('80%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('85%');

        $row1 = $this->form->addFields([new TLabel("Cooperado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cooperados_id,$id],[new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1]);
        $row1->layout = ['col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue],[new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta],[new TLabel("Validade:", null, '14px', null, '100%'),$validade]);
        $row2->layout = [' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $tab_6723e5f01be8e = new BootstrapFormBuilder('tab_6723e5f01be8e');
        $this->tab_6723e5f01be8e = $tab_6723e5f01be8e;
        $tab_6723e5f01be8e->setProperty('style', 'border:none; box-shadow:none;');

        $tab_6723e5f01be8e->appendPage("Conteúdo");

        $tab_6723e5f01be8e->addFields([new THidden('current_tab_tab_6723e5f01be8e')]);
        $tab_6723e5f01be8e->setTabFunction("$('[name=current_tab_tab_6723e5f01be8e]').val($(this).attr('data-current_page'));");

        $row4 = $tab_6723e5f01be8e->addFields([$conteudo]);
        $row4->layout = [' col-sm-12'];

        $row5 = $tab_6723e5f01be8e->addFields([$path_arquivo]);
        $row5->layout = [' col-sm-12'];

        $tab_6723e5f01be8e->appendPage("Observações");
        $row6 = $tab_6723e5f01be8e->addFields([$observacao]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->form->addFields([$tab_6723e5f01be8e]);
        $row7->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosDocumentacoesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de documentações padrão"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangetipos_documentacoes_id($param)
    {
        try
        {

            if (isset($param['tipos_documentacoes_id']) && $param['tipos_documentacoes_id'])
            { 
                $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipos_documentacoes_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'documentacoes_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new CooperadosDocumentacoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $path_arquivo_dir = 'arquivos/docs_cooperados';  

            $object->store(); // save the object 

            $this->fireEvents($object);

            $this->saveFile($object, $data, 'path_arquivo', $path_arquivo_dir); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

        }
        catch (Exception $e) // in case of exception
        {

            $this->fireEvents($this->form->getData());  

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

                $object = new CooperadosDocumentacoes($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public static function getFormName()
    {
        return self::$formName;
    }

}

