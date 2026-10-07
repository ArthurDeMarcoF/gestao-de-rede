<?php

class Expressao extends TRecord
{
    const TABLENAME  = 'expressao';
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
        parent::addAttribute('expressao');
        parent::addAttribute('dm_tipo');
            
    }

    /**
     * Method getTemplatesEmails
     */
    public function getTemplatesEmails()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('expr_assunto_id', '=', $this->id));
        return TemplatesEmail::getObjects( $criteria );
    }

    public function set_templates_email_expr_assunto_to_string($templates_email_expr_assunto_to_string)
    {
        if(is_array($templates_email_expr_assunto_to_string))
        {
            $values = Expressao::where('id', 'in', $templates_email_expr_assunto_to_string)->getIndexedArray('expressao', 'expressao');
            $this->templates_email_expr_assunto_to_string = implode(', ', $values);
        }
        else
        {
            $this->templates_email_expr_assunto_to_string = $templates_email_expr_assunto_to_string;
        }

        $this->vdata['templates_email_expr_assunto_to_string'] = $this->templates_email_expr_assunto_to_string;
    }

    public function get_templates_email_expr_assunto_to_string()
    {
        if(!empty($this->templates_email_expr_assunto_to_string))
        {
            return $this->templates_email_expr_assunto_to_string;
        }
    
        $values = TemplatesEmail::where('expr_assunto_id', '=', $this->id)->getIndexedArray('expr_assunto_id','{expr_assunto->expressao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(TemplatesEmail::where('expr_assunto_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

