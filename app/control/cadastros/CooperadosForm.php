<?php

class CooperadosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Cooperados';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosForm';

    use Adianti\Base\AdiantiFileSaveTrait;
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
        $this->form->setFormTitle("Cadastro de cooperados");

        $criteria_ativo = new TCriteria();
        $criteria_sexo = new TCriteria();
        $criteria_estado_civil = new TCriteria();
        $criteria_flg_envio_dados = new TCriteria();

        $filterVar = "dm_sim_nao";
        $criteria_ativo->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_sexo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "sexo";
        $criteria_sexo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_estado_civil->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "estado_civil";
        $criteria_estado_civil->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_flg_envio_dados->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_envio_dados";
        $criteria_flg_envio_dados->add(new TFilter('atributo', '=', $filterVar)); 

        $path_foto = new TImageCropper('path_foto');
        $id = new THidden('id');
        $nome = new TEntry('nome');
        $crm = new TEntry('crm');
        $data_filiacao = new TDate('data_filiacao');
        $cpf = new TEntry('cpf');
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValor', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $rg = new TEntry('rg');
        $sexo = new TDBCombo('sexo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_sexo );
        $data_nascimento = new TDate('data_nascimento');
        $estado_civil = new TDBCombo('estado_civil', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_estado_civil );
        $forma_integralizacao = new TEntry('forma_integralizacao');
        $cnis = new TEntry('cnis');
        $inss = new TEntry('inss');
        $flg_recolhe_inss = new TRadioGroup('flg_recolhe_inss');
        $flg_retem_ir = new TRadioGroup('flg_retem_ir');
        $flg_declara_dep = new TRadioGroup('flg_declara_dep');
        $numero_filhos = new TEntry('numero_filhos');
        $dt_desfiliacao = new TDate('dt_desfiliacao');
        $enderecos_container = new BPageContainer();
        $contatos_container = new BPageContainer();
        $especialidades_container = new BPageContainer();
        $cooperados_secretaria_cooperados_id = new THidden('cooperados_secretaria_cooperados_id[]');
        $cooperados_secretaria_cooperados___row__id = new THidden('cooperados_secretaria_cooperados___row__id[]');
        $cooperados_secretaria_cooperados___row__data = new THidden('cooperados_secretaria_cooperados___row__data[]');
        $cooperados_secretaria_cooperados_nome = new TEntry('cooperados_secretaria_cooperados_nome[]');
        $cooperados_secretaria_cooperados_contato = new TEntry('cooperados_secretaria_cooperados_contato[]');
        $cooperados_secretaria_cooperados_email = new TEntry('cooperados_secretaria_cooperados_email[]');
        $this->fieldList_66eb1c34654ed = new TFieldList();
        $beneficios_container = new BPageContainer();
        $documentos_container = new BPageContainer();
        $dados_bancarios_container = new BPageContainer();
        $contabilidade = new TEntry('contabilidade');
        $flg_envio_dados = new TDBRadioGroup('flg_envio_dados', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_envio_dados );
        $cooperados_ctb_contato_cooperados_id = new THidden('cooperados_ctb_contato_cooperados_id[]');
        $cooperados_ctb_contato_cooperados___row__id = new THidden('cooperados_ctb_contato_cooperados___row__id[]');
        $cooperados_ctb_contato_cooperados___row__data = new THidden('cooperados_ctb_contato_cooperados___row__data[]');
        $cooperados_ctb_contato_cooperados_nome = new TEntry('cooperados_ctb_contato_cooperados_nome[]');
        $cooperados_ctb_contato_cooperados_numero = new TEntry('cooperados_ctb_contato_cooperados_numero[]');
        $cooperados_ctb_contato_cooperados_email = new TEntry('cooperados_ctb_contato_cooperados_email[]');
        $this->fieldList_66d9e5a4e3885 = new TFieldList();
        $table_totais = new BPageContainer();
        $table_capital = new BPageContainer();
        $movimentacoes = new BPageContainer();

        $this->fieldList_66eb1c34654ed->addField(null, $cooperados_secretaria_cooperados_id, []);
        $this->fieldList_66eb1c34654ed->addField(null, $cooperados_secretaria_cooperados___row__id, ['uniqid' => true]);
        $this->fieldList_66eb1c34654ed->addField(null, $cooperados_secretaria_cooperados___row__data, []);
        $this->fieldList_66eb1c34654ed->addField(new TLabel("Nome", null, '14px', null), $cooperados_secretaria_cooperados_nome, ['width' => '33%']);
        $this->fieldList_66eb1c34654ed->addField(new TLabel("Contato", null, '14px', null), $cooperados_secretaria_cooperados_contato, ['width' => '33%']);
        $this->fieldList_66eb1c34654ed->addField(new TLabel("E-Mail", null, '14px', null), $cooperados_secretaria_cooperados_email, ['width' => '33%']);

        $this->fieldList_66eb1c34654ed->width = '100%';
        $this->fieldList_66eb1c34654ed->setFieldPrefix('cooperados_secretaria_cooperados');
        $this->fieldList_66eb1c34654ed->name = 'fieldList_66eb1c34654ed';

        $this->criteria_fieldList_66eb1c34654ed = new TCriteria();
        $this->default_item_fieldList_66eb1c34654ed = new stdClass();

        $this->form->addField($cooperados_secretaria_cooperados_id);
        $this->form->addField($cooperados_secretaria_cooperados___row__id);
        $this->form->addField($cooperados_secretaria_cooperados___row__data);
        $this->form->addField($cooperados_secretaria_cooperados_nome);
        $this->form->addField($cooperados_secretaria_cooperados_contato);
        $this->form->addField($cooperados_secretaria_cooperados_email);

        $this->fieldList_66eb1c34654ed->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $this->fieldList_66d9e5a4e3885->addField(null, $cooperados_ctb_contato_cooperados_id, []);
        $this->fieldList_66d9e5a4e3885->addField(null, $cooperados_ctb_contato_cooperados___row__id, ['uniqid' => true]);
        $this->fieldList_66d9e5a4e3885->addField(null, $cooperados_ctb_contato_cooperados___row__data, []);
        $this->fieldList_66d9e5a4e3885->addField(new TLabel("Nome", null, '14px', null), $cooperados_ctb_contato_cooperados_nome, ['width' => '33%']);
        $this->fieldList_66d9e5a4e3885->addField(new TLabel("Telefone/Celular", null, '14px', null), $cooperados_ctb_contato_cooperados_numero, ['width' => '33%']);
        $this->fieldList_66d9e5a4e3885->addField(new TLabel("E-Mail", null, '14px', null), $cooperados_ctb_contato_cooperados_email, ['width' => '33%']);

        $this->fieldList_66d9e5a4e3885->width = '100%';
        $this->fieldList_66d9e5a4e3885->setFieldPrefix('cooperados_ctb_contato_cooperados');
        $this->fieldList_66d9e5a4e3885->name = 'fieldList_66d9e5a4e3885';

        $this->criteria_fieldList_66d9e5a4e3885 = new TCriteria();
        $this->default_item_fieldList_66d9e5a4e3885 = new stdClass();

        $this->form->addField($cooperados_ctb_contato_cooperados_id);
        $this->form->addField($cooperados_ctb_contato_cooperados___row__id);
        $this->form->addField($cooperados_ctb_contato_cooperados___row__data);
        $this->form->addField($cooperados_ctb_contato_cooperados_nome);
        $this->form->addField($cooperados_ctb_contato_cooperados_numero);
        $this->form->addField($cooperados_ctb_contato_cooperados_email);

        $this->fieldList_66d9e5a4e3885->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $data_filiacao->addValidation("Data de filiação ", new TRequiredValidator()); 
        $cpf->addValidation("CPF", new TCPFValidator(), []); 

        $path_foto->enableFileHandling();
        $path_foto->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $path_foto->setImagePlaceholder(new TImage("fas:file-upload #dde5ec"));
        $nome->forceUpperCase();
        $ativo->setValue('Sim');
        $flg_envio_dados->setValue(DMService::obterValPadrao('cooperados', 'flg_envio_dados'));

        $sexo->enableSearch();
        $estado_civil->enableSearch();

        $data_filiacao->setDatabaseMask('yyyy-mm-dd');
        $dt_desfiliacao->setDatabaseMask('yyyy-mm-dd');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');

        $flg_retem_ir->addItems(["S"=>"Sim","N"=>"Não"]);
        $flg_declara_dep->addItems(["S"=>"Sim","N"=>"Não"]);
        $flg_recolhe_inss->addItems(["S"=>"Sim","N"=>"Não"]);

        $flg_retem_ir->setBreakItems(2);
        $flg_declara_dep->setBreakItems(2);
        $flg_recolhe_inss->setBreakItems(2);

        $rg->setMaxLength(9);
        $cpf->setMaxLength(14);
        $inss->setMaxLength(15);
        $contabilidade->setMaxLength(150);

        $rg->setMask('#.###.###');
        $cpf->setMask('###.###.###-##');
        $data_filiacao->setMask('dd/mm/yyyy');
        $dt_desfiliacao->setMask('dd/mm/yyyy');
        $data_nascimento->setMask('dd/mm/yyyy');

        $ativo->setLayout('horizontal');
        $flg_retem_ir->setLayout('horizontal');
        $flg_declara_dep->setLayout('horizontal');
        $flg_envio_dados->setLayout('horizontal');
        $flg_recolhe_inss->setLayout('horizontal');

        $ativo->setUseButton();
        $flg_retem_ir->setUseButton();
        $flg_declara_dep->setUseButton();
        $flg_envio_dados->setUseButton();
        $flg_recolhe_inss->setUseButton();

        $movimentacoes->setAction(new TAction(['CooperadosMovimentacoesList', 'onShow']));
        $beneficios_container->setAction(new TAction(['BeneficiosCortinaList', 'onShow']));
        $table_capital->setAction(new TAction(['CooperadosCapitalCortinaList', 'onShow']));
        $table_totais->setAction(new TAction(['VCooperadosCapitalTotaisSimpleList', 'onShow']));
        $contatos_container->setAction(new TAction(['CooperadosContatosCortinaList', 'onShow']));
        $enderecos_container->setAction(new TAction(['CooperadosEnderecosCortinaList', 'onShow']));
        $documentos_container->setAction(new TAction(['CooperadosDocumentacoesCortinaList', 'onShow']));
        $especialidades_container->setAction(new TAction(['CooperadosEspecialidadesCortinaList', 'onShow']));
        $dados_bancarios_container->setAction(new TAction(['CooperadosDadosBancariosCortinaList', 'onShow']));

        $table_totais->setId('b67252eb97383b');
        $table_capital->setId('b671a905a0a21e');
        $movimentacoes->setId('b6a0f5cb81bb4a');
        $contatos_container->setId('b6488b2ce6ad34');
        $enderecos_container->setId('b648c406c69528');
        $beneficios_container->setId('b64903bc3b3dd9');
        $documentos_container->setId('b648c8218ec753');
        $especialidades_container->setId('b648c62b9718dd');
        $dados_bancarios_container->setId('b649dbf91a14ea');

        $table_totais->hide();
        $table_capital->hide();
        $movimentacoes->hide();
        $contatos_container->hide();
        $enderecos_container->hide();
        $beneficios_container->hide();
        $documentos_container->hide();
        $especialidades_container->hide();
        $dados_bancarios_container->hide();

        $id->setSize(200);
        $rg->setSize('100%');
        $crm->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');
        $ativo->setSize('90%');
        $sexo->setSize('100%');
        $cnis->setSize('100%');
        $inss->setSize('100%');
        $estado_civil->setSize('100%');
        $flg_retem_ir->setSize('100%');
        $table_totais->setSize('100%');
        $data_filiacao->setSize('100%');
        $numero_filhos->setSize('100%');
        $contabilidade->setSize('100%');
        $table_capital->setSize('100%');
        $movimentacoes->setSize('100%');
        $path_foto->setSize('100%', 170);
        $dt_desfiliacao->setSize('100%');
        $flg_envio_dados->setSize('90%');
        $data_nascimento->setSize('100%');
        $flg_declara_dep->setSize('100%');
        $flg_recolhe_inss->setSize('100%');
        $contatos_container->setSize('100%');
        $enderecos_container->setSize('100%');
        $forma_integralizacao->setSize('100%');
        $beneficios_container->setSize('100%');
        $documentos_container->setSize('100%');
        $especialidades_container->setSize('100%');
        $dados_bancarios_container->setSize('100%');
        $cooperados_secretaria_cooperados_nome->setSize('100%');
        $cooperados_secretaria_cooperados_email->setSize('100%');
        $cooperados_ctb_contato_cooperados_nome->setSize('100%');
        $cooperados_ctb_contato_cooperados_email->setSize('100%');
        $cooperados_secretaria_cooperados_contato->setSize('100%');
        $cooperados_ctb_contato_cooperados_numero->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_totais->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_capital->add($loadingContainer);
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
        $this->beneficios_container = $beneficios_container;
        $this->documentos_container = $documentos_container;
        $this->dados_bancarios_container = $dados_bancarios_container;
        $this->table_totais = $table_totais;
        $this->table_capital = $table_capital;
        $this->movimentacoes = $movimentacoes;

        // Não exibe BpageContainer caso não possuir id 
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

            $loadingContainer->add('<br>Para incluir os benefícios é necessário clicar em salvar!');

            $beneficios_container->add($loadingContainer);
            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';

            $loadingContainer->add('<br>Para incluir os documentos é necessário clicar em salvar!');

            $documentos_container->add($loadingContainer);
            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';

            $loadingContainer = new TElement('div');
            $loadingContainer->style = 'text-align:center; padding:50px';

            $loadingContainer->add('<br>Para incluir os dados bancários é necessário clicar em salvar!');

            $dados_bancarios_container->add($loadingContainer);
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

            $beneficios_container->add($loadingContainer);
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
        }

        //Zerar variável de seção
        TSession::setValue('form_CooperadoContatosList_Cooperado_id', '');

        $this->form->appendPage("Dados Gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $bcontainer_66d0b3231ce4f = new BootstrapFormBuilder('bcontainer_66d0b3231ce4f');
        $this->bcontainer_66d0b3231ce4f = $bcontainer_66d0b3231ce4f;
        $bcontainer_66d0b3231ce4f->setProperty('style', 'border:none; box-shadow:none;');
        $row1 = $bcontainer_66d0b3231ce4f->addFields([$id,new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome],[new TLabel("CRM: <font color=\"red\">*</font>", null, '14px', null, '100%'),$crm],[new TLabel("Data de filiação: <font color=\"red\">*</font>", null, '14px', null, '100%'),$data_filiacao],[new TLabel("CPF:", null, '14px', null, '100%'),$cpf],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo]);
        $row1->layout = ['col-sm-4','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $bcontainer_66d0b3231ce4f->addFields([new TLabel("RG:", null, '14px', null, '100%'),$rg],[new TLabel("Sexo:", null, '14px', null, '100%'),$sexo],[new TLabel("Data de nascimento:", null, '14px', null, '100%'),$data_nascimento],[new TLabel("Estado civil:", null, '14px', null, '100%'),$estado_civil],[new TLabel("Forma de integralização:", null, '14px', null, '100%'),$forma_integralizacao]);
        $row2->layout = ['col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-4'];

        $row3 = $bcontainer_66d0b3231ce4f->addFields([new TLabel("CNIS:", null, '14px', null, '100%'),$cnis],[new TLabel("INSS:", null, '14px', null, '100%'),$inss],[new TLabel("Recolhe INSS:", null, '14px', null, '100%'),$flg_recolhe_inss],[new TLabel("Retém IR:", null, '14px', null, '100%'),$flg_retem_ir],[new TLabel("Declara dependentes:", null, '14px', null, '100%'),$flg_declara_dep],[new TLabel("Nº de dependentes:", null, '14px', null, '100%'),$numero_filhos]);
        $row3->layout = ['col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row4 = $bcontainer_66d0b3231ce4f->addFields([new TLabel("Data desfiliação:", null, '14px', null, '100%'),$dt_desfiliacao],[],[],[],[],[]);
        $row4->layout = ['col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row5 = $this->form->addFields([new TLabel("Foto:", null, '14px', null, '100%'),$path_foto],[$bcontainer_66d0b3231ce4f]);
        $row5->layout = [' col-sm-2',' col-sm-10'];

        $this->form->appendPage("Endereços");
        $row6 = $this->form->addFields([$enderecos_container]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Contatos");
        $row7 = $this->form->addFields([$contatos_container]);
        $row7->layout = [' col-sm-12'];

        $this->form->appendPage("Especialidades");
        $row8 = $this->form->addFields([$especialidades_container]);
        $row8->layout = [' col-sm-12'];

        $this->form->appendPage("Secretárias");
        $row9 = $this->form->addFields([$this->fieldList_66eb1c34654ed]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Benefícios");
        $row10 = $this->form->addFields([$beneficios_container]);
        $row10->layout = [' col-sm-12'];

        $this->form->appendPage("Documentos");
        $row11 = $this->form->addFields([$documentos_container]);
        $row11->layout = [' col-sm-12'];

        $this->form->appendPage("Dados Bancários");
        $row12 = $this->form->addFields([$dados_bancarios_container]);
        $row12->layout = [' col-sm-12'];

        $this->form->appendPage("Contabilidade");
        $row13 = $this->form->addFields([new TLabel("Contabilidade:", null, '14px', null, '100%'),$contabilidade],[new TLabel("Permite envio de dados?", null, '14px', null, '100%'),$flg_envio_dados]);
        $row13->layout = [' col-sm-9','col-sm-3'];

        $row14 = $this->form->addContent([new TFormSeparator("Contatos", '#8694B0', '14', '#eee')]);
        $row15 = $this->form->addFields([$this->fieldList_66d9e5a4e3885]);
        $row15->layout = [' col-sm-12'];

        $this->form->appendPage("Capital social");

        $tab_67252e67c47de = new BootstrapFormBuilder('tab_67252e67c47de');
        $this->tab_67252e67c47de = $tab_67252e67c47de;
        $tab_67252e67c47de->setProperty('style', 'border:none; box-shadow:none;');

        $tab_67252e67c47de->appendPage("Totais");

        $tab_67252e67c47de->addFields([new THidden('current_tab_tab_67252e67c47de')]);
        $tab_67252e67c47de->setTabFunction("$('[name=current_tab_tab_67252e67c47de]').val($(this).attr('data-current_page'));");

        $row16 = $tab_67252e67c47de->addFields([$table_totais]);
        $row16->layout = [' col-sm-12'];

        $tab_67252e67c47de->appendPage("Lançamentos");
        $row17 = $tab_67252e67c47de->addFields([$table_capital]);
        $row17->layout = [' col-sm-12'];

        $row18 = $this->form->addFields([$tab_67252e67c47de]);
        $row18->layout = [' col-sm-12'];

        $this->form->appendPage("Movimentações");
        $row19 = $this->form->addFields([$movimentacoes]);
        $row19->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar']), 'fas:arrow-left #000000');
        $this->btn_onvoltar = $btn_onvoltar;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de cooperados"]));
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

            $object = new Cooperados(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->nome = ucwords(strtolower($object->nome));

            $path_foto_dir = 'arquivos/fotos_cooperados';  

            $object->store(); // save the object 

            if(empty($data->id))
            {
                try
                {
                    $docs_padrao = DocumentacoesPadraoCooperados::where('id', 'IS NOT', NULL)
                                                                ->load();
                    // Adiciona documentos padrão
                    foreach ($docs_padrao as $doc)
                    {
                        $cooperado_doc = new CooperadosDocumentacoes();
                        $cooperado_doc->cooperados_id = $object->id;
                        $cooperado_doc->tipos_documentacoes_id = $doc->tipos_documentacoes_id;
                        $cooperado_doc->documentacoes_id = $doc->documentacoes_id;
                        $cooperado_doc->entregue = 'Não';
                        $cooperado_doc->ativo = 'Sim';
                        $cooperado_doc->store();
                    }
                }
                catch (Exception $e)
                {
                    ExpService::mostrar(35, $e->getMessage());
                    //Não gerou as documentações padrão<br>{$1}
                }
            }

            $this->saveFile($object, $data, 'path_foto', $path_foto_dir); 

            $cooperados_secretaria_cooperados_items = $this->storeItems('CooperadosSecretaria', 'cooperados_id', $object, $this->fieldList_66eb1c34654ed, function($masterObject, $detailObject){ 

                //code here

            }, $this->criteria_fieldList_66eb1c34654ed); 

//<generatedAutoCode>
            $this->criteria_fieldList_66d9e5a4e3885->setProperty('order', 'id asc');
//</generatedAutoCode>
            $cooperados_ctb_contato_cooperados_items = $this->storeItems('CooperadosCtbContato', 'cooperados_id', $object, $this->fieldList_66d9e5a4e3885, function($masterObject, $detailObject){ 

                //code here

            }, $this->criteria_fieldList_66d9e5a4e3885); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            // Alimenta vaiável de seção para uso nas tabs.
            TSession::setValue('form_CooperadoContatosList_Cooperado_id', $data->id);

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
    public function onVoltar($param = null) 
    {
        try 
        {
            $origem = TSession::getValue('cooperado_form_origem');

            if ($origem == 'ArquivosCooperadoList')
            {
                TSession::setValue('cooperado_form_origem', null);

                TApplication::loadPage('ArquivosCooperadoList', 'onShow');
            }
            else
            {
                TApplication::loadPage('CooperadosList', 'onShow');
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                TSession::setValue('form_CooperadoContatosList_Cooperado_id', $param['key']);

                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Cooperados($key); // instantiates the Active Record 

                                $this->enderecos_container->unhide();
                $this->enderecos_container->setParameter('cooperados_id', $object->id);
                $this->contatos_container->unhide();
                $this->contatos_container->setParameter('cooperados_id', $object->id);
                $this->especialidades_container->unhide();
                $this->especialidades_container->setParameter('cooperados_id', $object->id);
                $this->beneficios_container->unhide();
                $this->beneficios_container->setParameter('cooperados_id', $object->id);
                $this->documentos_container->unhide();
                $this->documentos_container->setParameter('cooperados_id', $object->id);
                $this->dados_bancarios_container->unhide();
                $this->dados_bancarios_container->setParameter('cooperados_id', $object->id);
                $this->table_totais->unhide();
                $this->table_totais->setParameter('cooperados_id', $object->id);
                $this->table_capital->unhide();
                $this->table_capital->setParameter('cooperados_id', $object->id);
                $this->movimentacoes->unhide();
                $this->movimentacoes->setParameter('id_cooperado', $object->id);

                $this->fieldList_66eb1c34654ed_items = $this->loadItems('CooperadosSecretaria', 'cooperados_id', $object, $this->fieldList_66eb1c34654ed, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_66eb1c34654ed); 

                $this->criteria_fieldList_66d9e5a4e3885->setProperty('order', 'id asc');
                $this->fieldList_66d9e5a4e3885_items = $this->loadItems('CooperadosCtbContato', 'cooperados_id', $object, $this->fieldList_66d9e5a4e3885, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_66d9e5a4e3885); 

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

        $this->fieldList_66eb1c34654ed->addHeader();
        $this->fieldList_66eb1c34654ed->addDetail($this->default_item_fieldList_66eb1c34654ed);

        $this->fieldList_66eb1c34654ed->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        $this->fieldList_66d9e5a4e3885->addHeader();
        $this->fieldList_66d9e5a4e3885->addDetail($this->default_item_fieldList_66d9e5a4e3885);

        $this->fieldList_66d9e5a4e3885->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        // reload para zerar variaveis do form
        TApplication::loadPage(__CLASS__,'onShow');

        // Zera variável de sessão
        TSession::setValue('form_CooperadoContatosList_Cooperado_id', '');

    }

    public function onShow($param = null)
    {
        $this->fieldList_66eb1c34654ed->addHeader();
        $this->fieldList_66eb1c34654ed->addDetail($this->default_item_fieldList_66eb1c34654ed);

        $this->fieldList_66eb1c34654ed->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        $this->fieldList_66d9e5a4e3885->addHeader();
        $this->fieldList_66d9e5a4e3885->addDetail($this->default_item_fieldList_66d9e5a4e3885);

        $this->fieldList_66d9e5a4e3885->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

