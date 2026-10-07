<?php

class CooperadosBeneficiariosDep extends TRecord
{
    const TABLENAME  = 'cooperados_beneficiarios_dep';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private CooperadosBeneficiarios $cooperados_beneficiarios;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cooperados_beneficiarios_id');
        parent::addAttribute('nome');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('codigo');
            
    }

    /**
     * Method set_cooperados_beneficiarios
     * Sample of usage: $var->cooperados_beneficiarios = $object;
     * @param $object Instance of CooperadosBeneficiarios
     */
    public function set_cooperados_beneficiarios(CooperadosBeneficiarios $object)
    {
        $this->cooperados_beneficiarios = $object;
        $this->cooperados_beneficiarios_id = $object->id;
    }

    /**
     * Method get_cooperados_beneficiarios
     * Sample of usage: $var->cooperados_beneficiarios->attribute;
     * @returns CooperadosBeneficiarios instance
     */
    public function get_cooperados_beneficiarios()
    {
    
        // loads the associated object
        if (empty($this->cooperados_beneficiarios))
            $this->cooperados_beneficiarios = new CooperadosBeneficiarios($this->cooperados_beneficiarios_id);
    
        // returns the associated object
        return $this->cooperados_beneficiarios;
    }

    
}

