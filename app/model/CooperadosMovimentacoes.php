<?php

class CooperadosMovimentacoes extends TRecord
{
    const TABLENAME  = 'cooperados_movimentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Cooperados $cooperados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('descricao');
        parent::addAttribute('usuario');
        parent::addAttribute('data_registro');
        parent::addAttribute('assunto');
            
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

