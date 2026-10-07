<?php

class VDominioValorCol extends TRecord
{
    const TABLENAME  = 'v_dominio_valor_col';
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
        parent::addAttribute('objeto');
        parent::addAttribute('atributo');
        parent::addAttribute('valor_padrao');
        parent::addAttribute('flg_colorir');
        parent::addAttribute('sequencia');
        parent::addAttribute('valor');
        parent::addAttribute('mascara');
        parent::addAttribute('cor_grafico');
            
    }

    
}

