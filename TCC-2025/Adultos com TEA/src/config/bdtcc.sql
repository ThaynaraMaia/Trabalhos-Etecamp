-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 13/08/2025 às 06:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdtcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('Adulto com TEA','Administrador') NOT NULL,
  `foto` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `nome`, `senha`, `tipo`) VALUES
(1, 'demiurgo@gmail.com', 'abuble', '$2y$10$bOOgYtghySysIW2bnwLkJeNNqB1OPz6FZu/cUsjd/e0gDXXBOXKZW', 'Adulto com TEA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conquistas`
--

CREATE TABLE IF NOT EXISTS `conquistas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `usuario_id` INTEGER NOT NULL,
  `nome` TEXT NOT NULL,
  `descricao` TEXT,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `usuario_id` INTEGER NOT NULL,
  `data` TEXT NOT NULL,
  `descricao` TEXT NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais`
--

CREATE TABLE IF NOT EXISTS `materiais` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `tipo` TEXT DEFAULT 'video',
  `emocao` TEXT NOT NULL,
  `titulo` TEXT NOT NULL,
  `descricao` TEXT,
  `url` TEXT,
  `icone` TEXT DEFAULT '🎬',
  `texto` TEXT,
  `fontes` TEXT,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais_acessados`
--

CREATE TABLE IF NOT EXISTS `materiais_acessados` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `usuario_id` INTEGER NOT NULL,
  `material_id` INTEGER NOT NULL,
  `acessado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (material_id) REFERENCES materiais(id) ON DELETE CASCADE,
  UNIQUE(usuario_id, material_id)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts` (Fórum)
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER NOT NULL,
  `autor` TEXT NOT NULL,
  `conteudo` TEXT NOT NULL,
  `tipo_post` TEXT DEFAULT 'admin-only',
  `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas` (Respostas do Fórum)
--

CREATE TABLE IF NOT EXISTS `respostas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `post_id` INTEGER NOT NULL,
  `user_id` INTEGER NOT NULL,
  `autor` TEXT NOT NULL,
  `conteudo` TEXT NOT NULL,
  `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversas_privadas`
--

CREATE TABLE IF NOT EXISTS `conversas_privadas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `usuario_id` INTEGER NOT NULL,
  `admin_id` INTEGER,
  `criada_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (admin_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_privadas`
--

CREATE TABLE IF NOT EXISTS `mensagens_privadas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `conversa_id` INTEGER NOT NULL,
  `remetente_id` INTEGER NOT NULL,
  `conteudo` TEXT NOT NULL,
  `lida` BOOLEAN DEFAULT 0,
  `enviada_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (conversa_id) REFERENCES conversas_privadas(id) ON DELETE CASCADE,
  FOREIGN KEY (remetente_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para melhor performance
--
CREATE INDEX IF NOT EXISTS idx_posts_user ON posts(user_id);
CREATE INDEX IF NOT EXISTS idx_posts_tipo ON posts(tipo_post);
CREATE INDEX IF NOT EXISTS idx_respostas_post ON respostas(post_id);
CREATE INDEX IF NOT EXISTS idx_respostas_user ON respostas(user_id);
CREATE INDEX IF NOT EXISTS idx_conversas_usuario ON conversas_privadas(usuario_id);
CREATE INDEX IF NOT EXISTS idx_conversas_admin ON conversas_privadas(admin_id);
CREATE INDEX IF NOT EXISTS idx_mensagens_conversa ON mensagens_privadas(conversa_id);
CREATE INDEX IF NOT EXISTS idx_mensagens_lida ON mensagens_privadas(lida);

-- --------------------------------------------------------

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;