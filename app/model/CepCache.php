<?php

class CepCache extends TRecord
{
    const TABLENAME  = 'cep_cache';
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
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('cidade');
        parent::addAttribute('bairro');
        parent::addAttribute('codigo_ibge');
        parent::addAttribute('uf');
        parent::addAttribute('cidades_id');
        parent::addAttribute('estados_id');
            
    }

    
}

