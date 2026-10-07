<?php

class CategoriaResponsavel extends TRecord
{
    const TABLENAME  = 'categoria_responsavel';
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
        parent::addAttribute('nome');
            
    }

    /**
     * Method getCredenciadosResponsaveiss
     */
    public function getCredenciadosResponsaveiss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('categoria_responsavel_id', '=', $this->id));
        return CredenciadosResponsaveis::getObjects( $criteria );
    }

    public function set_credenciados_responsaveis_credenciados_to_string($credenciados_responsaveis_credenciados_to_string)
    {
        if(is_array($credenciados_responsaveis_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_responsaveis_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_responsaveis_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_responsaveis_credenciados_to_string = $credenciados_responsaveis_credenciados_to_string;
        }

        $this->vdata['credenciados_responsaveis_credenciados_to_string'] = $this->credenciados_responsaveis_credenciados_to_string;
    }

    public function get_credenciados_responsaveis_credenciados_to_string()
    {
        if(!empty($this->credenciados_responsaveis_credenciados_to_string))
        {
            return $this->credenciados_responsaveis_credenciados_to_string;
        }
    
        $values = CredenciadosResponsaveis::where('categoria_responsavel_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_responsaveis_categoria_responsavel_to_string($credenciados_responsaveis_categoria_responsavel_to_string)
    {
        if(is_array($credenciados_responsaveis_categoria_responsavel_to_string))
        {
            $values = CategoriaResponsavel::where('id', 'in', $credenciados_responsaveis_categoria_responsavel_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_responsaveis_categoria_responsavel_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_responsaveis_categoria_responsavel_to_string = $credenciados_responsaveis_categoria_responsavel_to_string;
        }

        $this->vdata['credenciados_responsaveis_categoria_responsavel_to_string'] = $this->credenciados_responsaveis_categoria_responsavel_to_string;
    }

    public function get_credenciados_responsaveis_categoria_responsavel_to_string()
    {
        if(!empty($this->credenciados_responsaveis_categoria_responsavel_to_string))
        {
            return $this->credenciados_responsaveis_categoria_responsavel_to_string;
        }
    
        $values = CredenciadosResponsaveis::where('categoria_responsavel_id', '=', $this->id)->getIndexedArray('categoria_responsavel_id','{categoria_responsavel->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(CredenciadosResponsaveis::where('categoria_responsavel_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

