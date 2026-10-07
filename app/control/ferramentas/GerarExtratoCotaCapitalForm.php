<?php

class GerarExtratoCotaCapitalForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_GerarExtratoCotaCapitalForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Geração extrato cota-capital");

        $criteria_tipos_documentacoes_id = new TCriteria();

        $ano = new TSpinner('ano');
        $data = new TDate('data');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_1 = new TButton('button_1');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $ano->addValidation("ano base", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipo do documento", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documento", new TRequiredValidator()); 

        $ano->setRange(1, 4000, 1);
        $ano->setValue(date('Y'));
        $data->setMask('dd/mm/yyyy');
        $data->setDatabaseMask('yyyy-mm-dd');
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $ano->setSize('100%');
        $data->setSize('100%');
        $documentacoes_id->setSize('80%');
        $tipos_documentacoes_id->setSize('80%');


        $row1 = $this->form->addFields([new TLabel("Ano base: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ano],[new TLabel("Data de emissão:", null, '14px', null, '100%'),$data],[new TLabel("Tipo do documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_1]);
        $row1->layout = ['col-sm-2','col-sm-2',' col-sm-4',' col-sm-4'];

        // create the form actions
        $btn_ongerar = $this->form->addAction("Gerar", new TAction([$this, 'onGerar']), 'fas:cogs #ffffff');
        $this->btn_ongerar = $btn_ongerar;
        $btn_ongerar->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Geração extrato cota-capital"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangetipos_documentacoes_id($param)
    {
        try
        {

            if (isset($param['tipos_documentacoes_id']) && $param['tipos_documentacoes_id'])
            { 
                $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipos_documentacoes_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'documentacoes_id', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'documentacoes_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onGerar($param = null) 
    {
        try
        {

            $this->form->validate();

            if (empty($param['data']))
                $aux_data = date('d/m/Y');
            else
                $aux_data = $param['data'];

            $aux_prm = ['ano'      => $param['ano'],
                        'tipo_doc' => $param['tipos_documentacoes_id'],
                        'doc'      => $param['documentacoes_id'],
                        'data'     => $aux_data];

            JobService::criarJobUnico('Criação extratos cota-capital (SYS)', apiService::urlExtratoGerar, $aux_prm);

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipos_documentacoes_id))
            {
                $value = $object->tipos_documentacoes_id;

                $obj->tipos_documentacoes_id = $value;
            }
            if(isset($object->documentacoes_id))
            {
                $value = $object->documentacoes_id;

                $obj->documentacoes_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

}

