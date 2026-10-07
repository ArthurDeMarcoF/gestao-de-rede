<?php

class InclusaoBeneficiosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Beneficios';
    private static $primaryKey = 'id';
    private static $formName = 'form_InclusaoBeneficiosForm';

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
        $this->form->setFormTitle("Inclusão de benefícios em massa");

        $criteria_tipo_beneficio_id = new TCriteria();

        $tipo_beneficio_id = new TDBCombo('tipo_beneficio_id', 'databaserede', 'TiposBeneficios', 'id', '{tipo_beneficio}','tipo_beneficio asc' , $criteria_tipo_beneficio_id );
        $id = new THidden('id');
        $beneficio = new TEntry('beneficio');
        $ativo = new TRadioGroup('ativo');
        $identificador = new TEntry('identificador');
        $data_inicial = new TDate('data_inicial');
        $data_final = new TDate('data_final');

        $tipo_beneficio_id->addValidation("Tipo beneficio id", new TRequiredValidator()); 
        $beneficio->addValidation("Benefício ", new TRequiredValidator()); 
        $ativo->addValidation("Ativo", new TRequiredValidator()); 

        $tipo_beneficio_id->enableSearch();
        $ativo->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('Sim');
        $data_final->setMask('dd/mm/yyyy');
        $data_inicial->setMask('dd/mm/yyyy');

        $data_final->setDatabaseMask('yyyy-mm-dd');
        $data_inicial->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $ativo->setSize('100%');
        $data_final->setSize(110);
        $beneficio->setSize('100%');
        $data_inicial->setSize(110);
        $identificador->setSize('100%');
        $tipo_beneficio_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo de benefício: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipo_beneficio_id,$id],[new TLabel("Benefício: <font color=\"red\">*</font>", null, '14px', null, '100%'),$beneficio],[new TLabel("Ativo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ativo]);
        $row1->layout = ['col-sm-3','col-sm-5','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Identificador:", null, '14px', null, '100%'),$identificador],[new TLabel("Data inicial:", null, '14px', null, '100%'),$data_inicial],[new TLabel("Data final:", null, '14px', null, '100%'),$data_final]);
        $row2->layout = ['col-sm-4','col-sm-2','col-sm-2'];

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
            $container->add(TBreadCrumb::create(["Ferramentas","Inclusão de benefícios em massa"]));
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

            $cooperados = Cooperados::where('ativo', '=', 'Sim')
                                    ->load();
            $qtde_registros = 0;
            foreach ($cooperados as $cooperado)
            {
                $cooperados_benef = new Beneficios();
                $cooperados_benef->cooperados_id = $cooperado->id;
                $cooperados_benef->tipo_beneficio_id = $object->tipo_beneficio_id;
                $cooperados_benef->beneficio = $object->beneficio;
                $cooperados_benef->identificador = $object->identificador;
                $cooperados_benef->data_inicial = $object->data_inicial;
                $cooperados_benef->data_final = $object->data_final;
                $cooperados_benef->ativo = $object->ativo;
                $cooperados_benef->store();
                $qtde_registros = $qtde_registros + 1;
            }

/*
            $object->store(); // save the object 
*/

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Este benefíco foi incluído para <b> $qtde_registros </b> cooperados", $messageAction);

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

