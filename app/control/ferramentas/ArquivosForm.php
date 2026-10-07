<?php

class ArquivosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Arquivos';
    private static $primaryKey = 'id';
    private static $formName = 'form_ArquivosForm';

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
        $this->form->setFormTitle("Cadastro de arquivos");

        $criteria_arquivos_status_id = new TCriteria();
        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_documentacoes_id = new TCriteria();

        $nome_arquivo = new TEntry('nome_arquivo');
        $id = new THidden('id');
        $arquivos_status_id = new TDBCombo('arquivos_status_id', 'databaserede', 'ArquivosStatus', 'id', '{nome}','nome asc' , $criteria_arquivos_status_id );
        $ano_base = new TEntry('ano_base');
        $data_emissao = new TDate('data_emissao');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $documentacoes_id = new TDBCombo('documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{id}','id asc' , $criteria_documentacoes_id );

        $nome_arquivo->addValidation("Nome Arquivo", new TRequiredValidator()); 
        $arquivos_status_id->addValidation("Status", new TRequiredValidator()); 
        $ano_base->addValidation("Ano Base", new TRequiredValidator()); 
        $data_emissao->addValidation("Data emissão", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipo Documento", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documento", new TRequiredValidator()); 

        $nome_arquivo->setMaxLength(255);
        $data_emissao->setMask('dd/mm/yyyy');
        $data_emissao->setDatabaseMask('yyyy-mm-dd');
        $documentacoes_id->enableSearch();
        $arquivos_status_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $id->setSize(200);
        $ano_base->setSize('100%');
        $data_emissao->setSize(110);
        $nome_arquivo->setSize('100%');
        $documentacoes_id->setSize('100%');
        $arquivos_status_id->setSize('100%');
        $tipos_documentacoes_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Nome Arquivo: <font color='red'>*</font>", null, '14px', null, '100%'),$nome_arquivo,$id],[new TLabel("Status: <font color='red'>*</font>", null, '14px', null, '100%'),$arquivos_status_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Ano Base: <font color='red'>*</font>", null, '14px', null, '100%'),$ano_base],[new TLabel("Data emissão: <font color='red'>*</font>", null, '14px', null, '100%'),$data_emissao]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Tipo Documento: <font color='red'>*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id],[new TLabel("Documento: <font color='red'>*</font>", null, '14px', null, '100%'),$documentacoes_id]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ArquivosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Cadastro de arquivos"]));
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

            $object = new Arquivos(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

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
            TApplication::loadPage('ArquivosList', 'onShow', $loadPageParam); 

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

                $object = new Arquivos($key); // instantiates the Active Record 

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

