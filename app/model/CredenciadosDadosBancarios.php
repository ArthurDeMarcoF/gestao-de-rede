<?php

class CredenciadosDadosBancarios extends TRecord
{
    const TABLENAME  = 'credenciados_dados_bancarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Bancos $bancos;
    private Credenciados $credenciados;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('conta_descricao');
        parent::addAttribute('agencia');
        parent::addAttribute('conta');
        parent::addAttribute('ativo');
        parent::addAttribute('desativado');
        parent::addAttribute('observacao');
        parent::addAttribute('bancos_id');
        parent::addAttribute('credenciados_id');
            
    }

    /**
     * Method set_bancos
     * Sample of usage: $var->bancos = $object;
     * @param $object Instance of Bancos
     */
    public function set_bancos(Bancos $object)
    {
        $this->bancos = $object;
        $this->bancos_id = $object->id;
    }

    /**
     * Method get_bancos
     * Sample of usage: $var->bancos->attribute;
     * @returns Bancos instance
     */
    public function get_bancos()
    {
    
        // loads the associated object
        if (empty($this->bancos))
            $this->bancos = new Bancos($this->bancos_id);
    
        // returns the associated object
        return $this->bancos;
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

