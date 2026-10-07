<?php

class TemplatesEmail extends TRecord
{
    const TABLENAME  = 'templates_email';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Expressao $expr_assunto;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('codigo');
        parent::addAttribute('nome');
        parent::addAttribute('corpo');
        parent::addAttribute('expr_assunto_id');
            
    }

    /**
     * Method set_expressao
     * Sample of usage: $var->expressao = $object;
     * @param $object Instance of Expressao
     */
    public function set_expr_assunto(Expressao $object)
    {
        $this->expr_assunto = $object;
        $this->expr_assunto_id = $object->id;
    }

    /**
     * Method get_expr_assunto
     * Sample of usage: $var->expr_assunto->attribute;
     * @returns Expressao instance
     */
    public function get_expr_assunto()
    {
    
        // loads the associated object
        if (empty($this->expr_assunto))
            $this->expr_assunto = new Expressao($this->expr_assunto_id);
    
        // returns the associated object
        return $this->expr_assunto;
    }

    
}

