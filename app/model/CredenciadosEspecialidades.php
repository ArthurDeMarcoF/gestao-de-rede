<?php

class CredenciadosEspecialidades extends TRecord
{
    const TABLENAME  = 'credenciados_especialidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;
    private Especialidades $especialidades;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('rqe');
        parent::addAttribute('imprime_guia_medico');
        parent::addAttribute('credenciados_id');
        parent::addAttribute('especialidades_id');
            
    }

    /**
     * Method set_credenciados
     * Sample of usage: $var->credenciados = $object;
     * @param $object Instance of Credenciados
     */
    public function set_credenciados(Credenciados $object)
    {
        $this->credenciados = $object;
        $this->credenciados_id = $object->id;
    }

    /**
     * Method get_credenciados
     * Sample of usage: $var->credenciados->attribute;
     * @returns Credenciados instance
     */
    public function get_credenciados()
    {
    
        // loads the associated object
        if (empty($this->credenciados))
            $this->credenciados = new Credenciados($this->credenciados_id);
    
        // returns the associated object
        return $this->credenciados;
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

