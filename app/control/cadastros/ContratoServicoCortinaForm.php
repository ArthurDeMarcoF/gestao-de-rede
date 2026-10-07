<?php

class ContratoServicoCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'ContratoServico';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContratoServicoCortinaForm';

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
        $this->form->setFormTitle("Cadastro de serviços do contrato");

        $criteria_dm_situacao = new TCriteria();

        $filterVar = "contrato_servico";
        $criteria_dm_situacao->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_situacao";
        $criteria_dm_situacao->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new THidden('id');
        $contrato_id = new THidden('contrato_id');
        $servico = new TEntry('servico');
        $dt_inicio = new TDate('dt_inicio');
        $dt_termino = new TDate('dt_termino');
        $dm_situacao = new TDBCombo('dm_situacao', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_situacao );

        $servico->addValidation("Serviço", new TRequiredValidator()); 
        $dm_situacao->addValidation("Situação", new TRequiredValidator()); 

        $servico->setMaxLength(150);
        $dm_situacao->enableSearch();
        $contrato_id->setValue($param["contrato_id"] ?? "");
        $dm_situacao->setValue(DMService::obterValPadrao('contrato_servico', 'dm_situacao'));

        $dt_inicio->setMask('dd/mm/yyyy');
        $dt_termino->setMask('dd/mm/yyyy');

        $dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $dt_termino->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $servico->setSize('100%');
        $contrato_id->setSize(200);
        $dt_inicio->setSize('100%');
        $dt_termino->setSize('100%');
        $dm_situacao->setSize('100%');

        $row1 = $this->form->addFields([$id,$contrato_id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Serviço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$servico]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Início:", null, '14px', null, '100%'),$dt_inicio],[new TLabel("Término:", null, '14px', null, '100%'),$dt_termino],[new TLabel("Situação: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_situacao]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

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

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new ContratoServico(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->servico = ucwords(strtolower(trim($object->servico)));

            if (empty($object->contrato_id))
                $object->contrato_id = TSession::getValue('ContratoForm_key');

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
            TApplication::loadPage('ContratoServicoCortinaList', 'onShow', $loadPageParam); 

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

                $object = new ContratoServico($key); // instantiates the Active Record 

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

