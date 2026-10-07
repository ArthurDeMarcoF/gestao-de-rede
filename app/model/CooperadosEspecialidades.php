<?php

class CooperadosEspecialidades extends TRecord
{
    const TABLENAME  = 'cooperados_especialidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Cooperados $cooperados;
    private Especialidades $especialidades;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('especialidades_id');
        parent::addAttribute('rqe');
        parent::addAttribute('imprime_guia_medico');
            
    }

    /**
     * Method set_cooperados
     * Sample of usage: $var->cooperados = $object;
     * @param $object Instance of Cooperados
     */
    public function set_cooperados(Cooperados $object)
    {
        $this->cooperados = $object;
        $this->cooperados_id = $object->id;
    }

    /**
     * Method get_cooperados
     * Sample of usage: $var->cooperados->attribute;
     * @returns Cooperados instance
     */
    public function get_cooperados()
    {
    
        // loads the associated object
        if (empty($this->cooperados))
            $this->cooperados = new Cooperados($this->cooperados_id);
    
        // returns the associated object
        return $this->cooperados;
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

    
}

