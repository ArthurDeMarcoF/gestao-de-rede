<?php

class VCooperadosCapitalTotais extends TRecord
{
    const TABLENAME  = 'v_cooperados_capital_totais';
    const PRIMARYKEY = 'cooperados_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('des');
        parent::addAttribute('ordem');
        parent::addAttribute('valor');
            
    }

    
}

