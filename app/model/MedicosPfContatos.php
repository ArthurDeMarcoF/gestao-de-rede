<?php

class MedicosPfContatos extends TRecord
{
    const TABLENAME  = 'medicos_pf_contatos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MedicosPf $medicos_pf;
    private TiposContatos $tipos_contatos;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('contato');
        parent::addAttribute('imprime_guia_medico');
        parent::addAttribute('medicos_pf_id');
        parent::addAttribute('tipos_contatos_id');
            
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
    /**
     * Method set_tipos_contatos
     * Sample of usage: $var->tipos_contatos = $object;
     * @param $object Instance of TiposContatos
     */
    public function set_tipos_contatos(TiposContatos $object)
    {
        $this->tipos_contatos = $object;
        $this->tipos_contatos_id = $object->id;
    }

    /**
     * Method get_tipos_contatos
     * Sample of usage: $var->tipos_contatos->attribute;
     * @returns TiposContatos instance
     */
    public function get_tipos_contatos()
    {
    
        // loads the associated object
        if (empty($this->tipos_contatos))
            $this->tipos_contatos = new TiposContatos($this->tipos_contatos_id);
    
        // returns the associated object
        return $this->tipos_contatos;
    }

    
}

