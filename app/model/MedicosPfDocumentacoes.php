<?php

class MedicosPfDocumentacoes extends TRecord
{
    const TABLENAME  = 'medicos_pf_documentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MedicosPf $medicos_pf;
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
        parent::addAttribute('path_arquivo');
        parent::addAttribute('medicos_pf_id');
        parent::addAttribute('tipos_documentacoes_id');
        parent::addAttribute('documentacoes_id');
            
    }

    /**
     * Method set_medicos_pf
     * Sample of usage: $var->medicos_pf = $object;
     * @param $object Instance of MedicosPf
     */
    public function set_medicos_pf(MedicosPf $object)
    {
        $this->medicos_pf = $object;
        $this->medicos_pf_id = $object->id;
    }

    /**
     * Method get_medicos_pf
     * Sample of usage: $var->medicos_pf->attribute;
     * @returns MedicosPf instance
     */
    public function get_medicos_pf()
    {
    
        // loads the associated object
        if (empty($this->medicos_pf))
            $this->medicos_pf = new MedicosPf($this->medicos_pf_id);
    
        // returns the associated object
        return $this->medicos_pf;
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

