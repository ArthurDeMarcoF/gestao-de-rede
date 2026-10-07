<?php

class TiposContatos extends TRecord
{
    const TABLENAME  = 'tipos_contatos';
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
        parent::addAttribute('tipo_contato');
            
    }

    /**
     * Method getCooperadosContatoss
     */
    public function getCooperadosContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_contatos_id', '=', $this->id));
        return CooperadosContatos::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosContatoss
     */
    public function getCredenciadosContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_contatos_id', '=', $this->id));
        return CredenciadosContatos::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfContatoss
     */
    public function getMedicosPfContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_contatos_id', '=', $this->id));
        return MedicosPfContatos::getObjects( $criteria );
    }

    public function set_cooperados_contatos_cooperados_to_string($cooperados_contatos_cooperados_to_string)
    {
        if(is_array($cooperados_contatos_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_contatos_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_contatos_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_contatos_cooperados_to_string = $cooperados_contatos_cooperados_to_string;
        }

        $this->vdata['cooperados_contatos_cooperados_to_string'] = $this->cooperados_contatos_cooperados_to_string;
    }

    public function get_cooperados_contatos_cooperados_to_string()
    {
        if(!empty($this->cooperados_contatos_cooperados_to_string))
        {
            return $this->cooperados_contatos_cooperados_to_string;
        }
    
        $values = CooperadosContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_contatos_tipos_contatos_to_string($cooperados_contatos_tipos_contatos_to_string)
    {
        if(is_array($cooperados_contatos_tipos_contatos_to_string))
        {
            $values = TiposContatos::where('id', 'in', $cooperados_contatos_tipos_contatos_to_string)->getIndexedArray('tipo_contato', 'tipo_contato');
            $this->cooperados_contatos_tipos_contatos_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_contatos_tipos_contatos_to_string = $cooperados_contatos_tipos_contatos_to_string;
        }

        $this->vdata['cooperados_contatos_tipos_contatos_to_string'] = $this->cooperados_contatos_tipos_contatos_to_string;
    }

    public function get_cooperados_contatos_tipos_contatos_to_string()
    {
        if(!empty($this->cooperados_contatos_tipos_contatos_to_string))
        {
            return $this->cooperados_contatos_tipos_contatos_to_string;
        }
    
        $values = CooperadosContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
        return implode(', ', $values);
    }

    public function set_credenciados_contatos_credenciados_to_string($credenciados_contatos_credenciados_to_string)
    {
        if(is_array($credenciados_contatos_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_contatos_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_contatos_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_contatos_credenciados_to_string = $credenciados_contatos_credenciados_to_string;
        }

        $this->vdata['credenciados_contatos_credenciados_to_string'] = $this->credenciados_contatos_credenciados_to_string;
    }

    public function get_credenciados_contatos_credenciados_to_string()
    {
        if(!empty($this->credenciados_contatos_credenciados_to_string))
        {
            return $this->credenciados_contatos_credenciados_to_string;
        }
    
        $values = CredenciadosContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_contatos_tipos_contatos_to_string($credenciados_contatos_tipos_contatos_to_string)
    {
        if(is_array($credenciados_contatos_tipos_contatos_to_string))
        {
            $values = TiposContatos::where('id', 'in', $credenciados_contatos_tipos_contatos_to_string)->getIndexedArray('tipo_contato', 'tipo_contato');
            $this->credenciados_contatos_tipos_contatos_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_contatos_tipos_contatos_to_string = $credenciados_contatos_tipos_contatos_to_string;
        }

        $this->vdata['credenciados_contatos_tipos_contatos_to_string'] = $this->credenciados_contatos_tipos_contatos_to_string;
    }

    public function get_credenciados_contatos_tipos_contatos_to_string()
    {
        if(!empty($this->credenciados_contatos_tipos_contatos_to_string))
        {
            return $this->credenciados_contatos_tipos_contatos_to_string;
        }
    
        $values = CredenciadosContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_contatos_medicos_pf_to_string($medicos_pf_contatos_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_contatos_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_contatos_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_contatos_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_contatos_medicos_pf_to_string = $medicos_pf_contatos_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_contatos_medicos_pf_to_string'] = $this->medicos_pf_contatos_medicos_pf_to_string;
    }

    public function get_medicos_pf_contatos_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_contatos_medicos_pf_to_string))
        {
            return $this->medicos_pf_contatos_medicos_pf_to_string;
        }
    
        $values = MedicosPfContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_contatos_tipos_contatos_to_string($medicos_pf_contatos_tipos_contatos_to_string)
    {
        if(is_array($medicos_pf_contatos_tipos_contatos_to_string))
        {
            $values = TiposContatos::where('id', 'in', $medicos_pf_contatos_tipos_contatos_to_string)->getIndexedArray('tipo_contato', 'tipo_contato');
            $this->medicos_pf_contatos_tipos_contatos_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_contatos_tipos_contatos_to_string = $medicos_pf_contatos_tipos_contatos_to_string;
        }

        $this->vdata['medicos_pf_contatos_tipos_contatos_to_string'] = $this->medicos_pf_contatos_tipos_contatos_to_string;
    }

    public function get_medicos_pf_contatos_tipos_contatos_to_string()
    {
        if(!empty($this->medicos_pf_contatos_tipos_contatos_to_string))
        {
            return $this->medicos_pf_contatos_tipos_contatos_to_string;
        }
    
        $values = MedicosPfContatos::where('tipos_contatos_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
        return implode(', ', $values);
    }

    
}

