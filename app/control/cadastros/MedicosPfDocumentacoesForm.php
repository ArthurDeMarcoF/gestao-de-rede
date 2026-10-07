<?php

class MedicosPfDocumentacoesForm extends TPage
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
        $this->form->setFormTitle("Cadastro de médicos PF documentações");

        $criteria_medicos_pf_id = new TCriteria();
        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_documentacoes_id = new TCriteria();

        $medicos_pf_id = new TDBCombo('medicos_pf_id', 'databaserede', 'MedicosPf', 'id', '{id}','id asc' , $criteria_medicos_pf_id );
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $documentacoes_id = new TDBCombo('documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{id}','id asc' , $criteria_documentacoes_id );
        $entregue = new TRadioGroup('entregue');
        $ativo = new TRadioGroup('ativo');
        $emissao = new TDate('emissao');
        $data_alerta = new TDate('data_alerta');
        $validade = new TDate('validade');
        $conteudo = new THtmlEditor('conteudo');
        $path_arquivo = new TFile('path_arquivo');
        $observacao = new THtmlEditor('observacao');

        $medicos_pf_id->addValidation("Médicos PF", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipos de documentações", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentações ", new TRequiredValidator()); 
        $entregue->addValidation("Entregue", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 

        $path_arquivo->enableFileHandling();
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $entregue->addItems(["Sim"=>"Sim","Não"=>"Não"]);

        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue('Sim');
        $entregue->setValue('Sim');

        $ativo->setUseButton();
        $entregue->setUseButton();

        $medicos_pf_id->enableSearch();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');
        $data_alerta->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');
        $data_alerta->setDatabaseMask('yyyy-mm-dd');

        $emissao->setSize(110);
        $ativo->setSize('100%');
        $validade->setSize(110);
        $entregue->setSize('100%');
        $data_alerta->setSize(110);
        $path_arquivo->setSize('100%');
        $medicos_pf_id->setSize('100%');
        $conteudo->setSize('100%', 200);
        $observacao->setSize('100%', 200);
        $documentacoes_id->setSize('100%');
        $tipos_documentacoes_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Médicos PF:  <font color=\"red\">*</font>", null, '14px', null, '100%'),$medicos_pf_id],[new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id]);
        $row1->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue],[new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta],[new TLabel("Validade:", null, '14px', null, '100%'),$validade]);
        $row2->layout = [' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-2'];

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

        $btn_onaction = $this->form->addAction("Novo", new TAction([$this, 'onAction']), 'fas:plus #FFFFFF');
        $this->btn_onaction = $btn_onaction;
        $btn_onaction->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MedicosPfDocumentacoesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de médicos PF documentações"]));
        }
        $container->add($this->form);

        parent::add($container);

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
            TApplication::loadPage('MedicosPfDocumentacoesList', 'onShow', $loadPageParam); 

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onAction($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

