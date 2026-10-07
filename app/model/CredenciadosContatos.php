<?php

class CredenciadosContatos extends TRecord
{
    const TABLENAME  = 'credenciados_contatos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;
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
        parent::addAttribute('credenciados_id');
        parent::addAttribute('tipos_contatos_id');
            
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

