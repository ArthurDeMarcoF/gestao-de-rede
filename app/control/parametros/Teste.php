<?php

class Teste
{
    public function __construct($param)
    {
        
    }
    
    public static function onPararJob() 
    {
        try 
        {
            self::parar('jobGestaoRede');

            //</autoCode>
        }
        catch (Exception $e) 
        {
            echo $e->getMessage();    
        }
    } 
    
    public static function parar($prm_service) {
        exec('sudo systemctl stop '.escapeshellarg($prm_service).'.service', $aux_saida, $aux_status);
        //var_dump($aux_saida);
        var_dump($aux_status);
        var_dump($aux_saida);
        //TApplication::loadPage('MonitorForm', 'onShow', []);
    }
}
