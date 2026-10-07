<?php

class TiposDocumentacoes extends TRecord
{
    const TABLENAME  = 'tipos_documentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    const Alvaras = '1';
    const Identificacao = '2';
    const Termos = '3';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('tipo_documento');
            
    }

    /**
     * Method getArquivoss
     */
    public function getArquivoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return Arquivos::getObjects( $criteria );
    }
    /**
     * Method getDocumentacoess
     */
    public function getDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return Documentacoes::getObjects( $criteria );
    }
    /**
     * Method getCooperadosDocumentacoess
     */
    public function getCooperadosDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return CooperadosDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getDocumentacoesPadraoCooperadoss
     */
    public function getDocumentacoesPadraoCooperadoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return DocumentacoesPadraoCooperados::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosDocumentacoess
     */
    public function getCredenciadosDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return CredenciadosDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getDocumentacoesPadraoCredenciados
     */
    public function getDocumentacoesPadraoCredenciados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return DocumentacoesPadraoCredenciado::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfDocumentacoess
     */
    public function getMedicosPfDocumentacoess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return MedicosPfDocumentacoes::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfDocumentacoesPadraos
     */
    public function getMedicosPfDocumentacoesPadraos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipos_documentacoes_id', '=', $this->id));
        return MedicosPfDocumentacoesPadrao::getObjects( $criteria );
    }

    public function set_arquivos_tipos_documentacoes_to_string($arquivos_tipos_documentacoes_to_string)
    {
        if(is_array($arquivos_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $arquivos_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->arquivos_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_tipos_documentacoes_to_string = $arquivos_tipos_documentacoes_to_string;
        }

        $this->vdata['arquivos_tipos_documentacoes_to_string'] = $this->arquivos_tipos_documentacoes_to_string;
    }

    public function get_arquivos_tipos_documentacoes_to_string()
    {
        if(!empty($this->arquivos_tipos_documentacoes_to_string))
        {
            return $this->arquivos_tipos_documentacoes_to_string;
        }
    
        $values = Arquivos::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_arquivos_documentacoes_to_string($arquivos_documentacoes_to_string)
    {
        if(is_array($arquivos_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $arquivos_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->arquivos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_documentacoes_to_string = $arquivos_documentacoes_to_string;
        }

        $this->vdata['arquivos_documentacoes_to_string'] = $this->arquivos_documentacoes_to_string;
    }

    public function get_arquivos_documentacoes_to_string()
    {
        if(!empty($this->arquivos_documentacoes_to_string))
        {
            return $this->arquivos_documentacoes_to_string;
        }
    
        $values = Arquivos::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_arquivos_arquivos_status_to_string($arquivos_arquivos_status_to_string)
    {
        if(is_array($arquivos_arquivos_status_to_string))
        {
            $values = ArquivosStatus::where('id', 'in', $arquivos_arquivos_status_to_string)->getIndexedArray('nome', 'nome');
            $this->arquivos_arquivos_status_to_string = implode(', ', $values);
        }
        else
        {
            $this->arquivos_arquivos_status_to_string = $arquivos_arquivos_status_to_string;
        }

        $this->vdata['arquivos_arquivos_status_to_string'] = $this->arquivos_arquivos_status_to_string;
    }

    public function get_arquivos_arquivos_status_to_string()
    {
        if(!empty($this->arquivos_arquivos_status_to_string))
        {
            return $this->arquivos_arquivos_status_to_string;
        }
    
        $values = Arquivos::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('arquivos_status_id','{arquivos_status->nome}');
        return implode(', ', $values);
    }

    public function set_documentacoes_tipos_documentacoes_to_string($documentacoes_tipos_documentacoes_to_string)
    {
        if(is_array($documentacoes_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $documentacoes_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->documentacoes_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->documentacoes_tipos_documentacoes_to_string = $documentacoes_tipos_documentacoes_to_string;
        }

        $this->vdata['documentacoes_tipos_documentacoes_to_string'] = $this->documentacoes_tipos_documentacoes_to_string;
    }

    public function get_documentacoes_tipos_documentacoes_to_string()
    {
        if(!empty($this->documentacoes_tipos_documentacoes_to_string))
        {
            return $this->documentacoes_tipos_documentacoes_to_string;
        }
    
        $values = Documentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = CooperadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
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
    
        $values = CooperadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = CooperadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_documentacoes_padrao_cooperados_documentacoes_to_string($documentacoes_padrao_cooperados_documentacoes_to_string)
    {
        if(is_array($documentacoes_padrao_cooperados_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $documentacoes_padrao_cooperados_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->documentacoes_padrao_cooperados_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->documentacoes_padrao_cooperados_documentacoes_to_string = $documentacoes_padrao_cooperados_documentacoes_to_string;
        }

        $this->vdata['documentacoes_padrao_cooperados_documentacoes_to_string'] = $this->documentacoes_padrao_cooperados_documentacoes_to_string;
    }

    public function get_documentacoes_padrao_cooperados_documentacoes_to_string()
    {
        if(!empty($this->documentacoes_padrao_cooperados_documentacoes_to_string))
        {
            return $this->documentacoes_padrao_cooperados_documentacoes_to_string;
        }
    
        $values = DocumentacoesPadraoCooperados::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_documentacoes_padrao_cooperados_tipos_documentacoes_to_string($documentacoes_padrao_cooperados_tipos_documentacoes_to_string)
    {
        if(is_array($documentacoes_padrao_cooperados_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $documentacoes_padrao_cooperados_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->documentacoes_padrao_cooperados_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->documentacoes_padrao_cooperados_tipos_documentacoes_to_string = $documentacoes_padrao_cooperados_tipos_documentacoes_to_string;
        }

        $this->vdata['documentacoes_padrao_cooperados_tipos_documentacoes_to_string'] = $this->documentacoes_padrao_cooperados_tipos_documentacoes_to_string;
    }

    public function get_documentacoes_padrao_cooperados_tipos_documentacoes_to_string()
    {
        if(!empty($this->documentacoes_padrao_cooperados_tipos_documentacoes_to_string))
        {
            return $this->documentacoes_padrao_cooperados_tipos_documentacoes_to_string;
        }
    
        $values = DocumentacoesPadraoCooperados::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = CredenciadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
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
    
        $values = CredenciadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = CredenciadosDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_documentacoes_padrao_credenciado_tipos_documentacoes_to_string($documentacoes_padrao_credenciado_tipos_documentacoes_to_string)
    {
        if(is_array($documentacoes_padrao_credenciado_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $documentacoes_padrao_credenciado_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->documentacoes_padrao_credenciado_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->documentacoes_padrao_credenciado_tipos_documentacoes_to_string = $documentacoes_padrao_credenciado_tipos_documentacoes_to_string;
        }

        $this->vdata['documentacoes_padrao_credenciado_tipos_documentacoes_to_string'] = $this->documentacoes_padrao_credenciado_tipos_documentacoes_to_string;
    }

    public function get_documentacoes_padrao_credenciado_tipos_documentacoes_to_string()
    {
        if(!empty($this->documentacoes_padrao_credenciado_tipos_documentacoes_to_string))
        {
            return $this->documentacoes_padrao_credenciado_tipos_documentacoes_to_string;
        }
    
        $values = DocumentacoesPadraoCredenciado::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_documentacoes_padrao_credenciado_documentacoes_to_string($documentacoes_padrao_credenciado_documentacoes_to_string)
    {
        if(is_array($documentacoes_padrao_credenciado_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $documentacoes_padrao_credenciado_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->documentacoes_padrao_credenciado_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->documentacoes_padrao_credenciado_documentacoes_to_string = $documentacoes_padrao_credenciado_documentacoes_to_string;
        }

        $this->vdata['documentacoes_padrao_credenciado_documentacoes_to_string'] = $this->documentacoes_padrao_credenciado_documentacoes_to_string;
    }

    public function get_documentacoes_padrao_credenciado_documentacoes_to_string()
    {
        if(!empty($this->documentacoes_padrao_credenciado_documentacoes_to_string))
        {
            return $this->documentacoes_padrao_credenciado_documentacoes_to_string;
        }
    
        $values = DocumentacoesPadraoCredenciado::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
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
    
        $values = MedicosPfDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
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
    
        $values = MedicosPfDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = MedicosPfDocumentacoes::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string($medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string)
    {
        if(is_array($medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string))
        {
            $values = TiposDocumentacoes::where('id', 'in', $medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string)->getIndexedArray('tipo_documento', 'tipo_documento');
            $this->medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string = $medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string;
        }

        $this->vdata['medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string'] = $this->medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string;
    }

    public function get_medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string()
    {
        if(!empty($this->medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string))
        {
            return $this->medicos_pf_documentacoes_padrao_tipos_documentacoes_to_string;
        }
    
        $values = MedicosPfDocumentacoesPadrao::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_documentacoes_padrao_documentacoes_to_string($medicos_pf_documentacoes_padrao_documentacoes_to_string)
    {
        if(is_array($medicos_pf_documentacoes_padrao_documentacoes_to_string))
        {
            $values = Documentacoes::where('id', 'in', $medicos_pf_documentacoes_padrao_documentacoes_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_documentacoes_padrao_documentacoes_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_documentacoes_padrao_documentacoes_to_string = $medicos_pf_documentacoes_padrao_documentacoes_to_string;
        }

        $this->vdata['medicos_pf_documentacoes_padrao_documentacoes_to_string'] = $this->medicos_pf_documentacoes_padrao_documentacoes_to_string;
    }

    public function get_medicos_pf_documentacoes_padrao_documentacoes_to_string()
    {
        if(!empty($this->medicos_pf_documentacoes_padrao_documentacoes_to_string))
        {
            return $this->medicos_pf_documentacoes_padrao_documentacoes_to_string;
        }
    
        $values = MedicosPfDocumentacoesPadrao::where('tipos_documentacoes_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
        return implode(', ', $values);
    }

    
}

