<?php

class JobExec extends TRecord
{
    const TABLENAME  = 'job_exec';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    private Job $job;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('job_id');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_termino');
        parent::addAttribute('dm_status');
        parent::addAttribute('mensagem');
            
    }

    /**
     * Method set_job
     * Sample of usage: $var->job = $object;
     * @param $object Instance of Job
     */
    public function set_job(Job $object)
    {
        $this->job = $object;
        $this->job_id = $object->id;
    }

    /**
     * Method get_job
     * Sample of usage: $var->job->attribute;
     * @returns Job instance
     */
    public function get_job()
    {
    
        // loads the associated object
        if (empty($this->job))
            $this->job = new Job($this->job_id);
    
        // returns the associated object
        return $this->job;
    }

    
}

