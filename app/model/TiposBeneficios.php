<?php

class TiposBeneficios extends TRecord
{
    const TABLENAME  = 'tipos_beneficios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    const PVC = '1';
    const VA = '2';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('tipo_beneficio');
            
    }

    /**
     * Method getBeneficioss
     */
    public function getBeneficioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_beneficio_id', '=', $this->id));
        return Beneficios::getObjects( $criteria );
    }

    public function set_beneficios_tipo_beneficio_to_string($beneficios_tipo_beneficio_to_string)
    {
        if(is_array($beneficios_tipo_beneficio_to_string))
        {
            $values = TiposBeneficios::where('id', 'in', $beneficios_tipo_beneficio_to_string)->getIndexedArray('tipo_beneficio', 'tipo_beneficio');
            $this->beneficios_tipo_beneficio_to_string = implode(', ', $values);
        }
        else
        {
            $this->beneficios_tipo_beneficio_to_string = $beneficios_tipo_beneficio_to_string;
        }

        $this->vdata['beneficios_tipo_beneficio_to_string'] = $this->beneficios_tipo_beneficio_to_string;
    }

    public function get_beneficios_tipo_beneficio_to_string()
    {
        if(!empty($this->beneficios_tipo_beneficio_to_string))
        {
            return $this->beneficios_tipo_beneficio_to_string;
        }
    
        $values = Beneficios::where('tipo_beneficio_id', '=', $this->id)->getIndexedArray('tipo_beneficio_id','{tipo_beneficio->tipo_beneficio}');
        return implode(', ', $values);
    }

    public function set_beneficios_cooperados_to_string($beneficios_cooperados_to_string)
    {
        if(is_array($beneficios_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $beneficios_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->beneficios_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->beneficios_cooperados_to_string = $beneficios_cooperados_to_string;
        }

        $this->vdata['beneficios_cooperados_to_string'] = $this->beneficios_cooperados_to_string;
    }

    public function get_beneficios_cooperados_to_string()
    {
        if(!empty($this->beneficios_cooperados_to_string))
        {
            return $this->beneficios_cooperados_to_string;
        }
    
        $values = Beneficios::where('tipo_beneficio_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    
}

