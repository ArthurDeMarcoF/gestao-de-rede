<?php

class ImportadorCapitalForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ImportadorCapitalForm';

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
        $this->form->setFormTitle("Importador de capital social");

        $criteria_separador = new TCriteria();

        $filterVar = "dm_separador_import";
        $criteria_separador->add(new TFilter('codigo', '=', $filterVar)); 

        $separador = new TDBCombo('separador', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_separador );
        $arquivo = new TFile('arquivo');
        $button_importar = new TButton('button_importar');
        $element_671ab1db4cc56 = new BElement('a');
        $table_log = new BPageContainer();

        $separador->addValidation("Separador", new TRequiredValidator()); 
        $arquivo->addValidation("Arquivo", new TRequiredValidator()); 

        $separador->setValue(DMService::obterValPadrao(__CLASS__, 'separador'));
        $separador->enableSearch();
        $arquivo->enableFileHandling();
        $button_importar->addStyleClass('btn-default');
        $button_importar->setImage('fas:download #4CAF50');
        $table_log->setId('b672a667528c98');
        $button_importar->setAction(new TAction([$this, 'onImportar']), "Importar");
        $table_log->setAction(new TAction(['LogImportCapitalSimpleList', 'onShow']));

        $arquivo->setSize('87%');
        $separador->setSize('100%');
        $table_log->setSize('100%');
        $element_671ab1db4cc56->setSize('100%', 80);

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $table_log->add($loadingContainer);

        $this->element_671ab1db4cc56 = $element_671ab1db4cc56;
        $this->table_log = $table_log;

        $aux_replace = [];
        $aux_replace['dm_capital_social'] = DMService::montarListHTML('dm_capital_social');

        $template = new THtmlRenderer('app/resources/Informacoes_import_capital.html');
        $template->disableHtmlConversion();
        $template->enableSection('main', $aux_replace);
        $element_671ab1db4cc56 = $template;

        $this->form->appendPage("Importar");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Separador: <font color=\"red\">*</font>", null, '14px', null, '100%'),$separador],[new TLabel("Arquivo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$arquivo,$button_importar]);
        $row1->layout = ['col-sm-3',' col-sm-9'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row3 = $this->form->addFields([$element_671ab1db4cc56]);
        $row3->layout = [' col-sm-12'];

        $this->form->appendPage("Logs");
        $row4 = $this->form->addFields([$table_log]);
        $row4->layout = [' col-sm-12'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Utilitários","Importador de capital social"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onImportar($param = null) 
    {
        $aux_linha = 0;
        try 
        {
            TTransaction::open('databaserede');

            $this->form->validate();

            $fileName        = json_decode(urldecode($param['arquivo']))->fileName;
            $handle          = fopen($fileName, "r");
            $count           = 0;
            $separador       = $param['separador'];
            $limite_da_linha = 0;

            $aux_log = new LogImportCapital();
            $aux_log->system_user_id = TSession::getValue('userid');
            $aux_log->nome_arq       = $fileName;
            $aux_log->dt_inicio      = date('Y-m-d H:i:s');
            $aux_log->store();

            if (empty($aux_log->id))
                ExpService::mostrar(36);

            while (($dados = fgetcsv($handle, $limite_da_linha, $separador)) !== false) {
                $aux_linha++;

                $aux_crm       = trim($dados[0]);
                $aux_grupo     = trim($dados[1]);
                $aux_parcela   = trim($dados[2]);
                $aux_aquisicao = trim($dados[3]);
                $aux_valor     = trim($dados[4]);

                if (empty($aux_crm))
                    ExpService::mostrar(15, 'A', 'CRM');
                if (empty($aux_grupo))
                    ExpService::mostrar(15, 'B', 'Código do grupo');
                if (empty($aux_aquisicao))
                    ExpService::mostrar(15, 'D', 'Data aquisição');
                if (empty($aux_valor))
                    ExpService::mostrar(15, 'E', 'Valor');

                $aux_coop = Cooperados::where('crm', '=', $aux_crm)->first();

                if (!$aux_coop)
                    ExpService::montar(16, $aux_crm);

                $aux_parcela   = $aux_parcela == 0 ? null : $aux_parcela;
                $aux_aquisicao = DateTime::createFromFormat('d/m/Y', $aux_aquisicao);
                $aux_aquisicao = $aux_aquisicao->format('Y-m-d');
                $aux_valor     = str_replace(',', '.', $aux_valor);

                $aux_cota = new CooperadosCapital();
                $aux_cota->cooperados_id         = $aux_coop->id;
                $aux_cota->dm_capital_social     = $aux_grupo;
                $aux_cota->nr_parcela            = $aux_parcela;
                $aux_cota->data_aquisicao        = $aux_aquisicao;
                $aux_cota->valor                 = $aux_valor;
                $aux_cota->log_import_capital_id = $aux_log->id;
                $aux_cota->store();
            }

            $aux_log->dt_termino = date('Y-m-d H:i:s');
            $aux_log->store();

            TTransaction::close();
            ExpService::mostrar(17);

        }
        catch (Exception $e) 
        {
            TTransaction::rollback();
            TTransaction::close();
            ExpService::mostrar(20, $e->getMessage(), $aux_linha);
        }
    }

    public function onShow($param = null)
    {               

    } 

}

