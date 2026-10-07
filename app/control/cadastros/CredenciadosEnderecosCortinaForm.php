<?php

class CredenciadosEnderecosCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosEnderecos';
    private static $primaryKey = 'id';
    private static $formName = 'form_EnderecosCredenciadosCortinaForm';

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
        $this->form->setFormTitle("Cadastro de endereço do credenciado");

        $criteria_tipos_enderecos_id = new TCriteria();
        $criteria_cidades_estados_id = new TCriteria();

        $cep = new TEntry('cep');
        $id = new THidden('id');
        $tipos_enderecos_id = new TDBCombo('tipos_enderecos_id', 'databaserede', 'TiposEnderecos', 'id', '{tipo_endereco}','tipo_endereco asc' , $criteria_tipos_enderecos_id );
        $button_ = new TButton('button_');
        $endereco = new TEntry('endereco');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidades_estados_id = new TDBCombo('cidades_estados_id', 'databaserede', 'Estados', 'id', '{estado}','estado asc' , $criteria_cidades_estados_id );
        $button_1 = new TButton('button_1');
        $cidades_id = new TCombo('cidades_id');
        $button_2 = new TButton('button_2');
        $iss = new TEntry('iss');
        $inscricao_municipal = new TEntry('inscricao_municipal');
        $cnes = new TEntry('cnes');

        $cidades_estados_id->setChangeAction(new TAction([$this,'onChangecidades_estados_id']));

        $cep->addValidation("Cep", new TRequiredValidator()); 
        $tipos_enderecos_id->addValidation("Tipo de endereço", new TRequiredValidator()); 
        $endereco->addValidation("endereço", new TRequiredValidator()); 
        $bairro->addValidation("Bairro", new TRequiredValidator()); 
        $cidades_id->addValidation("Cidades", new TRequiredValidator()); 

        $cep->setMask('00000-000');
        $cep->setMaxLength(9);
        $bairro->setMaxLength(100);
        $endereco->setMaxLength(150);

        $cidades_id->enableSearch();
        $tipos_enderecos_id->enableSearch();
        $cidades_estados_id->enableSearch();

        $button_1->setAction(new TAction(['EstadosCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_2->setAction(new TAction(['CidadesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_->setAction(new TAction(['TiposEnderecosCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');
        $button_2->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');
        $button_2->setImage('fas:plus #4CAF50');

        $id->setSize(200);
        $cep->setSize('100%');
        $iss->setSize('100%');
        $cnes->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $endereco->setSize('100%');
        $cidades_id->setSize('80%');
        $tipos_enderecos_id->setSize('80%');
        $cidades_estados_id->setSize('80%');
        $inscricao_municipal->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("CEP: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cep,$id],[new TLabel("Tipo de endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_enderecos_id,$button_],[new TLabel("Endereço: <font color=\"red\">*</font>", null, '14px', null, '100%'),$endereco],[new TLabel("Número:", null, '14px', null, '100%'),$numero]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-4',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Bairro: <font color=\"red\">*</font>", null, '14px', null, '100%'),$bairro],[new TLabel("Estado: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_estados_id,$button_1],[new TLabel("Cidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id,$button_2]);
        $row2->layout = ['col-sm-4','col-sm-4','col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("ISS:", null, '14px', null, '100%'),$iss],[new TLabel("Inscrição municipal:", null, '14px', null, '100%'),$inscricao_municipal],[new TLabel("CNES:", null, '14px', null, '100%'),$cnes]);
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

        // if(!empty($param['pessoas_id']))
        // {
        //     TSession::setValue('form_EnderecosCortinaForm_PessoasId', $param['pessoas_id']);
        // }

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=CredenciadosEnderecosCortinaForm]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public static function onChangecidades_estados_id($param)
    {
        try
        {

            if (isset($param['cidades_estados_id']) && $param['cidades_estados_id'])
            { 
                $criteria = TCriteria::create(['estados_id' => $param['cidades_estados_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}', 'cidade asc', $criteria, TRUE); 
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

            $object = new CredenciadosEnderecos(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->credenciados_id = TSession::getValue('form_CredenciadoForm_Credenciado_id');

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
            TApplication::loadPage('CredenciadosEnderecosCortinaList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

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

                $object = new CredenciadosEnderecos($key); // instantiates the Active Record 

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

