<?php

class CatalogoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Catalogo';
    private static $primaryKey = 'id';
    private static $formName = 'form_CatalogoForm';

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
        $this->form->setFormTitle("Cadastro de catálogo");

        $criteria_dm_categoria = new TCriteria();
        $criteria_cidades_id = new TCriteria();

        $filterVar = "catalogo";
        $criteria_dm_categoria->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_categoria";
        $criteria_dm_categoria->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new THidden('id');
        $dm_categoria = new TDBCombo('dm_categoria', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_categoria );
        $cpf_cnpj = new TEntry('cpf_cnpj');
        $data_solicitacao = new TDate('data_solicitacao');
        $nome = new TEntry('nome');
        $cidades_id = new TDBCombo('cidades_id', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidades_id );
        $observacao = new TText('observacao');
        $table_especialidades = new BPageContainer();
        $table_contatos = new BPageContainer();
        $table_enderecos = new BPageContainer();

        $dm_categoria->addValidation("Categoria", new TRequiredValidator()); 
        $cpf_cnpj->addValidation("CPF/CNPJ", new TRequiredValidator()); 
        $data_solicitacao->addValidation("Data de solicitação", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 
        $cidades_id->addValidation("Cidades id", new TRequiredValidator()); 

        $data_solicitacao->setMask('dd/mm/yyyy');
        $data_solicitacao->setDatabaseMask('yyyy-mm-dd');
        $cidades_id->enableSearch();
        $dm_categoria->enableSearch();

        $nome->setMaxLength(100);
        $cpf_cnpj->setMaxLength(30);

        $table_contatos->setAction(new TAction(['CatalogoContatoCortinaList', 'onShow']));
        $table_enderecos->setAction(new TAction(['CatalogoEnderecoCortinaList', 'onShow']));
        $table_especialidades->setAction(new TAction(['CatalogoEspecialidadeCortinaList', 'onShow']));

        $table_contatos->setId('b6728f1d04ac2a');
        $table_enderecos->setId('b6728f1e0378d7');
        $table_especialidades->setId('b6728f5943b3a6');

        $table_contatos->hide();
        $table_enderecos->hide();
        $table_especialidades->hide();

        $id->setSize(200);
        $nome->setSize('100%');
        $cpf_cnpj->setSize('100%');
        $cidades_id->setSize('100%');
        $dm_categoria->setSize('100%');
        $observacao->setSize('100%', 70);
        $table_contatos->setSize('100%');
        $table_enderecos->setSize('100%');
        $data_solicitacao->setSize('100%');
        $table_especialidades->setSize('100%');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_especialidades->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_contatos->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_enderecos->add($loadingContainer);

        $this->table_especialidades = $table_especialidades;
        $this->table_contatos = $table_contatos;
        $this->table_enderecos = $table_enderecos;

        $this->form->appendPage("Dados");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$id,new TLabel("Categoria: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_categoria],[new TLabel("CPF/CNPJ: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cpf_cnpj],[new TLabel("Data de solicitação: <font color=\"red\">*</font>", null, '14px', null, '100%'),$data_solicitacao]);
        $row1->layout = [' col-sm-4',' col-sm-5',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Cidade: <font color=\"red\">*</font>", null, '14px', null, '100%'),$cidades_id]);
        $row3->layout = ['col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = [' col-sm-12'];

        $this->form->appendPage("Especialidades");
        $row5 = $this->form->addFields([$table_especialidades]);
        $row5->layout = [' col-sm-12'];

        $this->form->appendPage("Contatos");
        $row6 = $this->form->addFields([$table_contatos]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Endereços");
        $row7 = $this->form->addFields([$table_enderecos]);
        $row7->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CatalogoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        $btn_onefetivar = $this->form->addAction("Efetivar", new TAction([$this, 'onEfetivar']), 'fas:certificate #2196F3');
        $this->btn_onefetivar = $btn_onefetivar;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de catálogo"]));
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

            $object = new Catalogo(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TApplication::loadPage(__CLASS__, 'onEdit', ['key' => $object->id]);

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onEfetivar($param = null) 
    {
        try 
        {
            new TQuestion("Deseja efetivar o cadastro do catálogo?", 
                          new TAction([__CLASS__, 'onYesEfetivar'], ['key'=>$param['id']]), 
                          new TAction([__CLASS__, 'onNoEfetivar'],  ['key'=>$param['id']]));

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
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Catalogo($key); // instantiates the Active Record 

                                $this->table_especialidades->unhide();
                $this->table_especialidades->setParameter('catalogo_id', $object->id);
                $this->table_contatos->unhide();
                $this->table_contatos->setParameter('catalogo_id', $object->id);
                $this->table_enderecos->unhide();
                $this->table_enderecos->setParameter('catalogo_id', $object->id);

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

    public static function onYesEfetivar($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            $aux_cat = Catalogo::find($param['key']);
            $aux_cat_esp = CatalogoEspecialidade::where('catalogo_id', '=', $param['key'])
                                                ->load();
            $aux_cat_end = CatalogoEndereco::where('catalogo_id', '=', $param['key'])
                                           ->load();
            $aux_cat_con = CatalogoContato::where('catalogo_id', '=', $param['key'])
                                          ->load();

            // Cooperado
            if ($aux_cat->dm_categoria == 'C') { 
                $aux_coop = new Cooperados();
                $aux_coop->nome             = ucwords(strtolower(trim($aux_cat->nome)));
                $aux_coop->cpf              = $aux_cat->cpf_cnpj;
                $aux_coop->data_filiacao    = date('Y-m-d');
                $aux_coop->ativo            = DMService::obterValPadrao('cooperados', 'ativo');
                $aux_coop->flg_retem_ir     = DMService::obterValPadrao('cooperados', 'flg_retem_ir');
                $aux_coop->flg_declara_dep  = DMService::obterValPadrao('cooperados', 'flg_declara_dep');
                $aux_coop->flg_recolhe_inss = DMService::obterValPadrao('cooperados', 'flg_recolhe_inss');
                $aux_coop->flg_envio_dados  = DMService::obterValPadrao('cooperados', 'flg_envio_dados');
                $aux_coop->store();

                foreach ($aux_cat_esp as $aux_esp) {
                    $aux_coop_esp = new CooperadosEspecialidades();
                    $aux_coop_esp->cooperados_id       = $aux_coop->id;
                    $aux_coop_esp->especialidades_id   = $aux_esp->especialidades_id;
                    $aux_coop_esp->imprime_guia_medico = 'Não';
                    $aux_coop_esp->store();
                }

                foreach ($aux_cat_end as $aux_end) {
                    $aux_coop_end = new EnderecosCooperados();
                    $aux_coop_end->cooperados_id       = $aux_coop->id;
                    $aux_coop_end->cep                 = $aux_end->cep;
                    $aux_coop_end->endereco            = $aux_end->logradouro;
                    $aux_coop_end->numero              = $aux_end->numero;
                    $aux_coop_end->bairro              = $aux_end->bairro;
                    $aux_coop_end->cidades_id          = $aux_end->cidades_id;
                    $aux_coop_end->tipos_enderecos_id  = 1;
                    $aux_coop_end->imprime_guia_medico = DMService::obterValPadrao('enderecos_cooperados', 'imprime_guia_medico');
                    $aux_coop_end->store();
                }

                foreach ($aux_cat_con as $aux_con) {
                    $aux_coop_esp = new CooperadosContatos();
                    $aux_coop_esp->cooperados_id       = $aux_coop->id;
                    $aux_coop_esp->tipos_contatos_id   = 1;
                    $aux_coop_esp->contato             = $aux_con->contato;
                    $aux_coop_esp->imprime_guia_medico = DMService::obterValPadrao('cooperados_contatos', 'imprime_guia_medico');
                    $aux_coop_esp->store();
                }

                $aux_doc_padrao = DocumentacoesPadraoCooperados::where('documentacoes_id', 'IN', "(select d.id from documentacoes d where d.ativo = 'Sim')")
                                                               ->load();

                foreach ($aux_doc_padrao as $aux_doc) {
                    $aux_coop_doc = new CooperadosDocumentacoes();
                    $aux_coop_doc->cooperados_id          = $aux_coop->id;
                    $aux_coop_doc->tipos_documentacoes_id = $aux_doc->tipos_documentacoes_id;
                    $aux_coop_doc->documentacoes_id       = $aux_doc->documentacoes_id;
                    $aux_coop_doc->ativo                  = 'Sim';
                    $aux_coop_doc->entregue               = 'Não';
                    $aux_coop_doc->store();
                }

                TApplication::loadPage('CooperadosForm', 'onEdit', ['key'=>$aux_coop->id]);

            } elseif ($aux_cat->dm_categoria == 'P') {
                $aux_cred = new Credenciados();
                $aux_cred->data_inicio     = date('Y-m-d');
                $aux_cred->nome            = ucwords(trim($aux_cat->nome));
                $aux_cred->cnpj            = $aux_cat->cpf_cnpj;
                $aux_cred->ativo           = DMService::obterValPadrao('credenciados', 'ativo');
                $aux_cred->flg_dias_padrao = DMService::obterValPadrao('credenciados', 'flg_dias_padrao');
                $aux_cred->store();

                foreach ($aux_cat_esp as $aux_esp) {
                    $aux_cred_esp = new CredenciadosEspecialidades();
                    $aux_cred_esp->credenciados_id     = $aux_cred->id;
                    $aux_cred_esp->especialidades_id   = $aux_esp->especialidades_id;
                    $aux_cred_esp->imprime_guia_medico = DMService::obterValPadrao('credenciados_especialidades', 'imprime_guia_medico');;
                    $aux_cred_esp->store();
                }

                foreach ($aux_cat_end as $aux_end) {
                    $aux_cred_end = new CredenciadosEnderecos();
                    $aux_cred_end->credenciados_id     = $aux_cred->id;
                    $aux_cred_end->cep                 = $aux_end->cep;
                    $aux_cred_end->endereco            = $aux_end->logradouro;
                    $aux_cred_end->numero              = $aux_end->numero;
                    $aux_cred_end->bairro              = $aux_end->bairro;
                    $aux_cred_end->cidades_id          = $aux_end->cidades_id;
                    $aux_cred_end->tipos_enderecos_id  = 1;
                    $aux_cred_end->store();
                }

                foreach ($aux_cat_con as $aux_con) {
                    $aux_cred_esp = new CredenciadosContatos();
                    $aux_cred_esp->credenciados_id     = $aux_cred->id;
                    $aux_cred_esp->tipos_contatos_id   = 1;
                    $aux_cred_esp->contato             = $aux_con->contato;
                    $aux_cred_esp->store();
                }

                $aux_doc_padrao = DocumentacoesPadraoCredenciado::where('documentacoes_id', 'IN', "(select d.id from documentacoes d where d.ativo = 'Sim')")
                                                                ->load();
                foreach ($aux_doc_padrao as $aux_doc) {
                    $aux_cred_doc = new CredenciadosDocumentacoes();
                    $aux_cred_doc->credenciados_id        = $aux_cred->id;
                    $aux_cred_doc->tipos_documentacoes_id = $aux_doc->tipos_documentacoes_id;
                    $aux_cred_doc->documentacoes_id       = $aux_doc->documentacoes_id;
                    $aux_cred_doc->ativo                  = DMService::obterValPadrao('credenciados_documentacoes', 'ativo');
                    $aux_cred_doc->entregue               = DMService::obterValPadrao('credenciados_documentacoes', 'entregue');
                    $aux_cred_doc->store();
                }

                TApplication::loadPage('CredenciadosForm', 'onEdit', ['key'=>$aux_cred->id]);

            }

            if (ParamService::valor('sn_excluir_catalogo_efetivar') == 'S') {
                //Excluir o cadastro do catálogo ao efetivar, conforme parâmetro
                CatalogoEspecialidade::where('catalogo_id', '=', $param['key'])->delete();
                CatalogoEndereco::where('catalogo_id', '=', $param['key'])->delete();
                CatalogoContato::where('catalogo_id', '=', $param['key'])->delete();
                Catalogo::where('id', '=', $param['key'])->delete();
            }

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onNoEfetivar($param = null) 
    {
        try 
        {
            TApplication::loadPage(__CLASS__, 'onEdit', $param);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

