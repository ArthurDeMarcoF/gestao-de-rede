<?php

class TiposEnderecos extends TRecord
{
    const TABLENAME  = 'tipos_enderecos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const Importacao = '1';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('tipo_endereco');
            
    }

    /**
     * Method getEnderecosCooperadoss
     */
    public function getEnderecosCooperadoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_enderecos_id', '=', $this->id));
        return EnderecosCooperados::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosEnderecoss
     */
    public function getCredenciadosEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_enderecos_id', '=', $this->id));
        return CredenciadosEnderecos::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfEnderecoss
     */
    public function getMedicosPfEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_enderecos_id', '=', $this->id));
        return MedicosPfEnderecos::getObjects( $criteria );
    }

    public function set_enderecos_cooperados_cidades_to_string($enderecos_cooperados_cidades_to_string)
    {
        if(is_array($enderecos_cooperados_cidades_to_string))
        {
            $values = Cidades::where('id', 'in', $enderecos_cooperados_cidades_to_string)->getIndexedArray('cidade', 'cidade');
            $this->enderecos_cooperados_cidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->enderecos_cooperados_cidades_to_string = $enderecos_cooperados_cidades_to_string;
        }

        $this->vdata['enderecos_cooperados_cidades_to_string'] = $this->enderecos_cooperados_cidades_to_string;
    }

    public function get_enderecos_cooperados_cidades_to_string()
    {
        if(!empty($this->enderecos_cooperados_cidades_to_string))
        {
            return $this->enderecos_cooperados_cidades_to_string;
        }
    
        $values = EnderecosCooperados::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

    public function set_enderecos_cooperados_cooperados_to_string($enderecos_cooperados_cooperados_to_string)
    {
        if(is_array($enderecos_cooperados_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $enderecos_cooperados_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->enderecos_cooperados_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->enderecos_cooperados_cooperados_to_string = $enderecos_cooperados_cooperados_to_string;
        }

        $this->vdata['enderecos_cooperados_cooperados_to_string'] = $this->enderecos_cooperados_cooperados_to_string;
    }

    public function get_enderecos_cooperados_cooperados_to_string()
    {
        if(!empty($this->enderecos_cooperados_cooperados_to_string))
        {
            return $this->enderecos_cooperados_cooperados_to_string;
        }
    
        $values = EnderecosCooperados::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_enderecos_cooperados_tipos_enderecos_to_string($enderecos_cooperados_tipos_enderecos_to_string)
    {
        if(is_array($enderecos_cooperados_tipos_enderecos_to_string))
        {
            $values = TiposEnderecos::where('id', 'in', $enderecos_cooperados_tipos_enderecos_to_string)->getIndexedArray('id', 'id');
            $this->enderecos_cooperados_tipos_enderecos_to_string = implode(', ', $values);
        }
        else
        {
            $this->enderecos_cooperados_tipos_enderecos_to_string = $enderecos_cooperados_tipos_enderecos_to_string;
        }

        $this->vdata['enderecos_cooperados_tipos_enderecos_to_string'] = $this->enderecos_cooperados_tipos_enderecos_to_string;
    }

    public function get_enderecos_cooperados_tipos_enderecos_to_string()
    {
        if(!empty($this->enderecos_cooperados_tipos_enderecos_to_string))
        {
            return $this->enderecos_cooperados_tipos_enderecos_to_string;
        }
    
        $values = EnderecosCooperados::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
    }

    public function set_credenciados_enderecos_credenciados_to_string($credenciados_enderecos_credenciados_to_string)
    {
        if(is_array($credenciados_enderecos_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_enderecos_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_enderecos_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_enderecos_credenciados_to_string = $credenciados_enderecos_credenciados_to_string;
        }

        $this->vdata['credenciados_enderecos_credenciados_to_string'] = $this->credenciados_enderecos_credenciados_to_string;
    }

    public function get_credenciados_enderecos_credenciados_to_string()
    {
        if(!empty($this->credenciados_enderecos_credenciados_to_string))
        {
            return $this->credenciados_enderecos_credenciados_to_string;
        }
    
        $values = CredenciadosEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_enderecos_cidades_to_string($credenciados_enderecos_cidades_to_string)
    {
        if(is_array($credenciados_enderecos_cidades_to_string))
        {
            $values = Cidades::where('id', 'in', $credenciados_enderecos_cidades_to_string)->getIndexedArray('cidade', 'cidade');
            $this->credenciados_enderecos_cidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_enderecos_cidades_to_string = $credenciados_enderecos_cidades_to_string;
        }

        $this->vdata['credenciados_enderecos_cidades_to_string'] = $this->credenciados_enderecos_cidades_to_string;
    }

    public function get_credenciados_enderecos_cidades_to_string()
    {
        if(!empty($this->credenciados_enderecos_cidades_to_string))
        {
            return $this->credenciados_enderecos_cidades_to_string;
        }
    
        $values = CredenciadosEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

    public function set_credenciados_enderecos_tipos_enderecos_to_string($credenciados_enderecos_tipos_enderecos_to_string)
    {
        if(is_array($credenciados_enderecos_tipos_enderecos_to_string))
        {
            $values = TiposEnderecos::where('id', 'in', $credenciados_enderecos_tipos_enderecos_to_string)->getIndexedArray('id', 'id');
            $this->credenciados_enderecos_tipos_enderecos_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_enderecos_tipos_enderecos_to_string = $credenciados_enderecos_tipos_enderecos_to_string;
        }

        $this->vdata['credenciados_enderecos_tipos_enderecos_to_string'] = $this->credenciados_enderecos_tipos_enderecos_to_string;
    }

    public function get_credenciados_enderecos_tipos_enderecos_to_string()
    {
        if(!empty($this->credenciados_enderecos_tipos_enderecos_to_string))
        {
            return $this->credenciados_enderecos_tipos_enderecos_to_string;
        }
    
        $values = CredenciadosEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_enderecos_medicos_pf_to_string($medicos_pf_enderecos_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_enderecos_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_enderecos_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_enderecos_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_enderecos_medicos_pf_to_string = $medicos_pf_enderecos_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_enderecos_medicos_pf_to_string'] = $this->medicos_pf_enderecos_medicos_pf_to_string;
    }

    public function get_medicos_pf_enderecos_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_enderecos_medicos_pf_to_string))
        {
            return $this->medicos_pf_enderecos_medicos_pf_to_string;
        }
    
        $values = MedicosPfEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_enderecos_tipos_enderecos_to_string($medicos_pf_enderecos_tipos_enderecos_to_string)
    {
        if(is_array($medicos_pf_enderecos_tipos_enderecos_to_string))
        {
            $values = TiposEnderecos::where('id', 'in', $medicos_pf_enderecos_tipos_enderecos_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_enderecos_tipos_enderecos_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_enderecos_tipos_enderecos_to_string = $medicos_pf_enderecos_tipos_enderecos_to_string;
        }

        $this->vdata['medicos_pf_enderecos_tipos_enderecos_to_string'] = $this->medicos_pf_enderecos_tipos_enderecos_to_string;
    }

    public function get_medicos_pf_enderecos_tipos_enderecos_to_string()
    {
        if(!empty($this->medicos_pf_enderecos_tipos_enderecos_to_string))
        {
            return $this->medicos_pf_enderecos_tipos_enderecos_to_string;
        }
    
        $values = MedicosPfEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_enderecos_cidades_to_string($medicos_pf_enderecos_cidades_to_string)
    {
        if(is_array($medicos_pf_enderecos_cidades_to_string))
        {
            $values = Cidades::where('id', 'in', $medicos_pf_enderecos_cidades_to_string)->getIndexedArray('cidade', 'cidade');
            $this->medicos_pf_enderecos_cidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_enderecos_cidades_to_string = $medicos_pf_enderecos_cidades_to_string;
        }

        $this->vdata['medicos_pf_enderecos_cidades_to_string'] = $this->medicos_pf_enderecos_cidades_to_string;
    }

    public function get_medicos_pf_enderecos_cidades_to_string()
    {
        if(!empty($this->medicos_pf_enderecos_cidades_to_string))
        {
            return $this->medicos_pf_enderecos_cidades_to_string;
        }
    
        $values = MedicosPfEnderecos::where('tipos_enderecos_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

    
}

