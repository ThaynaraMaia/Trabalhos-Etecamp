-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/11/2024 às 21:25
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
-- Banco de dados: `banco_jundtask`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adm`
--

CREATE TABLE `adm` (
  `id_admin` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `foto_perfil` text NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `contato` int(11) NOT NULL,
  `data_nasc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adm`
--

INSERT INTO `adm` (`id_admin`, `nome`, `email`, `senha`, `foto_perfil`, `tipo`, `contato`, `data_nasc`) VALUES
(2, 'Giovana Neris', 'Admin@gmail.com', '$2y$10$r0TlgDt5X1y/jtlRdDywn.7XMYQvMu93/MPTOM2pyjMGYS5r2DCXG', '../uploads/download.jfif', 'A', 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `area_atuação`
--

CREATE TABLE `area_atuação` (
  `id_area` int(11) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `area_atuação`
--

INSERT INTO `area_atuação` (`id_area`, `cidade`, `id_categoria`) VALUES
(1, 'Cabreuva', NULL),
(2, 'Campo Limpo Paulista', NULL),
(3, 'Itupeva', NULL),
(4, 'Jarinu', NULL),
(5, 'Jundiai', NULL),
(6, 'Limeira', NULL),
(7, 'Varzea Paulista', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `atualizacoes_pendentes`
--

CREATE TABLE `atualizacoes_pendentes` (
  `id_atualizacoes_pendentes` int(11) NOT NULL,
  `id_trabalhador` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `foto_perfil` text DEFAULT NULL,
  `foto_trabalho1` text DEFAULT NULL,
  `foto_trabalho2` text DEFAULT NULL,
  `foto_trabalho3` text DEFAULT NULL,
  `foto_banner` text DEFAULT NULL,
  `aprovado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atualizacoes_pendentes`
--

INSERT INTO `atualizacoes_pendentes` (`id_atualizacoes_pendentes`, `id_trabalhador`, `nome`, `email`, `senha`, `contato`, `data_nasc`, `descricao`, `id_area`, `id_categoria`, `foto_perfil`, `foto_trabalho1`, `foto_trabalho2`, `foto_trabalho3`, `foto_banner`, `aprovado`) VALUES
(31, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobre vokjanskdnanddpPDCOcê...', NULL, NULL, 'foto de perfil.png', 'pub3.jpg', 'TelaPredefinida.png', 'TelaPredefinidaTrabalhos3.png', 'foto do banner.png', -1),
(32, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobre vokjanskdnanddpPDCOcê...', NULL, NULL, 'foto de perfil.png', 'pub3.jpg', 'TelaPredefinida.png', 'TelaPredefinidaTrabalhos3.png', 'foto do banner.png', 1),
(33, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobre vokjanskdnanddpPDCOcê...', NULL, NULL, 'foto de perfil.png', 'pub3.jpg', 'TelaPredefinida.png', 'TelaPredefinidaTrabalhos3.png', 'foto do banner.png', 1),
(34, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobrhgvkvvouyvouyvuoe você...', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(35, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobrhgvkvvouyvouyvuoe você...', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(36, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', '', NULL, NULL, 'foto de perfil.png', NULL, NULL, NULL, NULL, 1),
(37, 61, 'Paola Carosella', 'paola@gmail.com', NULL, '11140028922', '1982-08-26', 'Fale sobre você...juansanciasnckjsnjkcnakcnbkjabskj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, -1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nome_cat` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome_cat`, `imagem`) VALUES
(1, 'Serviços Doméstico', 'servico-de-limpeza.png'),
(2, 'Reparos e Manutenção', 'repair.png'),
(3, 'Serviços Tecnologicos', 'data-management.png'),
(4, 'Restaurante', 'restaurante.png'),
(5, 'Confeitaria', 'bolo.png'),
(6, 'Serviços para Eventos e Festas', 'festa-de-aniversario.png'),
(7, 'Saude e Beleza', 'secador-de-cabelo.png'),
(8, 'Assesoria Judicial', 'judicial.png'),
(9, 'Educação e Aulas Particulares', 'educacao.png'),
(10, 'Serviços Automotivos', 'servico-automotivo.png'),
(11, 'Artesanato', 'artesanato.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `foto_perfil` text NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `id_area` int(11) NOT NULL,
  `contato` varchar(11) NOT NULL,
  `data_nasc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome`, `email`, `senha`, `foto_perfil`, `tipo`, `status`, `id_area`, `contato`, `data_nasc`) VALUES
(15, 'Maria', 'Maria12@gmail.com', '$2y$10$IQzkVoUhJjeZ1vZP6t5bp.KXuMji2zskEdJB1njGfU5FVIw1xS6jC', '../uploads/download.jfif', '', '', 7, '12121212121', '1981-05-13'),
(16, 'Giovana Neris', 'giovananeris942@gmail.com', '$2y$10$iVzEfkTCsahEhOAkk4NqHeVrjCnJLNtDAL8YvaRdyKeM02PfUNI5u', '../uploads/download.jfif', '', '', 6, '12121212121', '2006-08-28'),
(17, 'louco mto louco', 'louco3@gmail.com', '$2y$10$XohQicz/RGpGpda2VBqBgO/aeMpLNTQ/XV2mS5nFSVL3s1RfmKWCq', '../uploads/images.jpg', '', '', 1, '11111111111', '2000-12-12'),
(18, 'luana', 'luana@gmail.com', '$2y$10$fyCrOn/ZohSj70IjzjpGoe2exkGfqJ/LP.FCAH0LtySgkJ.VxTiKK', '../uploads/fotoPerfilGeral.png', '', '', 2, '11111111111', '2000-08-28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_trabalhador` int(11) DEFAULT NULL,
  `id_trabalhador_sessao` int(11) DEFAULT NULL,
  `comentario` text NOT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `id_cliente`, `id_trabalhador`, `id_trabalhador_sessao`, `comentario`, `data_comentario`) VALUES
(18, NULL, 20, 22, 'pinto\r\n', '2024-09-25 13:41:21'),
(19, NULL, 20, 22, 'pinto\r\ncu\r\n', '2024-09-25 13:42:10'),
(20, NULL, 20, 22, 'safado', '2024-09-25 13:42:54'),
(21, NULL, 20, 22, 'loucio', '2024-09-25 13:44:15'),
(22, NULL, 20, 17, 'loucooooo', '2024-09-25 13:45:29'),
(23, 16, NULL, NULL, 'adasd', '2024-09-25 13:54:59'),
(24, 16, NULL, NULL, 'adasd', '2024-09-25 13:55:54'),
(25, 16, NULL, NULL, 'olaaa mundo', '2024-09-25 13:56:47'),
(26, 16, NULL, NULL, 'ijkj', '2024-09-25 13:57:51'),
(27, 16, NULL, NULL, 'dsad', '2024-09-25 14:00:30'),
(28, 16, NULL, NULL, 'fsdfsdf', '2024-09-25 14:01:52'),
(29, 16, NULL, NULL, 'pp', '2024-09-25 14:05:55'),
(30, 16, NULL, NULL, 'porra', '2024-09-25 14:10:44'),
(31, 16, NULL, NULL, 'dsd', '2024-09-25 14:13:57'),
(32, 16, NULL, NULL, 'dsdgfhf', '2024-09-25 14:15:12'),
(33, 16, NULL, NULL, 'dsfs', '2024-09-25 14:17:08'),
(34, 16, NULL, NULL, 'asda', '2024-09-25 14:17:26'),
(35, 16, 20, NULL, 'fsdfs', '2024-09-25 14:20:12'),
(36, 16, 20, NULL, 'louco', '2024-09-25 14:30:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversas`
--

CREATE TABLE `conversas` (
  `id_conversa` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_inicio` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id_curtida` int(11) NOT NULL,
  `data_curtida` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`id_curtida`, `data_curtida`, `id_cliente`, `id_trabalhador`) VALUES
(9, 0, 17, 24),
(10, 0, 17, 43),
(11, 0, 16, 23),
(13, 0, 16, 27),
(14, 0, 18, 23),
(15, 0, 18, 24),
(16, 0, 18, 27),
(18, 0, 16, 43),
(19, 0, 16, 50),
(20, 0, 16, 24);

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`id_favorito`, `id_trabalhador`, `id_cliente`) VALUES
(7, 24, 17),
(8, 27, 16),
(11, 50, 16),
(12, 24, 16);

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id_mensagem` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_trabalhador` int(11) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `remetente` enum('cliente','trabalhador') DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id_mensagem`, `id_cliente`, `id_trabalhador`, `mensagem`, `remetente`, `data_envio`) VALUES
(1, 16, 24, 'oi', 'cliente', '2024-10-21 13:11:24'),
(2, 16, 24, 'oi', 'cliente', '2024-10-21 13:11:52'),
(3, 16, 24, 'oi', 'cliente', '2024-10-21 13:12:04'),
(4, 16, 24, 'oi', 'cliente', '2024-10-21 13:14:00'),
(5, 16, 24, 'oi\r\n', 'cliente', '2024-10-21 13:26:00'),
(6, 16, 24, 'oi, gostaria de fazer um orçamento', 'cliente', '2024-10-21 13:27:46'),
(7, 16, 24, 'ola meu orçamento é de 100 reais a diaria', 'trabalhador', '2024-10-21 13:41:20'),
(8, 16, 24, 'para quais dias vc deseja?\r\n', 'trabalhador', '2024-10-21 13:48:06'),
(9, 16, 24, 'oi\r\n', 'cliente', '2024-10-21 14:53:34'),
(10, 16, 24, 'oi\r\n', 'cliente', '2024-10-21 14:55:30'),
(11, 16, 24, 'oi', 'cliente', '2024-10-21 14:55:59'),
(12, 16, 24, 'li\r\n', 'cliente', '2024-10-21 14:58:50'),
(13, 16, 24, 'oi quaanto custa seu serviço?', 'cliente', '2024-10-22 18:55:24'),
(14, 16, 24, 'custa 100 reaias a limpeza\r\n', 'trabalhador', '2024-10-22 18:56:24'),
(15, 16, 50, 'Olá ricardo, fiquei sabendo que vc faz encomenda para festa?', 'cliente', '2024-10-22 20:31:01'),
(16, 16, 24, 'oi', 'cliente', '2024-11-05 23:51:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reclamacao_cliente`
--

CREATE TABLE `reclamacao_cliente` (
  `id_reclamacao_cliente` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reclamacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reclamacao_cliente`
--

INSERT INTO `reclamacao_cliente` (`id_reclamacao_cliente`, `id_cliente`, `nome`, `email`, `reclamacao`) VALUES
(1, 17, 'michele gomes', 'gui@gmail.com', 'oi');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reclamacao_trabalhador`
--

CREATE TABLE `reclamacao_trabalhador` (
  `id_reclamacao_trabalhador` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reclamacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reclamacao_trabalhador`
--

INSERT INTO `reclamacao_trabalhador` (`id_reclamacao_trabalhador`, `id_trabalhador`, `nome`, `email`, `reclamacao`) VALUES
(2, 17, 'michele gomes', 'gui@gmail.com', 'teste2'),
(3, 17, 'michele gomes', 'gui@gmail.com', 'teste2'),
(4, 17, 'michele gomes', 'gui@gmail.com', 'teste'),
(5, 17, 'michele gomes', 'gui@gmail.com', 'teste'),
(6, 31, 'Juliana Ribeiro', 'juliana.ribeiro@limpeza.com', 'amei o site seus gostosos');

-- --------------------------------------------------------

--
-- Estrutura para tabela `trabalhador`
--

CREATE TABLE `trabalhador` (
  `id_trabalhador` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `foto_perfil` text NOT NULL,
  `foto_trabalho1` text NOT NULL,
  `foto_trabalho2` text NOT NULL,
  `foto_trabalho3` text NOT NULL,
  `foto_banner` text NOT NULL,
  `descricao` text NOT NULL,
  `contato` varchar(255) NOT NULL,
  `data_nasc` date NOT NULL,
  `media_avaliacao` decimal(3,2) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `permissao` int(4) NOT NULL,
  `curtidas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `trabalhador`
--

INSERT INTO `trabalhador` (`id_trabalhador`, `nome`, `email`, `senha`, `foto_perfil`, `foto_trabalho1`, `foto_trabalho2`, `foto_trabalho3`, `foto_banner`, `descricao`, `contato`, `data_nasc`, `media_avaliacao`, `tipo`, `status`, `id_categoria`, `id_area`, `permissao`, `curtidas`) VALUES
(17, 'michele gomes', 'gui@gmail.com', '$2y$10$2GLOHF./f4ZZYWAFFRVt3OmUyBHSs5ZGrwcblOAY2PXOmwHTniWFy', '../uploads/download.jfif', '../uploads/fundo1.jpg', '../uploads/fundo2.jpg', '../uploads/testeFundo.jpeg', '../uploads/fundoPerfil.png', 'sou pobre', '12121212121', '2002-10-16', 0.00, '', '', 10, 2, 0, 0),
(18, 'Maria', 'ma@gmail.com', '$2y$10$RRoK..jGqYKCBkwinAvE3eR9jnxdLJ6zL1ZGH47MniZ8KvrWD0c3a', '../uploads/download.jfif', '', '', '', '', '', '12121212121', '1923-07-05', 0.00, '', '', 10, 5, 0, 0),
(20, 'memphis', 'depay@corinthians.com', '$2y$10$z3vcOAAGGkHLMfBdjHNx4Oh5smLczVUbyNhFsSPicSN.4NhuuWHrm', '../uploads/images.jfif', '', '', '', '', '', '11991829034', '1910-09-01', 0.00, '', '', 8, 4, 0, 0),
(22, 'Pato Rog', 'patorogerio@gmail.com', '$2y$10$il6HsiBFAuVs.I3/OnGofu1YV6H8ywMYaSyqAWlwmw61de5zHw/H2', '../uploads/howardtheduck2.jpg', '', '', '', '', '', '11111111111', '1034-12-12', 0.00, '', '', 8, 4, 0, 0),
(23, 'Maria da Silva', 'maria.silva@limpeza.com', '$2y$10$1uzoRY652Bq4w1DCMq1DLeM97R86MtQjHYApDpsYZtIPOHAsqQyiG', 'maria silva.png', 'faxina7.png', 'faxina4.png', 'faxina6.png', 'banner2.png', 'Eu sou a Maria da Silva, uma profissional com mais de 10 anos de experiência em serviços de limpeza. Minha abordagem é sempre focada na satisfação do cliente. Eu realizo uma limpeza profunda em cada cômodo da casa, cuidando dos detalhes que muitas vezes passam despercebidos, como cantos, rodapés e atrás dos móveis.', '11987654321', '1000-08-28', 0.00, '', '', 1, 1, 0, 0),
(24, 'João Pereira', 'joao.pereira@limpeza.com', '$2y$10$C/RIZ3EasSolB48xanoU3.hafYo4nJzveNpXFlfFSWjLt7uKbsioa', 'joao .png', 'faxina5.png', 'faxina2.png', 'faxina8.png', 'banner3.png', 'Realizo serviços de limpeza em casas e apartamentos, sempre focando na organização e na manutenção da limpeza com regularidade. Tenho mais de 5 anos de experiência e ofereço pacotes flexíveis para atender a cada cliente.', '11986543210', '1000-08-28', 0.00, '', '', 1, 1, 0, 0),
(25, 'Renata Oliveira', 'renata.oliveira@limpeza.com', '$2y$10$whlrUXPQ3aGYdCsg6uuCFOEJw4ScSvLYHHqm/B3uH0kxkKVUWFTIq', 'renata.png', 'faxina4.png', 'faxina7.png', 'faxina1.png', 'banner4.png', 'Atuo na limpeza de residências e pequenos escritórios em Cabreuva, com foco na desinfecção de áreas de alto tráfego e limpeza detalhada de cozinhas e banheiros.', '11987651234', '1000-08-28', 0.00, '', '', 1, 1, 0, 0),
(26, 'Paulo Fernandes', 'paulo.fernandes@limpeza.com', '$2y$10$IvVlhNhI5a6UZl..NtQUI.CqNIE/lMlB5.M/PtewwcOyO4cA2WHli', 'paulo.png', 'faxina6.png', 'faxina1.png', 'faxina5.png', 'banner6.png', 'Com mais de 8 anos de experiência, ofereço serviços de limpeza de alta qualidade. Faço desde a limpeza básica até a detalhada, com foco em áreas de difícil acesso.', '11987654322', '1000-08-28', 0.00, '', '', 1, 1, 0, 0),
(27, 'Laura Martins', 'laura.martins@limpeza.com', '$2y$10$wTZ./O0vvKVi2c9wKrGDxuvEikFlWzIVbCPBtFtQTmjBcdOFSzKha', 'laura.png', 'faxina7.png', 'faxina4.png', 'faxina2.png', 'banner5.png', 'Sou especializada na organização e limpeza de casas e escritórios. Trabalho com produtos ecológicos e garantias de ambientes livres de sujeira.', '11998765432', '1000-01-28', 0.00, '', '', 1, 2, 0, 0),
(28, 'Rafael Mendes', 'rafael.mendes@limpeza.com', '$2y$10$rSUnYc.y83quCJgZGpAtGOavlATpgH/JVrzH8EFG54ExC7HkoAqB.', 'rafael.png', 'faxina3.png', 'faxina8.png', 'faxina1.png', 'banner6.png', 'Faço a limpeza regular de escritórios e residências, com atenção especial à organização e ao uso de produtos de limpeza seguros para pets e crianças.', '11986754213', '1000-03-28', 0.00, '', '', 1, 2, 0, 0),
(29, 'Ana Costa', 'ana.costa@limpeza.com', '$2y$10$5oO4jiE9tLkVPlC7S4tIE.b0tfUE78aBCyoQZzs.43xFXzq.Zv4Ru', 'ana.png', 'faxina1.png', 'faxina2.png', 'faxina3.png', 'banner6.png', 'Atuo na limpeza de residências e empresas, com serviços que incluem limpeza de estofados, lavagem de vidros e organização de ambientes.', '11985432109', '1000-09-28', 0.00, '', '', 1, 3, 0, 0),
(30, 'Carlos Souza', 'carlos.souza@limpeza.com', '$2y$10$eRvrGw6hXLfg3fOS2Un1g.PXV0UFv95DC4BdorbkQ7YKk8/zuzGPG', 'carlos.png', 'faxina4.png', 'faxina5.png', 'faxina6.png', 'banner1.png', 'Realizo limpeza detalhada de casas, apartamentos e empresas em Itupeva. Faço questão de deixar todos os espaços organizados e limpos, do chão ao teto.', '11982347654', '1000-11-28', 0.00, '', '', 1, 3, 0, 0),
(31, 'Juliana Ribeiro', 'juliana.ribeiro@limpeza.com', '$2y$10$x1p0iPNLWVtcWm3lnOKPwui7YyxMhNm52xDd5ph0o.n1zricXQ1HO', 'juliana.png', 'faxina7.png', 'faxina8.png', 'faxina2.png', 'banner5.png', 'Especialista em limpezas pós-obra, removo todo o resíduo e deixo o ambiente pronto para uso imediato. Atendo em Itupeva e região.', '11987653421', '2000-09-12', 0.00, '', '', 1, 3, 0, 0),
(32, 'Fernanda Alves', 'fernanda.alves@limpeza.com', '$2y$10$VmE9U5JXgto4shAL7IcQGur7JwxziveSqzLP3S0UljQJmF8Qrel.i', 'fernanda.png', 'faxina2.png', 'faxina7.png', 'faxina5.png', 'banner4.png', ' Ofereço serviços de limpeza profunda e organização de ambientes, garantindo uma rotina de manutenção para quem quer manter a casa sempre em ordem.', '11983456278', '1000-11-12', 0.00, '', '', 1, 6, 0, 0),
(33, 'Beatriz Lima', 'beatriz.lima@limpeza.com', '$2y$10$/QGuVCc0lr8nBvSIA4r4K.JwxgpAGqZdx/ZvpoTP0Ye9rGeSjYDxa', 'beatriz.png', 'faxina7.png', 'faxina8.png', 'faxina2.png', 'banner3.png', 'Sou especialista em limpeza de grandes espaços, como galpões e escritórios, utilizando equipamentos profissionais para garantir a eficiência do serviço.', '11987612345', '2000-02-12', 0.00, '', '', 1, 6, 0, 0),
(34, 'Camila Nunes', 'camila.nunes@limpeza.com', '$2y$10$3bsOLhSpbOGLa9UuoKXtv.P.MLR5DtnHs4SWqi0gNLkEZaKpctCYS', 'camila.png', 'faxina6.png', 'faxina2.png', 'faxina4.png', 'banner2.png', 'Atuo em Jundiaí com serviços de limpeza de condomínios e residências de grande porte, sempre priorizando o uso de produtos biodegradáveis.', '11986754321', '1000-02-12', 0.00, '', '', 1, 5, 0, 0),
(35, 'Lucas Silva', 'lucas.silva@limpeza.com', '$2y$10$ENI79nTUbtlb180s8bZ9R.z05fMOGDk8xFx5jFQ/CGjDlHIgxNx7y', 'lucas .png', 'faxina1.png', 'faxina3.png', 'faxina5.png', 'banner1.png', 'Trabalho com limpeza residencial e empresarial, oferecendo serviços personalizados que incluem a organização de ambientes e a lavagem de vidros.', '11986547896', '1222-02-12', 0.00, '', '', 1, 5, 0, 0),
(36, 'Patricia Rocha', 'patricia.rocha@limpeza.com', '$2y$10$i8usPgcXYSR.AGt1DHIJ1ejQ.3IZeCGXVPwhLUO5Qrait/PxBH0P2', 'patricia.png', 'faxina2.png', 'faxina7.png', 'faxina8.png', 'banner4.png', 'Faço serviços de limpeza pesada em casas, especialmente para quem busca uma faxina mais completa ou para eventos especiais.', '11983216587', '1200-03-12', 0.00, '', '', 1, 5, 0, 0),
(37, 'Eduardo Cunha', 'eduardo.cunha@limpeza.com', '$2y$10$pKf8sE1pcS77Z2tArVdJievKZDOUg.iJ104pmPg815ac3MuB6ISrK', 'lucas.png', 'faxina8.png', 'faxina3.png', 'faxina1.png', 'banner5.png', 'Faço a limpeza completa de espaços residenciais e empresariais, com atenção especial a detalhes como rodapés, portas e janelas.', '11987653210', '1000-02-12', 0.00, '', '', 1, 4, 0, 0),
(38, 'Mariana Santos', 'mariana.santos@limpeza.com', '$2y$10$nNBgrbhlLGN.pt2bMMUPe.aCAP2HXD7ssRsqXUkEAYldRNXexGohq', 'mariana.png', 'faxina6.png', 'faxina1.png', 'faxina2.png', 'banner1.png', 'Realizo serviços de limpeza em apartamentos e casas de médio porte, além de organização de ambientes e pequenos reparos de manutenção.', '11983214567', '1000-02-12', 0.00, '', '', 1, 4, 0, 0),
(39, 'Roberto Farias', 'roberto.farias@limpeza.com', '$2y$10$gQ06GDm132wtVYruWLwsVuEuTVMj/78127nfRsHnW2s5u8rKbRdOG', 'robert.png', 'faxina5.png', 'faxina6.png', 'faxina8.png', 'banner6.png', 'Atuo com limpezas de fim de obra e organização pós-eventos, oferecendo soluções rápidas e eficientes para deixar o local impecável.', '11987643125', '1000-02-28', 0.00, '', '', 1, 4, 0, 0),
(40, 'Daniela Almeida', 'daniela.almeida@limpeza.com', '$2y$10$wOBGPvcpJBv34CQDpU9YYehOMHM/IZdfF8BgvNOAw0vcr8E1drwVe', 'daniela.png', 'faxina8.png', 'faxina6.png', 'faxina5.png', 'banner4.png', 'Trabalho com serviços de limpeza residencial em Várzea Paulista, focando na limpeza profunda de cozinhas e banheiros, além de áreas externas.\r\n', '11983216578', '1000-02-13', 0.00, '', '', 1, 7, 0, 0),
(41, 'André Batista', 'andre.batista@limpeza.com', '$2y$10$jhTwkQ2SwPFBwBkePR4iuezolaad6ipyVMOnS5uWtTXiTPRSb1wDW', 'andre.png', 'faxina4.png', 'faxina2.png', 'faxina1.png', 'banner3.png', 'Ofereço serviços de limpeza para escritórios e comércios, com foco na manutenção diária e organização de ambientes de trabalho.', '11987653214', '1230-03-12', 0.00, '', '', 1, 7, 0, 0),
(42, 'Pedro de Oliveira Melo', 'pedro.melo@gmail.com', '$2y$10$be9.28FxpHAwJ/b8VgrtwOGY5MelWIHCI1GofIW9iGhd10K8bfGK6', '../uploads/teste2.jpeg', '', '', '', '', 'sou eu', '12956452384', '1980-05-30', 0.00, '', '', 3, 1, 0, 0),
(43, 'Ana Paula Oliveira Junior', 'anapaula@gmail.com', '$2y$10$tUFwBdGKkQylHG/Tv0.0NOpMo3KtK8FjGKh658Sr1dqV0khtZ.CCq', 'fotoM1.PNG', 'fotoTrabalho1.PNG', 'fotoTrabalho2.PNG', 'fotoTrabalho3.PNG', 'fotoBanner1.PNG', 'Sou a Ana, uma apaixonada por gastronomia. Tenho uma lanchonete que serve petiscos e pratos rápidos. Adoro receber os clientes e sempre busco inovar no cardápio com receitas que encantam!', '11912345678', '1980-10-10', 0.00, '', '', 4, 1, 0, 0),
(44, 'Carlos Eduardo', 'carloseduardo@hotmail.com', '$2y$10$mWXlZ5TcWfVm/svvnTbfv.YJwzE9TfbEi6mccL5i1z2mWr7cCINmW', 'fotoH.PNG', 'fotoTrabalho4.PNG', 'fotoTrabalho5.PNG', 'fotoTrabalho6.PNG', 'fotoBanner2.PNG', 'Me chamo Carlos e sou dono de uma marmitaria. Preparo refeições caseiras com ingredientes frescos e de qualidade. Meu maior objetivo é proporcionar conforto e sabor às mesas das pessoas', '11987654321', '1900-10-10', 0.00, '', '', 4, 5, 0, 0),
(45, 'Juliana Lima', 'julianalima@yahoo.com.br', '$2y$10$WVDA0hRAFW/5.nPdd42N3OAI5c8d2J4fvDWM2EVtgOD.ubwkvyFme', 'fotoM2.PNG', 'fotoTrabalho7.PNG', 'fotoTrabalho8.PNG', 'fotoTrabalho9.PNG', 'fotoBanner3.PNG', 'Oi, sou a Ju! Trabalho com um food truck que oferece comida saudável e saborosa. Acredito que comer bem é fundamental, e estou sempre em busca de novos ingredientes para minhas receitas', '11998765432', '2000-02-11', 0.00, '', '', 4, 3, 0, 0),
(46, 'Felipe Santos', 'felipesantos@gmail.com', '$2y$10$z/oJ24kWBg52vQXBMwz5DezAR3mN3JhPLr5iLBhfqCGBG6hb0YR9C', 'fotoH2.PNG', 'fotoTrabalho10.PNG', 'fotoTrabalho3.PNG', 'fotoTrabalho7.PNG', 'fotoBanner4.PNG', 'Meu nome é Felipe e sou chef em uma pequena lanchonete. Minha paixão é cozinhar e criar pratos que façam as pessoas sorrirem. Acredito que cada refeição deve ser uma experiência única.', '11963219876', '1960-02-23', 0.00, '', '', 4, 6, 0, 0),
(47, 'Larissa Ferreira', 'larissa.ferreira@gmail.com', '$2y$10$y4HqtH1TU3Q/xYH5rmAOwevA9wfWsOOGE8Sfw82VHAJAGKzAuLePa', 'fotoM3.PNG', 'fotoTrabalho1.PNG', 'fotoTrabalho9.PNG', 'fotoTrabalho4.PNG', 'fotoBanner5.PNG', 'Oi, sou a Larissa, e sou especialista em comidas de boteco. Tenho um barzinho que é o ponto de encontro da galera. Faço tudo com muito carinho e adoro ouvir as histórias dos clientes', '11934567890', '2000-04-04', 0.00, '', '', 4, 7, 0, 0),
(48, 'Bruno Almeida', 'bruno.almeida@gmail.com', '$2y$10$jiPa8caLfwVU8lVh3L0Fsu7B8CLM4INDWDflHp3d40I9vg9OaMEGa', 'fotoH3.PNG', 'fotoTrabalho8.PNG', 'fotoTrabalho2.PNG', 'fotoTrabalho9.PNG', 'fotoBanner6.PNG', 'Meu nome é Bruno, e sou dono de uma lanchonete de hambúrgueres artesanais. Gosto de experimentar novos sabores e sempre busco oferecer uma experiência única para meus clientes', '11901234567', '1978-02-02', 0.00, '', '', 4, 4, 0, 0),
(49, 'Mariana Costa', 'mariana.costa@hotmail.com', '$2y$10$fZ6YP2pXc82akP1DDAnJi.jharehmEBB5HvwNBNTRc584dPWU0yii', 'fotoM4.PNG', 'fotoTrabalho3.PNG', 'fotoTrabalho7.PNG', 'fotoTrabalho6.PNG', 'fotoBanner7.PNG', 'Sou a Mariana e tenho uma empresa de marmitas fitness. Minha missão é ajudar as pessoas a se alimentarem de forma saudável, sem abrir mão do sabor. Adoro ver meus clientes satisfeitos!', '1187654-3210', '2000-11-11', 0.00, '', '', 4, 2, 0, 0),
(50, 'Ricardo Silva', 'ricardosilva@yahoo.com.br', '$2y$10$twwzGDwisMmOLiQ5AFV/8OVKzdkWDlx4XshRRCavsiKto9s5K31wq', 'fotoH4.PNG', 'fotoTrabalho4.PNG', 'fotoTrabalho1.PNG', 'fotoTrabalho10.PNG', 'fotoBanner1.PNG', 'Oi, eu sou o Ricardo. Trabalho em uma lanchonete que serve pratos tradicionais. Tenho uma relação muito próxima com os clientes e sempre busco entender suas preferências.', '11912346789', '1990-02-10', 0.00, '', '', 4, 1, 0, 0),
(51, 'Camila Rocha', 'camilarocha@gmail.com', '$2y$10$7zq37Zz1mBHNyFHdMMjjeOPNZ7/K6j8pOKrBJGtDRKXDR0tU5NHiq', 'fotoM5.PNG', 'fotoTrabalho5.PNG', 'fotoTrabalho8.PNG', 'fotoTrabalho3.PNG', 'fotoBanner2.PNG', 'Meu nome é Camila e sou chef de cozinha em uma pequena bistrô. Acredito que a comida é uma forma de arte e me esforço para criar pratos que sejam tão bonitos quanto gostosos.', '11934561234', '2000-11-11', 0.00, '', '', 4, 5, 0, 0),
(52, 'Eduardo Martins', 'eduardomartins@hotmail.com', '$2y$10$.AIx/cf8zcB4lj7dATnhkutgF1gZiO.6meOMzvdMBYagL3bXYmDe2', 'fotoH5.PNG', 'fotoTrabalho3.PNG', 'fotoTrabalho9.PNG', 'fotoTrabalho5.PNG', 'fotoBanner3.PNG', 'Sou o Eduardo, e tenho uma lanchonete famosa pelos sanduíches. Cada receita é feita com muito amor e dedicação. Ver a alegria dos clientes ao comer é a minha maior recompensa', '11998765432', '2000-02-22', 0.00, '', '', 4, 3, 0, 0),
(53, 'Aline Souza', 'alinesouza@gmail.com', '$2y$10$zUbx87YLitw3h3iWw6zmQeWERMyqi0UwcxBKHWUZEM51ghuUtrgz.', 'fotoM6.PNG', 'fotoTrabalho4.PNG', 'fotoTrabalho7.PNG', 'fotoTrabalho6.PNG', 'fotoBanner4.PNG', 'Oi, sou a Aline! Tenho um pequeno restaurante que serve comida caseira. Cada prato que faço é preparado com receitas da minha avó, e adoro compartilhar essa tradição com meus clientes', '11921234567', '1999-05-05', 0.00, '', '', 4, 6, 0, 0),
(54, '', 'jorgelima@yahoo.com.br', '$2y$10$/CelomElx2fRklCDK9crDOIjQ0SuWSbtc6Hv1Nr.ad1T4yOMG8pm.', 'fotoH6.PNG', 'fotoTrabalho10.PNG', 'fotoTrabalho9.PNG', 'fotoTrabalho5.PNG', 'fotoBanner5.PNG', 'Meu nome é Jorge e sou chef em uma lanchonete que se destaca pelo seu tempero caseiro. Gosto de ouvir os feedbacks dos clientes e aprimorar sempre minhas receitas', '11909876543', '2000-05-05', 0.00, '', '', 4, 7, 0, 0),
(55, 'Paula Gomes', 'paulagomes@hotmail.com', '$2y$10$V9uHP6NRf5QGfTpdYMTOCe5QYQGsLUmBSlQ9dF/6IARMKgtuN5rz6', 'fotoM7.PNG', 'fotoTrabalho1.PNG', 'fotoTrabalho8.PNG', 'fotoTrabalho6.PNG', 'fotoBanner6.PNG', 'Sou a Paula, e trabalho em uma marmitaria que preza pela qualidade e sabor. Acredito que uma refeição saudável pode ser deliciosa e faço questão de oferecer isso aos meus clientes.', '11932109876', '2000-11-11', 0.00, '', '', 4, 4, 0, 0),
(56, 'André Pereira', 'andre.pereira@gmail.com', '$2y$10$mM0OgF36HvWrRq/H4lGqQeYB4.D0UxJJRbQ0Tr2dKYxzIoyp.BNPC', 'fotoH7.PNG', 'fotoTrabalho8.PNG', 'fotoTrabalho2.PNG', 'fotoTrabalho6.PNG', 'fotoBanner7.PNG', 'Meu nome é André e sou dono de uma lanchonete que serve comida nordestina. Adoro fazer pratos tradicionais e vejo isso como uma forma de representar minha cultura e minhas raízes.', '11998765432', '2000-02-22', 0.00, '', '', 4, 2, 0, 0),
(57, 'Renata Alves', 'renata.alves@yahoo.com.br', '$2y$10$Sdj7sdOxu8jb0Ch8WvATpulgR/I2sYZe09/jFmB/9vyHNFApKbS0i', 'fotoM8.PNG', 'fotoTrabalho3.PNG', 'fotoTrabalho9.PNG', 'fotoTrabalho1.PNG', 'fotoBanner6.PNG', 'Oi, sou a Renata! Tenho um pequeno café onde faço doces e salgados caseiros. A satisfação dos meus clientes ao experimentar minhas receitas é o que me motiva a continuar.', '11934567890', '1999-05-05', 0.00, '', '', 4, 5, 0, 0),
(58, 'Samuel Dias', 'samuel.dias@hotmail.com', '$2y$10$dF4OSCsguRr6vA5P3UoCXe//AdVz52k7bD.mjoTEHQBUnkHtVdwRa', 'fotoH8.PNG', 'fotoTrabalho4.PNG', 'fotoTrabalho5.PNG', 'fotoTrabalho8.PNG', 'fotoBanner7.PNG', 'Meu nome é Samuel, e sou chef em uma lanchonete de comida internacional. Adoro viajar e trazer novas influências para o meu cardápio, sempre buscando surpreender meus clientes', '11987651234', '2000-07-07', 0.00, '', '', 4, 6, 0, 0),
(59, 'Thais Mello', 'thaismello@gmail.com', '$2y$10$7XIWYLX9bK9pNx90vWxmq.6UoQSWRi0cy4adu4R8Mt4bPjmuK7TaW', 'fotoM9.PNG', 'fotoTrabalho3.PNG', 'fotoTrabalho9.PNG', 'fotoTrabalho3.PNG', 'fotoBanner4.PNG', 'Sou a Thais e trabalho com um delivery de marmitas. Meu foco é oferecer opções saudáveis e práticas, sempre com ingredientes frescos. Ver a saúde dos meus clientes melhorando é gratificante.', '11998765432', '3333-04-04', 0.00, '', '', 4, 1, 0, 0),
(60, 'daniel', 'd@gmail.com', '$2y$10$hOdvC1GMw0kqlcmH24eeleG648hSSVP1I3oIgwJYelr9uyuPnKMoO', '../uploads/ana.png', '', '', '', '', '', '11986543210', '1982-04-07', 0.00, '', '', 11, 5, 0, 0),
(61, 'Paola Carosella', 'paola@gmail.com', '$2y$10$w.rjzDQXIR3OoFmxuyRtXu1VCErf7wW5ZnwHB5nLflt1RvvAkqrEa', 'foto de perfil.png', '', '', '', '', '', '11140028922', '1982-08-26', 0.00, '', '', 4, 5, 0, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adm`
--
ALTER TABLE `adm`
  ADD PRIMARY KEY (`id_admin`);

--
-- Índices de tabela `area_atuação`
--
ALTER TABLE `area_atuação`
  ADD PRIMARY KEY (`id_area`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices de tabela `atualizacoes_pendentes`
--
ALTER TABLE `atualizacoes_pendentes`
  ADD PRIMARY KEY (`id_atualizacoes_pendentes`),
  ADD KEY `fk_trabalhador` (`id_trabalhador`),
  ADD KEY `fk_area` (`id_area`),
  ADD KEY `fk_categoria` (`id_categoria`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_trabalhador` (`id_trabalhador`),
  ADD KEY `id_trabalhador_sessao` (`id_trabalhador_sessao`);

--
-- Índices de tabela `conversas`
--
ALTER TABLE `conversas`
  ADD PRIMARY KEY (`id_conversa`),
  ADD KEY `id_trabalhador` (`id_trabalhador`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id_curtida`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_trabalhador` (`id_trabalhador`),
  ADD KEY `id_usuario` (`id_cliente`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices de tabela `reclamacao_cliente`
--
ALTER TABLE `reclamacao_cliente`
  ADD PRIMARY KEY (`id_reclamacao_cliente`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `reclamacao_trabalhador`
--
ALTER TABLE `reclamacao_trabalhador`
  ADD PRIMARY KEY (`id_reclamacao_trabalhador`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices de tabela `trabalhador`
--
ALTER TABLE `trabalhador`
  ADD PRIMARY KEY (`id_trabalhador`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_area` (`id_area`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adm`
--
ALTER TABLE `adm`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `area_atuação`
--
ALTER TABLE `area_atuação`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `atualizacoes_pendentes`
--
ALTER TABLE `atualizacoes_pendentes`
  MODIFY `id_atualizacoes_pendentes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `conversas`
--
ALTER TABLE `conversas`
  MODIFY `id_conversa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id_curtida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `reclamacao_cliente`
--
ALTER TABLE `reclamacao_cliente`
  MODIFY `id_reclamacao_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `reclamacao_trabalhador`
--
ALTER TABLE `reclamacao_trabalhador`
  MODIFY `id_reclamacao_trabalhador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `trabalhador`
--
ALTER TABLE `trabalhador`
  MODIFY `id_trabalhador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `area_atuação`
--
ALTER TABLE `area_atuação`
  ADD CONSTRAINT `area_atuação_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Restrições para tabelas `atualizacoes_pendentes`
--
ALTER TABLE `atualizacoes_pendentes`
  ADD CONSTRAINT `fk_area` FOREIGN KEY (`id_area`) REFERENCES `area_atuação` (`id_area`),
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `fk_trabalhador` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`),
  ADD CONSTRAINT `comentarios_ibfk_4` FOREIGN KEY (`id_trabalhador_sessao`) REFERENCES `trabalhador` (`id_trabalhador`);

--
-- Restrições para tabelas `conversas`
--
ALTER TABLE `conversas`
  ADD CONSTRAINT `conversas_ibfk_1` FOREIGN KEY (`id_trabalhador`) REFERENCES `conversas` (`id_conversa`),
  ADD CONSTRAINT `conversas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `conversas` (`id_conversa`);

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`);

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`);

--
-- Restrições para tabelas `reclamacao_cliente`
--
ALTER TABLE `reclamacao_cliente`
  ADD CONSTRAINT `reclamacao_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `reclamacao_trabalhador`
--
ALTER TABLE `reclamacao_trabalhador`
  ADD CONSTRAINT `reclamacao_trabalhador_ibfk_1` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhador` (`id_trabalhador`);

--
-- Restrições para tabelas `trabalhador`
--
ALTER TABLE `trabalhador`
  ADD CONSTRAINT `trabalhador_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `trabalhador_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `area_atuação` (`id_area`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
