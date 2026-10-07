(function () {
    'use strict';

    var seletorPagina = '#cooperadosRelatoriosPage';
    var instanciaGlobal = '__cooperadosRelatoriosAtual';
    var promessaBiblioteca = '__cooperadosRelatoriosC3Promise';

    function numero(valor) {
        return Number(valor || 0).toLocaleString('pt-BR', {
            maximumFractionDigits: 0
        });
    }

    function escapar(valor) {
        var elemento = document.createElement('span');
        elemento.textContent = String(valor === null || valor === undefined ? '' : valor);
        return elemento.innerHTML;
    }

    function textoLimpo(valor, fallback) {
        var semTags = String(valor === null || valor === undefined ? '' : valor)
            .replace(/<[^>]*>/g, ' ');
        var decodificador = document.createElement('textarea');
        decodificador.innerHTML = semTags;

        var texto = String(decodificador.value || decodificador.textContent || '')
            .replace(/<[^>]*>/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

        return texto || fallback || '';
    }

    function nomeSituacao(nome) {
        var chave = textoLimpo(nome, '').toLocaleUpperCase('pt-BR');

        if (['ATIVO', 'ATIVOS', 'S', 'SIM', '1'].indexOf(chave) !== -1) {
            return 'Ativos';
        }

        if (['INATIVO', 'INATIVOS', 'N', 'NAO', 'NÃO', '0'].indexOf(chave) !== -1) {
            return 'Inativos';
        }

        return 'Não informado';
    }

    function nomeSexo(nome) {
        var texto = textoLimpo(nome, 'Não informado');
        var chave = texto.toLocaleUpperCase('pt-BR');

        if (chave === 'M' || chave === 'MASCULINO') {
            return 'Masculino';
        }

        if (chave === 'F' || chave === 'FEMININO') {
            return 'Feminino';
        }

        return texto;
    }

    function consolidarEntradas(entradas, normalizarNome, limite) {
        var totais = Object.create(null);
        var ordem = [];

        (Array.isArray(entradas) ? entradas : []).forEach(function (entrada) {
            var nome = textoLimpo(entrada && entrada.nome, 'Não informado');
            var total = Number(entrada && entrada.total);

            if (typeof normalizarNome === 'function') {
                nome = normalizarNome(nome);
            }

            if (!Number.isFinite(total) || total <= 0) {
                return;
            }

            if (!Object.prototype.hasOwnProperty.call(totais, nome)) {
                totais[nome] = 0;
                ordem.push(nome);
            }

            totais[nome] += total;
        });

        var consolidadas = ordem.map(function (nome) {
            return { nome: nome, total: totais[nome] };
        });

        return limite ? consolidadas.slice(0, limite) : consolidadas;
    }

    function carregarScript(src, nomeGlobal) {
        return new Promise(function (resolve, reject) {
            if (window[nomeGlobal]) {
                resolve();
                return;
            }

            var script = Array.prototype.find.call(document.scripts, function (item) {
                var caminho = item.getAttribute('src') || '';
                return caminho === src || caminho.split('?')[0] === src;
            });

            if (script && script.dataset.cooperadosRelatoriosLoading !== 'true') {
                reject(new Error('A biblioteca de gráficos existente não está disponível.'));
                return;
            }

            if (!script) {
                script = document.createElement('script');
                script.src = src;
                script.dataset.cooperadosRelatoriosLoading = 'true';
                document.head.appendChild(script);
            }

            function carregado() {
                script.dataset.cooperadosRelatoriosLoading = 'false';

                if (window[nomeGlobal]) {
                    resolve();
                } else {
                    reject(new Error('A biblioteca de gráficos não ficou disponível.'));
                }
            }

            function falhou() {
                reject(new Error('Não foi possível carregar a biblioteca de gráficos.'));
            }

            script.addEventListener('load', carregado, { once: true });
            script.addEventListener('error', falhou, { once: true });
        });
    }

    function bibliotecaGraficos() {
        if (!Array.prototype.some.call(document.styleSheets, function (folha) {
            return folha.href && /\/c3(?:\.min)?\.css(?:$|\?)/.test(folha.href);
        })) {
            var css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'app/lib/include/c3/c3.min.css';
            css.dataset.cooperadosRelatoriosC3 = 'true';
            document.head.appendChild(css);
        }

        if (window.c3 && window.d3) {
            return Promise.resolve();
        }

        if (!window[promessaBiblioteca]) {
            window[promessaBiblioteca] = (window.d3
                ? Promise.resolve()
                : carregarScript('app/lib/include/c3/d3.min.js', 'd3'))
                .then(function () {
                    return window.c3
                        ? Promise.resolve()
                        : carregarScript('app/lib/include/c3/c3.min.js', 'c3');
                })
                .catch(function (erro) {
                    window[promessaBiblioteca] = null;
                    throw erro;
                });
        }

        return window[promessaBiblioteca];
    }

    window.iniciarCooperadosRelatorios = function () {
        var raiz = document.querySelector(seletorPagina);

        if (!raiz || raiz.dataset.cooperadosRelatoriosInicializado === 'true') {
            return;
        }

        if (window[instanciaGlobal] && typeof window[instanciaGlobal].destruir === 'function') {
            window[instanciaGlobal].destruir();
        }

        raiz.dataset.cooperadosRelatoriosInicializado = 'true';

        var configElemento = raiz.querySelector('#cooperadosRelatoriosConfig');
        var config;

        try {
            config = JSON.parse(configElemento ? configElemento.textContent : '{}');
        } catch (erro) {
            config = {};
        }

        var formulario = raiz.querySelector('#form_CooperadosRelatorios');
        var estado = {
            dados: config.dados || { kpis: {}, graficos: {} },
            graficos: [],
            carregando: false,
            removido: false,
            requisicao: null,
            filtrosRelatorios: '',
            filtrosAbertos: false,
            versaoRenderizacao: 0,
            timerToast: null,
            timerResize: null
        };
        var observer;

        function mostrarMensagem(mensagem, erro) {
            var toast = raiz.querySelector('.cooperados-relatorios-toast');

            if (!toast) {
                return;
            }

            window.clearTimeout(estado.timerToast);
            toast.textContent = mensagem;
            toast.classList.toggle('is-error', !!erro);
            toast.classList.add('is-visible');
            estado.timerToast = window.setTimeout(function () {
                toast.classList.remove('is-visible');
            }, erro ? 5200 : 3500);
        }

        function definirCarregando(carregando) {
            estado.carregando = carregando;
            raiz.classList.toggle('is-loading', carregando);
            raiz.setAttribute('aria-busy', carregando ? 'true' : 'false');

            Array.prototype.forEach.call(
                raiz.querySelectorAll('[data-cr-action="aplicar"], [data-cr-action="limpar"], [data-cr-action="atualizar"]'),
                function (botao) {
                    botao.disabled = carregando;
                }
            );
        }

        function destruirGraficos() {
            estado.graficos.forEach(function (grafico) {
                if (grafico && typeof grafico.destroy === 'function') {
                    grafico.destroy();
                }
            });
            estado.graficos = [];
        }

        function vazio(container, mensagem) {
            container.innerHTML = '<div class="cooperados-relatorios-chart-empty">' +
                '<i class="fas fa-chart-bar" aria-hidden="true"></i>' +
                '<span>' + escapar(mensagem) + '</span></div>';
        }

        function cor(estilo, nome, reserva) {
            return estilo.getPropertyValue('--cooperados-relatorios-' + nome).trim() || reserva;
        }

        function desenharDonut(container, entradas, titulo, paleta, coresPorNome) {
            container.replaceChildren();

            if (!entradas.length) {
                vazio(container, 'Nenhum dado encontrado para os filtros selecionados.');
                return;
            }

            var nomes = {};
            var cores = {};
            var total = 0;
            var colunas = entradas.map(function (entrada, indice) {
                var id = 'item_' + indice;
                nomes[id] = entrada.nome;
                cores[id] = (coresPorNome && coresPorNome[entrada.nome]) || paleta[indice % paleta.length];
                total += Number(entrada.total || 0);
                return [id, Number(entrada.total || 0)];
            });

            if (!total) {
                vazio(container, 'Nenhum dado encontrado para os filtros selecionados.');
                return;
            }

            estado.graficos.push(window.c3.generate({
                bindto: container,
                size: { height: 300 },
                transition: { duration: 0 },
                data: {
                    columns: colunas,
                    names: nomes,
                    colors: cores,
                    type: 'donut',
                    order: null
                },
                donut: {
                    title: numero(total) + ' ' + titulo,
                    width: 34,
                    label: {
                        format: function (valor, proporcao) {
                            return proporcao >= .07 ? numero(valor) : '';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    contents: function (pontos) {
                        var ponto = pontos[0];
                        var percentual = total ? (ponto.value * 100 / total).toLocaleString('pt-BR', {
                            minimumFractionDigits: 1,
                            maximumFractionDigits: 1
                        }) : '0,0';

                        return '<table class="c3-tooltip"><tr><th>' + escapar(nomes[ponto.id]) +
                            '</th></tr><tr><td>' + numero(ponto.value) + ' cooperado(s) · ' +
                            percentual + '%</td></tr></table>';
                    }
                }
            }));
        }

        function desenharBarras(container, entradas, corSerie) {
            container.scrollTop = 0;
            container.replaceChildren();

            if (!entradas.length) {
                vazio(container, 'Nenhum dado encontrado para os filtros selecionados.');
                return;
            }

            var largura = container.getBoundingClientRect().width || 520;
            var maiorNome = Math.max.apply(null, entradas.map(function (entrada) {
                return String(entrada.nome || '').length;
            }));
            var margemEsquerda = Math.min(185, Math.max(112, largura * .31, maiorNome * 5.8));
            var maiorValor = Math.max.apply(null, entradas.map(function (entrada) {
                return Number(entrada.total || 0);
            }));
            var passo = Math.max(1, Math.ceil((maiorValor * 1.12) / 5));
            var maximoDoEixo = passo * 5;
            var alturaGrafico = Math.max(315, entradas.length * 42 + 75);
            var marcas = [];

            for (var valor = 0; valor <= maximoDoEixo; valor += passo) {
                marcas.push(valor);
            }

            estado.graficos.push(window.c3.generate({
                bindto: container,
                size: {
                    height: alturaGrafico
                },
                padding: {
                    top: 12,
                    right: 48,
                    bottom: 14,
                    left: margemEsquerda
                },
                transition: { duration: 0 },
                data: {
                    columns: [
                        ['total'].concat(entradas.map(function (entrada) {
                            return Number(entrada.total || 0);
                        }))
                    ],
                    names: { total: 'Cooperados' },
                    colors: { total: corSerie },
                    type: 'bar',
                    labels: {
                        format: function (valor) {
                            return numero(valor);
                        }
                    }
                },
                bar: {
                    width: { ratio: .56 }
                },
                axis: {
                    rotated: true,
                    x: {
                        type: 'category',
                        categories: entradas.map(function (entrada) {
                            return entrada.nome;
                        }),
                        tick: {
                            culling: false,
                            multiline: true,
                            width: margemEsquerda - 15
                        }
                    },
                    y: {
                        min: 0,
                        max: maximoDoEixo,
                        padding: { bottom: 0, top: 0 },
                        tick: {
                            values: marcas,
                            format: numero
                        }
                    }
                },
                legend: { show: false },
                grid: { y: { show: true } },
                tooltip: {
                    contents: function (pontos) {
                        var entrada = entradas[pontos[0].index];
                        return '<table class="c3-tooltip"><tr><th>' + escapar(entrada.nome) +
                            '</th></tr><tr><td>' + numero(entrada.total) +
                            ' cooperado(s)</td></tr></table>';
                    }
                }
            }));
        }

        function renderizarGraficos() {
            var versao = ++estado.versaoRenderizacao;

            return bibliotecaGraficos().then(function () {
                if (estado.removido || !raiz.isConnected || versao !== estado.versaoRenderizacao) {
                    return;
                }

                destruirGraficos();

                var graficos = estado.dados.graficos || {};
                var estilo = window.getComputedStyle(raiz);
                var primary = cor(estilo, 'primary', '#008f57');
                var blue = cor(estilo, 'blue', '#2878d4');
                var purple = cor(estilo, 'purple', '#7956d8');
                var orange = cor(estilo, 'orange', '#dd7a19');
                var red = cor(estilo, 'red', '#d55353');
                var neutral = cor(estilo, 'neutral', '#75817c');
                var neutralFaint = cor(estilo, 'neutral-faint', '#c4ccc8');
                var situacoes = consolidarEntradas(graficos.situacao, nomeSituacao, 3);
                var sexos = consolidarEntradas(graficos.sexo, nomeSexo);
                var especialidades = consolidarEntradas(graficos.especialidades);
                var cidades = consolidarEntradas(graficos.cidades);

                desenharDonut(
                    raiz.querySelector('#crChartSituacao'),
                    situacoes,
                    'cooperados',
                    [primary, neutral, neutralFaint],
                    {
                        'Ativos': primary,
                        'Inativos': neutral,
                        'Não informado': neutralFaint
                    }
                );
                desenharDonut(
                    raiz.querySelector('#crChartSexo'),
                    sexos,
                    'cooperados',
                    [purple, blue, primary, orange, red]
                );
                desenharBarras(
                    raiz.querySelector('#crChartEspecialidades'),
                    especialidades,
                    blue
                );
                desenharBarras(
                    raiz.querySelector('#crChartCidades'),
                    cidades,
                    primary
                );
            }).catch(function (erro) {
                if (estado.removido) {
                    return;
                }

                destruirGraficos();
                Array.prototype.forEach.call(raiz.querySelectorAll('.cooperados-relatorios-chart'), function (container) {
                    vazio(container, 'Não foi possível carregar os gráficos.');
                });
                mostrarMensagem(erro.message, true);
            });
        }

        function aplicarDados(dados) {
            estado.dados = dados;

            ['total', 'ativos', 'inativos', 'novos'].forEach(function (campo) {
                var elemento = raiz.querySelector('[data-cr-kpi="' + campo + '"]');

                if (elemento) {
                    elemento.textContent = numero(dados.kpis && dados.kpis[campo]);
                }
            });

            var periodo = raiz.querySelector('[data-cr-periodo]');
            var atualizado = raiz.querySelector('[data-cr-atualizado]');

            if (periodo) {
                periodo.textContent = dados.kpis.periodo_novos || '';
            }

            if (atualizado) {
                atualizado.textContent = dados.atualizado_em || '';
            }

            return renderizarGraficos();
        }

        function filtrosValidos() {
            var intervalos = [
                ['data_filiacao_inicio', 'data_filiacao_fim', 'filiação'],
                ['dt_desfiliacao_inicio', 'dt_desfiliacao_fim', 'desfiliação'],
                ['vigente_inicial', 'vigente_final', 'vigência']
            ];

            for (var indice = 0; indice < intervalos.length; indice += 1) {
                var intervalo = intervalos[indice];
                var inicio = formulario.querySelector('[name="' + intervalo[0] + '"]').value;
                var fim = formulario.querySelector('[name="' + intervalo[1] + '"]').value;

                if (inicio && fim && inicio > fim) {
                    mostrarMensagem('A data inicial de ' + intervalo[2] + ' não pode ser posterior à data final.', true);
                    return false;
                }
            }

            return true;
        }

        function definirFiltrosAbertos(abertos) {
            var secao = raiz.querySelector('.cooperados-relatorios-filters');
            var botao = raiz.querySelector('[data-cr-action="toggle-filters"]');
            var painel = raiz.querySelector('#cooperadosRelatoriosFiltrosPainel');
            var rotulo = raiz.querySelector('[data-cr-filter-toggle-label]');
            var chevron = raiz.querySelector('[data-cr-filter-chevron]');

            estado.filtrosAbertos = !!abertos;

            if (secao) {
                secao.classList.toggle('is-expanded', estado.filtrosAbertos);
            }

            if (botao) {
                botao.setAttribute('aria-expanded', estado.filtrosAbertos ? 'true' : 'false');
            }

            if (painel) {
                painel.setAttribute('aria-hidden', estado.filtrosAbertos ? 'false' : 'true');
            }

            if (rotulo) {
                rotulo.textContent = estado.filtrosAbertos ? 'Ocultar filtros' : 'Filtros';
            }

            if (chevron) {
                chevron.classList.toggle('fa-chevron-down', !estado.filtrosAbertos);
                chevron.classList.toggle('fa-chevron-up', estado.filtrosAbertos);
            }
        }

        function contarFiltrosAtivos(parametros) {
            var filtrosAtivos = Object.create(null);

            parametros.forEach(function (valor, chave) {
                if (String(valor).trim() !== '') {
                    filtrosAtivos[chave] = true;
                }
            });

            return Object.keys(filtrosAtivos).length;
        }

        function atualizarBadgeFiltros(parametros) {
            var quantidade = contarFiltrosAtivos(parametros);
            var secao = raiz.querySelector('.cooperados-relatorios-filters');
            var badge = raiz.querySelector('[data-cr-filter-count]');

            if (secao) {
                secao.classList.toggle('has-active-filters', quantidade > 0);
            }

            if (badge) {
                badge.textContent = String(quantidade);
                badge.hidden = quantidade === 0;
            }
        }

        function guardarFiltrosRelatorios(parametros) {
            estado.filtrosRelatorios = parametros.toString();
            atualizarBadgeFiltros(parametros);

            raiz.querySelectorAll('[data-cr-report]').forEach(function (botao) {
                botao.dataset.crFilters = estado.filtrosRelatorios;
            });
        }

        function atualizar() {
            if (estado.carregando || !filtrosValidos()) {
                return;
            }

            definirCarregando(true);
            estado.requisicao = new AbortController();

            var parametros = new URLSearchParams(new FormData(formulario));

            fetch('engine.php?class=CooperadosRelatorios&method=onAtualizarAjax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: parametros.toString(),
                signal: estado.requisicao.signal
            }).then(function (resposta) {
                return resposta.text().then(function (texto) {
                    var payload;

                    try {
                        payload = JSON.parse(texto);
                    } catch (erro) {
                        throw new Error('A resposta do servidor não pôde ser processada.');
                    }

                    if (!resposta.ok || !payload.sucesso) {
                        throw new Error(payload.erro || 'Não foi possível atualizar os indicadores.');
                    }

                    return payload.dados;
                });
            }).then(function (dados) {
                guardarFiltrosRelatorios(parametros);
                return aplicarDados(dados);
            }).then(function () {
                mostrarMensagem('Indicadores atualizados.');
            }).catch(function (erro) {
                if (erro.name !== 'AbortError' && !estado.removido) {
                    mostrarMensagem(erro.message, true);
                }
            }).finally(function () {
                estado.requisicao = null;

                if (!estado.removido) {
                    definirCarregando(false);
                }
            });
        }

        function limparFiltros() {
            if (estado.carregando) {
                return;
            }

            formulario.reset();

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(formulario).find('.cooperados-relatorios-searchable').val('').trigger('change');
            }

            atualizar();
        }

        function aoClicar(evento) {
            var botaoAcao = evento.target.closest('[data-cr-action]');
            var botaoRelatorio = evento.target.closest('[data-cr-report]');

            if (botaoAcao && raiz.contains(botaoAcao)) {
                var acao = botaoAcao.getAttribute('data-cr-action');

                if (acao === 'limpar') {
                    limparFiltros();
                } else if (acao === 'toggle-filters') {
                    definirFiltrosAbertos(!estado.filtrosAbertos);
                }
            }

            if (botaoRelatorio && raiz.contains(botaoRelatorio)) {
                mostrarMensagem('Relatório ainda não disponível. A interface está preparada para a próxima etapa.');
            }
        }

        function aoEnviar(evento) {
            evento.preventDefault();
            atualizar();
        }

        function aoRedimensionar() {
            window.clearTimeout(estado.timerResize);
            estado.timerResize = window.setTimeout(function () {
                if (!estado.removido && raiz.isConnected) {
                    renderizarGraficos();
                }
            }, 180);
        }

        function iniciarCombos() {
            if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
                return;
            }

            window.jQuery(raiz).find('.cooperados-relatorios-searchable').each(function () {
                var combo = window.jQuery(this);

                if (combo.hasClass('select2-hidden-accessible')) {
                    return;
                }

                combo.select2({
                    width: '100%',
                    allowClear: true,
                    dropdownParent: window.jQuery(raiz),
                    placeholder: combo.data('placeholder') || 'Selecione',
                    language: {
                        noResults: function () {
                            return 'Nenhum resultado encontrado';
                        },
                        searching: function () {
                            return 'Buscando...';
                        }
                    }
                });
            });
        }

        function destruir() {
            if (estado.removido) {
                return;
            }

            estado.removido = true;
            estado.versaoRenderizacao += 1;

            if (estado.requisicao) {
                estado.requisicao.abort();
            }

            if (observer) {
                observer.disconnect();
            }

            window.clearTimeout(estado.timerToast);
            window.clearTimeout(estado.timerResize);
            window.removeEventListener('resize', aoRedimensionar);
            raiz.removeEventListener('click', aoClicar);
            formulario.removeEventListener('submit', aoEnviar);
            destruirGraficos();

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(raiz).find('.cooperados-relatorios-searchable.select2-hidden-accessible').select2('destroy');
            }

            if (window[instanciaGlobal] && window[instanciaGlobal].raiz === raiz) {
                window[instanciaGlobal] = null;
            }
        }

        raiz.addEventListener('click', aoClicar);
        formulario.addEventListener('submit', aoEnviar);
        window.addEventListener('resize', aoRedimensionar);
        iniciarCombos();
        guardarFiltrosRelatorios(new URLSearchParams(new FormData(formulario)));
        definirFiltrosAbertos(false);

        observer = new MutationObserver(function () {
            if (!raiz.isConnected) {
                destruir();
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });

        window[instanciaGlobal] = {
            raiz: raiz,
            destruir: destruir,
            obterFiltrosRelatorios: function () {
                return estado.filtrosRelatorios;
            }
        };

        renderizarGraficos();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.iniciarCooperadosRelatorios, { once: true });
    } else {
        window.setTimeout(window.iniciarCooperadosRelatorios, 0);
    }
}());
