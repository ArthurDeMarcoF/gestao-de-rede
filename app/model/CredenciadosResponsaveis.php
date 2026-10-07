<?php

class CredenciadosResponsaveis extends TRecord
{
    const TABLENAME  = 'credenciados_responsaveis';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;
    private CategoriaResponsavel $categoria_responsavel;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('credenciados_id');
        parent::addAttribute('categoria_responsavel_id');
        parent::addAttribute('nome');
        parent::addAttribute('cooperado');
            
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
     * Method set_categoria_responsavel
     * Sample of usage: $var->categoria_responsavel = $object;
     * @param $object Instance of CategoriaResponsavel
     */
    public function set_categoria_responsavel(CategoriaResponsavel $object)
    {
        $this->categoria_responsavel = $object;
        $this->categoria_responsavel_id = $object->id;
    }

    /**
     * Method get_categoria_responsavel
     * Sample of usage: $var->categoria_responsavel->attribute;
     * @returns CategoriaResponsavel instance
     */
    public function get_categoria_responsavel()
    {
    
        // loads the associated object
        if (empty($this->categoria_responsavel))
            $this->categoria_responsavel = new CategoriaResponsavel($this->categoria_responsavel_id);
    
        // returns the associated object
        return $this->categoria_responsavel;
    }

    
}

