<?php

class RelatorioParametro extends TRecord
{
    const TABLENAME  = 'relatorio_parametro';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Relatorio $relatorio;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('relatorio_id');
        parent::addAttribute('sequencia');
        parent::addAttribute('codigo');
        parent::addAttribute('descricao');
        parent::addAttribute('dm_tip_atributo');
        parent::addAttribute('dm_apresentacao');
        parent::addAttribute('mascara');
        parent::addAttribute('flg_obrigatorio');
            
    }

    /**
     * Method set_relatorio
     * Sample of usage: $var->relatorio = $object;
     * @param $object Instance of Relatorio
     */
    public function set_relatorio(Relatorio $object)
    {
        $this->relatorio = $object;
        $this->relatorio_id = $object->id;
    }

    /**
     * Method get_relatorio
     * Sample of usage: $var->relatorio->attribute;
     * @returns Relatorio instance
     */
    public function get_relatorio()
    {
    
        // loads the associated object
        if (empty($this->relatorio))
            $this->relatorio = new Relatorio($this->relatorio_id);
    
        // returns the associated object
        return $this->relatorio;
    }

    
}

