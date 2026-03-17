-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/11/2024 às 21:22
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `jpii`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cover_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `cover_image`) VALUES
(1, 'Eventos Distritais', 'imagem_2024-10-01_095117857.png'),
(2, 'Acampamentos', 'imagem_2024-10-01_165824769.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `body` varchar(2000) NOT NULL,
  `data` date NOT NULL,
  `userid` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `idcomentario` int(11) NOT NULL,
  `anexos` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) DEFAULT NULL,
  `resposta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `compra` varchar(900) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `data` date DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `created_by`, `created_at`) VALUES
(4, 'Acampamento Ano Novo 2024', 'Última atividade do ano, durando por dois dias.', '2024-12-20', 9, '2024-11-29 19:19:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `categoriaid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `images`
--

INSERT INTO `images` (`id`, `title`, `filename`, `description`, `categoria`, `created_at`, `categoriaid`, `userid`) VALUES
(1, 'Elo 2020', 'imagem_2024-10-01_095350365.png', NULL, 'Eventos Distritais', '2024-10-01 12:59:19', NULL, 9),
(2, 'Ponta de Flecha 2024', 'imagem_2024-10-01_122504675.png', NULL, 'Eventos Distritais', '2024-10-01 15:25:06', NULL, 9),
(3, 'Ponta de Flecha 2024', 'imagem_2024-10-01_122552171.png', NULL, 'Eventos Distritais', '2024-10-01 15:25:53', NULL, 9),
(5, 'Atividade Sobre Religiões', 'imagem_2024-10-01_170059635.png', NULL, 'Eventos Distritais', '2024-10-01 20:01:07', NULL, 9),
(6, 'Treinamento de Ciclismo', 'imagem_2024-10-01_170234160.png', NULL, 'Eventos Distritais', '2024-10-01 20:02:39', NULL, 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tipo` enum('topico','comentario','resposta') NOT NULL,
  `item_id` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `lida` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `descricao` varchar(2000) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `estoque` int(11) DEFAULT NULL,
  `preco` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `quantidade`, `estoque`, `preco`, `foto`, `userid`) VALUES
(4, 'Corda1', '  <div class=\"ui-pdp-container__row ui-pdp-container__row--highlighted-features-title\"> <h2 class=\"ui-vpp-text-alignment--left ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--SEMIBOLD highlighted-features-title\">O que voc&ecirc; precisa saber sobre este produto</h2> </div> <div class=\"ui-pdp-container__row ui-pdp-container__row--highlighted-features\"> <div class=\"ui-vpp-highlighted-specs__features\"> <ul class=\"ui-vpp-highlighted-specs__features-list\"> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Di&acirc;metro: 4 mm</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Comprimento: 10 m</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Sua for&ccedil;a de impacto m&aacute;xima &eacute; de 2kN.</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Recomendado para camping.</li> </ul> </div> </div>', 1, 25, 20, 'pngtree-colorful-rope-roll-on-transparent-background-perfect-for-crafting-or-outdoor-png-image_13138920.png', 9),
(5, 'Lanterna Recarregável 80-100', '<p>Quantidade de LEDs da lanterna: 2 LEDS: 1 LED alto brilho frontal / 1 LED COB Lateral | Fluxo luminoso (l&uacute;mens): LED frontal??80 lm / LED lateral??100 lm | Bateria: 3,7 V - 1,2 Ah - &Iacute;ons de l&iacute;tio | Entrada de carregamento: Mini USB 5 V DC | Cor da lanterna: Preta/amarela</p>  <p>Ref.: VONDER-8075080100</p>  <p>Marca.: VONDER *Imagens meramente ilustrativas</p>  <p>*Todas as informa&ccedil;&otilde;es divulgadas s&atilde;o de responsabilidade do Fabricante/Fornecedor</p>', 1, 30, 18, 'ebd4df22ea65915e6f4941d1b3056fc4.png', 9),
(6, 'Nautika barraca de camping panda iglu para 2 pessoas', '<div class=\"ui-pdp-container__row ui-pdp-container__row--highlighted-features-title\"> <div class=\"ui-pdp-container__row ui-pdp-container__row--highlighted-features-title\"> <h2 class=\"ui-vpp-text-alignment--left ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--SEMIBOLD highlighted-features-title\">O que voc&ecirc; precisa saber sobre este produto</h2> </div> <div class=\"ui-pdp-container__row ui-pdp-container__row--highlighted-features\"> <div class=\"ui-vpp-highlighted-specs__features\"> <ul class=\"ui-vpp-highlighted-specs__features-list\"> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Pr&aacute;tica e confort&aacute;vel para 2 pessoas</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Piso de polietileno de alta resist&ecirc;ncia e antifungos</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Acompanha estacas, cordas e varetas para montagem</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Inclui sacola para transporte</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Bolso interno para pequenos objetos.</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Fabricada em Poli&eacute;ster com acr&iacute;lico</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Estrutura composta por varetas de fibra de vidro interligadas por el&aacute;sticos internos com material 100% virgem</li> <li class=\"ui-vpp-highlighted-specs__features-list-item ui-pdp-color--BLACK ui-pdp-size--XSMALL ui-pdp-family--REGULAR\">Com sistema NANO-FLEX que proporciona uma montagem f&aacute;cil ', 1, 5, 100, '2cacdf6637682e3a9284299fabde4071.png', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `comentario_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `resposta` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(400) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `saldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id`, `username`, `nome`, `email`, `senha`, `descricao`, `tipo`, `status`, `foto`, `saldo`) VALUES
(9, '', 'admin', 'admin', 'admin', '', '3', '0', '5bbba0ab05b5cd44bc64fcb2292ba179.png', 0),
(11, '', 'Henrique Oliveira', 'henrique.oliveira17@escoteiromail.com', '12345678', 'Comecei no escotismo aos 8 anos e me especializei em orientação e técnicas de sobrevivência. O escotismo me ensinou resiliência e trabalho em equipe. Hoje, compartilho minhas experiências com outros jovens.', '1', '0', 'a5d32e0a7273b3f6441a7a1a843a1140.png', 70),
(12, '', 'Beatriz Almeida', ' beatriz.almeida30@escoteiros.org', '12345678', 'Sou líder escoteira e educadora. Meu foco é ajudar jovens a desenvolverem liderança e trabalho em equipe, acreditando no impacto do escotismo na formação de cidadãos responsáveis.', '2', '0', '760b8a5fc2aec4eff3b48ff971db6a7d.png', 0),
(13, '', 'José Martins', ' jose.martins45@escoteirosempre.com', '12345678', 'Escoteiro desde a infância, com 30 anos de experiência. Hoje, me dedico a formar novos líderes e preservar os princípios do escotismo, como respeito e serviço à comunidade.', '2', '0', 'b28cd777169406e0a8f9642f524c4499.png', 0),
(14, '', 'Cláudia Ferreira', ' claudia.ferreira25@escotismo.org', '12345678', '', '0', '0', 'd1a6ba24b5b37fb113ac0443308da3df.png', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `topico`
--

CREATE TABLE `topico` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `body` varchar(2000) NOT NULL,
  `data` date NOT NULL,
  `anexos` varchar(500) NOT NULL,
  `userid` int(11) NOT NULL,
  `restrito` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `topico`
--

INSERT INTO `topico` (`id`, `nome`, `body`, `data`, `anexos`, `userid`, `restrito`) VALUES
(57, 'Dúvida sobre o escotismo', 'Estou interessada em me envolver no movimento escoteiro, mas antes de tomar uma decisão final, gostaria de esclarecer algumas dúvidas sobre como o escotismo lida com o equilíbrio entre tradição e modernidade.\r\n\r\nTenho lido bastante sobre os princípios fundamentais do movimento, como o respeito à natureza, o serviço à comunidade e o desenvolvimento pessoal. No entanto, fico com algumas questões sobre como esses valores se aplicam em um mundo cada vez mais digital. Por exemplo, em atividades ao ar livre, como acampamentos e excursões, até que ponto o uso de tecnologias como GPS, smartphones ou outras ferramentas digitais é aceito ou incentivado, sem comprometer a essência do escotismo?\r\n\r\nGostaria de saber como o movimento lida com a integração de novos métodos e recursos, e se existem restrições sobre o uso de tecnologia nas atividades práticas. Também estou curioso sobre como o escotismo pode me ajudar a desenvolver habilidades de liderança, trabalho em equipe e responsabilidade, além de contribuir para minha formação pessoal.\r\n\r\nAgradeço desde já pela atenção e pelas respostas.', '2024-11-29', '', 14, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `useridcoment` (`userid`),
  ADD KEY `fk_post` (`post_id`),
  ADD KEY `resposta_id` (`resposta_id`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compras_usuario` (`userid`);

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk` (`created_by`);

--
-- Índices de tabela `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_images_categoria` (`categoriaid`),
  ADD KEY `fk_images_usuario` (`userid`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produtos_usuario` (`userid`);

--
-- Índices de tabela `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comentario_id` (`comentario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `topico`
--
ALTER TABLE `topico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `topico`
--
ALTER TABLE `topico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`resposta_id`) REFERENCES `comentarios` (`id`),
  ADD CONSTRAINT `fk_post` FOREIGN KEY (`post_id`) REFERENCES `topico` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useridcoment` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compras_usuario` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk` FOREIGN KEY (`created_by`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_categoria` FOREIGN KEY (`categoriaid`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_images_usuario` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_usuario` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`),
  ADD CONSTRAINT `respostas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tbl_usuarios` (`id`);

--
-- Restrições para tabelas `topico`
--
ALTER TABLE `topico`
  ADD CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `tbl_usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
