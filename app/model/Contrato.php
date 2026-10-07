<?php

class Contrato extends TRecord
{
    const TABLENAME  = 'contrato';
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
        parent::addAttribute('empresa');
        parent::addAttribute('cnpj');
        parent::addAttribute('dt_contrato');
        parent::addAttribute('dt_reajuste');
        parent::addAttribute('indice');
        parent::addAttribute('path_arquivo');
        parent::addAttribute('dm_situacao');
        parent::addAttribute('apolice');
            
    }

    /**
     * Method getContratoServicos
     */
    public function getContratoServicos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('contrato_id', '=', $this->id));
        return ContratoServico::getObjects( $criteria );
    }
    /**
     * Method getContratoValors
     */
    public function getContratoValors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('contrato_id', '=', $this->id));
        return ContratoValor::getObjects( $criteria );
    }

    public function set_contrato_servico_contrato_to_string($contrato_servico_contrato_to_string)
    {
        if(is_array($contrato_servico_contrato_to_string))
        {
            $values = Contrato::where('id', 'in', $contrato_servico_contrato_to_string)->getIndexedArray('nome', 'nome');
            $this->contrato_servico_contrato_to_string = implode(', ', $values);
        }
        else
        {
            $this->contrato_servico_contrato_to_string = $contrato_servico_contrato_to_string;
        }

        $this->vdata['contrato_servico_contrato_to_string'] = $this->contrato_servico_contrato_to_string;
    }

    public function get_contrato_servico_contrato_to_string()
    {
        if(!empty($this->contrato_servico_contrato_to_string))
        {
            return $this->contrato_servico_contrato_to_string;
        }
    
        $values = ContratoServico::where('contrato_id', '=', $this->id)->getIndexedArray('contrato_id','{contrato->nome}');
        return implode(', ', $values);
    }

    public function set_contrato_valor_contrato_to_string($contrato_valor_contrato_to_string)
    {
        if(is_array($contrato_valor_contrato_to_string))
        {
            $values = Contrato::where('id', 'in', $contrato_valor_contrato_to_string)->getIndexedArray('nome', 'nome');
            $this->contrato_valor_contrato_to_string = implode(', ', $values);
        }
        else
        {
            $this->contrato_valor_contrato_to_string = $contrato_valor_contrato_to_string;
        }

        $this->vdata['contrato_valor_contrato_to_string'] = $this->contrato_valor_contrato_to_string;
    }

    public function get_contrato_valor_contrato_to_string()
    {
        if(!empty($this->contrato_valor_contrato_to_string))
        {
            return $this->contrato_valor_contrato_to_string;
        }
    
        $values = ContratoValor::where('contrato_id', '=', $this->id)->getIndexedArray('contrato_id','{contrato->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(ContratoServico::where('contrato_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(ContratoValor::where('contrato_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

