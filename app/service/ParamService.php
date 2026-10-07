<?php

class ParamService
{
    private const  aux_sess_key  = 'Param';
    private static $aux_abriu    = null;
    private static $aux_database = 'databaserede';
    
    public function __construct($param)
    {
        $aux_param = TSession::getValue(self::aux_sess_key);
        
        if (is_null($aux_dmval))
            TSession::setValue(self::aux_sess_key, []);
    }
    
    private static function dados($prm_parametro,
                                  &$prm_nome,
                                  &$prm_desc,
                                  &$prm_dm_tipo,
                                  &$prm_dominio,
                                  &$prm_separador,
                                  &$prm_valor)
    {
        $aux_sessao = TSession::getValue(self::aux_sess_key);
        $aux_dados  = null;
        $aux_cd_dm = null;
        if (empty($aux_sessao) || !isset($aux_sessao[$prm_parametro])) {
            self::abreTransact();
            $aux_param = Parametro::where('codigo', '=', $prm_parametro)
                                  ->first();
                                  
            if ($aux_param) {
                if (!empty($aux_param->dominio_id)) {
                    $aux_dominio = Dominio::find($aux_param->dominio_id);
                    if ($aux_dominio) {
                        $aux_cd_dm = $aux_dominio->codigo;
                    }
                }
            
                $aux_objeto = new stdClass();
                $aux_objeto->codigo    = $prm_parametro;
                $aux_objeto->nome      = $aux_param->nome;
                $aux_objeto->desc      = $aux_param->descricao;
                $aux_objeto->dm_tipo   = $aux_param->dm_tipo_param;
                $aux_objeto->dominio   = $aux_cd_dm;
                $aux_objeto->separador = $aux_param->separador;
                $aux_objeto->valor     = $aux_param->valor;
                
                $aux_sessao[$prm_parametro] = $aux_objeto;
                
                TSession::setValue(self::aux_sess_key, $aux_sessao);
                
                $aux_dados = $aux_objeto;
                
            } else {
                self::fechaTransact();
                ExpService::mostrar(22, $prm_parametro); /* Exception - Parâmetro {$1} não localizado! */
            }
            self::fechaTransact();
        } else {
            $aux_dados = $aux_sessao[$prm_parametro];
        }
        
        $prm_nome      = $aux_dados->nome;
        $prm_desc      = $aux_dados->desc;
        $prm_dm_tipo   = $aux_dados->dm_tipo;
        $prm_dominio   = $aux_dados->dominio;
        $prm_separador = $aux_dados->separador;
        $prm_valor     = $aux_dados->valor;
        
    }
    
    public static function valor($prm_parametro) {
        self::dados($prm_parametro,
                    $aux_nome,
                    $aux_desc,
                    $aux_dm_tipo,
                    $aux_dominio,
                    $aux_sep,
                    $aux_valor);
        
        if (in_array($aux_dm_tipo, array('L', 'LDM')))
            return explode($aux_sep, $aux_valor);
        else 
            return $aux_valor;
    }
    
    public static function mask($prm_parametro) {
        self::dados($prm_parametro,
                    $aux_nome,
                    $aux_desc,
                    $aux_dm_tipo,
                    $aux_dominio,
                    $aux_sep,
                    $aux_valor);
        
        if (in_array($aux_dm_tipo, array('L', 'DM', 'LDM'))) {
            if (in_array($aux_dm_tipo, array('L', 'LDM'))) {
                $aux_valor = explode($aux_sep, $aux_valor);
                if ($aux_dm_tipo == 'LDM') {
                    foreach ($aux_valor as $aux_val) {
                        $aux_mask[] = DMService::obterMask($aux_dominio, $aux_val);
                    }
                    return implode('<br>', $aux_mask);
                }
                return implode('<br>', $aux_valor);
            }
            return DMService::obterMask($aux_dominio, $aux_valor);
        }
        return $aux_valor;
    }
    
    public static function eliminaSessao($prm_parametro) {
        $aux_sessao = TSession::getValue(self::aux_sess_key);
        unset($aux_sessao[$prm_parametro]);
        TSession::setValue(self::aux_sess_key, $aux_sessao);
    }
    
    private static function abreTransact(){
        static::$aux_abriu = TTransaction::getDatabase() != self::$aux_database ? true : false;
        if (static::$aux_abriu)
            TTransaction::open(self::$aux_database);
    }
    
    private static function fechaTransact(){
        if (static::$aux_abriu){
            TTransaction::close();
            static::$aux_abriu = null;
        }
    }
    
}
