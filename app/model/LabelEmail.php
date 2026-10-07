<?php

class LabelEmail extends TRecord
{
    const TABLENAME  = 'label_email';
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
        parent::addAttribute('label');
        parent::addAttribute('descricao');
        parent::addAttribute('chave');
        parent::addAttribute('script_sql');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('titulo');
            
    }

    
}

