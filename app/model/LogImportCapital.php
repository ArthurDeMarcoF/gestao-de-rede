<?php

class LogImportCapital extends TRecord
{
    const TABLENAME  = 'log_import_capital';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private SystemUsers $system_user;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('system_user_id');
        parent::addAttribute('nome_arq');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_termino');
            
    }

    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_user(SystemUsers $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }

    /**
     * Method get_system_user
     * Sample of usage: $var->system_user->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_user()
    {
        try{
        TTransaction::openFake('permission');
        // loads the associated object
        if (empty($this->system_user))
            $this->system_user = new SystemUsers($this->system_user_id);
        TTransaction::close();
        }catch(Exception $e){
            TTransaction::close();
        }
        // returns the associated object
        return $this->system_user;
    }

    /**
     * Method getCooperadosCapitals
     */
    public function getCooperadosCapitals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('log_import_capital_id', '=', $this->id));
        return CooperadosCapital::getObjects( $criteria );
    }

    public function set_cooperados_capital_cooperados_to_string($cooperados_capital_cooperados_to_string)
    {
        if(is_array($cooperados_capital_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_capital_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_capital_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_capital_cooperados_to_string = $cooperados_capital_cooperados_to_string;
        }

        $this->vdata['cooperados_capital_cooperados_to_string'] = $this->cooperados_capital_cooperados_to_string;
    }

    public function get_cooperados_capital_cooperados_to_string()
    {
        if(!empty($this->cooperados_capital_cooperados_to_string))
        {
            return $this->cooperados_capital_cooperados_to_string;
        }
    
        $values = CooperadosCapital::where('log_import_capital_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_capital_log_import_capital_to_string($cooperados_capital_log_import_capital_to_string)
    {
        if(is_array($cooperados_capital_log_import_capital_to_string))
        {
            $values = LogImportCapital::where('id', 'in', $cooperados_capital_log_import_capital_to_string)->getIndexedArray('id', 'id');
            $this->cooperados_capital_log_import_capital_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_capital_log_import_capital_to_string = $cooperados_capital_log_import_capital_to_string;
        }

        $this->vdata['cooperados_capital_log_import_capital_to_string'] = $this->cooperados_capital_log_import_capital_to_string;
    }

    public function get_cooperados_capital_log_import_capital_to_string()
    {
        if(!empty($this->cooperados_capital_log_import_capital_to_string))
        {
            return $this->cooperados_capital_log_import_capital_to_string;
        }
    
        $values = CooperadosCapital::where('log_import_capital_id', '=', $this->id)->getIndexedArray('log_import_capital_id','{log_import_capital->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(CooperadosCapital::where('log_import_capital_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

