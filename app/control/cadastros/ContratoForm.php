<?php

class ContratoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Contrato';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContratoForm';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Cadastro de contrato");

        $criteria_dm_situacao = new TCriteria();

        $filterVar = "contrato";
        $criteria_dm_situacao->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_situacao";
        $criteria_dm_situacao->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new THidden('id');
        $nome = new TEntry('nome');
        $apolice = new TEntry('apolice');
        $dm_situacao = new TDBCombo('dm_situacao', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_situacao );
        $cnpj = new TEntry('cnpj');
        $empresa = new TEntry('empresa');
        $dt_contrato = new TDate('dt_contrato');
        $dt_reajuste = new TDate('dt_reajuste');
        $indice = new TNumeric('indice', '2', ',', '.' );
        $path_arquivo = new TFile('path_arquivo');
        $table_servicos = new BPageContainer();
        $table_valores = new BPageContainer();

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $dm_situacao->addValidation("Situação", new TRequiredValidator()); 
        $cnpj->addValidation("CNPJ", new TRequiredValidator()); 
        $empresa->addValidation("Empresa", new TRequiredValidator()); 
        $dt_contrato->addValidation("Data do contrato", new TRequiredValidator()); 
        $dt_reajuste->addValidation("Data de reajuste", new TRequiredValidator()); 

        $dm_situacao->setValue(DMService::obterValPadrao('contrato','dm_situacao'));
        $dm_situacao->enableSearch();
        $path_arquivo->enableFileHandling();
        $dt_contrato->setDatabaseMask('yyyy-mm-dd');
        $dt_reajuste->setDatabaseMask('yyyy-mm-dd');

        $table_valores->setAction(new TAction(['ContratoValorCortinaList', 'onShow']));
        $table_servicos->setAction(new TAction(['ContratoServicoCortinaList', 'onShow']));

        $table_valores->setId('b673b8e6c5c759');
        $table_servicos->setId('b673b8e22de71b');

        $table_valores->hide();
        $table_servicos->hide();

        $dt_contrato->setMask('dd/mm/yyyy');
        $dt_reajuste->setMask('dd/mm/yyyy');
        $cnpj->setMask('00.000.000/0000-00');

        $cnpj->setMaxLength(18);
        $nome->setMaxLength(150);
        $apolice->setMaxLength(30);
        $empresa->setMaxLength(150);

        $id->setSize(200);
        $nome->setSize('100%');
        $cnpj->setSize('100%');
        $indice->setSize('100%');
        $apolice->setSize('100%');
        $empresa->setSize('100%');
        $dm_situacao->setSize('100%');
        $dt_contrato->setSize('100%');
        $dt_reajuste->setSize('100%');
        $path_arquivo->setSize('100%');
        $table_valores->setSize('100%');
        $table_servicos->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_servicos->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_valores->add($loadingContainer);

        $this->table_servicos = $table_servicos;
        $this->table_valores = $table_valores;

        $this->form->appendPage("Dados gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome],[new TLabel("Nº Apólice:", null, '14px', null, '100%'),$apolice],[new TLabel("Situação: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_situacao]);
        $row2->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("CNPJ: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cnpj],[new TLabel("Empresa: <font color=\"red\">*</font>", null, '14px', null, '100%'),$empresa]);
        $row3->layout = [' col-sm-4',' col-sm-8'];

        $row4 = $this->form->addFields([new TLabel("Data do contrato: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dt_contrato],[new TLabel("Data de reajuste: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dt_reajuste],[new TLabel("Índice de reajuste:", null, '14px', null, '100%'),$indice]);
        $row4->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Anexo:", null, '14px', null, '100%'),$path_arquivo]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Serviços");
        $row6 = $this->form->addFields([$table_servicos]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Valores");
        $row7 = $this->form->addFields([$table_valores]);
        $row7->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ContratoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de contrato"]));
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

            $object = new Contrato(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->empresa = trim($object->empresa);
            $object->nome    = trim($object->nome);

            $path_arquivo_dir = 'arquivos/docs_contrato/contrato';  

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'path_arquivo', $path_arquivo_dir); 

            if ($object->dm_situacao == 'I')
                ContratoServico::where('contrato_id', '=', $object->id)
                               ->where('dm_situacao', '=', 'A')
                               ->set('dm_situacao', 'I')
                               ->update();

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

            TApplication::loadPage(__CLASS__, 'onEdit', ['key'=>$object->id, 'id'=>$object->id]);

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

                $object = new Contrato($key); // instantiates the Active Record 

                                $this->table_servicos->unhide();
                $this->table_servicos->setParameter('contrato_id', $object->id);
                $this->table_valores->unhide();
                $this->table_valores->setParameter('contrato_id', $object->id);

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 

                TSession::setValue(__CLASS__.'_key', $key);

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

