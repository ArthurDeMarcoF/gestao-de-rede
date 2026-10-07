<?php

class MedicosPfEspecialidades extends TRecord
{
    const TABLENAME  = 'medicos_pf_especialidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Especialidades $especialidades;
    private MedicosPf $medicos_pf;

    

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
        parent::addAttribute('especialidades_id');
        parent::addAttribute('medicos_pf_id');
            
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
     * Method set_medicos_pf
     * Sample of usage: $var->medicos_pf = $object;
     * @param $object Instance of MedicosPf
     */
    public function set_medicos_pf(MedicosPf $object)
    {
        $this->medicos_pf = $object;
        $this->medicos_pf_id = $object->id;
    }

    /**
     * Method get_medicos_pf
     * Sample of usage: $var->medicos_pf->attribute;
     * @returns MedicosPf instance
     */
    public function get_medicos_pf()
    {
    
        // loads the associated object
        if (empty($this->medicos_pf))
            $this->medicos_pf = new MedicosPf($this->medicos_pf_id);
    
        // returns the associated object
        return $this->medicos_pf;
    }

    
}

