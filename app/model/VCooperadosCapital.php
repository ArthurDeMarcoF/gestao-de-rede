<?php

class VCooperadosCapital extends TRecord
{
    const TABLENAME  = 'v_cooperados_capital';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('linha');
        parent::addAttribute('crm');
        parent::addAttribute('nome');
        parent::addAttribute('dm_capital_social');
        parent::addAttribute('nr_parcela');
        parent::addAttribute('data_aquisicao');
        parent::addAttribute('valor');
            
    }

    
}

