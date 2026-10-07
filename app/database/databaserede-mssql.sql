CREATE TABLE acordo( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      nr_jet int   , 
      especialidades_id int   NOT NULL  , 
      medico varchar  (150)   NOT NULL  , 
      carteirinha varchar  (25)   NOT NULL  , 
      beneficiario varchar  (150)   NOT NULL  , 
      valor float   , 
      dt_atendimento date   , 
      dt_nota date   , 
      nr_nota varchar  (20)   , 
      dt_pagamento date   , 
      observacao nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE acordo_arquivo( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      acordo_id int   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      path_arquivo nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE api_error( 
      id  INT IDENTITY    NOT NULL  , 
      classe nvarchar(max)   , 
      metodo nvarchar(max)   , 
      url nvarchar(max)   , 
      dados nvarchar(max)   , 
      error_message nvarchar(max)   , 
      inserted_at datetime2   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp, 
      nome_arquivo varchar  (255)   NOT NULL  , 
      ano_base int   NOT NULL  , 
      data_emissao date   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      arquivos_status_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_cooperado( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      arquivos_status_id int   NOT NULL  , 
      cooperado_id int   NOT NULL  , 
      arquivos_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_status( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      nome varchar  (30)   NOT NULL  , 
      descricao varchar  (250)   NOT NULL  , 
      ativo varchar  (3)   NOT NULL    DEFAULT 'Sim', 
      cor varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE bancos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      banco nvarchar(max)   NOT NULL  , 
      codigo varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE beneficios( 
      id  INT IDENTITY    NOT NULL  , 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      beneficio nvarchar(max)   , 
      identificador nvarchar(max)   , 
      tipo_beneficio_id int   NOT NULL  , 
      ativo varchar  (5)   , 
      data_inicial date   , 
      data_final date   , 
      cooperados_id int   NOT NULL  , 
      valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      dm_categoria char  (1)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cpf_cnpj varchar  (30)   NOT NULL  , 
      data_solicitacao date   NOT NULL  , 
      cidades_id int   NOT NULL  , 
      observacao nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_contato( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      catalogo_id int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      contato varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_endereco( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      catalogo_id int   NOT NULL  , 
      cidades_id int   , 
      bairro varchar  (50)   , 
      logradouro varchar  (50)   , 
      numero varchar  (10)   , 
      cep varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_especialidade( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      catalogo_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_responsavel( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2   , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cep varchar  (8)   , 
      rua varchar  (500)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf char  (2)   , 
      cidades_id int   , 
      estados_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidades( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      estados_id int   NOT NULL  , 
      cidade varchar  (150)   NOT NULL  , 
      cod_ibge varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      nome varchar  (150)   NOT NULL  , 
      empresa varchar  (150)   NOT NULL  , 
      cnpj varchar  (18)   NOT NULL  , 
      dt_contrato date   NOT NULL  , 
      dt_reajuste date   NOT NULL  , 
      indice float   , 
      path_arquivo nvarchar(max)   , 
      dm_situacao char  (1)   , 
      apolice varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_servico( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      contrato_id int   NOT NULL  , 
      servico varchar  (150)   NOT NULL  , 
      dt_inicio date   , 
      dt_termino date   , 
      dm_situacao char  (1)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_valor( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      contrato_id int   NOT NULL  , 
      dt_pagamento date   NOT NULL  , 
      valor float   NOT NULL  , 
      nr_nota varchar  (20)   , 
      path_arquivo nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      crm varchar  (20)   , 
      inss varchar  (15)   , 
      data_filiacao date   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      cpf varchar  (14)   , 
      rg varchar  (20)   , 
      sexo char  (1)   , 
      data_nascimento date   , 
      ativo char  (1)   , 
      forma_integralizacao varchar  (50)   , 
      estado_civil char  (1)   , 
      numero_filhos int   , 
      cnis varchar  (20)   , 
      flg_retem_ir char  (1)   NOT NULL    DEFAULT 'N', 
      flg_declara_dep char  (1)   NOT NULL    DEFAULT 'N', 
      flg_recolhe_inss char  (1)   NOT NULL    DEFAULT 'N', 
      path_foto nvarchar(max)   , 
      contabilidade varchar  (150)   , 
      flg_envio_dados char  (1)   , 
      dt_desfiliacao date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      codigo nvarchar(max)   , 
      tipo varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios_dep( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_beneficiarios_id int   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      dm_tipo varchar  (2)   NOT NULL  , 
      codigo varchar  (21)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_capital( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      dm_capital_social char  (3)   NOT NULL  , 
      nr_parcela int   , 
      data_aquisicao date   NOT NULL  , 
      valor float   NOT NULL  , 
      log_import_capital_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_contatos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_ctb_contato( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      nome varchar  (100)   , 
      numero varchar  (30)   , 
      email varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_dados_bancarios( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      banco_id int   NOT NULL  , 
      conta_descricao nvarchar(max)   , 
      agencia nvarchar(max)   NOT NULL  , 
      conta nvarchar(max)   NOT NULL  , 
      cooperados_id int   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_documentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo nvarchar(max)   NOT NULL  , 
      conteudo nvarchar(max)   , 
      observacao nvarchar(max)   , 
      entregue varchar  (3)   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      cooperados_id int   NOT NULL  , 
      path_arquivo nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_especialidades( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
      rqe int   , 
      imprime_guia_medico varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_movimentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2   , 
      cooperados_id int   NOT NULL  , 
      descricao nvarchar(max)   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime2   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_secretaria( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cooperados_id int   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      contato varchar  (150)   , 
      email varchar  (150)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      data_inicio date   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cnpj varchar  (18)   NOT NULL  , 
      cnes varchar  (15)   , 
      inscricao_estadual varchar  (15)   , 
      ativo char  (1)   , 
      enquadramento_tributario char  (2)   , 
      codigo_prestador varchar  (15)   , 
      data_contrato date   , 
      dm_reaj_contr char  (3)   , 
      nr_dias_aviso_reaj int   , 
      flg_dias_padrao char  (1)   , 
      dm_tipo char  (2)   , 
      dt_descredenciamento date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_contatos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      contato varchar  (100)   , 
      credenciados_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_dados_bancarios( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      conta_descricao varchar  (150)   , 
      agencia varchar  (20)   NOT NULL  , 
      conta varchar  (20)   NOT NULL  , 
      ativo char  (1)   NOT NULL  , 
      desativado date   , 
      observacao varchar  (255)   , 
      bancos_id int   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_documentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo char  (1)   NOT NULL  , 
      conteudo nvarchar(max)   , 
      observacao nvarchar(max)   , 
      entregue char  (1)   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      arquivo_path nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_enderecos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero nvarchar(max)   , 
      bairro varchar  (100)   , 
      iss nvarchar(max)   , 
      cnes nvarchar(max)   , 
      inscricao_municipal int   , 
      credenciados_id int   NOT NULL  , 
      cidades_id int   NOT NULL  , 
      tipos_enderecos_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_especialidades( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      rqe int  (10)   , 
      imprime_guia_medico char  (1)   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_movimentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2   , 
      credenciados_id int   NOT NULL  , 
      descricao nvarchar(max)   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime2   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_reajuste( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      credenciados_id int   NOT NULL  , 
      data_reajuste date   NOT NULL  , 
      dm_reaj_contr char  (3)   NOT NULL  , 
      ultimo_indice float   , 
      indice float   NOT NULL  , 
      dm_tipo_doc char  (1)   NOT NULL  , 
      observacao varchar  (255)   , 
      path_anexo nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_responsaveis( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      credenciados_id int   NOT NULL  , 
      categoria_responsavel_id int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cooperado varchar  (3)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      descricao nvarchar(max)   NOT NULL  , 
      ativo varchar  (10)   , 
      tipos_documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_cooperados( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      documentacoes_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_credenciado( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2     DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (255)   , 
      flg_colorir_pad char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_relacionamento( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      dominio_id int   NOT NULL  , 
      objeto varchar  (62)   NOT NULL  , 
      atributo varchar  (62)   NOT NULL  , 
      valor_padrao varchar  (45)   , 
      flg_colorir varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_valor( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      dominio_id int   NOT NULL  , 
      sequencia int   NOT NULL  , 
      valor varchar  (45)   NOT NULL  , 
      mascara varchar  (255)   NOT NULL  , 
      cor_letra varchar  (9)   , 
      cor_fundo varchar  (9)   , 
      icone varchar  (62)   , 
      mascara_html nvarchar(max)   , 
      cor_grafico varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE enderecos_cooperados( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero int   , 
      cidades_id int   NOT NULL  , 
      bairro varchar  (100)   , 
      cooperados_id int   NOT NULL  , 
      tipos_enderecos_id int   NOT NULL  , 
      iss nvarchar(max)   , 
      im int   , 
      cnes nvarchar(max)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE especialidades( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      especialidade nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estados( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      estado varchar  (50)   NOT NULL  , 
      sigla char  (2)   , 
      cod_ibge char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE expressao( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      expressao varchar  (256)   NOT NULL  , 
      dm_tipo char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      assunto varchar  (256)   NOT NULL  , 
      destinatario varchar  (256)   NOT NULL  , 
      corpo longblob   , 
      data_envio datetime2   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_anexo( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      fila_emails_id int   NOT NULL  , 
      caminho varchar  (256)   NOT NULL  , 
      nome varchar  (256)   NOT NULL  , 
      extensao varchar  (20)   NOT NULL  , 
      tamanho varchar  (20)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_log( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      fila_emails_id int   NOT NULL  , 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      mensagem varchar  (4000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      nome varchar  (150)   NOT NULL  , 
      dm_situacao char  (1)   NOT NULL    DEFAULT 'A', 
      dm_tipo char  (1)   , 
      intervalo int   , 
      periodo varchar  (20)   , 
      dt_inicio datetime2   , 
      dt_termino datetime2   , 
      request nvarchar(max)   , 
      dm_tipo_req varchar  (15)   , 
      params_req nvarchar(max)   , 
      dt_prox_exec datetime2   , 
      usuario_notif_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job_exec( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      job_id int   NOT NULL  , 
      dt_inicio datetime  (6)   NOT NULL  , 
      dt_termino datetime  (6)   , 
      dm_status char  (1)   NOT NULL  , 
      mensagem nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE label_email( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      label varchar  (45)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      chave varchar  (62)   NOT NULL  , 
      script_sql nvarchar(max)   NOT NULL  , 
      dm_tipo char  (1)   NOT NULL    DEFAULT 'U', 
      titulo varchar  (45)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_import_capital( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      system_user_id int   NOT NULL  , 
      nome_arq varchar  (255)   NOT NULL  , 
      dt_inicio datetime2   , 
      dt_termino datetime2   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      crm varchar  (20)   , 
      data_contrato date   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      cpf varchar  (14)   , 
      rg varchar  (20)   , 
      sexo char  (1)   , 
      data_nascimento date   , 
      ativo char  (1)   , 
      estado_civil char  (1)   , 
      path_foto nvarchar(max)   , 
      flg_envio_dados char  (1)   , 
      data_encerramento_contrato date   , 
      cnpj varchar  (14)   , 
      cnes varchar  (7)   , 
      cod_plantonista varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_contatos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      medicos_pf_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_dados_bancarios( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      conta_descricao nvarchar(max)   , 
      agencia nvarchar(max)   NOT NULL  , 
      conta nvarchar(max)   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao nvarchar(max)   , 
      medicos_pf_id int   NOT NULL  , 
      bancos_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo nvarchar(max)   NOT NULL  , 
      conteudo nvarchar(max)   , 
      observacao nvarchar(max)   , 
      entregue varchar  (3)   NOT NULL  , 
      path_arquivo nvarchar(max)   , 
      medicos_pf_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes_padrao( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_enderecos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero int   , 
      bairro varchar  (100)   , 
      iss nvarchar(max)   , 
      im int   , 
      cnes nvarchar(max)   , 
      medicos_pf_id int   NOT NULL  , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      tipos_enderecos_id int   NOT NULL  , 
      cidades_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_especialidades( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      rqe int   , 
      imprime_guia_medico varchar  (10)   , 
      especialidades_id int   NOT NULL  , 
      medicos_pf_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_movimentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2   , 
      medicos_pf_id int   NOT NULL  , 
      descricao nvarchar(max)   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime2   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parametro( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (256)   , 
      dm_tipo_param varchar  (3)   NOT NULL  , 
      dominio_id int   , 
      separador varchar  (2)   , 
      valor nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      titulo varchar  (100)   NOT NULL  , 
      dm_tipo char  (2)   NOT NULL  , 
      dm_papel char  (3)   NOT NULL  , 
      dm_orientacao char  (1)   NOT NULL  , 
      cor_fundo varchar  (9)   , 
      margem_sup int   , 
      margem_inf int   , 
      margem_esq int   , 
      margem_dir int   , 
      flg_borda_sup char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_inf char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_esq char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_dir char  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_banda( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_banda char  (2)   NOT NULL  , 
      altura int   NOT NULL  , 
      sequencia int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_parametro( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      relatorio_id int   NOT NULL  , 
      sequencia int   NOT NULL  , 
      codigo varchar  (45)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_atributo char  (1)   NOT NULL  , 
      dm_apresentacao char  (3)   , 
      mascara varchar  (45)   , 
      flg_obrigatorio char  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE rel_imagem( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL  , 
      data_alteracao datetime2   , 
      nome varchar  (100)   , 
      img nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE templates_email( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      corpo longblob   , 
      expr_assunto_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_beneficios( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      tipo_beneficio varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_contatos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      tipo_contato varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_documentacoes( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao datetime2     DEFAULT current_timestamp(), 
      tipo_documento varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_enderecos( 
      id  INT IDENTITY    NOT NULL  , 
      data_criacao datetime2   NOT NULL    DEFAULT current_timestamp, 
      tipo_endereco varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE catalogo ADD UNIQUE (cpf_cnpj);
 ALTER TABLE cooperados ADD UNIQUE (cpf);
 ALTER TABLE credenciados ADD UNIQUE (cnpj);
 ALTER TABLE dominio ADD UNIQUE (codigo);
 ALTER TABLE label_email ADD UNIQUE (label);
 ALTER TABLE parametro ADD UNIQUE (codigo);
 ALTER TABLE templates_email ADD UNIQUE (codigo);
  
 ALTER TABLE acordo ADD CONSTRAINT fk_acordos_1 FOREIGN KEY (especialidades_id) references especialidades(id); 
ALTER TABLE acordo_arquivo ADD CONSTRAINT fk_acordo_arquivo_1 FOREIGN KEY (acordo_id) references acordo(id); 
ALTER TABLE arquivos ADD CONSTRAINT fk_extrato_cota_capital_arquivo_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE arquivos ADD CONSTRAINT fk_extrato_cota_capital_arquivo_3 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE arquivos ADD CONSTRAINT fk_arquivos_4 FOREIGN KEY (arquivos_status_id) references arquivos_status(id); 
ALTER TABLE arquivos_cooperado ADD CONSTRAINT fk_extrato_cota_capital_arquivo_cooperado_1 FOREIGN KEY (arquivos_status_id) references arquivos_status(id); 
ALTER TABLE arquivos_cooperado ADD CONSTRAINT fk_extrato_cota_capital_arquivo_cooperado_2 FOREIGN KEY (cooperado_id) references cooperados(id); 
ALTER TABLE arquivos_cooperado ADD CONSTRAINT fk_arquivos_cooperado_3 FOREIGN KEY (arquivos_id) references arquivos(id); 
ALTER TABLE beneficios ADD CONSTRAINT fk_beneficios_1 FOREIGN KEY (tipo_beneficio_id) references tipos_beneficios(id); 
ALTER TABLE beneficios ADD CONSTRAINT fk_beneficios_3 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE catalogo ADD CONSTRAINT fk_catalogo_1 FOREIGN KEY (cidades_id) references cidades(id); 
ALTER TABLE catalogo_contato ADD CONSTRAINT fk_catalogo_contato_1 FOREIGN KEY (catalogo_id) references catalogo(id); 
ALTER TABLE catalogo_endereco ADD CONSTRAINT fk_catalogo_endereco_1 FOREIGN KEY (catalogo_id) references catalogo(id); 
ALTER TABLE catalogo_endereco ADD CONSTRAINT fk_catalogo_endereco_2 FOREIGN KEY (cidades_id) references cidades(id); 
ALTER TABLE catalogo_especialidade ADD CONSTRAINT fk_catalogo_especialidade_1 FOREIGN KEY (especialidades_id) references especialidades(id); 
ALTER TABLE catalogo_especialidade ADD CONSTRAINT fk_catalogo_especialidade_2 FOREIGN KEY (catalogo_id) references catalogo(id); 
ALTER TABLE cidades ADD CONSTRAINT fk_cidades_1 FOREIGN KEY (estados_id) references estados(id); 
ALTER TABLE contrato_servico ADD CONSTRAINT fk_contrato_servico_1 FOREIGN KEY (contrato_id) references contrato(id); 
ALTER TABLE contrato_valor ADD CONSTRAINT fk_contrato_valor_1 FOREIGN KEY (contrato_id) references contrato(id); 
ALTER TABLE cooperados_beneficiarios ADD CONSTRAINT fk_cooperados_beneficiarios_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_beneficiarios_dep ADD CONSTRAINT fk_cooperados_beneficiarios_dep_1 FOREIGN KEY (cooperados_beneficiarios_id) references cooperados_beneficiarios(id); 
ALTER TABLE cooperados_capital ADD CONSTRAINT fk_cooperados_capital_2 FOREIGN KEY (log_import_capital_id) references log_import_capital(id); 
ALTER TABLE cooperados_capital ADD CONSTRAINT fk_new_table_50_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_contatos ADD CONSTRAINT fk_cooperado_contatos_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_contatos ADD CONSTRAINT fk_cooperado_contatos_2 FOREIGN KEY (tipos_contatos_id) references tipos_contatos(id); 
ALTER TABLE cooperados_ctb_contato ADD CONSTRAINT fk_cooperados_ctb_contato_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_dados_bancarios ADD CONSTRAINT fk_cooperados_contas_bancarias_1 FOREIGN KEY (banco_id) references bancos(id); 
ALTER TABLE cooperados_dados_bancarios ADD CONSTRAINT fk_cooperados_contas_bancarias_2 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_documentacoes ADD CONSTRAINT fk_cooperados_documentacoes_1 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE cooperados_documentacoes ADD CONSTRAINT fk_cooperados_documentacoes_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE cooperados_documentacoes ADD CONSTRAINT fk_cooperados_documentacoes_3 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_especialidades ADD CONSTRAINT fk_cooperados_especialidades_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_especialidades ADD CONSTRAINT fk_cooperados_especialidades_2 FOREIGN KEY (especialidades_id) references especialidades(id); 
ALTER TABLE cooperados_movimentacoes ADD CONSTRAINT fk_cooperados_movimentacoes_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE cooperados_secretaria ADD CONSTRAINT fk_cooperados_secretaria_1 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE credenciados_contatos ADD CONSTRAINT fk_credenciados_contatos_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_contatos ADD CONSTRAINT fk_credenciados_contatos_2 FOREIGN KEY (tipos_contatos_id) references tipos_contatos(id); 
ALTER TABLE credenciados_dados_bancarios ADD CONSTRAINT fk_credenciados_dados_bancarios_1 FOREIGN KEY (bancos_id) references bancos(id); 
ALTER TABLE credenciados_dados_bancarios ADD CONSTRAINT fk_credenciados_dados_bancarios_2 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_documentacoes ADD CONSTRAINT fk_credenciados_documentacoes_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_documentacoes ADD CONSTRAINT fk_credenciados_documentacoes_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE credenciados_documentacoes ADD CONSTRAINT fk_credenciados_documentacoes_3 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE credenciados_enderecos ADD CONSTRAINT fk_enderecos_credenciados_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_enderecos ADD CONSTRAINT fk_enderecos_credenciados_2 FOREIGN KEY (cidades_id) references cidades(id); 
ALTER TABLE credenciados_enderecos ADD CONSTRAINT fk_enderecos_credenciados_3 FOREIGN KEY (tipos_enderecos_id) references tipos_enderecos(id); 
ALTER TABLE credenciados_especialidades ADD CONSTRAINT fk_credenciados_especialidades_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_especialidades ADD CONSTRAINT fk_credenciados_especialidades_2 FOREIGN KEY (especialidades_id) references especialidades(id); 
ALTER TABLE credenciados_movimentacoes ADD CONSTRAINT fk_credenciados_movimentacoes_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_reajuste ADD CONSTRAINT fk_credenciados_reajuste_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_responsaveis ADD CONSTRAINT fk_responsavel_legal_1 FOREIGN KEY (credenciados_id) references credenciados(id); 
ALTER TABLE credenciados_responsaveis ADD CONSTRAINT fk_credenciados_responsaveis_2 FOREIGN KEY (categoria_responsavel_id) references categoria_responsavel(id); 
ALTER TABLE documentacoes ADD CONSTRAINT fk_documentacoes_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE documentacoes_padrao_cooperados ADD CONSTRAINT fk_doumentacoes_padrao_cooperados_1 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE documentacoes_padrao_cooperados ADD CONSTRAINT fk_documentacoes_padrao_cooperados_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE documentacoes_padrao_credenciado ADD CONSTRAINT fk_documentacoes_padrao_credenciado_1 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE documentacoes_padrao_credenciado ADD CONSTRAINT fk_documentacoes_padrao_credenciado_2 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE dominio_relacionamento ADD CONSTRAINT fk_dominio_relacionamento_1 FOREIGN KEY (dominio_id) references dominio(id); 
ALTER TABLE dominio_valor ADD CONSTRAINT fk_dominio_valor_1 FOREIGN KEY (dominio_id) references dominio(id); 
ALTER TABLE enderecos_cooperados ADD CONSTRAINT fk_enderecos_3 FOREIGN KEY (cooperados_id) references cooperados(id); 
ALTER TABLE enderecos_cooperados ADD CONSTRAINT fk_enderecos_3 FOREIGN KEY (tipos_enderecos_id) references tipos_enderecos(id); 
ALTER TABLE enderecos_cooperados ADD CONSTRAINT fk_endereco_1 FOREIGN KEY (cidades_id) references cidades(id); 
ALTER TABLE fila_email_anexo ADD CONSTRAINT fk_fila_emails_anexo_1 FOREIGN KEY (fila_emails_id) references fila_email(id); 
ALTER TABLE fila_email_log ADD CONSTRAINT fk_fila_emails_log_1 FOREIGN KEY (fila_emails_id) references fila_email(id); 
ALTER TABLE job_exec ADD CONSTRAINT fk_job_exec_1 FOREIGN KEY (job_id) references job(id); 
ALTER TABLE medicos_pf_contatos ADD CONSTRAINT fk_medicos_pf_contatos_1 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE medicos_pf_contatos ADD CONSTRAINT fk_medicos_pf_contatos_2 FOREIGN KEY (tipos_contatos_id) references tipos_contatos(id); 
ALTER TABLE medicos_pf_dados_bancarios ADD CONSTRAINT fk_medicos_pf_dados_bancarios_1 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE medicos_pf_dados_bancarios ADD CONSTRAINT fk_medicos_pf_dados_bancarios_2 FOREIGN KEY (bancos_id) references bancos(id); 
ALTER TABLE medicos_pf_documentacoes ADD CONSTRAINT fk_medicos_pf_documentacoes_1 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE medicos_pf_documentacoes ADD CONSTRAINT fk_medicos_pf_documentacoes_2 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE medicos_pf_documentacoes ADD CONSTRAINT fk_medicos_pf_documentacoes_3 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE medicos_pf_documentacoes_padrao ADD CONSTRAINT fk_medicos_pf_documentacoes_padrao_1 FOREIGN KEY (tipos_documentacoes_id) references tipos_documentacoes(id); 
ALTER TABLE medicos_pf_documentacoes_padrao ADD CONSTRAINT fk_medicos_pf_documentacoes_padrao_2 FOREIGN KEY (documentacoes_id) references documentacoes(id); 
ALTER TABLE medicos_pf_enderecos ADD CONSTRAINT fk_medicos_pf_enderecos_1 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE medicos_pf_enderecos ADD CONSTRAINT fk_medicos_pf_enderecos_2 FOREIGN KEY (tipos_enderecos_id) references tipos_enderecos(id); 
ALTER TABLE medicos_pf_enderecos ADD CONSTRAINT fk_medicos_pf_enderecos_3 FOREIGN KEY (cidades_id) references cidades(id); 
ALTER TABLE medicos_pf_especialidades ADD CONSTRAINT fk_medicos_pf_especialidades_1 FOREIGN KEY (especialidades_id) references especialidades(id); 
ALTER TABLE medicos_pf_especialidades ADD CONSTRAINT fk_medicos_pf_especialidades_2 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE medicos_pf_movimentacoes ADD CONSTRAINT fk_medicos_pf_movimentacoes_1 FOREIGN KEY (medicos_pf_id) references medicos_pf(id); 
ALTER TABLE parametro ADD CONSTRAINT fk_parametro_1 FOREIGN KEY (dominio_id) references dominio(id); 
ALTER TABLE relatorio_parametro ADD CONSTRAINT fk_relatorio_parametro_1 FOREIGN KEY (relatorio_id) references relatorio(id); 
ALTER TABLE templates_email ADD CONSTRAINT fk_templates_email_1 FOREIGN KEY (expr_assunto_id) references expressao(id); 

 CREATE VIEW v_aniversariantes AS SELECT c.nome            nome,
       c.data_nascimento data_nascimento,
       c.id              id
  FROM cooperados c
 WHERE DATE_FORMAT(c.data_nascimento, '%m%d') >= DATE_FORMAT(CURRENT_DATE, '%m%d')
   AND DATE_FORMAT(c.data_nascimento, '%m%d') <= DATE_FORMAT((CURRENT_DATE + INTERVAL f_obter_valor_param('nr_dias_aniver_dashboard') DAY), '%m%d')
   AND c.ativo = 'S'
 ORDER BY CAST(DATE_FORMAT(c.data_nascimento, '%m%d') AS UNSIGNED INTEGER),
          c.nome; 

CREATE VIEW v_benefs AS select cb.id   id,
       cb.tipo tipo,
       (select dv.cor_grafico
          from dominio       d,
               dominio_valor dv
         where d.id     = dv.dominio_id
           and d.codigo = 'dm_coop_benef_tipo'
           and dv.valor = cb.tipo) cor_grafico
  from cooperados_beneficiarios cb; 

CREATE VIEW v_cooperados_capital AS SELECT ROW_NUMBER() OVER (ORDER BY c.nome, cc.data_aquisicao) linha,
       c.id id,
       c.crm crm,
       c.nome nome,
       cc.dm_capital_social dm_capital_social,
       cc.nr_parcela nr_parcela,
       cc.data_aquisicao data_aquisicao,
       CASE
         WHEN COALESCE(FIND_IN_SET(cc.dm_capital_social, f_obter_valor_param('listdm_grupos_capital_negativos'))) > 0 THEN cc.valor * -1
         ELSE cc.valor
       END valor
  FROM cooperados_capital cc,
       cooperados         c
 WHERE c.id = cc.cooperados_id
 ORDER BY c.nome,
          cc.data_aquisicao
 ; 

CREATE VIEW v_cooperados_capital_totais AS SELECT v.id         cooperados_id,
       'Conta'      des,
       1            ordem,
       SUM(v.valor) valor
  FROM v_cooperados_capital v
 WHERE substr(v.dm_capital_social, 1, 1) = 1
 GROUP BY v.id, substr(v.dm_capital_social, 1, 1)
UNION ALL
SELECT v.id         cooperados_id,
       'Subconta'   des,
       2            ordem,
       SUM(v.valor) valor
  FROM v_cooperados_capital v
 WHERE substr(v.dm_capital_social, 1, 1) = 2
 GROUP BY v.id, substr(v.dm_capital_social, 1, 1)
UNION ALL
SELECT v.id          cooperados_id,
       'Total geral' des,
       3             ordem,
       SUM(v.valor)  valor
  FROM v_cooperados_capital v
 GROUP BY v.id
 ORDER BY 1, 3; 

CREATE VIEW v_dominio_valor AS SELECT dv.id             id,
       d.codigo          codigo,
       d.nome            nome,
       dv.sequencia      sequencia,
       dv.valor          valor,
       dv.mascara        mascara,
       dv.mascara_html   mascara_html,
       dv.cor_grafico    cor_grafico,
       d.flg_colorir_pad flg_colorir_pad
  FROM dominio d,
       dominio_valor dv
 WHERE d.id = dv.dominio_id; 

CREATE VIEW v_dominio_valor_col AS SELECT dv.id           id,
       d.codigo        codigo,
       d.nome          nome,
       dr.objeto       objeto,
       dr.atributo     atributo,
       dr.valor_padrao valor_padrao,
       dr.flg_colorir  flg_colorir,
       dv.sequencia    sequencia,
       dv.valor        valor,
       CASE dr.flg_colorir
         WHEN 'N' THEN dv.mascara
         ELSE dv.mascara_html
       END             mascara,
       dv.cor_grafico  cor_grafico
  FROM dominio                d,
       dominio_valor          dv,
       dominio_relacionamento dr
 WHERE d.id = dv.dominio_id
   AND d.id = dr.dominio_id; 

CREATE VIEW v_job AS SELECT j.id           id,
       j.nome         nome,
       j.dm_situacao  dm_situacao,
       j.dm_tipo      dm_tipo,
       j.intervalo    intervalo,
       j.periodo      periodo,
       j.dt_prox_exec dt_prox_exec,
       (SELECT je.dm_status
          FROM job_exec je
         WHERE je.id = (SELECT MAX(a.id)
                          FROM job_exec a
                         WHERE a.job_id = j.id)) dm_status_exec,
       (SELECT je.dt_inicio
          FROM job_exec je
         WHERE je.id = (SELECT MAX(a.id)
                          FROM job_exec a
                         WHERE a.job_id = j.id)) dt_ultim_exec
       
  FROM job j; 
 
