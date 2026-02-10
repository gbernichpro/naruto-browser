-- Basic Schema
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `yens` int(11) DEFAULT 0,
  `yensbanco` int(11) DEFAULT 0,
  `energia` int(11) DEFAULT 100,
  `energiamax` int(11) DEFAULT 100,
  `status` varchar(20) DEFAULT 'ativo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
