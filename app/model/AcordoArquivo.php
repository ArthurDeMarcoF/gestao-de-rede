<?php

class AcordoArquivo extends TRecord
{
    const TABLENAME  = 'acordo_arquivo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Acordo $acordo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('acordo_id');
        parent::addAttribute('descricao');
        parent::addAttribute('path_arquivo');
            
    }

    /**
     * Method set_acordo
     * Sample of usage: $var->acordo = $object;
     * @param $object Instance of Acordo
     */
    public function set_acordo(Acordo $object)
    {
        $this->acordo = $object;
        $this->acordo_id = $object->id;
    }

    /**
     * Method get_acordo
     * Sample of usage: $var->acordo->attribute;
     * @returns Acordo instance
     */
    public function get_acordo()
    {
    
        // loads the associated object
        if (empty($this->acordo))
            $this->acordo = new Acordo($this->acordo_id);
    
        // returns the associated object
        return $this->acordo;
    }

    
}

