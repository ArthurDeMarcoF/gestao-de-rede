<?php

class CooperadosList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'databaserede';
    private static $activeRecord = 'Cooperados';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosList';
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
        $this->form->setFormTitle("Listagem de cooperados");
        $this->limit = 20;

        $criteria_sexo = new TCriteria();
        $criteria_estado_civil = new TCriteria();
        $criteria_ativo = new TCriteria();
        $criteria_especialidades = new TCriteria();
        $criteria_cidade = new TCriteria();
        $criteria_flg_recolhe_inss = new TCriteria();
        $criteria_flg_retem_ir = new TCriteria();
        $criteria_flg_declara_dep = new TCriteria();

        $filterVar = "cooperados";
        $criteria_sexo->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "sexo";
        $criteria_sexo->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_estado_civil->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "estado_civil";
        $criteria_estado_civil->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "dm_sim_nao";
        $criteria_ativo->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_flg_recolhe_inss->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_recolhe_inss";
        $criteria_flg_recolhe_inss->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_flg_retem_ir->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_retem_ir";
        $criteria_flg_retem_ir->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "cooperados";
        $criteria_flg_declara_dep->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_declara_dep";
        $criteria_flg_declara_dep->add(new TFilter('atributo', '=', $filterVar)); 

        $nome = new TEntry('nome');
        $crm = new TEntry('crm');
        $cpf = new TEntry('cpf');
        $rg = new TEntry('rg');
        $sexo = new TDBCombo('sexo', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_sexo );
        $estado_civil = new TDBCombo('estado_civil', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_estado_civil );
        $cnis = new TEntry('cnis');
        $inss = new TEntry('inss');
        $data_filiacao_inicio = new TDate('data_filiacao_inicio');
        $data_filiacao_fim = new TDate('data_filiacao_fim');
        $data_nascimento = new TDate('data_nascimento');
        $dt_desfiliacao_inicio = new TDate('dt_desfiliacao_inicio');
        $dt_desfiliacao_fim = new TDate('dt_desfiliacao_fim');
        $ativo = new TDBRadioGroup('ativo', 'databaserede', 'VDominioValor', 'valor', '{mascara}','sequencia asc' , $criteria_ativo );
        $especialidades = new TDBCombo('especialidades', 'databaserede', 'Especialidades', 'id', '{especialidade}','especialidade asc' , $criteria_especialidades );
        $cidade = new TDBCombo('cidade', 'databaserede', 'Cidades', 'id', '{cidade}','cidade asc' , $criteria_cidade );
        $flg_recolhe_inss = new TDBRadioGroup('flg_recolhe_inss', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_recolhe_inss );
        $flg_retem_ir = new TDBRadioGroup('flg_retem_ir', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_retem_ir );
        $flg_declara_dep = new TDBRadioGroup('flg_declara_dep', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_declara_dep );
        $vigente_inicial = new TDate('vigente_inicial');
        $vigente_final = new TDate('vigente_final');


        $rg->setMaxLength(9);
        $cpf->setMaxLength(14);
        $inss->setMaxLength(15);

        $sexo->enableSearch();
        $cidade->enableSearch();
        $estado_civil->enableSearch();
        $especialidades->enableSearch();

        $ativo->setLayout('horizontal');
        $flg_retem_ir->setLayout('horizontal');
        $flg_declara_dep->setLayout('horizontal');
        $flg_recolhe_inss->setLayout('horizontal');

        $ativo->setUseButton();
        $flg_retem_ir->setUseButton();
        $flg_declara_dep->setUseButton();
        $flg_recolhe_inss->setUseButton();

        $vigente_final->setDatabaseMask('yyyy-mm-dd');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $vigente_inicial->setDatabaseMask('yyyy-mm-dd');
        $data_filiacao_fim->setDatabaseMask('yyyy-mm-dd');
        $dt_desfiliacao_fim->setDatabaseMask('yyyy-mm-dd');
        $data_filiacao_inicio->setDatabaseMask('yyyy-mm-dd');
        $dt_desfiliacao_inicio->setDatabaseMask('yyyy-mm-dd');

        $rg->setMask('#.###.###');
        $cpf->setMask('###.###.###-##');
        $vigente_final->setMask('dd/mm/yyyy');
        $data_nascimento->setMask('dd/mm/yyyy');
        $vigente_inicial->setMask('dd/mm/yyyy');
        $data_filiacao_fim->setMask('dd/mm/yyyy');
        $dt_desfiliacao_fim->setMask('dd/mm/yyyy');
        $data_filiacao_inicio->setMask('dd/mm/yyyy');
        $dt_desfiliacao_inicio->setMask('dd/mm/yyyy');

        $rg->setSize('100%');
        $crm->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $cnis->setSize('100%');
        $inss->setSize('100%');
        $ativo->setSize('90%');
        $cidade->setSize('100%');
        $vigente_final->setSize(110);
        $flg_retem_ir->setSize('90%');
        $estado_civil->setSize('100%');
        $data_nascimento->setSize(150);
        $vigente_inicial->setSize(110);
        $data_filiacao_fim->setSize(150);
        $especialidades->setSize('100%');
        $flg_declara_dep->setSize('90%');
        $dt_desfiliacao_fim->setSize(150);
        $flg_recolhe_inss->setSize('90%');
        $data_filiacao_inicio->setSize(150);
        $dt_desfiliacao_inicio->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("CRM:", null, '14px', null, '100%'),$crm]);
        $row1->layout = [' col-sm-7',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("CPF:", null, '14px', null, '100%'),$cpf],[new TLabel("RG:", null, '14px', null, '100%'),$rg],[new TLabel("Sexo:", null, '14px', null, '100%'),$sexo]);
        $row2->layout = [' col-sm-4',' col-sm-3',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Estado civil:", null, '14px', null, '100%'),$estado_civil],[new TLabel("CNIS:", null, '14px', null, '100%'),$cnis],[new TLabel("INSS:", null, '14px', null, '100%'),$inss]);
        $row3->layout = ['col-sm-3',' col-sm-4',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Data de filiação:", null, '14px', null, '100%'),$data_filiacao_inicio,new TLabel("até", null, '14px', null),$data_filiacao_fim],[new TLabel("Data de nascimento:", null, '14px', null, '100%'),$data_nascimento]);
        $row4->layout = [' col-sm-6','col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Data desfiliação:", null, '14px', null, '100%'),$dt_desfiliacao_inicio,new TLabel("até", null, '14px', null),$dt_desfiliacao_fim],[new TLabel("Ativo:", null, '14px', null, '100%'),$ativo]);
        $row5->layout = [' col-sm-6','col-sm-3'];

        $row6 = $this->form->addFields([new TLabel("Especialidade:", null, '14px', null, '100%'),$especialidades],[new TLabel("Cidade:", null, '14px', null, '100%'),$cidade],[]);
        $row6->layout = ['col-sm-6',' col-sm-4','col-sm-2'];

        $row7 = $this->form->addFields([new TLabel("Recolhe INSS:", null, '14px', null, '100%'),$flg_recolhe_inss],[new TLabel("Retém IR:", null, '14px', null, '100%'),$flg_retem_ir],[new TLabel("Declara dependentes:", null, '14px', null, '100%'),$flg_declara_dep]);
        $row7->layout = [' col-sm-3',' col-sm-3',' col-sm-3'];

        $row8 = $this->form->addFields([new TLabel("Vigentes no período:", null, '14px', null, '100%'),$vigente_inicial,new TLabel("até", null, '14px', null),$vigente_final]);
        $row8->layout = [' col-sm-6'];

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

        $column_path_foto_transformed = new TDataGridColumn('path_foto', "Foto", 'left' , '60px');
        $column_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_data_nascimento_transformed = new TDataGridColumn('data_nascimento', "Idade", 'left');
        $column_crm = new TDataGridColumn('crm', "CRM", 'left');
        $column_cpf = new TDataGridColumn('cpf', "CPF", 'left');
        $column_data_filiacao_transformed = new TDataGridColumn('data_filiacao', "Data de filiação", 'left');
        $column_cooperados_especialidades_especialidades_to_string = new TDataGridColumn('cooperados_especialidades_especialidades_to_string', "Especialidades", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_id = new TDataGridColumn('id', "ID", 'center' , '70px');

        $column_path_foto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if ($value)
                return "<img src=\"{$value}\" style=\" width: 50px; height: auto; border-radius: 10px; border: 1px solid #8694B0; \">";

            return '';

        });

        $column_data_nascimento_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $aux_data = new DateTime($value);
            $aux_hoje = new DateTime(date('Y-m-d'));
            $aux_diff = $aux_data->diff($aux_hoje);
            $aux_param = ParamService::valor('format_data_nasc');

            if ($aux_param == 1) // Data (dd/mm/aaaa)
                $aux_expr = $aux_data->format(DMService::obterMask('dm_format_data', 'D', false));

            elseif ($aux_param == 2) // Idade (X anos)
                $aux_expr = ExpService::montar(25, // {$1} anos 
                                               $aux_diff->format('%Y'));

            elseif ($aux_param == 3) // Data + Idade (dd/mm/aaaa, X anos)
                $aux_expr = ExpService::montar(26, // {$1}, {$2} anos 
                                               $aux_data->format(DMService::obterMask('dm_format_data', 'D', false)),
                                               $aux_diff->format('%Y'));
            else
                $aux_expr = 'Erro';

            if ($aux_data->format('%m-%d') == $aux_hoje->format('%m-%d'))
                $aux_expr = "<span style='white-space: nowrap'><i class='fas fa-birthday-cake' aria-hidden=''true''></i> $aux_expr</span>";

            return $aux_expr;

        });

        $column_data_filiacao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        $order_data_filiacao_transformed = new TAction(array($this, 'onReload'));
        $order_data_filiacao_transformed->setParameter('order', 'data_filiacao');
        $column_data_filiacao_transformed->setAction($order_data_filiacao_transformed);
        $order_ativo_transformed = new TAction(array($this, 'onReload'));
        $order_ativo_transformed->setParameter('order', 'ativo');
        $column_ativo_transformed->setAction($order_ativo_transformed);
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_path_foto_transformed);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento_transformed);
        $this->datagrid->addColumn($column_crm);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_data_filiacao_transformed);
        $this->datagrid->addColumn($column_cooperados_especialidades_especialidades_to_string);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_id);

        $action_onEdit = new TDataGridAction(array('CooperadosForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('CooperadosList', 'onDelete'));
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

        $panel = new TPanelGroup("Listagem de cooperados");
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
        $button_cadastrar->setAction(new TAction(['CooperadosForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['CooperadosList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['CooperadosList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['CooperadosList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['CooperadosList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['CooperadosList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['CooperadosList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );

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
            $container->add(TBreadCrumb::create(["Cadastros","Cooperados"]));
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
                $object = new Cooperados($key, FALSE); 

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

                $objects = $this->getCooperadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum cooperado ativo encontrado');
                }

                $handler = fopen($output, 'w');

                // BOM para acentuação no Excel
                fwrite($handler, "\xEF\xBB\xBF");

                // Cabeçalho
                fputcsv($handler, ['Nome', 'Email'], ';');

                foreach ($objects as $object)
                {
                    fputcsv($handler, [
                        $object->nome,
                        $object->email
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

                $objects = $this->getCooperadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum cooperado ativo encontrado');
                }

                $table = new TTableWriterXLS([300, 300]);

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

                // Cabeçalho
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

                $objects = $this->getCooperadosAtivosRelatorio();

                if (!$objects)
                {
                    throw new Exception('Nenhum cooperado ativo encontrado');
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

                <h2>Cooperados Ativos</h2>

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
                    'Cooperados Ativos',
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
            $page->setProperty('page-name', 'CooperadosListSearch');
            $page->setProperty('page_name', 'CooperadosListSearch');
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

        if (isset($data->crm) AND ( (is_scalar($data->crm) AND $data->crm !== '') OR (is_array($data->crm) AND (!empty($data->crm)) )) )
        {

            $filters[] = new TFilter('crm', '=', $data->crm);// create the filter 
        }

        if (isset($data->cpf) AND ( (is_scalar($data->cpf) AND $data->cpf !== '') OR (is_array($data->cpf) AND (!empty($data->cpf)) )) )
        {

            $filters[] = new TFilter('cpf', '=', $data->cpf);// create the filter 
        }

        if (isset($data->rg) AND ( (is_scalar($data->rg) AND $data->rg !== '') OR (is_array($data->rg) AND (!empty($data->rg)) )) )
        {

            $filters[] = new TFilter('rg', '=', $data->rg);// create the filter 
        }

        if (isset($data->sexo) AND ( (is_scalar($data->sexo) AND $data->sexo !== '') OR (is_array($data->sexo) AND (!empty($data->sexo)) )) )
        {

            $filters[] = new TFilter('sexo', '=', $data->sexo);// create the filter 
        }

        if (isset($data->estado_civil) AND ( (is_scalar($data->estado_civil) AND $data->estado_civil !== '') OR (is_array($data->estado_civil) AND (!empty($data->estado_civil)) )) )
        {

            $filters[] = new TFilter('estado_civil', '=', $data->estado_civil);// create the filter 
        }

        if (isset($data->cnis) AND ( (is_scalar($data->cnis) AND $data->cnis !== '') OR (is_array($data->cnis) AND (!empty($data->cnis)) )) )
        {

            $filters[] = new TFilter('cnis', '=', $data->cnis);// create the filter 
        }

        if (isset($data->inss) AND ( (is_scalar($data->inss) AND $data->inss !== '') OR (is_array($data->inss) AND (!empty($data->inss)) )) )
        {

            $filters[] = new TFilter('inss', 'like', "%{$data->inss}%");// create the filter 
        }

        if (isset($data->data_filiacao_inicio) AND ( (is_scalar($data->data_filiacao_inicio) AND $data->data_filiacao_inicio !== '') OR (is_array($data->data_filiacao_inicio) AND (!empty($data->data_filiacao_inicio)) )) )
        {

            $filters[] = new TFilter('data_filiacao', '>=', $data->data_filiacao_inicio);// create the filter 
        }

        if (isset($data->data_filiacao_fim) AND ( (is_scalar($data->data_filiacao_fim) AND $data->data_filiacao_fim !== '') OR (is_array($data->data_filiacao_fim) AND (!empty($data->data_filiacao_fim)) )) )
        {

            $filters[] = new TFilter('data_filiacao', '<=', $data->data_filiacao_fim);// create the filter 
        }

        if (isset($data->data_nascimento) AND ( (is_scalar($data->data_nascimento) AND $data->data_nascimento !== '') OR (is_array($data->data_nascimento) AND (!empty($data->data_nascimento)) )) )
        {

            $filters[] = new TFilter('data_nascimento', '=', $data->data_nascimento);// create the filter 
        }

        if (isset($data->dt_desfiliacao_inicio) AND ( (is_scalar($data->dt_desfiliacao_inicio) AND $data->dt_desfiliacao_inicio !== '') OR (is_array($data->dt_desfiliacao_inicio) AND (!empty($data->dt_desfiliacao_inicio)) )) )
        {

            $filters[] = new TFilter('dt_desfiliacao', '>=', $data->dt_desfiliacao_inicio);// create the filter 
        }

        if (isset($data->dt_desfiliacao_fim) AND ( (is_scalar($data->dt_desfiliacao_fim) AND $data->dt_desfiliacao_fim !== '') OR (is_array($data->dt_desfiliacao_fim) AND (!empty($data->dt_desfiliacao_fim)) )) )
        {

            $filters[] = new TFilter('dt_desfiliacao', '<=', $data->dt_desfiliacao_fim);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->especialidades) AND ( (is_scalar($data->especialidades) AND $data->especialidades !== '') OR (is_array($data->especialidades) AND (!empty($data->especialidades)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT cooperados_id FROM cooperados_especialidades WHERE especialidades_id = '{$data->especialidades}')");// create the filter 
        }

        if (isset($data->cidade) AND ( (is_scalar($data->cidade) AND $data->cidade !== '') OR (is_array($data->cidade) AND (!empty($data->cidade)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT cooperados_id FROM enderecos_cooperados WHERE cidades_id = '{$data->cidade}')");// create the filter 
        }

        if (isset($data->flg_recolhe_inss) AND ( (is_scalar($data->flg_recolhe_inss) AND $data->flg_recolhe_inss !== '') OR (is_array($data->flg_recolhe_inss) AND (!empty($data->flg_recolhe_inss)) )) )
        {

            $filters[] = new TFilter('flg_recolhe_inss', '=', $data->flg_recolhe_inss);// create the filter 
        }

        if (isset($data->flg_retem_ir) AND ( (is_scalar($data->flg_retem_ir) AND $data->flg_retem_ir !== '') OR (is_array($data->flg_retem_ir) AND (!empty($data->flg_retem_ir)) )) )
        {

            $filters[] = new TFilter('flg_retem_ir', '=', $data->flg_retem_ir);// create the filter 
        }

        if (isset($data->flg_declara_dep) AND ( (is_scalar($data->flg_declara_dep) AND $data->flg_declara_dep !== '') OR (is_array($data->flg_declara_dep) AND (!empty($data->flg_declara_dep)) )) )
        {

            $filters[] = new TFilter('flg_declara_dep', '=', $data->flg_declara_dep);// create the filter 
        }

        if ((isset($data->vigente_inicial) && ((is_scalar($data->vigente_inicial) && $data->vigente_inicial !== '') || (is_array($data->vigente_inicial) && !empty($data->vigente_inicial)))) || (isset($data->vigente_final) &&((is_scalar($data->vigente_final) && $data->vigente_final !== '') || (is_array($data->vigente_final) && !empty($data->vigente_final))))
        )
        {
            $vigenteCriteria = new TCriteria;

            if (!empty($data->vigente_final))
            {
                $vigenteCriteria->add(new TFilter('data_filiacao', '<=', $data->vigente_final));
            }

            if (!empty($data->vigente_inicial))
            {
                $criterioDesfiliacao = new TCriteria;
                $criterioDesfiliacao->add(new TFilter('dt_desfiliacao', 'is', null));
                $criterioDesfiliacao->add(new TFilter('dt_desfiliacao', '>=', $data->vigente_inicial), TExpression::OR_OPERATOR);

                $vigenteCriteria->add($criterioDesfiliacao);
            }

            $filters[] = $vigenteCriteria;
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

            // creates a repository for Cooperados
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
                $countFilters == 0 ? null : $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
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

        $object = new Cooperados($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    private function getCooperadosAtivosRelatorio()
    {
        $conn = TTransaction::get();

        $sql = "
            SELECT DISTINCT
                c.nome,
                cc.contato AS email
            FROM cooperados c
            INNER JOIN cooperados_contatos cc
                ON cc.cooperados_id = c.id
                AND cc.tipos_contatos_id = 2
            WHERE c.ativo = 'S'
            ORDER BY c.nome
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}

