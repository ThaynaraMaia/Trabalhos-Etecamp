-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/10/2025 às 07:44
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
-- Banco de dados: `pawsitive`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblanimaisadocao`
--

CREATE TABLE `tblanimaisadocao` (
  `id_animal` int(11) NOT NULL,
  `nome_animal` varchar(50) NOT NULL,
  `caracteristicas_animal` varchar(150) NOT NULL,
  `cidade_animal` varchar(100) NOT NULL,
  `descricao_animal` varchar(300) DEFAULT NULL,
  `genero_animal` varchar(10) NOT NULL,
  `especie_animal` enum('cachorro','gato') DEFAULT NULL,
  `idade_animal` int(11) NOT NULL,
  `condicao_saude` varchar(200) NOT NULL,
  `foto_animal` varchar(200) NOT NULL,
  `status_animal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblanimaisadocao`
--

INSERT INTO `tblanimaisadocao` (`id_animal`, `nome_animal`, `caracteristicas_animal`, `cidade_animal`, `descricao_animal`, `genero_animal`, `especie_animal`, `idade_animal`, `condicao_saude`, `foto_animal`, `status_animal`) VALUES
(3, 'Zeus', 'Tímido', 'Jundiai | São Paulo', 'Zeus, apesar do tamanho, é um cachorro dócil, tímido e super carinhoso! É a companhia perfeita para as maratonas de filmes e, apesar da idade, não dorme antes do final.', 'Macho', 'cachorro', 10, '0', '../../../imgAnimais/68c2c79ab77df-animal01.jpg', 0),
(4, 'Tito', 'Amoroso', 'Campo Limpo | São Paulo', 'Tito é um gato que foi resgatado da rua, por isso é um pouco arisco. Contudo, é um ótimo companheiro quando pega intimidade, não te abandonando nem durante o banho!', 'Macho', 'gato', 5, '0', '../../../imgAnimais/68e716a83ed6d-gatos.jpg', 0),
(5, 'Capitu', 'Tricolor', 'Varzea | São Paulo', 'Seu jeitinho especial a torna única e ainda mais encantadora. Ela é um exemplo de força, carinho e doçura — só precisa de alguém que enxergue além das aparências e a acolha com o coração', 'fêmea', 'gato', 7, 'cega parcial', '../../../imgAnimais/68e88100ef455-animal.06.jpg', 0),
(6, 'Apolo ', 'mestiço', 'Jundia | São Pailo', 'Dócil, tranquilo e cheio de amor pra dar, ele é o companheiro perfeito para quem busca um amigo fiel. Tudo o que deseja é um cantinho seguro para descansar e um coração humano disposto a retribuir o carinho que ele tem de sobra.', 'macho', 'cachorro', 17, 'Idoso', '../../../imgAnimais/68e885f61ad0d-cao-idosoo.jpg', 0),
(7, 'Trindade', 'cadeirante', 'Campo Limpo | São Paulo', 'Se você procura uma companheira leal, carinhosa e cheia de amor, ela é o par perfeito! Forte e doce na medida certa, está pronta para encher sua vida de afeto, alegria e momentos inesquecíveis.', 'fêmea', 'cachorro', 10, 'Cadeirante', '../../../imgAnimais/68e88652a2d08-animal07.jpg', 0),
(8, 'Chico', 'caseiro', 'Varzea | São Paulo', 'Afetuoso e surpreendentemente adaptável, ele demonstra a cada dia que as verdadeiras limitações desaparecem quando o amor está presente. Sua coragem e carinho inspiram quem o conhece, mostrando que juntos tudo é possível.', 'macho', 'cachorro', 8, 'Cego', '../../../imgAnimais/68e886b79811a-gatinho.jpg', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblanimaisestimacao`
--

CREATE TABLE `tblanimaisestimacao` (
  `id_animale` int(11) NOT NULL,
  `nome_animale` varchar(100) NOT NULL,
  `genero_animale` varchar(50) NOT NULL,
  `especie_animale` enum('gato','cachorro','outro') DEFAULT NULL,
  `idade_animale` int(20) NOT NULL,
  `condicao_saudee` enum('deficiencia motora','deficiencia visual','deficiencia auditiva','idoso') DEFAULT NULL,
  `foto_animale` varchar(200) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblanimaisestimacao`
--

INSERT INTO `tblanimaisestimacao` (`id_animale`, `nome_animale`, `genero_animale`, `especie_animale`, `idade_animale`, `condicao_saudee`, `foto_animale`, `id_usuario`) VALUES
(6, 'Amora', 'Fêmea', 'cachorro', 2, 'deficiencia motora', '../../../imgAnimais/68d67d03eb731-animal01.jpg', 22),
(17, 'amora2', 'Fêmea', 'gato', 2, 'deficiencia motora', '../../../imgAnimais/68e7294e88ef0-gatinho02.jpg', 15),
(18, 'Tieta', 'Fêmea', 'gato', 10, 'idoso', '../../../imgAnimais/68e889ddb0430-gato.jpg', 16);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblfavoritos`
--

CREATE TABLE `tblfavoritos` (
  `id` int(11) NOT NULL,
  `id_animal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_curtida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblfavoritos`
--

INSERT INTO `tblfavoritos` (`id`, `id_animal`, `id_usuario`, `data_curtida`) VALUES
(79, 1, 17, '2025-09-15 14:24:11'),
(83, 3, 17, '2025-09-16 15:12:52'),
(84, 3, 15, '2025-10-09 01:27:06'),
(85, 3, 16, '2025-10-10 04:45:15'),
(86, 4, 16, '2025-10-10 04:48:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbllocais`
--

CREATE TABLE `tbllocais` (
  `id_local` int(11) NOT NULL,
  `nome_local` varchar(255) NOT NULL,
  `descricao_local` text NOT NULL,
  `endereco_id` int(11) NOT NULL,
  `horario_abertura` varchar(50) DEFAULT NULL,
  `horario_fechamento` varchar(50) DEFAULT NULL,
  `tipo` enum('ong','veterinario','petshop') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbllocais`
--

INSERT INTO `tbllocais` (`id_local`, `nome_local`, `descricao_local`, `endereco_id`, `horario_abertura`, `horario_fechamento`, `tipo`) VALUES
(1, 'Hospital Veterinário Cão e Gato', 'O Hospital Veterinário Cão & Gato, em Jundiaí, oferece atendimento 24 horas para cães e gatos, com estrutura completa que inclui centro cirúrgico, UTI, internação, laboratório, farmácia e banco de sangue etc. Além de diversas outras áreas, como cardiologia, ortopedia e dermatologia, priorizando o bem-estar pets com atendimento de qualidade e tecnologia moderna.', 1, '07:00', '07:00', 'veterinario'),
(3, 'Agroserv', 'AgroServ é um pet shop completo que oferece rações, acessórios, medicamentos e atendimento veterinário. Conta também com serviços de banho e tosa. Podem encomendar produtos ortopédicos se não tiverem em estoque com atendimento personalizado e produtos de qualidade.', 3, '08:00', '18:30', 'petshop'),
(4, 'Petz Jundiaí Anhanguera', 'A Petz Jundiaí oferece produtos e serviços voltados ao bem-estar de todos os pets, incluindo animais idosos ou com deficiência. Conta com atendimento veterinário, banho e tosa adaptados, acessórios de apoio, alimentação especial e acompanhamento personalizado, priorizando o bem-estar dos pets.', 4, '08:00', '22:00', 'petshop'),
(5, 'DEBEA - Departamento do Bem Estar Animal', 'O DEBEA (Departamento do Bem-Estar Animal) é o órgão da Prefeitura de Jundiaí responsável pelo resgate, cuidado e adoção de cães e gatos abandonados ou vítimas de maus-tratos. Possui estrutura veterinária, abrigo temporário e projetos como o KAHU, que incentiva a adoção de animais idosos e com deficiência.', 5, '08:00', '17:00', 'ong'),
(6, 'Clínica Veterinária Dr. Cleber Cantelli', 'A Clínica Veterinária oferece atendimento geral para cães e gatos, com serviços de consultas, vacinas e cuidados básicos, conta com pet shop e farmácia veterinária integrados, comercializando medicamentos e produtos para pets. Funciona em horário comercial durante a semana e parcialmente aos sábados.', 6, '10:30', '17:00', 'veterinario'),
(7, 'Boticão Clínica Veterinária 24 horas', 'A Boticão Clínica Veterinária 24h, oferece atendimento completo e emergencial. A clínica inclui centro cirúrgico, laboratório próprio, internação e sala exclusiva para gatos. Tem serviços especializados como fisioterapia, ortopedia, reabilitação e terapias integrativas, incluindo acupuntura, fitoterapia e ozonioterapia.', 7, '07:00', '07:00', 'veterinario');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbllocal_endereco`
--

CREATE TABLE `tbllocal_endereco` (
  `id` int(11) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `cep` varchar(20) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbllocal_endereco`
--

INSERT INTO `tbllocal_endereco` (`id`, `rua`, `numero`, `bairro`, `cidade`, `cep`, `estado`, `complemento`) VALUES
(1, 'Trenton', '89', 'Parque do Colégio', 'Jundiaí', '13209-160', 'SP', ''),
(2, 'rua flor de maio parque internacional', '163', 'parque internacional', 'Campo Limpo Paulista', '13232524', 'SP', ''),
(3, 'Av. da Saudade', '435', 'Jardim Guanciale', 'Campo Limpo Paulista', '13236-070', 'SP', ''),
(4, 'Av. Dr. Adilson Rodrigues', '77', 'Jardim Samambaia', 'Jundiaí', '13211-685', 'SP', ''),
(5, 'Abrahão Farrão', '8', 'Chácara São Francisco', 'Jundiaí', '13214-792', 'SP', ''),
(6, 'Rio Jundiaí', '96', 'Jardim Santo Antonio I', 'Campo Limpo Paulista', '13232-060', 'SP', ''),
(7, 'Maria Maiolino Souza', '135', 'Centro', 'Campo Limpo Paulista', '13230-020', 'SP', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblong`
--

CREATE TABLE `tblong` (
  `id_ong` int(11) NOT NULL,
  `nome_ong` varchar(200) NOT NULL,
  `fundacao_ong` int(4) NOT NULL,
  `historia_ong` text NOT NULL,
  `foto_ong` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblong`
--

INSERT INTO `tblong` (`id_ong`, `nome_ong`, `fundacao_ong`, `historia_ong`, `foto_ong`) VALUES
(5, 'Patinhas Solidárias', 2002, 'Patinhas solidárias é uma ONG pequena, administrada por um casal que abraçou a causa anos atrás, tentando acolher o máximo de animais de rua. Eles oferecem abrigo e cuidados veterinários aos pets resgatados.', 'imgOngs/68e709baa4909-PS.jpg'),
(6, 'Clinica Cleber Cantelli', 2001, 'A clinica oferece uma gama completa de serviços, incluindo consultas, cirurgias, vacinas, exames e serviços de banho e tosa. É bem conceituada na comunidade local, com clientes destacando o cuidado, a atenção e a dedicação do médico veterinário.', 'imgOngs/68d3daa9e98f2-cleber.jpg'),
(7, 'SOS Patinhas', 2004, 'A SOS Patinhas é uma ONG dedicada à proteção e ao acolhimento de animais em situação de abandono. A organização oferece abrigo temporário, cuidados veterinários, além de garantir segurança, recuperação e a adoção responsável aos animais.', 'imgOngs/68d3db7a1549d-mukher2.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblong_enderecos`
--

CREATE TABLE `tblong_enderecos` (
  `id_endereco` int(11) NOT NULL,
  `id_ong` int(11) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `cep` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblong_enderecos`
--

INSERT INTO `tblong_enderecos` (`id_endereco`, `id_ong`, `rua`, `numero`, `complemento`, `cidade`, `estado`, `cep`) VALUES
(6, 6, 'Rio Jundiaí', '96', '', 'Campo Limpo Paulista', 'SP', '13232-060'),
(19, 7, 'Azulão', '456', '', 'Várzea Paulista', 'SP', '13221-560'),
(22, 5, 'Bela Vista', '123', '', 'Jundiaí', 'SP', '13207-780');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblong_telefones`
--

CREATE TABLE `tblong_telefones` (
  `id_telefone` int(11) NOT NULL,
  `id_ong` int(11) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `tipo_telefone` enum('comercial','celular','fax','outro') DEFAULT 'comercial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblong_telefones`
--

INSERT INTO `tblong_telefones` (`id_telefone`, `id_ong`, `telefone`, `tipo_telefone`) VALUES
(6, 6, '1140393981', 'comercial'),
(19, 7, '119628826', 'comercial'),
(22, 5, '119552250', 'comercial');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblsugestoes`
--

CREATE TABLE `tblsugestoes` (
  `check` int(11) NOT NULL,
  `conteudo` varchar(100) NOT NULL,
  `categoria_sugestao` varchar(100) NOT NULL,
  `animal` enum('gato','cachorro') NOT NULL,
  `sugestao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblsugestoes`
--

INSERT INTO `tblsugestoes` (`check`, `conteudo`, `categoria_sugestao`, `animal`, `sugestao`) VALUES
(1, 'cuidado basico', 'deficiencia motora', 'cachorro', ''),
(2, 'cuidado basico', 'deficiencia motora', 'cachorro', ''),
(3, 'cuidado basico', 'deficiencia motora', 'cachorro', ''),
(4, 'cuidado basico', 'deficiencia motora', 'gato', ''),
(5, 'cuidado basico', 'deficiencia motora', 'gato', ''),
(6, 'cuidado basico', 'deficiencia motora', 'gato', ''),
(7, 'nutricao', 'deficiencia visual', 'cachorro', ''),
(8, 'nutricao', 'deficiencia visual', 'cachorro', ''),
(9, 'nutricao', 'deficiencia visual', 'cachorro', ''),
(10, 'nutricao', 'deficiencia visual', 'gato', ''),
(11, 'nutricao', 'deficiencia visual', 'gato', ''),
(12, 'nutricao', 'deficiencia visual', 'gato', ''),
(13, 'adaptacao', 'deficiencia auditiva', 'cachorro', ''),
(14, 'adaptacao', 'deficiencia auditiva', 'cachorro', ''),
(15, 'adaptacao', 'deficiencia auditiva', 'cachorro', ''),
(16, 'adaptacao', 'deficiencia auditiva', 'gato', ''),
(17, 'adaptacao', 'deficiencia auditiva', 'gato', ''),
(18, 'adaptacao', 'deficiencia auditiva', 'gato', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblusuarios`
--

CREATE TABLE `tblusuarios` (
  `id` int(11) NOT NULL,
  `nome_usuario` varchar(100) NOT NULL,
  `email_usuario` varchar(50) NOT NULL,
  `senha_usuario` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('tutor/adotante','administrador') DEFAULT NULL,
  `status_usuario` enum('ativo','inativo','bloqueado') DEFAULT NULL,
  `foto_usuario` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblusuarios`
--

INSERT INTO `tblusuarios` (`id`, `nome_usuario`, `email_usuario`, `senha_usuario`, `tipo_usuario`, `status_usuario`, `foto_usuario`) VALUES
(15, 'talita', 'talita@gmail.com', '$2y$10$m9IdWH4SluAc9YRhFAilyemqud0voerf618aeB2ZU2RkhUHDjO0N.', 'administrador', 'ativo', '/imgUsuarios/user_15.png'),
(16, 'mayra', 'mayra@gmail.com', '$2y$10$NfPC0kfcaWnNnOI/csy7q.HRzGjhGaSd25Sa2aoAarfuWwIsciI7G', 'administrador', 'ativo', '/imgUsuarios/user_padrao.png'),
(17, 'mel', 'mel@gmail.com', '$2y$10$1ax2LytqdV4Td4gUzLe3xeVfjF66F3ig09BHYVZcovnuXjbApJP4q', 'tutor/adotante', 'ativo', '/imgUsuarios/user_padrao.png'),
(18, 'bolivia', 'bolivia@gmail.com', '$2y$10$Qs.jZtbLLhWL/p96oufnzOwzh7n8yaLfkCjuWdq2JfI24lLItqiKe', 'tutor/adotante', 'ativo', '/imgUsuarios/user_18.png'),
(20, 'amanda', 'amanda@gmail.com', '$2y$10$sKazp9iFwRicEBG/.eeOm.qePDbExwwV6SI/4cfUOPfc7rDKTikJa', 'tutor/adotante', 'ativo', '/imgUsuarios/user_20.png'),
(21, 'livia', 'livia@gmail.com', '$2y$10$nunMf.5MWHtplY1zeMtjKujyBYjqwRQ/XWG5h7aIYn6MKDcLy4LR6', 'tutor/adotante', 'ativo', '/imgUsuarios/user_21.png'),
(22, 'juca', 'juca@gmail.com', '$2y$10$3RwWmm9MOCfPidTNKCm5uerVmFR49kec/QzSrkHgGERYDE.6E2rGC', 'tutor/adotante', 'ativo', '/imgUsuarios/user_padrao.png'),
(23, 'Zeus', 'zeus@gmail.com', '$2y$10$tZNtMpaKO1Tk2x7c.efJlO66DTyeXp3dN0POSmrkF.6GvypUxviaK', 'tutor/adotante', 'ativo', '/imgUsuarios/user_padrao.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tblanimaisadocao`
--
ALTER TABLE `tblanimaisadocao`
  ADD PRIMARY KEY (`id_animal`);

--
-- Índices de tabela `tblanimaisestimacao`
--
ALTER TABLE `tblanimaisestimacao`
  ADD PRIMARY KEY (`id_animale`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Índices de tabela `tblfavoritos`
--
ALTER TABLE `tblfavoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_animal` (`id_animal`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `tbllocais`
--
ALTER TABLE `tbllocais`
  ADD PRIMARY KEY (`id_local`),
  ADD KEY `endereco_id` (`endereco_id`);

--
-- Índices de tabela `tbllocal_endereco`
--
ALTER TABLE `tbllocal_endereco`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tblong`
--
ALTER TABLE `tblong`
  ADD PRIMARY KEY (`id_ong`);

--
-- Índices de tabela `tblong_enderecos`
--
ALTER TABLE `tblong_enderecos`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `id_ong` (`id_ong`);

--
-- Índices de tabela `tblong_telefones`
--
ALTER TABLE `tblong_telefones`
  ADD PRIMARY KEY (`id_telefone`),
  ADD KEY `id_ong` (`id_ong`);

--
-- Índices de tabela `tblsugestoes`
--
ALTER TABLE `tblsugestoes`
  ADD PRIMARY KEY (`check`);

--
-- Índices de tabela `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tblanimaisadocao`
--
ALTER TABLE `tblanimaisadocao`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tblanimaisestimacao`
--
ALTER TABLE `tblanimaisestimacao`
  MODIFY `id_animale` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `tblfavoritos`
--
ALTER TABLE `tblfavoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de tabela `tbllocais`
--
ALTER TABLE `tbllocais`
  MODIFY `id_local` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tbllocal_endereco`
--
ALTER TABLE `tbllocal_endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tblong`
--
ALTER TABLE `tblong`
  MODIFY `id_ong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tblong_enderecos`
--
ALTER TABLE `tblong_enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tblong_telefones`
--
ALTER TABLE `tblong_telefones`
  MODIFY `id_telefone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tblusuarios`
--
ALTER TABLE `tblusuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tblanimaisestimacao`
--
ALTER TABLE `tblanimaisestimacao`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tblusuarios` (`id`);

--
-- Restrições para tabelas `tbllocais`
--
ALTER TABLE `tbllocais`
  ADD CONSTRAINT `tbllocais_ibfk_1` FOREIGN KEY (`endereco_id`) REFERENCES `tbllocal_endereco` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tblong_enderecos`
--
ALTER TABLE `tblong_enderecos`
  ADD CONSTRAINT `tblong_enderecos_ibfk_1` FOREIGN KEY (`id_ong`) REFERENCES `tblong` (`id_ong`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tblong_telefones`
--
ALTER TABLE `tblong_telefones`
  ADD CONSTRAINT `tblong_telefones_ibfk_1` FOREIGN KEY (`id_ong`) REFERENCES `tblong` (`id_ong`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
