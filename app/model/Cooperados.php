<?php

class Cooperados extends TRecord
{
    const TABLENAME  = 'cooperados';
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
        parent::addAttribute('crm');
        parent::addAttribute('inss');
        parent::addAttribute('data_filiacao');
        parent::addAttribute('nome');
        parent::addAttribute('cpf');
        parent::addAttribute('rg');
        parent::addAttribute('sexo');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('ativo');
        parent::addAttribute('forma_integralizacao');
        parent::addAttribute('estado_civil');
        parent::addAttribute('numero_filhos');
        parent::addAttribute('cnis');
        parent::addAttribute('flg_retem_ir');
        parent::addAttribute('flg_declara_dep');
        parent::addAttribute('flg_recolhe_inss');
        parent::addAttribute('path_foto');
        parent::addAttribute('contabilidade');
        parent::addAttribute('flg_envio_dados');
        parent::addAttribute('dt_desfiliacao');
            
    }

    /**
     * Method getArquivosCooperados
     */
    public function getArquivosCooperados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperado_id', '=', $this->id));
        return ArquivosCooperado::getObjects( $criteria );
    }
    /**
     * Method getEnderecosCooperadoss
     */
    public function getEnderecosCooperadoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return EnderecosCooperados::getObjects( $criteria );
    }
    /**
     * Method getCooperadosCtbContatos
     */
    public function getCooperadosCtbContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosCtbContato::getObjects( $criteria );
    }
    /**
     * Method getCooperadosDadosBancarioss
     */
    public function getCooperadosDadosBancarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosDadosBancarios::getObjects( $criteria );
    }
    /**
     * Method getCooperadosMovimentacoess
     */
    public function getCooperadosMovimentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosMovimentacoes::getObjects( $criteria );
    }
    /**
     * Method getCooperadosEspecialidadess
     */
    public function getCooperadosEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosEspecialidades::getObjects( $criteria );
    }
    /**
     * Method getCooperadosBeneficiarioss
     */
    public function getCooperadosBeneficiarioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosBeneficiarios::getObjects( $criteria );
    }
    /**
     * Method getCooperadosDocumentacoess
     */
    public function getCooperadosDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getCooperadosContatoss
     */
    public function getCooperadosContatoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosContatos::getObjects( $criteria );
    }
    /**
     * Method getBeneficioss
     */
    public function getBeneficioss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return Beneficios::getObjects( $criteria );
    }
    /**
     * Method getCooperadosSecretarias
     */
    public function getCooperadosSecretarias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosSecretaria::getObjects( $criteria );
    }
    /**
     * Method getCooperadosCapitals
     */
    public function getCooperadosCapitals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cooperados_id', '=', $this->id));
        return CooperadosCapital::getObjects( $criteria );
    }

    public function set_arquivos_cooperado_arquivos_status_to_string($arquivos_cooperado_arquivos_status_to_string)
    {
        if(is_array($arquivos_cooperado_arquivos_status_to_string))
        {
            $values = ArquivosStatus::where('id', 'in', $arquivos_cooperado_arquivos_status_to_string)->getIndexedArray('nome', 'nome');
            $this->arquivos_cooperado_arquivos_status_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_cooperado_arquivos_status_to_string = $arquivos_cooperado_arquivos_status_to_string;
        }

        $this->vdata['arquivos_cooperado_arquivos_status_to_string'] = $this->arquivos_cooperado_arquivos_status_to_string;
    }

    public function get_arquivos_cooperado_arquivos_status_to_string()
    {
        if(!empty($this->arquivos_cooperado_arquivos_status_to_string))
        {
            return $this->arquivos_cooperado_arquivos_status_to_string;
        }
    
        $values = ArquivosCooperado::where('cooperado_id', '=', $this->id)->getIndexedArray('arquivos_status_id','{arquivos_status->nome}');
        return implode(', ', $values);
    }

    public function set_arquivos_cooperado_cooperado_to_string($arquivos_cooperado_cooperado_to_string)
    {
        if(is_array($arquivos_cooperado_cooperado_to_string))
        {
            $values = Cooperados::where('id', 'in', $arquivos_cooperado_cooperado_to_string)->getIndexedArray('nome', 'nome');
            $this->arquivos_cooperado_cooperado_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_cooperado_cooperado_to_string = $arquivos_cooperado_cooperado_to_string;
        }

        $this->vdata['arquivos_cooperado_cooperado_to_string'] = $this->arquivos_cooperado_cooperado_to_string;
    }

    public function get_arquivos_cooperado_cooperado_to_string()
    {
        if(!empty($this->arquivos_cooperado_cooperado_to_string))
        {
            return $this->arquivos_cooperado_cooperado_to_string;
        }
    
        $values = ArquivosCooperado::where('cooperado_id', '=', $this->id)->getIndexedArray('cooperado_id','{cooperado->nome}');
        return implode(', ', $values);
    }

    public function set_arquivos_cooperado_arquivos_to_string($arquivos_cooperado_arquivos_to_string)
    {
        if(is_array($arquivos_cooperado_arquivos_to_string))
        {
            $values = Arquivos::where('id', 'in', $arquivos_cooperado_arquivos_to_string)->getIndexedArray('nome_arquivo', 'nome_arquivo');
            $this->arquivos_cooperado_arquivos_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_cooperado_arquivos_to_string = $arquivos_cooperado_arquivos_to_string;
        }

        $this->vdata['arquivos_cooperado_arquivos_to_string'] = $this->arquivos_cooperado_arquivos_to_string;
    }

    public function get_arquivos_cooperado_arquivos_to_string()
    {
        if(!empty($this->arquivos_cooperado_arquivos_to_string))
        {
            return $this->arquivos_cooperado_arquivos_to_string;
        }
    
        $values = ArquivosCooperado::where('cooperado_id', '=', $this->id)->getIndexedArray('arquivos_id','{arquivos->nome_arquivo}');
        return implode(', ', $values);
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
    
        $values = EnderecosCooperados::where('cooperados_id', '=', $this->id)->getIndexedArray('cidades_id','{cidades->cidade}');
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
    
        $values = EnderecosCooperados::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
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
    
        $values = EnderecosCooperados::where('cooperados_id', '=', $this->id)->getIndexedArray('tipos_enderecos_id','{tipos_enderecos->id}');
        return implode(', ', $values);
    }

    public function set_cooperados_ctb_contato_cooperados_to_string($cooperados_ctb_contato_cooperados_to_string)
    {
        if(is_array($cooperados_ctb_contato_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_ctb_contato_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_ctb_contato_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_ctb_contato_cooperados_to_string = $cooperados_ctb_contato_cooperados_to_string;
        }

        $this->vdata['cooperados_ctb_contato_cooperados_to_string'] = $this->cooperados_ctb_contato_cooperados_to_string;
    }

    public function get_cooperados_ctb_contato_cooperados_to_string()
    {
        if(!empty($this->cooperados_ctb_contato_cooperados_to_string))
        {
            return $this->cooperados_ctb_contato_cooperados_to_string;
        }
    
        $values = CooperadosCtbContato::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
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
    
        $values = CooperadosDadosBancarios::where('cooperados_id', '=', $this->id)->getIndexedArray('banco_id','{banco->banco}');
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
    
        $values = CooperadosDadosBancarios::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_movimentacoes_cooperados_to_string($cooperados_movimentacoes_cooperados_to_string)
    {
        if(is_array($cooperados_movimentacoes_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_movimentacoes_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_movimentacoes_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_movimentacoes_cooperados_to_string = $cooperados_movimentacoes_cooperados_to_string;
        }

        $this->vdata['cooperados_movimentacoes_cooperados_to_string'] = $this->cooperados_movimentacoes_cooperados_to_string;
    }

    public function get_cooperados_movimentacoes_cooperados_to_string()
    {
        if(!empty($this->cooperados_movimentacoes_cooperados_to_string))
        {
            return $this->cooperados_movimentacoes_cooperados_to_string;
        }
    
        $values = CooperadosMovimentacoes::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_especialidades_cooperados_to_string($cooperados_especialidades_cooperados_to_string)
    {
        if(is_array($cooperados_especialidades_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_especialidades_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_especialidades_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_especialidades_cooperados_to_string = $cooperados_especialidades_cooperados_to_string;
        }

        $this->vdata['cooperados_especialidades_cooperados_to_string'] = $this->cooperados_especialidades_cooperados_to_string;
    }

    public function get_cooperados_especialidades_cooperados_to_string()
    {
        if(!empty($this->cooperados_especialidades_cooperados_to_string))
        {
            return $this->cooperados_especialidades_cooperados_to_string;
        }
    
        $values = CooperadosEspecialidades::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_especialidades_especialidades_to_string($cooperados_especialidades_especialidades_to_string)
    {
        if(is_array($cooperados_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $cooperados_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->cooperados_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_especialidades_especialidades_to_string = $cooperados_especialidades_especialidades_to_string;
        }

        $this->vdata['cooperados_especialidades_especialidades_to_string'] = $this->cooperados_especialidades_especialidades_to_string;
    }

    public function get_cooperados_especialidades_especialidades_to_string()
    {
        if(!empty($this->cooperados_especialidades_especialidades_to_string))
        {
            return $this->cooperados_especialidades_especialidades_to_string;
        }
    
        $values = CooperadosEspecialidades::where('cooperados_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_cooperados_beneficiarios_cooperados_to_string($cooperados_beneficiarios_cooperados_to_string)
    {
        if(is_array($cooperados_beneficiarios_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_beneficiarios_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_beneficiarios_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_beneficiarios_cooperados_to_string = $cooperados_beneficiarios_cooperados_to_string;
        }

        $this->vdata['cooperados_beneficiarios_cooperados_to_string'] = $this->cooperados_beneficiarios_cooperados_to_string;
    }

    public function get_cooperados_beneficiarios_cooperados_to_string()
    {
        if(!empty($this->cooperados_beneficiarios_cooperados_to_string))
        {
            return $this->cooperados_beneficiarios_cooperados_to_string;
        }
    
        $values = CooperadosBeneficiarios::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_documentacoes_documentacoes_to_string($cooperados_documentacoes_documentacoes_to_string)
    {
        if(is_array($cooperados_documentacoes_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $cooperados_documentacoes_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->cooperados_documentacoes_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_documentacoes_documentacoes_to_string = $cooperados_documentacoes_documentacoes_to_string;
        }

        $this->vdata['cooperados_documentacoes_documentacoes_to_string'] = $this->cooperados_documentacoes_documentacoes_to_string;
    }

    public function get_cooperados_documentacoes_documentacoes_to_string()
    {
        if(!empty($this->cooperados_documentacoes_documentacoes_to_string))
        {
            return $this->cooperados_documentacoes_documentacoes_to_string;
        }
    
        $values = CooperadosDocumentacoes::where('cooperados_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_cooperados_documentacoes_tipos_documentacoes_to_string($cooperados_documentacoes_tipos_documentacoes_to_string)
    {
        if(is_array($cooperados_documentacoes_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $cooperados_documentacoes_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->cooperados_documentacoes_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_documentacoes_tipos_documentacoes_to_string = $cooperados_documentacoes_tipos_documentacoes_to_string;
        }

        $this->vdata['cooperados_documentacoes_tipos_documentacoes_to_string'] = $this->cooperados_documentacoes_tipos_documentacoes_to_string;
    }

    public function get_cooperados_documentacoes_tipos_documentacoes_to_string()
    {
        if(!empty($this->cooperados_documentacoes_tipos_documentacoes_to_string))
        {
            return $this->cooperados_documentacoes_tipos_documentacoes_to_string;
        }
    
        $values = CooperadosDocumentacoes::where('cooperados_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_cooperados_documentacoes_cooperados_to_string($cooperados_documentacoes_cooperados_to_string)
    {
        if(is_array($cooperados_documentacoes_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_documentacoes_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_documentacoes_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_documentacoes_cooperados_to_string = $cooperados_documentacoes_cooperados_to_string;
        }

        $this->vdata['cooperados_documentacoes_cooperados_to_string'] = $this->cooperados_documentacoes_cooperados_to_string;
    }

    public function get_cooperados_documentacoes_cooperados_to_string()
    {
        if(!empty($this->cooperados_documentacoes_cooperados_to_string))
        {
            return $this->cooperados_documentacoes_cooperados_to_string;
        }
    
        $values = CooperadosDocumentacoes::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
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
    
        $values = CooperadosContatos::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
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
    
        $values = CooperadosContatos::where('cooperados_id', '=', $this->id)->getIndexedArray('tipos_contatos_id','{tipos_contatos->tipo_contato}');
        return implode(', ', $values);
    }

    public function set_beneficios_tipo_beneficio_to_string($beneficios_tipo_beneficio_to_string)
    {
        if(is_array($beneficios_tipo_beneficio_to_string))
        {
            $values = TiposBeneficios::where('id', 'in', $beneficios_tipo_beneficio_to_string)->getIndexedArray('tipo_beneficio', 'tipo_beneficio');
            $this->beneficios_tipo_beneficio_to_string = implode(', ', $values);
        }
        else
        {
            $this->beneficios_tipo_beneficio_to_string = $beneficios_tipo_beneficio_to_string;
        }

        $this->vdata['beneficios_tipo_beneficio_to_string'] = $this->beneficios_tipo_beneficio_to_string;
    }

    public function get_beneficios_tipo_beneficio_to_string()
    {
        if(!empty($this->beneficios_tipo_beneficio_to_string))
        {
            return $this->beneficios_tipo_beneficio_to_string;
        }
    
        $values = Beneficios::where('cooperados_id', '=', $this->id)->getIndexedArray('tipo_beneficio_id','{tipo_beneficio->tipo_beneficio}');
        return implode(', ', $values);
    }

    public function set_beneficios_cooperados_to_string($beneficios_cooperados_to_string)
    {
        if(is_array($beneficios_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $beneficios_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->beneficios_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->beneficios_cooperados_to_string = $beneficios_cooperados_to_string;
        }

        $this->vdata['beneficios_cooperados_to_string'] = $this->beneficios_cooperados_to_string;
    }

    public function get_beneficios_cooperados_to_string()
    {
        if(!empty($this->beneficios_cooperados_to_string))
        {
            return $this->beneficios_cooperados_to_string;
        }
    
        $values = Beneficios::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_secretaria_cooperados_to_string($cooperados_secretaria_cooperados_to_string)
    {
        if(is_array($cooperados_secretaria_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_secretaria_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_secretaria_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_secretaria_cooperados_to_string = $cooperados_secretaria_cooperados_to_string;
        }

        $this->vdata['cooperados_secretaria_cooperados_to_string'] = $this->cooperados_secretaria_cooperados_to_string;
    }

    public function get_cooperados_secretaria_cooperados_to_string()
    {
        if(!empty($this->cooperados_secretaria_cooperados_to_string))
        {
            return $this->cooperados_secretaria_cooperados_to_string;
        }
    
        $values = CooperadosSecretaria::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_capital_cooperados_to_string($cooperados_capital_cooperados_to_string)
    {
        if(is_array($cooperados_capital_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_capital_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_capital_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_capital_cooperados_to_string = $cooperados_capital_cooperados_to_string;
        }

        $this->vdata['cooperados_capital_cooperados_to_string'] = $this->cooperados_capital_cooperados_to_string;
    }

    public function get_cooperados_capital_cooperados_to_string()
    {
        if(!empty($this->cooperados_capital_cooperados_to_string))
        {
            return $this->cooperados_capital_cooperados_to_string;
        }
    
        $values = CooperadosCapital::where('cooperados_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_capital_log_import_capital_to_string($cooperados_capital_log_import_capital_to_string)
    {
        if(is_array($cooperados_capital_log_import_capital_to_string))
        {
            $values = LogImportCapital::where('id', 'in', $cooperados_capital_log_import_capital_to_string)->getIndexedArray('id', 'id');
            $this->cooperados_capital_log_import_capital_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_capital_log_import_capital_to_string = $cooperados_capital_log_import_capital_to_string;
        }

        $this->vdata['cooperados_capital_log_import_capital_to_string'] = $this->cooperados_capital_log_import_capital_to_string;
    }

    public function get_cooperados_capital_log_import_capital_to_string()
    {
        if(!empty($this->cooperados_capital_log_import_capital_to_string))
        {
            return $this->cooperados_capital_log_import_capital_to_string;
        }
    
        $values = CooperadosCapital::where('cooperados_id', '=', $this->id)->getIndexedArray('log_import_capital_id','{log_import_capital->id}');
        return implode(', ', $values);
    }

    
}

