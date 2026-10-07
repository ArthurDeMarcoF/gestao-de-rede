<?php

class ParametroForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'Parametro';
    private static $primaryKey = 'id';
    private static $formName = 'form_ParametroForm';

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
        $this->form->setFormTitle("Cadastro de parâmetro");

        $criteria_dm_tipo_param = new TCriteria();
        $criteria_dominio_id = new TCriteria();
        $criteria_separador = new TCriteria();

        $filterVar = "parametro";
        $criteria_dm_tipo_param->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo_param";
        $criteria_dm_tipo_param->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "parametro";
        $criteria_separador->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "separador";
        $criteria_separador->add(new TFilter('atributo', '=', $filterVar)); 

        $id = new TEntry('id');
        $codigo = new TEntry('codigo');
        $dm_tipo_param = new TDBCombo('dm_tipo_param', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo_param );
        $nome = new TEntry('nome');
        $descricao = new TText('descricao');
        $dominio_id = new TDBCombo('dominio_id', 'databaserede', 'Dominio', 'id', '{codigo}','codigo asc' , $criteria_dominio_id );
        $separador = new TDBCombo('separador', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_separador );
        $valor = new TEntry('valor');

        $codigo->addValidation("Código", new TRequiredValidator()); 
        $dm_tipo_param->addValidation("Tipo", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 

        $id->setEditable(false);
        $nome->setMaxLength(100);
        $codigo->setMaxLength(45);

        $separador->enableSearch();
        $dominio_id->enableSearch();
        $dm_tipo_param->enableSearch();

        $id->setSize('100%');
        $nome->setSize('100%');
        $valor->setSize('100%');
        $codigo->setSize('100%');
        $separador->setSize('100%');
        $dominio_id->setSize('100%');
        $dm_tipo_param->setSize('100%');
        $descricao->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'),$id],[new TLabel("Código: <font color=\"red\">*</font>", null, '14px', null, '100%'),$codigo],[new TLabel("Tipo: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo_param]);
        $row1->layout = [' col-sm-2','col-sm-6',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Nome: <font color=\"red\">*</font>", null, '14px', null, '100%'),$nome]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Descrição:", null, '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Domínio:", null, '14px', null, '100%'),$dominio_id],[new TLabel("Separador:", null, '14px', null, '100%'),$separador],[new TLabel("Valor:", null, '14px', null, '100%'),$valor]);
        $row4->layout = [' col-sm-4',' col-sm-2',' col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ParametroList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Parâmetros","Cadastro de parâmetro"]));
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

            $object = new Parametro(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if (in_array($object->dm_tipo_param, array('DM', 'LDM')) && empty($object->dominio_id)) 
                ExpService::mostrar(18); /* Para parâmetros do tipo Domínio ou Lista domínio é necessário informar o domínio! */

            if (in_array($object->dm_tipo_param, array('L', 'LDM')) && empty($object->separador)) 
                ExpService::mostrar(19); /* Para parâmetros do tipo Lista ou Lista domínio é necessário informar o separador! */

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            // Limpar sessão
            ParamService::eliminaSessao($object->codigo);

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ParametroList', 'onShow', $loadPageParam); 

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

                $object = new Parametro($key); // instantiates the Active Record 

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

