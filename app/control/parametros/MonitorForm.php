<?php

class MonitorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_MonitorForm';

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
        $this->form->setFormTitle("Monitor");


        $button_iniciar = new TButton('button_iniciar');
        $button_reiniciar = new TButton('button_reiniciar');
        $button_parar = new TButton('button_parar');
        $button_iniciar1 = new TButton('button_iniciar1');
        $button_reiniciar1 = new TButton('button_reiniciar1');
        $button_parar1 = new TButton('button_parar1');


        $button_parar->setAction(new TAction([$this, 'onPararApi']), "Parar");
        $button_iniciar->setAction(new TAction([$this, 'onIniciarApi']), "Iniciar");
        $button_iniciar1->setAction(new TAction([$this, 'onIniciarJob']), "Iniciar");
        $button_reiniciar->setAction(new TAction([$this, 'onReiniciarApi']), "Reiniciar");
        $button_reiniciar1->setAction(new TAction([$this, 'onReiniciarjob']), "Reiniciar");
        $button_parar1->setAction(new TAction([$this, 'onPararJob'],['static' => 1]), "Parar");

        $button_parar->addStyleClass('btn-danger');
        $button_parar1->addStyleClass('btn-danger');
        $button_iniciar->addStyleClass('btn-success');
        $button_iniciar1->addStyleClass('btn-success');
        $button_reiniciar->addStyleClass('btn-warning');
        $button_reiniciar1->addStyleClass('btn-warning');

        $button_parar->setImage('fas:ban #FFFFFF');
        $button_parar1->setImage('fas:ban #FFFFFF');
        $button_reiniciar->setImage('fas:redo-alt #FFFFFF');
        $button_reiniciar1->setImage('fas:redo-alt #FFFFFF');
        $button_iniciar->setImage('fas:check-double #FFFFFF');
        $button_iniciar1->setImage('fas:check-double #FFFFFF');


        $row1 = $this->form->addFields([new TLabel("apiGestaoRede", null, '14px', null)],[new TLabel("##stsApi##", null, '14px', null)],[$button_iniciar,$button_reiniciar,$button_parar]);
        $row1->layout = [' col-sm-3 control-label','col-sm-3','col-sm-6'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row3 = $this->form->addFields([new TLabel("jobGestaoRede", null, '14px', null)],[new TLabel("##stsJob##", null, '14px', null)],[$button_iniciar1,$button_reiniciar1,$button_parar1]);
        $row3->layout = [' col-sm-3 control-label','col-sm-3','col-sm-6'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Parâmetros","Monitor"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onIniciarApi($param = null) 
    {
        try 
        {
            self::iniciar('apiGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onReiniciarApi($param = null) 
    {
        try 
        {
            self::reiniciar('apiGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onPararApi($param = null) 
    {
        try 
        {
            self::parar('apiGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onIniciarJob($param = null) 
    {
        try 
        {
            self::iniciar('jobGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onReiniciarjob($param = null) 
    {
        try 
        {
            self::reiniciar('jobGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onPararJob($param = null) 
    {
        try 
        {
            self::parar('jobGestaoRede');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

        TTransaction::open('databaserede');
        $aux_api = self::obter_se_ativo('apiGestaoRede');
        $aux_job = self::obter_se_ativo('jobGestaoRede');
        TTransaction::close();

        TScript::create("$('label:contains(\"##stsApi##\")').html('{$aux_api}')");
        TScript::create("$('label:contains(\"##stsJob##\")').html('{$aux_job}')");

    } 

    public function obter_se_ativo($prm_service) {

        exec('systemctl is-active ' . escapeshellarg($prm_service), $aux_saida, $aux_status);

        if ($aux_status === 0 && trim($aux_saida[0]) === 'active')
            return DMService::obterMask('dm_job_situacao', 'A');
        return DMService::obterMask('dm_job_situacao', 'I');

    }

    public function reiniciar($prm_service) {
        shell_exec('systemctl restart '.$prm_service.'.service');
        TApplication::loadPage('MonitorForm', 'onShow', []);
    }

    public function iniciar($prm_service) {
        shell_exec('systemctl start '.$prm_service.'.service');
        TApplication::loadPage('MonitorForm', 'onShow', []);
    }

    public function parar($prm_service) {
        exec('sudo systemctl stop '.$prm_service.'.service', $aux_saida, $aux_status);
        //var_dump($aux_saida);
        var_dump($aux_status);
        var_dump($aux_saida);
        //TApplication::loadPage('MonitorForm', 'onShow', []);
    }

}

