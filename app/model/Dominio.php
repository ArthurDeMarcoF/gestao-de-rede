<?php

class Dominio extends TRecord
{
    const TABLENAME  = 'dominio';
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
        parent::addAttribute('codigo');
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('flg_colorir_pad');
            
    }

    /**
     * Method getDominioValors
     */
    public function getDominioValors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('dominio_id', '=', $this->id));
        return DominioValor::getObjects( $criteria );
    }
    /**
     * Method getDominioRelacionamentos
     */
    public function getDominioRelacionamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('dominio_id', '=', $this->id));
        return DominioRelacionamento::getObjects( $criteria );
    }
    /**
     * Method getParametros
     */
    public function getParametros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('dominio_id', '=', $this->id));
        return Parametro::getObjects( $criteria );
    }

    public function set_dominio_valor_dominio_to_string($dominio_valor_dominio_to_string)
    {
        if(is_array($dominio_valor_dominio_to_string))
        {
            $values = Dominio::where('id', 'in', $dominio_valor_dominio_to_string)->getIndexedArray('nome', 'nome');
            $this->dominio_valor_dominio_to_string = implode(', ', $values);
        }
        else
        {
            $this->dominio_valor_dominio_to_string = $dominio_valor_dominio_to_string;
        }

        $this->vdata['dominio_valor_dominio_to_string'] = $this->dominio_valor_dominio_to_string;
    }

    public function get_dominio_valor_dominio_to_string()
    {
        if(!empty($this->dominio_valor_dominio_to_string))
        {
            return $this->dominio_valor_dominio_to_string;
        }
    
        $values = DominioValor::where('dominio_id', '=', $this->id)->getIndexedArray('dominio_id','{dominio->nome}');
        return implode(', ', $values);
    }

    public function set_dominio_relacionamento_dominio_to_string($dominio_relacionamento_dominio_to_string)
    {
        if(is_array($dominio_relacionamento_dominio_to_string))
        {
            $values = Dominio::where('id', 'in', $dominio_relacionamento_dominio_to_string)->getIndexedArray('nome', 'nome');
            $this->dominio_relacionamento_dominio_to_string = implode(', ', $values);
        }
        else
        {
            $this->dominio_relacionamento_dominio_to_string = $dominio_relacionamento_dominio_to_string;
        }

        $this->vdata['dominio_relacionamento_dominio_to_string'] = $this->dominio_relacionamento_dominio_to_string;
    }

    public function get_dominio_relacionamento_dominio_to_string()
    {
        if(!empty($this->dominio_relacionamento_dominio_to_string))
        {
            return $this->dominio_relacionamento_dominio_to_string;
        }
    
        $values = DominioRelacionamento::where('dominio_id', '=', $this->id)->getIndexedArray('dominio_id','{dominio->nome}');
        return implode(', ', $values);
    }

    public function set_parametro_dominio_to_string($parametro_dominio_to_string)
    {
        if(is_array($parametro_dominio_to_string))
        {
            $values = Dominio::where('id', 'in', $parametro_dominio_to_string)->getIndexedArray('nome', 'nome');
            $this->parametro_dominio_to_string = implode(', ', $values);
        }
        else
        {
            $this->parametro_dominio_to_string = $parametro_dominio_to_string;
        }

        $this->vdata['parametro_dominio_to_string'] = $this->parametro_dominio_to_string;
    }

    public function get_parametro_dominio_to_string()
    {
        if(!empty($this->parametro_dominio_to_string))
        {
            return $this->parametro_dominio_to_string;
        }
    
        $values = Parametro::where('dominio_id', '=', $this->id)->getIndexedArray('dominio_id','{dominio->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(DominioValor::where('dominio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(DominioRelacionamento::where('dominio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(Parametro::where('dominio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

