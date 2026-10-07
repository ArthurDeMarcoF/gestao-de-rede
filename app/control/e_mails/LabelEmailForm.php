<?php

class LabelEmailForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'LabelEmail';
    private static $primaryKey = 'id';
    private static $formName = 'form_LabelEmailForm';

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
        $this->form->setFormTitle("Cadastro de label de email");

        $criteria_dm_tipo = new TCriteria();

        $filterVar = "label_email";
        $criteria_dm_tipo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo";
        $criteria_dm_tipo->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new TEntry('id');
        $label = new TEntry('label');
        $chave = new TEntry('chave');
        $descricao = new TEntry('descricao');
        $dm_tipo = new TDBCombo('dm_tipo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo );
        $titulo = new TEntry('titulo');
        $bhelper_67a4de7423ce7 = new BHelper();
        $script_sql = new TText('script_sql');

        $label->addValidation("Label", new TRequiredValidator()); 
        $chave->addValidation("Chave", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $dm_tipo->addValidation("tipo", new TRequiredValidator()); 
        $script_sql->addValidation("SQL", new TRequiredValidator()); 

        $id->setEditable(false);
        $dm_tipo->enableSearch();
        $bhelper_67a4de7423ce7->enableHover();
        $bhelper_67a4de7423ce7->setSide("left");
        $bhelper_67a4de7423ce7->setIcon(new TImage("fas:question #CBCBCB"));
        $bhelper_67a4de7423ce7->setContent("Para a chave use @chave<br>Para labels apenas a primeira coluna é utilizada<br>Ex: SELECT nome FROM cooperados WHERE crm = @chave");
        $label->setMaxLength(45);
        $chave->setMaxLength(62);
        $titulo->setMaxLength(45);
        $descricao->setMaxLength(255);

        $id->setSize('100%');
        $label->setSize('100%');
        $chave->setSize('100%');
        $titulo->setSize('100%');
        $dm_tipo->setSize('100%');
        $descricao->setSize('100%');
        $script_sql->setSize('100%', 400);
        $bhelper_67a4de7423ce7->setSize('14');

        $script_sql->style = 'font-family: Andale mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Lucida Console, Monaco, Consolas, Droid Sans monospace, Monospace;';

        $row1 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'),$id],[new TLabel("Label: <font color=\"red\">*</font>", null, '14px', null, '100%'),$label],[new TLabel("Chave: <font color=\"red\">*</font>", null, '14px', null, '100%'),$chave]);
        $row1->layout = [' col-sm-2',' col-sm-6',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Descrição: <font color=\"red\">*</font>", null, '14px', null, '100%'),$descricao],[new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo]);
        $row2->layout = [' col-sm-8',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Título:", null, '14px', null, '100%'),$titulo]);
        $row3->layout = ['col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("SQL: <font color=\"red\">*</font>", null, '14px', null),$bhelper_67a4de7423ce7,$script_sql]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['LabelEmailList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["e-Mails","Cadastro de label de email"]));
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

            $object = new LabelEmail(); // create an empty object 

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
            TApplication::loadPage('LabelEmailList', 'onShow', $loadPageParam); 

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

                $object = new LabelEmail($key); // instantiates the Active Record 

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

