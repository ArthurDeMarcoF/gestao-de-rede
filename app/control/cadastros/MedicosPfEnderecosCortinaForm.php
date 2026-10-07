<?php

class MedicosPfEnderecosCortinaForm extends TPage
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
        $this->form->setFormTitle("Endereços de consultórios de especialidades  ");

        $criteria_tipos_enderecos_id = new TCriteria();
        $criteria_cidades_estados_id = new TCriteria();
        $criteria_cidades_id = new TCriteria();

        $cep = new TEntry('cep');
        $button_buscar = new TButton('button_buscar');
        $id = new THidden('id');
        $medicos_pf_id = new THidden('medicos_pf_id');
        $tipos_enderecos_id = new TDBCombo('tipos_enderecos_id', 'databaserede', 'TiposEnderecos', 'id', '{tipo_endereco}','tipo_endereco asc' , $criteria_tipos_enderecos_id );
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidades_estados_id = new TDBCombo('cidades_estados_id', 'databaserede', 'Estados', 'id', '{estado}','estado asc' , $criteria_cidades_estados_id );
        $cidades_id = new TDBCombo('cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidades_id );
        $iss = new TEntry('iss');
        $im = new TEntry('im');
        $cnes = new TEntry('cnes');
        $imprime_guia_medico = new TRadioGroup('imprime_guia_medico');

        $cep->addValidation("CEP", new TRequiredValidator()); 
        $tipos_enderecos_id->addValidation("Tipo de endereço", new TRequiredValidator()); 
        $endereco->addValidation("Endereço", new TRequiredValidator()); 
        $bairro->addValidation("Bairro", new TRequiredValidator()); 
        $cidades_id->addValidation("Cidades id", new TRequiredValidator()); 
        $imprime_guia_medico->addValidation("imprime no guia médico", new TRequiredValidator()); 

        $button_buscar->setAction(new TAction([$this, 'onBuscarCep']), "Buscar");
        $button_buscar->addStyleClass('btn-primary');
        $button_buscar->setImage('fas:search #FFFFFF');
        $tipos_enderecos_id->configureNoResultsQuickRegister(new TAction(['TiposEnderecosCortinaForm', 'onShow']), "", "fas:plus #4CAF50", "btn-default");
        $imprime_guia_medico->addItems(["S"=>"Sim","N"=>"Não"]);
        $imprime_guia_medico->setLayout('horizontal');
        $imprime_guia_medico->setValue('N');
        $imprime_guia_medico->setUseButton();
        $cnes->setMask('9999999', true);
        $cep->setMask('99999-999', true);

        $cidades_id->configureNoResultsCreateButton(new TAction(['CidadesCortinaForm', 'onShow']), "", "fas:plus #4CAF50", "btn-default");
        $cidades_estados_id->configureNoResultsCreateButton(new TAction(['EstadosCortinaForm', 'onShow']), "", "fas:plus #4CAF50", "btn-default");

        $cep->setMaxLength(9);
        $bairro->setMaxLength(100);
        $endereco->setMaxLength(150);

        $cidades_id->enableSearch();
        $tipos_enderecos_id->enableSearch();
        $cidades_estados_id->enableSearch();

        $id->setSize(200);
        $cep->setSize('70%');
        $im->setSize('100%');
        $iss->setSize('100%');
        $cnes->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $endereco->setSize('100%');
        $medicos_pf_id->setSize(200);
        $cidades_id->setSize('100%');
        $imprime_guia_medico->setSize(80);
        $tipos_enderecos_id->setSize('100%');
        $cidades_estados_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("CEP: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cep,$button_buscar,$id,$medicos_pf_id],[new TLabel("Tipo de endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_enderecos_id],[new TLabel("Endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$endereco],[new TLabel("Número:", null, '14px', null, '100%'),$numero]);
        $row1->layout = [' col-sm-4',' col-sm-2','col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Bairro: <font color=\"red\">*</font>", null, '14px', null, '100%'),$bairro],[new TLabel("Estado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_estados_id],[new TLabel("Cidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id]);
        $row2->layout = [' col-sm-4',' col-sm-4','col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("ISS:", null, '14px', null, '100%'),$iss],[new TLabel("Inscrição municipal:", null, '14px', null, '100%'),$im],[new TLabel("CNES:", null, '14px', null, '100%'),$cnes],[new TLabel("Imprime no guia médico?", null, '14px', null, '100%'),$imprime_guia_medico]);
        $row3->layout = [' col-sm-3','col-sm-3','col-sm-3',' col-sm-3'];

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

        $style = new TStyle('right-panel > .container-part[page-name=MedicosPfEnderecosCortinaForm]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public static function onBuscarCep($param = null) 
    {
        try 
        {
            if (!empty($param['cep'])) {
                TTransaction::open(self::$database);

                $dadosCep = CEPService::get($param['cep']);

                if ($dadosCep) {
                    $object = new stdClass();
                    $object->endereco           = $dadosCep->rua;
                    $object->bairro             = $dadosCep->bairro;
                    $object->cep                = $dadosCep->cep;
                    $object->cidades_id         = $dadosCep->cidades_id;
                    $object->cidades_estados_id = $dadosCep->estados_id;

                    TDBCombo::reloadFromModel(self::$formName, 'cidades_id', self::$database, 'Cidades', 'id', '{cidade}', 'cidade', null, true);
                    TDBCombo::reloadFromModel(self::$formName, 'cidades_estados_id', self::$database, 'Estados', 'id', '{estado}', 'estado', null, true);

                    TForm::sendData(self::$formName, $object, false, false, false, 500);    
                }

                TTransaction::close();
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
            TApplication::loadPage('MedicosPfEnderecosCortinaList', 'onShow', $loadPageParam); 

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

                $object = new MedicosPfEnderecos($key); // instantiates the Active Record 

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

        if (isset($param['medicos_pf_id'])) {
            $data = new stdClass;
            $data->medicos_pf_id = $param['medicos_pf_id'];
            $this->form->setData($data);
        }
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

