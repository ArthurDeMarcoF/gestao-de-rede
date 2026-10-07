<?php

class EmailsService
{
    public function __construct($param)
    {
        
    }
    
    private static function obterValorLabel($prm_label, $prm_chave){
        $aux_label = $objeto = LabelEmail::where('label', '=', $prm_label)->first();
        if ($aux_label){
            
            $conn = TTransaction::get();
            
            $aux_exec = $conn->prepare($aux_label->script_sql);
            $ret = $aux_exec->execute([$prm_chave]);
            
        }
    }
    
    private static function obterHTMLEmail($prm_email){
        
    }
    
    public static function montarTemplate($prm_template, $prm_chave){
        $aux_resp = apiService::execReqParams(apiService::urlMontaTemplate, ['template'=>$prm_template, 'chave'=>$prm_chave]);

        $aux_dados = json_decode($aux_resp, true);

        if (isset($aux_dados['html']))
            return $aux_dados['html'];
        return null;
    }
    
    public static function criarEmail(string $prm_template,
                                      string $prm_chave,
                                      string $prm_destinatario,
                                      string $prm_assunto,
                                      array  $prm_anexos)
    {
        
    }
    
    public static function obterExpAssunto(string $prm_template) {
        TTransaction::open('databaserede');
        $aux_template = TemplatesEmail::where('codigo', '=', $prm_template)->first();
        $aux_assunto  = ExpService::montar($aux_template->expr_assunto_id);
        TTransaction::close();
        return $aux_assunto;
    }
    
    public static function obterCorpo($prm_fila) {
        return apiService::execReqParams(apiService::urlObterCorpo, ['fila' => $prm_fila]);
    }
    
    
}
