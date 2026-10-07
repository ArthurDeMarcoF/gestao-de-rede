<?php

class Estados extends TRecord
{
    const TABLENAME  = 'estados';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('estado');
        parent::addAttribute('sigla');
        parent::addAttribute('cod_ibge');
            
    }

    /**
     * Method getCidadess
     */
    public function getCidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estados_id', '=', $this->id));
        return Cidades::getObjects( $criteria );
    }

    public function set_cidades_estados_to_string($cidades_estados_to_string)
    {
        if(is_array($cidades_estados_to_string))
        {
            $values = Estados::where('id', 'in', $cidades_estados_to_string)->getIndexedArray('estado', 'estado');
            $this->cidades_estados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cidades_estados_to_string = $cidades_estados_to_string;
        }

        $this->vdata['cidades_estados_to_string'] = $this->cidades_estados_to_string;
    }

    public function get_cidades_estados_to_string()
    {
        if(!empty($this->cidades_estados_to_string))
        {
            return $this->cidades_estados_to_string;
        }
    
        $values = Cidades::where('estados_id', '=', $this->id)->getIndexedArray('estados_id','{estados->estado}');
        return implode(', ', $values);
    }

    
}

