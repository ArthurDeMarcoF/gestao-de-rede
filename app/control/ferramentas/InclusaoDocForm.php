<?php

class InclusaoDocForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosDocumentacoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_InclusaoDocForm';

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
        $this->form->setFormTitle("Inclusão de documentos em massa");

        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_documentacoes_id = new TCriteria();

        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $id = new THidden('id');
        $documentacoes_id = new TDBCombo('documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}','id asc' , $criteria_documentacoes_id );
        $button_1 = new TButton('button_1');
        $ativo = new TRadioGroup('ativo');
        $entregue = new TRadioGroup('entregue');
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $data_alerta = new TDate('data_alerta');
        $conteudo = new THtmlEditor('conteudo');
        $observacao = new THtmlEditor('observacao');

        $tipos_documentacoes_id->addValidation("Tipos documentacoes id", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documentacoes id", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 
        $entregue->addValidation("Entregue", new TRequiredValidator()); 

        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $entregue->addItems(["Sim"=>"Sim","Não"=>"Não"]);

        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue('Sim');
        $entregue->setValue('Não');

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
        $conteudo->setSize('100%', 200);
        $documentacoes_id->setSize('85%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('85%');

        $this->form->appendPage("Dados Gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Tipo de documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_,$id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1],[new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo]);
        $row1->layout = [' col-sm-5',' col-sm-5',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Entregue: <font color=\"red\">*</font>", null, '14px', null, '100%'),$entregue],[new TLabel("Emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Validade:", null, '14px', null, '100%'),$validade],[new TLabel("Data de alerta:", null, '14px', null, '100%'),$data_alerta]);
        $row2->layout = ['col-sm-3','col-sm-2','col-sm-2','col-sm-2'];

        $this->form->appendPage("Conteúdo");
        $row3 = $this->form->addFields([$conteudo]);
        $row3->layout = [' col-sm-12'];

        $this->form->appendPage("Observações");
        $row4 = $this->form->addFields([$observacao]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Gerar", new TAction([$this, 'onSave']), 'fas:cogs #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-success'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Inclusão de documentos em massa"]));
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

            $object = new CooperadosDocumentacoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $cooperados = Cooperados::where('ativo', '=', 'Sim')
                                    ->load();
            $qtde_registros = 0;
            // buscar todos os cooperados ativos e adicionar o documento preenchido no form
            foreach ($cooperados as $cooperado)
            {
                $cooperados_doc = new CooperadosDocumentacoes();
                $cooperados_doc->cooperados_id = $cooperado->id;
                $cooperados_doc->tipos_documentacoes_id = $object->tipos_documentacoes_id;
                $cooperados_doc->documentacoes_id = $object->documentacoes_id;
                $cooperados_doc->emissao = $object->emissao;
                $cooperados_doc->validade = $object->validade;
                $cooperados_doc->data_alerta = $object->data_alerta;
                $cooperados_doc->renovacao = $object->renovacao;
                $cooperados_doc->entregue = $object->entregue;
                $cooperados_doc->ativo = $object->ativo;
                $cooperados_doc->conteudo = $object->conteudo;
                $cooperados_doc->observacao = $object->observacao;
                $cooperados_doc->store();
                $qtde_registros = $qtde_registros + 1;
            }

            /*
            $object->store(); // save the object 
*/

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Este documento foi incluído para <b> $qtde_registros </b> cooperados", $messageAction);

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

                $object = new CooperadosDocumentacoes($key); // instantiates the Active Record 

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

