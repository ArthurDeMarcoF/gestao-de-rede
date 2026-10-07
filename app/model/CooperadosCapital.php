<?php

class CooperadosCapital extends TRecord
{
    const TABLENAME  = 'cooperados_capital';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private LogImportCapital $log_import_capital;
    private Cooperados $cooperados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('dm_capital_social');
        parent::addAttribute('nr_parcela');
        parent::addAttribute('data_aquisicao');
        parent::addAttribute('valor');
        parent::addAttribute('log_import_capital_id');
            
    }

    /**
     * Method set_log_import_capital
     * Sample of usage: $var->log_import_capital = $object;
     * @param $object Instance of LogImportCapital
     */
    public function set_log_import_capital(LogImportCapital $object)
    {
        $this->log_import_capital = $object;
        $this->log_import_capital_id = $object->id;
    }

    /**
     * Method get_log_import_capital
     * Sample of usage: $var->log_import_capital->attribute;
     * @returns LogImportCapital instance
     */
    public function get_log_import_capital()
    {
    
        // loads the associated object
        if (empty($this->log_import_capital))
            $this->log_import_capital = new LogImportCapital($this->log_import_capital_id);
    
        // returns the associated object
        return $this->log_import_capital;
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

    
}

