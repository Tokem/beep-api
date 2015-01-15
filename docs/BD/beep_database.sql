CREATE TABLE IF NOT EXISTS `beep_atracoes` (
  `atr_id` int(11) NOT NULL AUTO_INCREMENT,
  `atr_nome` varchar(45) NOT NULL,
  `eve_id_fk` int(11) NOT NULL,
  `est_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`atr_id`),
  KEY `fk_beep_atracoes_beep_evento1_idx` (`eve_id_fk`),
  KEY `fk_beep_atracoes_beep_estilo1_idx` (`est_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_beeps`
--

CREATE TABLE IF NOT EXISTS `beep_beeps` (
  `bee_id` int(11) NOT NULL AUTO_INCREMENT,
  `bee_beeps` int(11) DEFAULT '0',
  `usr_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`bee_id`),
  KEY `fk_beep_beeps_beep_user1_idx` (`usr_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_categoria`
--

CREATE TABLE IF NOT EXISTS `beep_categoria` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nome` varchar(45) DEFAULT NULL,
  `cat_ativo` int(11) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_classificacao`
--

CREATE TABLE IF NOT EXISTS `beep_classificacao` (
  `cla_id` int(11) NOT NULL AUTO_INCREMENT,
  `cla_nome` varchar(45) DEFAULT NULL,
  `cla_beeps` int(11) DEFAULT NULL,
  PRIMARY KEY (`cla_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_configuracoes`
--

CREATE TABLE IF NOT EXISTS `beep_configuracoes` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_email` int(11) DEFAULT NULL,
  `con_sms` int(11) DEFAULT NULL,
  `con_sons` int(11) DEFAULT NULL,
  `con_notificacao` int(11) DEFAULT NULL,
  `usr_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`con_id`),
  KEY `fk_beep_configuracoes_beep_user1_idx` (`usr_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_estilo`
--

CREATE TABLE IF NOT EXISTS `beep_estilo` (
  `est_id` int(11) NOT NULL AUTO_INCREMENT,
  `est_nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`est_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_evento`
--

CREATE TABLE IF NOT EXISTS `beep_evento` (
  `eve_id` int(11) NOT NULL AUTO_INCREMENT,
  `eve_nome` varchar(45) NOT NULL,
  `eve_tag` varchar(45) DEFAULT NULL,
  `eve_data` date NOT NULL,
  `eve_hora_inicio` time NOT NULL,
  `eve_hora_termino` time DEFAULT NULL,
  `eve_local` varchar(45) DEFAULT NULL,
  `eve_localizacao` varchar(45) DEFAULT NULL,
  `eve_image` text,
  `eve_qrcode` text,
  `cat_id_fk` int(11) NOT NULL,
  `eve_especial` int(11) DEFAULT NULL,
  `eve_data_especial_inicio` datetime DEFAULT NULL,
  `eve_data_especial_fim` datetime DEFAULT NULL,
  `eve_ativo` tinyint(1) DEFAULT '0',
  `eve_check` int(11) DEFAULT '0',
  `usr_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`eve_id`),
  KEY `fk_beep_evento_beep_categoria_idx` (`cat_id_fk`),
  KEY `fk_beep_evento_beep_usuario1_idx` (`usr_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_evento_check`
--

CREATE TABLE IF NOT EXISTS `beep_evento_check` (
  `evc_id` int(11) NOT NULL AUTO_INCREMENT,
  `eve_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `chek_data_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`evc_id`),
  KEY `fk_beep_evento_has_beep_user_beep_user1_idx` (`usr_id`),
  KEY `fk_beep_evento_has_beep_user_beep_evento1_idx` (`eve_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Acionadores `beep_evento_check`
--
DROP TRIGGER IF EXISTS `check_delete`;
DELIMITER //
CREATE TRIGGER `check_delete` AFTER DELETE ON `beep_evento_check`
 FOR EACH ROW BEGIN
	DECLARE updatecount integer;
  SET updatecount = ( SELECT count(*) FROM beep_evento_check  WHERE eve_id = old.eve_id);
  IF updatecount>0
    THEN
      UPDATE beep_evento SET eve_check = updatecount WHERE eve_id = old.eve_id;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `check_update`;
DELIMITER //
CREATE TRIGGER `check_update` AFTER INSERT ON `beep_evento_check`
 FOR EACH ROW BEGIN
	DECLARE updatecount integer;
  SET updatecount = ( SELECT count(*) FROM beep_evento_check  WHERE eve_id = new.eve_id);
  IF updatecount>0
    THEN
      UPDATE beep_evento SET eve_check = updatecount WHERE eve_id = new.eve_id;
  END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_favoritos`
--

CREATE TABLE IF NOT EXISTS `beep_favoritos` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `eve_id` int(11) NOT NULL,
  PRIMARY KEY (`usr_id`,`eve_id`),
  KEY `fk_beep_user_has_beep_evento_beep_evento1_idx` (`eve_id`),
  KEY `fk_beep_user_has_beep_evento_beep_user1_idx` (`usr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_feed`
--

CREATE TABLE IF NOT EXISTS `beep_feed` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_texto` text,
  `fee_data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `eve_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`fee_id`),
  KEY `fk_beep_feed_beep_evento1_idx` (`eve_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_hitorico`
--

CREATE TABLE IF NOT EXISTS `beep_hitorico` (
  `his_id` int(11) NOT NULL,
  PRIMARY KEY (`his_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_ingresso`
--

CREATE TABLE IF NOT EXISTS `beep_ingresso` (
  `ing_id` int(11) NOT NULL AUTO_INCREMENT,
  `ing_valor` varchar(45) NOT NULL,
  `ing_descricao` varchar(45) NOT NULL,
  `eve_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`ing_id`),
  KEY `fk_beep_ingresso_beep_evento1_idx` (`eve_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_listas`
--

CREATE TABLE IF NOT EXISTS `beep_listas` (
  `lis_id` int(11) NOT NULL AUTO_INCREMENT,
  `lis_nome` varchar(45) DEFAULT NULL,
  `lis_data_criado` datetime DEFAULT NULL,
  `usr_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`lis_id`),
  KEY `fk_beep_listas_beep_user1_idx` (`usr_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_lista_visualizacao`
--

CREATE TABLE IF NOT EXISTS `beep_lista_visualizacao` (
  `eve_id` int(11) NOT NULL,
  `lis_id` int(11) NOT NULL,
  PRIMARY KEY (`eve_id`,`lis_id`),
  KEY `fk_beep_evento_has_beep_listas_beep_listas1_idx` (`lis_id`),
  KEY `fk_beep_evento_has_beep_listas_beep_evento1_idx` (`eve_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_tipo_ingresso`
--

CREATE TABLE IF NOT EXISTS `beep_tipo_ingresso` (
  `tip_id` int(11) NOT NULL AUTO_INCREMENT,
  `tip_nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`tip_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_usuario`
--

CREATE TABLE IF NOT EXISTS `beep_usuario` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_permissao` varchar(45) DEFAULT 'usuario',
  `usr_primeiro_nome` varchar(45) DEFAULT NULL,
  `usr_ultimo_nome` varchar(45) DEFAULT NULL,
  `usr_email` varchar(45) DEFAULT NULL,
  `usr_senha` varchar(45) DEFAULT 'MD5',
  `usr_data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_telefone` varchar(45) DEFAULT NULL,
  `usr_genero` varchar(45) DEFAULT NULL,
  `usr_locale` varchar(45) DEFAULT NULL,
  `usr_id_facebook` varchar(45) DEFAULT NULL,
  `usr_id_gmail` varchar(45) DEFAULT NULL,
  `usr_usuario` varchar(45) DEFAULT NULL,
  `usr_ativo` tinyint(1) DEFAULT '1',
  `usr_foto_perfil` varchar(45) DEFAULT NULL,
  `usr_tokem` varchar(45) DEFAULT NULL,
  `cla_id_fk` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`usr_id`),
  KEY `fk_beep_usuario_beep_classificacao1_idx` (`cla_id_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `beep_visualizacao`
--

CREATE TABLE IF NOT EXISTS `beep_visualizacao` (
  `vis_id` int(11) NOT NULL AUTO_INCREMENT,
  `vis_data_hora` varchar(45) DEFAULT NULL,
  `eve_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`vis_id`),
  KEY `fk_beep_visualizacao_beep_evento1_idx` (`eve_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
