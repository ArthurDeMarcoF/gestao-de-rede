<?php

class MedicosPfMovimentacoes extends TRecord
{
    const TABLENAME  = 'medicos_pf_movimentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private MedicosPf $medicos_pf;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('medicos_pf_id');
        parent::addAttribute('descricao');
        parent::addAttribute('usuario');
        parent::addAttribute('data_registro');
        parent::addAttribute('assunto');
            
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

    
}

