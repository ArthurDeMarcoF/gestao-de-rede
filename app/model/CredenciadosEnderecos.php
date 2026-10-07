<?php

class CredenciadosEnderecos extends TRecord
{
    const TABLENAME  = 'credenciados_enderecos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;
    private Cidades $cidades;
    private TiposEnderecos $tipos_enderecos;

    

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
        parent::addAttribute('cnes');
        parent::addAttribute('inscricao_municipal');
        parent::addAttribute('credenciados_id');
        parent::addAttribute('cidades_id');
        parent::addAttribute('tipos_enderecos_id');
            
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

    
}

