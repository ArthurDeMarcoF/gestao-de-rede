<?php

class Job extends TRecord
{
    const TABLENAME  = 'job';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private SystemUsers $usuario_notif;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('nome');
        parent::addAttribute('dm_situacao');
        parent::addAttribute('dm_tipo');
        parent::addAttribute('intervalo');
        parent::addAttribute('periodo');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_termino');
        parent::addAttribute('request');
        parent::addAttribute('dm_tipo_req');
        parent::addAttribute('params_req');
        parent::addAttribute('dt_prox_exec');
        parent::addAttribute('usuario_notif_id');
            
    }

    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_usuario_notif(SystemUsers $object)
    {
        $this->usuario_notif = $object;
        $this->usuario_notif_id = $object->id;
    }

    /**
     * Method get_usuario_notif
     * Sample of usage: $var->usuario_notif->attribute;
     * @returns SystemUsers instance
     */
    public function get_usuario_notif()
    {
        try{
        TTransaction::openFake('permission');
        // loads the associated object
        if (empty($this->usuario_notif))
            $this->usuario_notif = new SystemUsers($this->usuario_notif_id);
        TTransaction::close();
        }catch(Exception $e){
            TTransaction::close();
        }
        // returns the associated object
        return $this->usuario_notif;
    }

    /**
     * Method getJobExecs
     */
    public function getJobExecs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('job_id', '=', $this->id));
        return JobExec::getObjects( $criteria );
    }

    public function set_job_exec_job_to_string($job_exec_job_to_string)
    {
        if(is_array($job_exec_job_to_string))
        {
            $values = Job::where('id', 'in', $job_exec_job_to_string)->getIndexedArray('nome', 'nome');
            $this->job_exec_job_to_string = implode(', ', $values);
        }
        else
        {
            $this->job_exec_job_to_string = $job_exec_job_to_string;
        }

        $this->vdata['job_exec_job_to_string'] = $this->job_exec_job_to_string;
    }

    public function get_job_exec_job_to_string()
    {
        if(!empty($this->job_exec_job_to_string))
        {
            return $this->job_exec_job_to_string;
        }
    
        $values = JobExec::where('job_id', '=', $this->id)->getIndexedArray('job_id','{job->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(JobExec::where('job_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

