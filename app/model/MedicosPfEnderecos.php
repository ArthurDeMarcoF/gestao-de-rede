<?php

class MedicosPfEnderecos extends TRecord
{
    const TABLENAME  = 'medicos_pf_enderecos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MedicosPf $medicos_pf;
    private TiposEnderecos $tipos_enderecos;
    private Cidades $cidades;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cep');
        parent::addAttribute('endereco');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('iss');
        parent::addAttribute('im');
        parent::addAttribute('cnes');
        parent::addAttribute('medicos_pf_id');
        parent::addAttribute('imprime_guia_medico');
        parent::addAttribute('tipos_enderecos_id');
        parent::addAttribute('cidades_id');
            
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
     * Method set_tipos_enderecos
     * Sample of usage: $var->tipos_enderecos = $object;
     * @param $object Instance of TiposEnderecos
     */
    public function set_tipos_enderecos(TiposEnderecos $object)
    {
        $this->tipos_enderecos = $object;
        $this->tipos_enderecos_id = $object->id;
    }

    /**
     * Method get_tipos_enderecos
     * Sample of usage: $var->tipos_enderecos->attribute;
     * @returns TiposEnderecos instance
     */
    public function get_tipos_enderecos()
    {
    
        // loads the associated object
        if (empty($this->tipos_enderecos))
            $this->tipos_enderecos = new TiposEnderecos($this->tipos_enderecos_id);
    
        // returns the associated object
        return $this->tipos_enderecos;
    }
    /**
     * Method set_cidades
     * Sample of usage: $var->cidades = $object;
     * @param $object Instance of Cidades
     */
    public function set_cidades(Cidades $object)
    {
        $this->cidades = $object;
        $this->cidades_id = $object->id;
    }

    /**
     * Method get_cidades
     * Sample of usage: $var->cidades->attribute;
     * @returns Cidades instance
     */
    public function get_cidades()
    {
    
        // loads the associated object
        if (empty($this->cidades))
            $this->cidades = new Cidades($this->cidades_id);
    
        // returns the associated object
        return $this->cidades;
    }

    
}

