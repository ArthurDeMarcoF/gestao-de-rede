<?php

class ArquivosStatus extends TRecord
{
    const TABLENAME  = 'arquivos_status';
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
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('ativo');
        parent::addAttribute('cor');
            
    }

    /**
     * Method getArquivosCooperados
     */
    public function getArquivosCooperados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('arquivos_status_id', '=', $this->id));
        return ArquivosCooperado::getObjects( $criteria );
    }
    /**
     * Method getArquivoss
     */
    public function getArquivoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('arquivos_status_id', '=', $this->id));
        return Arquivos::getObjects( $criteria );
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
    
        $values = ArquivosCooperado::where('arquivos_status_id', '=', $this->id)->getIndexedArray('arquivos_status_id','{arquivos_status->nome}');
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
    
        $values = ArquivosCooperado::where('arquivos_status_id', '=', $this->id)->getIndexedArray('cooperado_id','{cooperado->nome}');
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
    
        $values = ArquivosCooperado::where('arquivos_status_id', '=', $this->id)->getIndexedArray('arquivos_id','{arquivos->nome_arquivo}');
        return implode(', ', $values);
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
    
        $values = Arquivos::where('arquivos_status_id', '=', $this->id)->getIndexedArray('tipos_documentacoes_id','{tipos_documentacoes->tipo_documento}');
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
    
        $values = Arquivos::where('arquivos_status_id', '=', $this->id)->getIndexedArray('documentacoes_id','{documentacoes->id}');
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
    
        $values = Arquivos::where('arquivos_status_id', '=', $this->id)->getIndexedArray('arquivos_status_id','{arquivos_status->nome}');
        return implode(', ', $values);
    }

    
}

