<?php

class CredenciadosDocumentacoes extends TRecord
{
    const TABLENAME  = 'credenciados_documentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;
    private TiposDocumentacoes $tipos_documentacoes;
    private Documentacoes $documentacoes;

    

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
        parent::addAttribute('credenciados_id');
        parent::addAttribute('tipos_documentacoes_id');
        parent::addAttribute('documentacoes_id');
        parent::addAttribute('arquivo_path');
            
    }

    /**
     * Method set_credenciados
     * Sample of usage: $var->credenciados = $object;
     * @param $object Instance of Credenciados
     */
    public function set_credenciados(Credenciados $object)
    {
        $this->credenciados = $object;
        $this->credenciados_id = $object->id;
    }

    /**
     * Method get_credenciados
     * Sample of usage: $var->credenciados->attribute;
     * @returns Credenciados instance
     */
    public function get_credenciados()
    {
    
        // loads the associated object
        if (empty($this->credenciados))
            $this->credenciados = new Credenciados($this->credenciados_id);
    
        // returns the associated object
        return $this->credenciados;
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

    
}

