<?php

class Catalogo extends TRecord
{
    const TABLENAME  = 'catalogo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Cidades $cidades;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('dm_categoria');
        parent::addAttribute('nome');
        parent::addAttribute('cpf_cnpj');
        parent::addAttribute('data_solicitacao');
        parent::addAttribute('cidades_id');
        parent::addAttribute('observacao');
            
    }

    /**
     * Method set_cidades
     * Sample of usage: $var->cidades = $object;
     * @param $object Instance of Cidades
     */
    public function set_cidades(Cidades $object)
    {
        $this->cidades = $object;
        $this->cidades_id = $object->id;
    }

    /**
     * Method get_cidades
     * Sample of usage: $var->cidades->attribute;
     * @returns Cidades instance
     */
    public function get_cidades()
    {
    
        // loads the associated object
        if (empty($this->cidades))
            $this->cidades = new Cidades($this->cidades_id);
    
        // returns the associated object
        return $this->cidades;
    }

    /**
     * Method getCatalogoContatos
     */
    public function getCatalogoContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('catalogo_id', '=', $this->id));
        return CatalogoContato::getObjects( $criteria );
    }
    /**
     * Method getCatalogoEnderecos
     */
    public function getCatalogoEnderecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('catalogo_id', '=', $this->id));
        return CatalogoEndereco::getObjects( $criteria );
    }
    /**
     * Method getCatalogoEspecialidades
     */
    public function getCatalogoEspecialidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('catalogo_id', '=', $this->id));
        return CatalogoEspecialidade::getObjects( $criteria );
    }

    public function set_catalogo_contato_catalogo_to_string($catalogo_contato_catalogo_to_string)
    {
        if(is_array($catalogo_contato_catalogo_to_string))
        {
            $values = Catalogo::where('id', 'in', $catalogo_contato_catalogo_to_string)->getIndexedArray('nome', 'nome');
            $this->catalogo_contato_catalogo_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_contato_catalogo_to_string = $catalogo_contato_catalogo_to_string;
        }

        $this->vdata['catalogo_contato_catalogo_to_string'] = $this->catalogo_contato_catalogo_to_string;
    }

    public function get_catalogo_contato_catalogo_to_string()
    {
        if(!empty($this->catalogo_contato_catalogo_to_string))
        {
            return $this->catalogo_contato_catalogo_to_string;
        }
    
        $values = CatalogoContato::where('catalogo_id', '=', $this->id)->getIndexedArray('catalogo_id','{catalogo->nome}');
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
    
        $values = CatalogoEndereco::where('catalogo_id', '=', $this->id)->getIndexedArray('catalogo_id','{catalogo->nome}');
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
    
        $values = CatalogoEndereco::where('catalogo_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
        return implode(', ', $values);
    }

    public function set_catalogo_especialidade_catalogo_to_string($catalogo_especialidade_catalogo_to_string)
    {
        if(is_array($catalogo_especialidade_catalogo_to_string))
        {
            $values = Catalogo::where('id', 'in', $catalogo_especialidade_catalogo_to_string)->getIndexedArray('nome', 'nome');
            $this->catalogo_especialidade_catalogo_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_especialidade_catalogo_to_string = $catalogo_especialidade_catalogo_to_string;
        }

        $this->vdata['catalogo_especialidade_catalogo_to_string'] = $this->catalogo_especialidade_catalogo_to_string;
    }

    public function get_catalogo_especialidade_catalogo_to_string()
    {
        if(!empty($this->catalogo_especialidade_catalogo_to_string))
        {
            return $this->catalogo_especialidade_catalogo_to_string;
        }
    
        $values = CatalogoEspecialidade::where('catalogo_id', '=', $this->id)->getIndexedArray('catalogo_id','{catalogo->nome}');
        return implode(', ', $values);
    }

    public function set_catalogo_especialidade_especialidades_to_string($catalogo_especialidade_especialidades_to_string)
    {
        if(is_array($catalogo_especialidade_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $catalogo_especialidade_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->catalogo_especialidade_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_especialidade_especialidades_to_string = $catalogo_especialidade_especialidades_to_string;
        }

        $this->vdata['catalogo_especialidade_especialidades_to_string'] = $this->catalogo_especialidade_especialidades_to_string;
    }

    public function get_catalogo_especialidade_especialidades_to_string()
    {
        if(!empty($this->catalogo_especialidade_especialidades_to_string))
        {
            return $this->catalogo_especialidade_especialidades_to_string;
        }
    
        $values = CatalogoEspecialidade::where('catalogo_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(CatalogoContato::where('catalogo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(CatalogoEndereco::where('catalogo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(CatalogoEspecialidade::where('catalogo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

