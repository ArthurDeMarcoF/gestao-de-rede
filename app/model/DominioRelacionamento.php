<?php

class DominioRelacionamento extends TRecord
{
    const TABLENAME  = 'dominio_relacionamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Dominio $dominio;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('dominio_id');
        parent::addAttribute('objeto');
        parent::addAttribute('atributo');
        parent::addAttribute('valor_padrao');
        parent::addAttribute('flg_colorir');
            
    }

    /**
     * Method set_dominio
     * Sample of usage: $var->dominio = $object;
     * @param $object Instance of Dominio
     */
    public function set_dominio(Dominio $object)
    {
        $this->dominio = $object;
        $this->dominio_id = $object->id;
    }

    /**
     * Method get_dominio
     * Sample of usage: $var->dominio->attribute;
     * @returns Dominio instance
     */
    public function get_dominio()
    {
    
        // loads the associated object
        if (empty($this->dominio))
            $this->dominio = new Dominio($this->dominio_id);
    
        // returns the associated object
        return $this->dominio;
    }

    
}

