<?php

class ImportadorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ImportadorForm';

  private static $database2 = 'databaserede';

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
        $this->form->setFormTitle("Importador ");

        $criteria_separador = new TCriteria();
        $criteria_separador_cred = new TCriteria();

        $filterVar = "dm_separador_import";
        $criteria_separador->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "dm_separador_import";
        $criteria_separador_cred->add(new TFilter('codigo', '=', $filterVar)); 

        $separador = new TDBCombo('separador', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_separador );
        $arquivo_cooperados = new TFile('arquivo_cooperados');
        $button_importar = new TButton('button_importar');
        $arquivo_contatos = new TFile('arquivo_contatos');
        $button_importar1 = new TButton('button_importar1');
        $element_66b766a2a7e3b = new BElement('a');
        $separador_cred = new TDBCombo('separador_cred', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_separador_cred );
        $arquivo_creden = new TFile('arquivo_creden');
        $button_importar2 = new TButton('button_importar2');
        $element_creden = new BElement('a');

        $separador->addValidation("Separador", new TRequiredValidator()); 

        $separador->enableSearch();
        $separador_cred->enableSearch();

        $arquivo_contatos->setAllowedExtensions(["csv"]);
        $arquivo_cooperados->setAllowedExtensions(["csv"]);

        $arquivo_creden->enableFileHandling();
        $arquivo_contatos->enableFileHandling();
        $arquivo_cooperados->enableFileHandling();

        $button_importar2->setAction(new TAction([$this, 'onImportarCreden']), "Importar");
        $button_importar->setAction(new TAction([$this, 'onImportarCooperado']), "Importar");
        $button_importar1->setAction(new TAction([$this, 'onImportarContatos']), "Importar");

        $button_importar->addStyleClass('btn-default');
        $button_importar1->addStyleClass('btn-default');
        $button_importar2->addStyleClass('btn-default');

        $button_importar->setImage('fas:download #4CAF50');
        $button_importar1->setImage('fas:download #4CAF50');
        $button_importar2->setImage('fas:download #4CAF50');

        $separador->setSize('100%');
        $arquivo_creden->setSize('87%');
        $separador_cred->setSize('100%');
        $arquivo_contatos->setSize('87%');
        $arquivo_cooperados->setSize('87%');
        $element_creden->setSize('100%', 80);
        $element_66b766a2a7e3b->setSize('100%', 80);

        $this->element_66b766a2a7e3b = $element_66b766a2a7e3b;
        $this->element_creden = $element_creden;

        $template = new THtmlRenderer('app/resources/Informacoes_import.html');
        $template->enableSection('main');
        $element_66b766a2a7e3b = $template;

        $template_cre = new THtmlRenderer('app/resources/Informacoes_import_creden.html');
        $template_cre->enableSection('main');
        $element_creden = $template_cre;

        //$element_66b766a2a7e3b->html($template);

        $this->form->appendPage("Cooperados");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Separador: <font color=\"red\">*</font>", null, '14px', null, '100%'),$separador],[new TLabel("Cooperados:", null, '14px', null, '100%'),$arquivo_cooperados,$button_importar]);
        $row1->layout = ['col-sm-3','col-sm-9'];

        $row2 = $this->form->addFields([],[new TLabel("Contatos dos Cooperados:", null, '14px', null, '100%'),$arquivo_contatos,$button_importar1]);
        $row2->layout = ['col-sm-3','col-sm-9'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row4 = $this->form->addFields([$element_66b766a2a7e3b]);
        $row4->layout = [' col-sm-12'];

        $this->form->appendPage("Credenciados");
        $row5 = $this->form->addFields([new TLabel("Separador: <font color=\"red\">*</font>", null, '14px', null, '100%'),$separador_cred],[new TLabel("Arquivo:", null, '14px', null, '100%'),$arquivo_creden,$button_importar2]);
        $row5->layout = ['col-sm-3',' col-sm-9'];

        $row6 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row7 = $this->form->addFields([$element_creden]);
        $row7->layout = [' col-sm-12'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Utilitários","Importador "]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onImportarCooperado($param = null) 
    {
        try 
        {
            //Obtém o nome do arquivo
            $fileName = json_decode(urldecode($param['arquivo_cooperados']))->fileName;

            //Abre o arquivo
            $handle = fopen($fileName, "r");

            //Abre uma transação com o banco de dados
            TTransaction::open(self::$database2);

            //Contador de registros inseridos
            $count = 0;

            //Separador das colunas do arquivo CSV
            //$separador = ',';
            $separador = $param['separador'];

            //Limite de caracteres que uma linha pode ter, 0 = sem limite
            $limite_da_linha = 0;

            //Percorre todas as linhas do arquivos
            while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                //echo '<br>COOP: '.$dados[1];
                // Cadastro de Cidades
                $nome_cidade = $dados[12];

                $cidades = Cidades::where('cidade', '=', $nome_cidade)->count();

                if($cidades == 0)
                {
                    $cidade = new Cidades;
                    $cidade->estados_id = 1;
                    $cidade->cidade =  $nome_cidade;

                    $cidade->store();
                }

                $qtd_coop = Cooperados::where('crm', '=', $dados[1])->count();

                if ($qtd_coop > 0){
                    $coop = Cooperados::where('crm', '=', $dados[1])->first();
                    $cooperado = Cooperados::find($coop->id);
                }else{
                    $cooperado = new Cooperados;
                }

                $cooperado->crm             =  $dados[1];
                $cooperado->nome            =  ucwords(strtolower($dados[2]));
                $cooperado->cpf             =  $dados[4];
                if($dados[6] == 'MASCULINO')
                {
                    $cooperado->sexo        =  'Masculino';
                }
                elseif ($dados[6] == 'FEMININO')
                {
                     $cooperado->sexo       =  'Feminino';
                }
                $cooperado->rg              =  utf8_encode($dados[8]);
                $cooperado->data_filiacao   =  $dados[16];
                $cooperado->data_nascimento =  $dados[17];
                $cooperado->inss            =  $dados[26];
                $cooperado->ativo           =  'Sim';

                //$cooperado->data_filiacao[0] = $cooperado->data_filiacao[0] > 30 ? '19' . $cooperado->data_filiacao[0] : '20' . $cooperado->data_filiacao[0];
                //$cooperado->data_filiacao = date('Y-m-d', strtotime($cooperado->data_filiacao));

                //$cooperado->data_nascimento[0] = $cooperado->data_nascimento[0] > 30 ? '19' . $cooperado->data_nascimento[0] : '20' . $cooperado->data_nascimento[0];
                //$cooperado->data_nascimento = date('Y-m-d', strtotime($cooperado->data_nascimento));

                $cooperado->store();

                // Cadastro endereços
                $qtd_end = EnderecosCooperados::where('endereco', '=', $dados[9] )
                                              ->where('bairro'  , '=', $dados[10])
                                              ->where('cep'     , '=', $dados[11])
                                              ->where('cooperados_id', '=', $cooperado->id)
                                              ->count();
                if ($qtd_end == 0){
                    $endereco                     = new EnderecosCooperados;
                    $endereco->endereco           = $dados[9];
                    $endereco->bairro             = $dados[10];
                    $endereco->cep                = $dados[11];
                    $endereco->tipos_enderecos_id = '1';     
                    $endereco->cooperados_id      = $cooperado->id;
                    // seleciona cidades                
                    $select_cidades       = Cidades::where('cidade', '=',  $dados[12])->first();
                    $endereco->cidades_id = $select_cidades->id;
                    $endereco->store();
                }

                // cadastro de especialidade
                $especialidade_desc = $dados[15];
                $especialidades     = Especialidades::where('especialidade', '=', $especialidade_desc)->count();

                if($especialidades == 0)
                {
                    $especialidade                = new Especialidades;
                    $especialidade->especialidade = $especialidade_desc;
                    $especialidade->store();
                }

                // especialidade do cooperado
                $select_especialidade               = Especialidades::where('especialidade', '=', $especialidade_desc)->first();

                $qtd_esp = CooperadosEspecialidades::where('cooperados_id'    , '=', $cooperado->id           )
                                                   ->where('especialidades_id', '=', $select_especialidade->id)
                                                   ->count();

                if ($qtd_esp == 0){
                    $cooperado_esp                      = new CooperadosEspecialidades;
                    $cooperado_esp->especialidades_id   = $select_especialidade->id;
                    $cooperado_esp->cooperados_id       = $cooperado->id;            
                    $cooperado_esp->rqe                 = $dados[19];            
                    $cooperado_esp->imprime_guia_medico = "Sim";            
                    $cooperado_esp->store();
                }

                // cadastro de bancos
                $banco_desc = '';
                $desc_banco = trim($dados[21]);
                if ($desc_banco == '1')
                {
                    $banco_desc = 'Banco do Brasil';
                }
                elseif ($desc_banco == '10')
                {
                    $banco_desc = 'Caixa Econômica Federal';
                }
                elseif ($desc_banco == '33')
                {
                    $banco_desc = 'Banco Santander';
                }
                elseif ($desc_banco == '104')
                {
                    $banco_desc = 'Banco Santander';
                }
                elseif ($desc_banco == '136')
                {
                    $banco_desc = 'Banco Unicred';
                }
                elseif ($desc_banco == '237')
                {
                    $banco_desc = 'Banco Bradesco';
                }
                elseif ($desc_banco == '260')
                {
                    $banco_desc = 'Banco Nubank';
                }
                elseif ($desc_banco == '341')
                {
                    $banco_desc = 'Itaú Unibanco';
                }
                elseif ($desc_banco == '399')
                {
                    $banco_desc = 'HSBC Bank Brasil';
                }
                elseif ($desc_banco == '748')
                {
                    $banco_desc = 'Banco Cooperativo Sicredi';
                }
                elseif ($desc_banco == '756')
                {
                    $banco_desc = 'Banco Cooperativo do Brasil - BANCOOB';
                }

                if (!empty($banco_desc))
                {
                    $bancos    = Bancos::where('banco', '=', trim($dados[21]))->count();

                    if($bancos == 0)
                    {
                        $banco         = new Bancos;
                        $banco->banco  = $banco_desc;
                        $banco->codigo = trim($dados[21]);
                        $banco->store();
                    }
                    $select_bancos                      = Bancos::where('banco', '=', $banco_desc)->first();

                    $conta = CooperadosDadosBancarios::where('cooperados_id', '=', $cooperado->id)
                                                     ->where('banco_id'     , '=', $select_bancos->id)
                                                     ->where('conta'        , '=', $dados[23])
                                                     ->first();

                    if (!$conta){
                        // Dados bancários do cooperado
                        $dados_bancarios                    = new CooperadosDadosBancarios;
                        $dados_bancarios->banco_id          = $select_bancos->id;
                        $dados_bancarios->cooperados_id     = $cooperado->id;            
                        // $dados_bancarios->conta_descricao   = "Conta importada";      
                        $dados_bancarios->agencia           = $dados[22];            
                        $dados_bancarios->conta             = $dados[23];            
                        $dados_bancarios->ativo             = "Sim";            
                        $dados_bancarios->store();
                    }
                }

                // count cooperados importados
                $count++;

            }

            //Fecha a transação
            TTransaction::close();

            //Fecha o arquivo
            fclose($handle);

            //Ação a ser executada quando a mensagem de sucesso for fechada
            $closeAction = new TAction(['CooperadosList', 'onReload']);

            //Mensagem de sucesse
            new TMessage('info', "{$count} cooperados foram importados!", $closeAction);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public  function onImportarContatos($param = null) 
    {
        try 
        {
            //Obtém o nome do arquivo
            $fileName = json_decode(urldecode($param['arquivo_contatos']))->fileName;

            //Abre o arquivo
            $handle = fopen($fileName, "r");

            //Abre uma transação com o banco de dados
            TTransaction::open(self::$database2);

            //Contador de registros inseridos
            $count = 0;

            //Separador das colunas do arquivo CSV
            //$separador = ',';
            $separador = $param['separador'];

            //Limite de caracteres que uma linha pode ter, 0 = sem limite
            $limite_da_linha = 0;

            $tipo_contato               = new TiposContatos(1);
            $tipo_contato->tipo_contato = 'Telefone Celular Particular';
            $tipo_contato->store();

            $tipo_contato               = new TiposContatos(2);
            $tipo_contato->tipo_contato = 'Telefone fixo Consultório e WhatsApp';
            $tipo_contato->store();

            $tipo_contato               = new TiposContatos(3);
            $tipo_contato->tipo_contato = 'WhatsApp consultório';
            $tipo_contato->store();

            $tipo_contato               = new TiposContatos(4);
            $tipo_contato->tipo_contato = 'E-mail Particular';
            $tipo_contato->store();

            $tipo_contato               = new TiposContatos(5);
            $tipo_contato->tipo_contato = 'E-mail Clínica';
            $tipo_contato->store();

            //Percorre todas as linhas do arquivos
            while (($dados_contato = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                $crm_coop                        = trim($dados_contato[1]);
                $cooperados                      = Cooperados::where('crm', '=', $crm_coop)->first();

                if(!empty($cooperados->id) && !empty($dados_contato[4]))
                {
                    $contato_coop                    = new CooperadosContatos;
                    $contato_coop->cooperados_id     = $cooperados->id;
                    $contato_coop->tipos_contatos_id = 1;
                    $contato_coop->contato           = $dados_contato[4];
                    $contato_coop->save();
                    $count++;
                }
               if(!empty($cooperados->id) && !empty($dados_contato[5]))
                {
                    $contato_coop                    = new CooperadosContatos;
                    $contato_coop->cooperados_id     = $cooperados->id;
                    $contato_coop->tipos_contatos_id = 2;
                    $contato_coop->contato           = $dados_contato[5];
                    $contato_coop->save();
                    $count++;
                }
                if(!empty($cooperados->id) && !empty($dados_contato[6]))
                {
                    $contato_coop                    = new CooperadosContatos;
                    $contato_coop->cooperados_id     = $cooperados->id;
                    $contato_coop->tipos_contatos_id = 3;
                    $contato_coop->contato           = $dados_contato[6];
                    $contato_coop->save();
                    $count++;
                }
                if(!empty($cooperados->id) && !empty($dados_contato[7]))
                {
                    $contato_coop                    = new CooperadosContatos;
                    $contato_coop->cooperados_id     = $cooperados->id;
                    $contato_coop->tipos_contatos_id = 4;
                    $contato_coop->contato           = $dados_contato[7];
                    $contato_coop->save();
                    $count++;
                }
                if(!empty($cooperados->id) && !empty($dados_contato[8]))
                {
                    $contato_coop                    = new CooperadosContatos;
                    $contato_coop->cooperados_id     = $cooperados->id;
                    $contato_coop->tipos_contatos_id = 5;
                    $contato_coop->contato           = $dados_contato[8];
                    $contato_coop->save();
                    $count++;
                }

            }

                  //Fecha a transação
            TTransaction::close();

            //Fecha o arquivo
            fclose($handle);

            //Ação a ser executada quando a mensagem de sucesso for fechada
            $closeAction = new TAction(['CooperadosList', 'onReload']);

            //Mensagem de sucesse
            new TMessage('info', "{$count} contatos foram importados!", $closeAction);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportarCreden($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database2);
            $fileName        = json_decode(urldecode($param['arquivo_creden']))->fileName;
            $handle          = fopen($fileName, "r");
            $count           = 0;
            $separador       = $param['separador_cred'];
            $limite_da_linha = 0;

            while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE) {
                $aux_especialidade = ucwords(trim($dados[0]));
                $aux_nome          = ucwords(trim($dados[1]));
                $aux_codigo        = trim($dados[2]);
                $aux_dta_contrato  = trim($dados[3]);
                $aux_cnpj          = trim($dados[4]);
                $aux_cnes          = trim($dados[5]);
                $aux_telefone      = trim($dados[6]);
                $aux_whatsapp      = trim($dados[7]);
                $aux_email         = strtolower(trim($dados[8]));
                $aux_logradouro    = ucwords(trim($dados[9]));
                $aux_numero        = trim($dados[10]);
                $aux_complemento   = trim($dados[11]);
                $aux_bairro        = trim($dados[12]);
                $aux_cidade        = ucwords(trim($dados[13]));
                $aux_cep           = trim($dados[14]);
                $aux_resp_tecnico  = ucwords(trim($dados[15]));
                $aux_resp_legal    = ucwords(trim($dados[16]));

                $aux_credenciado = Credenciados::where('cnpj', '=', $aux_cnpj)->first();

                if (!$aux_credenciado) {
                    $aux_credenciado = new Credenciados();
                    $aux_credenciado->data_inicio      = $aux_dta_contrato;
                    $aux_credenciado->nome             = $aux_nome;
                    $aux_credenciado->cnpj             = $aux_cnpj;
                    $aux_credenciado->cnes             = $aux_cnes;
                    $aux_credenciado->ativo            = 'S';
                    $aux_credenciado->codigo_prestador = $aux_codigo;
                    $aux_credenciado->data_contrato    = $aux_dta_contrato;
                    $aux_credenciado->store();
                }

                $aux_espec = Especialidades::where('especialidade', '=', $aux_especialidade)->first();

                if (!$aux_espec) {
                    $aux_espec = new Especialidades();
                    $aux_espec->especialidade = $aux_especialidade;
                    $aux_espec->store();
                }

                $aux_espec_cred = CredenciadosEspecialidades::where('credenciados_id'  , '=', $aux_credenciado->id)
                                                            ->where('especialidades_id', '=', $aux_espec->id)
                                                            ->count();

                if ($aux_espec_cred == 0) {
                    $aux_espec_cred = new CredenciadosEspecialidades();
                    $aux_espec_cred->imprime_guia_medico = 'N';
                    $aux_espec_cred->credenciados_id     = $aux_credenciado->id;
                    $aux_espec_cred->especialidades_id   = $aux_espec->id;
                    $aux_espec_cred->store();
                }

                $aux_vet_cont = array($aux_telefone, $aux_whatsapp, $aux_email);

                foreach ($aux_vet_cont as $aux_cont) {
                    if (!empty($aux_cont)) {
                        $aux_cont_cred = CredenciadosContatos::where('credenciados_id', '=', $aux_credenciado->id)
                                                             ->where('contato', '=', $aux_cont)
                                                             ->count();
                        if ($aux_cont_cred == 0) {
                            $aux_cont_cred = new CredenciadosContatos();
                            $aux_cont_cred->credenciados_id   = $aux_credenciado->id;
                            $aux_cont_cred->tipos_contatos_id = 7;
                            $aux_cont_cred->contato           = $aux_cont;
                            $aux_cont_cred->store();
                        }
                    }
                }

                $aux_vet_resp = array($aux_resp_tecnico, $aux_resp_legal);
                $aux_index    = 0;
                foreach ($aux_vet_resp as $aux_resp) {
                    if (!empty($aux_resp)) {
                        if ($aux_index == 0) $aux_tip_resp = 'T';
                        else $aux_tip_resp = 'L';

                        $aux_cont_resp = CredenciadosResponsaveis::where('credenciados_id', '=', $aux_credenciado->id)
                                                                 ->where('nome'           , '=', $aux_resp)
                                                                 ->where('dm_categoria'   , '=', $aux_tip_resp)
                                                                 ->count();

                        if ($aux_cont_resp == 0) {
                            $aux_cont_resp = new CredenciadosResponsaveis();
                            $aux_cont_resp->credenciados_id = $aux_credenciado->id;
                            $aux_cont_resp->dm_categoria    = $aux_tip_resp;
                            $aux_cont_resp->nome            = $aux_resp;
                            $aux_cont_resp->ativo           = 'S';
                            $aux_cont_resp->store();
                        }
                    }
                    $aux_index++;
                }

                $aux_cidade_obj = Cidades::where('cidade', '=', $aux_cidade)->first();

                if (!$aux_cidade_obj) {
                    $aux_cidade_obj = new Cidades();
                    $aux_cidade_obj->estados_id = 1;
                    $aux_cidade_obj->cidade     = $aux_cidade;
                    $aux_cidade_obj->store();
                }

                $aux_cont_endereco = CredenciadosEnderecos::where('credenciados_id', '=', $aux_credenciado->id)
                                                          ->where('numero'         , '=', $aux_numero)
                                                          ->where('endereco'       , '=', $aux_logradouro)
                                                          ->where('cidades_id'     , '=', $aux_cidade_obj->id)
                                                          ->count();

                if ($aux_cont_endereco == 0) {
                    $aux_endereco = new CredenciadosEnderecos();
                    $aux_endereco->credenciados_id    = $aux_credenciado->id;
                    $aux_endereco->cidades_id         = $aux_cidade_obj->id;
                    $aux_endereco->tipos_enderecos_id = 1;
                    $aux_endereco->cep                = $aux_cep;
                    $aux_endereco->endereco           = $aux_logradouro;
                    $aux_endereco->numero             = $aux_numero;
                    $aux_endereco->bairro             = $aux_bairro;
                    $aux_endereco->cnes               = $aux_cnes;
                    $aux_endereco->store();
                }

            }

            TTransaction::close();
            new TMessage('info', "Arquivo processado!");

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

    } 

}

