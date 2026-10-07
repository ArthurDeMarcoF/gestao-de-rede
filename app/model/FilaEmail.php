<?php

class FilaEmail extends TRecord
{
    const TABLENAME  = 'fila_email';
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
        parent::addAttribute('dm_status');
        parent::addAttribute('assunto');
        parent::addAttribute('destinatario');
        parent::addAttribute('corpo');
        parent::addAttribute('data_envio');
            
    }

    /**
     * Method getFilaEmailLogs
     */
    public function getFilaEmailLogs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fila_emails_id', '=', $this->id));
        return FilaEmailLog::getObjects( $criteria );
    }
    /**
     * Method getFilaEmailAnexos
     */
    public function getFilaEmailAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fila_emails_id', '=', $this->id));
        return FilaEmailAnexo::getObjects( $criteria );
    }

    public function set_fila_email_log_fila_emails_to_string($fila_email_log_fila_emails_to_string)
    {
        if(is_array($fila_email_log_fila_emails_to_string))
        {
            $values = FilaEmail::where('id', 'in', $fila_email_log_fila_emails_to_string)->getIndexedArray('assunto', 'assunto');
            $this->fila_email_log_fila_emails_to_string = implode(', ', $values);
        }
        else
        {
            $this->fila_email_log_fila_emails_to_string = $fila_email_log_fila_emails_to_string;
        }

        $this->vdata['fila_email_log_fila_emails_to_string'] = $this->fila_email_log_fila_emails_to_string;
    }

    public function get_fila_email_log_fila_emails_to_string()
    {
        if(!empty($this->fila_email_log_fila_emails_to_string))
        {
            return $this->fila_email_log_fila_emails_to_string;
        }
    
        $values = FilaEmailLog::where('fila_emails_id', '=', $this->id)->getIndexedArray('fila_emails_id','{fila_emails->assunto}');
        return implode(', ', $values);
    }

    public function set_fila_email_anexo_fila_emails_to_string($fila_email_anexo_fila_emails_to_string)
    {
        if(is_array($fila_email_anexo_fila_emails_to_string))
        {
            $values = FilaEmail::where('id', 'in', $fila_email_anexo_fila_emails_to_string)->getIndexedArray('assunto', 'assunto');
            $this->fila_email_anexo_fila_emails_to_string = implode(', ', $values);
        }
        else
        {
            $this->fila_email_anexo_fila_emails_to_string = $fila_email_anexo_fila_emails_to_string;
        }

        $this->vdata['fila_email_anexo_fila_emails_to_string'] = $this->fila_email_anexo_fila_emails_to_string;
    }

    public function get_fila_email_anexo_fila_emails_to_string()
    {
        if(!empty($this->fila_email_anexo_fila_emails_to_string))
        {
            return $this->fila_email_anexo_fila_emails_to_string;
        }
    
        $values = FilaEmailAnexo::where('fila_emails_id', '=', $this->id)->getIndexedArray('fila_emails_id','{fila_emails->assunto}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(FilaEmailLog::where('fila_emails_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(FilaEmailAnexo::where('fila_emails_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

