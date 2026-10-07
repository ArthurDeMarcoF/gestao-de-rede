<?php

class ExpService
{
    private const aux_database = 'databaserede';
    
    public function __construct($param)
    {
        
    }
    
    public static function obter($prm_expressao, &$prm_texto, &$prm_dm_tipo) {
        $aux_abriu = TTransaction::getDatabase() != self::aux_database ? true : false;
        if ($aux_abriu)
            TTransaction::open(self::aux_database);
            
        $aux_expr = Expressao::find($prm_expressao);
        
        if ($aux_expr) {
            $prm_texto   = $aux_expr->expressao;
            $prm_dm_tipo = $aux_expr->dm_tipo;
        }
        
        if ($aux_abriu)
            TTransaction::close();

    }
    
    public static function montar($prm_expressao, ...$prm_params) {
        return self::montarApi($prm_expressao, ...$prm_params);
    }
    
    public static function mostrar($prm_expressao, ...$prm_params) {
        self::obter($prm_expressao, $aux_texto, $aux_tipo);
        //$aux_texto = self::montar($prm_expressao, ...$prm_params);
        $aux_texto = self::montarApi($prm_expressao, ...$prm_params);
        
        if     ($aux_tipo == 'TI')
            TToast::show("info", $aux_texto, "topRight", "fas:info");
            
        elseif ($aux_tipo == 'TS')
            TToast::show("success", $aux_texto, "topRight", "fas:check");
            
        elseif ($aux_tipo == 'TW')
            TToast::show("warning", $aux_texto, "topRight", "fas:exclamation-triangle");
            
        elseif ($aux_tipo == 'TE')
            TToast::show("error", $aux_texto, "topRight", "fas:window-close");
            
        elseif ($aux_tipo == 'MI')
            new TMessage('info', $aux_texto);
            
        elseif ($aux_tipo == 'MW')
            new TMessage('warning', $aux_texto);
            
        elseif ($aux_tipo == 'ME')
            new TMessage('error', $aux_texto);
        
        elseif ($aux_tipo == 'E')
            throw new Exception($aux_texto);
            
    }
    
    public static function pergunta($prm_expressao, $prm_sim, $prm_nao, ...$prm_params) {
        
    }
    
    public static function montarApi($prm_expressao, ...$prm_params) {
        $aux_params = ['expressao' => $prm_expressao,
                       'params'    => implode(';', $prm_params)];
        
        $aux_resp = apiService::execReqParams(apiService::urlExprMonta, $aux_params);
        
        if (!empty($aux_resp))
            return $aux_resp;
        
        return null;
        
    }
    
}
