<?php

class CredenciadosList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'Credenciados';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    use BuilderDatagridTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Listagem de credenciados");
        $this->limit = 20;

        $criteria_enquadramento_tributario = new TCriteria();
        $criteria_ativo = new TCriteria();
        $criteria_cidade = new TCriteria();
        $criteria_especialidade = new TCriteria();
        $criteria_dm_tipo = new TCriteria();

        $filterVar = "credenciados";
        $criteria_enquadramento_tributario->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "enquadramento_tributario";
        $criteria_enquadramento_tributario->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_ativo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "ativo";
        $criteria_ativo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados";
        $criteria_dm_tipo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo";
        $criteria_dm_tipo->add(new TFilter('atributo', '=', $filterVar)); 

        $nome = new TEntry('nome');
        $codigo_prestador = new TEntry('codigo_prestador');
        $cnpj = new TEntry('cnpj');
        $enquadramento_tributario = new TDBCombo('enquadramento_tributario', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_enquadramento_tributario );
        $data_inicio = new TDate('data_inicio');
        $data_credenciamento_inicio = new TDate('data_credenciamento_inicio');
        $data_credenciamento_fim = new TDate('data_credenciamento_fim');
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $dt_descredenciamento_inicio = new TDate('dt_descredenciamento_inicio');
        $dt_descredenciamento_fim = new TDate('dt_descredenciamento_fim');
        $cidade = new TDBCombo('cidade', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidade );
        $especialidade = new TDBCombo('especialidade', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidade );
        $dm_tipo = new TDBCombo('dm_tipo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo );


        $nome->setMaxLength(100);
        $ativo->setLayout('horizontal');
        $ativo->setUseButton();
        $ativo->setBreakItems(2);
        $cidade->enableSearch();
        $dm_tipo->enableSearch();
        $especialidade->enableSearch();
        $enquadramento_tributario->enableSearch();

        $data_inicio->setMask('dd/mm/yyyy');
        $data_credenciamento_fim->setMask('dd/mm/yyyy');
        $dt_descredenciamento_fim->setMask('dd/mm/yyyy');
        $data_credenciamento_inicio->setMask('dd/mm/yyyy');
        $dt_descredenciamento_inicio->setMask('dd/mm/yyyy');

        $data_inicio->setDatabaseMask('yyyy-mm-dd');
        $data_credenciamento_fim->setDatabaseMask('yyyy-mm-dd');
        $dt_descredenciamento_fim->setDatabaseMask('yyyy-mm-dd');
        $data_credenciamento_inicio->setDatabaseMask('yyyy-mm-dd');
        $dt_descredenciamento_inicio->setDatabaseMask('yyyy-mm-dd');

        $nome->setSize('100%');
        $cnpj->setSize('100%');
        $ativo->setSize('100%');
        $cidade->setSize('100%');
        $dm_tipo->setSize('100%');
        $data_inicio->setSize(110);
        $especialidade->setSize('100%');
        $codigo_prestador->setSize('100%');
        $data_credenciamento_fim->setSize(150);
        $dt_descredenciamento_fim->setSize(150);
        $data_credenciamento_inicio->setSize(150);
        $enquadramento_tributario->setSize('100%');
        $dt_descredenciamento_inicio->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("Código do prestador:", null, '14px', null, '100%'),$codigo_prestador]);
        $row1->layout = ['col-sm-6','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("CNPJ:", null, '14px', null, '100%'),$cnpj],[new TLabel("Enquadramento tributário:", null, '14px', null, '100%'),$enquadramento_tributario],[new TLabel("Data de início:", null, '14px', null, '100%'),$data_inicio]);
        $row2->layout = ['col-sm-3','col-sm-4','col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Data do credenciamento:", null, '14px', null, '100%'),$data_credenciamento_inicio,new TLabel("até", null, '14px', null),$data_credenciamento_fim],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo]);
        $row3->layout = ['col-sm-6','col-sm-3'];

        $row4 = $this->form->addFields([new TLabel("Data do descredenciamento:", null, '14px', null, '100%'),$dt_descredenciamento_inicio,new TLabel("até", null, '14px', null),$dt_descredenciamento_fim],[new TLabel("Cidade:", null, '14px', null, '100%'),$cidade]);
        $row4->layout = ['col-sm-6',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Especialidade:", null, '14px', null, '100%'),$especialidade],[new TLabel("Tipo:", null, '14px', null, '100%'),$dm_tipo]);
        $row5->layout = ['col-sm-6',' col-sm-4'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->enableUserProperties('fa fa-cog', 'btn btn-default', new TAction([$this, 'setDatagridProperties']));
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_codigo_prestador = new TDataGridColumn('codigo_prestador', "Código do prestador", 'left');
        $column_cnpj = new TDataGridColumn('cnpj', "CNPJ", 'left');
        $column_dm_tipo_transformed = new TDataGridColumn('dm_tipo', "Tipo", 'left');
        $column_enquadramento_tributario_transformed = new TDataGridColumn('enquadramento_tributario', "Enquadramento tributário", 'left');
        $column_data_inicio_transformed = new TDataGridColumn('data_inicio', "Data do credenciamento", 'left');
        $column_cnes = new TDataGridColumn('cnes', "CNES", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');

        $column_dm_tipo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            return DMService::obterMask('dm_tipo_credenciado', $value);

        });

        $column_enquadramento_tributario_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            return DMService::obterMask('dm_enquadr_trib', $value);

        });

        $column_data_inicio_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_ativo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            return DMService::obterMask('dm_sim_nao', $value);

        });        

        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_codigo_prestador);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_dm_tipo_transformed);
        $this->datagrid->addColumn($column_enquadramento_tributario_transformed);
        $this->datagrid->addColumn($column_data_inicio_transformed);
        $this->datagrid->addColumn($column_cnes);
        $this->datagrid->addColumn($column_ativo_transformed);

        $action_onEdit = new TDataGridAction(array('CredenciadosForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('CredenciadosList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        $this->pageNavigation->keepLastPagination(__CLASS__);

        $panel = new TPanelGroup("Listagem de credenciados");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;

        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($headerActions);

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['CredenciadosForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['CredenciadosList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['CredenciadosList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['CredenciadosList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['CredenciadosList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['CredenciadosList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['CredenciadosList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Credenciados"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Credenciados($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
    public function onExportCsv($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.csv';

            if ((!file_exists($output) && is_writable(dirname($output))) || is_writable($output))
            {
                TTransaction::open(self::$database);

                $objects = $this->getCredenciadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum credenciado ativo com e-mail encontrado');
                }

                $handler = fopen($output, 'w');

                fwrite($handler, "\xEF\xBB\xBF");

                fputcsv($handler, ['Nome', 'Email'], ';');

                foreach ($objects as $object)
                {
                    fputcsv($handler, [
                        $object->nome ?? '',
                        $object->email ?? ''
                    ], ';');
                }

                fclose($handler);

                TTransaction::close();

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    public function onExportXls($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xls';

            if ((!file_exists($output) && is_writable(dirname($output))) || is_writable($output))
            {
                TTransaction::open(self::$database);

                $objects = $this->getCredenciadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum credenciado ativo com e-mail encontrado');
                }

                $table = new \TTableWriterXLS([300, 300]);

                $table->addStyle(
                    'title',
                    'Helvetica',
                    '10',
                    'B',
                    '#ffffff',
                    '#617FC3'
                );

                $table->addStyle(
                    'data',
                    'Helvetica',
                    '10',
                    '',
                    '#000000',
                    '#FFFFFF',
                    'LR'
                );

                $table->addRow();
                $table->addCell('Nome', 'left', 'title');
                $table->addCell('Email', 'left', 'title');

                foreach ($objects as $object)
                {
                    $table->addRow();

                    $table->addCell(
                        $object->nome ?? '',
                        'left',
                        'data'
                    );

                    $table->addCell(
                        $object->email ?? '',
                        'left',
                        'data'
                    );
                }

                $table->save($output);

                TTransaction::close();

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ((!file_exists($output) && is_writable(dirname($output))) || is_writable($output))
            {
                TTransaction::open(self::$database);

                $objects = $this->getCredenciadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum credenciado ativo com e-mail encontrado');
                }

                $html = '
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                    }

                    h2 {
                        text-align: center;
                        margin-bottom: 20px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th {
                        background: #617FC3;
                        color: #ffffff;
                        padding: 8px;
                        text-align: left;
                        border: 1px solid #dddddd;
                    }

                    td {
                        padding: 7px;
                        border: 1px solid #dddddd;
                    }
                </style>

                <h2>Credenciados Ativos</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                ';

                foreach ($objects as $object)
                {
                    $nome = htmlspecialchars(
                        $object->nome ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );

                    $email = htmlspecialchars(
                        $object->email ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );

                    $html .= "
                        <tr>
                            <td>{$nome}</td>
                            <td>{$email}</td>
                        </tr>
                    ";
                }

                $html .= '
                    </tbody>
                </table>
                ';

                TTransaction::close();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents(
                    $output,
                    $dompdf->output()
                );

                $window = TWindow::create(
                    'Credenciados Ativos',
                    0.8,
                    0.8
                );

                $iframe = new TElement('iframe');
                $iframe->src = $output;
                $iframe->type = 'application/pdf';
                $iframe->style = 'width:100%; height:calc(100% - 10px)';

                $window->add($iframe);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    public static function onShowCurtainFilters($param = null) 
    {
        try 
        {
            //code here

                        $filter = new self([]);

            $btnClose = new TButton('closeCurtain');
            $btnClose->class = 'btn btn-sm btn-default';
            $btnClose->style = 'margin-right:10px;';
            $btnClose->onClick = "Template.closeRightPanel();";
            $btnClose->setLabel("Fechar");
            $btnClose->setImage('fas:times');

            $filter->form->addHeaderWidget($btnClose);

            $page = new TPage();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('page-name', 'CredenciadosListSearch');
            $page->setProperty('page_name', 'CredenciadosListSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if (isset($data->codigo_prestador) AND ( (is_scalar($data->codigo_prestador) AND $data->codigo_prestador !== '') OR (is_array($data->codigo_prestador) AND (!empty($data->codigo_prestador)) )) )
        {

            $filters[] = new TFilter('codigo_prestador', 'like', "%{$data->codigo_prestador}%");// create the filter 
        }

        if (isset($data->cnpj) AND ( (is_scalar($data->cnpj) AND $data->cnpj !== '') OR (is_array($data->cnpj) AND (!empty($data->cnpj)) )) )
        {

            $filters[] = new TFilter('cnpj', 'like', "%{$data->cnpj}%");// create the filter 
        }

        if (isset($data->enquadramento_tributario) AND ( (is_scalar($data->enquadramento_tributario) AND $data->enquadramento_tributario !== '') OR (is_array($data->enquadramento_tributario) AND (!empty($data->enquadramento_tributario)) )) )
        {

            $filters[] = new TFilter('enquadramento_tributario', 'like', "%{$data->enquadramento_tributario}%");// create the filter 
        }

        if (isset($data->data_inicio) AND ( (is_scalar($data->data_inicio) AND $data->data_inicio !== '') OR (is_array($data->data_inicio) AND (!empty($data->data_inicio)) )) )
        {

            $filters[] = new TFilter('data_inicio', '=', $data->data_inicio);// create the filter 
        }

        if (isset($data->data_credenciamento_inicio) AND ( (is_scalar($data->data_credenciamento_inicio) AND $data->data_credenciamento_inicio !== '') OR (is_array($data->data_credenciamento_inicio) AND (!empty($data->data_credenciamento_inicio)) )) )
        {

            $filters[] = new TFilter('data_inicio', '>=', $data->data_credenciamento_inicio);// create the filter 
        }

        if (isset($data->data_credenciamento_fim) AND ( (is_scalar($data->data_credenciamento_fim) AND $data->data_credenciamento_fim !== '') OR (is_array($data->data_credenciamento_fim) AND (!empty($data->data_credenciamento_fim)) )) )
        {

            $filters[] = new TFilter('data_inicio', '<=', $data->data_credenciamento_fim);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->dt_descredenciamento_inicio) AND ( (is_scalar($data->dt_descredenciamento_inicio) AND $data->dt_descredenciamento_inicio !== '') OR (is_array($data->dt_descredenciamento_inicio) AND (!empty($data->dt_descredenciamento_inicio)) )) )
        {

            $filters[] = new TFilter('dt_descredenciamento', '>=', $data->dt_descredenciamento_inicio);// create the filter 
        }

        if (isset($data->dt_descredenciamento_fim) AND ( (is_scalar($data->dt_descredenciamento_fim) AND $data->dt_descredenciamento_fim !== '') OR (is_array($data->dt_descredenciamento_fim) AND (!empty($data->dt_descredenciamento_fim)) )) )
        {

            $filters[] = new TFilter('dt_descredenciamento', '<=', $data->dt_descredenciamento_fim);// create the filter 
        }

        if (isset($data->cidade) AND ( (is_scalar($data->cidade) AND $data->cidade !== '') OR (is_array($data->cidade) AND (!empty($data->cidade)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT credenciados_id FROM credenciados_enderecos WHERE cidades_id = '{$data->cidade}')");// create the filter 
        }

        if (isset($data->especialidade) AND ( (is_scalar($data->especialidade) AND $data->especialidade !== '') OR (is_array($data->especialidade) AND (!empty($data->especialidade)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT credenciados_id FROM credenciados_especialidades WHERE especialidades_id = '{$data->especialidade}')");// create the filter 
        }

        if (isset($data->dm_tipo) AND ( (is_scalar($data->dm_tipo) AND $data->dm_tipo !== '') OR (is_array($data->dm_tipo) AND (!empty($data->dm_tipo)) )) )
        {

            $filters[] = new TFilter('dm_tipo', '=', $data->dm_tipo);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'databaserede'
            TTransaction::open(self::$database);

            // creates a repository for Credenciados
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'nome';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            //</blockLine><btnShowCurtainFiltersAutoCode>
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $countFilters <= 0 ? null : $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }
            //</blockLine></btnShowCurtainFiltersAutoCode>

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new Credenciados($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    private function getCredenciadosAtivosRelatorio()
    {
        $conn = TTransaction::get();

        $sql = "
            SELECT DISTINCT
                c.nome,
                cc.contato AS email
            FROM credenciados c
            INNER JOIN credenciados_contatos cc
                ON cc.credenciados_id = c.id
            AND cc.tipos_contatos_id = 6
            WHERE c.ativo = 'S'
            ORDER BY c.nome
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}

