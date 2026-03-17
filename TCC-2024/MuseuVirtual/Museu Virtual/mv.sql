-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Nov-2024 às 13:11
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mv`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Pintura'),
(6, 'Fotografia'),
(7, 'Desenho'),
(8, 'Poema/Poesia'),
(9, 'Colagens'),
(10, 'Grafite'),
(12, 'Vídeo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `obra_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curtidas`
--

INSERT INTO `curtidas` (`id`, `usuario_id`, `obra_id`) VALUES
(24, 2, 11),
(25, 2, 15),
(9, 17, 11),
(11, 17, 14),
(8, 17, 16);

-- --------------------------------------------------------

--
-- Estrutura da tabela `obras`
--

CREATE TABLE `obras` (
  `id` int(11) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `categoria` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `trabalho_artistico` varchar(200) NOT NULL,
  `autor` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `curtidas` int(11) NOT NULL,
  `data` date NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `obras`
--

INSERT INTO `obras` (`id`, `titulo`, `categoria`, `descricao`, `trabalho_artistico`, `autor`, `status`, `curtidas`, `data`, `texto`) VALUES
(11, 'Artes Livres', 9, 'Um quadro com diversas colagens bem aleatórias', '66f14dd85ca5f-artesLivres.png', 17, 1, 4, '2024-09-23', ''),
(14, 'Grafite Botuja', 10, 'Uma pessoa e um trem grafitados na parede da biblioteca', '66f14f7c1bc90-grafite.png', 17, 1, 2, '2024-09-23', ''),
(15, 'Quadro 1AI', 1, 'Várias pinturas diferentes em um único quadro', '66f1501ed8e2a-pintura.png', 17, 1, 1, '2024-09-23', ''),
(16, 'Quadros escuros', 1, 'Um quadro tem uma frase e outro com um lobo pintado no chão', '66f1508cf1b45-quadros.png', 17, 1, 1, '2024-09-23', ''),
(43, 'Retrato', 8, 'Poema de Cecília Meireles', '66ff0cbd5e18f-Retrato.jpg', 17, 1, 0, '2024-10-03', 'Eu não tinha este rosto de hoje,\r\nAssim calmo, assim triste, assim magro,\r\nNem estes olhos tão vazios,\r\nNem o lábio amargo.\r\n\r\nEu não tinha estas mãos sem força,\r\nTão paradas e frias e mortas;\r\nEu não tinha este coração\r\nQue nem se mostra.\r\n\r\nEu não dei por esta mudança,\r\nTão simples, tão certa, tão fácil:\r\n— Em que espelho ficou perdida\r\na minha face?'),
(52, 'Festival de artes', 1, 'Pinturas expostas durante o Festival de Artes da ETECAMP', '671569a3807f9-Festival de artes 2.jpeg', 17, 1, 0, '2024-10-20', ''),
(54, 'Festival de artes 3', 1, 'Pinturas realizadas pelos alunos da ETEC', '67156a839caa5-Festival de artes 3.jpeg', 17, 1, 0, '2024-10-20', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `tipo` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `status`, `foto`) VALUES
(1, 'Administrador de Sistema', 'admin@email.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7ff865b53623b121fd34ee5426c792e5c33af8c227d0f59b242071be12249e63d2e6a858bcdcad1187', 1, 1, '66d5d3927d491-123.jpg'),
(2, 'Thamires', 'thamires@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', 0, 1, '2oculos.jpg'),
(3, 'Clara', 'clara@teste.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', 0, 1, 'oculosClara.jpg'),
(4, 'Gabriel', 'gabriel@teste.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', 0, 0, '3oculos.jpg'),
(17, 'Aluno da Etecamp', 'aluno@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f93ba1608fc10b710894fb9f8c89724c6eeb44d11d0f59b242071be12249e63d2e6a858bcdcad1187', 0, 1, '66f14a751da03-p1.jpg'),
(24, 'Teste', 'seila@etec.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', 0, 1, '6715a85f64502-border_collie-p.jpg');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`obra_id`),
  ADD KEY `obra_id` (`obra_id`);

--
-- Índices para tabela `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor` (`autor`),
  ADD KEY `categoria` (`categoria`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `obras_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `obras_ibfk_2` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
