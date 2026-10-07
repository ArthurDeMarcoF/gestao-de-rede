<?php

class Relatorio extends TRecord
{
    const TABLENAME  = 'relatorio';
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
        parent::addAttribute('titulo');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('dm_papel');
        parent::addAttribute('dm_orientacao');
        parent::addAttribute('cor_fundo');
        parent::addAttribute('margem_sup');
        parent::addAttribute('margem_inf');
        parent::addAttribute('margem_esq');
        parent::addAttribute('margem_dir');
        parent::addAttribute('flg_borda_sup');
        parent::addAttribute('flg_borda_inf');
        parent::addAttribute('flg_borda_esq');
        parent::addAttribute('flg_borda_dir');
            
    }

    /**
     * Method getRelatorioParametros
     */
    public function getRelatorioParametros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('relatorio_id', '=', $this->id));
        return RelatorioParametro::getObjects( $criteria );
    }

    public function set_relatorio_parametro_relatorio_to_string($relatorio_parametro_relatorio_to_string)
    {
        if(is_array($relatorio_parametro_relatorio_to_string))
        {
            $values = Relatorio::where('id', 'in', $relatorio_parametro_relatorio_to_string)->getIndexedArray('titulo', 'titulo');
            $this->relatorio_parametro_relatorio_to_string = implode(', ', $values);
        }
        else
        {
            $this->relatorio_parametro_relatorio_to_string = $relatorio_parametro_relatorio_to_string;
        }

        $this->vdata['relatorio_parametro_relatorio_to_string'] = $this->relatorio_parametro_relatorio_to_string;
    }

    public function get_relatorio_parametro_relatorio_to_string()
    {
        if(!empty($this->relatorio_parametro_relatorio_to_string))
        {
            return $this->relatorio_parametro_relatorio_to_string;
        }
    
        $values = RelatorioParametro::where('relatorio_id', '=', $this->id)->getIndexedArray('relatorio_id','{relatorio->titulo}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(RelatorioParametro::where('relatorio_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

