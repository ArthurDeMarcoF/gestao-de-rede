<?php

class VJob extends TRecord
{
    const TABLENAME  = 'v_job';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('dm_situacao');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('intervalo');
        parent::addAttribute('periodo');
        parent::addAttribute('dt_prox_exec');
        parent::addAttribute('dm_status_exec');
        parent::addAttribute('dt_ultim_exec');
            
    }

    
}

