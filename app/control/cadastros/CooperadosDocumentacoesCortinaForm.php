<?php

class CooperadosDocumentacoesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosDocumentacoesCortinaForm';

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
        $this->form->setFormTitle("Documentações");

        $criteria_tipos_documentacoes_id = new TCriteria();

        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $id = new THidden('id');
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_1 = new TButton('button_1');
        $entregue = new TRadioGroup('entregue');
        $ativo = new TRadioGroup('ativo');
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $data_alerta = new TDate('data_alerta');
        $conteudo = new THtmlEditor('conteudo');
        $path_arquivo = new TFile('path_arquivo');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentacoes id", new TRequiredValidator()); 
        $entregue->addValidation("Entregue", new TRequiredValidator()); 

        $path_arquivo->enableFileHandling();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

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

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');
        $data_alerta->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');
        $data_alerta->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $ativo->setSize('100%');
        $validade->setSize(110);
        $emissao->setSize('65%');
        $entregue->setSize('100%');
        $data_alerta->setSize(110);
        $path_arquivo->setSize('100%');
        $conteudo->setSize('100%', 200);
        $documentacoes_id->setSize('80%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('80%');

        $row1 = $this->form->addFields([new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_,$id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1],[new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue]);
        $row1->layout = [' col-sm-4',' col-sm-5','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Validade:", null, '14px', null, '100%'),$validade],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $tab_6723e6ff1bea0 = new BootstrapFormBuilder('tab_6723e6ff1bea0');
        $this->tab_6723e6ff1bea0 = $tab_6723e6ff1bea0;
        $tab_6723e6ff1bea0->setProperty('style', 'border:none; box-shadow:none;');

        $tab_6723e6ff1bea0->appendPage("Conteúdo");

        $tab_6723e6ff1bea0->addFields([new THidden('current_tab_tab_6723e6ff1bea0')]);
        $tab_6723e6ff1bea0->setTabFunction("$('[name=current_tab_tab_6723e6ff1bea0]').val($(this).attr('data-current_page'));");

        $row4 = $tab_6723e6ff1bea0->addFields([$conteudo]);
        $row4->layout = [' col-sm-12'];

        $row5 = $tab_6723e6ff1bea0->addFields([$path_arquivo]);
        $row5->layout = [' col-sm-12'];

        $tab_6723e6ff1bea0->appendPage("Observações");
        $row6 = $tab_6723e6ff1bea0->addFields([$observacao]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->form->addFields([$tab_6723e6ff1bea0]);
        $row7->layout = [' col-sm-12'];

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

        $style = new TStyle('right-panel > .container-part[page-name=CooperadosDocumentacoesCortinaForm]');
        $style->width = '60% !important';   
        $style->show(true);

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

            $object->cooperados_id = TSession::getValue('form_CooperadoContatosList_Cooperado_id');

            $path_arquivo_dir = 'arquivos/docs_cooperados';  

            $object->store(); // save the object 

            // TSession::setValue('form_CooperadoContatosList_Cooperado_id', '');

            $this->fireEvents($object);

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
            TApplication::loadPage('CooperadosDocumentacoesCortinaList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

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

