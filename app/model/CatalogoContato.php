<?php

class CatalogoContato extends TRecord
{
    const TABLENAME  = 'catalogo_contato';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Catalogo $catalogo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('catalogo_id');
        parent::addAttribute('nome');
        parent::addAttribute('contato');
            
    }

    /**
     * Method set_catalogo
     * Sample of usage: $var->catalogo = $object;
     * @param $object Instance of Catalogo
     */
    public function set_catalogo(Catalogo $object)
    {
        $this->catalogo = $object;
        $this->catalogo_id = $object->id;
    }

    /**
     * Method get_catalogo
     * Sample of usage: $var->catalogo->attribute;
     * @returns Catalogo instance
     */
    public function get_catalogo()
    {
    
        // loads the associated object
        if (empty($this->catalogo))
            $this->catalogo = new Catalogo($this->catalogo_id);
    
        // returns the associated object
        return $this->catalogo;
    }

    
}

