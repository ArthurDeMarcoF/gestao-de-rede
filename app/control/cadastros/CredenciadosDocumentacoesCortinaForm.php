<?php

class CredenciadosDocumentacoesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosDocumentacoesCortinaForm';

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
        $this->form->setFormTitle("Cadastro de documentos do credenciado");

        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_documentacoes_id = new TCriteria();
        $criteria_entregue = new TCriteria();
        $criteria_ativo = new TCriteria();

        $filterVar = "credenciados_documentacoes";
        $criteria_entregue->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "entregue";
        $criteria_entregue->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados_documentacoes";
        $criteria_ativo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "ativo";
        $criteria_ativo->add(new TFilter('atributo', '=', $filterVar)); 

        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $id = new THidden('id');
        $documentacoes_id = new TDBCombo('documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}','descricao asc' , $criteria_documentacoes_id );
        $button_1 = new TButton('button_1');
        $entregue = new TDBRadioGroup('entregue', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_entregue );
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $data_alerta = new TDate('data_alerta');
        $conteudo = new THtmlEditor('conteudo');
        $arquivo_path = new TFile('arquivo_path');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentacoes id", new TRequiredValidator()); 

        $arquivo_path->enableFileHandling();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName,"tipos_documentacoes_id" => $param["tipos_documentacoes_id"] ?? ""]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue(DMService::obterValPadrao('credenciados_documentacoes','ativo'));
        $entregue->setValue(DMService::obterValPadrao('credenciados_documentacoes', 'entregue'));

        $ativo->setUseButton();
        $entregue->setUseButton();

        $ativo->setBreakItems(2);
        $entregue->setBreakItems(2);

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');
        $data_alerta->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');
        $data_alerta->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $emissao->setSize(110);
        $ativo->setSize('100%');
        $validade->setSize(110);
        $entregue->setSize('100%');
        $data_alerta->setSize(110);
        $arquivo_path->setSize('100%');
        $conteudo->setSize('100%', 200);
        $documentacoes_id->setSize('80%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('80%');

        $row1 = $this->form->addFields([new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_,$id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1],[new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue]);
        $row1->layout = [' col-sm-5',' col-sm-5',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Validade:", null, '14px', null, '100%'),$validade],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $tab_6723e3801be68 = new BootstrapFormBuilder('tab_6723e3801be68');
        $this->tab_6723e3801be68 = $tab_6723e3801be68;
        $tab_6723e3801be68->setProperty('style', 'border:none; box-shadow:none;');

        $tab_6723e3801be68->appendPage("Conteúdo");

        $tab_6723e3801be68->addFields([new THidden('current_tab_tab_6723e3801be68')]);
        $tab_6723e3801be68->setTabFunction("$('[name=current_tab_tab_6723e3801be68]').val($(this).attr('data-current_page'));");

        $row4 = $tab_6723e3801be68->addFields([$conteudo]);
        $row4->layout = [' col-sm-12'];

        $row5 = $tab_6723e3801be68->addFields([$arquivo_path]);
        $row5->layout = [' col-sm-12'];

        $tab_6723e3801be68->appendPage("Observações");
        $row6 = $tab_6723e3801be68->addFields([$observacao]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->form->addFields([$tab_6723e3801be68]);
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

        $style = new TStyle('right-panel > .container-part[page-name=CredenciadosDocumentacoesCortinaForm]');
        $style->width = '60% !important';   
        $style->show(true);

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

            $object->credenciados_id = TSession::getValue('form_CredenciadoForm_Credenciado_id');

            $arquivo_path_dir = 'arquivos/docs_credenciados';  

            $object->store(); // save the object 

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
            TApplication::loadPage('CredenciadosDocumentacoesCortinaList', 'onShow', $loadPageParam); 

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

                $object = new CredenciadosDocumentacoes($key); // instantiates the Active Record 

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

