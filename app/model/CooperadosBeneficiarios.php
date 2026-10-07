<?php

class CooperadosBeneficiarios extends TRecord
{
    const TABLENAME  = 'cooperados_beneficiarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

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
        parent::addAttribute('codigo');
        parent::addAttribute('tipo');
            
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
     * Method getCooperadosBeneficiariosDeps
     */
    public function getCooperadosBeneficiariosDeps()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_beneficiarios_id', '=', $this->id));
        return CooperadosBeneficiariosDep::getObjects( $criteria );
    }

    public function set_cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string($cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string)
    {
        if(is_array($cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string))
        {
            $values = CooperadosBeneficiarios::where('id', 'in', $cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string)->getIndexedArray('cooperados_id', 'cooperados_id');
            $this->cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string = $cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string;
        }

        $this->vdata['cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string'] = $this->cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string;
    }

    public function get_cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string()
    {
        if(!empty($this->cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string))
        {
            return $this->cooperados_beneficiarios_dep_cooperados_beneficiarios_to_string;
        }
    
        $values = CooperadosBeneficiariosDep::where('cooperados_beneficiarios_id', '=', $this->id)->getIndexedArray('cooperados_beneficiarios_id','{cooperados_beneficiarios->cooperados_id}');
        return implode(', ', $values);
    }

    
}

