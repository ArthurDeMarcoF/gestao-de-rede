<?php

class Arquivos extends TRecord
{
    const TABLENAME  = 'arquivos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private TiposDocumentacoes $tipos_documentacoes;
    private Documentacoes $documentacoes;
    private ArquivosStatus $arquivos_status;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('nome_arquivo');
        parent::addAttribute('ano_base');
        parent::addAttribute('data_emissao');
        parent::addAttribute('tipos_documentacoes_id');
        parent::addAttribute('documentacoes_id');
        parent::addAttribute('arquivos_status_id');
            
    }

    /**
     * Method set_tipos_documentacoes
     * Sample of usage: $var->tipos_documentacoes = $object;
     * @param $object Instance of TiposDocumentacoes
     */
    public function set_tipos_documentacoes(TiposDocumentacoes $object)
    {
        $this->tipos_documentacoes = $object;
        $this->tipos_documentacoes_id = $object->id;
    }

    /**
     * Method get_tipos_documentacoes
     * Sample of usage: $var->tipos_documentacoes->attribute;
     * @returns TiposDocumentacoes instance
     */
    public function get_tipos_documentacoes()
    {
    
        // loads the associated object
        if (empty($this->tipos_documentacoes))
            $this->tipos_documentacoes = new TiposDocumentacoes($this->tipos_documentacoes_id);
    
        // returns the associated object
        return $this->tipos_documentacoes;
    }
    /**
     * Method set_documentacoes
     * Sample of usage: $var->documentacoes = $object;
     * @param $object Instance of Documentacoes
     */
    public function set_documentacoes(Documentacoes $object)
    {
        $this->documentacoes = $object;
        $this->documentacoes_id = $object->id;
    }

    /**
     * Method get_documentacoes
     * Sample of usage: $var->documentacoes->attribute;
     * @returns Documentacoes instance
     */
    public function get_documentacoes()
    {
    
        // loads the associated object
        if (empty($this->documentacoes))
            $this->documentacoes = new Documentacoes($this->documentacoes_id);
    
        // returns the associated object
        return $this->documentacoes;
    }
    /**
     * Method set_arquivos_status
     * Sample of usage: $var->arquivos_status = $object;
     * @param $object Instance of ArquivosStatus
     */
    public function set_arquivos_status(ArquivosStatus $object)
    {
        $this->arquivos_status = $object;
        $this->arquivos_status_id = $object->id;
    }

    /**
     * Method get_arquivos_status
     * Sample of usage: $var->arquivos_status->attribute;
     * @returns ArquivosStatus instance
     */
    public function get_arquivos_status()
    {
    
        // loads the associated object
        if (empty($this->arquivos_status))
            $this->arquivos_status = new ArquivosStatus($this->arquivos_status_id);
    
        // returns the associated object
        return $this->arquivos_status;
    }

    /**
     * Method getArquivosCooperados
     */
    public function getArquivosCooperados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('arquivos_id', '=', $this->id));
        return ArquivosCooperado::getObjects( $criteria );
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
    
        $values = ArquivosCooperado::where('arquivos_id', '=', $this->id)->getIndexedArray('arquivos_status_id','{arquivos_status->nome}');
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
    
        $values = ArquivosCooperado::where('arquivos_id', '=', $this->id)->getIndexedArray('cooperado_id','{cooperado->nome}');
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
    
        $values = ArquivosCooperado::where('arquivos_id', '=', $this->id)->getIndexedArray('arquivos_id','{arquivos->nome_arquivo}');
        return implode(', ', $values);
    }

    
}

