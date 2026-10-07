<?php

class CooperadosDocumentacoes extends TRecord
{
    const TABLENAME  = 'cooperados_documentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Documentacoes $documentacoes;
    private TiposDocumentacoes $tipos_documentacoes;
    private Cooperados $cooperados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('emissao');
        parent::addAttribute('validade');
        parent::addAttribute('data_alerta');
        parent::addAttribute('ativo');
        parent::addAttribute('conteudo');
        parent::addAttribute('observacao');
        parent::addAttribute('entregue');
        parent::addAttribute('documentacoes_id');
        parent::addAttribute('tipos_documentacoes_id');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('path_arquivo');
            
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
     * Method set_cooperados
     * Sample of usage: $var->cooperados = $object;
     * @param $object Instance of Cooperados
     */
    public function set_cooperados(Cooperados $object)
    {
        $this->cooperados = $object;
        $this->cooperados_id = $object->id;
    }

    /**
     * Method get_cooperados
     * Sample of usage: $var->cooperados->attribute;
     * @returns Cooperados instance
     */
    public function get_cooperados()
    {
    
        // loads the associated object
        if (empty($this->cooperados))
            $this->cooperados = new Cooperados($this->cooperados_id);
    
        // returns the associated object
        return $this->cooperados;
    }

    
}

