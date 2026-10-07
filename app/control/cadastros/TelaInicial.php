<?php

class TelaInicial extends TPage
{
    public function __construct($param = null)
    {
        parent::__construct();

        TTransaction::open('databaserede');

        // CONTADORES COOPERADOS
        $total_cooperados = Cooperados::where('ativo', '=', 'S')->count();
        $docs_vencidos    = CooperadosDocumentacoes::where('validade', '<', date('Y-m-d'))->count();
        $beneficiarios    = CooperadosBeneficiarios::count();

        /**
         * ANIVERSARIANTES COOPERADOS
         */
        $hoje    = date('m-d');
        $limite = date('m-d', strtotime('+7 days'));

        $criteria = new TCriteria;
        $criteria->add(new TFilter('ativo', '=', 'S'));

        if ($hoje <= $limite) {

            $criteria->add(
                new TFilter("DATE_FORMAT(data_nascimento, '%m-%d')", '>=', $hoje)
            );

            $criteria->add(
                new TFilter("DATE_FORMAT(data_nascimento, '%m-%d')", '<=', $limite)
            );

        } else {

            // Agrupa o OR para não quebrar o filtro ativo = 'S'
            $criteriaDatas = new TCriteria;

            $criteriaDatas->add(
                new TFilter(
                    "DATE_FORMAT(data_nascimento, '%m-%d')",
                    'BETWEEN',
                    [$hoje, '12-31']
                )
            );

            $criteriaDatas->add(
                new TFilter(
                    "DATE_FORMAT(data_nascimento, '%m-%d')",
                    'BETWEEN',
                    ['01-01', $limite]
                ),
                TExpression::OR_OPERATOR
            );

            $criteria->add($criteriaDatas);
        }

        $aniversariantes = Cooperados::getObjects($criteria);

        $hojeTs = strtotime(date('Y-m-d'));

        usort($aniversariantes, function ($a, $b) use ($hojeTs) {

            $proxA = strtotime(date('Y') . '-' . date('m-d', strtotime($a->data_nascimento)));
            $proxB = strtotime(date('Y') . '-' . date('m-d', strtotime($b->data_nascimento)));

            if ($proxA < $hojeTs) {
                $proxA = strtotime('+1 year', $proxA);
            }

            if ($proxB < $hojeTs) {
                $proxB = strtotime('+1 year', $proxB);
            }

            return $proxA <=> $proxB;
        });

        $html_aniversariantes = '';

        foreach ($aniversariantes as $a) {

            $prox = strtotime(date('Y') . '-' . date('m-d', strtotime($a->data_nascimento)));

            if ($prox < $hojeTs) {
                $prox = strtotime('+1 year', $prox);
            }

            $dias = ceil(($prox - $hojeTs) / 86400);

            if ($dias == 0) {
                $tempo = "<span class='cooperado-inicial-tempo tempo-hoje'>Hoje 🎉</span>";
            } elseif ($dias == 1) {
                $tempo = "<span class='cooperado-inicial-tempo tempo-amanha'>Amanhã</span>";
            } else {
                $tempo = "<span class='cooperado-inicial-tempo tempo-em'>Em {$dias} dias</span>";
            }

            $html_aniversariantes .= "
                <tr>
                    <td>{$this->formatarDataNasc($a->data_nascimento)}</td>
                    <td>{$a->nome}</td>
                    <td>{$tempo}</td>
                </tr>";
        }

        /**
         * DESFILIAÇÕES COOPERADOS
         */
        $hoje   = date('Y-m-d');
        $hojeTs = strtotime($hoje);

        $desfiliacoes = Cooperados::where('ativo', '=', 'S')
            ->where('dt_desfiliacao', '>=', $hoje)
            ->load();

        usort($desfiliacoes, function ($a, $b) {
            return strtotime($a->dt_desfiliacao) <=> strtotime($b->dt_desfiliacao);
        });

        $html_desfiliacao = '';

        foreach ($desfiliacoes as $d) {

            $dtDesfTs = strtotime($d->dt_desfiliacao);

            $dias = ceil(($dtDesfTs - $hojeTs) / 86400);

            if ($dias > 60 || $dias < 0) {
                continue;
            }

            if ($dias == 0) {
                $tempo = "<span class='cooperado-inicial-tempo tempo-urgente'>Último dia</span>";
            } elseif ($dias == 1) {
                $tempo = "<span class='cooperado-inicial-tempo tempo-amanha'>Amanhã</span>";
            } elseif ($dias <= 7) {
                $tempo = "<span class='cooperado-inicial-tempo tempo-urgente'>Em {$dias} dias</span>";
            } else {
                $tempo = "<span class='cooperado-inicial-tempo tempo-normal'>Em {$dias} dias</span>";
            }

            $html_desfiliacao .= "
                <tr>
                    <td>" . date('d/m/Y', $dtDesfTs) . "</td>
                    <td>{$d->nome}</td>
                    <td>{$tempo}</td>
                </tr>
            ";
        }

        /**
         * CONTADORES CREDENCIADOS
         */
        $total_credenciados = Credenciados::where('ativo', '=', 'S')->count();
        $docs_vencidos_cred = CredenciadosDocumentacoes::where('validade', '<', date('Y-m-d'))->count();

        $criteria = new TCriteria;
        $criteria->add(new TFilter('ativo', '=', 'S'));
        $criteria->add(
            new TFilter(
                'id',
                'IN',
                "(SELECT credenciados_id FROM credenciados_responsaveis WHERE cooperado = 'Sim')"
            )
        );

        $total_cooperados_vinc = Credenciados::count($criteria);

        /**
         * ANIVERSÁRIOS / DATA INÍCIO CREDENCIADOS
         */
        $hoje    = date('m-d');
        $limite = date('m-d', strtotime('+7 days'));

        $criteriaCredAniv = new TCriteria;
        $criteriaCredAniv->add(new TFilter('ativo', '=', 'S'));

        if ($hoje <= $limite) {

            $criteriaCredAniv->add(
                new TFilter("DATE_FORMAT(data_inicio, '%m-%d')", '>=', $hoje)
            );

            $criteriaCredAniv->add(
                new TFilter("DATE_FORMAT(data_inicio, '%m-%d')", '<=', $limite)
            );

        } else {

            // Agrupa o OR para não quebrar o filtro ativo = 'S'
            $criteriaDatasCred = new TCriteria;

            $criteriaDatasCred->add(
                new TFilter(
                    "DATE_FORMAT(data_inicio, '%m-%d')",
                    'BETWEEN',
                    [$hoje, '12-31']
                )
            );

            $criteriaDatasCred->add(
                new TFilter(
                    "DATE_FORMAT(data_inicio, '%m-%d')",
                    'BETWEEN',
                    ['01-01', $limite]
                ),
                TExpression::OR_OPERATOR
            );

            $criteriaCredAniv->add($criteriaDatasCred);
        }

        $aniversariantesCred = Credenciados::getObjects($criteriaCredAniv);

        $hojeTs = strtotime(date('Y-m-d'));

        usort($aniversariantesCred, function ($a, $b) use ($hojeTs) {

            $proxA = strtotime(date('Y') . '-' . date('m-d', strtotime($a->data_inicio)));
            $proxB = strtotime(date('Y') . '-' . date('m-d', strtotime($b->data_inicio)));

            if ($proxA < $hojeTs) {
                $proxA = strtotime('+1 year', $proxA);
            }

            if ($proxB < $hojeTs) {
                $proxB = strtotime('+1 year', $proxB);
            }

            return $proxA <=> $proxB;
        });

        $html_aniversariantes_cred = '';

        foreach ($aniversariantesCred as $c) {

            $prox = strtotime(date('Y') . '-' . date('m-d', strtotime($c->data_inicio)));

            if ($prox < $hojeTs) {
                $prox = strtotime('+1 year', $prox);
            }

            $dias = ceil(($prox - $hojeTs) / 86400);

            if ($dias == 0) {
                $tempo = "<span class='credenciado-inicial-tempo tempo-hoje'>Hoje</span>";
            } elseif ($dias == 1) {
                $tempo = "<span class='credenciado-inicial-tempo tempo-amanha'>Amanhã</span>";
            } else {
                $tempo = "<span class='credenciado-inicial-tempo tempo-em'>Em {$dias} dias</span>";
            }

            $html_aniversariantes_cred .= "
                <tr>
                    <td>" . TDate::date2br($c->data_inicio) . "</td>
                    <td>{$c->nome}</td>
                    <td>{$tempo}</td>
                </tr>";
        }

        /**
         * DESCREDENCIAMENTO CREDENCIADOS
         */
        $hoje   = date('Y-m-d');
        $hojeTs = strtotime($hoje);

        $descred = Credenciados::where('dt_descredenciamento', '>=', $hoje)->load();

        usort($descred, function ($a, $b) {
            return strtotime($a->dt_descredenciamento) <=> strtotime($b->dt_descredenciamento);
        });

        $html_descredenciamento = '';

        foreach ($descred as $c) {

            $dtDescredTs = strtotime($c->dt_descredenciamento);

            $dias = ceil(($dtDescredTs - $hojeTs) / 86400);

            if ($dias > 60 || $dias < 0) {
                continue;
            }

            if ($dias == 0) {
                $tempo = "<span class='credenciado-inicial-tempo tempo-urgente'>Último dia</span>";
            } elseif ($dias == 1) {
                $tempo = "<span class='credenciado-inicial-tempo tempo-amanha'>Amanhã</span>";
            } elseif ($dias <= 7) {
                $tempo = "<span class='credenciado-inicial-tempo tempo-urgente'>Em {$dias} dias</span>";
            } else {
                $tempo = "<span class='credenciado-inicial-tempo tempo-normal'>Em {$dias} dias</span>";
            }

            $html_descredenciamento .= "
                <tr>
                    <td>" . TDate::date2br($c->dt_descredenciamento) . "</td>
                    <td>{$c->nome}</td>
                    <td>{$tempo}</td>
                </tr>
            ";
        }

        TTransaction::close();

        /**
         * HTML COOPERADOS
         */
        $htmlCoop = new THtmlRenderer('app/resources/cooperados_tela_inicial.html');
        $htmlCoop->disableHtmlConversion(true);
        $htmlCoop->enableSection('main', [
            'total_cooperados'      => $total_cooperados,
            'docs_vencidos'         => $docs_vencidos,
            'beneficiarios'         => $beneficiarios,
            'lista_aniversariantes' => $html_aniversariantes,
            'lista_desfiliacao'     => $html_desfiliacao,
        ]);

        $panel1 = new TPanelGroup(
            "<span class='panel-title cooperados'>
                <i class='fas fa-user-md'></i> Cooperados
            </span>"
        );

        $panel1->add($htmlCoop);

        /**
         * HTML CREDENCIADOS
         */
        $htmlCred = new THtmlRenderer('app/resources/credenciados_tela_inicial.html');
        $htmlCred->disableHtmlConversion(true);
        $htmlCred->enableSection('main', [
            'total_credenciados'      => $total_credenciados,
            'docs_vencidos_cred'      => $docs_vencidos_cred,
            'cooperados_vinculados'   => $total_cooperados_vinc,
            'lista_aniversariantes'   => $html_aniversariantes_cred,
            'lista_descredenciamento' => $html_descredenciamento,
        ]);

        $panel2 = new TPanelGroup(
            "<span class='panel-title credenciados'>
                <i class='fas fa-hospital'></i> Credenciados
            </span>"
        );

        $panel2->add($htmlCred);

        /**
         * CONTAINER
         */
        $vbox = TVBox::pack($panel1, $panel2);
        $vbox->style = 'width:100%';

        parent::add($vbox);
    }

    private function formatarDataNasc($data)
    {
        $dt = new DateTime($data);
        $hoje = new DateTime();
        $idade = $dt->diff($hoje)->y;

        return $dt->format('d/m/Y') . ", {$idade} anos";
    }
}