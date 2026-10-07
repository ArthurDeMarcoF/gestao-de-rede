CREATE TABLE acordo( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      nr_jet integer   , 
      especialidades_id integer   NOT NULL  , 
      medico varchar  (150)   NOT NULL  , 
      carteirinha varchar  (25)   NOT NULL  , 
      beneficiario varchar  (150)   NOT NULL  , 
      valor float   , 
      dt_atendimento date   , 
      dt_nota date   , 
      nr_nota varchar  (20)   , 
      dt_pagamento date   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE acordo_arquivo( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      acordo_id integer   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      path_arquivo text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE api_error( 
      id  SERIAL    NOT NULL  , 
      classe text   , 
      metodo text   , 
      url text   , 
      dados text   , 
      error_message text   , 
      inserted_at timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp, 
      nome_arquivo varchar  (255)   NOT NULL  , 
      ano_base integer   NOT NULL  , 
      data_emissao date   NOT NULL  , 
      tipos_documentacoes_id integer   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
      arquivos_status_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_cooperado( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      arquivos_status_id integer   NOT NULL  , 
      cooperado_id integer   NOT NULL  , 
      arquivos_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_status( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      nome varchar  (30)   NOT NULL  , 
      descricao varchar  (250)   NOT NULL  , 
      ativo varchar  (3)   NOT NULL    DEFAULT 'Sim', 
      cor varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE bancos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      banco text   NOT NULL  , 
      codigo varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE beneficios( 
      id  SERIAL    NOT NULL  , 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      beneficio text   , 
      identificador text   , 
      tipo_beneficio_id integer   NOT NULL  , 
      ativo varchar  (5)   , 
      data_inicial date   , 
      data_final date   , 
      cooperados_id integer   NOT NULL  , 
      valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      dm_categoria char  (1)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cpf_cnpj varchar  (30)   NOT NULL  , 
      data_solicitacao date   NOT NULL  , 
      cidades_id integer   NOT NULL  , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_contato( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      catalogo_id integer   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      contato varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_endereco( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      catalogo_id integer   NOT NULL  , 
      cidades_id integer   , 
      bairro varchar  (50)   , 
      logradouro varchar  (50)   , 
      numero varchar  (10)   , 
      cep varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_especialidade( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      catalogo_id integer   NOT NULL  , 
      especialidades_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_responsavel( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp   , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cep varchar  (8)   , 
      rua varchar  (500)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf char  (2)   , 
      cidades_id integer   , 
      estados_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidades( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      estados_id integer   NOT NULL  , 
      cidade varchar  (150)   NOT NULL  , 
      cod_ibge varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      nome varchar  (150)   NOT NULL  , 
      empresa varchar  (150)   NOT NULL  , 
      cnpj varchar  (18)   NOT NULL  , 
      dt_contrato date   NOT NULL  , 
      dt_reajuste date   NOT NULL  , 
      indice float   , 
      path_arquivo text   , 
      dm_situacao char  (1)   , 
      apolice varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_servico( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      contrato_id integer   NOT NULL  , 
      servico varchar  (150)   NOT NULL  , 
      dt_inicio date   , 
      dt_termino date   , 
      dm_situacao char  (1)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_valor( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      contrato_id integer   NOT NULL  , 
      dt_pagamento date   NOT NULL  , 
      valor float   NOT NULL  , 
      nr_nota varchar  (20)   , 
      path_arquivo text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
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
      numero_filhos integer   , 
      cnis varchar  (20)   , 
      flg_retem_ir char  (1)   NOT NULL    DEFAULT 'N', 
      flg_declara_dep char  (1)   NOT NULL    DEFAULT 'N', 
      flg_recolhe_inss char  (1)   NOT NULL    DEFAULT 'N', 
      path_foto text   , 
      contabilidade varchar  (150)   , 
      flg_envio_dados char  (1)   , 
      dt_desfiliacao date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      codigo text   , 
      tipo varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios_dep( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_beneficiarios_id integer   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      dm_tipo varchar  (2)   NOT NULL  , 
      codigo varchar  (21)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_capital( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      dm_capital_social char  (3)   NOT NULL  , 
      nr_parcela integer   , 
      data_aquisicao date   NOT NULL  , 
      valor float   NOT NULL  , 
      log_import_capital_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_contatos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      tipos_contatos_id integer   NOT NULL  , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_ctb_contato( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      nome varchar  (100)   , 
      numero varchar  (30)   , 
      email varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_dados_bancarios( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      banco_id integer   NOT NULL  , 
      conta_descricao text   , 
      agencia text   NOT NULL  , 
      conta text   NOT NULL  , 
      cooperados_id integer   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_documentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo text   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue varchar  (3)   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
      tipos_documentacoes_id integer   NOT NULL  , 
      cooperados_id integer   NOT NULL  , 
      path_arquivo text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_especialidades( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      especialidades_id integer   NOT NULL  , 
      rqe integer   , 
      imprime_guia_medico varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_movimentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp   , 
      cooperados_id integer   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro timestamp   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_secretaria( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cooperados_id integer   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      contato varchar  (150)   , 
      email varchar  (150)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
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
      nr_dias_aviso_reaj integer   , 
      flg_dias_padrao char  (1)   , 
      dm_tipo char  (2)   , 
      dt_descredenciamento date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_contatos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      contato varchar  (100)   , 
      credenciados_id integer   NOT NULL  , 
      tipos_contatos_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_dados_bancarios( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      conta_descricao varchar  (150)   , 
      agencia varchar  (20)   NOT NULL  , 
      conta varchar  (20)   NOT NULL  , 
      ativo char  (1)   NOT NULL  , 
      desativado date   , 
      observacao varchar  (255)   , 
      bancos_id integer   NOT NULL  , 
      credenciados_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_documentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo char  (1)   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue char  (1)   NOT NULL  , 
      credenciados_id integer   NOT NULL  , 
      tipos_documentacoes_id integer   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
      arquivo_path text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_enderecos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero text   , 
      bairro varchar  (100)   , 
      iss text   , 
      cnes text   , 
      inscricao_municipal integer   , 
      credenciados_id integer   NOT NULL  , 
      cidades_id integer   NOT NULL  , 
      tipos_enderecos_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_especialidades( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      rqe integer   , 
      imprime_guia_medico char  (1)   NOT NULL  , 
      credenciados_id integer   NOT NULL  , 
      especialidades_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_movimentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp   , 
      credenciados_id integer   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro timestamp   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_reajuste( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      credenciados_id integer   NOT NULL  , 
      data_reajuste date   NOT NULL  , 
      dm_reaj_contr char  (3)   NOT NULL  , 
      ultimo_indice float   , 
      indice float   NOT NULL  , 
      dm_tipo_doc char  (1)   NOT NULL  , 
      observacao varchar  (255)   , 
      path_anexo text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_responsaveis( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      credenciados_id integer   NOT NULL  , 
      categoria_responsavel_id integer   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cooperado varchar  (3)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      descricao text   NOT NULL  , 
      ativo varchar  (10)   , 
      tipos_documentacoes_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_cooperados( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      documentacoes_id integer   NOT NULL  , 
      tipos_documentacoes_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_credenciado( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp     DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      tipos_documentacoes_id integer   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (255)   , 
      flg_colorir_pad char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_relacionamento( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      dominio_id integer   NOT NULL  , 
      objeto varchar  (62)   NOT NULL  , 
      atributo varchar  (62)   NOT NULL  , 
      valor_padrao varchar  (45)   , 
      flg_colorir varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_valor( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      dominio_id integer   NOT NULL  , 
      sequencia integer   NOT NULL  , 
      valor varchar  (45)   NOT NULL  , 
      mascara varchar  (255)   NOT NULL  , 
      cor_letra varchar  (9)   , 
      cor_fundo varchar  (9)   , 
      icone varchar  (62)   , 
      mascara_html text   , 
      cor_grafico varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE enderecos_cooperados( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero integer   , 
      cidades_id integer   NOT NULL  , 
      bairro varchar  (100)   , 
      cooperados_id integer   NOT NULL  , 
      tipos_enderecos_id integer   NOT NULL  , 
      iss text   , 
      im integer   , 
      cnes text   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE especialidades( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      especialidade text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estados( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      estado varchar  (50)   NOT NULL  , 
      sigla char  (2)   , 
      cod_ibge char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE expressao( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      expressao varchar  (256)   NOT NULL  , 
      dm_tipo char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      assunto varchar  (256)   NOT NULL  , 
      destinatario varchar  (256)   NOT NULL  , 
      corpo longblob   , 
      data_envio timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_anexo( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      fila_emails_id integer   NOT NULL  , 
      caminho varchar  (256)   NOT NULL  , 
      nome varchar  (256)   NOT NULL  , 
      extensao varchar  (20)   NOT NULL  , 
      tamanho varchar  (20)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_log( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      fila_emails_id integer   NOT NULL  , 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      mensagem varchar  (4000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      nome varchar  (150)   NOT NULL  , 
      dm_situacao char  (1)   NOT NULL    DEFAULT 'A', 
      dm_tipo char  (1)   , 
      intervalo integer   , 
      periodo varchar  (20)   , 
      dt_inicio timestamp   , 
      dt_termino timestamp   , 
      request text   , 
      dm_tipo_req varchar  (15)   , 
      params_req text   , 
      dt_prox_exec timestamp   , 
      usuario_notif_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job_exec( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      job_id integer   NOT NULL  , 
      dt_inicio datetime  (6)   NOT NULL  , 
      dt_termino datetime  (6)   , 
      dm_status char  (1)   NOT NULL  , 
      mensagem text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE label_email( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      label varchar  (45)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      chave varchar  (62)   NOT NULL  , 
      script_sql text   NOT NULL  , 
      dm_tipo char  (1)   NOT NULL    DEFAULT 'U', 
      titulo varchar  (45)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_import_capital( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      system_user_id integer   NOT NULL  , 
      nome_arq varchar  (255)   NOT NULL  , 
      dt_inicio timestamp   , 
      dt_termino timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      crm varchar  (20)   , 
      data_contrato date   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      cpf varchar  (14)   , 
      rg varchar  (20)   , 
      sexo char  (1)   , 
      data_nascimento date   , 
      ativo char  (1)   , 
      estado_civil char  (1)   , 
      path_foto text   , 
      flg_envio_dados char  (1)   , 
      data_encerramento_contrato date   , 
      cnpj varchar  (14)   , 
      cnes varchar  (7)   , 
      cod_plantonista varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_contatos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      medicos_pf_id integer   NOT NULL  , 
      tipos_contatos_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_dados_bancarios( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      conta_descricao text   , 
      agencia text   NOT NULL  , 
      conta text   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao text   , 
      medicos_pf_id integer   NOT NULL  , 
      bancos_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo text   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue varchar  (3)   NOT NULL  , 
      path_arquivo text   , 
      medicos_pf_id integer   NOT NULL  , 
      tipos_documentacoes_id integer   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes_padrao( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      tipos_documentacoes_id integer   NOT NULL  , 
      documentacoes_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_enderecos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero integer   , 
      bairro varchar  (100)   , 
      iss text   , 
      im integer   , 
      cnes text   , 
      medicos_pf_id integer   NOT NULL  , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      tipos_enderecos_id integer   NOT NULL  , 
      cidades_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_especialidades( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      rqe integer   , 
      imprime_guia_medico varchar  (10)   , 
      especialidades_id integer   NOT NULL  , 
      medicos_pf_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_movimentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp   , 
      medicos_pf_id integer   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro timestamp   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parametro( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (256)   , 
      dm_tipo_param varchar  (3)   NOT NULL  , 
      dominio_id integer   , 
      separador varchar  (2)   , 
      valor text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      titulo varchar  (100)   NOT NULL  , 
      dm_tipo char  (2)   NOT NULL  , 
      dm_papel char  (3)   NOT NULL  , 
      dm_orientacao char  (1)   NOT NULL  , 
      cor_fundo varchar  (9)   , 
      margem_sup integer   , 
      margem_inf integer   , 
      margem_esq integer   , 
      margem_dir integer   , 
      flg_borda_sup char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_inf char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_esq char  (1)   NOT NULL    DEFAULT 'N', 
      flg_borda_dir char  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_banda( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_banda char  (2)   NOT NULL  , 
      altura integer   NOT NULL  , 
      sequencia integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_parametro( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp(), 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      relatorio_id integer   NOT NULL  , 
      sequencia integer   NOT NULL  , 
      codigo varchar  (45)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_atributo char  (1)   NOT NULL  , 
      dm_apresentacao char  (3)   , 
      mascara varchar  (45)   , 
      flg_obrigatorio char  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id)) ; 

CREATE TABLE rel_imagem( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL  , 
      data_alteracao timestamp   , 
      nome varchar  (100)   , 
      img text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE templates_email( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      corpo longblob   , 
      expr_assunto_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_beneficios( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      tipo_beneficio varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_contatos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      tipo_contato varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_documentacoes( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
      data_alteracao timestamp     DEFAULT current_timestamp(), 
      tipo_documento varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_enderecos( 
      id  SERIAL    NOT NULL  , 
      data_criacao timestamp   NOT NULL    DEFAULT current_timestamp, 
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
 
 
 CREATE index idx_acordo_especialidades_id on acordo(especialidades_id); 
CREATE index idx_acordo_arquivo_acordo_id on acordo_arquivo(acordo_id); 
CREATE index idx_arquivos_tipos_documentacoes_id on arquivos(tipos_documentacoes_id); 
CREATE index idx_arquivos_documentacoes_id on arquivos(documentacoes_id); 
CREATE index idx_arquivos_arquivos_status_id on arquivos(arquivos_status_id); 
CREATE index idx_arquivos_cooperado_arquivos_status_id on arquivos_cooperado(arquivos_status_id); 
CREATE index idx_arquivos_cooperado_cooperado_id on arquivos_cooperado(cooperado_id); 
CREATE index idx_arquivos_cooperado_arquivos_id on arquivos_cooperado(arquivos_id); 
CREATE index idx_beneficios_tipo_beneficio_id on beneficios(tipo_beneficio_id); 
CREATE index idx_beneficios_cooperados_id on beneficios(cooperados_id); 
CREATE index idx_catalogo_cidades_id on catalogo(cidades_id); 
CREATE index idx_catalogo_contato_catalogo_id on catalogo_contato(catalogo_id); 
CREATE index idx_catalogo_endereco_catalogo_id on catalogo_endereco(catalogo_id); 
CREATE index idx_catalogo_endereco_cidades_id on catalogo_endereco(cidades_id); 
CREATE index idx_catalogo_especialidade_especialidades_id on catalogo_especialidade(especialidades_id); 
CREATE index idx_catalogo_especialidade_catalogo_id on catalogo_especialidade(catalogo_id); 
CREATE index idx_cidades_estados_id on cidades(estados_id); 
CREATE index idx_contrato_servico_contrato_id on contrato_servico(contrato_id); 
CREATE index idx_contrato_valor_contrato_id on contrato_valor(contrato_id); 
CREATE index idx_cooperados_beneficiarios_cooperados_id on cooperados_beneficiarios(cooperados_id); 
CREATE index idx_cooperados_beneficiarios_dep_cooperados_beneficiarios_id on cooperados_beneficiarios_dep(cooperados_beneficiarios_id); 
CREATE index idx_cooperados_capital_log_import_capital_id on cooperados_capital(log_import_capital_id); 
CREATE index idx_cooperados_capital_cooperados_id on cooperados_capital(cooperados_id); 
CREATE index idx_cooperados_contatos_cooperados_id on cooperados_contatos(cooperados_id); 
CREATE index idx_cooperados_contatos_tipos_contatos_id on cooperados_contatos(tipos_contatos_id); 
CREATE index idx_cooperados_ctb_contato_cooperados_id on cooperados_ctb_contato(cooperados_id); 
CREATE index idx_cooperados_dados_bancarios_banco_id on cooperados_dados_bancarios(banco_id); 
CREATE index idx_cooperados_dados_bancarios_cooperados_id on cooperados_dados_bancarios(cooperados_id); 
CREATE index idx_cooperados_documentacoes_documentacoes_id on cooperados_documentacoes(documentacoes_id); 
CREATE index idx_cooperados_documentacoes_tipos_documentacoes_id on cooperados_documentacoes(tipos_documentacoes_id); 
CREATE index idx_cooperados_documentacoes_cooperados_id on cooperados_documentacoes(cooperados_id); 
CREATE index idx_cooperados_especialidades_cooperados_id on cooperados_especialidades(cooperados_id); 
CREATE index idx_cooperados_especialidades_especialidades_id on cooperados_especialidades(especialidades_id); 
CREATE index idx_cooperados_movimentacoes_cooperados_id on cooperados_movimentacoes(cooperados_id); 
CREATE index idx_cooperados_secretaria_cooperados_id on cooperados_secretaria(cooperados_id); 
CREATE index idx_credenciados_contatos_credenciados_id on credenciados_contatos(credenciados_id); 
CREATE index idx_credenciados_contatos_tipos_contatos_id on credenciados_contatos(tipos_contatos_id); 
CREATE index idx_credenciados_dados_bancarios_bancos_id on credenciados_dados_bancarios(bancos_id); 
CREATE index idx_credenciados_dados_bancarios_credenciados_id on credenciados_dados_bancarios(credenciados_id); 
CREATE index idx_credenciados_documentacoes_credenciados_id on credenciados_documentacoes(credenciados_id); 
CREATE index idx_credenciados_documentacoes_tipos_documentacoes_id on credenciados_documentacoes(tipos_documentacoes_id); 
CREATE index idx_credenciados_documentacoes_documentacoes_id on credenciados_documentacoes(documentacoes_id); 
CREATE index idx_credenciados_enderecos_credenciados_id on credenciados_enderecos(credenciados_id); 
CREATE index idx_credenciados_enderecos_cidades_id on credenciados_enderecos(cidades_id); 
CREATE index idx_credenciados_enderecos_tipos_enderecos_id on credenciados_enderecos(tipos_enderecos_id); 
CREATE index idx_credenciados_especialidades_credenciados_id on credenciados_especialidades(credenciados_id); 
CREATE index idx_credenciados_especialidades_especialidades_id on credenciados_especialidades(especialidades_id); 
CREATE index idx_credenciados_movimentacoes_credenciados_id on credenciados_movimentacoes(credenciados_id); 
CREATE index idx_credenciados_reajuste_credenciados_id on credenciados_reajuste(credenciados_id); 
CREATE index idx_credenciados_responsaveis_credenciados_id on credenciados_responsaveis(credenciados_id); 
CREATE index idx_credenciados_responsaveis_categoria_responsavel_id on credenciados_responsaveis(categoria_responsavel_id); 
CREATE index idx_documentacoes_tipos_documentacoes_id on documentacoes(tipos_documentacoes_id); 
CREATE index idx_documentacoes_padrao_cooperados_documentacoes_id on documentacoes_padrao_cooperados(documentacoes_id); 
CREATE index idx_documentacoes_padrao_cooperados_tipos_documentacoes_id on documentacoes_padrao_cooperados(tipos_documentacoes_id); 
CREATE index idx_documentacoes_padrao_credenciado_tipos_documentacoes_id on documentacoes_padrao_credenciado(tipos_documentacoes_id); 
CREATE index idx_documentacoes_padrao_credenciado_documentacoes_id on documentacoes_padrao_credenciado(documentacoes_id); 
CREATE index idx_dominio_relacionamento_dominio_id on dominio_relacionamento(dominio_id); 
CREATE index idx_dominio_valor_dominio_id on dominio_valor(dominio_id); 
CREATE index idx_enderecos_cooperados_cooperados_id on enderecos_cooperados(cooperados_id); 
CREATE index idx_enderecos_cooperados_tipos_enderecos_id on enderecos_cooperados(tipos_enderecos_id); 
CREATE index idx_enderecos_cooperados_cidades_id on enderecos_cooperados(cidades_id); 
CREATE index idx_fila_email_anexo_fila_emails_id on fila_email_anexo(fila_emails_id); 
CREATE index idx_fila_email_log_fila_emails_id on fila_email_log(fila_emails_id); 
CREATE index idx_job_exec_job_id on job_exec(job_id); 
CREATE index idx_medicos_pf_contatos_medicos_pf_id on medicos_pf_contatos(medicos_pf_id); 
CREATE index idx_medicos_pf_contatos_tipos_contatos_id on medicos_pf_contatos(tipos_contatos_id); 
CREATE index idx_medicos_pf_dados_bancarios_medicos_pf_id on medicos_pf_dados_bancarios(medicos_pf_id); 
CREATE index idx_medicos_pf_dados_bancarios_bancos_id on medicos_pf_dados_bancarios(bancos_id); 
CREATE index idx_medicos_pf_documentacoes_medicos_pf_id on medicos_pf_documentacoes(medicos_pf_id); 
CREATE index idx_medicos_pf_documentacoes_tipos_documentacoes_id on medicos_pf_documentacoes(tipos_documentacoes_id); 
CREATE index idx_medicos_pf_documentacoes_documentacoes_id on medicos_pf_documentacoes(documentacoes_id); 
CREATE index idx_medicos_pf_documentacoes_padrao_tipos_documentacoes_id on medicos_pf_documentacoes_padrao(tipos_documentacoes_id); 
CREATE index idx_medicos_pf_documentacoes_padrao_documentacoes_id on medicos_pf_documentacoes_padrao(documentacoes_id); 
CREATE index idx_medicos_pf_enderecos_medicos_pf_id on medicos_pf_enderecos(medicos_pf_id); 
CREATE index idx_medicos_pf_enderecos_tipos_enderecos_id on medicos_pf_enderecos(tipos_enderecos_id); 
CREATE index idx_medicos_pf_enderecos_cidades_id on medicos_pf_enderecos(cidades_id); 
CREATE index idx_medicos_pf_especialidades_especialidades_id on medicos_pf_especialidades(especialidades_id); 
CREATE index idx_medicos_pf_especialidades_medicos_pf_id on medicos_pf_especialidades(medicos_pf_id); 
CREATE index idx_medicos_pf_movimentacoes_medicos_pf_id on medicos_pf_movimentacoes(medicos_pf_id); 
CREATE index idx_parametro_dominio_id on parametro(dominio_id); 
CREATE index idx_relatorio_parametro_relatorio_id on relatorio_parametro(relatorio_id); 
CREATE index idx_templates_email_expr_assunto_id on templates_email(expr_assunto_id); 
