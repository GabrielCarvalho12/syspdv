
CREATE DATABASE IF NOT EXISTS `zig`;
USE `zig`;

CREATE TABLE IF NOT EXISTS `clientes_segmentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `id_segmento` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_empresas_clientes_segmentos` (`id_segmento`),
  CONSTRAINT `FK_empresas_clientes_segmentos` FOREIGN KEY (`id_segmento`) REFERENCES `clientes_segmentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `categoria_fluxo_caixa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `clientes_tipos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_cliente_tipo` int NOT NULL,
  `id_cliente_segmento` int DEFAULT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cnpj` varchar(50) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_clientes_tipos_clientes` (`id_cliente_tipo`),
  KEY `FK_clientes_clientes_segmentos` (`id_cliente_segmento`),
  KEY `FK_clientes_empresas` (`id_empresa`),
  CONSTRAINT `FK_clientes_clientes_segmentos` FOREIGN KEY (`id_cliente_segmento`) REFERENCES `clientes_segmentos` (`id`),
  CONSTRAINT `FK_clientes_empresas` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_clientes_tipos_clientes` FOREIGN KEY (`id_cliente_tipo`) REFERENCES `clientes_tipos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `clientes_enderecos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL DEFAULT '0',
  `id_cliente` int NOT NULL DEFAULT '0',
  `cep` varchar(50) DEFAULT NULL,
  `endereco` varchar(50) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `complemento` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_clientes_enderecos_empresas` (`id_empresa`),
  KEY `FK_clientes_enderecos_clientes` (`id_cliente`),
  CONSTRAINT `FK_clientes_enderecos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  CONSTRAINT `FK_clientes_enderecos_empresas` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tipos_pdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `config_pdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_tipo_pdv` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_config_pdv_clientes` (`id_empresa`),
  KEY `FK_config_pdv_tipo_pdv` (`id_tipo_pdv`),
  CONSTRAINT `FK_config_pdv_clientes` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_config_pdv_tipo_pdv` FOREIGN KEY (`id_tipo_pdv`) REFERENCES `tipos_pdv` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `fluxo_caixa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL DEFAULT '0',
  `id_categoria` int DEFAULT NULL,
  `descricao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `data` timestamp NULL DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `tipo_movimento` int DEFAULT NULL COMMENT '0 = Saída, 1 = Entrada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_fluxo_caixa_empresas` (`id_empresa`),
  KEY `FK_fluxo_caixa_categoria_fluxo_caixa` (`id_categoria`),
  CONSTRAINT `FK_fluxo_caixa_categoria_fluxo_caixa` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_fluxo_caixa` (`id`),
  CONSTRAINT `FK_fluxo_caixa_empresas` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `perfis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `sexos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL DEFAULT '0',
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `remember_token` varchar(60) DEFAULT NULL,
  `remember_expire_date` timestamp NULL DEFAULT NULL,
  `id_sexo` int DEFAULT NULL,
  `id_perfil` int DEFAULT NULL,
  `imagem` text,
  `status` int DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_usuarios_sexo` (`id_sexo`),
  KEY `FK_usuarios_perfis` (`id_perfil`),
  KEY `FK_usuarios_clientes` (`id_empresa`),
  CONSTRAINT `FK_usuarios_clientes` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_usuarios_perfis` FOREIGN KEY (`id_perfil`) REFERENCES `perfis` (`id`),
  CONSTRAINT `FK_usuarios_sexo` FOREIGN KEY (`id_sexo`) REFERENCES `sexos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `log_acessos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL DEFAULT '0',
  `id_empresa` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_log_usuarios` (`id_usuario`),
  KEY `FK_log_clientes` (`id_empresa`),
  CONSTRAINT `FK_log_clientes` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_log_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1070 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `meios_pagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `legenda` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `preco` double NOT NULL DEFAULT '0',
  `descricao` text,
  `imagem` text,
  `ativar_quantidade` int NOT NULL DEFAULT '0' COMMENT 'SIM = 1, NÃO = 0',
  `quantidade` int DEFAULT '0',
  `mostrar_em_vendas` int DEFAULT '1' COMMENT 'SIM =1, NÃO = 0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_produtos_clientes` (`id_empresa`),
  CONSTRAINT `FK_produtos_clientes` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `recuperacao_de_senha` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `hash` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- Criando a tabela 'caixa' com índice único para o status 'aberto'
CREATE TABLE IF NOT EXISTS caixa (
    id BIGINT NOT NULL PRIMARY KEY, -- ID gerado com base na data/hora
    data_abertura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora de abertura
    data_fechamento DATETIME DEFAULT NULL, -- Data e hora de fechamento (inicialmente NULL)
    status ENUM('aberto', 'fechado') DEFAULT 'fechado', -- Status do caixa
    usuario_abertura INT, -- ID do usuário que abriu o caixa
    usuario_fechamento INT -- ID do usuário que fechou o caixa
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Trigger para garantir que o ID seja gerado com base na data/hora (YYYYMMDDHHMMSS)
DELIMITER $$
CREATE TRIGGER trg_before_insert_caixa
BEFORE INSERT ON caixa
FOR EACH ROW
BEGIN
    -- Gerar o ID baseado na data e hora atuais no formato YYYYMMDDHHMMSS
    SET NEW.id = DATE_FORMAT(NOW(), '%Y%m%d%H%i%s');
END $$
DELIMITER ;

-- Criando a tabela 'vendas'
CREATE TABLE IF NOT EXISTS `vendas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_meio_pagamento` int NOT NULL,
  `id_empresa` int NOT NULL,
  `id_produto` int DEFAULT NULL,
  `preco` double DEFAULT '0',
  `quantidade` int DEFAULT NULL,
  `valor` double NOT NULL DEFAULT '0',
  `valor_recebido` double DEFAULT NULL COMMENT 'Este valor é preenchido somente quando a opção de pagamento for dinheiro',
  `troco` double DEFAULT NULL COMMENT 'Este campo é preenchido quando houver troco durante a venda',
  `data_compensacao` date DEFAULT NULL,
  `codigo_venda` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `caixa_id` BIGINT,
  PRIMARY KEY (`id`),
  KEY `FK_vendas_meios_de_pagamento` (`id_meio_pagamento`),
  KEY `FK_vendas_usuarios` (`id_usuario`),
  KEY `FK_vendas_clientes` (`id_empresa`),
  CONSTRAINT `FK_vendas_clientes` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_vendas_meios_de_pagamento` FOREIGN KEY (`id_meio_pagamento`) REFERENCES `meios_pagamentos` (`id`),
  CONSTRAINT `FK_vendas_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_caixa` FOREIGN KEY (`caixa_id`) REFERENCES `caixa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=277 DEFAULT CHARSET=latin1;

INSERT INTO `clientes_segmentos` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Restaurante', NULL, NULL),
	(2, 'Hamburgueria', '2020-05-28 10:28:08', '2020-05-28 10:28:09'),
	(3, 'Pizzaria', '2020-05-28 10:28:52', '2020-05-28 10:28:53'),
	(4, 'Outros...', '2020-06-02 00:00:26', '2020-06-02 00:00:27'),
	(5, 'Arte e Antiguidades', '2021-01-11 16:10:15', NULL),
	(6, 'Artigos Religiosos', '2021-01-11 16:10:15', NULL),
	(7, 'Assinaturas e Revistas', '2021-01-11 16:10:15', NULL),
	(8, 'Automóveis e Veículos', '2021-01-11 16:10:15', NULL),
	(9, 'Bebês e Cia', '2021-01-11 16:10:15', NULL),
	(10, 'Blu-Ray', '2021-01-11 16:10:15', NULL),
	(11, 'Brindes / Materiais Promocionais', '2021-01-11 16:10:15', NULL),
	(12, 'Brinquedos e Games', '2021-01-11 16:10:15', NULL),
	(13, 'Casa e Decoração', '2021-01-11 16:10:15', NULL),
	(14, 'CDs', '2021-01-11 16:10:15', NULL),
	(15, 'Colecionáveis', '2021-01-11 16:10:15', NULL),
	(16, 'Compras Coletivas', '2021-01-11 16:10:15', NULL),
	(17, 'Construção e Ferramentas', '2021-01-11 16:10:15', NULL),
	(18, 'Cosméticos e Perfumaria', '2021-01-11 16:10:15', NULL),
	(19, 'Cursos e Educação', '2021-01-11 16:10:15', NULL),
	(20, 'Discos de Vinil', '2021-01-11 16:10:15', NULL),
	(21, 'DVDs', '2021-01-11 16:10:15', NULL),
	(22, 'Eletrodomésticos', '2021-01-11 16:10:15', NULL),
	(23, 'Eletrônicos', '2021-01-11 16:10:15', NULL),
	(24, 'Emissoras de Rádio', '2021-01-11 16:10:15', NULL),
	(25, 'Emissoras de Televisão', '2021-01-11 16:10:15', NULL),
	(26, 'Empregos', '2021-01-11 16:10:15', NULL),
	(27, 'Empresas de Telemarketing', '2021-01-11 16:10:15', NULL),
	(28, 'Esporte e Lazer', '2021-01-11 16:10:15', NULL),
	(29, 'Fitas K7 Gravadas', '2021-01-11 16:10:15', NULL),
	(30, 'Flores, Cestas e Presentes', '2021-01-11 16:10:15', NULL),
	(31, 'Fotografia', '2021-01-11 16:10:15', NULL),
	(32, 'HD-DVD', '2021-01-11 16:10:15', NULL),
	(33, 'Igrejas / Templos / Instituições Religiosas', '2021-01-11 16:10:15', NULL),
	(34, 'Indústria, Comércio e Negócios', '2021-01-11 16:10:15', NULL),
	(35, 'Infláveis Promocionais', '2021-01-11 16:10:15', NULL),
	(36, 'Informática', '2021-01-11 16:10:15', NULL),
	(37, 'Ingressos', '2021-01-11 16:10:15', NULL),
	(38, 'Instrumentos Musicais', '2021-01-11 16:10:15', NULL),
	(39, 'Joalheria', '2021-01-11 16:10:15', NULL),
	(40, 'Lazer', '2021-01-11 16:10:15', NULL),
	(41, 'LD', '2021-01-11 16:10:15', NULL),
	(42, 'Livros', '2021-01-11 16:10:15', NULL),
	(43, 'MD', '2021-01-11 16:10:15', NULL),
	(44, 'Moda e Acessórios', '2021-01-11 16:10:15', NULL),
	(45, 'Motéis', '2021-01-11 16:10:15', NULL),
	(46, 'Música Digital', '2021-01-11 16:10:15', NULL),
	(47, 'Natal', '2021-01-11 16:10:15', NULL),
	(48, 'Negócios e Oportunidades', '2021-01-11 16:10:15', NULL),
	(49, 'Outros Serviços', '2021-01-11 16:10:15', NULL),
	(50, 'Outros Serviços de Avaliação', '2021-01-11 16:10:15', NULL),
	(51, 'Papelaria e Escritório', '2021-01-11 16:10:15', NULL),
	(52, 'Páscoa', '2021-01-11 16:10:15', NULL),
	(53, 'Pet Shop', '2021-01-11 16:10:15', NULL),
	(54, 'Saúde', '2021-01-11 16:10:15', NULL),
	(55, 'Serviço Advocaticios', '2021-01-11 16:10:15', NULL),
	(56, 'Serviço de Distribuição de Jornais / Revistas', '2021-01-11 16:10:15', NULL),
	(57, 'Serviços Administrativos', '2021-01-11 16:10:15', NULL),
	(58, 'Serviços Artísticos', '2021-01-11 16:10:15', NULL),
	(59, 'Serviços de Abatedouros / Matadouros', '2021-01-11 16:10:15', NULL),
	(60, 'Serviços de Aeroportos', '2021-01-11 16:10:15', NULL),
	(61, 'Serviços de Agências', '2021-01-11 16:10:15', NULL),
	(62, 'Serviços de Aluguel / Locação', '2021-01-11 16:10:15', NULL),
	(63, 'Serviços de Armazenagem', '2021-01-11 16:10:15', NULL),
	(64, 'Serviços de Assessorias', '2021-01-11 16:10:15', NULL),
	(65, 'Serviços de Assistência Técnica / Instalações ', '2021-01-11 16:10:15', NULL),
	(66, 'Serviços de Associações', '2021-01-11 16:10:15', NULL),
	(67, 'Serviços de Bancos de Sangue', '2021-01-11 16:10:15', NULL),
	(68, 'Serviços de Bibliotecas', '2021-01-11 16:10:15', NULL),
	(69, 'Serviços de Cartórios', '2021-01-11 16:10:15', NULL),
	(70, 'Serviços de Casas Lotéricas', '2021-01-11 16:10:15', NULL),
	(71, 'Serviços de Confecções', '2021-01-11 16:10:15', NULL),
	(72, 'Serviços de Consórcios', '2021-01-11 16:10:15', NULL),
	(73, 'Serviços de Consultorias', '2021-01-11 16:10:15', NULL),
	(74, 'Serviços de Cooperativas', '2021-01-11 16:10:15', NULL),
	(75, 'Serviços de Despachante', '2021-01-11 16:10:15', NULL),
	(76, 'Serviços de Engenharia', '2021-01-11 16:10:15', NULL),
	(77, 'Serviços de Estacionamentos', '2021-01-11 16:10:15', NULL),
	(78, 'Serviços de Estaleiros', '2021-01-11 16:10:15', NULL),
	(79, 'Serviços de Exportação / Importação', '2021-01-11 16:10:15', NULL),
	(80, 'Serviços de Geólogos', '2021-01-11 16:10:15', NULL),
	(81, 'Serviços de joalheiros', '2021-01-11 16:10:15', NULL),
	(82, 'Serviços de Leiloeiros', '2021-01-11 16:10:15', NULL),
	(83, 'Serviços de limpeza', '2021-01-11 16:10:15', NULL),
	(84, 'Serviços de Loja de Conveniência', '2021-01-11 16:10:15', NULL),
	(85, 'Serviços de Mão de Obra', '2021-01-11 16:10:15', NULL),
	(86, 'Serviços de Órgão Públicos', '2021-01-11 16:10:15', NULL),
	(87, 'Serviços de Pesquisas', '2021-01-11 16:10:15', NULL),
	(88, 'Serviços de Portos', '2021-01-11 16:10:15', NULL),
	(89, 'Serviços de Saúde / Bem Estar', '2021-01-11 16:10:15', NULL),
	(90, 'Serviços de Seguradoras', '2021-01-11 16:10:15', NULL),
	(91, 'Serviços de Segurança', '2021-01-11 16:10:15', NULL),
	(92, 'Serviços de Sinalização', '2021-01-11 16:10:15', NULL),
	(93, 'Serviços de Sindicatos / Federações', '2021-01-11 16:10:15', NULL),
	(94, 'Serviços de Traduções', '2021-01-11 16:10:15', NULL),
	(95, 'Serviços de Transporte', '2021-01-11 16:10:15', NULL),
	(96, 'Serviços de Utilidade Pública', '2021-01-11 16:10:15', NULL),
	(97, 'Serviços em Agricultura / Pecuária / Piscicultura', '2021-01-11 16:10:15', NULL),
	(98, 'Serviços em Alimentação', '2021-01-11 16:10:15', NULL),
	(99, 'Serviços em Arte', '2021-01-11 16:10:15', NULL),
	(100, 'Serviços em Cine / Foto / Som', '2021-01-11 16:10:15', NULL),
	(101, 'Serviços em Comunicação', '2021-01-11 16:10:15', NULL),
	(102, 'Serviços em Construção', '2021-01-11 16:10:15', NULL),
	(103, 'Serviços em Ecologia / Meio Ambiente', '2021-01-11 16:10:15', NULL),
	(104, 'Serviços em Eletroeletrônica / Metal Mecânica', '2021-01-11 16:10:15', NULL),
	(105, 'Serviços em Festas / Eventos', '2021-01-11 16:10:15', NULL),
	(106, 'Serviços em Informática', '2021-01-11 16:10:15', NULL),
	(107, 'Serviços em Internet', '2021-01-11 16:10:15', NULL),
	(108, 'Serviços em Jóias / Relógios / Óticas', '2021-01-11 16:10:15', NULL),
	(109, 'Serviços em Telefonia', '2021-01-11 16:10:15', NULL),
	(110, 'Serviços em Veículos', '2021-01-11 16:10:15', NULL),
	(111, 'Serviços Esotéricos / Místicos', '2021-01-11 16:10:15', NULL),
	(112, 'Serviços Financeiros', '2021-01-11 16:10:15', NULL),
	(113, 'Serviços Funerários', '2021-01-11 16:10:15', NULL),
	(114, 'Serviços Gerais', '2021-01-11 16:10:15', NULL),
	(115, 'Serviços Gráficos / Editoriais', '2021-01-11 16:10:15', NULL),
	(116, 'Serviços para Animais', '2021-01-11 16:10:15', NULL),
	(117, 'Serviços para Deficientes', '2021-01-11 16:10:15', NULL),
	(118, 'Serviços para Escritórios', '2021-01-11 16:10:15', NULL),
	(119, 'Serviços para Roupas', '2021-01-11 16:10:15', NULL),
	(120, 'Serviços Socias / Assistenciais', '2021-01-11 16:10:15', NULL),
	(121, 'Sex Shop', '2021-01-11 16:10:15', NULL),
	(122, 'Shopping Centers', '2021-01-11 16:10:15', NULL),
	(123, 'Tabacaria', '2021-01-11 16:10:15', NULL),
	(124, 'Tarifas Bancárias', '2021-01-11 16:10:15', NULL),
	(125, 'Tarifas Telefônicas', '2021-01-11 16:10:15', NULL),
	(126, 'Telefonia', '2021-01-11 16:10:15', NULL),
	(127, 'Turismo', '2021-01-11 16:10:15', NULL);

  INSERT INTO `empresas` (`id`, `nome`, `email`, `telefone`, `celular`, `id_segmento`, `created_at`, `updated_at`) VALUES
  (1, 'Empresa Admin', 'empresaadmin@gmail.com', '(47) 9988-8581', '(47) 99988-8581', 1, '2022-10-05 23:26:21', '2021-12-02 00:12:46');

  INSERT INTO `meios_pagamentos` (`id`, `legenda`, `created_at`, `updated_at`) VALUES
	(1, 'Dinheiro', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 'Crédito', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, 'Débito', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(4, 'Pix', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

  INSERT INTO `perfis` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', '2020-06-21 13:00:15', '0000-00-00 00:00:00'),
	(2, 'Administrador', '2020-04-25 00:53:27', '0000-00-00 00:00:00'),
	(4, 'Vendedor', '2020-04-25 00:53:32', '0000-00-00 00:00:00'),
	(5, 'Gerente', '2020-04-25 00:53:32', '0000-00-00 00:00:00');

INSERT INTO `sexos` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Masculino', '2020-02-21 14:08:58', '0000-00-00 00:00:00'),
	(2, 'Feminino', '2020-02-21 14:09:09', '0000-00-00 00:00:00');

INSERT INTO `tipos_pdv` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Padrão', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 'Diferencial', '2020-05-23 17:02:09', '2020-05-23 17:02:09');

INSERT INTO `config_pdv` (`id`, `id_empresa`, `id_tipo_pdv`, `created_at`, `updated_at`) VALUES
	(3, 1, 2, '2020-06-28 10:17:42', '2020-06-28 10:17:42');

INSERT INTO `usuarios` (`id`, `id_empresa`, `nome`, `email`, `password`, `id_sexo`, `id_perfil`, `imagem`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Admin', 'admin@admin.com', '3b5df72898847f008454f4ed60280d6bdffc890d', 1, 2, 'public/imagem/perfil_usuarios/user.png', '2020-05-27 18:29:11', '2020-05-27 15:29:09');