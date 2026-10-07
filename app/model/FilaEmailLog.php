<?php

class FilaEmailLog extends TRecord
{
    const TABLENAME  = 'fila_email_log';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private FilaEmail $fila_emails;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('fila_emails_id');
        parent::addAttribute('dm_status');
        parent::addAttribute('mensagem');
            
    }

    /**
     * Method set_fila_email
     * Sample of usage: $var->fila_email = $object;
     * @param $object Instance of FilaEmail
     */
    public function set_fila_emails(FilaEmail $object)
    {
        $this->fila_emails = $object;
        $this->fila_emails_id = $object->id;
    }

    /**
     * Method get_fila_emails
     * Sample of usage: $var->fila_emails->attribute;
     * @returns FilaEmail instance
     */
    public function get_fila_emails()
    {
    
        // loads the associated object
        if (empty($this->fila_emails))
            $this->fila_emails = new FilaEmail($this->fila_emails_id);
    
        // returns the associated object
        return $this->fila_emails;
    }

    
}

