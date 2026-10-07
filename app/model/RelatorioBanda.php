<?php

class RelatorioBanda extends TRecord
{
    const TABLENAME  = 'relatorio_banda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('descricao');
        parent::addAttribute('dm_tip_banda');
        parent::addAttribute('altura');
        parent::addAttribute('sequencia');
            
    }

    
}

