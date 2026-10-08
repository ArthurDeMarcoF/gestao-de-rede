(function () {
    'use strict';

    var seletorPagina = '#credenciadosRelatoriosPage';
    var instanciaGlobal = '__credenciadosRelatoriosAtual';
    var promessaBiblioteca = '__credenciadosRelatoriosC3Promise';

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

    function nomeTipo(nome) {
        return textoLimpo(nome, 'Não informado');
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

            if (script && script.dataset.credenciadosRelatoriosLoading !== 'true') {
                reject(new Error('A biblioteca de gráficos existente não está disponível.'));
                return;
            }

            if (!script) {
                script = document.createElement('script');
                script.src = src;
                script.dataset.credenciadosRelatoriosLoading = 'true';
                document.head.appendChild(script);
            }

            function carregado() {
                script.dataset.credenciadosRelatoriosLoading = 'false';

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
            css.dataset.credenciadosRelatoriosC3 = 'true';
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

    window.iniciarCredenciadosRelatorios = function () {
        var raiz = document.querySelector(seletorPagina);

        if (!raiz || raiz.dataset.credenciadosRelatoriosInicializado === 'true') {
            return;
        }

        if (window[instanciaGlobal] && typeof window[instanciaGlobal].destruir === 'function') {
            window[instanciaGlobal].destruir();
        }

        raiz.dataset.credenciadosRelatoriosInicializado = 'true';

        var configElemento = raiz.querySelector('#credenciadosRelatoriosConfig');
        var config;

        try {
            config = JSON.parse(configElemento ? configElemento.textContent : '{}');
        } catch (erro) {
            config = {};
        }

        var formulario = raiz.querySelector('#form_CredenciadosRelatorios');
        var configuracoesRelatorios = {
            credenciados: {
                titulo: 'Relatório de Credenciados',
                kicker: 'RELATÓRIO CADASTRAL',
                icone: 'fas fa-users',
                singular: 'credenciado encontrado',
                plural: 'credenciados encontrados',
                vazio: 'Nenhum credenciado encontrado para os filtros selecionados.',
                carregando: 'Carregando relatório de credenciados...',
                endpointConsulta: 'onRelatorioCredenciadosAjax',
                endpointExcel: 'onExportarCredenciadosExcel',
                arquivoPadrao: 'relatorio_credenciados.xlsx'
            },
            telefones: {
                titulo: 'Relatório de Telefones',
                kicker: 'CONTATOS DOS CREDENCIADOS',
                icone: 'fas fa-phone',
                singular: 'telefone encontrado',
                plural: 'telefones encontrados',
                vazio: 'Nenhum telefone encontrado para os filtros selecionados.',
                carregando: 'Carregando relatório de telefones...',
                endpointConsulta: 'onRelatorioContatosAjax',
                endpointExcel: 'onExportarContatosExcel',
                arquivoPadrao: 'relatorio_telefones.xlsx'
            },
            emails: {
                titulo: 'Relatório de E-mails',
                kicker: 'CONTATOS DOS CREDENCIADOS',
                icone: 'fas fa-envelope',
                singular: 'e-mail encontrado',
                plural: 'e-mails encontrados',
                vazio: 'Nenhum e-mail encontrado para os filtros selecionados.',
                carregando: 'Carregando relatório de e-mails...',
                endpointConsulta: 'onRelatorioContatosAjax',
                endpointExcel: 'onExportarContatosExcel',
                arquivoPadrao: 'relatorio_emails.xlsx'
            }
        };
        var estado = {
            dados: config.dados || { kpis: {}, graficos: {} },
            graficos: [],
            carregando: false,
            exportandoExcel: false,
            removido: false,
            requisicao: null,
            requisicaoExcel: null,
            filtrosRelatorios: '',
            filtrosAbertos: false,
            relatorioAtivo: 'credenciados',
            ultimoFocoRelatorio: null,
            versaoRenderizacao: 0,
            timerToast: null,
            timerResize: null
        };
        var observer;

        function mostrarMensagem(mensagem, erro) {
            var toast = raiz.querySelector('.credenciados-relatorios-toast');

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

        function definirCarregando(carregando, mensagem, carregamentoRelatorio) {
            estado.carregando = carregando;
            raiz.classList.toggle('is-loading', carregando);
            raiz.classList.toggle('is-report-loading', carregando && !!carregamentoRelatorio);
            raiz.setAttribute('aria-busy', carregando ? 'true' : 'false');

            var textoCarregamento = raiz.querySelector('[data-cr-loading-text]');

            if (textoCarregamento) {
                textoCarregamento.textContent = mensagem || 'Atualizando indicadores...';
            }

            Array.prototype.forEach.call(
                raiz.querySelectorAll('[data-cr-action="aplicar"], [data-cr-action="limpar"], [data-cr-report]'),
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
            container.innerHTML = '<div class="credenciados-relatorios-chart-empty">' +
                '<i class="fas fa-chart-bar" aria-hidden="true"></i>' +
                '<span>' + escapar(mensagem) + '</span></div>';
        }

        function cor(estilo, nome, reserva) {
            return estilo.getPropertyValue('--credenciados-relatorios-' + nome).trim() || reserva;
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
                            '</th></tr><tr><td>' + numero(ponto.value) + ' credenciado(s) · ' +
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
                    names: { total: 'Credenciados' },
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
                            ' credenciado(s)</td></tr></table>';
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
                var tipos = consolidarEntradas(graficos.tipo, nomeTipo);
                var especialidades = consolidarEntradas(graficos.especialidades);
                var cidades = consolidarEntradas(graficos.cidades);

                desenharDonut(
                    raiz.querySelector('#crChartSituacao'),
                    situacoes,
                    'credenciados',
                    [primary, neutral, neutralFaint],
                    {
                        'Ativos': primary,
                        'Inativos': neutral,
                        'Não informado': neutralFaint
                    }
                );
                desenharDonut(
                    raiz.querySelector('#crChartTipo'),
                    tipos,
                    'credenciados',
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
                Array.prototype.forEach.call(raiz.querySelectorAll('.credenciados-relatorios-chart'), function (container) {
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
                ['data_inicio_inicio', 'data_inicio_fim', 'credenciamento'],
                ['data_contrato_inicio', 'data_contrato_fim', 'contrato'],
                ['dt_descredenciamento_inicio', 'dt_descredenciamento_fim', 'descredenciamento'],
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
            var secao = raiz.querySelector('.credenciados-relatorios-filters');
            var botao = raiz.querySelector('[data-cr-action="toggle-filters"]');
            var painel = raiz.querySelector('#credenciadosRelatoriosFiltrosPainel');
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
            var secao = raiz.querySelector('.credenciados-relatorios-filters');
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

        function fecharRelatorioCredenciados() {
            var modal = raiz.querySelector('[data-cr-credenciados-modal]');

            if (!modal || modal.hidden) {
                return;
            }

            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('credenciados-relatorios-modal-open');

            if (estado.ultimoFocoRelatorio && typeof estado.ultimoFocoRelatorio.focus === 'function') {
                estado.ultimoFocoRelatorio.focus();
            }

            estado.ultimoFocoRelatorio = null;
        }

        function abrirRelatorioCredenciados() {
            var modal = raiz.querySelector('[data-cr-credenciados-modal]');
            var dialogo = raiz.querySelector('.credenciados-relatorios-report-dialog');

            if (!modal || !dialogo) {
                throw new Error('A visualização do relatório não está disponível.');
            }

            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('credenciados-relatorios-modal-open');
            window.requestAnimationFrame(function () {
                dialogo.focus();
            });
        }

        function configuracaoRelatorio(tipo) {
            return configuracoesRelatorios[tipo] || null;
        }

        function aplicarConfiguracaoRelatorio(tipo) {
            var configuracao = configuracaoRelatorio(tipo);
            var titulo = raiz.querySelector('[data-cr-report-title]');
            var kicker = raiz.querySelector('[data-cr-report-kicker]');
            var icone = raiz.querySelector('[data-cr-report-icon]');
            var vazio = raiz.querySelector('[data-cr-report-empty-text]');

            if (!configuracao) {
                throw new Error('O tipo de relatório selecionado não está disponível.');
            }

            estado.relatorioAtivo = tipo;

            if (titulo) {
                titulo.textContent = configuracao.titulo;
            }

            if (kicker) {
                kicker.textContent = configuracao.kicker;
            }

            if (icone) {
                icone.className = configuracao.icone;
            }

            if (vazio) {
                vazio.textContent = configuracao.vazio;
            }

            return configuracao;
        }

        function corpoRequisicaoRelatorio(tipo) {
            var parametros = new URLSearchParams(estado.filtrosRelatorios);

            if (tipo === 'telefones' || tipo === 'emails') {
                parametros.set('relatorio_contatos', tipo);
            }

            return parametros.toString();
        }

        function renderizarFiltrosRelatorio(filtros) {
            var container = raiz.querySelector('[data-cr-report-filters]');

            if (!container) {
                return;
            }

            container.replaceChildren();

            if (!Array.isArray(filtros) || !filtros.length) {
                var nenhum = document.createElement('span');
                nenhum.textContent = 'Nenhum filtro aplicado';
                container.appendChild(nenhum);
                return;
            }

            var fragmento = document.createDocumentFragment();

            filtros.forEach(function (filtro) {
                var item = document.createElement('span');
                var rotulo = document.createElement('strong');
                var valor = document.createElement('span');

                item.className = 'credenciados-relatorios-report-filter-item';
                rotulo.textContent = String(filtro.rotulo || 'Filtro') + ':';
                valor.textContent = String(filtro.valor || '-');
                item.appendChild(rotulo);
                item.appendChild(valor);
                fragmento.appendChild(item);
            });

            container.appendChild(fragmento);
        }

        function renderizarTabelaRelatorio(dados) {
            var colunas = Array.isArray(dados.colunas) ? dados.colunas : [];
            var registros = Array.isArray(dados.registros) ? dados.registros : [];
            var cabecalho = raiz.querySelector('[data-cr-report-head]');
            var corpo = raiz.querySelector('[data-cr-report-body]');
            var tabelaContainer = raiz.querySelector('[data-cr-report-table-wrap]');
            var vazioContainer = raiz.querySelector('[data-cr-report-empty]');

            if (!cabecalho || !corpo || !tabelaContainer || !vazioContainer) {
                throw new Error('A tabela do relatório não está disponível.');
            }

            cabecalho.replaceChildren();
            corpo.replaceChildren();
            tabelaContainer.scrollTop = 0;
            tabelaContainer.scrollLeft = 0;
            tabelaContainer.hidden = registros.length === 0;
            vazioContainer.hidden = registros.length !== 0;

            if (!registros.length) {
                return;
            }

            var linhaCabecalho = document.createElement('tr');

            colunas.forEach(function (coluna) {
                var th = document.createElement('th');
                th.scope = 'col';
                th.dataset.column = String(coluna.chave || '');
                th.textContent = String(coluna.rotulo || coluna.chave || '');
                linhaCabecalho.appendChild(th);
            });

            cabecalho.appendChild(linhaCabecalho);

            var fragmento = document.createDocumentFragment();

            registros.forEach(function (registro) {
                var linha = document.createElement('tr');

                colunas.forEach(function (coluna) {
                    var celula = document.createElement('td');
                    var chave = String(coluna.chave || '');
                    var valor = registro && registro[chave] !== null && registro[chave] !== undefined
                        ? String(registro[chave]).trim()
                        : '';

                    celula.dataset.column = chave;
                    celula.textContent = valor || '-';
                    linha.appendChild(celula);
                });

                fragmento.appendChild(linha);
            });

            corpo.appendChild(fragmento);
        }

        function renderizarRelatorio(dados) {
            var total = Number(dados && dados.total);
            var totalElemento = raiz.querySelector('[data-cr-report-total]');
            var configuracao = configuracaoRelatorio(estado.relatorioAtivo);

            if (!Number.isFinite(total)) {
                total = Array.isArray(dados && dados.registros) ? dados.registros.length : 0;
            }

            if (totalElemento && configuracao) {
                totalElemento.textContent = numero(total) + ' ' + (total === 1
                    ? configuracao.singular
                    : configuracao.plural);
            }

            renderizarFiltrosRelatorio(dados && dados.filtros_aplicados);
            renderizarTabelaRelatorio(dados || {});
        }

        function definirExportandoExcel(exportando) {
            estado.exportandoExcel = exportando;

            var botao = raiz.querySelector('[data-cr-report-excel]');
            var icone = raiz.querySelector('[data-cr-report-excel-icon]');
            var rotulo = raiz.querySelector('[data-cr-report-excel-label]');

            if (botao) {
                botao.disabled = exportando;
                botao.classList.toggle('is-loading', exportando);
                botao.setAttribute('aria-busy', exportando ? 'true' : 'false');
            }

            if (icone) {
                icone.className = exportando ? 'fas fa-spinner fa-spin' : 'fas fa-file-excel';
            }

            if (rotulo) {
                rotulo.textContent = exportando ? 'Gerando...' : 'Excel';
            }
        }

        function nomeArquivoExcel(resposta) {
            var disposicao = resposta.headers.get('Content-Disposition') || '';
            var codificado = disposicao.match(/filename\*=UTF-8''([^;]+)/i);
            var simples = disposicao.match(/filename="?([^";]+)"?/i);
            var configuracao = configuracaoRelatorio(estado.relatorioAtivo);
            var nomePadrao = configuracao ? configuracao.arquivoPadrao : 'relatorio.xlsx';
            var nome = codificado ? codificado[1] : (simples ? simples[1] : nomePadrao);

            try {
                nome = decodeURIComponent(nome);
            } catch (erro) {
                nome = String(nome);
            }

            return nome.replace(/[\\/:*?"<>|]/g, '_');
        }

        function baixarArquivoExcel(blob, nomeArquivo) {
            var url = window.URL.createObjectURL(blob);
            var link = document.createElement('a');

            link.href = url;
            link.download = nomeArquivo;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            link.remove();

            window.setTimeout(function () {
                window.URL.revokeObjectURL(url);
            }, 1000);
        }

        function exportarRelatorioExcel() {
            if (estado.exportandoExcel || estado.carregando) {
                return;
            }

            var configuracao = configuracaoRelatorio(estado.relatorioAtivo);

            if (!configuracao) {
                mostrarMensagem('O relatório selecionado não está disponível.', true);
                return;
            }

            definirExportandoExcel(true);
            estado.requisicaoExcel = new AbortController();

            fetch('engine.php?class=CredenciadosRelatorios&method=' + configuracao.endpointExcel, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: corpoRequisicaoRelatorio(estado.relatorioAtivo),
                signal: estado.requisicaoExcel.signal
            }).then(function (resposta) {
                if (!resposta.ok) {
                    return resposta.text().then(function (texto) {
                        var payload;

                        try {
                            payload = JSON.parse(texto);
                        } catch (erro) {
                            payload = null;
                        }

                        throw new Error(payload && payload.erro
                            ? payload.erro
                            : 'Não foi possível gerar o arquivo Excel. Tente novamente.');
                    });
                }

                var nomeArquivo = nomeArquivoExcel(resposta);

                return resposta.blob().then(function (blob) {
                    if (!blob.size) {
                        throw new Error('O arquivo Excel gerado está vazio. Tente novamente.');
                    }

                    return { blob: blob, nomeArquivo: nomeArquivo };
                });
            }).then(function (arquivo) {
                baixarArquivoExcel(arquivo.blob, arquivo.nomeArquivo);
                mostrarMensagem('Relatório Excel gerado com sucesso.');
            }).catch(function (erro) {
                if (erro.name !== 'AbortError' && !estado.removido) {
                    mostrarMensagem(erro.message || 'Não foi possível gerar o arquivo Excel. Tente novamente.', true);
                }
            }).finally(function () {
                estado.requisicaoExcel = null;

                if (!estado.removido) {
                    definirExportandoExcel(false);
                }
            });
        }

        function carregarRelatorio(tipo) {
            if (estado.carregando) {
                return;
            }

            var configuracao;

            try {
                configuracao = aplicarConfiguracaoRelatorio(tipo);
            } catch (erro) {
                mostrarMensagem(erro.message, true);
                return;
            }

            estado.ultimoFocoRelatorio = document.activeElement;
            definirCarregando(true, configuracao.carregando, true);
            estado.requisicao = new AbortController();

            fetch('engine.php?class=CredenciadosRelatorios&method=' + configuracao.endpointConsulta, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: corpoRequisicaoRelatorio(tipo),
                signal: estado.requisicao.signal
            }).then(function (resposta) {
                return resposta.text().then(function (texto) {
                    var payload;

                    try {
                        payload = JSON.parse(texto);
                    } catch (erro) {
                        throw new Error('A resposta do relatório não pôde ser processada.');
                    }

                    if (!resposta.ok || !payload.sucesso) {
                        throw new Error(payload.erro || 'Não foi possível carregar o relatório selecionado.');
                    }

                    return payload.dados;
                });
            }).then(function (dados) {
                renderizarRelatorio(dados || {});
                abrirRelatorioCredenciados();
            }).catch(function (erro) {
                if (erro.name !== 'AbortError' && !estado.removido) {
                    mostrarMensagem(erro.message || 'Não foi possível carregar o relatório selecionado.', true);
                }
            }).finally(function () {
                estado.requisicao = null;

                if (!estado.removido) {
                    definirCarregando(false);
                }
            });
        }

        function atualizar() {
            if (estado.carregando || !filtrosValidos()) {
                return;
            }

            definirCarregando(true);
            estado.requisicao = new AbortController();

            var parametros = new URLSearchParams(new FormData(formulario));

            fetch('engine.php?class=CredenciadosRelatorios&method=onAtualizarAjax', {
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
                window.jQuery(formulario).find('.credenciados-relatorios-searchable').val('').trigger('change');
            }

            atualizar();
        }

        function aoClicar(evento) {
            var botaoAcao = evento.target.closest('[data-cr-action]');
            var botaoRelatorio = evento.target.closest('[data-cr-report]');
            var botaoExcel = evento.target.closest('[data-cr-report-excel]');
            var fecharRelatorio = evento.target.closest('[data-cr-report-close]');

            if (fecharRelatorio && raiz.contains(fecharRelatorio)) {
                fecharRelatorioCredenciados();
                return;
            }

            if (botaoExcel && raiz.contains(botaoExcel)) {
                exportarRelatorioExcel();
                return;
            }

            if (botaoAcao && raiz.contains(botaoAcao)) {
                var acao = botaoAcao.getAttribute('data-cr-action');

                if (acao === 'limpar') {
                    limparFiltros();
                } else if (acao === 'toggle-filters') {
                    definirFiltrosAbertos(!estado.filtrosAbertos);
                }
            }

            if (botaoRelatorio && raiz.contains(botaoRelatorio)) {
                carregarRelatorio(botaoRelatorio.getAttribute('data-cr-report'));
            }
        }

        function aoTeclar(evento) {
            if (evento.key === 'Escape') {
                fecharRelatorioCredenciados();
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

            window.jQuery(raiz).find('.credenciados-relatorios-searchable').each(function () {
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

            if (estado.requisicaoExcel) {
                estado.requisicaoExcel.abort();
            }

            if (observer) {
                observer.disconnect();
            }

            window.clearTimeout(estado.timerToast);
            window.clearTimeout(estado.timerResize);
            window.removeEventListener('resize', aoRedimensionar);
            document.removeEventListener('keydown', aoTeclar);
            raiz.removeEventListener('click', aoClicar);
            formulario.removeEventListener('submit', aoEnviar);
            document.body.classList.remove('credenciados-relatorios-modal-open');
            destruirGraficos();

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(raiz).find('.credenciados-relatorios-searchable.select2-hidden-accessible').select2('destroy');
            }

            if (window[instanciaGlobal] && window[instanciaGlobal].raiz === raiz) {
                window[instanciaGlobal] = null;
            }
        }

        raiz.addEventListener('click', aoClicar);
        formulario.addEventListener('submit', aoEnviar);
        window.addEventListener('resize', aoRedimensionar);
        document.addEventListener('keydown', aoTeclar);
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
        document.addEventListener('DOMContentLoaded', window.iniciarCredenciadosRelatorios, { once: true });
    } else {
        window.setTimeout(window.iniciarCredenciadosRelatorios, 0);
    }
}());
