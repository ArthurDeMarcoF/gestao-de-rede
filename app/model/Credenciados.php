<?php

class Credenciados extends TRecord
{
    const TABLENAME  = 'credenciados';
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
        parent::addAttribute('data_inicio');
        parent::addAttribute('nome');
        parent::addAttribute('cnpj');
        parent::addAttribute('cnes');
        parent::addAttribute('inscricao_estadual');
        parent::addAttribute('ativo');
        parent::addAttribute('enquadramento_tributario');
        parent::addAttribute('codigo_prestador');
        parent::addAttribute('data_contrato');
        parent::addAttribute('dm_reaj_contr');
        parent::addAttribute('nr_dias_aviso_reaj');
        parent::addAttribute('flg_dias_padrao');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('dt_descredenciamento');
            
    }

    /**
     * Method getCredenciadosMovimentacoess
     */
    public function getCredenciadosMovimentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosMovimentacoes::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosContatoss
     */
    public function getCredenciadosContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosContatos::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosEnderecoss
     */
    public function getCredenciadosEnderecoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosEnderecos::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosResponsaveiss
     */
    public function getCredenciadosResponsaveiss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosResponsaveis::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosDocumentacoess
     */
    public function getCredenciadosDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosDadosBancarioss
     */
    public function getCredenciadosDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosDadosBancarios::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosEspecialidadess
     */
    public function getCredenciadosEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosEspecialidades::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosReajustes
     */
    public function getCredenciadosReajustes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('credenciados_id', '=', $this->id));
        return CredenciadosReajuste::getObjects( $criteria );
    }

    public function set_credenciados_movimentacoes_credenciados_to_string($credenciados_movimentacoes_credenciados_to_string)
    {
        if(is_array($credenciados_movimentacoes_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_movimentacoes_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_movimentacoes_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_movimentacoes_credenciados_to_string = $credenciados_movimentacoes_credenciados_to_string;
        }

        $this->vdata['credenciados_movimentacoes_credenciados_to_string'] = $this->credenciados_movimentacoes_credenciados_to_string;
    }

    public function get_credenciados_movimentacoes_credenciados_to_string()
    {
        if(!empty($this->credenciados_movimentacoes_credenciados_to_string))
        {
            return $this->credenciados_movimentacoes_credenciados_to_string;
        }
    
        $values = CredenciadosMovimentacoes::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosContatos::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosContatos::where('credenciados_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
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
    
        $values = CredenciadosEnderecos::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosEnderecos::where('credenciados_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = CredenciadosEnderecos::where('credenciados_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
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
    
        $values = CredenciadosResponsaveis::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosResponsaveis::where('credenciados_id', '=', $this->id)->getIndexedArray('categoria_responsavel_id','{categoria_responsavel->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_documentacoes_credenciados_to_string($credenciados_documentacoes_credenciados_to_string)
    {
        if(is_array($credenciados_documentacoes_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_documentacoes_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_documentacoes_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_documentacoes_credenciados_to_string = $credenciados_documentacoes_credenciados_to_string;
        }

        $this->vdata['credenciados_documentacoes_credenciados_to_string'] = $this->credenciados_documentacoes_credenciados_to_string;
    }

    public function get_credenciados_documentacoes_credenciados_to_string()
    {
        if(!empty($this->credenciados_documentacoes_credenciados_to_string))
        {
            return $this->credenciados_documentacoes_credenciados_to_string;
        }
    
        $values = CredenciadosDocumentacoes::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_documentacoes_tipos_documentacoes_to_string($credenciados_documentacoes_tipos_documentacoes_to_string)
    {
        if(is_array($credenciados_documentacoes_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $credenciados_documentacoes_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->credenciados_documentacoes_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_documentacoes_tipos_documentacoes_to_string = $credenciados_documentacoes_tipos_documentacoes_to_string;
        }

        $this->vdata['credenciados_documentacoes_tipos_documentacoes_to_string'] = $this->credenciados_documentacoes_tipos_documentacoes_to_string;
    }

    public function get_credenciados_documentacoes_tipos_documentacoes_to_string()
    {
        if(!empty($this->credenciados_documentacoes_tipos_documentacoes_to_string))
        {
            return $this->credenciados_documentacoes_tipos_documentacoes_to_string;
        }
    
        $values = CredenciadosDocumentacoes::where('credenciados_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_credenciados_documentacoes_documentacoes_to_string($credenciados_documentacoes_documentacoes_to_string)
    {
        if(is_array($credenciados_documentacoes_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $credenciados_documentacoes_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->credenciados_documentacoes_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_documentacoes_documentacoes_to_string = $credenciados_documentacoes_documentacoes_to_string;
        }

        $this->vdata['credenciados_documentacoes_documentacoes_to_string'] = $this->credenciados_documentacoes_documentacoes_to_string;
    }

    public function get_credenciados_documentacoes_documentacoes_to_string()
    {
        if(!empty($this->credenciados_documentacoes_documentacoes_to_string))
        {
            return $this->credenciados_documentacoes_documentacoes_to_string;
        }
    
        $values = CredenciadosDocumentacoes::where('credenciados_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
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
    
        $values = CredenciadosDadosBancarios::where('credenciados_id', '=', $this->id)->getIndexedArray('bancos_id','{bancos->banco}');
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
    
        $values = CredenciadosDadosBancarios::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_especialidades_credenciados_to_string($credenciados_especialidades_credenciados_to_string)
    {
        if(is_array($credenciados_especialidades_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_especialidades_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_especialidades_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_especialidades_credenciados_to_string = $credenciados_especialidades_credenciados_to_string;
        }

        $this->vdata['credenciados_especialidades_credenciados_to_string'] = $this->credenciados_especialidades_credenciados_to_string;
    }

    public function get_credenciados_especialidades_credenciados_to_string()
    {
        if(!empty($this->credenciados_especialidades_credenciados_to_string))
        {
            return $this->credenciados_especialidades_credenciados_to_string;
        }
    
        $values = CredenciadosEspecialidades::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_especialidades_especialidades_to_string($credenciados_especialidades_especialidades_to_string)
    {
        if(is_array($credenciados_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $credenciados_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->credenciados_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_especialidades_especialidades_to_string = $credenciados_especialidades_especialidades_to_string;
        }

        $this->vdata['credenciados_especialidades_especialidades_to_string'] = $this->credenciados_especialidades_especialidades_to_string;
    }

    public function get_credenciados_especialidades_especialidades_to_string()
    {
        if(!empty($this->credenciados_especialidades_especialidades_to_string))
        {
            return $this->credenciados_especialidades_especialidades_to_string;
        }
    
        $values = CredenciadosEspecialidades::where('credenciados_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_credenciados_reajuste_credenciados_to_string($credenciados_reajuste_credenciados_to_string)
    {
        if(is_array($credenciados_reajuste_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_reajuste_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_reajuste_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_reajuste_credenciados_to_string = $credenciados_reajuste_credenciados_to_string;
        }

        $this->vdata['credenciados_reajuste_credenciados_to_string'] = $this->credenciados_reajuste_credenciados_to_string;
    }

    public function get_credenciados_reajuste_credenciados_to_string()
    {
        if(!empty($this->credenciados_reajuste_credenciados_to_string))
        {
            return $this->credenciados_reajuste_credenciados_to_string;
        }
    
        $values = CredenciadosReajuste::where('credenciados_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    
}

