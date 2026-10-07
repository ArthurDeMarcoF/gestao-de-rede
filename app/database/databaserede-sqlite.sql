PRAGMA foreign_keys=OFF; 

CREATE TABLE acordo( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nr_jet int   , 
      especialidades_id int   NOT NULL  , 
      medico varchar  (150)   NOT NULL  , 
      carteirinha varchar  (25)   NOT NULL  , 
      beneficiario varchar  (150)   NOT NULL  , 
      valor double   , 
      dt_atendimento date   , 
      dt_nota date   , 
      nr_nota varchar  (20)   , 
      dt_pagamento date   , 
      observacao text   , 
 PRIMARY KEY (id),
FOREIGN KEY(especialidades_id) REFERENCES especialidades(id)) ; 

CREATE TABLE acordo_arquivo( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      acordo_id int   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      path_arquivo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(acordo_id) REFERENCES acordo(id)) ; 

CREATE TABLE api_error( 
      id  INTEGER    NOT NULL  , 
      classe text   , 
      metodo text   , 
      url text   , 
      dados text   , 
      error_message text   , 
      inserted_at datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome_arquivo varchar  (255)   NOT NULL  , 
      ano_base int   NOT NULL  , 
      data_emissao date   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      arquivos_status_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id),
FOREIGN KEY(arquivos_status_id) REFERENCES arquivos_status(id)) ; 

CREATE TABLE arquivos_cooperado( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      arquivos_status_id int   NOT NULL  , 
      cooperado_id int   NOT NULL  , 
      arquivos_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(arquivos_status_id) REFERENCES arquivos_status(id),
FOREIGN KEY(cooperado_id) REFERENCES cooperados(id),
FOREIGN KEY(arquivos_id) REFERENCES arquivos(id)) ; 

CREATE TABLE arquivos_status( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome varchar  (30)   NOT NULL  , 
      descricao varchar  (250)   NOT NULL  , 
      ativo varchar  (3)   NOT NULL    DEFAULT 'Sim', 
      cor varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE bancos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      banco text   NOT NULL  , 
      codigo varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE beneficios( 
      id  INTEGER    NOT NULL  , 
      data_alteracao datetime   , 
      data_criacao datetime   NOT NULL  , 
      beneficio text   , 
      identificador text   , 
      tipo_beneficio_id int   NOT NULL  , 
      ativo varchar  (5)   , 
      data_inicial date   , 
      data_final date   , 
      cooperados_id int   NOT NULL  , 
      valor double   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_beneficio_id) REFERENCES tipos_beneficios(id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE catalogo( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      dm_categoria char  (1)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cpf_cnpj varchar  (30)   NOT NULL  , 
      data_solicitacao date   NOT NULL  , 
      cidades_id int   NOT NULL  , 
      observacao text   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidades_id) REFERENCES cidades(id)) ; 

CREATE TABLE catalogo_contato( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      catalogo_id int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      contato varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(catalogo_id) REFERENCES catalogo(id)) ; 

CREATE TABLE catalogo_endereco( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      catalogo_id int   NOT NULL  , 
      cidades_id int   , 
      bairro varchar  (50)   , 
      logradouro varchar  (50)   , 
      numero varchar  (10)   , 
      cep varchar  (9)   , 
 PRIMARY KEY (id),
FOREIGN KEY(catalogo_id) REFERENCES catalogo(id),
FOREIGN KEY(cidades_id) REFERENCES cidades(id)) ; 

CREATE TABLE catalogo_especialidade( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      catalogo_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(especialidades_id) REFERENCES especialidades(id),
FOREIGN KEY(catalogo_id) REFERENCES catalogo(id)) ; 

CREATE TABLE categoria_responsavel( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
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
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      estados_id int   NOT NULL  , 
      cidade varchar  (150)   NOT NULL  , 
      cod_ibge varchar  (20)   , 
 PRIMARY KEY (id),
FOREIGN KEY(estados_id) REFERENCES estados(id)) ; 

CREATE TABLE contrato( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome varchar  (150)   NOT NULL  , 
      empresa varchar  (150)   NOT NULL  , 
      cnpj varchar  (18)   NOT NULL  , 
      dt_contrato date   NOT NULL  , 
      dt_reajuste date   NOT NULL  , 
      indice double   , 
      path_arquivo text   , 
      dm_situacao char  (1)   , 
      apolice varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_servico( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      contrato_id int   NOT NULL  , 
      servico varchar  (150)   NOT NULL  , 
      dt_inicio date   , 
      dt_termino date   , 
      dm_situacao char  (1)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(contrato_id) REFERENCES contrato(id)) ; 

CREATE TABLE contrato_valor( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      contrato_id int   NOT NULL  , 
      dt_pagamento date   NOT NULL  , 
      valor double   NOT NULL  , 
      nr_nota varchar  (20)   , 
      path_arquivo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(contrato_id) REFERENCES contrato(id)) ; 

CREATE TABLE cooperados( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
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
      path_foto text   , 
      contabilidade varchar  (150)   , 
      flg_envio_dados char  (1)   , 
      dt_desfiliacao date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      codigo text   , 
      tipo varchar  (20)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_beneficiarios_dep( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_beneficiarios_id int   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      dm_tipo varchar  (2)   NOT NULL  , 
      codigo varchar  (21)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_beneficiarios_id) REFERENCES cooperados_beneficiarios(id)) ; 

CREATE TABLE cooperados_capital( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      dm_capital_social char  (3)   NOT NULL  , 
      nr_parcela int   , 
      data_aquisicao date   NOT NULL  , 
      valor double   NOT NULL  , 
      log_import_capital_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(log_import_capital_id) REFERENCES log_import_capital(id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_contatos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id),
FOREIGN KEY(tipos_contatos_id) REFERENCES tipos_contatos(id)) ; 

CREATE TABLE cooperados_ctb_contato( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      nome varchar  (100)   , 
      numero varchar  (30)   , 
      email varchar  (100)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_dados_bancarios( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      banco_id int   NOT NULL  , 
      conta_descricao text   , 
      agencia text   NOT NULL  , 
      conta text   NOT NULL  , 
      cooperados_id int   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao text   , 
 PRIMARY KEY (id),
FOREIGN KEY(banco_id) REFERENCES bancos(id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_documentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo text   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue varchar  (3)   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      cooperados_id int   NOT NULL  , 
      path_arquivo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_especialidades( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
      rqe int   , 
      imprime_guia_medico varchar  (10)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id),
FOREIGN KEY(especialidades_id) REFERENCES especialidades(id)) ; 

CREATE TABLE cooperados_movimentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE cooperados_secretaria( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cooperados_id int   NOT NULL  , 
      nome varchar  (150)   NOT NULL  , 
      contato varchar  (150)   , 
      email varchar  (150)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id)) ; 

CREATE TABLE credenciados( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
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
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      contato varchar  (100)   , 
      credenciados_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id),
FOREIGN KEY(tipos_contatos_id) REFERENCES tipos_contatos(id)) ; 

CREATE TABLE credenciados_dados_bancarios( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      conta_descricao varchar  (150)   , 
      agencia varchar  (20)   NOT NULL  , 
      conta varchar  (20)   NOT NULL  , 
      ativo char  (1)   NOT NULL  , 
      desativado date   , 
      observacao varchar  (255)   , 
      bancos_id int   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(bancos_id) REFERENCES bancos(id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id)) ; 

CREATE TABLE credenciados_documentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo char  (1)   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue char  (1)   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
      arquivo_path text   , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id)) ; 

CREATE TABLE credenciados_enderecos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero text   , 
      bairro varchar  (100)   , 
      iss text   , 
      cnes text   , 
      inscricao_municipal int   , 
      credenciados_id int   NOT NULL  , 
      cidades_id int   NOT NULL  , 
      tipos_enderecos_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id),
FOREIGN KEY(cidades_id) REFERENCES cidades(id),
FOREIGN KEY(tipos_enderecos_id) REFERENCES tipos_enderecos(id)) ; 

CREATE TABLE credenciados_especialidades( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      rqe int  (10)   , 
      imprime_guia_medico char  (1)   NOT NULL  , 
      credenciados_id int   NOT NULL  , 
      especialidades_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id),
FOREIGN KEY(especialidades_id) REFERENCES especialidades(id)) ; 

CREATE TABLE credenciados_movimentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      credenciados_id int   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id)) ; 

CREATE TABLE credenciados_reajuste( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      credenciados_id int   NOT NULL  , 
      data_reajuste date   NOT NULL  , 
      dm_reaj_contr char  (3)   NOT NULL  , 
      ultimo_indice double   , 
      indice double   NOT NULL  , 
      dm_tipo_doc char  (1)   NOT NULL  , 
      observacao varchar  (255)   , 
      path_anexo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id)) ; 

CREATE TABLE credenciados_responsaveis( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      credenciados_id int   NOT NULL  , 
      categoria_responsavel_id int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      cooperado varchar  (3)   , 
 PRIMARY KEY (id),
FOREIGN KEY(credenciados_id) REFERENCES credenciados(id),
FOREIGN KEY(categoria_responsavel_id) REFERENCES categoria_responsavel(id)) ; 

CREATE TABLE documentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      descricao text   NOT NULL  , 
      ativo varchar  (10)   , 
      tipos_documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id)) ; 

CREATE TABLE documentacoes_padrao_cooperados( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      documentacoes_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id)) ; 

CREATE TABLE documentacoes_padrao_credenciado( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   , 
      data_alteracao datetime   , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id)) ; 

CREATE TABLE dominio( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (255)   , 
      flg_colorir_pad char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_relacionamento( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      dominio_id int   NOT NULL  , 
      objeto varchar  (62)   NOT NULL  , 
      atributo varchar  (62)   NOT NULL  , 
      valor_padrao varchar  (45)   , 
      flg_colorir varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id),
FOREIGN KEY(dominio_id) REFERENCES dominio(id)) ; 

CREATE TABLE dominio_valor( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      dominio_id int   NOT NULL  , 
      sequencia int   NOT NULL  , 
      valor varchar  (45)   NOT NULL  , 
      mascara varchar  (255)   NOT NULL  , 
      cor_letra varchar  (9)   , 
      cor_fundo varchar  (9)   , 
      icone varchar  (62)   , 
      mascara_html text   , 
      cor_grafico varchar  (9)   , 
 PRIMARY KEY (id),
FOREIGN KEY(dominio_id) REFERENCES dominio(id)) ; 

CREATE TABLE enderecos_cooperados( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero int   , 
      cidades_id int   NOT NULL  , 
      bairro varchar  (100)   , 
      cooperados_id int   NOT NULL  , 
      tipos_enderecos_id int   NOT NULL  , 
      iss text   , 
      im int   , 
      cnes text   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id),
FOREIGN KEY(cooperados_id) REFERENCES cooperados(id),
FOREIGN KEY(tipos_enderecos_id) REFERENCES tipos_enderecos(id),
FOREIGN KEY(cidades_id) REFERENCES cidades(id)) ; 

CREATE TABLE especialidades( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      especialidade text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estados( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      estado varchar  (50)   NOT NULL  , 
      sigla char  (2)   , 
      cod_ibge char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE expressao( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      expressao varchar  (256)   NOT NULL  , 
      dm_tipo char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      assunto varchar  (256)   NOT NULL  , 
      destinatario varchar  (256)   NOT NULL  , 
      corpo longblob   , 
      data_envio datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_anexo( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      fila_emails_id int   NOT NULL  , 
      caminho varchar  (256)   NOT NULL  , 
      nome varchar  (256)   NOT NULL  , 
      extensao varchar  (20)   NOT NULL  , 
      tamanho varchar  (20)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(fila_emails_id) REFERENCES fila_email(id)) ; 

CREATE TABLE fila_email_log( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      fila_emails_id int   NOT NULL  , 
      dm_status varchar  (2)   NOT NULL    DEFAULT 'P', 
      mensagem varchar  (4000)   , 
 PRIMARY KEY (id),
FOREIGN KEY(fila_emails_id) REFERENCES fila_email(id)) ; 

CREATE TABLE job( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome varchar  (150)   NOT NULL  , 
      dm_situacao char  (1)   NOT NULL    DEFAULT 'A', 
      dm_tipo char  (1)   , 
      intervalo int   , 
      periodo varchar  (20)   , 
      dt_inicio datetime   , 
      dt_termino datetime   , 
      request text   , 
      dm_tipo_req varchar  (15)   , 
      params_req text   , 
      dt_prox_exec datetime   , 
      usuario_notif_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job_exec( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      job_id int   NOT NULL  , 
      dt_inicio datetime  (6)   NOT NULL  , 
      dt_termino datetime  (6)   , 
      dm_status char  (1)   NOT NULL  , 
      mensagem text   , 
 PRIMARY KEY (id),
FOREIGN KEY(job_id) REFERENCES job(id)) ; 

CREATE TABLE label_email( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      label varchar  (45)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      chave varchar  (62)   NOT NULL  , 
      script_sql text   NOT NULL  , 
      dm_tipo char  (1)   NOT NULL    DEFAULT 'U', 
      titulo varchar  (45)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_import_capital( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      system_user_id int   NOT NULL  , 
      nome_arq varchar  (255)   NOT NULL  , 
      dt_inicio datetime   , 
      dt_termino datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
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
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      medicos_pf_id int   NOT NULL  , 
      tipos_contatos_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id),
FOREIGN KEY(tipos_contatos_id) REFERENCES tipos_contatos(id)) ; 

CREATE TABLE medicos_pf_dados_bancarios( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      conta_descricao text   , 
      agencia text   NOT NULL  , 
      conta text   NOT NULL  , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao text   , 
      medicos_pf_id int   NOT NULL  , 
      bancos_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id),
FOREIGN KEY(bancos_id) REFERENCES bancos(id)) ; 

CREATE TABLE medicos_pf_documentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo text   NOT NULL  , 
      conteudo text   , 
      observacao text   , 
      entregue varchar  (3)   NOT NULL  , 
      path_arquivo text   , 
      medicos_pf_id int   NOT NULL  , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id)) ; 

CREATE TABLE medicos_pf_documentacoes_padrao( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      tipos_documentacoes_id int   NOT NULL  , 
      documentacoes_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(tipos_documentacoes_id) REFERENCES tipos_documentacoes(id),
FOREIGN KEY(documentacoes_id) REFERENCES documentacoes(id)) ; 

CREATE TABLE medicos_pf_enderecos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      cep varchar  (9)   NOT NULL  , 
      endereco varchar  (150)   NOT NULL  , 
      numero int   , 
      bairro varchar  (100)   , 
      iss text   , 
      im int   , 
      cnes text   , 
      medicos_pf_id int   NOT NULL  , 
      imprime_guia_medico varchar  (1)   NOT NULL    DEFAULT 'N', 
      tipos_enderecos_id int   NOT NULL  , 
      cidades_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id),
FOREIGN KEY(tipos_enderecos_id) REFERENCES tipos_enderecos(id),
FOREIGN KEY(cidades_id) REFERENCES cidades(id)) ; 

CREATE TABLE medicos_pf_especialidades( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      rqe int   , 
      imprime_guia_medico varchar  (10)   , 
      especialidades_id int   NOT NULL  , 
      medicos_pf_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(especialidades_id) REFERENCES especialidades(id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id)) ; 

CREATE TABLE medicos_pf_movimentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      medicos_pf_id int   NOT NULL  , 
      descricao text   NOT NULL  , 
      usuario varchar  (50)   NOT NULL  , 
      data_registro datetime   NOT NULL  , 
      assunto varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(medicos_pf_id) REFERENCES medicos_pf(id)) ; 

CREATE TABLE parametro( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      descricao varchar  (256)   , 
      dm_tipo_param varchar  (3)   NOT NULL  , 
      dominio_id int   , 
      separador varchar  (2)   , 
      valor text   , 
 PRIMARY KEY (id),
FOREIGN KEY(dominio_id) REFERENCES dominio(id)) ; 

CREATE TABLE relatorio( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
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
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_banda char  (2)   NOT NULL  , 
      altura int   NOT NULL  , 
      sequencia int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_parametro( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      relatorio_id int   NOT NULL  , 
      sequencia int   NOT NULL  , 
      codigo varchar  (45)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      dm_tip_atributo char  (1)   NOT NULL  , 
      dm_apresentacao char  (3)   , 
      mascara varchar  (45)   , 
      flg_obrigatorio char  (1)   NOT NULL    DEFAULT 'N', 
 PRIMARY KEY (id),
FOREIGN KEY(relatorio_id) REFERENCES relatorio(id)) ; 

CREATE TABLE rel_imagem( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      nome varchar  (100)   , 
      img text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE templates_email( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      codigo varchar  (45)   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
      corpo longblob   , 
      expr_assunto_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(expr_assunto_id) REFERENCES expressao(id)) ; 

CREATE TABLE tipos_beneficios( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      tipo_beneficio varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_contatos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      tipo_contato varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_documentacoes( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      data_alteracao datetime   , 
      tipo_documento varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_enderecos( 
      id  INTEGER    NOT NULL  , 
      data_criacao datetime   NOT NULL  , 
      tipo_endereco varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 CREATE UNIQUE INDEX unique_idx_catalogo_cpf_cnpj ON catalogo(cpf_cnpj);
 CREATE UNIQUE INDEX unique_idx_cooperados_cpf ON cooperados(cpf);
 CREATE UNIQUE INDEX unique_idx_credenciados_cnpj ON credenciados(cnpj);
 CREATE UNIQUE INDEX unique_idx_dominio_codigo ON dominio(codigo);
 CREATE UNIQUE INDEX unique_idx_label_email_label ON label_email(label);
 CREATE UNIQUE INDEX unique_idx_parametro_codigo ON parametro(codigo);
 CREATE UNIQUE INDEX unique_idx_templates_email_codigo ON templates_email(codigo);
 
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
 
