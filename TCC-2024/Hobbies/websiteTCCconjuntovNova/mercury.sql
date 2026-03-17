-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/10/2024 às 01:10
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mercury`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `hobbies`
--

CREATE TABLE `hobbies` (
  `id` int(20) UNSIGNED NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT NULL CHECK (`status` in ('executados','em andamento','á fazer')),
  `descricao` text DEFAULT NULL,
  `sentimento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `hobbies`
--

INSERT INTO `hobbies` (`id`, `id_usuarios`, `nome`, `status`, `descricao`, `sentimento`) VALUES
(1, 1, 'jogar bola', 'em andamento', 'fdshh', NULL),
(5, 3, 'correr', 'a fazer', 'kkkk', ''),
(6, 3, 'correr', 'a fazer', 'kkkk', ''),
(7, 3, 'correr', 'a fazer', 'kkkk', ''),
(8, 3, 'caminhar', 'em andamento', '50km', ''),
(9, 6, 'jogar', 'executados', 'videogame', 'feliz'),
(10, 6, 'wsdqD', 'executados', 'ASEFDwaesf', 'feliz'),
(11, 6, 'wsd', 'executados', 'qawsd', 'meh'),
(13, 4, 'Alexandre Drumond de Paula', 'executados', 'vfgbdzf', 'triste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `foto_post` text NOT NULL,
  `data_postagem` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `foto_perfil` text NOT NULL,
  `tipo` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `foto_perfil`, `tipo`, `status`) VALUES
(1, 'clara', 'clara.viana@gmail.com', 'senha123', '', 1, 1),
(3, 'julia', 'julia.viana@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f7110eda4d09e062aa5e4a390b0a572ac0d2c0220d0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 1),
(4, 'g', 'gabi@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '', 1, 1),
(6, 'gustavo', 'guto@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f5f6955d227a320c7f1f6c7da2a6d96a851a8118fd0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 0),
(7, 'gi', 'gi@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '', 1, 1),
(8, 'Lelau', 'lelau@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f5f6955d227a320c7f1f6c7da2a6d96a851a8118fd0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 1),
(9, 'Joyce', 'joyce@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 0),
(10, 'Joyce', 'joyce.confessone@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 0),
(11, 'Karen', 'karen@gmail', '4326999ffff76d4bae86b2b214f5f420bad0fe7f618dcdfb0cd9ae4481164961c4796dd8e3930c8dd0f59b242071be12249e63d2e6a858bcdcad1187', '', 0, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `posts_d` (`posts_id`);

--
-- Índices de tabela `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `administradores_ibfk_2` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`);

--
-- Restrições para tabelas `hobbies`
--
ALTER TABLE `hobbies`
  ADD CONSTRAINT `hobbies_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
