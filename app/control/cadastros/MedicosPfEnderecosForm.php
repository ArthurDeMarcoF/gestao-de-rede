<?php

class MedicosPfEnderecosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPfEnderecos';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfEnderecosForm';

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
        $this->form->setFormTitle("Cadastro de endereços de médicos PF");

        $criteria_medicos_pf_id = new TCriteria();
        $criteria_tipos_enderecos_id = new TCriteria();
        $criteria_cidades_estados_id = new TCriteria();
        $criteria_cidades_id = new TCriteria();

        $medicos_pf_id = new TDBCombo('medicos_pf_id', 'databaserede', 'MedicosPf', 'id', '{id}','id asc' , $criteria_medicos_pf_id );
        $tipos_enderecos_id = new TDBCombo('tipos_enderecos_id', 'databaserede', 'TiposEnderecos', 'id', '{id}','id asc' , $criteria_tipos_enderecos_id );
        $cep = new TEntry('cep');
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidades_estados_id = new TDBCombo('cidades_estados_id', 'databaserede', 'Estados', 'id', '{estado}','estado asc' , $criteria_cidades_estados_id );
        $cidades_id = new TDBCombo('cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidades_id );
        $iss = new TEntry('iss');
        $im = new TEntry('im');
        $cnes = new TEntry('cnes');

        $medicos_pf_id->addValidation("Médicos PF", new TRequiredValidator()); 
        $tipos_enderecos_id->addValidation("Tipo de endereço", new TRequiredValidator()); 
        $cep->addValidation("CEP", new TRequiredValidator()); 
        $endereco->addValidation("Endereço", new TRequiredValidator()); 
        $bairro->addValidation("Bairro", new TRequiredValidator()); 
        $cidades_id->addValidation("Cidades id", new TRequiredValidator()); 

        $cep->setMaxLength(9);
        $bairro->setMaxLength(100);
        $endereco->setMaxLength(150);

        $cidades_id->enableSearch();
        $medicos_pf_id->enableSearch();
        $tipos_enderecos_id->enableSearch();
        $cidades_estados_id->enableSearch();

        $im->setSize('100%');
        $cep->setSize('100%');
        $iss->setSize('100%');
        $cnes->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $endereco->setSize('100%');
        $cidades_id->setSize('100%');
        $medicos_pf_id->setSize('100%');
        $tipos_enderecos_id->setSize('100%');
        $cidades_estados_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Médico PF: <font color=\"red\">*</font>", null, '14px', null, '100%'),$medicos_pf_id],[new TLabel("Tipo de endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_enderecos_id],[new TLabel("CEP: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cep],[new TLabel("Endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$endereco]);
        $row1->layout = [' col-sm-4',' col-sm-2',' col-sm-2',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Número:", null, '14px', null, '100%'),$numero],[new TLabel("Bairro: <font color=\"red\">*</font>", null, '14px', null, '100%'),$bairro],[new TLabel("Estado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_estados_id]);
        $row2->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row3 = $this->form->addFields([new TLabel("Cidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id],[new TLabel("ISS:", null, '14px', null, '100%'),$iss],[new TLabel("Inscrição municipal:", null, '14px', null, '100%'),$im],[new TLabel("CNES:", null, '14px', null, '100%'),$cnes]);
        $row3->layout = [' col-sm-4',' col-sm-2',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MedicosPfEnderecosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de endereços de médicos PF"]));
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

            $object = new MedicosPfEnderecos(); // create an empty object 

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
            TApplication::loadPage('MedicosPfEnderecosList', 'onShow', $loadPageParam); 

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

                $object = new MedicosPfEnderecos($key); // instantiates the Active Record 

                                $object->cidades_estados_id = $object->cidades->estados_id;

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

