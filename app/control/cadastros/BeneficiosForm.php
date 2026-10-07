<?php

class BeneficiosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Beneficios';
    private static $primaryKey = 'id';
    private static $formName = 'form_BeneficiosForm';

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
        $this->form->setFormTitle("Cadastro de benefícios");

        $criteria_cooperados_id = new TCriteria();
        $criteria_tipo_beneficio_id = new TCriteria();

        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $id = new THidden('id');
        $tipo_beneficio_id = new TDBCombo('tipo_beneficio_id', 'databaserede', 'TiposBeneficios', 'id', '{tipo_beneficio}','tipo_beneficio asc' , $criteria_tipo_beneficio_id );
        $beneficio = new TEntry('beneficio');
        $ativo = new TRadioGroup('ativo');
        $identificador = new TEntry('identificador');
        $valor = new TEntry('valor');
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');

        $tipo_beneficio_id->addValidation("Tipo de benefício", new TRequiredValidator()); 
        $beneficio->addValidation("Benefício ", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 

        $ativo->addItems(["Sim"=>" Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('Sim');
        $cooperados_id->enableSearch();
        $tipo_beneficio_id->enableSearch();

        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $ativo->setSize('100%');
        $valor->setSize('100%');
        $data_final->setSize(110);
        $beneficio->setSize('100%');
        $data_inicial->setSize(110);
        $cooperados_id->setSize('100%');
        $identificador->setSize('100%');
        $tipo_beneficio_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cooperado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cooperados_id,$id],[new TLabel("Tipo de benefício: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipo_beneficio_id],[new TLabel("Benefício: <font color=\"red\">*</font>", null, '14px', null, '100%'),$beneficio]);
        $row1->layout = ['col-sm-4','col-sm-4','col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo],[new TLabel("Identificador:", null, '14px', null, '100%'),$identificador],[new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final]);
        $row2->layout = [' col-sm-3',' col-sm-3','col-sm-2','col-sm-2','col-sm-2'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['BeneficiosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de beneficios"]));
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

            $object = new Beneficios(); // create an empty object 

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
            TApplication::loadPage('BeneficiosList', 'onShow', $loadPageParam); 

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

                $object = new Beneficios($key); // instantiates the Active Record 

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

