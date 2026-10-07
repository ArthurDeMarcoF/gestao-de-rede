<?php

class CatalogoEspecialidade extends TRecord
{
    const TABLENAME  = 'catalogo_especialidade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Especialidades $especialidades;
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
        parent::addAttribute('especialidades_id');
            
    }

    /**
     * Method set_especialidades
     * Sample of usage: $var->especialidades = $object;
     * @param $object Instance of Especialidades
     */
    public function set_especialidades(Especialidades $object)
    {
        $this->especialidades = $object;
        $this->especialidades_id = $object->id;
    }

    /**
     * Method get_especialidades
     * Sample of usage: $var->especialidades->attribute;
     * @returns Especialidades instance
     */
    public function get_especialidades()
    {
    
        // loads the associated object
        if (empty($this->especialidades))
            $this->especialidades = new Especialidades($this->especialidades_id);
    
        // returns the associated object
        return $this->especialidades;
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

