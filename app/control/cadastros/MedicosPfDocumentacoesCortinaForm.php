<?php

class MedicosPfDocumentacoesCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPfDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfDocumentacoesForm';

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
        $this->form->setFormTitle("Documentações de consultórios de especialidades  ");

        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_documentacoes_id = new TCriteria();

        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $id = new THidden('id');
        $medicos_pf_id = new THidden('medicos_pf_id');
        $documentacoes_id = new TDBCombo('documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}','descricao asc' , $criteria_documentacoes_id );
        $entregue = new TRadioGroup('entregue');
        $ativo = new TRadioGroup('ativo');
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $data_alerta = new TDate('data_alerta');
        $conteudo = new THtmlEditor('conteudo');
        $path_arquivo = new TFile('path_arquivo');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->addValidation("Tipos de documentações", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentações ", new TRequiredValidator()); 
        $entregue->addValidation("Entregue", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 

        $path_arquivo->enableFileHandling();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

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
        $emissao->setSize(110);
        $ativo->setSize('100%');
        $validade->setSize(110);
        $entregue->setSize('100%');
        $data_alerta->setSize(110);
        $medicos_pf_id->setSize(200);
        $path_arquivo->setSize('100%');
        $conteudo->setSize('100%', 200);
        $observacao->setSize('100%', 200);
        $documentacoes_id->setSize('100%');
        $tipos_documentacoes_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$id,$medicos_pf_id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id],[new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue]);
        $row1->layout = [' col-sm-5',' col-sm-5','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Validade:", null, '14px', null, '100%'),$validade],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta]);
        $row2->layout = [' col-sm-2',' col-sm-3',' col-sm-3',' col-sm-4'];

        $tab_683d8a704985a = new BootstrapFormBuilder('tab_683d8a704985a');
        $this->tab_683d8a704985a = $tab_683d8a704985a;
        $tab_683d8a704985a->setProperty('style', 'border:none; box-shadow:none;');

        $tab_683d8a704985a->appendPage("Conteúdo");

        $tab_683d8a704985a->addFields([new THidden('current_tab_tab_683d8a704985a')]);
        $tab_683d8a704985a->setTabFunction("$('[name=current_tab_tab_683d8a704985a]').val($(this).attr('data-current_page'));");

        $row3 = $tab_683d8a704985a->addFields([$conteudo]);
        $row3->layout = [' col-sm-12'];

        $row4 = $tab_683d8a704985a->addFields([$path_arquivo]);
        $row4->layout = [' col-sm-12'];

        $tab_683d8a704985a->appendPage("Observações");
        $row5 = $tab_683d8a704985a->addFields([$observacao]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([$tab_683d8a704985a]);
        $row6->layout = [' col-sm-12'];

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

        $style = new TStyle('right-panel > .container-part[page-name=MedicosPfDocumentacoesCortinaForm]');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new MedicosPfDocumentacoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $path_arquivo_dir = 'arquivos/docs_medicos_pf';  

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
            TApplication::loadPage('MedicosPfDocumentacoesCortinaList', 'onShow', $loadPageParam); 

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

                $object = new MedicosPfDocumentacoes($key); // instantiates the Active Record 

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

        if (isset($param['medicos_pf_id'])) {
            $data = new stdClass;
            $data->medicos_pf_id = $param['medicos_pf_id'];
            $this->form->setData($data);
        }
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

