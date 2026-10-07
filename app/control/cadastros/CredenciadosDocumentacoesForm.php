<?php

class CredenciadosDocumentacoesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosDocumentacoesForm';

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
        $this->form->setFormTitle("Cadastro de documentações dos credenciados");

        $criteria_credenciados_id = new TCriteria();
        $criteria_entregue = new TCriteria();
        $criteria_ativo = new TCriteria();
        $criteria_tipos_documentacoes_id = new TCriteria();

        $filterVar = "credenciados_documentacoes";
        $criteria_entregue->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "entregue";
        $criteria_entregue->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados_documentacoes";
        $criteria_ativo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "ativo";
        $criteria_ativo->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new THidden('id');
        $credenciados_id = new TDBCombo('credenciados_id', 'databaserede', 'Credenciados', 'id', '{nome}','nome asc' , $criteria_credenciados_id );
        $entregue = new TDBRadioGroup('entregue', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_entregue );
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $data_alerta = new TDate('data_alerta');
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_1 = new TButton('button_1');
        $conteudo = new THtmlEditor('conteudo');
        $arquivo_path = new TFile('arquivo_path');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $credenciados_id->addValidation("Credenciados id", new TRequiredValidator()); 
        $entregue->addValidation("entregue", new TRequiredValidator()); 
        $ativo->addValidation("ativo", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentacoes id", new TRequiredValidator()); 

        $arquivo_path->enableFileHandling();
        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue(DMService::obterValPadrao('credenciados_documentacoes','ativo'));
        $entregue->setValue(DMService::obterValPadrao('credenciados_documentacoes','entregue'));

        $ativo->setUseButton();
        $entregue->setUseButton();

        $ativo->setBreakItems(2);
        $entregue->setBreakItems(2);

        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName,"tipos_documentacoes_id" => $param["tipos_documentacoes_id"] ?? ""]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $credenciados_id->enableSearch();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');
        $data_alerta->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');
        $data_alerta->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $emissao->setSize(110);
        $validade->setSize(110);
        $ativo->setSize('100%');
        $entregue->setSize('100%');
        $data_alerta->setSize(110);
        $arquivo_path->setSize('100%');
        $conteudo->setSize('100%', 200);
        $credenciados_id->setSize('100%');
        $documentacoes_id->setSize('80%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('80%');

        $row1 = $this->form->addFields([$id,new TLabel("Credenciado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$credenciados_id],[new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue]);
        $row1->layout = [' col-sm-9',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Validade:", null, '14px', null, '100%'),$validade],[new TLabel("Data alerta:", null, '14px', null, '100%'),$data_alerta],[new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $tab_6723e52a1be7a = new BootstrapFormBuilder('tab_6723e52a1be7a');
        $this->tab_6723e52a1be7a = $tab_6723e52a1be7a;
        $tab_6723e52a1be7a->setProperty('style', 'border:none; box-shadow:none;');

        $tab_6723e52a1be7a->appendPage("Conteúdo");

        $tab_6723e52a1be7a->addFields([new THidden('current_tab_tab_6723e52a1be7a')]);
        $tab_6723e52a1be7a->setTabFunction("$('[name=current_tab_tab_6723e52a1be7a]').val($(this).attr('data-current_page'));");

        $row5 = $tab_6723e52a1be7a->addFields([$conteudo]);
        $row5->layout = [' col-sm-12'];

        $row6 = $tab_6723e52a1be7a->addFields([$arquivo_path]);
        $row6->layout = [' col-sm-12'];

        $tab_6723e52a1be7a->appendPage("Observação");
        $row7 = $tab_6723e52a1be7a->addFields([$observacao]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->form->addFields([$tab_6723e52a1be7a]);
        $row8->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CredenciadosDocumentacoesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de documentações dos credenciados"]));
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

            $object = new CredenciadosDocumentacoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $arquivo_path_dir = 'arquivos/docs_credenciados'; 

            $object->store(); // save the object 

            $this->fireEvents($object);

            $this->saveFile($object, $data, 'arquivo_path', $arquivo_path_dir);
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
            TApplication::loadPage('CredenciadosDocumentacoesList', 'onShow', $loadPageParam); 

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

                $object = new CredenciadosDocumentacoes($key); // instantiates the Active Record 

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

