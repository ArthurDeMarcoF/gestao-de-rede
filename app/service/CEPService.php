<?php

class CEPService
{
    public static function get($cep)
    {
        $cep = str_replace(['-', '.'], ['', ''], $cep);

        $dadosCep = CEPCacheService::get($cep);

        if ($dadosCep) {
            return $dadosCep;
        }

        try {
            $dadosCep = BuilderCEPService::get($cep);
        } catch (Exception $e) {
            $apiError = new ApiError();
            $apiError->url = BuilderCEPService::getUrl($cep);
            $apiError->error_message = $e->getMessage();
            $apiError->inserted_at = date('Y-m-d H:i:s');
            $apiError->store();

            return null;
        }

        $dadosCep->rua = $dadosCep->tipo_logradouro . ' ' . $dadosCep->logradouro;
        $dadosCep->cep = $cep;

        $cidade = Cidades::where('cod_ibge', '=', $dadosCep->cidade_cod_ibge)->first();
        $estado = Estados::where('cod_ibge', '=', $dadosCep->estado_cod_ibge)->first();

        if ($cidade) {
            $dadosCep->cidades_id = $cidade->id;
            $dadosCep->estados_id = $cidade->estados_id;
        } else {
            if (!$estado) {
                $estado = new Estados;
                $estado->sigla = $dadosCep->uf;
                $estado->estado = $dadosCep->estado;
                $estado->cod_ibge = $dadosCep->estado_cod_ibge;
                $estado->store();
            }

            $cidade = new Cidades;
            $cidade->cidade = $dadosCep->cidade;
            $cidade->cod_ibge = $dadosCep->cidade_cod_ibge;
            $cidade->estados_id = $estado->id;
            $cidade->store();

            $dadosCep->cidades_id = $cidade->id;
            $dadosCep->estados_id = $cidade->estados_id;
        }

        CEPCacheService::save($dadosCep);

        return $dadosCep;
    }
}
