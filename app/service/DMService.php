<?php

class DMService
{
    private static $aux_abriu = null;
    private static $aux_database = 'databaserede';
    
    public function __construct($param)
    {
        $aux_dmval = TSession::getValue('DominioValor');
        $aux_dmobj = TSession::getValue('DominioRelac');
        
        if (is_null($aux_dmval))
            TSession::setValue('DominioValor', []);
            
        if (is_null($aux_dmobj))
            TSession::setValue('DominioRelac', []);
    }
    
    public static function obterDominioRelac(string $prm_objeto, 
                                             string $prm_atributo): ?string
    {
        $aux_relac = DominioRelacionamento::where('objeto'  , '=', $prm_objeto)
                                          ->where('atributo', '=', $prm_atributo)
                                          ->first();
        if ($aux_relac){
            $aux_dm = Dominio::find($aux_relac->dominio_id);
            if ($aux_dm)
                return $aux_dm->codigo;
            return null;
        }
        return null;
    }
    
    public static function obterMask(string $prm_dominio, 
                                     ?string $prm_valor,
                                     bool   $prm_html = true): ?string
    {
        $aux_sessao = TSession::getValue('DominioValor');
        $aux_chave  = $prm_dominio.'#'.$prm_valor;
        
        if (empty($prm_valor))
            return null;
        
        if (empty($aux_sessao) || !isset($aux_sessao[$aux_chave])){
            self::abreTransact();
            $aux_valor = VDominioValor::where('codigo', '=', $prm_dominio)
                                      ->where('valor' , '=', $prm_valor)
                                      ->first();
            self::fechaTransact();
            if ($aux_valor){
                $aux_sessao[$aux_chave] = $aux_valor;
                TSession::setValue('DominioValor', $aux_sessao);
                if ($prm_html)
                    return $aux_valor->mascara_html;
                return $aux_valor->mascara;
            }
            return ExpService::montar(30, $prm_valor, $prm_dominio); // vl: {$1} dm: {$2} não encontrado!
        }
        $aux_valor = $aux_sessao[$aux_chave];
        if ($prm_html) 
            return $aux_valor->mascara_html;
        return $aux_valor->mascara;
    }
    
    public static function obterMaskCol(string $prm_objeto, 
                                        string $prm_atributo,
                                        string $prm_valor,
                                        bool   $prm_html = true): ?string 
    {
        return self::obterMask(self::obterDominioRelac($prm_objeto, $prm_atributo), $prm_valor, $prm_html);
    }
    
    public static function obterValPadrao(string $prm_objeto, string $prm_atributo): ?string{
        self::abreTransact();
        $aux_obj = VDominioValorCol::where('objeto'  , '=', $prm_objeto)
                                   ->where('atributo', '=', $prm_atributo)
                                   ->first();
        self::fechaTransact();
        if ($aux_obj)
            return $aux_obj->valor_padrao;
        return null;
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
    
    public static function montarListHTML($prm_dominio) {
        self::abreTransact();
        
        $aux_vals = VDominioValor::where('codigo', '=', $prm_dominio)
                                 ->orderBy('sequencia')
                                 ->load();
        self::fechaTransact();
        
        $aux_html = '<ol style="font-size:12px;">'.chr(13);
        foreach ($aux_vals as $aux_val) {
            if ($aux_val->flg_colorir_pad == 'S')
                $aux_html = $aux_html . '<li value="' . $aux_val->valor . '">' . $aux_val->mascara_html . '</li>'.chr(13);
            else
                $aux_html = $aux_html . '<li value="' . $aux_val->valor . '">' . $aux_val->mascara . '</li>'.chr(13);
        }
        
        $aux_html = $aux_html . '</ol>'.chr(13);
        
        
        return $aux_html;
    }
    
    public static function eliminaSessao() {
        TSession::setValue('DominioValor', []);
        TSession::setValue('DominioRelac', []);
    }
    
}
