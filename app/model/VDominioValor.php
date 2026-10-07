<?php

class VDominioValor extends TRecord
{
    const TABLENAME  = 'v_dominio_valor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('codigo');
        parent::addAttribute('nome');
        parent::addAttribute('sequencia');
        parent::addAttribute('valor');
        parent::addAttribute('mascara');
        parent::addAttribute('mascara_html');
        parent::addAttribute('cor_grafico');
        parent::addAttribute('flg_colorir_pad');
            
    }

    
}

