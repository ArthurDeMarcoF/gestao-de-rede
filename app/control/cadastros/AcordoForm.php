<?php

class AcordoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Acordo';
    private static $primaryKey = 'id';
    private static $formName = 'form_AcordoForm';

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
        $this->form->setFormTitle("Cadastro de acordo");

        $criteria_especialidades_id = new TCriteria();

        $id = new TEntry('id');
        $nr_jet = new TEntry('nr_jet');
        $nr_nota = new TEntry('nr_nota');
        $especialidades_id = new TDBCombo('especialidades_id', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades_id );
        $medico = new TEntry('medico');
        $carteirinha = new TEntry('carteirinha');
        $beneficiario = new TEntry('beneficiario');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $dt_atendimento = new TDate('dt_atendimento');
        $dt_nota = new TDate('dt_nota');
        $dt_pagamento = new TDate('dt_pagamento');
        $observacao = new TText('observacao');
        $table_arquivos = new BPageContainer();

        $carteirinha->setExitAction(new TAction([$this,'onBuscarBenef']));

        $especialidades_id->addValidation("Especialidades id", new TRequiredValidator()); 
        $medico->addValidation("Médico", new TRequiredValidator()); 
        $carteirinha->addValidation("Carteirinha", new TRequiredValidator()); 
        $beneficiario->addValidation("Beneficiário", new TRequiredValidator()); 

        $id->setEditable(false);
        $especialidades_id->enableSearch();
        $table_arquivos->setAction(new TAction(['AcordoArquivoCortinaList', 'onShow']));
        $table_arquivos->setId('b6734d30e26be9');
        $table_arquivos->hide();
        $dt_nota->setDatabaseMask('yyyy-mm-dd');
        $dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $dt_atendimento->setDatabaseMask('yyyy-mm-dd');

        $nr_nota->setMaxLength(20);
        $medico->setMaxLength(150);
        $carteirinha->setMaxLength(21);
        $beneficiario->setMaxLength(150);

        $dt_nota->setMask('dd/mm/yyyy');
        $dt_pagamento->setMask('dd/mm/yyyy');
        $dt_atendimento->setMask('dd/mm/yyyy');
        $carteirinha->setMask('0000.0000.000000-00 0');

        $id->setSize('100%');
        $valor->setSize('100%');
        $nr_jet->setSize('100%');
        $medico->setSize('100%');
        $nr_nota->setSize('100%');
        $dt_nota->setSize('100%');
        $carteirinha->setSize('100%');
        $beneficiario->setSize('100%');
        $dt_pagamento->setSize('100%');
        $dt_atendimento->setSize('100%');
        $observacao->setSize('100%', 70);
        $table_arquivos->setSize('100%');
        $especialidades_id->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_arquivos->add($loadingContainer);

        $this->table_arquivos = $table_arquivos;

        $this->form->appendPage("Dados Gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("JET:", null, '14px', null, '100%'),$nr_jet],[new TLabel("Nota:", null, '14px', null, '100%'),$nr_nota]);
        $row1->layout = [' col-sm-2',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Especialidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$especialidades_id],[new TLabel("Médico: <font color=\"red\">*</font>", null, '14px', null, '100%'),$medico]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Carteirinha: <font color=\"red\">*</font>", null, '14px', null, '100%'),$carteirinha],[new TLabel("Beneficiário: <font color=\"red\">*</font>", null, '14px', null, '100%'),$beneficiario]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Data atendimento:", null, '14px', null, '100%'),$dt_atendimento],[new TLabel("Data emissão NF:", null, '14px', null, '100%'),$dt_nota],[new TLabel("Data de pagamento:", null, '14px', null, '100%'),$dt_pagamento]);
        $row4->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Arquivos");
        $row6 = $this->form->addFields([$table_arquivos]);
        $row6->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['AcordoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de acordo"]));
        }
        $container->add($this->form);

        if (ParamService::valor('sn_buscar_nome_benef_acordos') == 'S')
            TEntry::disableField(self::$formName, 'beneficiario');

        parent::add($container);

    }

    public static function onBuscarBenef($param = null) 
    {
        try 
        {
            if (empty($param['beneficiario']) && ParamService::valor('sn_buscar_nome_benef_acordos') == 'S') {

                $aux_req    = curl_init();
                $aux_cartao = str_replace(array('.', '-', ' '), '', $param['carteirinha']);

                curl_setopt($aux_req, CURLOPT_URL, "http://192.168.1.7:5000/buscar_benef/{$aux_cartao}");
                curl_setopt($aux_req, CURLOPT_RETURNTRANSFER, true);

                $aux_resp = curl_exec($aux_req);

                if (curl_errno($aux_req) || empty($aux_resp)) {
                    TEntry::enableField(self::$formName, 'beneficiario');
                    ExpService::mostrar(21);
                } else {
                    $aux_json = json_decode($aux_resp);

                    $object = new stdClass();
                    $object->beneficiario = ucwords(strtolower(trim($aux_json->nome)));
                    TForm::sendData(self::$formName, $object);
                }
                curl_close($aux_req);
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

            $object = new Acordo(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->medico       = ucwords(strtolower(trim($object->medico)));
            $object->beneficiario = ucwords(strtolower(trim($object->beneficiario)));

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

            TApplication::loadPage(__CLASS__, 'onEdit', ['key'=>$object->id]);

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

                $object = new Acordo($key); // instantiates the Active Record 

                                $this->table_arquivos->unhide();
                $this->table_arquivos->setParameter('acordo_id', $object->id);

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
                TEntry::enableField(self::$formName, 'beneficiario');
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

