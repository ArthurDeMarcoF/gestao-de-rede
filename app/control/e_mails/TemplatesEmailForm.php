<?php

class TemplatesEmailForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'databaserede';
    private static $activeRecord = 'TemplatesEmail';
    private static $primaryKey = 'id';
    private static $formName = 'form_TemplatesEmailForm';

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
        $this->form->setFormTitle("Cadastro de templates email");


        $id = new TEntry('id');
        $codigo = new TEntry('codigo');
        $expr_assunto_id = new TSeekButton('expr_assunto_id');
        $desc_exp = new TEntry('desc_exp');
        $nome = new TEntry('nome');
        $corpo = new THtmlEditor('corpo');
        $element_67322b5da8622 = new BElement('a');


        $id->setEditable(false);
        $desc_exp->setEditable(false);

        $nome->setMaxLength(100);
        $codigo->setMaxLength(45);

        $id->setSize('100%');
        $nome->setSize('100%');
        $codigo->setSize('100%');
        $desc_exp->setSize('75%');
        $corpo->setSize('100%', 350);
        $expr_assunto_id->setSize('20%');
        $element_67322b5da8622->setSize('100%', 80);

        $this->element_67322b5da8622 = $element_67322b5da8622;

        $seed = AdiantiApplicationConfig::get()['general']['seed'];
        $expr_assunto_id_seekAction = new TAction(['ExpressaoSeekWindow', 'onShow']);
        $seekFilters = [];
        $seekFields = base64_encode(serialize([
            ['name'=> 'expr_assunto_id', 'column'=>'{id}'],
            ['name'=> 'expr_assunto_id', 'column'=>'{id}'],
            ['name'=> 'desc_exp', 'column'=>'{expressao}']
        ]));

        $seekFilters = base64_encode(serialize($seekFilters));
        $expr_assunto_id_seekAction->setParameter('_seek_fields', $seekFields);
        $expr_assunto_id_seekAction->setParameter('_seek_filters', $seekFilters);
        $expr_assunto_id_seekAction->setParameter('_seek_hash', md5($seed.$seekFields.$seekFilters));
        $expr_assunto_id->setAction($expr_assunto_id_seekAction);

        $aux_replace = [];
        $aux_replace['table_lables'] = self::montarLabels();

        $template = new THtmlRenderer('app/resources/informacoes_template_email.html');
        $template->disableHtmlConversion();
        $template->enableSection('main', $aux_replace);
        $element_67322b5da8622 = $template;

        $this->form->appendPage("Template");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("ID", null, '14px', null, '100%'),$id],[new TLabel("Código", null, '14px', null, '100%'),$codigo],[new TLabel("Expressão assunto", null, '14px', null, '100%'),$expr_assunto_id,$desc_exp]);
        $row1->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row2 = $this->form->addFields([new TLabel("Nome", null, '14px', null, '100%'),$nome]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([$corpo]);
        $row3->layout = [' col-sm-12'];

        $this->form->appendPage("Labels");
        $row4 = $this->form->addFields([$element_67322b5da8622]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:plus #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-success'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['TemplatesEmailList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["e-Mails","Cadastro de templates email"]));
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

            $object = new TemplatesEmail(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $this->fireEvents($object);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('TemplatesEmailList', 'onShow', $loadPageParam); 

        }
        catch (Exception $e) // in case of exception
        {

            $this->fireEvents($this->form->getData()); 

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

                $object = new TemplatesEmail($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->expr_assunto_id))
            {
                $value = $object->expr_assunto_id;

                $obj->expr_assunto_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->expr_assunto_id))
            {
                $value = $object->expr_assunto_id;

                $obj->expr_assunto_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public static function getFormName()
    {
        return self::$formName;
    }

    public static function montarLabels() {

        $aux_table =            '<table>'.chr(13);
        $aux_table = $aux_table.'   <thead>'.chr(13);
        $aux_table = $aux_table.'       <tr>'.chr(13);
        $aux_table = $aux_table.'           <th>Label</th>'.chr(13);
        $aux_table = $aux_table.'           <th>Descrição</th>'.chr(13);
        $aux_table = $aux_table.'           <th>Tipo</th>'.chr(13);
        $aux_table = $aux_table.'           <th>Chave</th>'.chr(13);
        $aux_table = $aux_table.'       </tr>'.chr(13);
        $aux_table = $aux_table.'   </thead>'.chr(13);
        $aux_table = $aux_table.'   <tbody>'.chr(13);

        TTransaction::open(self::$database);

        $aux_labels = LabelEmail::where('id', '<>', 0)
                                ->orderBy('label')
                                ->load();

        foreach ($aux_labels as $aux_label) {
            $aux_tipo  = DMService::obterMaskCol('label_email', 'dm_tipo', $aux_label->dm_tipo);

            $aux_table = $aux_table.'       <tr>'.chr(13);
            $aux_table = $aux_table.'           <td style="padding: 4px;">{$'.$aux_label->label.'}</td>'.chr(13);
            $aux_table = $aux_table.'           <td style="padding: 4px;">'.$aux_label->descricao.'</td>'.chr(13);
            $aux_table = $aux_table.'           <td style="padding: 4px;">'.$aux_tipo.'</td>'.chr(13);
            $aux_table = $aux_table.'           <td style="padding: 4px;">'.$aux_label->chave.'</td>'.chr(13);
            $aux_table = $aux_table.'       </tr>'.chr(13);
        }
        TTransaction::close();

        $aux_table = $aux_table.'   </tbody>'.chr(13);
        $aux_table = $aux_table.'</table>'.chr(13);

        return $aux_table;
    }

}

