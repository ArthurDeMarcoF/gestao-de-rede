CREATE TABLE acordo( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      nr_jet number(10)   , 
      especialidades_id number(10)    NOT NULL , 
      medico varchar  (150)    NOT NULL , 
      carteirinha varchar  (25)    NOT NULL , 
      beneficiario varchar  (150)    NOT NULL , 
      valor binary_double   , 
      dt_atendimento date   , 
      dt_nota date   , 
      nr_nota varchar  (20)   , 
      dt_pagamento date   , 
      observacao varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE acordo_arquivo( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      acordo_id number(10)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      path_arquivo varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE api_error( 
      id number(10)    NOT NULL , 
      classe varchar(3000)   , 
      metodo varchar(3000)   , 
      url varchar(3000)   , 
      dados varchar(3000)   , 
      error_message varchar(3000)   , 
      inserted_at timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp , 
      nome_arquivo varchar  (255)    NOT NULL , 
      ano_base number(10)    NOT NULL , 
      data_emissao date    NOT NULL , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
      arquivos_status_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_cooperado( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      arquivos_status_id number(10)    NOT NULL , 
      cooperado_id number(10)    NOT NULL , 
      arquivos_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE arquivos_status( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      nome varchar  (30)    NOT NULL , 
      descricao varchar  (250)    NOT NULL , 
      ativo varchar  (3)    DEFAULT 'Sim'  NOT NULL , 
      cor varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE bancos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      banco varchar(3000)    NOT NULL , 
      codigo varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE beneficios( 
      id number(10)    NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      beneficio varchar(3000)   , 
      identificador varchar(3000)   , 
      tipo_beneficio_id number(10)    NOT NULL , 
      ativo varchar  (5)   , 
      data_inicial date   , 
      data_final date   , 
      cooperados_id number(10)    NOT NULL , 
      valor binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      dm_categoria char  (1)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      cpf_cnpj varchar  (30)    NOT NULL , 
      data_solicitacao date    NOT NULL , 
      cidades_id number(10)    NOT NULL , 
      observacao varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_contato( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      catalogo_id number(10)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      contato varchar  (100)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_endereco( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      catalogo_id number(10)    NOT NULL , 
      cidades_id number(10)   , 
      bairro varchar  (50)   , 
      logradouro varchar  (50)   , 
      numero varchar  (10)   , 
      cep varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE catalogo_especialidade( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      catalogo_id number(10)    NOT NULL , 
      especialidades_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_responsavel( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)   , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cep varchar  (8)   , 
      rua varchar  (500)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf char  (2)   , 
      cidades_id number(10)   , 
      estados_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidades( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      estados_id number(10)    NOT NULL , 
      cidade varchar  (150)    NOT NULL , 
      cod_ibge varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      nome varchar  (150)    NOT NULL , 
      empresa varchar  (150)    NOT NULL , 
      cnpj varchar  (18)    NOT NULL , 
      dt_contrato date    NOT NULL , 
      dt_reajuste date    NOT NULL , 
      indice binary_double   , 
      path_arquivo varchar(3000)   , 
      dm_situacao char  (1)   , 
      apolice varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_servico( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      contrato_id number(10)    NOT NULL , 
      servico varchar  (150)    NOT NULL , 
      dt_inicio date   , 
      dt_termino date   , 
      dm_situacao char  (1)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contrato_valor( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      contrato_id number(10)    NOT NULL , 
      dt_pagamento date    NOT NULL , 
      valor binary_double    NOT NULL , 
      nr_nota varchar  (20)   , 
      path_arquivo varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      crm varchar  (20)   , 
      inss varchar  (15)   , 
      data_filiacao date    NOT NULL , 
      nome varchar  (150)    NOT NULL , 
      cpf varchar  (14)   , 
      rg varchar  (20)   , 
      sexo char  (1)   , 
      data_nascimento date   , 
      ativo char  (1)   , 
      forma_integralizacao varchar  (50)   , 
      estado_civil char  (1)   , 
      numero_filhos number(10)   , 
      cnis varchar  (20)   , 
      flg_retem_ir char  (1)    DEFAULT 'N'  NOT NULL , 
      flg_declara_dep char  (1)    DEFAULT 'N'  NOT NULL , 
      flg_recolhe_inss char  (1)    DEFAULT 'N'  NOT NULL , 
      path_foto varchar(3000)   , 
      contabilidade varchar  (150)   , 
      flg_envio_dados char  (1)   , 
      dt_desfiliacao date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      codigo varchar(3000)   , 
      tipo varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_beneficiarios_dep( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_beneficiarios_id number(10)    NOT NULL , 
      nome varchar  (150)    NOT NULL , 
      dm_tipo varchar  (2)    NOT NULL , 
      codigo varchar  (21)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_capital( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      dm_capital_social char  (3)    NOT NULL , 
      nr_parcela number(10)   , 
      data_aquisicao date    NOT NULL , 
      valor binary_double    NOT NULL , 
      log_import_capital_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_contatos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      tipos_contatos_id number(10)    NOT NULL , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)    DEFAULT 'N'  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_ctb_contato( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      nome varchar  (100)   , 
      numero varchar  (30)   , 
      email varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_dados_bancarios( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      banco_id number(10)    NOT NULL , 
      conta_descricao varchar(3000)   , 
      agencia varchar(3000)    NOT NULL , 
      conta varchar(3000)    NOT NULL , 
      cooperados_id number(10)    NOT NULL , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_documentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo varchar(3000)    NOT NULL , 
      conteudo varchar(3000)   , 
      observacao varchar(3000)   , 
      entregue varchar  (3)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      cooperados_id number(10)    NOT NULL , 
      path_arquivo varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_especialidades( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      especialidades_id number(10)    NOT NULL , 
      rqe number(10)   , 
      imprime_guia_medico varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_movimentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)   , 
      cooperados_id number(10)    NOT NULL , 
      descricao varchar(3000)    NOT NULL , 
      usuario varchar  (50)    NOT NULL , 
      data_registro timestamp(0)    NOT NULL , 
      assunto varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cooperados_secretaria( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cooperados_id number(10)    NOT NULL , 
      nome varchar  (150)    NOT NULL , 
      contato varchar  (150)   , 
      email varchar  (150)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      data_inicio date    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      cnpj varchar  (18)    NOT NULL , 
      cnes varchar  (15)   , 
      inscricao_estadual varchar  (15)   , 
      ativo char  (1)   , 
      enquadramento_tributario char  (2)   , 
      codigo_prestador varchar  (15)   , 
      data_contrato date   , 
      dm_reaj_contr char  (3)   , 
      nr_dias_aviso_reaj number(10)   , 
      flg_dias_padrao char  (1)   , 
      dm_tipo char  (2)   , 
      dt_descredenciamento date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_contatos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      contato varchar  (100)   , 
      credenciados_id number(10)    NOT NULL , 
      tipos_contatos_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_dados_bancarios( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      conta_descricao varchar  (150)   , 
      agencia varchar  (20)    NOT NULL , 
      conta varchar  (20)    NOT NULL , 
      ativo char  (1)    NOT NULL , 
      desativado date   , 
      observacao varchar  (255)   , 
      bancos_id number(10)    NOT NULL , 
      credenciados_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_documentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo char  (1)    NOT NULL , 
      conteudo varchar(3000)   , 
      observacao varchar(3000)   , 
      entregue char  (1)    NOT NULL , 
      credenciados_id number(10)    NOT NULL , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
      arquivo_path varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_enderecos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cep varchar  (9)    NOT NULL , 
      endereco varchar  (150)    NOT NULL , 
      numero varchar(3000)   , 
      bairro varchar  (100)   , 
      iss varchar(3000)   , 
      cnes varchar(3000)   , 
      inscricao_municipal number(10)   , 
      credenciados_id number(10)    NOT NULL , 
      cidades_id number(10)    NOT NULL , 
      tipos_enderecos_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_especialidades( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      rqe number(10)  (10)   , 
      imprime_guia_medico char  (1)    NOT NULL , 
      credenciados_id number(10)    NOT NULL , 
      especialidades_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_movimentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)   , 
      credenciados_id number(10)    NOT NULL , 
      descricao varchar(3000)    NOT NULL , 
      usuario varchar  (50)    NOT NULL , 
      data_registro timestamp(0)    NOT NULL , 
      assunto varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_reajuste( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      credenciados_id number(10)    NOT NULL , 
      data_reajuste date    NOT NULL , 
      dm_reaj_contr char  (3)    NOT NULL , 
      ultimo_indice binary_double   , 
      indice binary_double    NOT NULL , 
      dm_tipo_doc char  (1)    NOT NULL , 
      observacao varchar  (255)   , 
      path_anexo varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credenciados_responsaveis( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      credenciados_id number(10)    NOT NULL , 
      categoria_responsavel_id number(10)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      cooperado varchar  (3)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      descricao varchar(3000)    NOT NULL , 
      ativo varchar  (10)   , 
      tipos_documentacoes_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_cooperados( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      documentacoes_id number(10)    NOT NULL , 
      tipos_documentacoes_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documentacoes_padrao_credenciado( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      codigo varchar  (45)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      descricao varchar  (255)   , 
      flg_colorir_pad char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_relacionamento( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      dominio_id number(10)    NOT NULL , 
      objeto varchar  (62)    NOT NULL , 
      atributo varchar  (62)    NOT NULL , 
      valor_padrao varchar  (45)   , 
      flg_colorir varchar  (1)    DEFAULT 'N'  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dominio_valor( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      dominio_id number(10)    NOT NULL , 
      sequencia number(10)    NOT NULL , 
      valor varchar  (45)    NOT NULL , 
      mascara varchar  (255)    NOT NULL , 
      cor_letra varchar  (9)   , 
      cor_fundo varchar  (9)   , 
      icone varchar  (62)   , 
      mascara_html varchar(3000)   , 
      cor_grafico varchar  (9)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE enderecos_cooperados( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cep varchar  (9)    NOT NULL , 
      endereco varchar  (150)    NOT NULL , 
      numero number(10)   , 
      cidades_id number(10)    NOT NULL , 
      bairro varchar  (100)   , 
      cooperados_id number(10)    NOT NULL , 
      tipos_enderecos_id number(10)    NOT NULL , 
      iss varchar(3000)   , 
      im number(10)   , 
      cnes varchar(3000)   , 
      imprime_guia_medico varchar  (1)    DEFAULT 'N'  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE especialidades( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      especialidade varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estados( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      estado varchar  (50)    NOT NULL , 
      sigla char  (2)   , 
      cod_ibge char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE expressao( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      expressao varchar  (256)    NOT NULL , 
      dm_tipo char  (2)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      dm_status varchar  (2)    DEFAULT 'P'  NOT NULL , 
      assunto varchar  (256)    NOT NULL , 
      destinatario varchar  (256)    NOT NULL , 
      corpo longblob   , 
      data_envio timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_anexo( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      fila_emails_id number(10)    NOT NULL , 
      caminho varchar  (256)    NOT NULL , 
      nome varchar  (256)    NOT NULL , 
      extensao varchar  (20)    NOT NULL , 
      tamanho varchar  (20)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fila_email_log( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      fila_emails_id number(10)    NOT NULL , 
      dm_status varchar  (2)    DEFAULT 'P'  NOT NULL , 
      mensagem varchar  (4000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      nome varchar  (150)    NOT NULL , 
      dm_situacao char  (1)    DEFAULT 'A'  NOT NULL , 
      dm_tipo char  (1)   , 
      intervalo number(10)   , 
      periodo varchar  (20)   , 
      dt_inicio timestamp(0)   , 
      dt_termino timestamp(0)   , 
      request varchar(3000)   , 
      dm_tipo_req varchar  (15)   , 
      params_req varchar(3000)   , 
      dt_prox_exec timestamp(0)   , 
      usuario_notif_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE job_exec( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      job_id number(10)    NOT NULL , 
      dt_inicio datetime  (6)    NOT NULL , 
      dt_termino datetime  (6)   , 
      dm_status char  (1)    NOT NULL , 
      mensagem varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE label_email( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      label varchar  (45)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      chave varchar  (62)    NOT NULL , 
      script_sql varchar(3000)    NOT NULL , 
      dm_tipo char  (1)    DEFAULT 'U'  NOT NULL , 
      titulo varchar  (45)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_import_capital( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      system_user_id number(10)    NOT NULL , 
      nome_arq varchar  (255)    NOT NULL , 
      dt_inicio timestamp(0)   , 
      dt_termino timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      crm varchar  (20)   , 
      data_contrato date    NOT NULL , 
      nome varchar  (150)    NOT NULL , 
      cpf varchar  (14)   , 
      rg varchar  (20)   , 
      sexo char  (1)   , 
      data_nascimento date   , 
      ativo char  (1)   , 
      estado_civil char  (1)   , 
      path_foto varchar(3000)   , 
      flg_envio_dados char  (1)   , 
      data_encerramento_contrato date   , 
      cnpj varchar  (14)   , 
      cnes varchar  (7)   , 
      cod_plantonista varchar  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_contatos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      contato varchar  (100)   , 
      imprime_guia_medico varchar  (1)    DEFAULT 'N'  NOT NULL , 
      medicos_pf_id number(10)    NOT NULL , 
      tipos_contatos_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_dados_bancarios( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      conta_descricao varchar(3000)   , 
      agencia varchar(3000)    NOT NULL , 
      conta varchar(3000)    NOT NULL , 
      ativo varchar  (3)   , 
      desativado date   , 
      observacao varchar(3000)   , 
      medicos_pf_id number(10)    NOT NULL , 
      bancos_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      emissao date   , 
      validade date   , 
      data_alerta date   , 
      ativo varchar(3000)    NOT NULL , 
      conteudo varchar(3000)   , 
      observacao varchar(3000)   , 
      entregue varchar  (3)    NOT NULL , 
      path_arquivo varchar(3000)   , 
      medicos_pf_id number(10)    NOT NULL , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_documentacoes_padrao( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      tipos_documentacoes_id number(10)    NOT NULL , 
      documentacoes_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_enderecos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      cep varchar  (9)    NOT NULL , 
      endereco varchar  (150)    NOT NULL , 
      numero number(10)   , 
      bairro varchar  (100)   , 
      iss varchar(3000)   , 
      im number(10)   , 
      cnes varchar(3000)   , 
      medicos_pf_id number(10)    NOT NULL , 
      imprime_guia_medico varchar  (1)    DEFAULT 'N'  NOT NULL , 
      tipos_enderecos_id number(10)    NOT NULL , 
      cidades_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_especialidades( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      rqe number(10)   , 
      imprime_guia_medico varchar  (10)   , 
      especialidades_id number(10)    NOT NULL , 
      medicos_pf_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE medicos_pf_movimentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)   , 
      medicos_pf_id number(10)    NOT NULL , 
      descricao varchar(3000)    NOT NULL , 
      usuario varchar  (50)    NOT NULL , 
      data_registro timestamp(0)    NOT NULL , 
      assunto varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parametro( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      codigo varchar  (45)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      descricao varchar  (256)   , 
      dm_tipo_param varchar  (3)    NOT NULL , 
      dominio_id number(10)   , 
      separador varchar  (2)   , 
      valor varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      titulo varchar  (100)    NOT NULL , 
      dm_tipo char  (2)    NOT NULL , 
      dm_papel char  (3)    NOT NULL , 
      dm_orientacao char  (1)    NOT NULL , 
      cor_fundo varchar  (9)   , 
      margem_sup number(10)   , 
      margem_inf number(10)   , 
      margem_esq number(10)   , 
      margem_dir number(10)   , 
      flg_borda_sup char  (1)    DEFAULT 'N'  NOT NULL , 
      flg_borda_inf char  (1)    DEFAULT 'N'  NOT NULL , 
      flg_borda_esq char  (1)    DEFAULT 'N'  NOT NULL , 
      flg_borda_dir char  (1)    DEFAULT 'N'  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_banda( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      descricao varchar  (100)    NOT NULL , 
      dm_tip_banda char  (2)    NOT NULL , 
      altura number(10)    NOT NULL , 
      sequencia number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE relatorio_parametro( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp()  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      relatorio_id number(10)    NOT NULL , 
      sequencia number(10)    NOT NULL , 
      codigo varchar  (45)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      dm_tip_atributo char  (1)    NOT NULL , 
      dm_apresentacao char  (3)   , 
      mascara varchar  (45)   , 
      flg_obrigatorio char  (1)    DEFAULT 'N'  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE rel_imagem( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    NOT NULL , 
      data_alteracao timestamp(0)   , 
      nome varchar  (100)   , 
      img varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE templates_email( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      codigo varchar  (45)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
      corpo longblob   , 
      expr_assunto_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_beneficios( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      tipo_beneficio varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_contatos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      tipo_contato varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_documentacoes( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      data_alteracao timestamp(0)    DEFAULT current_timestamp() , 
      tipo_documento varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipos_enderecos( 
      id number(10)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT current_timestamp  NOT NULL , 
      tipo_endereco varchar  (30)    NOT NULL , 
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
 CREATE SEQUENCE acordo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER acordo_id_seq_tr 

BEFORE INSERT ON acordo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT acordo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE acordo_arquivo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER acordo_arquivo_id_seq_tr 

BEFORE INSERT ON acordo_arquivo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT acordo_arquivo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE api_error_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER api_error_id_seq_tr 

BEFORE INSERT ON api_error FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT api_error_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE arquivos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER arquivos_id_seq_tr 

BEFORE INSERT ON arquivos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT arquivos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE arquivos_cooperado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER arquivos_cooperado_id_seq_tr 

BEFORE INSERT ON arquivos_cooperado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT arquivos_cooperado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE arquivos_status_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER arquivos_status_id_seq_tr 

BEFORE INSERT ON arquivos_status FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT arquivos_status_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE bancos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER bancos_id_seq_tr 

BEFORE INSERT ON bancos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT bancos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE beneficios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER beneficios_id_seq_tr 

BEFORE INSERT ON beneficios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT beneficios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE catalogo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER catalogo_id_seq_tr 

BEFORE INSERT ON catalogo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT catalogo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE catalogo_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER catalogo_contato_id_seq_tr 

BEFORE INSERT ON catalogo_contato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT catalogo_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE catalogo_endereco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER catalogo_endereco_id_seq_tr 

BEFORE INSERT ON catalogo_endereco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT catalogo_endereco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE catalogo_especialidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER catalogo_especialidade_id_seq_tr 

BEFORE INSERT ON catalogo_especialidade FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT catalogo_especialidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_responsavel_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_responsavel_id_seq_tr 

BEFORE INSERT ON categoria_responsavel FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT categoria_responsavel_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cep_cache_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cep_cache_id_seq_tr 

BEFORE INSERT ON cep_cache FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cep_cache_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cidades_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidades_id_seq_tr 

BEFORE INSERT ON cidades FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cidades_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE contrato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER contrato_id_seq_tr 

BEFORE INSERT ON contrato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT contrato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE contrato_servico_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER contrato_servico_id_seq_tr 

BEFORE INSERT ON contrato_servico FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT contrato_servico_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE contrato_valor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER contrato_valor_id_seq_tr 

BEFORE INSERT ON contrato_valor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT contrato_valor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_id_seq_tr 

BEFORE INSERT ON cooperados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_beneficiarios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_beneficiarios_id_seq_tr 

BEFORE INSERT ON cooperados_beneficiarios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_beneficiarios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_beneficiarios_dep_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_beneficiarios_dep_id_seq_tr 

BEFORE INSERT ON cooperados_beneficiarios_dep FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_beneficiarios_dep_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_capital_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_capital_id_seq_tr 

BEFORE INSERT ON cooperados_capital FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_capital_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_contatos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_contatos_id_seq_tr 

BEFORE INSERT ON cooperados_contatos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_contatos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_ctb_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_ctb_contato_id_seq_tr 

BEFORE INSERT ON cooperados_ctb_contato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_ctb_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_dados_bancarios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_dados_bancarios_id_seq_tr 

BEFORE INSERT ON cooperados_dados_bancarios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_dados_bancarios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_documentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_documentacoes_id_seq_tr 

BEFORE INSERT ON cooperados_documentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_documentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_especialidades_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_especialidades_id_seq_tr 

BEFORE INSERT ON cooperados_especialidades FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_especialidades_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_movimentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_movimentacoes_id_seq_tr 

BEFORE INSERT ON cooperados_movimentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_movimentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cooperados_secretaria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cooperados_secretaria_id_seq_tr 

BEFORE INSERT ON cooperados_secretaria FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cooperados_secretaria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_id_seq_tr 

BEFORE INSERT ON credenciados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_contatos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_contatos_id_seq_tr 

BEFORE INSERT ON credenciados_contatos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_contatos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_dados_bancarios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_dados_bancarios_id_seq_tr 

BEFORE INSERT ON credenciados_dados_bancarios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_dados_bancarios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_documentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_documentacoes_id_seq_tr 

BEFORE INSERT ON credenciados_documentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_documentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_enderecos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_enderecos_id_seq_tr 

BEFORE INSERT ON credenciados_enderecos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_enderecos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_especialidades_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_especialidades_id_seq_tr 

BEFORE INSERT ON credenciados_especialidades FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_especialidades_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_movimentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_movimentacoes_id_seq_tr 

BEFORE INSERT ON credenciados_movimentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_movimentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_reajuste_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_reajuste_id_seq_tr 

BEFORE INSERT ON credenciados_reajuste FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_reajuste_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credenciados_responsaveis_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credenciados_responsaveis_id_seq_tr 

BEFORE INSERT ON credenciados_responsaveis FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credenciados_responsaveis_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE documentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER documentacoes_id_seq_tr 

BEFORE INSERT ON documentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT documentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE documentacoes_padrao_cooperados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER documentacoes_padrao_cooperados_id_seq_tr 

BEFORE INSERT ON documentacoes_padrao_cooperados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT documentacoes_padrao_cooperados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE documentacoes_padrao_credenciado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER documentacoes_padrao_credenciado_id_seq_tr 

BEFORE INSERT ON documentacoes_padrao_credenciado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT documentacoes_padrao_credenciado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE dominio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER dominio_id_seq_tr 

BEFORE INSERT ON dominio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT dominio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE dominio_relacionamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER dominio_relacionamento_id_seq_tr 

BEFORE INSERT ON dominio_relacionamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT dominio_relacionamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE dominio_valor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER dominio_valor_id_seq_tr 

BEFORE INSERT ON dominio_valor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT dominio_valor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE enderecos_cooperados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER enderecos_cooperados_id_seq_tr 

BEFORE INSERT ON enderecos_cooperados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT enderecos_cooperados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE especialidades_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER especialidades_id_seq_tr 

BEFORE INSERT ON especialidades FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT especialidades_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estados_id_seq_tr 

BEFORE INSERT ON estados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT estados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE expressao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER expressao_id_seq_tr 

BEFORE INSERT ON expressao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT expressao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fila_email_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fila_email_id_seq_tr 

BEFORE INSERT ON fila_email FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT fila_email_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fila_email_anexo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fila_email_anexo_id_seq_tr 

BEFORE INSERT ON fila_email_anexo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT fila_email_anexo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fila_email_log_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fila_email_log_id_seq_tr 

BEFORE INSERT ON fila_email_log FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT fila_email_log_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE job_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER job_id_seq_tr 

BEFORE INSERT ON job FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT job_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE job_exec_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER job_exec_id_seq_tr 

BEFORE INSERT ON job_exec FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT job_exec_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE label_email_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER label_email_id_seq_tr 

BEFORE INSERT ON label_email FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT label_email_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE log_import_capital_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER log_import_capital_id_seq_tr 

BEFORE INSERT ON log_import_capital FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT log_import_capital_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_id_seq_tr 

BEFORE INSERT ON medicos_pf FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_contatos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_contatos_id_seq_tr 

BEFORE INSERT ON medicos_pf_contatos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_contatos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_dados_bancarios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_dados_bancarios_id_seq_tr 

BEFORE INSERT ON medicos_pf_dados_bancarios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_dados_bancarios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_documentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_documentacoes_id_seq_tr 

BEFORE INSERT ON medicos_pf_documentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_documentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_documentacoes_padrao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_documentacoes_padrao_id_seq_tr 

BEFORE INSERT ON medicos_pf_documentacoes_padrao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_documentacoes_padrao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_enderecos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_enderecos_id_seq_tr 

BEFORE INSERT ON medicos_pf_enderecos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_enderecos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_especialidades_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_especialidades_id_seq_tr 

BEFORE INSERT ON medicos_pf_especialidades FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_especialidades_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medicos_pf_movimentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medicos_pf_movimentacoes_id_seq_tr 

BEFORE INSERT ON medicos_pf_movimentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT medicos_pf_movimentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE parametro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER parametro_id_seq_tr 

BEFORE INSERT ON parametro FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT parametro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE relatorio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER relatorio_id_seq_tr 

BEFORE INSERT ON relatorio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT relatorio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE relatorio_banda_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER relatorio_banda_id_seq_tr 

BEFORE INSERT ON relatorio_banda FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT relatorio_banda_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE relatorio_parametro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER relatorio_parametro_id_seq_tr 

BEFORE INSERT ON relatorio_parametro FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT relatorio_parametro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE rel_imagem_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER rel_imagem_id_seq_tr 

BEFORE INSERT ON rel_imagem FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT rel_imagem_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE templates_email_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER templates_email_id_seq_tr 

BEFORE INSERT ON templates_email FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT templates_email_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipos_beneficios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipos_beneficios_id_seq_tr 

BEFORE INSERT ON tipos_beneficios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipos_beneficios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipos_contatos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipos_contatos_id_seq_tr 

BEFORE INSERT ON tipos_contatos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipos_contatos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipos_documentacoes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipos_documentacoes_id_seq_tr 

BEFORE INSERT ON tipos_documentacoes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipos_documentacoes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipos_enderecos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipos_enderecos_id_seq_tr 

BEFORE INSERT ON tipos_enderecos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipos_enderecos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
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
 
