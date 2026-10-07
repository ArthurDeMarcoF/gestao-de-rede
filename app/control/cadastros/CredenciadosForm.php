<?php

class CredenciadosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Credenciados';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosForm';

    use BuilderMasterDetailFieldListTrait;

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
        $this->form->setFormTitle("Cadastro de credenciados");

        $criteria_enquadramento_tributario = new TCriteria();
        $criteria_ativo = new TCriteria();
        $criteria_dm_tipo = new TCriteria();
        $criteria_credenciados_responsaveis_credenciados_categoria_responsavel_id = new TCriteria();
        $criteria_dm_reaj_contr = new TCriteria();
        $criteria_flg_dias_padrao = new TCriteria();

        $filterVar = "dm_enquadr_trib";
        $criteria_enquadramento_tributario->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_ativo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "ativo";
        $criteria_ativo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_dm_tipo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo";
        $criteria_dm_tipo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_dm_reaj_contr->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_reaj_contr";
        $criteria_dm_reaj_contr->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_flg_dias_padrao->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_dias_padrao";
        $criteria_flg_dias_padrao->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new THidden('id');
        $nome = new TEntry('nome');
        $codigo_prestador = new TEntry('codigo_prestador');
        $data_inicio = new TDate('data_inicio');
        $cnpj = new TEntry('cnpj');
        $enquadramento_tributario = new TDBCombo('enquadramento_tributario', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_enquadramento_tributario );
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $data_contrato = new TDate('data_contrato');
        $inscricao_estadual = new TEntry('inscricao_estadual');
        $cnes = new TEntry('cnes');
        $dm_tipo = new TDBCombo('dm_tipo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo );
        $dt_descredenciamento = new TDate('dt_descredenciamento');
        $enderecos_container = new BPageContainer();
        $contatos_container = new BPageContainer();
        $especialidades_container = new BPageContainer();
        $documentos_container = new BPageContainer();
        $bancos_list = new BPageContainer();
        $credenciados_responsaveis_credenciados_id = new THidden('credenciados_responsaveis_credenciados_id[]');
        $credenciados_responsaveis_credenciados___row__id = new THidden('credenciados_responsaveis_credenciados___row__id[]');
        $credenciados_responsaveis_credenciados___row__data = new THidden('credenciados_responsaveis_credenciados___row__data[]');
        $credenciados_responsaveis_credenciados_categoria_responsavel_id = new TDBCombo('credenciados_responsaveis_credenciados_categoria_responsavel_id[]', 'databaserede', 'CategoriaResponsavel', 'id', '{nome}','nome asc' , $criteria_credenciados_responsaveis_credenciados_categoria_responsavel_id );
        $credenciados_responsaveis_credenciados_nome = new TEntry('credenciados_responsaveis_credenciados_nome[]');
        $credenciados_responsaveis_credenciados_cooperado = new TCombo('credenciados_responsaveis_credenciados_cooperado[]');
        $this->fieldList_66f6b9c9c1bad = new TFieldList();
        $dm_reaj_contr = new TDBCombo('dm_reaj_contr', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_reaj_contr );
        $nr_dias_aviso_reaj = new TNumeric('nr_dias_aviso_reaj', '0', ',', '.' );
        $flg_dias_padrao = new TDBRadioGroup('flg_dias_padrao', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_dias_padrao );
        $table_reajustes = new BPageContainer();
        $movimentacoes = new BPageContainer();

        $this->fieldList_66f6b9c9c1bad->addField(null, $credenciados_responsaveis_credenciados_id, []);
        $this->fieldList_66f6b9c9c1bad->addField(null, $credenciados_responsaveis_credenciados___row__id, ['uniqid' => true]);
        $this->fieldList_66f6b9c9c1bad->addField(null, $credenciados_responsaveis_credenciados___row__data, []);
        $this->fieldList_66f6b9c9c1bad->addField(new TLabel("Categoria <font color=\"red\">*</font>", null, '14px', null), $credenciados_responsaveis_credenciados_categoria_responsavel_id, ['width' => '25%']);
        $this->fieldList_66f6b9c9c1bad->addField(new TLabel("Nome <font color=\"red\">*</font>", null, '14px', null), $credenciados_responsaveis_credenciados_nome, ['width' => '50%']);
        $this->fieldList_66f6b9c9c1bad->addField(new TLabel("Cooperado?", null, '14px', null), $credenciados_responsaveis_credenciados_cooperado, ['width' => '25%']);

        $this->fieldList_66f6b9c9c1bad->width = '100%';
        $this->fieldList_66f6b9c9c1bad->setFieldPrefix('credenciados_responsaveis_credenciados');
        $this->fieldList_66f6b9c9c1bad->name = 'fieldList_66f6b9c9c1bad';

        $this->criteria_fieldList_66f6b9c9c1bad = new TCriteria();
        $this->default_item_fieldList_66f6b9c9c1bad = new stdClass();

        $this->form->addField($credenciados_responsaveis_credenciados_id);
        $this->form->addField($credenciados_responsaveis_credenciados___row__id);
        $this->form->addField($credenciados_responsaveis_credenciados___row__data);
        $this->form->addField($credenciados_responsaveis_credenciados_categoria_responsavel_id);
        $this->form->addField($credenciados_responsaveis_credenciados_nome);
        $this->form->addField($credenciados_responsaveis_credenciados_cooperado);

        $this->fieldList_66f6b9c9c1bad->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $flg_dias_padrao->setChangeAction(new TAction([$this,'onChangeDiasPadrao']));

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $data_inicio->addValidation("Data de início ", new TRequiredValidator()); 
        $cnpj->addValidation("CNPJ", new TRequiredValidator()); 
        $dm_tipo->addValidation("Tipo", new TRequiredValidator()); 
        $credenciados_responsaveis_credenciados_categoria_responsavel_id->addValidation("Categoria", new TRequiredListValidator()); 
        $cnpj->addValidation("CNPJ", new TCNPJValidator(), []); 

        $credenciados_responsaveis_credenciados_cooperado->addItems(["Sim"=>"Sim","Não"=>"Não"]);
        $flg_dias_padrao->setBreakItems(2);
        $ativo->setLayout('horizontal');
        $flg_dias_padrao->setLayout('horizontal');

        $ativo->setUseButton();
        $flg_dias_padrao->setUseButton();

        $data_inicio->setDatabaseMask('yyyy-mm-dd');
        $data_contrato->setDatabaseMask('yyyy-mm-dd');
        $dt_descredenciamento->setDatabaseMask('yyyy-mm-dd');

        $ativo->setValue('S');
        $dm_reaj_contr->setValue(DMService::obterValPadrao('credenciados', 'dm_reaj_contr'));
        $flg_dias_padrao->setValue(DMService::obterValPadrao('credenciados', 'flg_dias_padrao'));

        $data_inicio->setMask('dd/mm/yyyy');
        $cnpj->setMask('##.###.###/####-##');
        $data_contrato->setMask('dd/mm/yyyy');
        $dt_descredenciamento->setMask('dd/mm/yyyy');

        $cnpj->setMaxLength(18);
        $cnes->setMaxLength(15);
        $nome->setMaxLength(100);
        $codigo_prestador->setMaxLength(15);
        $inscricao_estadual->setMaxLength(15);

        $dm_tipo->enableSearch();
        $dm_reaj_contr->enableSearch();
        $enquadramento_tributario->enableSearch();
        $credenciados_responsaveis_credenciados_cooperado->enableSearch();
        $credenciados_responsaveis_credenciados_categoria_responsavel_id->enableSearch();

        $movimentacoes->setAction(new TAction(['CredenciadosMovimentacoesList', 'onShow']));
        $table_reajustes->setAction(new TAction(['CredenciadosReajusteCortinaList', 'onShow']));
        $bancos_list->setAction(new TAction(['CredenciadosDadosBancariosCortinaList', 'onShow']));
        $contatos_container->setAction(new TAction(['CredenciadosContatosCortinaList', 'onShow']));
        $enderecos_container->setAction(new TAction(['CredenciadosEnderecosCortinaList', 'onShow']));
        $documentos_container->setAction(new TAction(['CredenciadosDocumentacoesCortinaList', 'onShow']));
        $especialidades_container->setAction(new TAction(['CredenciadosEspecialidadesCortinaList', 'onShow']));

        $bancos_list->setId('b66f1b37a89217');
        $movimentacoes->setId('b6a0f5f7eb4301');
        $table_reajustes->setId('b6717bb7fbe497');
        $contatos_container->setId('b64b6ca7a53c07');
        $enderecos_container->setId('b64b683bb6b1e6');
        $documentos_container->setId('b64bae7fe61940');
        $especialidades_container->setId('b64b6e9e0461b3');

        $bancos_list->hide();
        $movimentacoes->hide();
        $table_reajustes->hide();
        $contatos_container->hide();
        $enderecos_container->hide();
        $documentos_container->hide();
        $especialidades_container->hide();

        $id->setSize(200);
        $nome->setSize('100%');
        $cnpj->setSize('100%');
        $ativo->setSize('90%');
        $cnes->setSize('100%');
        $dm_tipo->setSize('100%');
        $data_inicio->setSize('100%');
        $bancos_list->setSize('100%');
        $data_contrato->setSize('100%');
        $dm_reaj_contr->setSize('100%');
        $movimentacoes->setSize('100%');
        $flg_dias_padrao->setSize('100%');
        $table_reajustes->setSize('100%');
        $codigo_prestador->setSize('100%');
        $inscricao_estadual->setSize('100%');
        $contatos_container->setSize('100%');
        $nr_dias_aviso_reaj->setSize('100%');
        $enderecos_container->setSize('100%');
        $dt_descredenciamento->setSize('100%');
        $documentos_container->setSize('100%');
        $enquadramento_tributario->setSize('100%');
        $especialidades_container->setSize('100%');
        $credenciados_responsaveis_credenciados_nome->setSize('100%');
        $credenciados_responsaveis_credenciados_cooperado->setSize('100%');
        $credenciados_responsaveis_credenciados_categoria_responsavel_id->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bancos_list->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_reajustes->add($loadingContainer);
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
        $this->bancos_list = $bancos_list;
        $this->table_reajustes = $table_reajustes;
        $this->movimentacoes = $movimentacoes;

        $this->form->appendPage("Dados Gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$id,new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome],[new TLabel("Código do prestador:", null, '14px', null, '100%'),$codigo_prestador],[new TLabel("Data do credenciamento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$data_inicio]);
        $row1->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("CNPJ:", null, '14px', null, '100%'),$cnpj],[new TLabel("Enquadramento tributário:", null, '14px', null, '100%'),$enquadramento_tributario],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo],[new TLabel("Data contrato:", null, '14px', null, '100%'),$data_contrato]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Inscrição estadual:", null, '14px', null, '100%'),$inscricao_estadual],[new TLabel("CNES:", null, '14px', null, '100%'),$cnes],[new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo],[new TLabel("Data descredenciamento:", null, '14px', null, '100%'),$dt_descredenciamento]);
        $row3->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        $this->form->appendPage("Endereços");
        $row4 = $this->form->addFields([$enderecos_container]);
        $row4->layout = [' col-sm-12'];

        $this->form->appendPage("Contatos");
        $row5 = $this->form->addFields([$contatos_container]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Especialidades");
        $row6 = $this->form->addFields([$especialidades_container]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Documentos");
        $row7 = $this->form->addFields([$documentos_container]);
        $row7->layout = [' col-sm-12'];

        $this->form->appendPage("Dados Bancários");
        $row8 = $this->form->addFields([$bancos_list]);
        $row8->layout = [' col-sm-12'];

        $this->form->appendPage("Responsáveis");
        $row9 = $this->form->addFields([$this->fieldList_66f6b9c9c1bad]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Reajustes");
        $row10 = $this->form->addFields([new TLabel("Reajuste contratual:", null, '14px', null, '100%'),$dm_reaj_contr],[new TLabel("Dias para aviso do reajuste:", null, '14px', null, '100%'),$nr_dias_aviso_reaj],[new TLabel("Usar dias padrão sistema?", null, '14px', null, '100%'),$flg_dias_padrao],[]);
        $row10->layout = ['col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        $row11 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row12 = $this->form->addFields([$table_reajustes]);
        $row12->layout = [' col-sm-12'];

        $this->form->appendPage("Movimentações");
        $row13 = $this->form->addFields([$movimentacoes]);
        $row13->layout = [' col-sm-12'];

        // Não exibe BPageContainer caso não possuir id 
        if (empty($param['id']))
        {
            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer->add('<br>Para incluir os endereços é necessário clicar em salvar!');
            $enderecos_container->add($loadingContainer);

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer->add('<br>Para incluir os contatos é necessário clicar em salvar!');
            $contatos_container->add($loadingContainer);

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer->add('<br>Para incluir as especialidades é necessário clicar em salvar!');
            $especialidades_container->add($loadingContainer);

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';
            $loadingContainer->add('<br>Para incluir os documentos é necessário clicar em salvar!');
            $documentos_container->add($loadingContainer);
        }
        else 
        {
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
         }

        //Zerar variável de seção
        TSession::setValue('form_CredenciadoForm_Credenciado_id', '');

        $aux_label = ExpService::montar(24, ParamService::valor('nr_dias_aviso_reaj_padrao'));
        TScript::create("$('label:contains(\"Usar dias padrão sistema?\")').html('$aux_label')");

        TNumeric::disableField(self::$formName, 'nr_dias_aviso_reaj');

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CredenciadosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de credenciados"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeDiasPadrao($param = null) 
    {
        try 
        {
            if ($param['flg_dias_padrao'] == 'S')
                TNumeric::disableField(self::$formName, 'nr_dias_aviso_reaj');
            else
                TNumeric::enableField(self::$formName, 'nr_dias_aviso_reaj');

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

            $object = new Credenciados(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->nome = ucwords(trim($object->nome));

            if ($object->flg_dias_padrao == 'N' && empty($object->nr_dias_aviso_reaj))
                throw new Exception('Deve ser informado uma quantidade de dias para aviso!');

            $object->store(); // save the object 

            $credenciados_responsaveis_credenciados_items = $this->storeItems('CredenciadosResponsaveis', 'credenciados_id', $object, $this->fieldList_66f6b9c9c1bad, function($masterObject, $detailObject){ 

            }, $this->criteria_fieldList_66f6b9c9c1bad); 

            $aux_docs = DocumentacoesPadraoCredenciado::where('documentacoes_id', 'IN', "(select d.id from documentacoes d where d.ativo = 'Sim')")
                                                      ->load();
            foreach ($aux_docs as $aux_doc) {
                $aux_cred_doc = new CredenciadosDocumentacoes();
                $aux_cred_doc->credenciados_id        = $object->id;
                $aux_cred_doc->tipos_documentacoes_id = $aux_doc->tipos_documentacoes_id;
                $aux_cred_doc->documentacoes_id       = $aux_doc->documentacoes_id;
                $aux_cred_doc->ativo                  = DMService::obterValPadrao('credenciados_documentacoes', 'ativo');
                $aux_cred_doc->entregue               = DMService::obterValPadrao('credenciados_documentacoes', 'entregue');
                $aux_cred_doc->store();
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            // Alimenta vaiável de seção para uso nas tabs.
            TSession::setValue('form_CredenciadoForm_Credenciado_id', $data->id);

            TApplication::loadPage(__CLASS__, 'onEdit', ['key'=>$object->id]);

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

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
                TSession::setValue('form_CredenciadoForm_Credenciado_id', $param['key']);

                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Credenciados($key); // instantiates the Active Record 

                                $this->enderecos_container->unhide();
                $this->contatos_container->unhide();
                $this->especialidades_container->unhide();
                $this->documentos_container->unhide();
                $this->documentos_container->setParameter('credenciados_id', $object->id);
                $this->bancos_list->unhide();
                $this->bancos_list->setParameter('credenciado_id', $object->id);
                $this->table_reajustes->unhide();
                $this->table_reajustes->setParameter('credenciados_id', $object->id);
                $this->movimentacoes->unhide();
                $this->movimentacoes->setParameter('id_credenciado', $object->id);

                $this->fieldList_66f6b9c9c1bad_items = $this->loadItems('CredenciadosResponsaveis', 'credenciados_id', $object, $this->fieldList_66f6b9c9c1bad, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_66f6b9c9c1bad); 

                $this->form->setData($object); // fill the form 

                if ($object->flg_dias_padrao == 'N')
                    TNumeric::enableField(self::$formName, 'nr_dias_aviso_reaj');

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

        $this->fieldList_66f6b9c9c1bad->addHeader();
        $this->fieldList_66f6b9c9c1bad->addDetail($this->default_item_fieldList_66f6b9c9c1bad);

        $this->fieldList_66f6b9c9c1bad->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        // reload para zerar variaveis do form
        TApplication::loadPage(__CLASS__,'onShow');

        // Zera variável de sessão
        TSession::setValue('form_CredenciadoContatosList_Credenciado_id', '');

    }

    public function onShow($param = null)
    {
        $this->fieldList_66f6b9c9c1bad->addHeader();
        $this->fieldList_66f6b9c9c1bad->addDetail($this->default_item_fieldList_66f6b9c9c1bad);

        $this->fieldList_66f6b9c9c1bad->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

