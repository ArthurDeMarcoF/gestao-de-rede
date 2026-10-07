<?php

class DominioForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Dominio';
    private static $primaryKey = 'id';
    private static $formName = 'form_DominioForm';

    use BuilderMasterDetailTrait;
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
        $this->form->setFormTitle("Cadastro de domínio");

        $criteria_flg_colorir_pad = new TCriteria();
        $criteria_dominio_relacionamento_dominio_flg_colorir = new TCriteria();

        $filterVar = "dominio";
        $criteria_flg_colorir_pad->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_colorir_pad";
        $criteria_flg_colorir_pad->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "dominio_relacionamento";
        $criteria_dominio_relacionamento_dominio_flg_colorir->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "flg_colorir";
        $criteria_dominio_relacionamento_dominio_flg_colorir->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new TEntry('id');
        $codigo = new TEntry('codigo');
        $nome = new TEntry('nome');
        $flg_colorir_pad = new TDBRadioGroup('flg_colorir_pad', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_flg_colorir_pad );
        $descricao = new TText('descricao');
        $dominio_valor_dominio_id = new THidden('dominio_valor_dominio_id');
        $dominio_valor_dominio_sequencia = new TEntry('dominio_valor_dominio_sequencia');
        $dominio_valor_dominio_valor = new TEntry('dominio_valor_dominio_valor');
        $dominio_valor_dominio_mascara = new TEntry('dominio_valor_dominio_mascara');
        $dominio_valor_dominio_cor_fundo = new TColor('dominio_valor_dominio_cor_fundo');
        $dominio_valor_dominio_cor_letra = new TColor('dominio_valor_dominio_cor_letra');
        $dominio_valor_dominio_icone = new TIcon('dominio_valor_dominio_icone');
        $dominio_valor_dominio_cor_grafico = new TColor('dominio_valor_dominio_cor_grafico');
        $button_adicionar_dominio_valor_dominio = new TButton('button_adicionar_dominio_valor_dominio');
        $dominio_relacionamento_dominio_id = new THidden('dominio_relacionamento_dominio_id[]');
        $dominio_relacionamento_dominio___row__id = new THidden('dominio_relacionamento_dominio___row__id[]');
        $dominio_relacionamento_dominio___row__data = new THidden('dominio_relacionamento_dominio___row__data[]');
        $dominio_relacionamento_dominio_objeto = new TEntry('dominio_relacionamento_dominio_objeto[]');
        $dominio_relacionamento_dominio_atributo = new TEntry('dominio_relacionamento_dominio_atributo[]');
        $dominio_relacionamento_dominio_valor_padrao = new TEntry('dominio_relacionamento_dominio_valor_padrao[]');
        $dominio_relacionamento_dominio_flg_colorir = new TDBCombo('dominio_relacionamento_dominio_flg_colorir[]', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dominio_relacionamento_dominio_flg_colorir );
        $this->fieldList_66d5e31ac6cdc = new TFieldList();

        $this->fieldList_66d5e31ac6cdc->addField(null, $dominio_relacionamento_dominio_id, []);
        $this->fieldList_66d5e31ac6cdc->addField(null, $dominio_relacionamento_dominio___row__id, ['uniqid' => true]);
        $this->fieldList_66d5e31ac6cdc->addField(null, $dominio_relacionamento_dominio___row__data, []);
        $this->fieldList_66d5e31ac6cdc->addField(new TLabel("Objeto", null, '14px', null), $dominio_relacionamento_dominio_objeto, ['width' => '35%']);
        $this->fieldList_66d5e31ac6cdc->addField(new TLabel("Atributo", null, '14px', null), $dominio_relacionamento_dominio_atributo, ['width' => '35%']);
        $this->fieldList_66d5e31ac6cdc->addField(new TLabel("Valor padrão", null, '14px', null), $dominio_relacionamento_dominio_valor_padrao, ['width' => '20%']);
        $this->fieldList_66d5e31ac6cdc->addField(new TLabel("Colorir", null, '14px', null), $dominio_relacionamento_dominio_flg_colorir, ['width' => '10%']);

        $this->fieldList_66d5e31ac6cdc->width = '100%';
        $this->fieldList_66d5e31ac6cdc->setFieldPrefix('dominio_relacionamento_dominio');
        $this->fieldList_66d5e31ac6cdc->name = 'fieldList_66d5e31ac6cdc';

        $this->criteria_fieldList_66d5e31ac6cdc = new TCriteria();
        $this->default_item_fieldList_66d5e31ac6cdc = new stdClass();

        $this->form->addField($dominio_relacionamento_dominio_id);
        $this->form->addField($dominio_relacionamento_dominio___row__id);
        $this->form->addField($dominio_relacionamento_dominio___row__data);
        $this->form->addField($dominio_relacionamento_dominio_objeto);
        $this->form->addField($dominio_relacionamento_dominio_atributo);
        $this->form->addField($dominio_relacionamento_dominio_valor_padrao);
        $this->form->addField($dominio_relacionamento_dominio_flg_colorir);

        $this->fieldList_66d5e31ac6cdc->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $codigo->addValidation("Código", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 

        $id->setEditable(false);
        $flg_colorir_pad->setLayout('horizontal');
        $flg_colorir_pad->setUseButton();
        $flg_colorir_pad->setBreakItems(2);
        $button_adicionar_dominio_valor_dominio->setAction(new TAction([$this, 'onAddDetailDominioValorDominio'],['static' => 1]), "Adicionar");
        $button_adicionar_dominio_valor_dominio->addStyleClass('btn-default');
        $button_adicionar_dominio_valor_dominio->setImage('fas:plus #2ecc71');
        $dominio_relacionamento_dominio_flg_colorir->enableSearch();
        $nome->setMaxLength(100);
        $codigo->setMaxLength(45);
        $dominio_valor_dominio_valor->setMaxLength(45);
        $dominio_valor_dominio_mascara->setMaxLength(255);

        $id->setSize('100%');
        $nome->setSize('100%');
        $codigo->setSize('100%');
        $descricao->setSize('100%', 70);
        $flg_colorir_pad->setSize('100%');
        $dominio_valor_dominio_id->setSize(200);
        $dominio_valor_dominio_valor->setSize('100%');
        $dominio_valor_dominio_icone->setSize('100%');
        $dominio_valor_dominio_mascara->setSize('100%');
        $dominio_valor_dominio_sequencia->setSize('100%');
        $dominio_valor_dominio_cor_fundo->setSize('100%');
        $dominio_valor_dominio_cor_letra->setSize('100%');
        $dominio_valor_dominio_cor_grafico->setSize('100%');
        $dominio_relacionamento_dominio_objeto->setSize('100%');
        $dominio_relacionamento_dominio_atributo->setSize('100%');
        $dominio_relacionamento_dominio_flg_colorir->setSize('100%');
        $dominio_relacionamento_dominio_valor_padrao->setSize('100%');

        $button_adicionar_dominio_valor_dominio->id = '66d5d388c6cd5';

        $row1 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'),$id],[new TLabel("Código: <font color=\"red\">*</font>", null, '14px', null, '100%'),$codigo],[]);
        $row1->layout = [' col-sm-2',' col-sm-6',' col-sm-4'];

        $tab_66d5d25ac6cc5 = new BootstrapFormBuilder('tab_66d5d25ac6cc5');
        $this->tab_66d5d25ac6cc5 = $tab_66d5d25ac6cc5;
        $tab_66d5d25ac6cc5->setProperty('style', 'border:none; box-shadow:none;');

        $tab_66d5d25ac6cc5->appendPage("Dados");

        $tab_66d5d25ac6cc5->addFields([new THidden('current_tab_tab_66d5d25ac6cc5')]);
        $tab_66d5d25ac6cc5->setTabFunction("$('[name=current_tab_tab_66d5d25ac6cc5]').val($(this).attr('data-current_page'));");

        $row2 = $tab_66d5d25ac6cc5->addFields([new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome],[new TLabel("Colorir padrão?", null, '14px', null, '100%'),$flg_colorir_pad]);
        $row2->layout = [' col-sm-10','col-sm-2'];

        $row3 = $tab_66d5d25ac6cc5->addFields([new TLabel("Descrição:", null, '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-12'];

        $tab_66d5d25ac6cc5->appendPage("Valores");

        $this->detailFormDominioValorDominio = new BootstrapFormBuilder('detailFormDominioValorDominio');
        $this->detailFormDominioValorDominio->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormDominioValorDominio->setProperty('class', 'form-horizontal builder-detail-form');

        $row4 = $this->detailFormDominioValorDominio->addFields([$dominio_valor_dominio_id,new TLabel("Sequência: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dominio_valor_dominio_sequencia],[new TLabel("Valor: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dominio_valor_dominio_valor],[new TLabel("Máscara: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dominio_valor_dominio_mascara]);
        $row4->layout = [' col-sm-2',' col-sm-4',' col-sm-6'];

        $row5 = $this->detailFormDominioValorDominio->addFields([new TLabel("Cor do fundo:", null, '14px', null, '100%'),$dominio_valor_dominio_cor_fundo],[new TLabel("Cor da letra:", null, '14px', null, '100%'),$dominio_valor_dominio_cor_letra],[new TLabel("Ícone:", null, '14px', null, '100%'),$dominio_valor_dominio_icone],[new TLabel("Cor em gráficos:", null, '14px', null, '100%'),$dominio_valor_dominio_cor_grafico]);
        $row5->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row6 = $this->detailFormDominioValorDominio->addFields([$button_adicionar_dominio_valor_dominio]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->detailFormDominioValorDominio->addFields([new THidden('dominio_valor_dominio__row__id')]);
        $this->dominio_valor_dominio_criteria = new TCriteria();

        $this->dominio_valor_dominio_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->dominio_valor_dominio_list->generateHiddenFields();
        $this->dominio_valor_dominio_list->setId('dominio_valor_dominio_list');

        $this->dominio_valor_dominio_list->style = 'width:100%';
        $this->dominio_valor_dominio_list->class .= ' table-bordered';

        $column_dominio_valor_dominio_sequencia = new TDataGridColumn('sequencia', "Sequência", 'left' , '15%');
        $column_dominio_valor_dominio_valor = new TDataGridColumn('valor', "Valor", 'left' , '25%');
        $column_dominio_valor_dominio_mascara_html = new TDataGridColumn('mascara_html', "Máscara", 'left' , '45%');
        $column_dominio_valor_dominio_cor_grafico_transformed = new TDataGridColumn('cor_grafico', "Cor em gráficos", 'center' , '15%');

        $column_dominio_valor_dominio__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_dominio_valor_dominio__row__data->setVisibility(false);

        $action_onEditDetailDominioValor = new TDataGridAction(array('DominioForm', 'onEditDetailDominioValor'));
        $action_onEditDetailDominioValor->setUseButton(false);
        $action_onEditDetailDominioValor->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailDominioValor->setLabel("Editar");
        $action_onEditDetailDominioValor->setImage('far:edit #478fca');
        $action_onEditDetailDominioValor->setFields(['__row__id', '__row__data']);

        $this->dominio_valor_dominio_list->addAction($action_onEditDetailDominioValor);
        $action_onDeleteDetailDominioValor = new TDataGridAction(array('DominioForm', 'onDeleteDetailDominioValor'));
        $action_onDeleteDetailDominioValor->setUseButton(false);
        $action_onDeleteDetailDominioValor->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailDominioValor->setLabel("Excluir");
        $action_onDeleteDetailDominioValor->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailDominioValor->setFields(['__row__id', '__row__data']);

        $this->dominio_valor_dominio_list->addAction($action_onDeleteDetailDominioValor);

        $this->dominio_valor_dominio_list->addColumn($column_dominio_valor_dominio_sequencia);
        $this->dominio_valor_dominio_list->addColumn($column_dominio_valor_dominio_valor);
        $this->dominio_valor_dominio_list->addColumn($column_dominio_valor_dominio_mascara_html);
        $this->dominio_valor_dominio_list->addColumn($column_dominio_valor_dominio_cor_grafico_transformed);

        $this->dominio_valor_dominio_list->addColumn($column_dominio_valor_dominio__row__data);

        $this->dominio_valor_dominio_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->dominio_valor_dominio_list);
        $this->detailFormDominioValorDominio->addContent([$tableResponsiveDiv]);

        $column_dominio_valor_dominio_cor_grafico_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if (!empty($value))
                return "<i class='fas fa-circle' aria-hidden='true'  style='color: ".$value.";'></i>";
            return '';

        });        $row8 = $tab_66d5d25ac6cc5->addFields([$this->detailFormDominioValorDominio]);
        $row8->layout = [' col-sm-12'];

        $tab_66d5d25ac6cc5->appendPage("Relacionamentos");
        $row9 = $tab_66d5d25ac6cc5->addFields([$this->fieldList_66d5e31ac6cdc]);
        $row9->layout = [' col-sm-12'];

        $row10 = $this->form->addFields([$tab_66d5d25ac6cc5]);
        $row10->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['DominioList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Parâmetros","Cadastro de domínio"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onAddDetailDominioValorDominio($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Sequência", 'name'=>"dominio_valor_dominio_sequencia", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Valor", 'name'=>"dominio_valor_dominio_valor", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Máscara", 'name'=>"dominio_valor_dominio_mascara", 'class'=>'TRequiredValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->dominio_valor_dominio__row__id) ? $data->dominio_valor_dominio__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new DominioValor();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->dominio_valor_dominio_id;
            $grid_data->sequencia = $data->dominio_valor_dominio_sequencia;
            $grid_data->valor = $data->dominio_valor_dominio_valor;
            $grid_data->mascara = $data->dominio_valor_dominio_mascara;
            $grid_data->cor_fundo = $data->dominio_valor_dominio_cor_fundo;
            $grid_data->cor_letra = $data->dominio_valor_dominio_cor_letra;
            $grid_data->icone = $data->dominio_valor_dominio_icone;
            $grid_data->cor_grafico = $data->dominio_valor_dominio_cor_grafico;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['dominio_valor_dominio_id'] ?? null;
            $__row__data['__display__']['sequencia'] =  $param['dominio_valor_dominio_sequencia'] ?? null;
            $__row__data['__display__']['valor'] =  $param['dominio_valor_dominio_valor'] ?? null;
            $__row__data['__display__']['mascara'] =  $param['dominio_valor_dominio_mascara'] ?? null;
            $__row__data['__display__']['cor_fundo'] =  $param['dominio_valor_dominio_cor_fundo'] ?? null;
            $__row__data['__display__']['cor_letra'] =  $param['dominio_valor_dominio_cor_letra'] ?? null;
            $__row__data['__display__']['icone'] =  $param['dominio_valor_dominio_icone'] ?? null;
            $__row__data['__display__']['cor_grafico'] =  $param['dominio_valor_dominio_cor_grafico'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->dominio_valor_dominio_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('dominio_valor_dominio_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->dominio_valor_dominio_id = '';
            $data->dominio_valor_dominio_sequencia = '';
            $data->dominio_valor_dominio_valor = '';
            $data->dominio_valor_dominio_mascara = '';
            $data->dominio_valor_dominio_cor_fundo = '';
            $data->dominio_valor_dominio_cor_letra = '';
            $data->dominio_valor_dominio_icone = '';
            $data->dominio_valor_dominio_cor_grafico = '';
            $data->dominio_valor_dominio__row__id = '';

            $data->dominio_valor_dominio_sequencia = $grid_data->sequencia + 1;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#66d5d388c6cd5');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailDominioValor($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->dominio_valor_dominio_id = $__row__data->__display__->id ?? null;
            $data->dominio_valor_dominio_sequencia = $__row__data->__display__->sequencia ?? null;
            $data->dominio_valor_dominio_valor = $__row__data->__display__->valor ?? null;
            $data->dominio_valor_dominio_mascara = $__row__data->__display__->mascara ?? null;
            $data->dominio_valor_dominio_cor_fundo = $__row__data->__display__->cor_fundo ?? null;
            $data->dominio_valor_dominio_cor_letra = $__row__data->__display__->cor_letra ?? null;
            $data->dominio_valor_dominio_icone = $__row__data->__display__->icone ?? null;
            $data->dominio_valor_dominio_cor_grafico = $__row__data->__display__->cor_grafico ?? null;
            $data->dominio_valor_dominio__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#66d5d388c6cd5');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailDominioValor($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->dominio_valor_dominio_id = '';
            $data->dominio_valor_dominio_sequencia = '';
            $data->dominio_valor_dominio_valor = '';
            $data->dominio_valor_dominio_mascara = '';
            $data->dominio_valor_dominio_cor_fundo = '';
            $data->dominio_valor_dominio_cor_letra = '';
            $data->dominio_valor_dominio_icone = '';
            $data->dominio_valor_dominio_cor_grafico = '';
            $data->dominio_valor_dominio__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('dominio_valor_dominio_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#66d5d388c6cd5');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Dominio(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $dominio_relacionamento_dominio_items = $this->storeItems('DominioRelacionamento', 'dominio_id', $object, $this->fieldList_66d5e31ac6cdc, function($masterObject, $detailObject){ 

            }, $this->criteria_fieldList_66d5e31ac6cdc); 

            $dominio_valor_dominio_items = $this->storeMasterDetailItems('DominioValor', 'dominio_id', 'dominio_valor_dominio', $object, $param['dominio_valor_dominio_list___row__data'] ?? [], $this->form, $this->dominio_valor_dominio_list, function($masterObject, $detailObject){ 

                $detailObject->mascara_html = self::montarMaskHTML($detailObject->mascara,
                                                                   $detailObject->cor_letra,
                                                                   $detailObject->cor_fundo,
                                                                   $detailObject->icone);

            }, $this->dominio_valor_dominio_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            DMService::eliminaSessao();

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
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Dominio($key); // instantiates the Active Record 

                $this->fieldList_66d5e31ac6cdc_items = $this->loadItems('DominioRelacionamento', 'dominio_id', $object, $this->fieldList_66d5e31ac6cdc, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_66d5e31ac6cdc); 

                $dominio_valor_dominio_items = $this->loadMasterDetailItems('DominioValor', 'dominio_id', 'dominio_valor_dominio', $object, $this->form, $this->dominio_valor_dominio_list, $this->dominio_valor_dominio_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

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

        $this->fieldList_66d5e31ac6cdc->addHeader();
        $this->fieldList_66d5e31ac6cdc->addDetail($this->default_item_fieldList_66d5e31ac6cdc);

        $this->fieldList_66d5e31ac6cdc->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_66d5e31ac6cdc->addHeader();
        $this->fieldList_66d5e31ac6cdc->addDetail($this->default_item_fieldList_66d5e31ac6cdc);

        $this->fieldList_66d5e31ac6cdc->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    public function montarMaskHTML($prm_mask, $prm_cor_letra, $prm_cor_fundo, $prm_icone){
        $aux_icone = '';
        $aux_mask  = '';
        if (!empty($prm_icone)){
            $aux_icone = "<i class='".$prm_icone."' aria-hidden='true'> </i> ";
        }

        if (!empty($prm_cor_letra) && !empty($prm_cor_fundo)){
            $aux_mask = '<span style="color: '.$prm_cor_letra.'; background: '.$prm_cor_fundo.'" class="label">'.$aux_icone.$prm_mask.'</span>';
        }elseif (!empty($prm_cor_letra) && empty($prm_cor_fundo)) {
            $aux_mask = '<span style="color: '.$prm_cor_letra.'; white-space: nowrap">'.$aux_icone.$prm_mask.'</span>';
        }else{
            $aux_mask = '<span style="white-space: nowrap">'.$aux_icone.$prm_mask.'</span>';
        }
        return $aux_mask;
    }

}

