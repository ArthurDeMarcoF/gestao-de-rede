<?php

class CooperadosDadosBancarios extends TRecord
{
    const TABLENAME  = 'cooperados_dados_bancarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Bancos $banco;
    private Cooperados $cooperados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('banco_id');
        parent::addAttribute('conta_descricao');
        parent::addAttribute('agencia');
        parent::addAttribute('conta');
        parent::addAttribute('cooperados_id');
        parent::addAttribute('ativo');
        parent::addAttribute('desativado');
        parent::addAttribute('observacao');
            
    }

    /**
     * Method set_bancos
     * Sample of usage: $var->bancos = $object;
     * @param $object Instance of Bancos
     */
    public function set_banco(Bancos $object)
    {
        $this->banco = $object;
        $this->banco_id = $object->id;
    }

    /**
     * Method get_banco
     * Sample of usage: $var->banco->attribute;
     * @returns Bancos instance
     */
    public function get_banco()
    {
    
        // loads the associated object
        if (empty($this->banco))
            $this->banco = new Bancos($this->banco_id);
    
        // returns the associated object
        return $this->banco;
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

