<?php

class MedicosPfForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'MedicosPf';
    private static $primaryKey = 'id';
    private static $formName = 'form_MedicosPfForm';

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
        $this->form->setFormTitle("Cadastro de consultórios de especialidades");

        $criteria_sexo = new TCriteria();
        $criteria_estado_civil = new TCriteria();

        $filterVar = "cooperados";
        $criteria_sexo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "sexo";
        $criteria_sexo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_estado_civil->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "estado_civil";
        $criteria_estado_civil->add(new TFilter('atributo', '=', $filterVar)); 

        $path_foto = new TImageCropper('path_foto');
        $nome = new TEntry('nome');
        $data_contrato = new TDate('data_contrato');
        $cpf = new TEntry('cpf');
        $ativo = new TRadioGroup('ativo');
        $rg = new TEntry('rg');
        $sexo = new TDBCombo('sexo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_sexo );
        $data_nascimento = new TDate('data_nascimento');
        $estado_civil = new TDBCombo('estado_civil', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_estado_civil );
        $data_encerramento_contrato = new TDate('data_encerramento_contrato');
        $cnpj = new TEntry('cnpj');
        $cnes = new TEntry('cnes');
        $crm = new TEntry('crm');
        $cod_plantonista = new TEntry('cod_plantonista');
        $id = new THidden('id');
        $enderecos_container = new BPageContainer();
        $contatos_container = new BPageContainer();
        $especialidades_container = new BPageContainer();
        $documentos_container = new BPageContainer();
        $dados_bancarios_container = new BPageContainer();
        $movimentacoes = new BPageContainer();

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $data_contrato->addValidation("Data contrato", new TRequiredValidator()); 

        $path_foto->enableFileHandling();
        $path_foto->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $path_foto->setImagePlaceholder(new TImage("fas:file-upload #dde5ec"));
        $nome->forceUpperCase();
        $ativo->addItems(["S"=>"Sim","N"=>"Não"]);
        $ativo->setLayout('horizontal');
        $ativo->setValue('S');
        $ativo->setUseButton();
        $sexo->enableSearch();
        $estado_civil->enableSearch();

        $data_contrato->setDatabaseMask('yyyy-mm-dd');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $data_encerramento_contrato->setDatabaseMask('yyyy-mm-dd');

        $rg->setMaxLength(20);
        $cpf->setMaxLength(14);
        $crm->setMaxLength(20);
        $nome->setMaxLength(250);

        $movimentacoes->setAction(new TAction(['MedicosPfMovimentacoesList', 'onShow']));
        $contatos_container->setAction(new TAction(['MedicosPfContatosCortinaList', 'onShow']));
        $enderecos_container->setAction(new TAction(['MedicosPfEnderecosCortinaList', 'onShow']));
        $documentos_container->setAction(new TAction(['MedicosPfDocumentacoesCortinaList', 'onShow']));
        $especialidades_container->setAction(new TAction(['MedicosPfEspecialidadesCortinaList', 'onShow']));
        $dados_bancarios_container->setAction(new TAction(['MedicosPfDadosBancariosCortinaList', 'onShow']));

        $movimentacoes->setId('b6a0f4f7a1a50c');
        $contatos_container->setId('b6839fb825dbfe');
        $enderecos_container->setId('b6839f39d12a27');
        $documentos_container->setId('b6839fbd0092c9');
        $especialidades_container->setId('b6839fbb8208d3');
        $dados_bancarios_container->setId('b6839fbee588ee');

        $movimentacoes->hide();
        $contatos_container->hide();
        $enderecos_container->hide();
        $documentos_container->hide();
        $especialidades_container->hide();
        $dados_bancarios_container->hide();

        $cpf->setMask('999.999.999-99');
        $cnes->setMask('9999999', true);
        $rg->setMask('99.999.999-9', true);
        $data_contrato->setMask('dd/mm/yyyy');
        $data_nascimento->setMask('dd/mm/yyyy');
        $cnpj->setMask('99.999.999/9999-99', true);
        $cod_plantonista->setMask('9999999', true);
        $data_encerramento_contrato->setMask('dd/mm/yyyy');

        $id->setSize(200);
        $rg->setSize('100%');
        $cpf->setSize('100%');
        $crm->setSize('100%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $cnpj->setSize('100%');
        $cnes->setSize('100%');
        $ativo->setSize('100%');
        $estado_civil->setSize('100%');
        $data_contrato->setSize('100%');
        $movimentacoes->setSize('100%');
        $path_foto->setSize('100%', 170);
        $cod_plantonista->setSize('40%');
        $data_nascimento->setSize('100%');
        $contatos_container->setSize('100%');
        $enderecos_container->setSize('100%');
        $documentos_container->setSize('100%');
        $especialidades_container->setSize('100%');
        $data_encerramento_contrato->setSize('50%');
        $dados_bancarios_container->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $enderecos_container->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $contatos_container->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $especialidades_container->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $documentos_container->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $dados_bancarios_container->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $movimentacoes->add($loadingContainer);

        $this->enderecos_container = $enderecos_container;
        $this->contatos_container = $contatos_container;
        $this->especialidades_container = $especialidades_container;
        $this->documentos_container = $documentos_container;
        $this->dados_bancarios_container = $dados_bancarios_container;
        $this->movimentacoes = $movimentacoes;

        $this->form->appendPage("Dados Gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $bcontainer_683e0f56acb01 = new BootstrapFormBuilder('bcontainer_683e0f56acb01');
        $this->bcontainer_683e0f56acb01 = $bcontainer_683e0f56acb01;
        $bcontainer_683e0f56acb01->setProperty('style', 'border:none; box-shadow:none;');
        $row1 = $bcontainer_683e0f56acb01->addFields([new TLabel("Nome: <span style=\"color: red\">*</span>", null, '14px', null, '100%'),$nome],[new TLabel("Data do contrato: <span style=\"color: red\">*</span>", null, '14px', null, '100%'),$data_contrato],[new TLabel("CPF:", null, '14px', null, '100%'),$cpf],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo]);
        $row1->layout = ['col-sm-4','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $bcontainer_683e0f56acb01->addFields([new TLabel("RG:", null, '14px', null, '100%'),$rg],[new TLabel("Sexo:", null, '14px', null, '100%'),$sexo],[new TLabel("Data nascimento:", null, '14px', null, '100%'),$data_nascimento],[new TLabel("Estado civil:", null, '14px', null, '100%'),$estado_civil],[new TLabel("Data encerramento contrato:", null, '14px', null, '100%'),$data_encerramento_contrato]);
        $row2->layout = ['col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-4'];

        $row3 = $bcontainer_683e0f56acb01->addFields([new TLabel("CNPJ:", null, '14px', null, '100%'),$cnpj],[new TLabel("CNES:", null, '14px', null),$cnes],[new TLabel("CRM:", null, '14px', null, '100%'),$crm],[new TLabel("Código de plantonista:", null, '14px', null, '100%'),$cod_plantonista]);
        $row3->layout = ['col-sm-3','col-sm-3','col-sm-2',' col-sm-4'];

        $row4 = $bcontainer_683e0f56acb01->addFields([$id]);
        $row4->layout = ['col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Foto:", null, '14px', null, '100%'),$path_foto],[$bcontainer_683e0f56acb01],[]);
        $row5->layout = ['col-sm-2','col-sm-10'];

        $this->form->appendPage("Endereços");
        $row6 = $this->form->addFields([$enderecos_container]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Contatos");
        $row7 = $this->form->addFields([$contatos_container]);
        $row7->layout = [' col-sm-12'];

        $this->form->appendPage("Especialidades");
        $row8 = $this->form->addFields([$especialidades_container]);
        $row8->layout = [' col-sm-12'];

        $this->form->appendPage("Documentos");
        $row9 = $this->form->addFields([$documentos_container]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Dados Bancários");
        $row10 = $this->form->addFields([$dados_bancarios_container]);
        $row10->layout = [' col-sm-12'];

        $this->form->appendPage("Movimentações");
        $row11 = $this->form->addFields([$movimentacoes]);
        $row11->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MedicosPfList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de consultórios de especialidades"]));
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

            $object = new MedicosPf(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $path_foto_dir = 'arquivos/fotos_medicos_pf';  

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'path_foto', $path_foto_dir);
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
            TApplication::loadPage('MedicosPfList', 'onShow', $loadPageParam); 

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

                $object = new MedicosPf($key); // instantiates the Active Record 

                                $this->enderecos_container->unhide();
                $this->enderecos_container->setParameter('medicos_pf_id', $object->id);
                $this->contatos_container->unhide();
                $this->contatos_container->setParameter('medicos_pf_id', $object->id);
                $this->especialidades_container->unhide();
                $this->especialidades_container->setParameter('medicos_pf_id', $object->id);
                $this->documentos_container->unhide();
                $this->documentos_container->setParameter('medicos_pf_id', $object->id);
                $this->dados_bancarios_container->unhide();
                $this->dados_bancarios_container->setParameter('medicos_pf_id', $object->id);
                $this->movimentacoes->unhide();
                $this->movimentacoes->setParameter('id_medico_pf', $object->id);

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

