<?php

class MedicosPf extends TRecord
{
    const TABLENAME  = 'medicos_pf';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('crm');
        parent::addAttribute('data_contrato');
        parent::addAttribute('nome');
        parent::addAttribute('cpf');
        parent::addAttribute('rg');
        parent::addAttribute('sexo');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('ativo');
        parent::addAttribute('estado_civil');
        parent::addAttribute('path_foto');
        parent::addAttribute('flg_envio_dados');
        parent::addAttribute('data_encerramento_contrato');
        parent::addAttribute('cnpj');
        parent::addAttribute('cnes');
        parent::addAttribute('cod_plantonista');
            
    }

    /**
     * Method getMedicosPfMovimentacoess
     */
    public function getMedicosPfMovimentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfMovimentacoes::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfEnderecoss
     */
    public function getMedicosPfEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfEnderecos::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfContatoss
     */
    public function getMedicosPfContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfContatos::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfDadosBancarioss
     */
    public function getMedicosPfDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfDadosBancarios::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfDocumentacoess
     */
    public function getMedicosPfDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfEspecialidadess
     */
    public function getMedicosPfEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('medicos_pf_id', '=', $this->id));
        return MedicosPfEspecialidades::getObjects( $criteria );
    }

    public function set_medicos_pf_movimentacoes_medicos_pf_to_string($medicos_pf_movimentacoes_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_movimentacoes_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_movimentacoes_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_movimentacoes_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_movimentacoes_medicos_pf_to_string = $medicos_pf_movimentacoes_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_movimentacoes_medicos_pf_to_string'] = $this->medicos_pf_movimentacoes_medicos_pf_to_string;
    }

    public function get_medicos_pf_movimentacoes_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_movimentacoes_medicos_pf_to_string))
        {
            return $this->medicos_pf_movimentacoes_medicos_pf_to_string;
        }
    
        $values = MedicosPfMovimentacoes::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfEnderecos::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfEnderecos::where('medicos_pf_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
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
    
        $values = MedicosPfEnderecos::where('medicos_pf_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = MedicosPfContatos::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfContatos::where('medicos_pf_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
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
    
        $values = MedicosPfDadosBancarios::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfDadosBancarios::where('medicos_pf_id', '=', $this->id)->getIndexedArray('bancos_id','{bancos->banco}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_documentacoes_medicos_pf_to_string($medicos_pf_documentacoes_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_documentacoes_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_documentacoes_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_documentacoes_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_documentacoes_medicos_pf_to_string = $medicos_pf_documentacoes_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_documentacoes_medicos_pf_to_string'] = $this->medicos_pf_documentacoes_medicos_pf_to_string;
    }

    public function get_medicos_pf_documentacoes_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_documentacoes_medicos_pf_to_string))
        {
            return $this->medicos_pf_documentacoes_medicos_pf_to_string;
        }
    
        $values = MedicosPfDocumentacoes::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_documentacoes_tipos_documentacoes_to_string($medicos_pf_documentacoes_tipos_documentacoes_to_string)
    {
        if(is_array($medicos_pf_documentacoes_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $medicos_pf_documentacoes_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->medicos_pf_documentacoes_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_documentacoes_tipos_documentacoes_to_string = $medicos_pf_documentacoes_tipos_documentacoes_to_string;
        }

        $this->vdata['medicos_pf_documentacoes_tipos_documentacoes_to_string'] = $this->medicos_pf_documentacoes_tipos_documentacoes_to_string;
    }

    public function get_medicos_pf_documentacoes_tipos_documentacoes_to_string()
    {
        if(!empty($this->medicos_pf_documentacoes_tipos_documentacoes_to_string))
        {
            return $this->medicos_pf_documentacoes_tipos_documentacoes_to_string;
        }
    
        $values = MedicosPfDocumentacoes::where('medicos_pf_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_documentacoes_documentacoes_to_string($medicos_pf_documentacoes_documentacoes_to_string)
    {
        if(is_array($medicos_pf_documentacoes_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $medicos_pf_documentacoes_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_documentacoes_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_documentacoes_documentacoes_to_string = $medicos_pf_documentacoes_documentacoes_to_string;
        }

        $this->vdata['medicos_pf_documentacoes_documentacoes_to_string'] = $this->medicos_pf_documentacoes_documentacoes_to_string;
    }

    public function get_medicos_pf_documentacoes_documentacoes_to_string()
    {
        if(!empty($this->medicos_pf_documentacoes_documentacoes_to_string))
        {
            return $this->medicos_pf_documentacoes_documentacoes_to_string;
        }
    
        $values = MedicosPfDocumentacoes::where('medicos_pf_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_especialidades_especialidades_to_string($medicos_pf_especialidades_especialidades_to_string)
    {
        if(is_array($medicos_pf_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $medicos_pf_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->medicos_pf_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_especialidades_especialidades_to_string = $medicos_pf_especialidades_especialidades_to_string;
        }

        $this->vdata['medicos_pf_especialidades_especialidades_to_string'] = $this->medicos_pf_especialidades_especialidades_to_string;
    }

    public function get_medicos_pf_especialidades_especialidades_to_string()
    {
        if(!empty($this->medicos_pf_especialidades_especialidades_to_string))
        {
            return $this->medicos_pf_especialidades_especialidades_to_string;
        }
    
        $values = MedicosPfEspecialidades::where('medicos_pf_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_especialidades_medicos_pf_to_string($medicos_pf_especialidades_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_especialidades_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_especialidades_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_especialidades_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_especialidades_medicos_pf_to_string = $medicos_pf_especialidades_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_especialidades_medicos_pf_to_string'] = $this->medicos_pf_especialidades_medicos_pf_to_string;
    }

    public function get_medicos_pf_especialidades_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_especialidades_medicos_pf_to_string))
        {
            return $this->medicos_pf_especialidades_medicos_pf_to_string;
        }
    
        $values = MedicosPfEspecialidades::where('medicos_pf_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    
}

