<?php

class CredenciadosReajusteCortinaForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'CredenciadosReajuste';
    private static $primaryKey = 'id';
    private static $formName = 'form_CredenciadosReajusteCortinaForm';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Cadastro de reajuste do credenciado");

        $criteria_dm_reaj_contr = new TCriteria();
        $criteria_dm_tipo_doc = new TCriteria();

        $filterVar = "credenciados_reajuste";
        $criteria_dm_reaj_contr->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_reaj_contr";
        $criteria_dm_reaj_contr->add(new TFilter('atributo', '=', $filterVar)); 
        $filterVar = "credenciados_reajuste";
        $criteria_dm_tipo_doc->add(new TFilter('objeto', '=', $filterVar)); 
        $filterVar = "dm_tipo_doc";
        $criteria_dm_tipo_doc->add(new TFilter('atributo', '=', $filterVar)); 

        $credenciados_id = new THidden('credenciados_id');
        $id = new THidden('id');
        $data_reajuste = new TDate('data_reajuste');
        $ultimo_indice = new TNumeric('ultimo_indice', '2', ',', '.' );
        $indice = new TNumeric('indice', '2', ',', '.' );
        $dm_reaj_contr = new TDBCombo('dm_reaj_contr', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_reaj_contr );
        $dm_tipo_doc = new TDBCombo('dm_tipo_doc', 'databaserede', 'VDominioValorCol', 'valor', '{mascara}','sequencia asc' , $criteria_dm_tipo_doc );
        $observacao = new TText('observacao');
        $path_anexo = new TFile('path_anexo');

        $data_reajuste->addValidation("Data do reajuste", new TRequiredValidator()); 
        $indice->addValidation("Índice", new TRequiredValidator()); 
        $dm_reaj_contr->addValidation("Reajuste contratual", new TRequiredValidator()); 
        $dm_tipo_doc->addValidation("Tipo do documento", new TRequiredValidator()); 

        $credenciados_id->setValue($param["credenciados_id"] ?? "");
        $data_reajuste->setMask('dd/mm/yyyy');
        $data_reajuste->setDatabaseMask('yyyy-mm-dd');
        $path_anexo->enableFileHandling();
        $dm_tipo_doc->enableSearch();
        $dm_reaj_contr->enableSearch();

        $id->setSize(200);
        $indice->setSize('100%');
        $path_anexo->setSize('100%');
        $dm_tipo_doc->setSize('100%');
        $credenciados_id->setSize(200);
        $data_reajuste->setSize('100%');
        $ultimo_indice->setSize('100%');
        $dm_reaj_contr->setSize('100%');
        $observacao->setSize('100%', 70);

        $row1 = $this->form->addFields([$credenciados_id,$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Data do reajuste: <font color=\"red\">*</font>", null, '14px', null, '100%'),$data_reajuste],[new TLabel("Último índice:", null, '14px', null, '100%'),$ultimo_indice],[new TLabel("Índice atual: <font color=\"red\">*</font>", null, '14px', null, '100%'),$indice]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Reajuste contratual: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_reaj_contr],[new TLabel("Tipo do documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$dm_tipo_doc]);
        $row3->layout = [' col-sm-6',' col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([new TLabel("Anexo:", null, '14px', null, '100%'),$path_anexo]);
        $row5->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new CredenciadosReajuste(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $path_anexo_dir = 'arquivos/docs_reajustes';  

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'path_anexo', $path_anexo_dir);
            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $aux_cred = Credenciados::find($object->credenciados_id);

            if (empty($aux_cred->dm_reaj_contr) && ParamService::valor('sn_atualizar_cad_reaj') == 'S') {
                $aux_cred->dm_reaj_contr = $object->dm_reaj_contr;
                $aux_cred->store();

                $aux_obj = new stdClass();
                $aux_obj->dm_reaj_contr = $aux_cred->dm_reaj_contr;
                TForm::sendData('form_CredenciadosForm', $aux_obj);
            }

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('CredenciadosReajusteCortinaList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

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

                $object = new CredenciadosReajuste($key); // instantiates the Active Record 

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

        self::obterUltimoIndice();

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    public static function obterUltimoIndice($param = null) {
        try {
            TTransaction::open('databaserede');
            $aux_reaj = CredenciadosReajuste::where('credenciados_id', '=', TSession::getValue('form_CredenciadoForm_Credenciado_id'))
                                            ->orderBy('data_reajuste', 'desc')
                                            ->first();

            $aux_taxa = Credenciados::find(TSession::getValue('form_CredenciadoForm_Credenciado_id'));

            TTransaction::close();

            $object = new stdClass();

            $object->dm_reaj_contr = $aux_taxa->dm_reaj_contr;

            if ($aux_reaj) 
                $object->ultimo_indice = str_replace('.', ',', $aux_reaj->indice);

            TForm::sendData(self::$formName, $object);

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

}

