<?php

class MedicosPfDadosBancarios extends TRecord
{
    const TABLENAME  = 'medicos_pf_dados_bancarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private MedicosPf $medicos_pf;
    private Bancos $bancos;

    

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
        parent::addAttribute('medicos_pf_id');
        parent::addAttribute('bancos_id');
            
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

    
}

