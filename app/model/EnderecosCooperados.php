<?php

class EnderecosCooperados extends TRecord
{
    const TABLENAME  = 'enderecos_cooperados';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Cooperados $cooperados;
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
        parent::addAttribute('cidades_id');
        parent::addAttribute('bairro');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('tipos_enderecos_id');
        parent::addAttribute('iss');
        parent::addAttribute('im');
        parent::addAttribute('cnes');
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

