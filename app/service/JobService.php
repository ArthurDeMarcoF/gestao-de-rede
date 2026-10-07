<?php

class JobService
{
    private static $aux_database = 'databaserede';
    
    public function __construct($param) {
        
    }
    
    private static function obterReqJob($prm_job):?string {
        $aux_abriu = TTransaction::getDatabase() != self::$aux_database ? true : false;
        if ($aux_abriu)
            TTransaction::open(self::$aux_database);
        
        $aux_job = Job::find($prm_job);
        
        if ($aux_abriu)
            TTransaction::close();
        
        if ($aux_job) {
            return $aux_job->request;
        }
        return null;
    }
    
    public static function execJob($prm_job) {
        $aux_abriu = TTransaction::getDatabase() != self::$aux_database ? true : false;
        if ($aux_abriu)
            TTransaction::open(self::$aux_database);
        
        Job::where('id', '=', $prm_job)
           ->set('dt_prox_exec', date('Y-m-d H:i:s'))
           ->update();
            
        ExpService::mostrar(1); /* Job agendado para execução imediata! */
        
        if ($aux_abriu)
            TTransaction::close();
    }
    
    public static function criarJobUnico($prm_nome, $prm_url, $prm_params) {
        $aux_abriu = TTransaction::getDatabase() != self::$aux_database ? true : false;
        if ($aux_abriu)
            TTransaction::open(self::$aux_database);
        
        $aux_url = apiService::urlBase . $prm_url[1] . '?' . http_build_query($prm_params);
        
        $aux_job = new Job();
        $aux_job->nome             = $prm_nome;
        $aux_job->dm_situacao      = 'A';
        $aux_job->dm_tipo          = 'U';
        $aux_job->dt_inicio        = date('Y-m-d H:i:s');
        $aux_job->request          = $aux_url;
        $aux_job->dm_tipo_req      = $prm_url[0];
        $aux_job->dt_prox_exec     = date('Y-m-d H:i:s');
        $aux_job->usuario_notif_id = TSession::getValue('userid');
        $aux_job->store();
        
        ExpService::mostrar(13); /* O processo foi agendado para execução em segundo plano. Você receberá uma notificação do progresso! */
        
        if ($aux_abriu)
            TTransaction::close();
    }
}
