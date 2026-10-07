<?php

class CooperadosContatos extends TRecord
{
    const TABLENAME  = 'cooperados_contatos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Cooperados $cooperados;
    private TiposContatos $tipos_contatos;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('tipos_contatos_id');
        parent::addAttribute('contato');
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

