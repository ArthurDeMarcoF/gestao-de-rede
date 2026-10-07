<?php

class Bancos extends TRecord
{
    const TABLENAME  = 'bancos';
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
        parent::addAttribute('banco');
        parent::addAttribute('codigo');
            
    }

    /**
     * Method getCooperadosDadosBancarioss
     */
    public function getCooperadosDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('banco_id', '=', $this->id));
        return CooperadosDadosBancarios::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosDadosBancarioss
     */
    public function getCredenciadosDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('bancos_id', '=', $this->id));
        return CredenciadosDadosBancarios::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfDadosBancarioss
     */
    public function getMedicosPfDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('bancos_id', '=', $this->id));
        return MedicosPfDadosBancarios::getObjects( $criteria );
    }

    public function set_cooperados_dados_bancarios_banco_to_string($cooperados_dados_bancarios_banco_to_string)
    {
        if(is_array($cooperados_dados_bancarios_banco_to_string))
        {
            $values = Bancos::where('id', 'in', $cooperados_dados_bancarios_banco_to_string)->getIndexedArray('banco', 'banco');
            $this->cooperados_dados_bancarios_banco_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_dados_bancarios_banco_to_string = $cooperados_dados_bancarios_banco_to_string;
        }

        $this->vdata['cooperados_dados_bancarios_banco_to_string'] = $this->cooperados_dados_bancarios_banco_to_string;
    }

    public function get_cooperados_dados_bancarios_banco_to_string()
    {
        if(!empty($this->cooperados_dados_bancarios_banco_to_string))
        {
            return $this->cooperados_dados_bancarios_banco_to_string;
        }
    
        $values = CooperadosDadosBancarios::where('banco_id', '=', $this->id)->getIndexedArray('banco_id','{banco->banco}');
        return implode(', ', $values);
    }

    public function set_cooperados_dados_bancarios_cooperados_to_string($cooperados_dados_bancarios_cooperados_to_string)
    {
        if(is_array($cooperados_dados_bancarios_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_dados_bancarios_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_dados_bancarios_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_dados_bancarios_cooperados_to_string = $cooperados_dados_bancarios_cooperados_to_string;
        }

        $this->vdata['cooperados_dados_bancarios_cooperados_to_string'] = $this->cooperados_dados_bancarios_cooperados_to_string;
    }

    public function get_cooperados_dados_bancarios_cooperados_to_string()
    {
        if(!empty($this->cooperados_dados_bancarios_cooperados_to_string))
        {
            return $this->cooperados_dados_bancarios_cooperados_to_string;
        }
    
        $values = CooperadosDadosBancarios::where('banco_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_dados_bancarios_bancos_to_string($credenciados_dados_bancarios_bancos_to_string)
    {
        if(is_array($credenciados_dados_bancarios_bancos_to_string))
        {
            $values = Bancos::where('id', 'in', $credenciados_dados_bancarios_bancos_to_string)->getIndexedArray('banco', 'banco');
            $this->credenciados_dados_bancarios_bancos_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_dados_bancarios_bancos_to_string = $credenciados_dados_bancarios_bancos_to_string;
        }

        $this->vdata['credenciados_dados_bancarios_bancos_to_string'] = $this->credenciados_dados_bancarios_bancos_to_string;
    }

    public function get_credenciados_dados_bancarios_bancos_to_string()
    {
        if(!empty($this->credenciados_dados_bancarios_bancos_to_string))
        {
            return $this->credenciados_dados_bancarios_bancos_to_string;
        }
    
        $values = CredenciadosDadosBancarios::where('bancos_id', '=', $this->id)->getIndexedArray('bancos_id','{bancos->banco}');
        return implode(', ', $values);
    }

    public function set_credenciados_dados_bancarios_credenciados_to_string($credenciados_dados_bancarios_credenciados_to_string)
    {
        if(is_array($credenciados_dados_bancarios_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_dados_bancarios_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_dados_bancarios_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_dados_bancarios_credenciados_to_string = $credenciados_dados_bancarios_credenciados_to_string;
        }

        $this->vdata['credenciados_dados_bancarios_credenciados_to_string'] = $this->credenciados_dados_bancarios_credenciados_to_string;
    }

    public function get_credenciados_dados_bancarios_credenciados_to_string()
    {
        if(!empty($this->credenciados_dados_bancarios_credenciados_to_string))
        {
            return $this->credenciados_dados_bancarios_credenciados_to_string;
        }
    
        $values = CredenciadosDadosBancarios::where('bancos_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_dados_bancarios_medicos_pf_to_string($medicos_pf_dados_bancarios_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_dados_bancarios_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_dados_bancarios_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_dados_bancarios_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_dados_bancarios_medicos_pf_to_string = $medicos_pf_dados_bancarios_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_dados_bancarios_medicos_pf_to_string'] = $this->medicos_pf_dados_bancarios_medicos_pf_to_string;
    }

    public function get_medicos_pf_dados_bancarios_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_dados_bancarios_medicos_pf_to_string))
        {
            return $this->medicos_pf_dados_bancarios_medicos_pf_to_string;
        }
    
        $values = MedicosPfDadosBancarios::where('bancos_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_dados_bancarios_bancos_to_string($medicos_pf_dados_bancarios_bancos_to_string)
    {
        if(is_array($medicos_pf_dados_bancarios_bancos_to_string))
        {
            $values = Bancos::where('id', 'in', $medicos_pf_dados_bancarios_bancos_to_string)->getIndexedArray('banco', 'banco');
            $this->medicos_pf_dados_bancarios_bancos_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_dados_bancarios_bancos_to_string = $medicos_pf_dados_bancarios_bancos_to_string;
        }

        $this->vdata['medicos_pf_dados_bancarios_bancos_to_string'] = $this->medicos_pf_dados_bancarios_bancos_to_string;
    }

    public function get_medicos_pf_dados_bancarios_bancos_to_string()
    {
        if(!empty($this->medicos_pf_dados_bancarios_bancos_to_string))
        {
            return $this->medicos_pf_dados_bancarios_bancos_to_string;
        }
    
        $values = MedicosPfDadosBancarios::where('bancos_id', '=', $this->id)->getIndexedArray('bancos_id','{bancos->banco}');
        return implode(', ', $values);
    }

    
}

