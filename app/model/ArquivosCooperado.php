<?php

class ArquivosCooperado extends TRecord
{
    const TABLENAME  = 'arquivos_cooperado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private ArquivosStatus $arquivos_status;
    private Cooperados $cooperado;
    private Arquivos $arquivos;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('arquivos_status_id');
        parent::addAttribute('cooperado_id');
        parent::addAttribute('arquivos_id');
            
    }

    /**
     * Method set_arquivos_status
     * Sample of usage: $var->arquivos_status = $object;
     * @param $object Instance of ArquivosStatus
     */
    public function set_arquivos_status(ArquivosStatus $object)
    {
        $this->arquivos_status = $object;
        $this->arquivos_status_id = $object->id;
    }

    /**
     * Method get_arquivos_status
     * Sample of usage: $var->arquivos_status->attribute;
     * @returns ArquivosStatus instance
     */
    public function get_arquivos_status()
    {
    
        // loads the associated object
        if (empty($this->arquivos_status))
            $this->arquivos_status = new ArquivosStatus($this->arquivos_status_id);
    
        // returns the associated object
        return $this->arquivos_status;
    }
    /**
     * Method set_cooperados
     * Sample of usage: $var->cooperados = $object;
     * @param $object Instance of Cooperados
     */
    public function set_cooperado(Cooperados $object)
    {
        $this->cooperado = $object;
        $this->cooperado_id = $object->id;
    }

    /**
     * Method get_cooperado
     * Sample of usage: $var->cooperado->attribute;
     * @returns Cooperados instance
     */
    public function get_cooperado()
    {
    
        // loads the associated object
        if (empty($this->cooperado))
            $this->cooperado = new Cooperados($this->cooperado_id);
    
        // returns the associated object
        return $this->cooperado;
    }
    /**
     * Method set_arquivos
     * Sample of usage: $var->arquivos = $object;
     * @param $object Instance of Arquivos
     */
    public function set_arquivos(Arquivos $object)
    {
        $this->arquivos = $object;
        $this->arquivos_id = $object->id;
    }

    /**
     * Method get_arquivos
     * Sample of usage: $var->arquivos->attribute;
     * @returns Arquivos instance
     */
    public function get_arquivos()
    {
    
        // loads the associated object
        if (empty($this->arquivos))
            $this->arquivos = new Arquivos($this->arquivos_id);
    
        // returns the associated object
        return $this->arquivos;
    }

    
}

