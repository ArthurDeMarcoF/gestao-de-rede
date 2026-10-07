<?php

class FilaEmailForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'FilaEmail';
    private static $primaryKey = 'id';
    private static $formName = 'form_FilaEmailForm';

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
        $this->form->setFormTitle("Cadastro de e-mail");

        $criteria_dm_status = new TCriteria();

        $filterVar = "dm_status_email";
        $criteria_dm_status->add(new TFilter('codigo', '=', $filterVar)); 

        $id = new TEntry('id');
        $data_criacao = new TDateTime('data_criacao');
        $data_alteracao = new TDateTime('data_alteracao');
        $data_envio = new TDateTime('data_envio');
        $dm_status = new TDBCombo('dm_status', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_dm_status );
        $destinatario = new TEntry('destinatario');
        $assunto = new TEntry('assunto');
        $corpo = new THtmlEditor('corpo');
        $anexos = new TMultiFile('anexos');

        $dm_status->addValidation("Status", new TRequiredValidator()); 
        $destinatario->addValidation("Destinatário", new TRequiredValidator()); 
        $assunto->addValidation("Assunto", new TRequiredValidator()); 
        $corpo->addValidation("Corpo", new TRequiredValidator()); 

        $dm_status->setValue('P');
        $dm_status->enableSearch();
        $anexos->enableFileHandling();
        $assunto->setMaxLength(256);
        $destinatario->setMaxLength(256);

        $data_envio->setMask('dd/mm/yyyy hh:ii');
        $data_criacao->setMask('dd/mm/yyyy hh:ii');
        $data_alteracao->setMask('dd/mm/yyyy hh:ii');

        $data_envio->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_criacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_alteracao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $anexos->setSize('100%');
        $data_envio->setSize(150);
        $assunto->setSize('100%');
        $data_criacao->setSize(150);
        $dm_status->setSize('100%');
        $corpo->setSize('100%', 300);
        $data_alteracao->setSize(150);
        $destinatario->setSize('100%');

        $id->setEditable(false);
        $corpo->setEditable(false);
        $anexos->setEditable(false);
        $assunto->setEditable(false);
        $dm_status->setEditable(false);
        $data_envio->setEditable(false);
        $data_criacao->setEditable(false);
        $destinatario->setEditable(false);
        $data_alteracao->setEditable(false);

        $row1 = $this->form->addFields([new TLabel("ID", null, '14px', null, '100%'),$id],[new TLabel("Data de criação", null, '14px', null, '100%'),$data_criacao],[new TLabel("Data de alteração:", null, '14px', null, '100%'),$data_alteracao],[new TLabel("Data de envio", null, '14px', null, '100%'),$data_envio]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Status", null, '14px', null, '100%'),$dm_status],[new TLabel("Destinatário", null, '14px', null, '100%'),$destinatario]);
        $row2->layout = [' col-sm-3',' col-sm-9'];

        $row3 = $this->form->addFields([new TLabel("Assunto", null, '14px', null, '100%'),$assunto]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Corpo", null, '14px', null, '100%'),$corpo]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([new TLabel("Anexos", null, '14px', null, '100%'),$anexos]);
        $row5->layout = [' col-sm-12'];

        // create the form actions
        $btn_onenviar = $this->form->addAction("Enviar", new TAction([$this, 'onEnviar'],['static' => 1]), 'fas:cloud-upload-alt #4CAF50');
        $this->btn_onenviar = $btn_onenviar;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['FilaEmailList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["e-Mails","Cadastro de e-mail"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onEnviar($param = null) 
    {
        try 
        {
            $aux_resp = apiService::execReq(apiService::urlEnviaEmail, null, $param['id']);

            if (!empty($aux_resp))
                TToast::show("success", $aux_resp, "topRight", "fas:check-circle");

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

                $object = new FilaEmail($key); // instantiates the Active Record 

                                $object->anexos  = FilaEmailAnexo::where('fila_emails_id', '=', $object->id)->getIndexedArray('id','caminho');

                if (empty($object->corpo)){
                    $object->corpo = EmailsService::obterCorpo($object->id);
                }

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

