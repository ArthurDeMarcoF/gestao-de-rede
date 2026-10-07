<?php

class EnvioDocsMassaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_EnvioDocsMassaForm';

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
        $this->form->setFormTitle("Envio documentos em massa");

        $criteria_tipo_doc = new TCriteria();
        $criteria_tipo_contato = new TCriteria();
        $criteria_template = new TCriteria();

        $tipo_doc = new TDBCombo('tipo_doc', 'databaserede', 'TiposDocumentacoes', 'id', '{tipo_documento}','tipo_documento asc' , $criteria_tipo_doc );
        $doc = new TCombo('doc');
        $doc1 = new TCombo('doc1');
        $tipo_contato = new TDBCombo('tipo_contato', 'databaserede', 'TiposContatos', 'id', '{tipo_contato}','tipo_contato asc' , $criteria_tipo_contato );
        $template = new TDBCombo('template', 'databaserede', 'TemplatesEmail', 'codigo', '{nome}','nome asc' , $criteria_template );

        $tipo_doc->setChangeAction(new TAction([$this,'onChangetipo_doc']));

        $tipo_doc->addValidation("Tipo do documento", new TRequiredValidator()); 
        $doc->addValidation("Documento", new TRequiredValidator()); 
        $doc1->addValidation("Documento", new TRequiredValidator()); 
        $tipo_contato->addValidation("Tipo do contato", new TRequiredValidator()); 
        $template->addValidation("Template", new TRequiredValidator()); 

        $doc->setSize('100%');
        $doc1->setSize('100%');
        $tipo_doc->setSize('100%');
        $template->setSize('100%');
        $tipo_contato->setSize('100%');

        $doc->enableSearch();
        $doc1->enableSearch();
        $tipo_doc->enableSearch();
        $template->enableSearch();
        $tipo_contato->enableSearch();


        $row1 = $this->form->addFields([new TLabel("Tipo do documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipo_doc]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Documento: <font color=\"red\">*</font>", null, '14px', null, '100%'),$doc],[new TLabel("Documento1: <font color=\"red\">*</font>", null, '14px', null, '100%'),$doc1]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Tipo contato: <font color=\"red\">*</font>", null, '14px', null, '100%'),$tipo_contato],[new TLabel("Template: <font color=\"red\">*</font>", null, '14px', null, '100%'),$template]);
        $row3->layout = [' col-sm-6','col-sm-6'];

        // create the form actions
        $btn_ongerar = $this->form->addAction("Gerar", new TAction([$this, 'onGerar'],['static' => 1]), 'fas:cogs #ffffff');
        $this->btn_ongerar = $btn_ongerar;
        $btn_ongerar->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ferramentas","Envio documentos em massa"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangetipo_doc($param)
    {
        try
        {

            if (isset($param['tipo_doc']) && $param['tipo_doc'])
            { 
                $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipo_doc']]);
                TDBCombo::reloadFromModel(self::$formName, 'doc', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'doc'); 
            }  

            if (isset($param['tipo_doc']) && $param['tipo_doc'])
            { 
                $criteria = TCriteria::create(['tipos_documentacoes_id' => $param['tipo_doc']]);
                TDBCombo::reloadFromModel(self::$formName, 'doc1', 'databaserede', 'Documentacoes', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'doc1'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onGerar($param = null) 
    {
        try
        {

            $this->form->validate();

            $aux_prm = ['tipo_doc' => $param['tipo_doc'],
                        'doc'      => $param['doc'],
                        'doc1'     => $param['doc1'],
                        'cnt'      => $param['tipo_contato'],
                        'template' => $param['template']];

            JobService::criarJobUnico('Envio de documentos em massa (SYS)', apiService::urlEnvioMassDoc, $aux_prm);

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipo_doc))
            {
                $value = $object->tipo_doc;

                $obj->tipo_doc = $value;
            }
            if(isset($object->doc))
            {
                $value = $object->doc;

                $obj->doc = $value;
            }
            if(isset($object->doc1))
            {
                $value = $object->doc1;

                $obj->doc1 = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipo_doc))
            {
                $value = $object->tipo_doc;

                $obj->tipo_doc = $value;
            }
            if(isset($object->doc))
            {
                $value = $object->doc;

                $obj->doc = $value;
            }
            if(isset($object->doc1))
            {
                $value = $object->doc1;

                $obj->doc1 = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

}

