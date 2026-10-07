<?php

class apiService
{
    private const apiBearer            = '309d22b95b7a8f5ebf17cbb78a08ef041b31a95732952cd41ca9be5e';
    public  const urlBase              = 'http://192.168.100.27:8080';
    
    public  const urlEmailsPend        = ['GET' , '/emails/enviar_fila_emails_pend'];
    public  const urlEnviaEmail        = ['GET' , '/emails/enviar_fila_email'];
    public  const urlCriaEmail         = ['POST', '/emails/criar_email'];
    public  const urlCriaEmailTemplate = ['POST', '/emails/criar_email_template'];
    public  const urlMontaTemplate     = ['GET' , '/emails/montar_template'];
    public  const urlObterCorpo        = ['GET' , '/emails/obter_corpo'];
    public  const urlExprTexto         = ['GET' , '/expressoes/buscar_texto'];
    public  const urlExprTipo          = ['GET' , '/expressoes/buscar_tipo'];
    public  const urlExprMonta         = ['GET' , '/expressoes/montar'];
    public  const urlExtratoGerar      = ['POST', '/extratos/gerar_docs'];
    public  const urlEnvioMassDoc      = ['POST', '/docs/envio_massa'];
    
    public function __construct($param) {
        
    }
    
    public static function execReq($prm_url, $prm_payload = null, ...$prm_params) {
        try {
            $aux_url    = self::urlBase . $prm_url[1];
            $aux_header = ["Authorization: Bearer " . self::apiBearer,];
            $aux_req    = curl_init();
            
            foreach ($prm_params as $aux_prm) {
                $aux_url = $aux_url . '/' . $aux_prm;
            }
            
            if (!empty($prm_payload)) {
                $aux_header[] = 'Content-Type: application/json';
                $aux_header[] = 'Content-Length: ' . strlen($prm_payload);
                curl_setopt($aux_req, CURLOPT_POSTFIELDS, $prm_payload);
            }
            
            if ($prm_url[0] == 'POST') 
                curl_setopt($aux_req, CURLOPT_POST, true);
            
            curl_setopt($aux_req, CURLOPT_URL, $aux_url);
            curl_setopt($aux_req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($aux_req, CURLOPT_HTTPHEADER, $aux_header);
            
    
            $aux_resp = curl_exec($aux_req);
    
            if (curl_errno($aux_req)) {
                new TMessage('error', curl_error($aux_req));
                curl_close($aux_req);
                return null;
            } else {
                curl_close($aux_req);
                return $aux_resp;
            }
        } catch (Exception $e) {
            new TMessage('error', 'Req: '.$e->getMessage());
            return null;
        }
        
    }
    
    public static function execReqParams($prm_url, $params) {
        try {
            $aux_url    = self::urlBase . $prm_url[1];
            $aux_header = ["Authorization: Bearer " . self::apiBearer,];
            $aux_req    = curl_init();
            
            $aux_url = $aux_url . '?' . http_build_query($params);
            
            curl_setopt($aux_req, CURLOPT_URL, $aux_url);
            curl_setopt($aux_req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($aux_req, CURLOPT_HTTPHEADER, $aux_header);
            if ($prm_url[0] == 'POST') 
                curl_setopt($aux_req, CURLOPT_POST, true);
    
            $aux_resp = curl_exec($aux_req);
    
            if (curl_errno($aux_req)) {
                new TMessage('error', curl_error($aux_req));
                curl_close($aux_req);
                return null;
            } else {
                curl_close($aux_req);
                return $aux_resp;
            }
        } catch (Exception $e) {
            new TMessage('error', 'Req: '.$e->getMessage());
            return null;
        }
    }
    
}
