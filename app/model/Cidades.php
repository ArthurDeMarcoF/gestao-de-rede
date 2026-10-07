<?php

class Cidades extends TRecord
{
    const TABLENAME  = 'cidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Estados $estados;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('estados_id');
        parent::addAttribute('cidade');
        parent::addAttribute('cod_ibge');
    
    }

    /**
     * Method set_estados
     * Sample of usage: $var->estados = $object;
     * @param $object Instance of Estados
     */
    public function set_estados(Estados $object)
    {
        $this->estados = $object;
        $this->estados_id = $object->id;
    }

    /**
     * Method get_estados
     * Sample of usage: $var->estados->attribute;
     * @returns Estados instance
     */
    public function get_estados()
    {
    
        // loads the associated object
        if (empty($this->estados))
            $this->estados = new Estados($this->estados_id);
    
        // returns the associated object
        return $this->estados;
    }

    /**
     * Method getEnderecosCooperadoss
     */
    public function getEnderecosCooperadoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidades_id', '=', $this->id));
        return EnderecosCooperados::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosEnderecoss
     */
    public function getCredenciadosEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidades_id', '=', $this->id));
        return CredenciadosEnderecos::getObjects( $criteria );
    }
    /**
     * Method getCatalogoEnderecos
     */
    public function getCatalogoEnderecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidades_id', '=', $this->id));
        return CatalogoEndereco::getObjects( $criteria );
    }
    /**
     * Method getCatalogos
     */
    public function getCatalogos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidades_id', '=', $this->id));
        return Catalogo::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfEnderecoss
     */
    public function getMedicosPfEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidades_id', '=', $this->id));
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
    
        $values = EnderecosCooperados::where('cidades_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = EnderecosCooperados::where('cidades_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
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
    
        $values = EnderecosCooperados::where('cidades_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
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
    
        $values = CredenciadosEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = CredenciadosEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
    }

    public function set_catalogo_endereco_catalogo_to_string($catalogo_endereco_catalogo_to_string)
    {
        if(is_array($catalogo_endereco_catalogo_to_string))
        {
            $values = Catalogo::where('id', 'in', $catalogo_endereco_catalogo_to_string)->getIndexedArray('nome', 'nome');
            $this->catalogo_endereco_catalogo_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_endereco_catalogo_to_string = $catalogo_endereco_catalogo_to_string;
        }

        $this->vdata['catalogo_endereco_catalogo_to_string'] = $this->catalogo_endereco_catalogo_to_string;
    }

    public function get_catalogo_endereco_catalogo_to_string()
    {
        if(!empty($this->catalogo_endereco_catalogo_to_string))
        {
            return $this->catalogo_endereco_catalogo_to_string;
        }
    
        $values = CatalogoEndereco::where('cidades_id', '=', $this->id)->getIndexedArray('catalogo_id','{catalogo->nome}');
        return implode(', ', $values);
    }

    public function set_catalogo_endereco_cidades_to_string($catalogo_endereco_cidades_to_string)
    {
        if(is_array($catalogo_endereco_cidades_to_string))
        {
            $values = Cidades::where('id', 'in', $catalogo_endereco_cidades_to_string)->getIndexedArray('cidade', 'cidade');
            $this->catalogo_endereco_cidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_endereco_cidades_to_string = $catalogo_endereco_cidades_to_string;
        }

        $this->vdata['catalogo_endereco_cidades_to_string'] = $this->catalogo_endereco_cidades_to_string;
    }

    public function get_catalogo_endereco_cidades_to_string()
    {
        if(!empty($this->catalogo_endereco_cidades_to_string))
        {
            return $this->catalogo_endereco_cidades_to_string;
        }
    
        $values = CatalogoEndereco::where('cidades_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

    public function set_catalogo_cidades_to_string($catalogo_cidades_to_string)
    {
        if(is_array($catalogo_cidades_to_string))
        {
            $values = Cidades::where('id', 'in', $catalogo_cidades_to_string)->getIndexedArray('cidade', 'cidade');
            $this->catalogo_cidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_cidades_to_string = $catalogo_cidades_to_string;
        }

        $this->vdata['catalogo_cidades_to_string'] = $this->catalogo_cidades_to_string;
    }

    public function get_catalogo_cidades_to_string()
    {
        if(!empty($this->catalogo_cidades_to_string))
        {
            return $this->catalogo_cidades_to_string;
        }
    
        $values = Catalogo::where('cidades_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = MedicosPfEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
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
    
        $values = MedicosPfEnderecos::where('cidades_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

}

