<?php

class ImportDocsForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ImportDocsForm';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Importador de documentos");

        $criteria_dm_categoria = new TCriteria();
        $criteria_tipos_documentacoes_id = new TCriteria();
        $criteria_ativo = new TCriteria();
        $criteria_entregue = new TCriteria();

        $filterVar = __CLASS__;
        $criteria_dm_categoria->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_categoria";
        $criteria_dm_categoria->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = __CLASS__;
        $criteria_ativo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "ativo";
        $criteria_ativo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = __CLASS__;
        $criteria_entregue->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "entregue";
        $criteria_entregue->add(new TFilter('atributo', '=', $filterVar)); 

        $dm_categoria = new TDBCombo('dm_categoria', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_categoria );
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $button_ = new TButton('button_');
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_1 = new TButton('button_1');
        $emissao = new TDate('emissao');
        $validade = new TDate('validade');
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $entregue = new TDBRadioGroup('entregue', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_entregue );
        $observacao = new THtmlEditor('observacao');
        $arquivo = new TFile('arquivo');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $arquivo->enableFileHandling();
        $arquivo->setAllowedExtensions([".zip"]);
        $button_1->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_->setAction(new TAction(['TiposDocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");

        $button_->addStyleClass('btn-default');
        $button_1->addStyleClass('btn-default');

        $button_->setImage('fas:plus #4CAF50');
        $button_1->setImage('fas:plus #4CAF50');

        $emissao->setMask('dd/mm/yyyy');
        $validade->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $validade->setDatabaseMask('yyyy-mm-dd');

        $ativo->setLayout('horizontal');
        $entregue->setLayout('horizontal');

        $ativo->setValue(DMService::obterValPadrao(__CLASS__, 'ativo'));
        $entregue->setValue(DMService::obterValPadrao(__CLASS__, 'entregue'));

        $ativo->setUseButton();
        $entregue->setUseButton();

        $ativo->setBreakItems(2);
        $entregue->setBreakItems(2);

        $dm_categoria->enableSearch();
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $ativo->setSize('100%');
        $emissao->setSize('100%');
        $arquivo->setSize('100%');
        $validade->setSize('100%');
        $entregue->setSize('100%');
        $dm_categoria->setSize('100%');
        $documentacoes_id->setSize('80%');
        $observacao->setSize('100%', 200);
        $tipos_documentacoes_id->setSize('80%');

        $row1 = $this->form->addFields([new TLabel("Categoria:", null, '14px', null, '100%'),$dm_categoria],[new TLabel("Tipo:", null, '14px', null, '100%'),$tipos_documentacoes_id,$button_],[new TLabel("Documento:", null, '14px', null, '100%'),$documentacoes_id,$button_1]);
        $row1->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row2 = $this->form->addFields([new TLabel("Data emissão:", null, '14px', null, '100%'),$emissao],[new TLabel("Data validade:", null, '14px', null, '100%'),$validade],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo],[new TLabel("Entregue:", null, '14px', null, '100%'),$entregue]);
        $row2->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Arquivo:", null, '14px', null, '100%'),$arquivo]);
        $row4->layout = [' col-sm-12'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Utilitários","Importador de documentos"]));
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

