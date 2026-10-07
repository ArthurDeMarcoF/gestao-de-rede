<?php

class Acordo extends TRecord
{
    const TABLENAME  = 'acordo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Especialidades $especialidades;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('nr_jet');
        parent::addAttribute('especialidades_id');
        parent::addAttribute('medico');
        parent::addAttribute('carteirinha');
        parent::addAttribute('beneficiario');
        parent::addAttribute('valor');
        parent::addAttribute('dt_atendimento');
        parent::addAttribute('dt_nota');
        parent::addAttribute('nr_nota');
        parent::addAttribute('dt_pagamento');
        parent::addAttribute('observacao');
            
    }

    /**
     * Method set_especialidades
     * Sample of usage: $var->especialidades = $object;
     * @param $object Instance of Especialidades
     */
    public function set_especialidades(Especialidades $object)
    {
        $this->especialidades = $object;
        $this->especialidades_id = $object->id;
    }

    /**
     * Method get_especialidades
     * Sample of usage: $var->especialidades->attribute;
     * @returns Especialidades instance
     */
    public function get_especialidades()
    {
    
        // loads the associated object
        if (empty($this->especialidades))
            $this->especialidades = new Especialidades($this->especialidades_id);
    
        // returns the associated object
        return $this->especialidades;
    }

    /**
     * Method getAcordoArquivos
     */
    public function getAcordoArquivos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('acordo_id', '=', $this->id));
        return AcordoArquivo::getObjects( $criteria );
    }

    public function set_acordo_arquivo_acordo_to_string($acordo_arquivo_acordo_to_string)
    {
        if(is_array($acordo_arquivo_acordo_to_string))
        {
            $values = Acordo::where('id', 'in', $acordo_arquivo_acordo_to_string)->getIndexedArray('medico', 'medico');
            $this->acordo_arquivo_acordo_to_string = implode(', ', $values);
        }
        else
        {
            $this->acordo_arquivo_acordo_to_string = $acordo_arquivo_acordo_to_string;
        }

        $this->vdata['acordo_arquivo_acordo_to_string'] = $this->acordo_arquivo_acordo_to_string;
    }

    public function get_acordo_arquivo_acordo_to_string()
    {
        if(!empty($this->acordo_arquivo_acordo_to_string))
        {
            return $this->acordo_arquivo_acordo_to_string;
        }
    
        $values = AcordoArquivo::where('acordo_id', '=', $this->id)->getIndexedArray('acordo_id','{acordo->medico}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(AcordoArquivo::where('acordo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

