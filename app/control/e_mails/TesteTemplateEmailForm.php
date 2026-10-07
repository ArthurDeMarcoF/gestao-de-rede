<?php

class TesteTemplateEmailForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_TesteTemplateEmailForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Teste de template");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Teste de template");

        $criteria_template = new TCriteria();

        $template = new TDBCombo('template', 'databaserede', 'TemplatesEmail', 'codigo', '{nome}','nome asc' , $criteria_template );
        $chave = new TEntry('chave');
        $button_gerar_template = new TButton('button_gerar_template');
        $destinatario = new TEntry('destinatario');
        $assunto = new TEntry('assunto');
        $corpo = new THtmlEditor('corpo');


        $template->enableSearch();
        $button_gerar_template->setAction(new TAction([$this, 'onGerarTemplate'],['static' => 1]), "Gerar template");
        $button_gerar_template->addStyleClass('btn-default');
        $button_gerar_template->setImage('fas:cogs #673AB7');
        $corpo->setEditable(false);
        $chave->setSize('100%');
        $assunto->setSize('100%');
        $template->setSize('100%');
        $corpo->setSize('100%', 300);
        $destinatario->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Template:", null, '14px', null, '100%'),$template],[new TLabel("Chave:", null, '14px', null, '100%'),$chave],[new TLabel(" ", null, '14px', null, '100%'),$button_gerar_template]);
        $row1->layout = [' col-sm-7','col-sm-3','col-sm-2'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row3 = $this->form->addFields([new TLabel("Destinatário:", null, '14px', null, '100%'),$destinatario]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Assunto:", null, '14px', null, '100%'),$assunto]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([new TLabel("Corpo:", null, '14px', null, '100%'),$corpo]);
        $row5->layout = [' col-sm-12'];

        // create the form actions
        $btn_onenviar = $this->form->addAction("Enviar", new TAction([$this, 'onEnviar'],['static' => 1]), 'fas:cloud-upload-alt #ffffff');
        $this->btn_onenviar = $btn_onenviar;
        $btn_onenviar->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public  function onGerarTemplate($param = null) 
    {
        try 
        {

            $aux_template = $param['template'];
            $aux_chave    = $param['chave'];

            if (empty($aux_template) || empty($aux_chave)) {
                ExpService::mostrar(28); // Excpt - O template e a chave devem ser informados!
            }

            $aux_html = EmailsService::montarTemplate($aux_template, $aux_chave);
            if ($aux_html) {
                $object = new stdClass();
                $object->corpo   = $aux_html;
                $object->assunto = EmailsService::obterExpAssunto($aux_template);
                TForm::sendData(self::$formName, $object);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEnviar($param = null) 
    {
        try
        {

            if (empty($param['destinatario']) || empty($param['assunto']) || empty($param['corpo'])) {
                ExpService::mostrar(29); // Except - Os campos destinatario, assunto e corpo devem ser preenchidos!
            }

            $aux_json = json_encode(['destinatario' => $param['destinatario'],
                                     'assunto'      => $param['assunto'],
                                     'corpo'        => $param['corpo']]);

            $aux_resp = apiService::execReq(apiService::urlCriaEmail, $aux_json);

            if (!empty($aux_resp)) {
                new TMessage('info', $aux_resp);
            }

            TApplication::loadPage('FilaEmailList', 'onShow', []);

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

