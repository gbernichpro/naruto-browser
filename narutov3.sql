/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : naruto

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2013-09-01 22:05:54
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `acoes_do_invasor`
-- ----------------------------
DROP TABLE IF EXISTS `acoes_do_invasor`;
CREATE TABLE `acoes_do_invasor` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(100) NOT NULL,
  `enderecos` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acoes_do_invasor
-- ----------------------------

-- ----------------------------
-- Table structure for `amigos`
-- ----------------------------
DROP TABLE IF EXISTS `amigos`;
CREATE TABLE `amigos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `amigoid` int(11) NOT NULL,
  `status` enum('sim','nao') NOT NULL DEFAULT 'nao',
  PRIMARY KEY (`id`),
  KEY `idx_1` (`amigoid`,`usuarioid`),
  KEY `idx_2` (`amigoid`,`id`,`status`,`usuarioid`),
  KEY `idx_3` (`amigoid`,`id`,`usuarioid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amigos
-- ----------------------------

-- ----------------------------
-- Table structure for `animais`
-- ----------------------------
DROP TABLE IF EXISTS `animais`;
CREATE TABLE `animais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `valorap` int(11) NOT NULL DEFAULT '500',
  `categoria` enum('animais') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  `taijutsu` int(11) NOT NULL DEFAULT '0',
  `ninjutsu` int(11) NOT NULL DEFAULT '0',
  `genjutsu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of animais
-- ----------------------------

-- ----------------------------
-- Table structure for `atualizacoes`
-- ----------------------------
DROP TABLE IF EXISTS `atualizacoes`;
CREATE TABLE `atualizacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `texto` tinytext NOT NULL,
  `hora` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of atualizacoes
-- ----------------------------
INSERT INTO `atualizacoes` VALUES ('1', '6', '<a href=?p=view&view=darkangel>Darkangel</a> aprendeu <b>Kage Bunshin no Jutsu</b>.', '1378048256');

-- ----------------------------
-- Table structure for `block`
-- ----------------------------
DROP TABLE IF EXISTS `block`;
CREATE TABLE `block` (
  `id` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `tentativa` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of block
-- ----------------------------

-- ----------------------------
-- Table structure for `bolsas`
-- ----------------------------
DROP TABLE IF EXISTS `bolsas`;
CREATE TABLE `bolsas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `categoria` enum('bolsas') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bolsas
-- ----------------------------

-- ----------------------------
-- Table structure for `book`
-- ----------------------------
DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `inimigoid` int(11) NOT NULL,
  `ultimo` datetime NOT NULL,
  `yens` int(11) NOT NULL,
  `hoje` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_1` (`inimigoid`,`usuarioid`),
  KEY `idx_2` (`id`,`inimigoid`,`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of book
-- ----------------------------

-- ----------------------------
-- Table structure for `chat`
-- ----------------------------
DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message` text COLLATE utf8_bin NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of chat
-- ----------------------------

-- ----------------------------
-- Table structure for `clan_guerras_participantes`
-- ----------------------------
DROP TABLE IF EXISTS `clan_guerras_participantes`;
CREATE TABLE `clan_guerras_participantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `pontos` int(11) NOT NULL,
  `vitorias` int(11) NOT NULL,
  `derrotas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of clan_guerras_participantes
-- ----------------------------

-- ----------------------------
-- Table structure for `clas_guerras`
-- ----------------------------
DROP TABLE IF EXISTS `clas_guerras`;
CREATE TABLE `clas_guerras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clan_1` int(11) NOT NULL,
  `clan_2` int(11) NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `status` enum('finalizada','iniciada','espera') NOT NULL DEFAULT 'espera',
  `clan_vencedor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of clas_guerras
-- ----------------------------

-- ----------------------------
-- Table structure for `clas_guerras_convites`
-- ----------------------------
DROP TABLE IF EXISTS `clas_guerras_convites`;
CREATE TABLE `clas_guerras_convites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clan_1` int(11) NOT NULL,
  `clan_2` int(11) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of clas_guerras_convites
-- ----------------------------

-- ----------------------------
-- Table structure for `clas_investimentos`
-- ----------------------------
DROP TABLE IF EXISTS `clas_investimentos`;
CREATE TABLE `clas_investimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `invid` int(11) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT '1',
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`orgid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of clas_investimentos
-- ----------------------------

-- ----------------------------
-- Table structure for `configuracoes`
-- ----------------------------
DROP TABLE IF EXISTS `configuracoes`;
CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `config_atk1` int(11) NOT NULL DEFAULT '0',
  `config_atk2` int(11) NOT NULL DEFAULT '0',
  `config_atk3` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of configuracoes
-- ----------------------------

-- ----------------------------
-- Table structure for `contato`
-- ----------------------------
DROP TABLE IF EXISTS `contato`;
CREATE TABLE `contato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `mensagem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contato
-- ----------------------------

-- ----------------------------
-- Table structure for `gm_guerracla`
-- ----------------------------
DROP TABLE IF EXISTS `gm_guerracla`;
CREATE TABLE `gm_guerracla` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `cla1` int(100) NOT NULL,
  `cla2` int(100) NOT NULL,
  `convite` char(1) NOT NULL DEFAULT '0',
  `horario` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gm_guerracla
-- ----------------------------

-- ----------------------------
-- Table structure for `gm_guerracla_ninjas`
-- ----------------------------
DROP TABLE IF EXISTS `gm_guerracla_ninjas`;
CREATE TABLE `gm_guerracla_ninjas` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `guerra` int(100) NOT NULL,
  `cla` int(100) NOT NULL,
  `idninja` int(11) NOT NULL,
  `pontos` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gm_guerracla_ninjas
-- ----------------------------

-- ----------------------------
-- Table structure for `gm_guerracla_relatorios`
-- ----------------------------
DROP TABLE IF EXISTS `gm_guerracla_relatorios`;
CREATE TABLE `gm_guerracla_relatorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `data` datetime NOT NULL,
  `usuarioid` int(11) NOT NULL,
  `inimigoid` int(11) NOT NULL,
  `vencedor` int(11) NOT NULL,
  `nivel` varchar(255) NOT NULL,
  `exp` varchar(255) NOT NULL,
  `yens` int(11) NOT NULL,
  `taijutsu` varchar(255) NOT NULL,
  `ninjutsu` varchar(255) NOT NULL,
  `genjutsu` varchar(255) NOT NULL,
  `energia` varchar(255) NOT NULL,
  `chakra` varchar(255) NOT NULL,
  `equips1` varchar(255) NOT NULL,
  `equips2` varchar(255) NOT NULL,
  `equips3` varchar(255) NOT NULL,
  `equips4` varchar(255) NOT NULL,
  `equips5` varchar(255) NOT NULL,
  `equips6` varchar(255) NOT NULL,
  `equips7` varchar(255) NOT NULL,
  `equips8` varchar(255) NOT NULL,
  `doujutsu` varchar(10) NOT NULL,
  `danos` varchar(255) NOT NULL,
  `ip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  PRIMARY KEY (`id`),
  KEY `idx_1` (`inimigoid`,`status`),
  KEY `idx_2` (`id`,`inimigoid`,`usuarioid`),
  KEY `idx_3` (`inimigoid`,`usuarioid`),
  KEY `idx_4` (`usuarioid`),
  KEY `idx_5` (`inimigoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gm_guerracla_relatorios
-- ----------------------------

-- ----------------------------
-- Table structure for `inv_invasao`
-- ----------------------------
DROP TABLE IF EXISTS `inv_invasao`;
CREATE TABLE `inv_invasao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `valor` int(11) NOT NULL DEFAULT '0',
  `categoria` enum('xp','dano','yens') NOT NULL,
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inv_invasao
-- ----------------------------

-- ----------------------------
-- Table structure for `invasor`
-- ----------------------------
DROP TABLE IF EXISTS `invasor`;
CREATE TABLE `invasor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_bin NOT NULL,
  `hp` int(11) NOT NULL,
  `hpmaximo` int(11) NOT NULL,
  `premio` int(11) NOT NULL,
  `vitorias` int(11) NOT NULL,
  `status` enum('t','c') COLLATE utf8_bin NOT NULL,
  `derrotadopor` varchar(255) COLLATE utf8_bin NOT NULL,
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `nivelmin` int(11) NOT NULL DEFAULT '0',
  `nivelmax` int(11) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `expmax` int(11) NOT NULL DEFAULT '0',
  `abertopor` varchar(255) COLLATE utf8_bin NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of invasor
-- ----------------------------
INSERT INTO `invasor` VALUES ('1', '', '0', '0', '0', '0', 't', '', '0', '0', '1', '0', '0', '', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `invcla`
-- ----------------------------
DROP TABLE IF EXISTS `invcla`;
CREATE TABLE `invcla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `categoria` enum('hp','yens','cred') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of invcla
-- ----------------------------

-- ----------------------------
-- Table structure for `inventario`
-- ----------------------------
DROP TABLE IF EXISTS `inventario`;
CREATE TABLE `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `categoria` enum('arma','vestimenta','calcado','bijuu','acessorios') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inventario
-- ----------------------------

-- ----------------------------
-- Table structure for `jutsus`
-- ----------------------------
DROP TABLE IF EXISTS `jutsus`;
CREATE TABLE `jutsus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `usuarioid` int(11) NOT NULL,
  `jutsu` int(11) NOT NULL,
  `nivel` int(1) NOT NULL,
  `exp` int(11) NOT NULL,
  `expmax` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `Otimizacao1` (`status`),
  KEY `Otimizacao2` (`jutsu`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jutsus
-- ----------------------------

-- ----------------------------
-- Table structure for `listamedalhas`
-- ----------------------------
DROP TABLE IF EXISTS `listamedalhas`;
CREATE TABLE `listamedalhas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medalha` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tipo1` enum('evento','score') NOT NULL,
  `tipo2` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of listamedalhas
-- ----------------------------
INSERT INTO `listamedalhas` VALUES ('1', 'Staff', 'Por ser um membro da Staff', 'evento', '3');
INSERT INTO `listamedalhas` VALUES ('2', 'Arena', 'Por ser campe?o da arena', 'evento', '3');
INSERT INTO `listamedalhas` VALUES ('3', 'Guerra de vila', 'Ficou em 1? Lugar durante a guerra', 'evento', '3');
INSERT INTO `listamedalhas` VALUES ('4', 'Guerra de vila', 'Ficou em 2? lugar na guerra', 'evento', '2');

-- ----------------------------
-- Table structure for `log_bugs`
-- ----------------------------
DROP TABLE IF EXISTS `log_bugs`;
CREATE TABLE `log_bugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_bugs
-- ----------------------------

-- ----------------------------
-- Table structure for `log_creditos`
-- ----------------------------
DROP TABLE IF EXISTS `log_creditos`;
CREATE TABLE `log_creditos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_creditos
-- ----------------------------

-- ----------------------------
-- Table structure for `log_transferencias`
-- ----------------------------
DROP TABLE IF EXISTS `log_transferencias`;
CREATE TABLE `log_transferencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_transferencias
-- ----------------------------

-- ----------------------------
-- Table structure for `log_vendas`
-- ----------------------------
DROP TABLE IF EXISTS `log_vendas`;
CREATE TABLE `log_vendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_vendas
-- ----------------------------

-- ----------------------------
-- Table structure for `logcompras`
-- ----------------------------
DROP TABLE IF EXISTS `logcompras`;
CREATE TABLE `logcompras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of logcompras
-- ----------------------------

-- ----------------------------
-- Table structure for `lotto`
-- ----------------------------
DROP TABLE IF EXISTS `lotto`;
CREATE TABLE `lotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of lotto
-- ----------------------------

-- ----------------------------
-- Table structure for `medalhas`
-- ----------------------------
DROP TABLE IF EXISTS `medalhas`;
CREATE TABLE `medalhas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `medalha` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tipo` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of medalhas
-- ----------------------------

-- ----------------------------
-- Table structure for `membros`
-- ----------------------------
DROP TABLE IF EXISTS `membros`;
CREATE TABLE `membros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `usuarioid` int(11) NOT NULL,
  `posicao` int(11) NOT NULL DEFAULT '3',
  `rank` varchar(255) NOT NULL,
  `doado` int(11) NOT NULL DEFAULT '0',
  `status` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `missoes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `status` (`status`),
  KEY `orgid` (`orgid`),
  KEY `orgid_2` (`orgid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of membros
-- ----------------------------

-- ----------------------------
-- Table structure for `mensagens`
-- ----------------------------
DROP TABLE IF EXISTS `mensagens`;
CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `origem` int(11) NOT NULL,
  `destino` int(11) NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` text NOT NULL,
  `status` enum('lido','naolido') NOT NULL DEFAULT 'naolido',
  PRIMARY KEY (`id`),
  KEY `origem` (`origem`,`destino`),
  KEY `origem_2` (`origem`),
  KEY `destino` (`destino`),
  KEY `Otimizacao1` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mensagens
-- ----------------------------
INSERT INTO `mensagens` VALUES ('1', '2013-08-31 17:00:21', '1', '1', 'teste', 'teste', 'naolido');

-- ----------------------------
-- Table structure for `nal_torneio`
-- ----------------------------
DROP TABLE IF EXISTS `nal_torneio`;
CREATE TABLE `nal_torneio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('fechado','inscricao','aberto','fim') NOT NULL DEFAULT 'fechado',
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `nivelmin` int(11) NOT NULL,
  `nivelmax` int(11) NOT NULL,
  `custo` int(11) NOT NULL,
  `vencedor` int(11) NOT NULL,
  `premio` int(11) NOT NULL,
  `scorevenc` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `inscricoes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of nal_torneio
-- ----------------------------

-- ----------------------------
-- Table structure for `nal_war`
-- ----------------------------
DROP TABLE IF EXISTS `nal_war`;
CREATE TABLE `nal_war` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `status` enum('fechado','inscricao','aberto','fim') COLLATE latin1_general_ci DEFAULT 'fechado',
  `premio` int(255) NOT NULL,
  `vencedor` int(15) NOT NULL,
  `nivelmin` int(11) NOT NULL,
  `nivelmax` int(11) NOT NULL,
  `custo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- ----------------------------
-- Records of nal_war
-- ----------------------------

-- ----------------------------
-- Table structure for `nal_war_vilas`
-- ----------------------------
DROP TABLE IF EXISTS `nal_war_vilas`;
CREATE TABLE `nal_war_vilas` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `vilaid` int(15) NOT NULL,
  `warid` int(15) NOT NULL,
  `score` int(15) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- ----------------------------
-- Records of nal_war_vilas
-- ----------------------------

-- ----------------------------
-- Table structure for `natureza`
-- ----------------------------
DROP TABLE IF EXISTS `natureza`;
CREATE TABLE `natureza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `natureza` enum('fogo','agua','raio','terra','vento','nenhum') NOT NULL,
  `nivel` int(1) NOT NULL,
  `exp` int(11) NOT NULL,
  `expmax` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of natureza
-- ----------------------------

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) NOT NULL,
  `texto` text NOT NULL,
  `autor` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news
-- ----------------------------

-- ----------------------------
-- Table structure for `org_wars`
-- ----------------------------
DROP TABLE IF EXISTS `org_wars`;
CREATE TABLE `org_wars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org` int(11) NOT NULL,
  `war_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of org_wars
-- ----------------------------

-- ----------------------------
-- Table structure for `org_wars_arq`
-- ----------------------------
DROP TABLE IF EXISTS `org_wars_arq`;
CREATE TABLE `org_wars_arq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_org` int(11) NOT NULL,
  `from_org` int(11) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(2500) COLLATE utf8_unicode_ci NOT NULL,
  `exp_1` int(11) NOT NULL DEFAULT '0',
  `exp_2` int(11) NOT NULL DEFAULT '0',
  `vit_1` int(11) NOT NULL DEFAULT '0',
  `vit_2` int(11) NOT NULL DEFAULT '0',
  `der_1` int(11) NOT NULL DEFAULT '0',
  `der_2` int(11) NOT NULL DEFAULT '0',
  `total_1` int(11) NOT NULL DEFAULT '0',
  `total_2` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `war_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `result` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of org_wars_arq
-- ----------------------------

-- ----------------------------
-- Table structure for `org_wars_declare`
-- ----------------------------
DROP TABLE IF EXISTS `org_wars_declare`;
CREATE TABLE `org_wars_declare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_org` int(11) NOT NULL,
  `from_org` int(11) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(2500) COLLATE utf8_unicode_ci NOT NULL,
  `exp_1` int(25) NOT NULL DEFAULT '0',
  `exp_2` int(25) NOT NULL DEFAULT '0',
  `vit_1` int(25) NOT NULL DEFAULT '0',
  `vit_2` int(25) NOT NULL DEFAULT '0',
  `der_1` int(25) NOT NULL DEFAULT '0',
  `der_2` int(25) NOT NULL DEFAULT '0',
  `total_1` int(25) NOT NULL DEFAULT '0',
  `total_2` int(25) NOT NULL DEFAULT '0',
  `time` int(35) NOT NULL,
  `war_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of org_wars_declare
-- ----------------------------

-- ----------------------------
-- Table structure for `org_wars_paz`
-- ----------------------------
DROP TABLE IF EXISTS `org_wars_paz`;
CREATE TABLE `org_wars_paz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `war_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `temp` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of org_wars_paz
-- ----------------------------

-- ----------------------------
-- Table structure for `organizacoes`
-- ----------------------------
DROP TABLE IF EXISTS `organizacoes`;
CREATE TABLE `organizacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vila` int(11) NOT NULL,
  `sigla` varchar(4) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `expmax` int(11) NOT NULL DEFAULT '10',
  `liderid` int(11) NOT NULL,
  `reserva` int(11) NOT NULL DEFAULT '0',
  `data` datetime NOT NULL,
  `descricao` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  `minimo` int(11) NOT NULL DEFAULT '1',
  `vitorias_guerras` int(11) NOT NULL,
  `derrotas_guerras` int(11) NOT NULL,
  `pontos` int(11) NOT NULL,
  `war_v` int(11) NOT NULL,
  `war_d` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`id`),
  KEY `Otimizacao2` (`vila`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of organizacoes
-- ----------------------------

-- ----------------------------
-- Table structure for `personagens`
-- ----------------------------
DROP TABLE IF EXISTS `personagens`;
CREATE TABLE `personagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itachi` int(11) NOT NULL DEFAULT '0',
  `kisame` int(11) NOT NULL DEFAULT '0',
  `sasori` int(11) NOT NULL DEFAULT '0',
  `deidara` int(11) NOT NULL DEFAULT '0',
  `kakuzu` int(11) NOT NULL DEFAULT '0',
  `hidan` int(11) NOT NULL DEFAULT '0',
  `konohamaru` int(11) NOT NULL DEFAULT '0',
  `kiba` int(11) NOT NULL DEFAULT '0',
  `ino` int(11) NOT NULL DEFAULT '0',
  `tenten` int(11) NOT NULL DEFAULT '0',
  `lee` int(11) NOT NULL DEFAULT '0',
  `neji` int(11) NOT NULL DEFAULT '0',
  `hinata` int(11) NOT NULL DEFAULT '0',
  `temari` int(11) NOT NULL DEFAULT '0',
  `shino` int(11) NOT NULL DEFAULT '0',
  `kankurou` int(11) NOT NULL DEFAULT '0',
  `tayuya` int(11) NOT NULL DEFAULT '0',
  `gaara` int(11) NOT NULL DEFAULT '0',
  `shikamaru` int(11) NOT NULL DEFAULT '0',
  `chouji` int(11) NOT NULL DEFAULT '0',
  `haku` int(11) NOT NULL DEFAULT '0',
  `kabuto` int(11) NOT NULL DEFAULT '0',
  `kidoumaru` int(11) NOT NULL DEFAULT '0',
  `iruka` int(11) NOT NULL DEFAULT '0',
  `sai` int(11) NOT NULL DEFAULT '0',
  `zabuza` int(11) NOT NULL DEFAULT '0',
  `jiroubo` int(11) NOT NULL DEFAULT '0',
  `sakon` int(11) NOT NULL DEFAULT '0',
  `kimimaro` int(11) NOT NULL DEFAULT '0',
  `kurenai` int(11) NOT NULL DEFAULT '0',
  `hayate` int(11) NOT NULL DEFAULT '0',
  `hagane` int(11) NOT NULL DEFAULT '0',
  `asuma` int(11) NOT NULL DEFAULT '0',
  `gai` int(11) NOT NULL DEFAULT '0',
  `danzou` int(11) NOT NULL DEFAULT '0',
  `ao` int(11) NOT NULL DEFAULT '0',
  `tobi` int(11) NOT NULL DEFAULT '0',
  `shisui` int(11) NOT NULL DEFAULT '0',
  `jiraya` int(11) NOT NULL DEFAULT '0',
  `madara` int(11) NOT NULL DEFAULT '0',
  `obito` int(11) NOT NULL DEFAULT '0',
  `pain` int(11) NOT NULL DEFAULT '0',
  `mizukage` int(11) NOT NULL DEFAULT '0',
  `minato` int(11) NOT NULL DEFAULT '0',
  `orochimaru` int(11) NOT NULL DEFAULT '0',
  `zetsu` int(11) NOT NULL DEFAULT '0',
  `konan` int(11) NOT NULL DEFAULT '0',
  `nidaime` int(11) NOT NULL DEFAULT '0',
  `senju` int(11) NOT NULL DEFAULT '0',
  `killer bee` int(11) NOT NULL DEFAULT '0',
  `tsunade` int(11) NOT NULL DEFAULT '0',
  `tsuchikage` int(11) NOT NULL DEFAULT '0',
  `karin` int(11) NOT NULL DEFAULT '0',
  `raikage` int(11) NOT NULL DEFAULT '0',
  `suigetsu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of personagens
-- ----------------------------

-- ----------------------------
-- Table structure for `pets`
-- ----------------------------
DROP TABLE IF EXISTS `pets`;
CREATE TABLE `pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `petid` int(11) NOT NULL,
  `exp` int(11) NOT NULL DEFAULT '0',
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id`,`petid`,`usuarioid`),
  KEY `idx_2` (`expira`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pets
-- ----------------------------

-- ----------------------------
-- Table structure for `portao`
-- ----------------------------
DROP TABLE IF EXISTS `portao`;
CREATE TABLE `portao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `categoria` enum('portao') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of portao
-- ----------------------------

-- ----------------------------
-- Table structure for `quests`
-- ----------------------------
DROP TABLE IF EXISTS `quests`;
CREATE TABLE `quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `questid` int(11) NOT NULL,
  `vitorias` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`,`questid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of quests
-- ----------------------------

-- ----------------------------
-- Table structure for `ramen`
-- ----------------------------
DROP TABLE IF EXISTS `ramen`;
CREATE TABLE `ramen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `ramenid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ramen
-- ----------------------------

-- ----------------------------
-- Table structure for `relatorios`
-- ----------------------------
DROP TABLE IF EXISTS `relatorios`;
CREATE TABLE `relatorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `data` datetime NOT NULL,
  `usuarioid` int(11) NOT NULL,
  `inimigoid` int(11) NOT NULL,
  `vencedor` int(11) NOT NULL,
  `nivel` varchar(255) NOT NULL,
  `exp` varchar(255) NOT NULL,
  `yens` int(11) NOT NULL,
  `taijutsu` varchar(255) NOT NULL,
  `ninjutsu` varchar(255) NOT NULL,
  `genjutsu` varchar(255) NOT NULL,
  `energia` varchar(255) NOT NULL,
  `chakra` varchar(255) NOT NULL,
  `equips1` varchar(255) NOT NULL,
  `equips2` varchar(255) NOT NULL,
  `equips3` varchar(255) NOT NULL,
  `equips4` varchar(255) NOT NULL,
  `equips5` varchar(255) NOT NULL,
  `equips6` varchar(255) NOT NULL,
  `equips7` varchar(255) NOT NULL,
  `equips8` varchar(255) NOT NULL,
  `doujutsu` varchar(10) NOT NULL,
  `danos` varchar(255) NOT NULL,
  `ip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  PRIMARY KEY (`id`),
  KEY `idx_1` (`inimigoid`,`status`),
  KEY `idx_2` (`id`,`inimigoid`,`usuarioid`),
  KEY `idx_3` (`inimigoid`,`usuarioid`),
  KEY `idx_4` (`usuarioid`),
  KEY `idx_5` (`inimigoid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of relatorios
-- ----------------------------

-- ----------------------------
-- Table structure for `salas`
-- ----------------------------
DROP TABLE IF EXISTS `salas`;
CREATE TABLE `salas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL DEFAULT '0',
  `fim` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of salas
-- ----------------------------
INSERT INTO `salas` VALUES ('1', '6', '2013-09-01 12:24:51');
INSERT INTO `salas` VALUES ('2', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('3', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('4', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('5', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('6', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('7', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('8', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('9', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('10', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('11', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('12', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('13', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('14', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('15', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('16', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('17', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('18', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('19', '0', '0000-00-00 00:00:00');
INSERT INTO `salas` VALUES ('20', '0', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `seguranca`
-- ----------------------------
DROP TABLE IF EXISTS `seguranca`;
CREATE TABLE `seguranca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `origem` int(11) NOT NULL,
  `destino` int(11) NOT NULL,
  `assunto` varchar(60) NOT NULL,
  `msg` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of seguranca
-- ----------------------------

-- ----------------------------
-- Table structure for `selos`
-- ----------------------------
DROP TABLE IF EXISTS `selos`;
CREATE TABLE `selos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'off',
  `venda` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `valor` int(11) NOT NULL DEFAULT '0',
  `valorap` int(11) NOT NULL DEFAULT '500',
  `categoria` enum('animais') NOT NULL,
  `upgrade` int(11) NOT NULL DEFAULT '0',
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`),
  KEY `usuarioid_2` (`usuarioid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of selos
-- ----------------------------

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('tic', '');
INSERT INTO `settings` VALUES ('end_lotto', '');
INSERT INTO `settings` VALUES ('loteria', 'f');
INSERT INTO `settings` VALUES ('preco', '');
INSERT INTO `settings` VALUES ('vencedor', '');
INSERT INTO `settings` VALUES ('last_winner', '');
INSERT INTO `settings` VALUES ('lottery_premio', '');
INSERT INTO `settings` VALUES ('tipo', '');
INSERT INTO `settings` VALUES ('adm', '');

-- ----------------------------
-- Table structure for `spam`
-- ----------------------------
DROP TABLE IF EXISTS `spam`;
CREATE TABLE `spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `informanteid` int(11) NOT NULL,
  `informante` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of spam
-- ----------------------------

-- ----------------------------
-- Table structure for `table_animais`
-- ----------------------------
DROP TABLE IF EXISTS `table_animais`;
CREATE TABLE `table_animais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('animais') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `credshop` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `creditos` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqtai` int(11) NOT NULL DEFAULT '0',
  `reqnin` int(11) NOT NULL DEFAULT '0',
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  `maxtai` int(11) NOT NULL,
  `maxnin` int(11) NOT NULL,
  `maxgen` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`,`credshop`),
  KEY `idx_2` (`categoria`,`id`,`reqgen`),
  KEY `idx_3` (`categoria`,`reqgen`,`reqnin`,`reqtai`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_animais
-- ----------------------------
INSERT INTO `table_animais` VALUES ('1', 'animais', 'nao', 'nao', '0', 'Ton-Ton', '', '0', '0', '0', '1', '10000', 'tontonanimal', '10', '10', '10', '100', '100', '100');
INSERT INTO `table_animais` VALUES ('3', 'animais', 'nao', 'sim', '50', 'Enma', '', '0', '0', '0', '1', '100000', 'enmaanimal', '100', '100', '100', '1000', '1000', '1000');
INSERT INTO `table_animais` VALUES ('2', 'animais', 'sim', 'sim', '35', 'Taka', '', '0', '0', '0', '1', '80000', 'takaanimal', '78', '75', '80', '500', '490', '510');
INSERT INTO `table_animais` VALUES ('4', 'animais', 'nao', 'sim', '70', 'Mukade', '', '0', '0', '0', '1', '150000', 'Mukadeanimal', '113', '112', '110', '1500', '1490', '1480');
INSERT INTO `table_animais` VALUES ('5', 'animais', 'nao', 'nao', '0', 'Pakku', '', '0', '0', '0', '5', '20000', 'pakkunanimal', '20', '20', '15', '150', '150', '130');
INSERT INTO `table_animais` VALUES ('6', 'animais', 'nao', 'nao', '0', 'Gamakichi', '', '10', '10', '10', '10', '40000', 'gamakichianimal', '40', '30', '20', '170', '150', '140');
INSERT INTO `table_animais` VALUES ('7', 'animais', 'nao', 'nao', '0', 'Gamatatsu', '', '10', '10', '10', '10', '40000', 'gamatatsuanimal', '40', '30', '20', '170', '150', '130');
INSERT INTO `table_animais` VALUES ('8', 'animais', 'nao', 'nao', '0', 'Akamaru', '', '20', '20', '20', '20', '100000', 'akamaruanimal', '50', '50', '50', '200', '200', '200');
INSERT INTO `table_animais` VALUES ('9', 'animais', 'nao', 'nao', '0', 'Ninken', '', '30', '30', '30', '30', '150000', 'ninkenanimal', '70', '70', '70', '250', '250', '250');
INSERT INTO `table_animais` VALUES ('10', 'animais', 'nao', 'nao', '0', 'Doki', '', '40', '40', '40', '40', '200000', 'Dokianimal', '80', '80', '80', '300', '300', '300');

-- ----------------------------
-- Table structure for `table_bolsas`
-- ----------------------------
DROP TABLE IF EXISTS `table_bolsas`;
CREATE TABLE `table_bolsas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('bolsas') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `credshop` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `creditos` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqtai` int(11) NOT NULL DEFAULT '0',
  `reqnin` int(11) NOT NULL DEFAULT '0',
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`,`credshop`),
  KEY `idx_2` (`categoria`,`id`,`reqgen`),
  KEY `idx_3` (`categoria`,`reqgen`,`reqnin`,`reqtai`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_bolsas
-- ----------------------------
INSERT INTO `table_bolsas` VALUES ('1', 'bolsas', 'nao', 'nao', '0', 'Bolsa Pequena', 'Guarda 1,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '1000', 'bolsayens', '0', '0', '0');
INSERT INTO `table_bolsas` VALUES ('2', 'bolsas', 'nao', 'nao', '0', 'Bolsa Compacta', 'Guarda 5,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '5000', 'bolsayens', '0', '0', '0');
INSERT INTO `table_bolsas` VALUES ('3', 'bolsas', 'nao', 'nao', '0', 'Bolsa Media', 'Guarda 10,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '10000', 'bolsayens', '0', '0', '0');
INSERT INTO `table_bolsas` VALUES ('4', 'bolsas', 'nao', 'nao', '0', 'Bolsa Grande', 'Guarda 15,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '15000', 'bolsayens', '0', '0', '0');
INSERT INTO `table_bolsas` VALUES ('5', 'bolsas', 'nao', 'nao', '0', 'Bolsa Extra Grande', 'Guarda 20,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '20000', 'bolsayens', '0', '0', '0');
INSERT INTO `table_bolsas` VALUES ('6', 'bolsas', 'nao', 'nao', '0', 'Bolsa Mega Bonus', 'Guarda 100,000 yens (Para recuperar o dinheiro basta vender para o jogo)', '0', '0', '0', '0', '100000', 'bolsayens', '0', '0', '0');

-- ----------------------------
-- Table structure for `table_clashop`
-- ----------------------------
DROP TABLE IF EXISTS `table_clashop`;
CREATE TABLE `table_clashop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('hp','yens','cred') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `yens` int(11) NOT NULL,
  `cred` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`),
  KEY `idx_2` (`categoria`,`id`),
  KEY `idx_3` (`categoria`),
  KEY `idx_4` (`nome`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_clashop
-- ----------------------------
INSERT INTO `table_clashop` VALUES ('1', 'hp', 'nao', 'Pedra de hp', '+ 5000 hp', '0', '200', 'energia', '0', '0', '0', '5000', '0', '0');
INSERT INTO `table_clashop` VALUES ('2', 'cred', 'nao', '10 creditos ', '10 creditos', '0', '25000', 'yens', '0', '0', '0', '0', '0', '10');
INSERT INTO `table_clashop` VALUES ('3', 'yens', 'nao', 'Pacote yens', 'yens + 5000', '0', '2500', 'bolsayens', '0', '0', '0', '0', '5000', '0');

-- ----------------------------
-- Table structure for `table_investimentos`
-- ----------------------------
DROP TABLE IF EXISTS `table_investimentos`;
CREATE TABLE `table_investimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tai` int(11) NOT NULL DEFAULT '0',
  `nin` int(11) NOT NULL DEFAULT '0',
  `gen` int(11) NOT NULL DEFAULT '0',
  `custo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_investimentos
-- ----------------------------
INSERT INTO `table_investimentos` VALUES ('1', 'Sala de Treinamento de Taijutsu', 'Um local para treino de Taijutsu.', '3', '0', '0', '30000');
INSERT INTO `table_investimentos` VALUES ('2', 'Sala de Treinamento de Ninjutsu', 'Um local para treino de Ninjutsu.', '0', '3', '0', '30000');
INSERT INTO `table_investimentos` VALUES ('3', 'Sala de Treinamento de Genjutsu', 'Um local para treino de Genjutsu.', '0', '0', '3', '30000');

-- ----------------------------
-- Table structure for `table_invshop`
-- ----------------------------
DROP TABLE IF EXISTS `table_invshop`;
CREATE TABLE `table_invshop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('xp','dano','yens') NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `exp` int(11) NOT NULL,
  `tempo` int(11) NOT NULL,
  `porcentagem` varchar(25) NOT NULL,
  `yens` int(11) NOT NULL,
  `yensmax` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`),
  KEY `idx_2` (`categoria`,`id`),
  KEY `idx_3` (`categoria`),
  KEY `idx_4` (`nome`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_invshop
-- ----------------------------
INSERT INTO `table_invshop` VALUES ('1', 'xp', 'Amuleto do Anci', 'Poucos ninjas conseguiram possuir este amuleto,dizem que ', '1', '7', 'amuletoexp', '10', '7', '0', '0', '0');
INSERT INTO `table_invshop` VALUES ('2', 'dano', 'Amuleto da Furia', 'Um ferreiro criou um amuleto para konoha que quando utilizado o usuario poder', '1', '7', 'amuletodano', '0', '7', '25.0', '0', '0');

-- ----------------------------
-- Table structure for `table_itens`
-- ----------------------------
DROP TABLE IF EXISTS `table_itens`;
CREATE TABLE `table_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('arma','vestimenta','calcado','bijuu','acessorios') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `credshop` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `clashop` enum('nao','sim') NOT NULL DEFAULT 'nao',
  `creditos` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqtai` int(11) NOT NULL DEFAULT '0',
  `reqnin` int(11) NOT NULL DEFAULT '0',
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`,`credshop`),
  KEY `idx_2` (`categoria`,`id`,`reqgen`),
  KEY `idx_3` (`categoria`,`reqgen`,`reqnin`,`reqtai`),
  KEY `idx_4` (`nome`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_itens
-- ----------------------------
INSERT INTO `table_itens` VALUES ('1', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Ino', '', '0', '0', '0', '10', '4500', 'vestimenta_ino', '10', '10', '10');
INSERT INTO `table_itens` VALUES ('2', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Rock Lee', '', '0', '0', '0', '10', '4500', 'vestimenta_lee', '10', '10', '10');
INSERT INTO `table_itens` VALUES ('3', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta do Naruto', 'Vestimenta simples do Naruto', '0', '0', '0', '4', '750', 'roupa_naruto', '0', '0', '4');
INSERT INTO `table_itens` VALUES ('4', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta da Kushina', 'Vestimenta simples da Kushina', '0', '0', '0', '1', '250', 'roupa_konohamaru', '0', '0', '2');
INSERT INTO `table_itens` VALUES ('5', 'arma', 'nao', 'nao', 'nao', '5', 'Kunai Simples', 'Kunai de batalha, muito utilizada em confrontos f', '0', '0', '0', '1', '900', 'kunai_simples', '1', '0', '0');
INSERT INTO `table_itens` VALUES ('6', 'arma', 'nao', 'nao', 'nao', '0', 'Par de Kunais', 'Duas Kunais Simples. Quando utilizadas em conjunto, possui um efeito maior nos atributos de combate.', '0', '0', '0', '2', '1500', 'par_kunais', '2', '1', '1');
INSERT INTO `table_itens` VALUES ('7', 'arma', 'nao', 'nao', 'nao', '0', 'Shurikens', 'Varias shurikens para ataques em massa.', '0', '0', '0', '3', '1950', 'shurikens', '3', '2', '1');
INSERT INTO `table_itens` VALUES ('8', 'arma', 'sim', 'nao', 'nao', '0', 'Fuuma Shuriken', 'Shuriken de tamanho maximizado. Se obtiver sucesso no acerto, chega a matar a vitima.', '0', '0', '0', '4', '2300', 'fuuma_shuriken', '7', '3', '3');
INSERT INTO `table_itens` VALUES ('9', 'arma', 'nao', 'nao', 'nao', '0', 'Kunai Tridente', 'Kunai com 3 pontas. Este tipo de kunai foi muito utilizado pelo Yondaime.', '0', '0', '0', '5', '2500', 'kunai_yondaime', '8', '5', '3');
INSERT INTO `table_itens` VALUES ('10', 'arma', 'nao', 'nao', 'nao', '0', 'Par de Kunais Tridente', 'Duas Kunais Tridente. Quando utilizadas em conjunto, provoca um bonus enorme em Ninjutsu, mais alguns adicionais nos outros atributos.', '0', '0', '0', '7', '3800', 'par_kunais_yondaime', '13', '8', '4');
INSERT INTO `table_itens` VALUES ('11', 'arma', 'sim', 'nao', 'nao', '0', 'Senbons', 'Agulhas super afiadas, capazes de interromper o fluxo de chakra da vitima.', '0', '0', '0', '9', '4300', 'senbons', '14', '7', '7');
INSERT INTO `table_itens` VALUES ('12', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta do Sasuke', 'Vestimenta simples do Sasuke.', '0', '0', '0', '4', '750', 'roupa_sasuke', '0', '0', '4');
INSERT INTO `table_itens` VALUES ('13', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta da Sakura', 'Vestimenta simples da Sakura.', '0', '0', '0', '4', '750', 'roupa_sakura', '0', '0', '4');
INSERT INTO `table_itens` VALUES ('14', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Chouji', '', '0', '0', '0', '5', '2200', 'vestimenta_chouji', '2', '2', '7');
INSERT INTO `table_itens` VALUES ('15', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Kiba', '', '0', '0', '0', '5', '2200', 'vestimenta_kiba', '2', '2', '7');
INSERT INTO `table_itens` VALUES ('16', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Hinata', '', '0', '0', '0', '5', '2200', 'vestimenta_hinata', '2', '2', '7');
INSERT INTO `table_itens` VALUES ('17', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Tenten', '', '0', '0', '0', '12', '15000', 'vestimenta_tenten', '17', '17', '17');
INSERT INTO `table_itens` VALUES ('18', 'arma', 'nao', 'nao', 'nao', '0', 'Bast', '', '0', '0', '0', '10', '9000', 'bastao_combate', '15', '9', '8');
INSERT INTO `table_itens` VALUES ('19', 'arma', 'sim', 'nao', 'nao', '0', 'Laminas de Chakra', 'Arma extremamente cortante, ainda mais se usada com seu chakra, como catalizador.', '0', '0', '0', '12', '11200', 'laminas_chakra', '18', '14', '10');
INSERT INTO `table_itens` VALUES ('20', 'arma', 'nao', 'nao', 'nao', '0', 'Ninjaken', 'Katana utilizada por membros da ANBU, para combates de curt', '0', '0', '0', '13', '13350', 'ninjaken', '22', '13', '15');
INSERT INTO `table_itens` VALUES ('21', 'arma', 'nao', 'nao', 'nao', '0', 'Zanbatou', 'Espada grande e larga,com caracter', '0', '0', '0', '14', '15000', 'zanbatou', '26', '15', '20');
INSERT INTO `table_itens` VALUES ('22', 'arma', 'sim', 'nao', 'nao', '0', 'Katana', 'Espada utilizada por Sasuke, de tronco simples, e lamina fortalecida com chakra.', '0', '0', '0', '15', '20000', 'katana_sasuke', '30', '20', '25');
INSERT INTO `table_itens` VALUES ('23', 'arma', 'sim', 'nao', 'nao', '0', 'Samehada', 'Famosa espada utilizada por Kisame, que pode roubar o chakra do inimigo.', '0', '0', '0', '26', '50000', 'samehada', '90', '80', '85');
INSERT INTO `table_itens` VALUES ('24', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Shikamaru', '', '0', '0', '0', '13', '15000', 'vestimenta_shikamaru', '17', '17', '17');
INSERT INTO `table_itens` VALUES ('25', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Shino', '', '0', '0', '0', '14', '25000', 'vestimenta_shino', '25', '25', '25');
INSERT INTO `table_itens` VALUES ('26', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Asuma', '', '0', '0', '0', '17', '30000', 'vestimenta_asuma', '32', '32', '32');
INSERT INTO `table_itens` VALUES ('27', 'calcado', 'nao', 'nao', 'nao', '0', 'Sandália Ninja Simples', '', '2', '2', '2', '0', '1500', 'sandalia_simples', '2', '2', '2');
INSERT INTO `table_itens` VALUES ('28', 'calcado', 'nao', 'nao', 'nao', '0', 'Sandália Ninja Refinada', '', '10', '10', '10', '0', '4200', 'sandalia_refinada', '10', '10', '10');
INSERT INTO `table_itens` VALUES ('29', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '15', '15', '15', '0', '8000', 'calcado_combate', '15', '15', '15');
INSERT INTO `table_itens` VALUES ('30', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '25', '25', '25', '0', '15000', 'sandalia_protecao', '35', '35', '40');
INSERT INTO `table_itens` VALUES ('31', 'arma', 'nao', 'nao', 'nao', '0', 'Garra Envenenada', 'Garra Com Correntes extremamente fortes capazes de cortar com facilidade o inimigo deixando no corte da ferida um veneno Mortal!  ', '0', '0', '0', '25', '38220', 'garra_envenenada', '70', '60', '70');
INSERT INTO `table_itens` VALUES ('32', 'arma', 'sim', 'nao', 'nao', '0', 'Hiramekarei', '', '0', '0', '0', '22', '35000', 'hiramekarei', '60', '55', '50');
INSERT INTO `table_itens` VALUES ('33', 'arma', 'nao', 'nao', 'nao', '25', 'Ikazuki no Kiba', '', '0', '0', '0', '27', '60000', 'ikazuki_no_kiba', '100', '80', '95');
INSERT INTO `table_itens` VALUES ('34', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '35', '35', '35', '0', '20000', 'calcadochoujiro', '40', '0', '45');
INSERT INTO `table_itens` VALUES ('35', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '50', '50', '50', '0', '26500', 'calcadohinata', '50', '40', '50');
INSERT INTO `table_itens` VALUES ('36', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Kurenai', '', '0', '0', '0', '17', '30000', 'vestimenta_kurenai', '32', '32', '32');
INSERT INTO `table_itens` VALUES ('37', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Kabuto', '', '0', '0', '0', '18', '35750', 'vestimenta_kabuto', '40', '40', '40');
INSERT INTO `table_itens` VALUES ('38', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Gai Sensei', '', '0', '0', '0', '19', '40000', 'vestimenta_gai', '45', '45', '45');
INSERT INTO `table_itens` VALUES ('39', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Haku', '', '0', '0', '0', '20', '42500', 'vestimenta_haku', '50', '48', '48');
INSERT INTO `table_itens` VALUES ('40', 'bijuu', 'nao', 'nao', 'nao', '0', 'Matabi', 'O Nibi no Nekomata (??, que literalmente significa \"Gato de Duas-Caudas\") foi um Bij? selado em uma kunoichi de Kumogakure, Yugito Nii. N', '1', '0', '0', '17', '100000', 'matabibiju', '240', '250', '235');
INSERT INTO `table_itens` VALUES ('41', 'bijuu', 'nao', 'nao', 'nao', '0', 'Shukaku', 'Bijju de1 Cauda foi um Bij? selado em Gaara para servir como uma arma por Sunagakure e ao mesmo tempo representava uma grande amea', '1', '0', '1', '10', '80000', 'shukakubiju', '200', '210', '200');
INSERT INTO `table_itens` VALUES ('42', 'bijuu', 'nao', 'nao', 'nao', '0', 'Isobu', 'Sanbi (??, que literalmente significa \"Tartaruga M', '1', '0', '1', '25', '127000', 'isobubiju', '280', '285', '280');
INSERT INTO `table_itens` VALUES ('43', 'bijuu', 'sim', 'nao', 'nao', '0', 'Son Gokuu', 'O Yonbi (??, que significa \"Gorila de Quatro-Caudas\") tinha R?shi como seu Jinch?riki, ele foi capturado por Itachi e Kisame. Esse Bij? tinha a capacidade de usar o Y?ton, uma mistura dos elementos Katon e Doton. ', '1', '0', '0', '32', '160000', 'songokkubju', '300', '320', '320');
INSERT INTO `table_itens` VALUES ('44', 'bijuu', 'nao', 'nao', 'nao', '0', 'Kokuo ', 'Gobi (??, que literalmente significa \"Hibr', '1', '0', '0', '41', '215000', 'kokuobiju', '380', '440', '380');
INSERT INTO `table_itens` VALUES ('45', 'bijuu', 'sim', 'nao', 'nao', '100', 'Choumei', 'Shichibi (??, que literalmente significa \"Besouro-Rinoceronte de Sete-Caudas\") foi um Bij? selado em F? e que j', '1', '0', '1', '57', '350000', 'choumeibiju', '500', '550', '500');
INSERT INTO `table_itens` VALUES ('46', 'bijuu', 'nao', 'sim', 'nao', '150', 'Kurama', '+10,Super Raro Somente Encontrado No Credshop Ou Em Promo', '0', '0', '0', '999', '1000000', 'kuramabiju', '650', '700', '650');
INSERT INTO `table_itens` VALUES ('47', 'vestimenta', 'nao', 'sim', 'nao', '20', 'Vestimenta Akatsuki', '(Item Exclusivo Credshop)', '0', '0', '9999', '999', '200000', 'manto_akatsuki', '200', '200', '200');
INSERT INTO `table_itens` VALUES ('48', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Neji', '', '0', '0', '0', '15', '27000', 'vestimenta_neji', '27', '27', '27');
INSERT INTO `table_itens` VALUES ('49', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Neji Shippuden', '', '0', '0', '0', '30', '80000', 'vestimenta_neji_ship', '100', '100', '100');
INSERT INTO `table_itens` VALUES ('50', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Chouji Shippuden', '', '0', '0', '0', '21', '50000', 'vestimenta_chouji_ship', '56', '56', '56');
INSERT INTO `table_itens` VALUES ('51', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Tenten Shippuden', '', '0', '0', '0', '22', '50000', 'vestimenta_tenten_ship', '56', '56', '56');
INSERT INTO `table_itens` VALUES ('52', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Temari Shippuden', '', '0', '0', '0', '23', '53750', 'vestimenta_temari_ship', '60', '60', '60');
INSERT INTO `table_itens` VALUES ('53', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Kankurou Shippuden', '', '0', '0', '0', '24', '55000', 'vestimenta_kankurou_ship', '65', '65', '65');
INSERT INTO `table_itens` VALUES ('54', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Kiba Shippuden', '', '0', '0', '0', '25', '57500', 'vestimenta_kiba_ship', '70', '70', '70');
INSERT INTO `table_itens` VALUES ('55', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Shino Shippuden', '', '0', '0', '0', '29', '72500', 'vestimenta_shino_ship', '90', '90', '90');
INSERT INTO `table_itens` VALUES ('56', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Ino Shippuden', '', '0', '0', '0', '26', '60000', 'vestimenta_ino_ship', '75', '75', '75');
INSERT INTO `table_itens` VALUES ('57', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Shikamaru Shippuden', '', '0', '0', '0', '28', '70000', 'vestimenta_shikamaru_ship', '85', '85', '85');
INSERT INTO `table_itens` VALUES ('58', 'calcado', 'nao', 'sim', 'nao', '20', 'Botas Anbu', 'Botas extremamente Fortes quer no Ataque quer na defesa, Podendo ser complementadas com Chakra e super silenciosas !! \r\n', '65', '65', '65', '0', '27750', 'botas_anbu', '55', '50', '55');
INSERT INTO `table_itens` VALUES ('59', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '60', '60', '60', '0', '30000', 'calcadosasuke', '60', '60', '60');
INSERT INTO `table_itens` VALUES ('60', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Gaara', '', '0', '0', '0', '16', '28500', 'vestimenta_gaara', '29', '29', '29');
INSERT INTO `table_itens` VALUES ('61', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Sakura Shippuden', '', '0', '0', '215', '0', '82000', 'vestimenta_sakura_ship', '105', '105', '105');
INSERT INTO `table_itens` VALUES ('62', 'vestimenta', 'sim', 'nao', 'nao', '25', 'Vestimenta Naruto Shippuden', '', '0', '0', '0', '31', '82000', 'vestimenta_naruto_ship', '105', '105', '105');
INSERT INTO `table_itens` VALUES ('63', 'vestimenta', 'sim', 'nao', 'nao', '25', 'Vestimenta Sakura Shippuden', '', '0', '0', '0', '31', '82000', 'vestimenta_sakura_ship', '105', '105', '105');
INSERT INTO `table_itens` VALUES ('64', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Rock Lee Shippuden', '', '0', '0', '0', '27', '65000', 'vestimenta_lee_ship', '80', '80', '80');
INSERT INTO `table_itens` VALUES ('65', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Gaara Shippuden', '', '0', '0', '0', '33', '90000', 'vestimenta_gaara_ship', '117', '117', '117');
INSERT INTO `table_itens` VALUES ('66', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Zabuza', '', '0', '0', '0', '35', '100000', 'vestimenta_zabuza', '130', '130', '130');
INSERT INTO `table_itens` VALUES ('67', 'vestimenta', 'nao', 'nao', 'nao', '40', 'Vestimenta Kakashi', '', '0', '0', '0', '37', '125000', 'vestimenta_kakashi', '140', '140', '140');
INSERT INTO `table_itens` VALUES ('68', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Killer Bee', '', '0', '0', '0', '38', '135000', 'vestimenta_bee', '147', '147', '147');
INSERT INTO `table_itens` VALUES ('69', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Kimimaro', '', '0', '0', '0', '39', '140000', 'vestimenta_kimimaro', '152', '152', '152');
INSERT INTO `table_itens` VALUES ('70', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Sai', '', '0', '0', '0', '40', '147000', 'vestimenta_sai', '160', '160', '160');
INSERT INTO `table_itens` VALUES ('71', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Itachi', '', '0', '0', '0', '45', '170000', 'vestimenta_itachi', '190', '190', '190');
INSERT INTO `table_itens` VALUES ('72', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Sandaime', '', '0', '0', '0', '45', '171000', 'vestimenta_sandaime', '190', '190', '190');
INSERT INTO `table_itens` VALUES ('73', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Tsunade', '', '0', '0', '0', '47', '180000', 'vestimenta_godaime', '200', '200', '200');
INSERT INTO `table_itens` VALUES ('74', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Jiraya', '', '0', '0', '0', '47', '180000', 'vestimenta_jiraya', '200', '200', '200');
INSERT INTO `table_itens` VALUES ('75', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Orochimaru', '', '0', '0', '0', '47', '180000', 'vestimenta_orochimaru', '200', '200', '200');
INSERT INTO `table_itens` VALUES ('76', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Suigetsu', '', '0', '0', '0', '49', '200000', 'vestimenta_suigetsu', '215', '215', '215');
INSERT INTO `table_itens` VALUES ('77', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Uchiha Madara', '', '0', '0', '0', '50', '210000', 'vestimenta_madara', '220', '220', '220');
INSERT INTO `table_itens` VALUES ('78', 'vestimenta', 'nao', 'sim', 'nao', '35', 'Vestimenta Anbu', '(Item Exclusivo Credshop)', '0', '0', '0', '999', '200000', 'roupa_anbu', '240', '240', '240');
INSERT INTO `table_itens` VALUES ('79', 'bijuu', 'sim', 'nao', 'nao', '0', 'Saiken', 'Rokubi (??, que literalmente significa \"Lesma de Seis-Caudas\") foi capturado pela Akatsuki e foi extra', '1', '0', '1', '50', '260000', 'saikenbiju', '460', '470', '462');
INSERT INTO `table_itens` VALUES ('80', 'arma', 'nao', 'nao', 'nao', '30', 'Hachibi no Ken', 'Orifinalmente usada pelo grande ninja Killer Bee.', '0', '0', '0', '29', '65750', 'Hachibi_no_ken', '110', '100', '110');
INSERT INTO `table_itens` VALUES ('81', 'arma', 'sim', 'nao', 'nao', '0', 'Uchiha Gumbai', '', '0', '0', '0', '30', '80000', 'Uchiha_gumbai', '130', '100', '115');
INSERT INTO `table_itens` VALUES ('82', 'arma', 'nao', 'sim', 'nao', '25', 'Marionetes', '', '0', '0', '0', '999', '200000', '13', '240', '200', '180');
INSERT INTO `table_itens` VALUES ('83', 'calcado', 'sim', 'nao', 'nao', '0', 'Botas de Combate', '', '100', '100', '100', '0', '37500', 'botasdecombate', '80', '80', '80');
INSERT INTO `table_itens` VALUES ('84', 'arma', 'nao', 'nao', 'nao', '0', 'Ma', '', '0', '0', '0', '32', '92000', 'cani', '140', '120', '130');
INSERT INTO `table_itens` VALUES ('85', 'vestimenta', 'nao', 'sim', 'nao', '50', 'Vestimenta Namikaze Minato', '(Item Exclusivo Credshop)', '0', '0', '0', '999', '500000', 'roupa_kage_especial', '300', '300', '300');
INSERT INTO `table_itens` VALUES ('86', 'arma', 'nao', 'nao', 'nao', '0', 'Espada Ginkaku', '', '0', '0', '0', '35', '107000', 'jinkaku', '160', '155', '150');
INSERT INTO `table_itens` VALUES ('87', 'arma', 'nao', 'nao', 'nao', '0', 'Espada Kinkaku', '', '0', '0', '0', '36', '110000', 'jinkaku2', '167', '150', '145');
INSERT INTO `table_itens` VALUES ('88', 'arma', 'sim', 'nao', 'nao', '0', 'Foice Hidan', '', '0', '0', '0', '38', '115000', 'foicehidan', '172', '150', '160');
INSERT INTO `table_itens` VALUES ('89', 'arma', 'nao', 'nao', 'nao', '0', 'Hyuuga Garian Tou', '', '0', '0', '0', '39', '130000', 'ryuuga_garian_tou', '184', '165', '170');
INSERT INTO `table_itens` VALUES ('90', 'arma', 'sim', 'nao', 'nao', '0', 'Lamina Cristalina', '', '0', '0', '0', '42', '175000', 'lamina_cristalina', '200', '175', '185');
INSERT INTO `table_itens` VALUES ('91', 'arma', 'nao', 'sim', 'nao', '50', 'Leque Tobi Uchiha', 'Uma Arma extremamente poderosa nas m', '0', '0', '0', '999', '500000', 'lequeuchiha2', '280', '295', '230');
INSERT INTO `table_itens` VALUES ('92', 'acessorios', 'nao', 'nao', 'nao', '0', 'Monoculo Deidara', '', '0', '0', '0', '10', '15000', 'monoculodeidara', '30', '30', '30');
INSERT INTO `table_itens` VALUES ('93', 'acessorios', 'nao', 'nao', 'nao', '0', 'Dispositivo de Chakra', '', '0', '0', '0', '18', '22000', 'dispositivochakra', '50', '50', '50');
INSERT INTO `table_itens` VALUES ('94', 'acessorios', 'nao', 'nao', 'nao', '0', 'Anel Akatsuki (Deidara)', '', '0', '0', '0', '28', '40000', 'aneldeidara', '80', '70', '80');
INSERT INTO `table_itens` VALUES ('95', 'acessorios', 'sim', 'nao', 'nao', '25', 'Colar Tsunade', '', '0', '0', '0', '33', '50000', 'colartsunade', '92', '80', '85');
INSERT INTO `table_itens` VALUES ('96', 'acessorios', 'nao', 'sim', 'nao', '15', 'Mascara Kakashi', 'Item Exclusivo (Credshop)', '0', '0', '0', '99', '120000', 'mascarakakashi', '150', '150', '150');
INSERT INTO `table_itens` VALUES ('97', 'acessorios', 'sim', 'nao', 'nao', '0', 'Anel Akatsuki (Kisame)', '', '0', '0', '0', '35', '55000', 'anelkisame', '95', '100', '100');
INSERT INTO `table_itens` VALUES ('98', 'calcado', 'nao', 'nao', 'nao', '15', 'Cal', '', '120', '120', '120', '0', '45000', 'calcadosuigetsu', '100', '100', '100');
INSERT INTO `table_itens` VALUES ('99', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '150', '150', '150', '1', '50000', 'calcadojounnin', '115', '115', '115');
INSERT INTO `table_itens` VALUES ('100', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Tsuchikage', '', '0', '0', '0', '51', '230000', 'vestimenta_tsuchikage', '230', '230', '230');
INSERT INTO `table_itens` VALUES ('101', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Raikage', '', '0', '0', '0', '52', '245000', 'vestimenta_raikage', '240', '240', '240');
INSERT INTO `table_itens` VALUES ('102', 'acessorios', 'nao', 'nao', 'nao', '0', 'Anel Akatsuki (Kakuzu)', '', '0', '0', '0', '37', '57500', 'anelkakuso', '100', '100', '107');
INSERT INTO `table_itens` VALUES ('103', 'acessorios', 'sim', 'nao', 'nao', '0', 'Anel Akatsuki (Hidan)', '', '0', '0', '0', '39', '59000', 'anelhidan', '110', '100', '110');
INSERT INTO `table_itens` VALUES ('104', 'acessorios', 'sim', 'nao', 'nao', '0', 'Anel Akatsuki (Zetsu)', '', '0', '0', '0', '40', '60000', 'anelzetsu', '115', '110', '115');
INSERT INTO `table_itens` VALUES ('105', 'acessorios', 'sim', 'nao', 'nao', '0', 'Anel Akatsuki (Konan)', '', '0', '0', '0', '43', '65000', 'anelkonan', '120', '120', '120');
INSERT INTO `table_itens` VALUES ('106', 'acessorios', 'nao', 'nao', 'nao', '0', 'Anel Akatsuki (Orochimaru)', '', '0', '0', '0', '45', '70000', 'anelorochimaru', '130', '130', '130');
INSERT INTO `table_itens` VALUES ('107', 'acessorios', 'sim', 'nao', 'nao', '0', 'Anel Akatsuki (Itachi)', '', '0', '0', '0', '48', '75000', 'anelakt1', '140', '140', '140');
INSERT INTO `table_itens` VALUES ('108', 'acessorios', 'nao', 'nao', 'nao', '0', 'Anel Akatsuki (Pain)', '', '0', '0', '0', '50', '82500', 'anelpain', '150', '150', '150');
INSERT INTO `table_itens` VALUES ('109', 'acessorios', 'nao', 'sim', 'nao', '25', 'Anel Akatsuki (Sasori & Tobi)', 'Item Exclusivo (Credshop)', '0', '0', '0', '9999', '300000', 'anelsasoritobi', '160', '160', '160');
INSERT INTO `table_itens` VALUES ('110', 'acessorios', 'sim', 'nao', 'nao', '0', 'Mascara Fuuton', '', '0', '0', '0', '53', '92000', 'mascarafuuton', '156', '160', '155');
INSERT INTO `table_itens` VALUES ('111', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '200', '200', '200', '10', '75000', 'calcadogai', '130', '130', '130');
INSERT INTO `table_itens` VALUES ('112', 'calcado', 'sim', 'nao', 'nao', '0', 'Cal', '', '215', '215', '215', '10', '85000', 'KAGEBOTA', '145', '145', '145');
INSERT INTO `table_itens` VALUES ('113', 'calcado', 'nao', 'nao', 'nao', '25', 'Cal', '', '250', '250', '250', '1', '100000', 'calcadogaarashipp', '160', '160', '160');
INSERT INTO `table_itens` VALUES ('114', 'calcado', 'nao', 'sim', 'nao', '25', 'Calçado Naruto Shipp', '', '300', '300', '300', '1', '150000', 'calcadonarutoshipp', '190', '190', '190');
INSERT INTO `table_itens` VALUES ('115', 'calcado', 'nao', 'nao', 'nao', '25', 'Cal', '', '250', '250', '250', '1', '100000', 'calcadokarin', '160', '160', '160');
INSERT INTO `table_itens` VALUES ('116', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Naruto Sannin Mode', '', '1', '1', '100', '55', '265000', 'narutosaninmode', '260', '260', '260');
INSERT INTO `table_itens` VALUES ('117', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Nidaime', '', '1', '1', '1', '57', '275000', 'vestimenta_nidaime', '272', '272', '272');
INSERT INTO `table_itens` VALUES ('118', 'vestimenta', 'sim', 'nao', 'nao', '75', 'Vestimenta Hashirama', '', '1', '1', '1', '60', '300000', 'vesthashirama', '290', '290', '290');
INSERT INTO `table_itens` VALUES ('119', 'vestimenta', 'nao', 'sim', 'nao', '35', 'Vestimenta Karin', '(Item Exclusivo Credshop)', '0', '0', '0', '999', '100000', 'vestkarin', '240', '230', '250');
INSERT INTO `table_itens` VALUES ('120', 'bijuu', 'nao', 'sim', 'nao', '200', 'Juubi', '+10,Super Raro Somente Encontrado No Credshop Ou Em Promo', '0', '0', '0', '999', '2000000', 'juubibiju', '700', '750', '700');
INSERT INTO `table_itens` VALUES ('121', 'vestimenta', 'nao', 'sim', 'nao', '70', 'Vestimenta Tobi', 'Exclusiva CredShop', '0', '0', '0', '9999', '1000000', 'roupa_tobi', '330', '330', '330');
INSERT INTO `table_itens` VALUES ('122', 'calcado', 'sim', 'nao', 'nao', '0', 'Cal', '', '320', '320', '320', '1', '180000', 'calcadosasukeshipp', '215', '215', '215');
INSERT INTO `table_itens` VALUES ('123', 'arma', 'nao', 'sim', 'nao', '70', 'Espada Nidaime & Senju', 'Exclusivo Credshop', '0', '0', '0', '999', '700000', 'espadasenjunidaime', '300', '300', '250');
INSERT INTO `table_itens` VALUES ('124', 'bijuu', 'nao', 'sim', 'nao', '100', 'Hachibi', '', '0', '0', '0', '62', '430000', 'hachibibiju', '580', '620', '580');
INSERT INTO `table_itens` VALUES ('125', 'acessorios', 'nao', 'nao', 'nao', '0', 'Mascara Katon', '', '0', '0', '0', '57', '100000', 'mascarakaton', '160', '160', '170');
INSERT INTO `table_itens` VALUES ('126', 'acessorios', 'nao', 'nao', 'nao', '50', 'Mascara Raiton', '', '0', '0', '0', '59', '120000', 'mascararaiton', '170', '170', '170');
INSERT INTO `table_itens` VALUES ('127', 'acessorios', 'sim', 'nao', 'nao', '0', 'Mascara Nidaime', '', '0', '0', '0', '60', '135000', 'mascaranidaime', '185', '180', '175');
INSERT INTO `table_itens` VALUES ('128', 'acessorios', 'nao', 'nao', 'nao', '50', 'Mascara Tobi', '', '0', '0', '0', '70', '200000', 'mascaratobi', '205', '200', '210');
INSERT INTO `table_itens` VALUES ('129', 'acessorios', 'nao', 'nao', 'nao', '0', 'Mascara Tobi Guerra', '', '0', '0', '0', '72', '250000', 'mascaratobi3', '215', '210', '220');
INSERT INTO `table_itens` VALUES ('130', 'arma', 'nao', 'nao', 'nao', '0', 'Katana Ossea', '', '0', '0', '0', '45', '190000', 'katana_ossea', '210', '180', '190');
INSERT INTO `table_itens` VALUES ('131', 'arma', 'nao', 'nao', 'nao', '0', 'Katana Alongada', '', '0', '0', '0', '47', '200000', 'katana_longa', '218', '190', '190');
INSERT INTO `table_itens` VALUES ('132', 'arma', 'sim', 'nao', 'nao', '0', 'Lan', '', '0', '0', '0', '50', '220000', 'lancashukaku', '225', '200', '200');
INSERT INTO `table_itens` VALUES ('133', 'arma', 'nao', 'nao', 'nao', '0', 'Kusanagi', '', '0', '0', '0', '52', '230000', 'kusanagi', '235', '207', '205');
INSERT INTO `table_itens` VALUES ('134', 'arma', 'nao', 'nao', 'nao', '0', 'Kusanagi Tsurugi', '', '0', '0', '0', '55', '245000', 'kusanagi_tsurugi', '240', '210', '210');
INSERT INTO `table_itens` VALUES ('135', 'arma', 'sim', 'nao', 'nao', '0', 'Karasu', '', '0', '0', '0', '60', '265000', 'karasu', '260', '220', '220');
INSERT INTO `table_itens` VALUES ('136', 'arma', 'sim', 'nao', 'nao', '0', 'Kuroari', '', '0', '0', '0', '61', '270000', 'kuroari', '265', '225', '225');
INSERT INTO `table_itens` VALUES ('137', 'arma', 'nao', 'nao', 'nao', '60', 'Marionete Sandaime', '', '0', '0', '0', '65', '300000', 'sandaime_kazekage', '280', '230', '220');
INSERT INTO `table_itens` VALUES ('138', 'calcado', 'nao', 'sim', 'nao', '35', 'Botas Akatsuki', '', '372', '372', '372', '1', '200000', 'calcado_akatsuki', '235', '230', '235');
INSERT INTO `table_itens` VALUES ('139', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '400', '400', '400', '1', '230000', 'calcadosanin', '250', '255', '250');
INSERT INTO `table_itens` VALUES ('140', 'calcado', 'nao', 'sim', 'nao', '50', 'Calçado Nidaime', '', '430', '430', '430', '1', '250000', 'calcadonidaime', '270', '270', '270');
INSERT INTO `table_itens` VALUES ('141', 'calcado', 'nao', 'nao', 'nao', '0', 'Cal', '', '470', '470', '470', '1', '275000', 'calcadomadara', '285', '285', '285');
INSERT INTO `table_itens` VALUES ('142', 'calcado', 'sim', 'sim', 'nao', '90', 'Calçado Naruto Kyubi', '', '500', '500', '500', '1', '300000', 'calcadochakranarutokyuubi', '300', '300', '300');
INSERT INTO `table_itens` VALUES ('143', 'calcado', 'nao', 'sim', 'nao', '50', 'Calçado Mizukage', '', '430', '430', '430', '1', '250000', 'calcadomizukage', '270', '270', '270');
INSERT INTO `table_itens` VALUES ('144', 'arma', 'nao', 'sim', 'nao', '110', 'Lamina Totsuka', '(Exclusiva Credshop)', '0', '0', '0', '999', '2000000', 'espadasusano', '320', '320', '300');
INSERT INTO `table_itens` VALUES ('145', 'vestimenta', 'nao', 'sim', 'nao', '100', 'Vestimenta Hokage', 'Exclusivo Credshop', '0', '0', '0', '999', '1000000', 'vesthokage', '350', '350', '350');
INSERT INTO `table_itens` VALUES ('146', 'vestimenta', 'nao', 'sim', 'nao', '100', 'Vestimenta Mizukage', 'Exclusiva Credshop', '0', '0', '0', '999', '1000000', 'vestmizukagecred', '350', '350', '350');
INSERT INTO `table_itens` VALUES ('147', 'acessorios', 'nao', 'sim', 'nao', '75', 'Colar Rikkudou', 'Exclusivo Credshop', '0', '0', '0', '999', '500000', 'colarrikudou', '215', '222', '220');
INSERT INTO `table_itens` VALUES ('148', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Yugito', '', '0', '0', '0', '62', '315000', 'vestyugito', '300', '300', '300');
INSERT INTO `table_itens` VALUES ('149', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Fuu', '', '0', '0', '0', '62', '315000', 'vestfuu', '300', '300', '300');
INSERT INTO `table_itens` VALUES ('150', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Han', '', '0', '0', '0', '65', '340000', 'vesthan', '305', '320', '310');
INSERT INTO `table_itens` VALUES ('151', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Omoi', '', '0', '0', '0', '67', '365000', 'vestomoi', '320', '320', '320');
INSERT INTO `table_itens` VALUES ('152', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Samui', '', '0', '0', '0', '67', '365000', 'vestsamui', '320', '310', '330');
INSERT INTO `table_itens` VALUES ('153', 'vestimenta', 'nao', 'nao', 'nao', '0', 'Vestimenta Utakata', '', '0', '0', '0', '69', '390000', 'vestutakata', '330', '330', '330');
INSERT INTO `table_itens` VALUES ('154', 'vestimenta', 'sim', 'nao', 'nao', '0', 'Vestimenta Yagura', '', '0', '0', '0', '70', '410750', 'vestyagura', '325', '340', '330');
INSERT INTO `table_itens` VALUES ('155', 'arma', 'nao', 'sim', 'nao', '110', 'Escudo Yata', '(Exclusiva Credshop)', '0', '0', '0', '99', '2000000', 'escudo', '280', '340', '300');
INSERT INTO `table_itens` VALUES ('156', 'acessorios', 'nao', 'sim', 'nao', '50', 'Colar Itachi Uchiha', '(Exclusivo Credshop)', '0', '0', '0', '99', '400000', 'colaritachi', '205', '205', '205');

-- ----------------------------
-- Table structure for `table_jutsus`
-- ----------------------------
DROP TABLE IF EXISTS `table_jutsus`;
CREATE TABLE `table_jutsus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `natureza` enum('fogo','agua','raio','terra','vento','nenhum') NOT NULL,
  `creditos` int(11) NOT NULL DEFAULT '0',
  `forca` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `doujutsu` int(11) NOT NULL DEFAULT '0',
  `doujutsu_nivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `texto` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`id`,`natureza`,`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=56756346 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_jutsus
-- ----------------------------
INSERT INTO `table_jutsus` VALUES ('28', ' Magen Kasegui no Jutsu', 'nenhum', '0', '40', '10', '1', '5', '10000', '\"Somente olha para seu inimigo e o mesmo sente estacas enormes em todo seu corpo impedindo o mesmo de realizar qualquer movimento .\"<br />$player1 utilizou $jutsu em $player2, $dano');
INSERT INTO `table_jutsus` VALUES ('27', 'Hiraishin no Jutsu', 'nenhum', '0', '60', '15', '0', '0', '10000', '\"Marca o oponnente com um selo,e instantaniamente se teletransporta  e o ataqua sem o memso sequer notar a sua presen');
INSERT INTO `table_jutsus` VALUES ('26', 'Oodama Rasengan', 'nenhum', '0', '45', '6', '0', '0', '650', '$player1 Aperfei');
INSERT INTO `table_jutsus` VALUES ('25', 'Izanagi', 'nenhum', '0', '200', '25', '4', '30', '9000', '<i>\"Com o poder de se conectar ');
INSERT INTO `table_jutsus` VALUES ('24', 'Kamui', 'nenhum', '0', '80', '15', '4', '10', '8000', '<i>\"Fecha os olhos e se concentra,para criar uma fissura no Espa');
INSERT INTO `table_jutsus` VALUES ('23', 'Shishi Rendan', 'nenhum', '0', '40', '6', '0', '0', '600', '$player1 come');
INSERT INTO `table_jutsus` VALUES ('22', 'Chibaku Tensei', 'nenhum', '0', '100', '20', '3', '20', '7000', '<i>\"T');
INSERT INTO `table_jutsus` VALUES ('21', 'Six Paths of Pein', 'nenhum', '0', '60', '10', '3', '10', '5000', '<i>\"Conseguir');
INSERT INTO `table_jutsus` VALUES ('20', 'Naraku Kohushi Baski Tensei', 'nenhum', '0', '40', '5', '3', '8', '3500', '<i>\"Kohushi Baski, apare');
INSERT INTO `table_jutsus` VALUES ('19', 'Juukenhou - Hakke Sanbyakurokujuuichi Shisa', 'nenhum', '0', '80', '15', '2', '15', '7000', '<i>\"T');
INSERT INTO `table_jutsus` VALUES ('18', 'Juukenhou - Hakke Hyakunijuuhachi Shou', 'nenhum', '0', '60', '7', '2', '10', '5000', '<i>\"T');
INSERT INTO `table_jutsus` VALUES ('17', 'Juukenhou - Hakke Rokujuuyon Shou', 'nenhum', '0', '35', '5', '2', '8', '3500', '<i>\"T');
INSERT INTO `table_jutsus` VALUES ('16', 'Susanoo', 'nenhum', '0', '110', '19', '4', '26', '7000', '<i>\"Deus imortal, desintegre a exist');
INSERT INTO `table_jutsus` VALUES ('15', 'Amaterasu', 'nenhum', '0', '85', '16', '4', '20', '5000', '<i>\"Chamas negras que jamais se apagam, consuma meu inimigo at');
INSERT INTO `table_jutsus` VALUES ('14', 'Tsukuyomi', 'nenhum', '0', '67', '12', '4', '10', '3500', '<i>\"Sua alma vagar');
INSERT INTO `table_jutsus` VALUES ('13', 'Doton: Doryuudan', 'terra', '0', '40', '16', '0', '0', '2600', '$player1 criou um drag');
INSERT INTO `table_jutsus` VALUES ('12', 'Fuuton: Juuha Shou', 'vento', '0', '30', '16', '0', '0', '2600', '$player1 utilizou o jutsu $jutsu para criar uma l');
INSERT INTO `table_jutsus` VALUES ('11', 'Suiton: Suiryuudan no Jutsu', 'agua', '0', '60', '16', '0', '0', '2600', '$player1 realizou alguns selos, e criou um drag');
INSERT INTO `table_jutsus` VALUES ('9', 'Raiton: Gian', 'raio', '0', '85', '16', '0', '0', '2600', '$player1 usou o jutsu $jutsu, disparando um enorme raio com um imenso poder de destrui');
INSERT INTO `table_jutsus` VALUES ('10', 'Katon: Housenka no Jutsu', 'fogo', '0', '60', '16', '0', '0', '2600', '$player1 come');
INSERT INTO `table_jutsus` VALUES ('8', 'Tajuu Kage Bunshin no Jutsu', 'nenhum', '0', '25', '5', '0', '0', '450', '$player1 multiplicou-se rapidamente com o jutsu $jutsu, criando centenas de clones, que atacaram $player2, $dano');
INSERT INTO `table_jutsus` VALUES ('7', 'Kage Bunshin no Jutsu', 'nenhum', '0', '15', '2', '0', '0', '150', '$player1 utilizou o jutsu $jutsu, criando v');
INSERT INTO `table_jutsus` VALUES ('5', 'Raiton Chidori', 'raio', '0', '55', '11', '0', '0', '600', '$player1 concentrou seu chakra do raio em sua m');
INSERT INTO `table_jutsus` VALUES ('6', 'Doton: Doryuuheki', 'terra', '0', '60', '12', '0', '0', '600', '$player1 realizou alguns selos de m');
INSERT INTO `table_jutsus` VALUES ('4', 'Suiton: Mizu Bunshin no Jutsu', 'agua', '0', '40', '12', '0', '0', '2500', '$player1 criou alguns clones de ');
INSERT INTO `table_jutsus` VALUES ('3', 'Katon: Goukakyuu no Jutsu', 'fogo', '0', '40', '12', '0', '0', '600', '$player1 utilizou a natureza de seu chakra para criar o jutsu $jutsu. A enorme bola de fogo foi ao encontro de $player2, $dano');
INSERT INTO `table_jutsus` VALUES ('1', 'Rasengan', 'nenhum', '0', '35', '4', '0', '0', '300', '$player1 concentrou seu chakra na palma da m');
INSERT INTO `table_jutsus` VALUES ('2', 'Fuuton: Rasenshuriken', 'vento', '0', '85', '12', '0', '0', '600', '$player1 concentrou seu chakra do vento na m');
INSERT INTO `table_jutsus` VALUES ('29', 'Magen  Moeru Karada Kami', 'nenhum', '0', '65', '12', '1', '13', '20000', '$player1 Genjutsu, que d');
INSERT INTO `table_jutsus` VALUES ('30', 'Karasu no Genjutsu', 'nenhum', '0', '50', '17', '1', '10', '30000', '$player1 Genjutsu capaz de criar corvos ilus');
INSERT INTO `table_jutsus` VALUES ('31', 'Fuuton Shinkuuha', 'vento', '0', '40', '10', '0', '0', '10000', '<i>\"Toma uma respira');
INSERT INTO `table_jutsus` VALUES ('32', 'Fuuton Shinkuugyoku', 'vento', '0', '55', '12', '0', '0', '20000', '<i>\"Depois de completar o selo necess');
INSERT INTO `table_jutsus` VALUES ('33', 'Fuuton Shinkuudaigyo', 'vento', '0', '65', '15', '0', '0', '25000', '<i>\"Depois de completar os selos necess');
INSERT INTO `table_jutsus` VALUES ('34', 'Fuuton  Shinkuurenpa', 'vento', '0', '85', '17', '0', '0', '30000', '<i>\"Dispara uma quantidade sucessiva de rajadas, de v');
INSERT INTO `table_jutsus` VALUES ('35', 'Rendam', 'nenhum', '0', '12', '5', '0', '0', '450', '<i>\"Come');
INSERT INTO `table_jutsus` VALUES ('36', 'Asshou', 'nenhum', '0', '17', '7', '0', '0', '750', '<i>\"Ataca com uma press');
INSERT INTO `table_jutsus` VALUES ('37', 'Chuusuusei Biribiri', 'nenhum', '0', '19', '8', '0', '0', '860', '<i>\"Ataque que transforma o seu chakra em pulsos el');

-- ----------------------------
-- Table structure for `table_missoes`
-- ----------------------------
DROP TABLE IF EXISTS `table_missoes`;
CREATE TABLE `table_missoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('aguardo','andamento') NOT NULL,
  `orgid` int(11) NOT NULL,
  `membros` int(11) NOT NULL DEFAULT '0',
  `maximo` int(11) NOT NULL,
  `yens` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `logo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orgid` (`orgid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of table_missoes
-- ----------------------------

-- ----------------------------
-- Table structure for `table_personagens`
-- ----------------------------
DROP TABLE IF EXISTS `table_personagens`;
CREATE TABLE `table_personagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem` varchar(255) NOT NULL,
  `nivel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_personagens
-- ----------------------------
INSERT INTO `table_personagens` VALUES ('1', 'temari', '2');
INSERT INTO `table_personagens` VALUES ('2', 'hinata', '3');
INSERT INTO `table_personagens` VALUES ('3', 'lee', '4');
INSERT INTO `table_personagens` VALUES ('4', 'neji', '5');
INSERT INTO `table_personagens` VALUES ('5', 'tenten', '6');
INSERT INTO `table_personagens` VALUES ('6', 'kiba', '7');
INSERT INTO `table_personagens` VALUES ('7', 'shino', '8');
INSERT INTO `table_personagens` VALUES ('8', 'kankurou', '9');
INSERT INTO `table_personagens` VALUES ('9', 'tayuya', '10');
INSERT INTO `table_personagens` VALUES ('10', 'gaara', '11');
INSERT INTO `table_personagens` VALUES ('11', 'ino', '12');
INSERT INTO `table_personagens` VALUES ('12', 'shikamaru', '13');
INSERT INTO `table_personagens` VALUES ('13', 'chouji', '14');
INSERT INTO `table_personagens` VALUES ('14', 'haku', '15');
INSERT INTO `table_personagens` VALUES ('15', 'kabuto', '16');
INSERT INTO `table_personagens` VALUES ('16', 'konohamaru', '1');
INSERT INTO `table_personagens` VALUES ('17', 'kidoumaru', '17');
INSERT INTO `table_personagens` VALUES ('18', 'iruka', '18');
INSERT INTO `table_personagens` VALUES ('19', 'sai', '19');
INSERT INTO `table_personagens` VALUES ('20', 'zabuza', '20');
INSERT INTO `table_personagens` VALUES ('21', 'jiroubo', '21');
INSERT INTO `table_personagens` VALUES ('22', 'sakon', '22');
INSERT INTO `table_personagens` VALUES ('23', 'kimimaro', '23');
INSERT INTO `table_personagens` VALUES ('24', 'kurenai', '24');
INSERT INTO `table_personagens` VALUES ('25', 'hayate', '25');
INSERT INTO `table_personagens` VALUES ('26', 'hagane', '26');
INSERT INTO `table_personagens` VALUES ('27', 'asuma', '27');
INSERT INTO `table_personagens` VALUES ('28', 'gai', '28');
INSERT INTO `table_personagens` VALUES ('29', 'danzou', '29');
INSERT INTO `table_personagens` VALUES ('30', 'tobi', '32');
INSERT INTO `table_personagens` VALUES ('31', 'shisui', '33');
INSERT INTO `table_personagens` VALUES ('32', 'itachi', '31');
INSERT INTO `table_personagens` VALUES ('33', 'jiraya', '34');
INSERT INTO `table_personagens` VALUES ('34', 'sasori', '43');
INSERT INTO `table_personagens` VALUES ('35', 'madara', '36');
INSERT INTO `table_personagens` VALUES ('36', 'pain', '41');
INSERT INTO `table_personagens` VALUES ('37', 'konan', '35');
INSERT INTO `table_personagens` VALUES ('38', 'nidaime', '49');
INSERT INTO `table_personagens` VALUES ('39', 'ao', '40');
INSERT INTO `table_personagens` VALUES ('40', 'mizukage', '42');
INSERT INTO `table_personagens` VALUES ('41', 'minato', '44');
INSERT INTO `table_personagens` VALUES ('42', 'orochimaru', '45');
INSERT INTO `table_personagens` VALUES ('43', 'obito', '46');
INSERT INTO `table_personagens` VALUES ('44', 'deidara', '37');
INSERT INTO `table_personagens` VALUES ('45', 'suigetsu', '51');
INSERT INTO `table_personagens` VALUES ('46', 'senju', '50');
INSERT INTO `table_personagens` VALUES ('47', 'tsunade', '52');
INSERT INTO `table_personagens` VALUES ('48', 'raikage', '53');

-- ----------------------------
-- Table structure for `table_pets`
-- ----------------------------
DROP TABLE IF EXISTS `table_pets`;
CREATE TABLE `table_pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` tinytext NOT NULL,
  `nivel` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  `vida` int(11) NOT NULL,
  `venda` enum('sim','nao') NOT NULL DEFAULT 'sim',
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_pets
-- ----------------------------

-- ----------------------------
-- Table structure for `table_portoes`
-- ----------------------------
DROP TABLE IF EXISTS `table_portoes`;
CREATE TABLE `table_portoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('portao') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `credshop` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `creditos` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqtai` int(11) NOT NULL DEFAULT '0',
  `reqnin` int(11) NOT NULL DEFAULT '0',
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  `vida` int(11) NOT NULL,
  `porcentagem` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`,`credshop`),
  KEY `idx_2` (`categoria`,`id`,`reqgen`),
  KEY `idx_3` (`categoria`,`reqgen`,`reqnin`,`reqtai`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_portoes
-- ----------------------------
INSERT INTO `table_portoes` VALUES ('1', 'portao', 'nao', 'nao', '0', '1', 'Localiza-se ao lado direito do c', '0', '0', '0', '11', '20000', 'portao1', '0', '0', '0', '3', '7.0');
INSERT INTO `table_portoes` VALUES ('2', 'portao', 'nao', 'nao', '0', '2', 'Localiza-se ao lado esquerdo do c', '0', '0', '0', '20', '25000', 'portao2', '0', '0', '0', '3', '12.0');
INSERT INTO `table_portoes` VALUES ('3', 'portao', 'nao', 'nao', '0', '3', 'Localiza-se entre os pulm', '0', '0', '0', '30', '35000', 'portao3', '0', '0', '0', '3', '18.0');
INSERT INTO `table_portoes` VALUES ('4', 'portao', 'nao', 'nao', '0', '4', 'A partir da abertura do quarto port', '0', '0', '0', '40', '45000', 'portao4', '0', '0', '0', '3', '22.0');
INSERT INTO `table_portoes` VALUES ('5', 'portao', 'nao', 'nao', '0', '5', 'Localizado abaixo do quarto port', '0', '0', '0', '50', '50000', 'portao5', '0', '0', '0', '3', '25.0');
INSERT INTO `table_portoes` VALUES ('6', 'portao', 'nao', 'nao', '0', '6', 'Localiza-se abaixo do quinto port', '0', '0', '0', '60', '55000', 'portao6', '0', '0', '0', '3', '30.0');
INSERT INTO `table_portoes` VALUES ('7', 'portao', 'nao', 'nao', '0', '7', 'Localizado abaixo do sexto port', '0', '0', '0', '70', '60000', 'portao7', '0', '0', '0', '2', '40.0');
INSERT INTO `table_portoes` VALUES ('8', 'portao', 'sim', 'nao', '0', '8', 'Localiza-se no cora', '0', '0', '0', '80', '65000', 'portao8', '0', '0', '0', '1', '60.0');

-- ----------------------------
-- Table structure for `table_quests`
-- ----------------------------
DROP TABLE IF EXISTS `table_quests`;
CREATE TABLE `table_quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `nivel` int(11) NOT NULL,
  `vitorias` int(11) NOT NULL,
  `yens` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_quests
-- ----------------------------
INSERT INTO `table_quests` VALUES ('1', 'Quest Estudante Ninja', 'Lute e ven', '1', '25', '1500', '100');
INSERT INTO `table_quests` VALUES ('2', 'Quest Gennin', 'Lute e ven', '5', '50', '3200', '220');
INSERT INTO `table_quests` VALUES ('3', 'Quest Chunnin', 'Lute e ven', '20', '150', '22500', '1000');
INSERT INTO `table_quests` VALUES ('4', 'Quest Jounnin', 'Lute e ven', '35', '200', '32500', '1800');
INSERT INTO `table_quests` VALUES ('5', 'Quest Anbu', 'Lute e ven', '45', '300', '52000', '3000');
INSERT INTO `table_quests` VALUES ('6', 'Quest Sannin', 'Lute e ven', '50', '450', '78250', '6200');
INSERT INTO `table_quests` VALUES ('8', 'Quest Taka', 'Lute e ven', '15', '150', '10000', '690');

-- ----------------------------
-- Table structure for `table_selos`
-- ----------------------------
DROP TABLE IF EXISTS `table_selos`;
CREATE TABLE `table_selos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` enum('selos') NOT NULL,
  `vip` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `credshop` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `creditos` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `reqtai` int(11) NOT NULL DEFAULT '0',
  `reqnin` int(11) NOT NULL DEFAULT '0',
  `reqgen` int(11) NOT NULL DEFAULT '0',
  `reqnivel` int(11) NOT NULL DEFAULT '0',
  `valor` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `taijutsu` int(11) NOT NULL,
  `ninjutsu` int(11) NOT NULL,
  `genjutsu` int(11) NOT NULL,
  `vida` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`categoria`,`credshop`),
  KEY `idx_2` (`categoria`,`id`,`reqgen`),
  KEY `idx_3` (`categoria`,`reqgen`,`reqnin`,`reqtai`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_selos
-- ----------------------------
INSERT INTO `table_selos` VALUES ('1', 'selos', 'nao', 'nao', '0', 'Selo Amaldi', 'Selo Maligno capaz de despertar seus poderes mais ocultos.', '0', '0', '0', '10', '20000', 'seloamaldicoado', '50', '50', '50', '15');
INSERT INTO `table_selos` VALUES ('2', 'selos', 'nao', 'nao', '0', 'Selo da Terra', 'None', '0', '0', '0', '17', '30000', 'selodaterra', '75', '75', '75', '15');
INSERT INTO `table_selos` VALUES ('3', 'selos', 'nao', 'nao', '0', 'Selo Hyuuga Nivel 1', 'None', '0', '0', '0', '20', '35000', 'selohyuuga', '80', '80', '80', '14');
INSERT INTO `table_selos` VALUES ('4', 'selos', 'sim', 'nao', '0', 'Selo Hyuuga Nivel 2', 'None', '0', '0', '0', '25', '40000', 'selohyuuga2', '90', '90', '90', '15');
INSERT INTO `table_selos` VALUES ('5', 'selos', 'nao', 'nao', '0', 'Selo Amaldi', 'None', '0', '0', '0', '30', '45000', 'seloamaldicoado3', '100', '100', '100', '14');
INSERT INTO `table_selos` VALUES ('6', 'selos', 'sim', 'nao', '0', 'Selo Amaldi', 'None', '0', '0', '0', '40', '50000', 'seloalmadicoado2', '115', '115', '115', '13');
INSERT INTO `table_selos` VALUES ('7', 'selos', 'nao', 'nao', '0', 'Selo da Terra Avan', 'None', '0', '0', '0', '40', '50000', 'selodaterra2', '115', '110', '112', '14');
INSERT INTO `table_selos` VALUES ('8', 'selos', 'nao', 'nao', '0', 'Shiki Fujiin', 'None', '0', '0', '0', '50', '60000', 'selonaruto', '125', '130', '125', '15');
INSERT INTO `table_selos` VALUES ('9', 'selos', 'sim', 'nao', '0', 'Shiki Fujiin Nivel 2', 'none', '0', '0', '0', '60', '100000', 'selonaruto2', '150', '150', '150', '15');

-- ----------------------------
-- Table structure for `table_tarefas`
-- ----------------------------
DROP TABLE IF EXISTS `table_tarefas`;
CREATE TABLE `table_tarefas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tipo` enum('nivel','vitorias','score') NOT NULL DEFAULT 'nivel',
  `valor` int(11) NOT NULL,
  `premio_yens` int(11) NOT NULL,
  `creditos` int(11) NOT NULL,
  `premio_item` varchar(255) NOT NULL DEFAULT 'nada',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of table_tarefas
-- ----------------------------
INSERT INTO `table_tarefas` VALUES ('1', 'Atingir nivel 5', 'nivel', '4', '5000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('2', 'Atingir nivel 10', 'nivel', '9', '10000', '0', 'Senbons');
INSERT INTO `table_tarefas` VALUES ('3', 'Ter 15 vitorias PVP', 'vitorias', '15', '5000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('4', 'Ter 50 vitorias PVP', 'vitorias', '50', '10000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('5', 'Ter 150 vitorias PVP', 'vitorias', '150', '15000', '0', 'Katana');
INSERT INTO `table_tarefas` VALUES ('6', 'Ter 500 vitorias PVP', 'vitorias', '500', '25000', '2', 'nada');
INSERT INTO `table_tarefas` VALUES ('7', 'Obtenha 1000 vitorias pvp', 'vitorias', '1000', '30000', '5', 'nada');
INSERT INTO `table_tarefas` VALUES ('8', 'Obtenha 10000 vitorias pvp', 'vitorias', '10000', '100000', '10', 'nada');
INSERT INTO `table_tarefas` VALUES ('9', 'Atingir nivel 20', 'nivel', '19', '20000', '3', 'nada');
INSERT INTO `table_tarefas` VALUES ('10', 'Atingir nivel 30', 'nivel', '29', '30000', '0', 'Shukaku');
INSERT INTO `table_tarefas` VALUES ('11', 'Atingir nivel 40', 'nivel', '39', '50000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('12', 'Atingir nivel 50', 'nivel', '49', '100000', '10', 'Botas Akatsuki');
INSERT INTO `table_tarefas` VALUES ('13', 'Complete 50 de Score', 'score', '49', '5000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('14', 'Complete 100 de Score', 'score', '99', '10000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('15', 'Complete 250 de Score', 'score', '249', '18750', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('16', 'Complete 500 de Score', 'score', '499', '20000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('17', 'Complete 1000 de Score', 'score', '999', '15000', '5', 'nada');
INSERT INTO `table_tarefas` VALUES ('18', 'Complete 2500 de Score', 'score', '2499', '30000', '0', 'nada');
INSERT INTO `table_tarefas` VALUES ('19', 'Obtenha 5000 vit?rias pvp', 'vitorias', '4999', '50000', '5', 'nada');
INSERT INTO `table_tarefas` VALUES ('20', 'Obtenha 15000 vit?rias pvp', 'vitorias', '14999', '175000', '10', 'nada');
INSERT INTO `table_tarefas` VALUES ('21', 'Complete 3000 de score', 'score', '2999', '50000', '10', 'nada');
INSERT INTO `table_tarefas` VALUES ('22', 'Complete 5000 de Score', 'score', '4999', '100000', '10', 'Samehada Evoluida');
INSERT INTO `table_tarefas` VALUES ('23', 'Complete 7000 de Score', 'score', '6999', '100000', '1', 'nada');
INSERT INTO `table_tarefas` VALUES ('24', 'Complete 10000 de Score', 'score', '9999', '200000', '10', 'nada');
INSERT INTO `table_tarefas` VALUES ('25', 'Atingir Nivel 60', 'nivel', '59', '100000', '5', 'Kusanagi');
INSERT INTO `table_tarefas` VALUES ('26', 'Atingir Nivel 70', 'nivel', '69', '150000', '2', 'Samehada Evoluida');

-- ----------------------------
-- Table structure for `table_usaveis`
-- ----------------------------
DROP TABLE IF EXISTS `table_usaveis`;
CREATE TABLE `table_usaveis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of table_usaveis
-- ----------------------------
INSERT INTO `table_usaveis` VALUES ('1', 'Pergaminho da Terra', 'Aumenta em 2% a chance de se obter sucesso em um aprimoramento.', 'pergaminho_terra', '300');
INSERT INTO `table_usaveis` VALUES ('2', 'Pergaminho do Ceu', 'Aumenta em 5% a chance de se obter sucesso em um aprimoramento.', 'pergaminho_ceu', '500');
INSERT INTO `table_usaveis` VALUES ('3', 'Pergaminho Sagrado', 'Aumenta em 10% a chance de se obter sucesso em um aprimoramento.', 'pergaminho_sagrado', '1000');

-- ----------------------------
-- Table structure for `tarefas_completadas`
-- ----------------------------
DROP TABLE IF EXISTS `tarefas_completadas`;
CREATE TABLE `tarefas_completadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tarefa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`tarefa_id`,`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tarefas_completadas
-- ----------------------------

-- ----------------------------
-- Table structure for `transfer`
-- ----------------------------
DROP TABLE IF EXISTS `transfer`;
CREATE TABLE `transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `limite` int(11) NOT NULL DEFAULT '50000',
  `transferido` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transfer
-- ----------------------------

-- ----------------------------
-- Table structure for `usaveis`
-- ----------------------------
DROP TABLE IF EXISTS `usaveis`;
CREATE TABLE `usaveis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `status` enum('off','on') NOT NULL DEFAULT 'off',
  PRIMARY KEY (`id`),
  KEY `usuarioid` (`usuarioid`,`itemid`),
  KEY `Otimizacao1` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of usaveis
-- ----------------------------

-- ----------------------------
-- Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('ativo','inativo','banido') NOT NULL DEFAULT 'ativo',
  `usuario` varchar(15) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `personagem` varchar(255) NOT NULL,
  `avatar` int(11) NOT NULL DEFAULT '0',
  `vila` int(11) NOT NULL,
  `reg` datetime NOT NULL,
  `renegado` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `preso` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `vip_inicio` datetime NOT NULL,
  `vip` datetime NOT NULL,
  `alunoid` varchar(15) NOT NULL,
  `senseiid` varchar(15) NOT NULL,
  `orgid` int(11) NOT NULL DEFAULT '0',
  `orgmissao` int(11) NOT NULL DEFAULT '0',
  `nivel` int(11) NOT NULL DEFAULT '1',
  `yens` int(11) NOT NULL DEFAULT '300',
  `yens_fat` int(11) NOT NULL DEFAULT '300',
  `yens_perd` int(11) NOT NULL DEFAULT '0',
  `exp` int(11) NOT NULL DEFAULT '0',
  `expmax` int(11) NOT NULL DEFAULT '5',
  `exptotal` int(11) NOT NULL DEFAULT '0',
  `energia` int(11) NOT NULL DEFAULT '100',
  `energiamax` int(11) NOT NULL DEFAULT '100',
  `taijutsu` int(11) NOT NULL DEFAULT '1',
  `ninjutsu` int(11) NOT NULL DEFAULT '1',
  `genjutsu` int(11) NOT NULL DEFAULT '1',
  `batalhas` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `vitorias` int(11) NOT NULL DEFAULT '0',
  `derrotas` int(11) NOT NULL DEFAULT '0',
  `empates` int(11) NOT NULL DEFAULT '0',
  `hunt_restantes` int(11) NOT NULL DEFAULT '8',
  `hunt` int(11) NOT NULL DEFAULT '0',
  `hunt_fim` datetime NOT NULL,
  `missao` int(11) NOT NULL DEFAULT '0',
  `missao_tempo` int(11) NOT NULL,
  `missao_fim` datetime NOT NULL,
  `quest` int(11) NOT NULL DEFAULT '0',
  `quest_vitorias` int(11) NOT NULL DEFAULT '0',
  `treino` int(11) NOT NULL DEFAULT '0',
  `treino_tempo` int(11) NOT NULL,
  `treino_fim` datetime NOT NULL,
  `penalidade_fim` datetime NOT NULL,
  `doujutsu` int(11) NOT NULL DEFAULT '0',
  `doujutsu_nivel` int(11) NOT NULL DEFAULT '0',
  `doujutsu_exp` int(11) NOT NULL DEFAULT '0',
  `doujutsu_expmax` int(11) NOT NULL DEFAULT '150',
  `natureza1` enum('','fogo','agua','vento','raio','terra') NOT NULL,
  `natureza2` enum('','fogo','agua','vento','raio','terra') NOT NULL,
  `natureza3` enum('','fogo','agua','vento','raio','terra') NOT NULL,
  `config_skin` varchar(255) NOT NULL DEFAULT 'naruto',
  `config_apresentacao` text NOT NULL,
  `config_atualizacoes` enum('sim','nao') NOT NULL DEFAULT 'sim',
  `config_personagem` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `config_avatar` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `config_vila` int(11) NOT NULL DEFAULT '1',
  `config_pergunta` int(11) NOT NULL DEFAULT '0',
  `config_resposta` varchar(255) NOT NULL,
  `config_recuperacao` int(11) NOT NULL DEFAULT '0',
  `pessoal_nome` varchar(100) NOT NULL,
  `pessoal_sexo` enum('','m','f') NOT NULL,
  `pessoal_idade` int(11) NOT NULL,
  `pessoal_pais` varchar(100) NOT NULL,
  `pessoal_uf` varchar(2) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `loginip` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `tipo` enum('player','bot') NOT NULL DEFAULT 'player',
  `pontos` int(11) NOT NULL,
  `tempo` int(11) NOT NULL,
  `tipodeconta` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `creditos` int(11) NOT NULL,
  `creditosusados` int(11) NOT NULL,
  `creditostransferidos` int(11) NOT NULL DEFAULT '0',
  `ativador` int(11) NOT NULL,
  `pontoscla` int(11) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `premiodiario` int(11) NOT NULL,
  `inwar` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `inwar_score` int(11) NOT NULL,
  `caiu` int(11) NOT NULL,
  `torneio` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `torneio_eliminado` int(11) NOT NULL,
  `torneio_score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vila` (`vila`),
  KEY `usuario` (`usuario`),
  KEY `vip` (`vip`),
  KEY `renegado` (`renegado`),
  KEY `ip` (`ip`),
  KEY `orgid` (`orgid`),
  KEY `orgmissao` (`orgmissao`,`nivel`,`yens_fat`,`yens_perd`,`vitorias`,`derrotas`),
  KEY `orgid_2` (`orgid`,`nivel`,`yens_fat`,`yens_perd`,`vitorias`,`derrotas`,`empates`),
  KEY `idx_1` (`tempo`),
  KEY `idx_2` (`status`),
  KEY `idx_3` (`email`),
  KEY `idx_4` (`senha`),
  KEY `idx_5` (`tipo`),
  KEY `Otimizacao1` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of usuarios
-- ----------------------------

-- ----------------------------
-- Table structure for `vendas`
-- ----------------------------
DROP TABLE IF EXISTS `vendas`;
CREATE TABLE `vendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`usuarioid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of vendas
-- ----------------------------

-- ----------------------------
-- Table structure for `vendaspets`
-- ----------------------------
DROP TABLE IF EXISTS `vendaspets`;
CREATE TABLE `vendaspets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioid` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of vendaspets
-- ----------------------------

-- ----------------------------
-- Table structure for `verificador`
-- ----------------------------
DROP TABLE IF EXISTS `verificador`;
CREATE TABLE `verificador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('off','on') NOT NULL DEFAULT 'off',
  `usuarioid` int(11) NOT NULL,
  `hora_missao` datetime NOT NULL,
  `hora_ataque` datetime NOT NULL,
  `inimigoid` int(11) NOT NULL,
  `yens` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_1` (`status`,`usuarioid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of verificador
-- ----------------------------

-- ----------------------------
-- Table structure for `vip`
-- ----------------------------
DROP TABLE IF EXISTS `vip`;
CREATE TABLE `vip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `autenticacao` varchar(255) NOT NULL,
  `usuarioid` int(11) NOT NULL,
  `valor` float NOT NULL,
  `meio` enum('ps','pp') NOT NULL,
  `status` enum('analise','entregue','cancelado') NOT NULL DEFAULT 'analise',
  `obs` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Otimizacao1` (`data`),
  KEY `Otimizacao2` (`usuarioid`),
  KEY `Otimizacao3` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vip
-- ----------------------------
