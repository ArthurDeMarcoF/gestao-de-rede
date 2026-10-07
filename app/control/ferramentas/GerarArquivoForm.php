<?php

/*

class GerarArquivoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_GerarArquivoForm';

*/

class GerarArquivoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Arquivos';
    private static $primaryKey = 'id';
    private static $formName = 'form_GerarArquivoForm';

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
        $this->form->setFormTitle("Gerar Documentos");

        $criteria_tipos_documentacoes_id = new TCriteria();

        $filterVar = 12;
        $criteria_tipos_documentacoes_id->add(new TFilter('id', '=', $filterVar)); 

        $ano = new TSpinner('ano');
        $data = new TDate('data');
        $tipos_documentacoes_id = new TDBCombo('tipos_documentacoes_id', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipos_documentacoes_id );
        $documentacoes_id = new TCombo('documentacoes_id');
        $button_ = new TButton('button_');

        $tipos_documentacoes_id->setChangeAction(new TAction([$this,'onChangetipos_documentacoes_id']));

        $ano->addValidation("ano base", new TRequiredValidator()); 
        $tipos_documentacoes_id->addValidation("Tipo do documento", new TRequiredValidator()); 
        $documentacoes_id->addValidation("Documento", new TRequiredValidator()); 

        $ano->setRange(1, 4000, 1);
        $ano->setValue(date('Y'));
        $data->setMask('dd/mm/yyyy');
        $data->setDatabaseMask('yyyy-mm-dd');
        $button_->setAction(new TAction(['DocumentacoesCortinaForm', 'onShow'],['form_name' =>  self::$formName]), "");
        $button_->addStyleClass('btn-default');
        $button_->setImage('fas:plus #4CAF50');
        $documentacoes_id->enableSearch();
        $tipos_documentacoes_id->enableSearch();

        $ano->setSize('100%');
        $data->setSize('100%');
        $documentacoes_id->setSize('80%');
        $tipos_documentacoes_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Ano base: <font color=\"red\">*</font>", null, '14px', null, '100%'),$ano],[new TLabel("Data de emissão:", null, '14px', null, '100%'),$data],[new TLabel("Tipo do documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipos_documentacoes_id],[new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$documentacoes_id,$button_]);
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
            $container->add(TBreadCrumb::create(["Ferramentas","Gerar Documentos"]));
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
            TTransaction::open('databaserede');

            $data_emissao = empty($param['data']) ? date('Y-m-d') : TDate::date2us($param['data']); 
            $tipo = new TiposDocumentacoes($param['tipos_documentacoes_id']); 
            $documento = new Documentacoes($param['documentacoes_id']);
            $ano = $param['ano']; 

            // Criar registro em ARQUIVOS (STANDBY)
            $arquivo = new Arquivos();
            $arquivo->data_criacao           = date('Y-m-d H:i:s');
            $arquivo->data_alteracao         = date('Y-m-d H:i:s');
            $arquivo->nome_arquivo           = $tipo->tipo_documento . ' - ' . $ano;;
            $arquivo->ano_base               = $ano;
            $arquivo->data_emissao           = $data_emissao;
            $arquivo->tipos_documentacoes_id = $tipo->id;
            $arquivo->documentacoes_id       = $documento->id;
            $arquivo->arquivos_status_id     = 1;
            $arquivo->store();

            $conn = TTransaction::get();

            $sql = "
                INSERT INTO arquivos_cooperado
                (
                    data_criacao,
                    data_alteracao,
                    arquivos_status_id,
                    cooperado_id,
                    arquivos_id
                )
                SELECT
                    NOW(),
                    NOW(),
                    1,
                    c.id,
                    :arquivo_id
                FROM cooperados c
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':arquivo_id', $arquivo->id);
            $stmt->execute();

            TToast::show(
                'success',
                'Extrato criado com sucesso!<br>Registros gerados para todos os cooperados ativos.',
                'topRight',
                'far:check-circle'
            );

            TTransaction::close();

            TApplication::loadPage('ArquivosList', 'onShow');

        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TToast('error', $e->getMessage());
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

