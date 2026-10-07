<?php

class CooperadosEnderecosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'EnderecosCooperados';
    private static $primaryKey = 'id';
    private static $formName = 'form_EnderecosForm';

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
        $this->form->setFormTitle("Endereços");

        $criteria_cooperados_id = new TCriteria();
        $criteria_tipos_enderecos_id = new TCriteria();
        $criteria_cidades_estados_id = new TCriteria();

        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $id = new THidden('id');
        $tipos_enderecos_id = new TDBCombo('tipos_enderecos_id', 'databaserede', 'TiposEnderecos', 'id', '{tipo_endereco}','tipo_endereco asc' , $criteria_tipos_enderecos_id );
        $cep = new TEntry('cep');
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidades_estados_id = new TDBCombo('cidades_estados_id', 'databaserede', 'Estados', 'id', '{estado}','estado asc' , $criteria_cidades_estados_id );
        $cidades_id = new TCombo('cidades_id');
        $iss = new TEntry('iss');
        $im = new TEntry('im');
        $cnes = new TEntry('cnes');

        $cidades_estados_id->setChangeAction(new TAction([$this,'onChangecidades_estados_id']));

        $tipos_enderecos_id->addValidation("Tipo de endereço", new TRequiredValidator()); 
        $cep->addValidation("CEP ", new TRequiredValidator()); 
        $endereco->addValidation("Endereço ", new TRequiredValidator()); 
        $bairro->addValidation("Bairro", new TRequiredValidator()); 
        $cidades_estados_id->addValidation("Estado", new TRequiredValidator()); 
        $cidades_id->addValidation("Cidades", new TRequiredValidator()); 

        $cep->setMaxLength(9);
        $endereco->setMaxLength(150);

        $cidades_id->enableSearch();
        $cooperados_id->enableSearch();
        $tipos_enderecos_id->enableSearch();
        $cidades_estados_id->enableSearch();

        $id->setSize(200);
        $im->setSize('100%');
        $cep->setSize('100%');
        $iss->setSize('100%');
        $cnes->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $endereco->setSize('100%');
        $cidades_id->setSize('100%');
        $cooperados_id->setSize('100%');
        $tipos_enderecos_id->setSize('100%');
        $cidades_estados_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cooperado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cooperados_id,$id],[new TLabel("Tipo de endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_enderecos_id],[new TLabel("CEP:  <font color=\"red\">*</font>", null, '14px', null, '100%'),$cep],[new TLabel("Endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$endereco]);
        $row1->layout = ['col-sm-4',' col-sm-2','col-sm-2','col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Número:", null, '14px', null, '100%'),$numero],[new TLabel("Bairro: <font color=\"red\">*</font>", null, '14px', null, '100%'),$bairro],[new TLabel("Estado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_estados_id]);
        $row2->layout = ['col-sm-2',' col-sm-6','col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Cidades: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id],[new TLabel("ISS:", null, '14px', null, '100%'),$iss],[new TLabel("Inscrição municipal:", null, '14px', null, '100%'),$im],[new TLabel("CNES:", null, '14px', null, '100%'),$cnes]);
        $row3->layout = ['col-sm-4',' col-sm-2',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosEnderecosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de enderecos"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangecidades_estados_id($param)
    {
        try
        {

            if (isset($param['cidades_estados_id']) && $param['cidades_estados_id'])
            { 
                $criteria = TCriteria::create(['estados_id' => $param['cidades_estados_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}', 'id asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'cidades_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new EnderecosCooperados(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $this->fireEvents($object);

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
            TApplication::loadPage('CooperadosEnderecosList', 'onShow', $loadPageParam); 

        }
        catch (Exception $e) // in case of exception
        {

            $this->fireEvents($this->form->getData()); 

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

                $object = new EnderecosCooperados($key); // instantiates the Active Record 

                                $object->cidades_estados_id = $object->cidades->estados_id;

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->cidades_estados_id))
            {
                $value = $object->cidades_estados_id;

                $obj->cidades_estados_id = $value;
            }
            if(isset($object->cidades_id))
            {
                $value = $object->cidades_id;

                $obj->cidades_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->cidades->estados_id))
            {
                $value = $object->cidades->estados_id;

                $obj->cidades_estados_id = $value;
            }
            if(isset($object->cidades_id))
            {
                $value = $object->cidades_id;

                $obj->cidades_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public static function getFormName()
    {
        return self::$formName;
    }

}

