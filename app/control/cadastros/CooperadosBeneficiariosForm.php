<?php

class CooperadosBeneficiariosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CooperadosBeneficiarios';
    private static $primaryKey = 'id';
    private static $formName = 'form_CooperadosBeneficiariosForm';

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
        $this->form->setFormTitle("Cadastro de cooperados beneficiarios");

        $criteria_tipo = new TCriteria();
        $criteria_cooperados_id = new TCriteria();
        $criteria_cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo = new TCriteria();

        $filterVar = "dm_coop_benef_tipo";
        $criteria_tipo->add(new TFilter('codigo', '=', $filterVar)); 
        $filterVar = "dm_coop_benef_tipo_dep";
        $criteria_cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo->add(new TFilter('codigo', '=', $filterVar)); 

        $id = new THidden('id');
        $codigo = new TEntry('codigo');
        $tipo = new TDBCombo('tipo', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_tipo );
        $cooperados_id = new TDBCombo('cooperados_id', 'databaserede', 'Cooperados', 'id', '{nome}','nome asc' , $criteria_cooperados_id );
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_id = new THidden('cooperados_beneficiarios_dep_cooperados_beneficiarios_id[]');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios___row__id = new THidden('cooperados_beneficiarios_dep_cooperados_beneficiarios___row__id[]');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios___row__data = new THidden('cooperados_beneficiarios_dep_cooperados_beneficiarios___row__data[]');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_nome = new TEntry('cooperados_beneficiarios_dep_cooperados_beneficiarios_nome[]');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo = new TEntry('cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo[]');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo = new TDBCombo('cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo[]', 'databaserede', 'VDominioValor', 'valor', '{mascara_html}','sequencia asc' , $criteria_cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo );
        $this->fieldList_66d74bf7acf4e = new TFieldList();

        $this->fieldList_66d74bf7acf4e->addField(null, $cooperados_beneficiarios_dep_cooperados_beneficiarios_id, []);
        $this->fieldList_66d74bf7acf4e->addField(null, $cooperados_beneficiarios_dep_cooperados_beneficiarios___row__id, ['uniqid' => true]);
        $this->fieldList_66d74bf7acf4e->addField(null, $cooperados_beneficiarios_dep_cooperados_beneficiarios___row__data, []);
        $this->fieldList_66d74bf7acf4e->addField(new TLabel("Nome", null, '14px', null), $cooperados_beneficiarios_dep_cooperados_beneficiarios_nome, ['width' => '50%']);
        $this->fieldList_66d74bf7acf4e->addField(new TLabel("Código", null, '14px', null), $cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo, ['width' => '30%']);
        $this->fieldList_66d74bf7acf4e->addField(new TLabel("Tipo", null, '14px', null), $cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo, ['width' => '20%']);

        $this->fieldList_66d74bf7acf4e->width = '100%';
        $this->fieldList_66d74bf7acf4e->setFieldPrefix('cooperados_beneficiarios_dep_cooperados_beneficiarios');
        $this->fieldList_66d74bf7acf4e->name = 'fieldList_66d74bf7acf4e';

        $this->criteria_fieldList_66d74bf7acf4e = new TCriteria();
        $this->default_item_fieldList_66d74bf7acf4e = new stdClass();

        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios_id);
        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios___row__id);
        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios___row__data);
        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios_nome);
        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo);
        $this->form->addField($cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo);

        $this->fieldList_66d74bf7acf4e->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $cooperados_id->addValidation("Cooperados id", new TRequiredValidator()); 

        $codigo->setMask('0000.0000.000000.00-0');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo->setMask('0000.0000.000000.00-0');

        $tipo->enableSearch();
        $cooperados_id->enableSearch();
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo->enableSearch();

        $id->setSize(200);
        $tipo->setSize('100%');
        $codigo->setSize('100%');
        $cooperados_id->setSize('100%');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_nome->setSize('100%');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_codigo->setSize('100%');
        $cooperados_beneficiarios_dep_cooperados_beneficiarios_dm_tipo->setSize('100%');

        $row1 = $this->form->addFields([$id,new TLabel("Código :", null, '14px', null, '100%'),$codigo],[new TLabel("Tipo :", null, '14px', null, '100%'),$tipo]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Cooperado:", null, '14px', null, '100%'),$cooperados_id]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addContent([new TFormSeparator("Dependentes", '#8694B0', '18', '#eee')]);
        $row4 = $this->form->addFields([$this->fieldList_66d74bf7acf4e]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['CooperadosBeneficiariosList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro de cooperados beneficiarios"]));
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

            $object = new CooperadosBeneficiarios(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $cooperados_beneficiarios_dep_cooperados_beneficiarios_items = $this->storeItems('CooperadosBeneficiariosDep', 'cooperados_beneficiarios_id', $object, $this->fieldList_66d74bf7acf4e, function($masterObject, $detailObject){ 

                $detailObject->nome = ucwords(trim($detailObject->nome));

            }, $this->criteria_fieldList_66d74bf7acf4e); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('CooperadosBeneficiariosList', 'onShow', $loadPageParam); 

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

                $object = new CooperadosBeneficiarios($key); // instantiates the Active Record 

                $this->fieldList_66d74bf7acf4e_items = $this->loadItems('CooperadosBeneficiariosDep', 'cooperados_beneficiarios_id', $object, $this->fieldList_66d74bf7acf4e, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_fieldList_66d74bf7acf4e); 

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

        $this->fieldList_66d74bf7acf4e->addHeader();
        $this->fieldList_66d74bf7acf4e->addDetail($this->default_item_fieldList_66d74bf7acf4e);

        $this->fieldList_66d74bf7acf4e->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_66d74bf7acf4e->addHeader();
        $this->fieldList_66d74bf7acf4e->addDetail($this->default_item_fieldList_66d74bf7acf4e);

        $this->fieldList_66d74bf7acf4e->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

}

