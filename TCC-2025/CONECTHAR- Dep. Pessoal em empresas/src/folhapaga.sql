-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/10/2025 às 12:14
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
-- Banco de dados: `folhapaga`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  `nome_cargo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`id`, `empresa_id`, `setor_id`, `nome_cargo`, `descricao`, `data_criacao`) VALUES
(1, 9, 8, 'Analista', 'Cargo de analista para o departamento pessoal', '2025-10-10 04:04:37'),
(2, 1, 11, 'Analista', NULL, '2025-10-17 19:29:26'),
(3, 1, 12, 'Analista', 'Simmmmm', '2025-10-17 23:44:44'),
(4, 13, 13, 'Analista', NULL, '2025-10-30 11:00:20'),
(5, 13, 14, 'Gerente', NULL, '2025-10-30 11:01:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config_pagamento`
--

CREATE TABLE `config_pagamento` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `qtdPagamentos` varchar(10) DEFAULT '1',
  `diaPagamento1` varchar(10) DEFAULT '5',
  `diaPagamento2` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `controlarfolhadepagamento`
--

CREATE TABLE `controlarfolhadepagamento` (
  `id` int(11) NOT NULL,
  `mes_referencia` date NOT NULL,
  `total_liquido_geral` decimal(12,2) DEFAULT NULL,
  `total_bruto_geral` decimal(12,2) DEFAULT NULL,
  `data_processamento` timestamp NOT NULL DEFAULT current_timestamp(),
  `processado_por_gestor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `nome_empresa` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`id`, `cnpj`, `nome_empresa`, `senha_hash`) VALUES
(1, '12345678910111', 'teste', '$2b$10$default_hash_aqui'),
(2, '12345678911101', 'teste1', '$2b$10$default_hash_aqui'),
(3, '12345678991011', 'r', '$2b$10$default_hash_aqui'),
(4, '12345678902331', 'rmaon', '$2b$10$default_hash_aqui'),
(8, '12345678918456', 'tamandua', '$2b$10$default_hash_aqui'),
(9, '12345678922543', 'bolsonaro', '$2b$10$ktV7ftJ0i/Hdy1EjODxQVeDBtcP7LVm6uUUTc9y8k55bWjx4tfWua'),
(10, '1234567892412', 'meu bem', '$2b$10$FowSlFRUUTsvhUiLYVkgFOZc8CJGmSQ/LjjQzSG5bIunxr5/kHlGm'),
(11, '12345678910234', 'wuwa', '$2b$10$JywJpNHAcOarIiIJTyL/EOIICxxWgzUzz0b7BNQdkq/3gOMoSlgYy'),
(12, '12345678910232', 'teste', '$2b$10$h/iY/gK5J10STb4.p.QOlumSn8LMLrW/SJ/3Inh0.b8fjTl4jJ7WK'),
(13, '12345678910444', 'AFRO - Nation', '$2b$10$CEwhvCQvsJRhXxUbqSNCsuykRsv3Owt5CokQOpFjXEzdBT2SwrpZm');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ferias`
--

CREATE TABLE `ferias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `dias_corridos` int(11) NOT NULL,
  `status` enum('pendente','aprovado','rejeitado','em_gozo','finalizado') DEFAULT 'pendente',
  `aprovado_por` int(11) DEFAULT NULL,
  `data_aprovacao` timestamp NULL DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciarbeneficios`
--

CREATE TABLE `gerenciarbeneficios` (
  `id` int(11) NOT NULL,
  `gestor_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cargo_id` int(11) DEFAULT NULL,
  `setor_id` int(11) DEFAULT NULL,
  `nome_do_beneficio` varchar(100) NOT NULL,
  `descricao_beneficio` text DEFAULT NULL,
  `valor_aplicado` decimal(10,2) DEFAULT NULL,
  `data_inicio` date DEFAULT curdate(),
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `gerenciarbeneficios`
--

INSERT INTO `gerenciarbeneficios` (`id`, `gestor_id`, `usuario_id`, `cargo_id`, `setor_id`, `nome_do_beneficio`, `descricao_beneficio`, `valor_aplicado`, `data_inicio`, `data_fim`, `ativo`) VALUES
(8, 5, NULL, 1, 8, 'Vale Refeição', 'Vale refeição diário', 25.00, '2025-10-01', NULL, 1),
(9, 5, NULL, 1, 8, 'Vale Transporte', 'Benefício transporte', 8.00, '2025-10-01', NULL, 1),
(10, 5, NULL, 1, 8, 'Plano de Saúde', 'Plano de saúde empresarial - coparticipação', 150.00, '2025-10-01', NULL, 1),
(11, 1, NULL, 2, 11, 'VR', 'Vai comer e ter refeição sim', 100.00, '2025-10-17', '2025-10-31', 1),
(12, 1, NULL, 3, 12, 'VT', 'tbmm n sei', 1000000.00, '2025-10-31', '2025-11-04', 1),
(13, 29, NULL, 4, 13, 'Vale refeição', '', 350.00, '2025-10-30', '2025-11-30', 1),
(14, 29, NULL, 4, 13, 'Vale transporte', '', 450.00, '2025-10-30', '2025-11-30', 1),
(15, 29, NULL, 5, 14, 'Vale refeição', '', 560.00, '2025-10-30', '2025-11-30', 1),
(16, 29, NULL, 5, 14, 'Vale Viagem', '', 800.00, '2025-10-30', '2025-11-30', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciardocumentacaotrabalhista`
--

CREATE TABLE `gerenciardocumentacaotrabalhista` (
  `id` int(11) NOT NULL,
  `gestor_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `acao_gerenciamento` varchar(100) DEFAULT NULL,
  `data_gerenciamento` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciarponto`
--

CREATE TABLE `gerenciarponto` (
  `id` int(11) NOT NULL,
  `gestor_id` int(11) NOT NULL,
  `registro_ponto_id` int(11) NOT NULL,
  `acao_gerenciamento` varchar(50) DEFAULT NULL,
  `data_acao` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciarrelatorios`
--

CREATE TABLE `gerenciarrelatorios` (
  `id` int(11) NOT NULL,
  `gerado_por_usuario_id` int(11) NOT NULL,
  `tipo_relatorio` varchar(100) NOT NULL,
  `data_geracao` timestamp NOT NULL DEFAULT current_timestamp(),
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `parametros_geracao` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros_geracao`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciarusuarios`
--

CREATE TABLE `gerenciarusuarios` (
  `id` int(11) NOT NULL,
  `gestor_id` int(11) NOT NULL,
  `usuario_gerenciado_id` int(11) NOT NULL,
  `acao_realizada` varchar(100) DEFAULT NULL,
  `data_acao` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_alteracoes`
--

CREATE TABLE `historico_alteracoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `campo_alterado` varchar(100) NOT NULL,
  `valor_anterior` text DEFAULT NULL,
  `valor_novo` text DEFAULT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp(),
  `aprovado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_salario`
--

CREATE TABLE `historico_salario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `salario_anterior` decimal(10,2) NOT NULL,
  `salario_novo` decimal(10,2) NOT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp(),
  `motivo` text DEFAULT NULL,
  `aprovado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_salario`
--

INSERT INTO `historico_salario` (`id`, `usuario_id`, `salario_anterior`, `salario_novo`, `data_alteracao`, `motivo`, `aprovado_por`) VALUES
(1, 26, 1000.00, 5000.00, '2025-10-29 21:29:33', 'simmmmmm', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_acesso`
--

CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL,
  `usuario_tipo` enum('gestor','colaborador') NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `solicitacao_id` int(11) DEFAULT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `dados_adicionais` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_adicionais`)),
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `usuario_id`, `tipo`, `titulo`, `mensagem`, `solicitacao_id`, `lida`, `dados_adicionais`, `criado_em`) VALUES
(1, 26, 'aprovacao_solicitacao', 'Solicitação Aprovada: reajuste_salarial', ' Boa notícia! Seu pedido de reajuste salarial foi APROVADO!\n\n Detalhes do Reajuste:\n• Salário Anterior: R$ 1.000,00\n• Novo Salário: R$ 5.000,00\n• Aumento: R$ 4.000,00 (400.00%)\n• Vigência: A partir de 2025-10-29\n\nO novo valor já está atualizado em seu cadastro e será aplicado na próxima folha de pagamento.\n\nParabéns pelo reconhecimento! ', 14, 0, '{\"salario_anterior\":1000,\"salario_novo\":5000,\"diferenca\":4000,\"percentual\":\"400.00\",\"data_vigencia\":\"2025-10-29\"}', '2025-10-29 21:29:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontos`
--

CREATE TABLE `pontos` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `setor` varchar(255) DEFAULT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  `tipo_registro` enum('entrada','saida','inicio_intervalo','fim_intervalo') NOT NULL,
  `horas` tinyint(3) UNSIGNED DEFAULT NULL,
  `cnpj` varchar(14) NOT NULL,
  `data_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pontos`
--

INSERT INTO `pontos` (`id`, `usuario_id`, `nome`, `setor`, `tipo_usuario`, `tipo_registro`, `horas`, `cnpj`, `data_registro`) VALUES
(1, 5, 'bolsonaro', 'Departamento Pessoal', 'gestor', 'entrada', 8, '12345678922', '2025-09-28 21:56:23'),
(2, 5, 'bolsonaro', 'Departamento Pessoal', 'gestor', 'saida', 6, '12345678922', '2025-09-30 10:40:46'),
(3, 1, 'teste', 'Departamento Pessoal', 'gestor', 'entrada', 8, '12345678910111', '2025-10-04 19:44:27'),
(4, 1, 'teste', 'Departamento Pessoal', 'gestor', 'entrada', 6, '12345678910111', '2025-10-04 19:44:36'),
(5, 7, 'cris', 'TI', 'colaborador', 'entrada', 8, '55770680881', '2025-10-04 19:56:20'),
(6, 7, 'cris', 'TI', 'colaborador', 'saida', 6, '55770680881', '2025-10-04 19:56:37'),
(7, 7, 'cris', 'TI', 'colaborador', 'saida', 8, '55770680881', '2025-10-07 17:42:33'),
(8, 1, 'teste', 'Departamento Pessoal', 'gestor', 'entrada', 4, '12345678910111', '2025-10-17 19:47:22'),
(9, 26, 'sla', 'Departamento Pessoal', 'colaborador', 'entrada', 8, '12345678910111', '2025-10-29 18:37:33'),
(10, 29, 'Ebony', 'Diretor', 'gestor', 'entrada', 4, '12345678910444', '2025-10-30 08:08:39'),
(11, 30, 'Mirelly', 'ADM', 'colaborador', 'entrada', 4, '00000000000000', '2025-10-30 08:13:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `realizarsolicitacoes`
--

CREATE TABLE `realizarsolicitacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo_solicitacao` enum('ferias','alteracao_dados','consulta_banco_horas','banco_horas','desligamento','reembolso','outros','reajuste_salarial') NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_solicitacao` date DEFAULT current_timestamp(),
  `status` enum('pendente','aprovada','rejeitada','reprovada') DEFAULT 'pendente',
  `gestor_id` int(11) DEFAULT NULL,
  `data_aprovacao_rejeicao` date DEFAULT NULL,
  `observacao_gestor` text DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `salario_solicitado` decimal(10,2) DEFAULT NULL,
  `justificativa` text DEFAULT NULL,
  `campo` varchar(255) DEFAULT NULL,
  `novo_valor` varchar(255) DEFAULT NULL,
  `periodo_inicio` date DEFAULT NULL,
  `periodo_fim` date DEFAULT NULL,
  `valor_reembolso` decimal(10,2) DEFAULT NULL,
  `categoria_reembolso` varchar(100) DEFAULT NULL,
  `data_desligamento` date DEFAULT NULL,
  `motivo_desligamento` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `realizarsolicitacoes`
--

INSERT INTO `realizarsolicitacoes` (`id`, `usuario_id`, `tipo_solicitacao`, `descricao`, `data_solicitacao`, `status`, `gestor_id`, `data_aprovacao_rejeicao`, `observacao_gestor`, `titulo`, `data_inicio`, `data_fim`, `salario_solicitado`, `justificativa`, `campo`, `novo_valor`, `periodo_inicio`, `periodo_fim`, `valor_reembolso`, `categoria_reembolso`, `data_desligamento`, `motivo_desligamento`, `created_at`, `updated_at`) VALUES
(1, 7, 'outros', NULL, '2025-10-05', 'pendente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 19:42:23', NULL),
(2, 7, 'outros', NULL, '2025-10-07', 'pendente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 20:05:37', NULL),
(3, 7, 'reajuste_salarial', NULL, '2025-10-07', 'pendente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 20:34:42', NULL),
(4, 7, 'reajuste_salarial', NULL, '2025-10-07', 'pendente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 20:41:47', NULL),
(5, 26, 'reajuste_salarial', 'Sim, eu mereço', '2025-10-17', 'reprovada', 1, '2025-10-29', 'não pode', 'Reajuste salarial – R$ 12000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 02:39:18', '2025-10-29 20:19:50'),
(6, 26, 'reajuste_salarial', 'simmmmmm', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Reajuste salarial – R$ 1200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:18:37', '2025-10-29 20:19:44'),
(7, 26, 'reajuste_salarial', 'simmmmmm', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Reajuste salarial – R$ 1200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:18:59', '2025-10-29 20:19:36'),
(8, 26, 'ferias', 'Feria merecidas', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Férias 2025-10-18 → 2025-10-25', '2025-10-18', '2025-10-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:44:58', '2025-10-29 20:19:30'),
(9, 26, 'ferias', 'ferias teste', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Férias 2025-10-25 → 2025-11-01', '2025-10-25', '2025-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:49:51', '2025-10-29 20:19:25'),
(10, 26, 'ferias', 'Ferias teste final', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Férias 2025-12-20 → 2026-01-01', '2025-12-20', '2026-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:54:08', '2025-10-29 20:19:16'),
(11, 26, 'alteracao_dados', 'Alteração solicitada: Telefone → 11954464799', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Alteração: Telefone', NULL, NULL, NULL, NULL, 'Telefone', '11954464799', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:54:25', '2025-10-29 20:19:08'),
(12, 26, 'consulta_banco_horas', 'teste de banco de horas', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Consulta - Banco de Horas', '2025-10-18', '2025-10-25', NULL, NULL, NULL, NULL, '2025-10-18', '2025-10-25', NULL, NULL, NULL, NULL, '2025-10-18 03:54:43', '2025-10-29 20:19:02'),
(13, 26, 'reajuste_salarial', 'teste aumento de salario', '2025-10-18', 'reprovada', 1, '2025-10-29', 'não pode', 'Reajuste salarial – R$ 3000.00', NULL, NULL, 3000.00, 'teste aumento de salario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:54:58', '2025-10-29 20:18:54'),
(14, 26, 'reajuste_salarial', 'simmmmmm', '2025-10-29', 'aprovada', 1, '2025-10-29', 'Solicitação aprovada e processada automaticamente pelo gestor', 'Reajuste salarial – R$ 5000.00', NULL, NULL, 5000.00, 'simmmmmm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 21:20:15', '2025-10-29 21:29:33'),
(15, 30, 'reajuste_salarial', 'Conforme falado com a gerente, devo ser promovida', '2025-10-30', 'pendente', 29, NULL, NULL, 'Reajuste salarial – R$ 6000.00', NULL, NULL, 6000.00, 'Conforme falado com a gerente, devo ser promovida', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-30 11:12:52', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `realizarupload`
--

CREATE TABLE `realizarupload` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `setor_id` int(11) DEFAULT NULL,
  `tipo_documento` enum('contrato','holerite','atestado','recibo','declaracao','outros','avatar') NOT NULL DEFAULT 'outros',
  `caminho_arquivo` varchar(255) NOT NULL,
  `data_upload` date DEFAULT current_timestamp(),
  `status` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `nome_arquivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `realizarupload`
--

INSERT INTO `realizarupload` (`id`, `usuario_id`, `setor_id`, `tipo_documento`, `caminho_arquivo`, `data_upload`, `status`, `nome_arquivo`) VALUES
(1, 5, NULL, 'contrato', 'uploads/documento-5-1759236227587.jpeg', '2025-09-30', 'pendente', 'WhatsApp Image 2025-09-28 at 18.06.41 (1).jpeg'),
(2, 1, NULL, 'contrato', 'uploads/documento-1-1759601770709.jpg', '2025-10-04', 'pendente', 'changli.jpg'),
(3, 7, 2, 'contrato', 'uploads/doc-7-wallpaper-1759618486038.png', '2025-10-04', 'pendente', 'wallpaper.png'),
(4, 1, NULL, 'contrato', 'uploads/doc-1-wallpaper-1759625802462.png', '2025-10-04', 'pendente', 'wallpaper.png'),
(5, 1, NULL, 'contrato', 'uploads/doc-1-wallpaper-1759625934775.png', '2025-10-04', 'pendente', 'wallpaper.png'),
(6, 1, NULL, 'contrato', 'uploads/doc-1-wallpaper-1759626077092.png', '2025-10-04', 'pendente', 'wallpaper.png'),
(7, 1, NULL, 'contrato', 'uploads/doc-1-wallpaper-1759626176373.png', '2025-10-04', 'pendente', 'wallpaper.png'),
(8, 1, NULL, 'recibo', 'uploads/doc-1-changli-1759626218148.jpg', '2025-10-04', 'pendente', 'changli.jpg'),
(9, 1, NULL, 'contrato', 'uploads/doc-1-changli-1759626957797.jpg', '2025-10-04', 'pendente', 'changli.jpg'),
(10, 1, NULL, 'contrato', 'uploads/doc-1-changli-1759627255153.jpg', '2025-10-04', 'pendente', 'changli.jpg'),
(11, 1, NULL, 'avatar', 'uploads/doc-1-geminigeneratedimage87opg87opg87opg8-1760064208537.png', '2025-10-09', 'pendente', 'Gemini_Generated_Image_87opg87opg87opg8.png'),
(12, 1, NULL, 'contrato', 'uploads/doc-1-f98d2be7-1c4c-43b7-9920-c9ecea8ddfeb-1760066925019.docx', '2025-10-10', 'pendente', 'f98d2be7-1c4c-43b7-9920-c9ecea8ddfeb.docx'),
(13, 1, NULL, 'recibo', 'uploads/doc-1-organograma-1760315740554.png', '2025-10-12', 'pendente', 'organograma.png'),
(14, 1, NULL, 'atestado', 'uploads/doc-1-perguntafeitaparaochat-1760315949326.txt', '2025-10-12', 'pendente', 'PerguntaFeitaParaOchat.txt'),
(15, 1, NULL, 'declaracao', 'uploads/doc-1-ramonvianaferreiradosreismodulo01comerci-1760316159848.pdf', '2025-10-12', 'pendente', 'ramon_viana_ferreira_dos_reis_modulo_01_comercio.pdf'),
(16, 1, NULL, 'contrato', 'uploads/doc-1-organograma-1760316380577.png', '2025-10-12', 'pendente', 'organograma.png'),
(17, 1, NULL, 'outros', 'uploads/doc-1-perguntafeitaparaochat-1760316409667.txt', '2025-10-12', 'pendente', 'PerguntaFeitaParaOchat.txt'),
(18, 1, NULL, 'recibo', 'uploads/doc-1-organograma-1760316605644.png', '2025-10-12', 'pendente', 'organograma.png'),
(19, 1, NULL, 'recibo', 'uploads/doc-1-marcelo-1760341524831.jpeg', '2025-10-13', 'pendente', 'Marcelo.jpeg'),
(20, 1, NULL, 'declaracao', 'uploads/doc-1-etec-1760341873984.png', '2025-10-13', 'pendente', 'Etec.png'),
(21, 1, NULL, 'recibo', 'uploads/doc-1-gays-1760342145948.jpeg', '2025-10-13', 'pendente', 'gays.jpeg'),
(22, 1, NULL, 'atestado', 'uploads/doc-1-nayaranovamente-1760342805997.pdf', '2025-10-13', 'pendente', 'Nayara_Novamente.pdf'),
(23, 26, 11, 'contrato', 'uploads/doc-26-gays-1760343859586.jpeg', '2025-10-13', 'pendente', 'gays.jpeg'),
(24, 29, NULL, 'contrato', 'uploads/doc-29-contratoshanyqua-1761822610724.pdf', '2025-10-30', 'pendente', 'Contrato_Shanyqua.pdf'),
(25, 29, NULL, 'contrato', 'uploads/doc-29-contratomirelly-1761822646026.pdf', '2025-10-30', 'pendente', 'Contrato_Mirelly.pdf'),
(26, 30, 13, 'atestado', 'uploads/doc-30-atestado-1761822719994.pdf', '2025-10-30', 'pendente', 'Atestado.pdf');

-- --------------------------------------------------------

--
-- Estrutura para tabela `registroponto`
--

CREATE TABLE `registroponto` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_hora_registro` datetime NOT NULL,
  `tipo_registro` enum('entrada','saida','intervalo_inicio','intervalo_fim') NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `setor` varchar(50) DEFAULT NULL,
  `horas` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome_setor` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `setores`
--

INSERT INTO `setores` (`id`, `empresa_id`, `nome_setor`, `descricao`) VALUES
(1, 10, 'ADM', NULL),
(2, 9, 'TI', NULL),
(4, 9, 'putaria', NULL),
(5, 9, 'Artes', NULL),
(6, 9, 'Todos', NULL),
(7, 9, 'ADM', NULL),
(8, 9, 'Departamento Pessoal', NULL),
(9, 9, 'odio', NULL),
(10, 9, 'PENIANO', NULL),
(11, 1, 'Departamento Pessoal', NULL),
(12, 1, 'RH', 'Somos todos humanos'),
(13, 13, 'ADM', NULL),
(14, 13, 'Logistica', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_anexos`
--

CREATE TABLE `solicitacao_anexos` (
  `id` int(11) NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `path` varchar(500) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `mime_type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `solicitacao_anexos`
--

INSERT INTO `solicitacao_anexos` (`id`, `solicitacao_id`, `nome`, `path`, `criado_em`, `mime_type`, `size`) VALUES
(1, 14, 'Folha_Pagamento_setor_1761768459927.pdf', '1761772815416_Folha_Pagamento_setor_1761768459927.pdf', '2025-10-29 21:20:15', 'application/pdf', 121856),
(2, 14, 'Folha_Pagamento_setor_1761768459927.pdf', '1761772815430_Folha_Pagamento_setor_1761768459927.pdf', '2025-10-29 21:20:15', 'application/pdf', 121856);

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_log`
--

CREATE TABLE `solicitacao_log` (
  `id` int(11) NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `gestor_id` int(11) DEFAULT NULL,
  `acao` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `solicitacao_log`
--

INSERT INTO `solicitacao_log` (`id`, `solicitacao_id`, `gestor_id`, `acao`, `created_at`, `observacao`) VALUES
(1, 10, 1, 'status:aprovada', '2025-10-29 20:18:19', 'Solicitação aprovada pelo gestor'),
(2, 13, 1, 'status:reprovada', '2025-10-29 20:18:54', 'não pode'),
(3, 12, 1, 'status:reprovada', '2025-10-29 20:19:02', 'não pode'),
(4, 11, 1, 'status:reprovada', '2025-10-29 20:19:08', 'não pode'),
(5, 10, 1, 'status:reprovada', '2025-10-29 20:19:16', 'não pode'),
(6, 9, 1, 'status:reprovada', '2025-10-29 20:19:25', 'não pode'),
(7, 8, 1, 'status:reprovada', '2025-10-29 20:19:30', 'não pode'),
(8, 7, 1, 'status:reprovada', '2025-10-29 20:19:36', 'não pode'),
(9, 6, 1, 'status:reprovada', '2025-10-29 20:19:44', 'não pode'),
(10, 5, 1, 'status:reprovada', '2025-10-29 20:19:50', 'não pode'),
(11, 14, 1, 'aprovacao_automatica', '2025-10-29 21:29:33', 'Solicitação aprovada e processada automaticamente. Dados atualizados no sistema.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `numero_registro` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cnpj` varchar(14) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo_usuario` enum('gestor','colaborador') NOT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `setor` varchar(50) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT 0.00,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_jornada` varchar(100) NOT NULL,
  `horas_diarias` int(11) NOT NULL DEFAULT 8,
  `dependentes` int(11) DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_admissao` date DEFAULT NULL,
  `gestor_id` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_desligamento` date DEFAULT NULL,
  `motivo_desligamento` text DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `empresa_id`, `numero_registro`, `nome`, `cpf`, `email`, `cnpj`, `senha_hash`, `tipo_usuario`, `cargo`, `setor`, `salario`, `data_criacao`, `tipo_jornada`, `horas_diarias`, `dependentes`, `foto`, `telefone`, `data_admissao`, `gestor_id`, `ativo`, `data_desligamento`, `motivo_desligamento`, `endereco`, `cep`, `cidade`, `estado`) VALUES
(1, 1, 'G1', 'teste', NULL, NULL, '12345678910111', '$2b$10$Cl8nwzxRUMZBIyjDllm8QOG7J8QOvEE7k/LMpubTj779o23lT9gJy', 'gestor', NULL, NULL, 0.00, '2025-08-27 09:28:49', '6x1', 8, 0, 'doc-1-geminigeneratedimage87opg87opg87opg8-1760064208537.png', NULL, '2025-08-27', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'G2', 'teste1', NULL, NULL, '12345678911101', '$2b$10$FUooESsPuSv9ODgmOX1Ehek/89VS/VCtFv9ya4I64ZrASqyl80jYu', 'gestor', NULL, NULL, 0.00, '2025-08-27 09:38:29', '6x1', 8, 0, NULL, NULL, '2025-08-27', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'G3', 'r', NULL, NULL, '1234567899101', '$2b$10$B849u4PKLbINn0LEuEIjgegMwene1NaUupI6R.OgvpgLJKiO7JKFK', 'gestor', NULL, NULL, 0.00, '2025-09-11 14:27:02', '6x1', 8, 0, NULL, NULL, '2025-09-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'G4', 'rmaon', NULL, NULL, '12345678902331', '$2b$10$CtX8pT4FXstCLh2zK0CCHutsMXqQ7i4.w7FzqT/8xaaSTc5zTtrV2', 'gestor', NULL, NULL, 0.00, '2025-09-11 14:49:05', '6x1', 8, 0, NULL, NULL, '2025-09-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 9, 'G9', 'bolsonaro', NULL, NULL, '12345678922543', '$2b$10$ktV7ftJ0i/Hdy1EjODxQVeDBtcP7LVm6uUUTc9y8k55bWjx4tfWua', 'gestor', NULL, NULL, 0.00, '2025-09-16 01:12:45', '6x1', 8, 0, NULL, NULL, '2025-09-15', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 10, 'G10', 'luis', NULL, NULL, '12345678924', '$2b$10$FowSlFRUUTsvhUiLYVkgFOZc8CJGmSQ/LjjQzSG5bIunxr5/kHlGm', 'gestor', 'Adm', 'ADM', 0.00, '2025-09-29 02:28:19', '6x1', 8, 0, NULL, NULL, '2025-09-28', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 9, 'C001', 'cris', '55770680881', NULL, '55770680881', '$2b$10$9WXuFoVIFPsvuWRHBYVE8OyzXOOVXSTeHfMB.4Pel0IFWB5ixcFQq', 'colaborador', 'Analista', 'TI', 450.00, '2025-09-29 14:11:40', '6x1', 6, 0, '1759196108067.jpg', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 9, 'CC003', 'edson', '55770680882', NULL, '12345678922', '$2b$10$0xnH0IXNMXWDNFAdkv6pvOF9eE.y82qOGeSjUkHytWI42zKhxSsda', 'colaborador', 'Analista', 'TI', 140.00, '2025-09-30 01:14:29', '6x1', 6, 0, '1759196132303.jpg', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 9, 'CC004', 'jean', '55770680883', NULL, '12345678922', '$2b$10$ZppMgNl6tLpjtX.sbtMMNu9R5Q6MeyDUHEqj65.WDcKfxcKyfEVeW', 'colaborador', 'Desing', 'Artes', 0.00, '2025-09-30 01:36:30', '6x1', 23, 0, '1759196189829.jpg', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 9, 'CC006', 'rebecca', '55770680885', NULL, '12345678922', '$2b$10$2T89V75xqBr51X/yz46I5eYFEGahw8c.MYetGQsMzom7j3ZcZxVvK', 'colaborador', 'Analista', 'TI', 0.00, '2025-09-30 01:43:18', '6x1', 12, 0, '1759196598380.jpg', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 9, 'CC009', 'samuel', '55770680889', NULL, '12345678922', '$2b$10$/ue3J4g7oSii4OxKqpgqM.vEc9v0WV/A4QiSDtWEmiRBhn5NjUJLi', 'colaborador', 'Analista', 'odio', 0.00, '2025-09-30 13:34:21', '6x1', 12, 0, '1759239260750.jpg', NULL, '2025-09-30', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 9, 'CC010', 'mAUHE', '40028922', NULL, '12345678922', '$2b$10$sZoxR0qx5yLv5ZbUQdu0.ORRS3OP/vYp3GJt54/2rG74LqMM.hVfC', 'colaborador', 'Vadiação', 'PENIANO', 0.00, '2025-09-30 13:57:17', 'flexivel', 23, 0, '1759240637201.jpeg', NULL, '2025-09-30', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 9, 'CC011', 'Ramon Viana Ferreira Dos Reis ', '55770680871', NULL, '12345678922', '$2b$10$Bt3vCU7vH.l8m1V0I.AcueHVGKFteySpkppiTl68q.oiJc/pu3FYu', 'colaborador', 'Analista', 'TI', 1250.00, '2025-10-01 21:33:15', '6x1', 12, 0, 'fundofoda.png', NULL, '2025-10-01', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 11, 'G11', 'changli', NULL, NULL, '12345678910234', '$2b$10$JywJpNHAcOarIiIJTyL/EOIICxxWgzUzz0b7BNQdkq/3gOMoSlgYy', 'gestor', 'Chefe', 'Recursos humanos', 0.00, '2025-10-04 16:35:14', '5x2', 8, 0, NULL, NULL, '2025-10-04', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 12, 'G12', 'aaa', NULL, NULL, '12345678910232', '$2b$10$h/iY/gK5J10STb4.p.QOlumSn8LMLrW/SJ/3Inh0.b8fjTl4jJ7WK', 'gestor', 'Chefe', 'Recursos humanos', 0.00, '2025-10-04 16:49:18', '6x1', 4, 0, NULL, NULL, '2025-10-04', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 1, 'CC001', 'sla', '12345678911', NULL, '12345678910111', '$2b$10$YyLJl0VdpgLWKLzJtb6ySOzl6L60x9g.KGo80hek48tki8n4XpxJ.', 'colaborador', 'Analista', 'Departamento Pessoal', 5000.00, '2025-10-04 22:43:54', '6x1', 8, 0, '1759617833972.jpg', NULL, '2025-10-04', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 1, 'CC002', 'cris', '55770680822', NULL, '00000000000000', '$2b$10$suzqP/.k.PecSlapi8n7be0eLw/.7TdCCAf.M4zDz9kAFwRDdxJN6', 'colaborador', 'Analista', 'RH', 20000.00, '2025-10-18 00:29:23', '5x2', 12, 0, '1760753848646.jpeg', NULL, '2025-10-17', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, 'CC003', 'socorro', '12345678910', NULL, '00000000000000', '$2b$10$a3gjTBQQeLfRNhwlOBhxuu6GRpxXqvC1ikW4QCFkgUqsJap/hbB/G', 'colaborador', 'Analista', 'RH', 2000.00, '2025-10-26 23:56:33', '5x2', 8, 0, 'fundofoda.png', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 13, 'G13', 'Ebony', NULL, NULL, '12345678910444', '$2b$10$CEwhvCQvsJRhXxUbqSNCsuykRsv3Owt5CokQOpFjXEzdBT2SwrpZm', 'gestor', 'ADM', 'Diretor', 0.00, '2025-10-30 10:59:39', '6x1', 12, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 13, 'CC001', 'Mirelly', '55770680851', NULL, '00000000000000', '$2b$10$eoGD6pYdZ.4b1byjlJ9CjOl69tTKD.wNhYxBAJO3SNd5qHriVqiRm', 'colaborador', 'Analista', 'ADM', 2450.00, '2025-10-30 11:03:49', '5x2', 12, 0, '1761822229926.jfif', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 13, 'CC002', 'Luana', '55770680852', NULL, '00000000000000', '$2b$10$lPww3T/WmBK9KaLbQyuQ0uHBPodt8V9OaI/uHOzwBNtiIRKxUtV1S', 'colaborador', 'Analista', 'ADM', 4500.00, '2025-10-30 11:05:55', '5x2', 10, 0, '1761822355371.jfif', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 13, 'CC003', 'Shanyqua', '55770680853', NULL, '00000000000000', '$2b$10$G8D9W3ggj6VlPjl.5MQ1e.EPZ69p1F/l9cM7NvnnJBYG7SfVkYJqu', 'colaborador', 'Gerente', 'Logistica', 3400.00, '2025-10-30 11:06:58', '4x3', 12, 0, '1761822418926.jfif', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 13, 'CC004', 'Samira', '55770680854', NULL, '00000000000000', '$2b$10$827I6XRd0wlAMO.UDFBC6.rQ69zA8mNtfZH5DFczM2Al5slnOcPVm', 'colaborador', 'Gerente', 'Logistica', 3400.00, '2025-10-30 11:07:41', 'flexivel', 12, 0, '1761822461837.jfif', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_beneficios`
--

CREATE TABLE `usuario_beneficios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `valor_personalizado` decimal(10,2) DEFAULT NULL,
  `data_inicio` date DEFAULT curdate(),
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario_beneficios`
--

INSERT INTO `usuario_beneficios` (`id`, `usuario_id`, `beneficio_id`, `valor_personalizado`, `data_inicio`, `data_fim`, `ativo`) VALUES
(1, 7, 8, 25.00, '2025-10-01', NULL, 1),
(2, 7, 9, 8.00, '2025-10-01', NULL, 1),
(3, 7, 10, 150.00, '2025-10-01', NULL, 1),
(4, 15, 8, 25.00, '2025-10-01', NULL, 1),
(5, 15, 9, 8.00, '2025-10-01', NULL, 1),
(6, 15, 10, 150.00, '2025-10-01', NULL, 1),
(7, 18, 8, 25.00, '2025-10-01', NULL, 1),
(8, 18, 9, 8.00, '2025-10-01', NULL, 1),
(9, 18, 10, 150.00, '2025-10-01', NULL, 1),
(10, 21, 8, 25.00, '2025-10-01', NULL, 1),
(11, 21, 9, 8.00, '2025-10-01', NULL, 1),
(12, 21, 10, 150.00, '2025-10-01', NULL, 1),
(18, 27, 12, 1000000.00, '2025-10-17', NULL, 1),
(19, 26, 11, 100.00, '2025-10-17', NULL, 1),
(27, 31, 13, 350.00, '2025-10-30', NULL, 1),
(28, 30, 13, 350.00, '2025-10-30', NULL, 1),
(29, 30, 14, 450.00, '2025-10-30', NULL, 1),
(30, 33, 15, 560.00, '2025-10-30', NULL, 1),
(31, 33, 16, 800.00, '2025-10-30', NULL, 1),
(32, 32, 15, 560.00, '2025-10-30', NULL, 1),
(33, 32, 16, 800.00, '2025-10-30', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `visualizardados`
--

CREATE TABLE `visualizardados` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visualizarholerites`
--

CREATE TABLE `visualizarholerites` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mes_referencia` date NOT NULL,
  `salario_base` decimal(10,2) DEFAULT NULL,
  `proventos_detalhe` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`proventos_detalhe`)),
  `descontos_detalhe` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`descontos_detalhe`)),
  `salario_liquido` decimal(10,2) DEFAULT NULL,
  `arquivo_pdf_caminho` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `visualizarholerites`
--

INSERT INTO `visualizarholerites` (`id`, `usuario_id`, `mes_referencia`, `salario_base`, `proventos_detalhe`, `descontos_detalhe`, `salario_liquido`, `arquivo_pdf_caminho`, `data_criacao`, `data_atualizacao`) VALUES
(1, 26, '0000-00-00', 1100.00, '[{\"codigo\":\"001\",\"descricao\":\"Salário Base\",\"referencia\":\"30 dias\",\"valor\":1000},{\"codigo\":\"BENundefined\",\"descricao\":\"VR\",\"referencia\":\"Vai comer e ter refeição sim\",\"valor\":100}]', '[{\"codigo\":\"094\",\"descricao\":\"Vale Refeição\",\"referencia\":\"10%\",\"valor\":12}]', 1088.00, NULL, '2025-10-29 01:30:47', '2025-10-29 01:30:47'),
(2, 26, '2025-10-01', 1000.00, '[{\"codigo\":\"001\",\"descricao\":\"Salário Base\",\"referencia\":\"30 dias\",\"valor\":1000}]', '[{\"codigo\":\"301\",\"descricao\":\"INSS\",\"referencia\":\"Tabela\",\"valor\":75}]', 925.00, NULL, '2025-10-29 06:01:23', '2025-10-29 18:28:28'),
(3, 27, '2025-10-01', 20000.00, '[{\"codigo\":\"001\",\"descricao\":\"Salário Base\",\"referencia\":\"30 dias\",\"valor\":20000},{\"codigo\":\"MANUAL\",\"descricao\":\"13º Salário\",\"referencia\":\"5000\",\"valor\":1000}]', '[{\"codigo\":\"301\",\"descricao\":\"INSS\",\"referencia\":\"Tabela\",\"valor\":908.85},{\"codigo\":\"302\",\"descricao\":\"IRRF\",\"referencia\":\"Tabela\",\"valor\":4629.07}]', 15462.08, NULL, '2025-10-29 06:33:47', '2025-10-29 06:33:47');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_setor_cargo` (`empresa_id`,`setor_id`,`nome_cargo`),
  ADD KEY `fk_cargos_empresa` (`empresa_id`),
  ADD KEY `fk_cargos_setor` (`setor_id`);

--
-- Índices de tabela `config_pagamento`
--
ALTER TABLE `config_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `controlarfolhadepagamento`
--
ALTER TABLE `controlarfolhadepagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mes_referencia` (`mes_referencia`),
  ADD KEY `fk_controlar_folha_gestor` (`processado_por_gestor_id`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `ferias`
--
ALTER TABLE `ferias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `aprovado_por` (`aprovado_por`);

--
-- Índices de tabela `gerenciarbeneficios`
--
ALTER TABLE `gerenciarbeneficios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`nome_do_beneficio`,`data_inicio`),
  ADD KEY `fk_gerenciar_beneficios_gestor` (`gestor_id`),
  ADD KEY `fk_beneficios_cargo` (`cargo_id`),
  ADD KEY `fk_beneficios_setor` (`setor_id`);

--
-- Índices de tabela `gerenciardocumentacaotrabalhista`
--
ALTER TABLE `gerenciardocumentacaotrabalhista`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_id` (`documento_id`),
  ADD KEY `fk_gerenciar_documentacao_gestor` (`gestor_id`);

--
-- Índices de tabela `gerenciarponto`
--
ALTER TABLE `gerenciarponto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registro_ponto_id` (`registro_ponto_id`),
  ADD KEY `fk_gerenciar_ponto_gestor` (`gestor_id`);

--
-- Índices de tabela `gerenciarrelatorios`
--
ALTER TABLE `gerenciarrelatorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gerenciar_relatorios_gerador` (`gerado_por_usuario_id`);

--
-- Índices de tabela `gerenciarusuarios`
--
ALTER TABLE `gerenciarusuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gerenciar_usuarios_gestor` (`gestor_id`),
  ADD KEY `fk_gerenciar_usuarios_usuario_gerenciado` (`usuario_gerenciado_id`);

--
-- Índices de tabela `historico_alteracoes`
--
ALTER TABLE `historico_alteracoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `aprovado_por` (`aprovado_por`);

--
-- Índices de tabela `historico_salario`
--
ALTER TABLE `historico_salario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `aprovado_por` (`aprovado_por`);

--
-- Índices de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitacao_id` (`solicitacao_id`),
  ADD KEY `idx_usuario_lida` (`usuario_id`,`lida`);

--
-- Índices de tabela `pontos`
--
ALTER TABLE `pontos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cnpj` (`cnpj`),
  ADD KEY `idx_data` (`data_registro`),
  ADD KEY `idx_usuario_id` (`usuario_id`);

--
-- Índices de tabela `realizarsolicitacoes`
--
ALTER TABLE `realizarsolicitacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_realizar_solicitacoes_usuario` (`usuario_id`),
  ADD KEY `fk_realizar_solicitacoes_gestor` (`gestor_id`);

--
-- Índices de tabela `realizarupload`
--
ALTER TABLE `realizarupload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_realizar_upload_usuario` (`usuario_id`),
  ADD KEY `idx_realizarupload_setor_id` (`setor_id`);

--
-- Índices de tabela `registroponto`
--
ALTER TABLE `registroponto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_registro_ponto_usuario` (`usuario_id`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_setor` (`empresa_id`,`nome_setor`),
  ADD KEY `fk_setores_empresa` (`empresa_id`);

--
-- Índices de tabela `solicitacao_anexos`
--
ALTER TABLE `solicitacao_anexos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_solicitacao_id` (`solicitacao_id`);

--
-- Índices de tabela `solicitacao_log`
--
ALTER TABLE `solicitacao_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitacao_id` (`solicitacao_id`),
  ADD KEY `gestor_id` (`gestor_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_id` (`empresa_id`,`numero_registro`),
  ADD UNIQUE KEY `uq_usuario_cpf` (`cpf`),
  ADD UNIQUE KEY `uq_usuario_email` (`email`),
  ADD KEY `idx_usuario_cnpj` (`cnpj`);

--
-- Índices de tabela `usuario_beneficios`
--
ALTER TABLE `usuario_beneficios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_beneficio_ativo` (`usuario_id`,`beneficio_id`) USING BTREE,
  ADD KEY `fk_usuario_beneficios_beneficio` (`beneficio_id`);

--
-- Índices de tabela `visualizardados`
--
ALTER TABLE `visualizardados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visualizar_dados_usuario` (`usuario_id`);

--
-- Índices de tabela `visualizarholerites`
--
ALTER TABLE `visualizarholerites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`mes_referencia`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `config_pagamento`
--
ALTER TABLE `config_pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `controlarfolhadepagamento`
--
ALTER TABLE `controlarfolhadepagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `ferias`
--
ALTER TABLE `ferias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gerenciarbeneficios`
--
ALTER TABLE `gerenciarbeneficios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `gerenciardocumentacaotrabalhista`
--
ALTER TABLE `gerenciardocumentacaotrabalhista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gerenciarponto`
--
ALTER TABLE `gerenciarponto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gerenciarrelatorios`
--
ALTER TABLE `gerenciarrelatorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gerenciarusuarios`
--
ALTER TABLE `gerenciarusuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_alteracoes`
--
ALTER TABLE `historico_alteracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_salario`
--
ALTER TABLE `historico_salario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pontos`
--
ALTER TABLE `pontos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `realizarsolicitacoes`
--
ALTER TABLE `realizarsolicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `realizarupload`
--
ALTER TABLE `realizarupload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `registroponto`
--
ALTER TABLE `registroponto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `solicitacao_anexos`
--
ALTER TABLE `solicitacao_anexos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `solicitacao_log`
--
ALTER TABLE `solicitacao_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `usuario_beneficios`
--
ALTER TABLE `usuario_beneficios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `visualizardados`
--
ALTER TABLE `visualizardados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visualizarholerites`
--
ALTER TABLE `visualizarholerites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cargos`
--
ALTER TABLE `cargos`
  ADD CONSTRAINT `fk_cargos_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cargos_setor` FOREIGN KEY (`setor_id`) REFERENCES `setores` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `config_pagamento`
--
ALTER TABLE `config_pagamento`
  ADD CONSTRAINT `fk_config_pagamento_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `controlarfolhadepagamento`
--
ALTER TABLE `controlarfolhadepagamento`
  ADD CONSTRAINT `fk_controlar_folha_gestor` FOREIGN KEY (`processado_por_gestor_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `ferias`
--
ALTER TABLE `ferias`
  ADD CONSTRAINT `ferias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ferias_ibfk_2` FOREIGN KEY (`aprovado_por`) REFERENCES `usuario` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `gerenciarbeneficios`
--
ALTER TABLE `gerenciarbeneficios`
  ADD CONSTRAINT `fk_beneficios_cargo` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_beneficios_setor` FOREIGN KEY (`setor_id`) REFERENCES `setores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_gerenciar_beneficios_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_gerenciar_beneficios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `gerenciardocumentacaotrabalhista`
--
ALTER TABLE `gerenciardocumentacaotrabalhista`
  ADD CONSTRAINT `fk_gerenciar_documentacao_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `gerenciarponto`
--
ALTER TABLE `gerenciarponto`
  ADD CONSTRAINT `fk_gerenciar_ponto_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `gerenciarusuarios`
--
ALTER TABLE `gerenciarusuarios`
  ADD CONSTRAINT `fk_gerenciar_usuarios_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `historico_alteracoes`
--
ALTER TABLE `historico_alteracoes`
  ADD CONSTRAINT `historico_alteracoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_alteracoes_ibfk_2` FOREIGN KEY (`aprovado_por`) REFERENCES `usuario` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `historico_salario`
--
ALTER TABLE `historico_salario`
  ADD CONSTRAINT `historico_salario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_salario_ibfk_2` FOREIGN KEY (`aprovado_por`) REFERENCES `usuario` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_2` FOREIGN KEY (`solicitacao_id`) REFERENCES `realizarsolicitacoes` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pontos`
--
ALTER TABLE `pontos`
  ADD CONSTRAINT `fk_pontos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `realizarsolicitacoes`
--
ALTER TABLE `realizarsolicitacoes`
  ADD CONSTRAINT `fk_realizar_solicitacoes_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `setores`
--
ALTER TABLE `setores`
  ADD CONSTRAINT `fk_setores_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `solicitacao_anexos`
--
ALTER TABLE `solicitacao_anexos`
  ADD CONSTRAINT `fk_solicitacao_anexos_realizarsolicitacoes` FOREIGN KEY (`solicitacao_id`) REFERENCES `realizarsolicitacoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `usuario_beneficios`
--
ALTER TABLE `usuario_beneficios`
  ADD CONSTRAINT `fk_usuario_beneficios_beneficio` FOREIGN KEY (`beneficio_id`) REFERENCES `gerenciarbeneficios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario_beneficios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
