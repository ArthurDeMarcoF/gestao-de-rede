<?php

class CooperadosCapitalCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosCapital';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosCapitalCortinaForm';

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
        $this->form->setFormTitle("Cadastro do capital social");

        $criteria_dm_capital_social = new TCriteria();

        $filterVar = "cooperados_capital";
        $criteria_dm_capital_social->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_capital_social";
        $criteria_dm_capital_social->add(new TFilter('atributo', '=', $filterVar)); 

        $cooperados_id = new THidden('cooperados_id');
        $id = new THidden('id');
        $dm_capital_social = new TDBCombo('dm_capital_social', 'databaserede', 'VDominioValorCol', 'valor', '{valor} - {mascara}','sequencia asc' , $criteria_dm_capital_social );
        $nr_parcela = new TEntry('nr_parcela');
        $data_aquisicao = new TDate('data_aquisicao');
        $valor = new TNumeric('valor', '2', ',', '.' );

        $dm_capital_social->addValidation("Grupo", new TRequiredValidator()); 
        $data_aquisicao->addValidation("Data", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 

        $cooperados_id->setValue($param["cooperados_id"] ?? "");
        $dm_capital_social->enableSearch();
        $data_aquisicao->setMask('dd/mm/yyyy');
        $data_aquisicao->setDatabaseMask('yyyy-mm-dd');
        $id->setSize(200);
        $valor->setSize('100%');
        $cooperados_id->setSize(200);
        $nr_parcela->setSize('100%');
        $data_aquisicao->setSize(110);
        $dm_capital_social->setSize('100%');

        $row1 = $this->form->addFields([$cooperados_id,$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Grupo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_capital_social]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Parcela:", null, '14px', null, '100%'),$nr_parcela],[new TLabel("Data: <font color=\"red\">*</font>", null, '14px', null, '100%'),$data_aquisicao],[new TLabel("Valor: <font color=\"red\">*</font>", null, '14px', null, '100%'),$valor]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new CooperadosCapital(); // create an empty object 

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
            TApplication::loadPage('CooperadosCapitalCortinaList', 'onShow', $loadPageParam); 

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

                $object = new CooperadosCapital($key); // instantiates the Active Record 

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

        $cooperados_id = TSession::getvalue('form_CooperadoContatosList_Cooperado_id');

        if (isset($cooperados_id)) {
            $data = new stdClass;
            $data->cooperados_id = $cooperados_id;
            $this->form->setData($data);
        }
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

