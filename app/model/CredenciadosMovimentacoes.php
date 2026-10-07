<?php

class CredenciadosMovimentacoes extends TRecord
{
    const TABLENAME  = 'credenciados_movimentacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Credenciados $credenciados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('credenciados_id');
        parent::addAttribute('descricao');
        parent::addAttribute('usuario');
        parent::addAttribute('data_registro');
        parent::addAttribute('assunto');
            
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

    
}

