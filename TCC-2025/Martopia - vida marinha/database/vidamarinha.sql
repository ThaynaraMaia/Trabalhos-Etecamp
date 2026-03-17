-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Tempo de geração: 04/12/2025 às 00:12
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
-- Banco de dados: `vidamarinha`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_postagem` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_postagem`, `id_usuario`, `texto`, `data_comentario`) VALUES
(78, 41, 15, 'omg que incrivel', '2025-10-10 12:37:36'),
(79, 52, 21, 'Amiga eu tenho alguns, procura no insta @mergulho.peixinho', '2025-10-30 00:27:27'),
(80, 49, 21, 'Amo muito a ilha Anchieta! ❤️', '2025-10-30 00:28:20'),
(81, 60, 22, 'Moça, sabe me informar o valor do ingresso? Tenho muita vontade de ir', '2025-10-30 00:37:48'),
(82, 57, 23, 'Nossa amiga que incrível! Será que encontro online?', '2025-10-30 00:43:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conscientizacao`
--

CREATE TABLE `conscientizacao` (
  `id` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `link` text DEFAULT NULL,
  `data_publicacao` datetime DEFAULT current_timestamp(),
  `id_autor` int(11) NOT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conscientizacao`
--

INSERT INTO `conscientizacao` (`id`, `titulo`, `link`, `data_publicacao`, `id_autor`, `tipo`, `categoria`, `texto`) VALUES
(1, 'Renascimento da vida marinha no litoral paulista', 'https://redeglobo.globo.com/tvmorena/noticia/o-renascimento-da-vida-marinha-no-litoral-paulista.ghtml', '2025-10-07 21:19:01', 2, 'Conscientização', 'Noticias', ''),
(2, 'PROJETO MONITORA A BIODIVERSIDADE MARINHA NO LITORAL PAULISTA', '', '2025-10-08 08:00:20', 1, 'Conscientização', 'Texto', 'Já começam a aparecer os primeiros resultados do projeto de Avaliação da efetividade do Parque Estadual Marinho da Laje de Santos e das Estações Ecológicas Tupinambás e Tupiniquins, vinculado ao Programa Costa Atlântica, promovido pela Fundação SOS Mata Atlântica. Entre os resultados iniciais, destacam-se os novos registros de ocorrência de espécies de corais, a descoberta de ambientes marinhos pouco conhecidos pela ciência e a observação de representantes da megafauna ameaçada de extinção, como baleia de bryde e raia-manta.\r\n\r\n“Ainda há muito desconhecimento sobre o fundo do oceano no litoral paulista”, comenta o Professor adjunto do Departamento de Ciências do Mar, do Campus da Baixada Santista, da Universidade Federal de São Paulo, Unifesp, Fábio Motta, coordenador do projeto. “Existem diversos santuários nesse litoral. Eles são locais de refúgio para várias espécies marinhas ainda não totalmente estudadas. Nosso trabalho de pesquisa as identifica para sua posterior preservação”. Um exemplo dessa afirmação foi a descoberta, nessa região, de um tipo de espécie de coral de águas rasas, antes só visto em águas profundas no mar do Caribe.\r\n\r\nGerenciado pela Fundação de Apoio à Universidade Federal de São Paulo, FapUnifesp, o projeto avalia a importância das áreas de proteção marinha para preservação da biodiversidade. A equipe de pesquisadores é multidisciplinar, composta por docentes do Departamento de Ciências do Mar da Unifesp, do Instituto de Biologia da Universidade Federal do Rio de Janeiro (UFRJ), e por pesquisadores da Universidade Federal do ABC (UFABC) e da Universidade Federal da Paraíba (UFPB).\r\n\r\nNa prática, as informações para a pesquisa são coletadas em Unidades de Conservação. Entre outros aspectos relevantes no trabalho de campo, verifica-se o tamanho médio da fauna marinha, a qualidade dos locais onde as espécies encontradas vivem e a cobertura dos recifes rochosos, cientificamente chamados de ambientes bentônicos. Tudo isso é feito por meio de mergulhos. “Esse projeto vai gerar base de monitoramento de longo prazo. Isso é importante para apoiar os gestores públicos na tomada de decisão relativa à conservação desses ambientes”, reflete prof. Motta.\r\n\r\nPara garantir a precisão do sistema de monitoramento, está previsto o estabelecimento de pontos fixos no fundo do mar, próximo à costa da Ilha de Alcatrazes, onde as fotografias da fauna marinha serão feitas. Para o professor Rodrigo Moura, da UFRJ, essa metodologia vai contribuir para o conhecimento sobre a taxa de crescimento dos corais e da dinâmica das macroalgas, que são muito sazonais, além de consolidar Alcatrazes como sítio de monitoramento.\r\n\r\nTodos os locais em estudo, pelo projeto, são unidades de conservação federais e estaduais de proteção integral. Na Estação Ecológica de Tupinambás e na Estação Ecológica de Tupiniquins é proibido o turismo e pesca em seu entorno. Entre elas situa-se o Parque Estadual Marinho da Laje de Santos, aberto para atividades educacionais e de turismo.'),
(3, 'No litoral norte paulista, USP tem centro dedicado à vida marinha ', 'https://www.youtube.com/embed/QPNxTKAu-Ts?si=M3pd_RZmzTDy6Ya-', '2025-10-08 08:27:57', 1, 'Conscientização', 'Videos', ''),
(4, 'Mostra reúne 60 fotografias sobre vida marinha em São Paulo', 'https://www.youtube.com/embed/9i0k_V-AOl0?si=ONWLVgYPYC9OWMzG', '2025-10-08 08:34:04', 1, 'Conscientização', 'Videos', ''),
(5, 'PLÁSTICOS no MAR ', 'https://www.youtube.com/embed/-UmOPQRpRIE?si=8MLNBdhp11RZgOEh', '2025-10-30 00:17:01', 25, 'Conscientização', 'Videos', ''),
(6, 'Canudos, como deixar de usar?', 'https://www.youtube.com/embed/z4fqJia0jjM?si=kmlGBGVkFEgbZJdN', '2025-10-30 00:39:22', 25, 'Conscientização', 'Videos', ''),
(7, 'Resíduos Farmacêuticos, mais uma ameaça aos oceanos', 'https://www.youtube.com/embed/pZtSgdVvCP4?si=58X3R3Pv90cPeInd', '2025-10-30 00:40:57', 25, 'Conscientização', 'Videos', ''),
(8, 'Lixo no ambiente marinho', 'https://www.youtube.com/embed/tlH6nXRmsjU?si=7ghBZo9LkVU5ycqr', '2025-10-30 00:42:02', 25, 'Conscientização', 'Videos', ''),
(9, 'Mícroplástico', 'https://www.youtube.com/embed/6sK3yuL8xqI?si=I4nbZk5zkk0nwZ20', '2025-10-30 00:44:14', 25, 'Conscientização', 'Videos', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudos`
--

CREATE TABLE `conteudos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `link` text DEFAULT NULL,
  `data_publicacao` datetime DEFAULT current_timestamp(),
  `id_autor` int(11) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conteudos`
--

INSERT INTO `conteudos` (`id`, `titulo`, `link`, `data_publicacao`, `id_autor`, `tipo`, `categoria`) VALUES
(15, 'Biodiversidade e Ecossistemas Bentonicos marinhos do litoral norte de São Paulo', 'https://www2.unifap.br/alexandresantiago/files/2012/12/E-BOOKBiota_1.pdf', '2025-10-06 22:48:06', 1, 'Educação', 'Artigos'),
(16, 'Peixes de Recife Rochoso: Estação Ecológica de Tupinambás - São Paulo', 'https://www.researchgate.net/publication/320211337_Peixes_de_Recife_Rochoso_Estacao_Ecologica_de_Tupinambas_-_Sao_Paulo', '2025-10-06 22:53:43', 1, 'Educação', 'Livros'),
(20, 'Brasil debaixo d\'água ', 'https://www.youtube.com/watch?v=iKxUbsG__4g&list=PL7qks14c2RedTP97kMpbl1b7d0asOsD1f&index=10', '2025-10-07 20:12:02', 1, 'Educação', 'Documentarios'),
(22, 'Á procura de nemo ', 'https://www.primevideo.com/-/pt/detail/À-Procura-de-Nemo/0TEMYUQ0G7ONMQLKTIUKANTNKP', '2025-10-07 20:17:31', 1, 'Educação', 'Filmes'),
(24, 'Descarte de Farmácos', 'https://open.spotify.com/episode/57kWjmgRfyXvQOu61Wacpi', '2025-10-07 20:21:10', 1, 'Educação', 'Podcasts'),
(25, 'Guia Prático de Identificação da Fauna Marinha (TAMAR)', 'https://www.tamar.org.br/publicacoes_html/pdf/2000/2000_Guia_Pratico_de_Identificacao.pdf', '2025-10-07 20:28:15', 1, 'Educação', 'Guias de Campo'),
(26, 'IOUSP – Noções sobre Oceanografia', 'https://www.io.usp.br/index.php/extensao/cursos/nocoes-sobre-oceanografia.html?utm_source=chatgpt.com', '2025-10-07 20:35:24', 1, 'Educação', 'Cursos'),
(27, 'Instituto Biopesca', 'http://www.biopesca.org.br', '2025-10-07 20:38:58', 1, 'Educação', 'Projetos para Conhecer'),
(28, 'O Estudo científico do mar entre ciência e política Estado, laboratórios e cient', 'https://www.scielo.br/j/vh/a/g6SxHwtVDYmsH6VXXfcCdRb/?lang=pt', '2025-10-29 22:06:57', 3, 'Educação', 'Artigos'),
(29, 'Distribuição do plâncton na região costeira de São Sebastião ', 'https://io.usp.br/images/publicacoes/n41a02_97.pdf', '2025-10-29 22:09:11', 3, 'Educação', 'Artigos'),
(30, 'AVALIAÇÃO DO ESTADO DO CONHECIMENTO DA DIVERSIDADE BIOLÓGICA DO BRASIL ', 'https://antigo.mma.gov.br/estruturas/chm/_arquivos/aguadoc1.pdf', '2025-10-29 22:12:30', 3, 'Educação', 'Artigos'),
(31, 'Ecologia de costões rochosos', 'https://periodicos.pucpr.br/estudosdebiologia/article/view/22921/22020', '2025-10-29 22:14:00', 3, 'Educação', 'Artigos'),
(32, 'Ciências do mar vol.2', 'https://www.marinha.mil.br/secirm/sites/www.marinha.mil.br.secirm/files/publicacoes/ppgmar/CienciasdoMarVol2.pdf', '2025-10-29 22:16:46', 3, 'Educação', 'Livros'),
(33, 'BIOLOGIA MARINHA - Uma Introdução às Ciências Marinhas', 'https://doceru.com/doc/x5vexsc', '2025-10-29 22:21:36', 3, 'Educação', 'Livros'),
(34, 'Biologia Marinha', 'https://www.livros1.com.br/pdf-read/livar/BIOLOGIA-MARINHA.pdf', '2025-10-29 22:25:11', 3, 'Educação', 'Livros'),
(35, 'Sobre Homens e Tubarões', 'https://livrariapublica.com.br/livros/sobre-homens-e-tubaroes-gabriel-ganme/#pdf', '2025-10-29 22:27:54', 3, 'Educação', 'Livros'),
(36, 'Bioestatística', 'https://livrariapublica.com.br/livros/bioestatistica-luciano-silva/', '2025-10-29 22:29:47', 3, 'Educação', 'Livros'),
(37, 'OCEANOS INFINITOS | Uma Jornada pelos Oceanos do Mundo', 'https://youtu.be/Nz7ffAyUVEI?si=17CaPeY5urjR_WUo', '2025-10-29 22:32:03', 3, 'Educação', 'Documentarios'),
(38, 'MESTRES DO OCEANO - Os Animais Mais Fascinantes das Profundezas', 'https://youtu.be/h-enFqXm0zI?si=V6tTQMRuIZ7CN-Mc', '2025-10-29 22:33:34', 3, 'Educação', 'Documentarios'),
(39, 'Brasil Submerso – O Oceano Que o Mundo Ignora ', 'https://youtu.be/huyTG5WsA6U?si=CJLh4PZeYBjWF_N6', '2025-10-29 22:34:52', 3, 'Educação', 'Documentarios'),
(40, 'Segredos do Pacífico: A Vida Marinha Fantástica da Polinésia Francesa', 'https://youtu.be/mQB0nOd79FA?si=8ykb2o_dlpOchJT0', '2025-10-29 22:36:04', 3, 'Educação', 'Documentarios'),
(41, 'CORAIS: Um Mundo De Cores No Fundo Do Mar ', 'https://youtu.be/jtS7LO8dxy0?si=SlROJXgmu-1LIKYG', '2025-10-29 22:38:13', 3, 'Educação', 'Documentarios'),
(42, 'A Pequena Sereia', 'https://www.disneyplus.com/pt-br/browse/entity-f7643452-fe64-4b05-8f09-c8bea9b2dd60', '2025-10-29 22:41:55', 3, 'Educação', 'Filmes'),
(43, 'Luca', 'https://www.disneyplus.com/pt-br/browse/entity-f28b825f-c207-406b-923a-67f85e6d90e0', '2025-10-29 22:43:02', 3, 'Educação', 'Filmes'),
(44, 'O Espanta Tubarões', 'https://www.primevideo.com/-/pt/detail/O-Espanta-Tubar%C3%B5es/0SC84V4MX5KWF9GIG0R8CFV92W', '2025-10-29 22:44:08', 3, 'Educação', 'Filmes'),
(45, 'No Coração do Mar', 'https://www.primevideo.com/-/pt/detail/No-Cora%C3%A7%C3%A3o-do-Mar/0O6ZA9QVGKXP9CP2EG71G6GNR2', '2025-10-29 22:45:16', 3, 'Educação', 'Filmes'),
(46, 'Winter, O Golfinho', 'https://www.primevideo.com/-/pt/detail/Winter---O-Golfinho/0PHV9KRUEP2L3Y4TLD7K8OW9B6', '2025-10-29 22:46:25', 3, 'Educação', 'Filmes'),
(47, 'Megatubarão', 'https://www.primevideo.com/-/pt/detail/Megatubar%C3%A3o/0KAF3T3G87FC0NGWAAE3DGZC2R', '2025-10-29 22:47:40', 3, 'Educação', 'Filmes'),
(48, 'O mar não está pra peixe - USP', 'https://jornal.usp.br/sinopses-podcasts/o-mar-nao-esta-pra-peixe/', '2025-10-29 22:49:08', 3, 'Educação', 'Podcasts'),
(49, 'PodMar - O Podcast do Mar', 'https://www.cembra.org.br/pt-br/podcast', '2025-10-29 22:50:18', 3, 'Educação', 'Podcasts'),
(50, 'Uma Gota no Oceano - Spotify', 'https://open.spotify.com/show/2M44DTmENoruP91mzGUOz6?si=3f6e5a3b464b4718', '2025-10-29 22:51:58', 3, 'Educação', 'Podcasts'),
(51, 'Biologia Marinha - Instituto Bióicos - Spotify', 'https://open.spotify.com/show/5lM5JIMG4QwsVdC2hLeUUq', '2025-10-29 22:53:39', 3, 'Educação', 'Podcasts'),
(52, 'EVOLUÇÃO DA VIDA MARINHA - Ciência Sem Fim', 'https://www.youtube.com/live/DyysgAOuPZA?si=lrup3pdecPZnl0Z3', '2025-10-29 22:54:55', 3, 'Educação', 'Podcasts'),
(53, 'Recifes profundos: biodiversidade marinha única e desconhecida', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-recifes-profundos-biodiversidade-marinha-unica-e-desconhecida/files/Folheto_Recifes_Profundos.pdf', '2025-10-29 22:58:37', 3, 'Educação', 'Guias de Campo'),
(54, 'Engenheiros do Oceano: corais tropicais do Brasil', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-engenheiros-do-oceano-corais-tropicais-do-brasil/files/Folheto_Corais_Tropicais_do_Brasil.pdf', '2025-10-29 23:10:34', 3, 'Educação', 'Guias de Campo'),
(55, 'Peixes recifais: aquarelas vivas', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-peixes-recifais-aquarelas-vivas-2ed/files/Folheto-Peixes-Recifais-CEBIMarUSP.pdf', '2025-10-29 23:11:07', 3, 'Educação', 'Guias de Campo'),
(56, 'Costão rochoso: a vida entre o mar e a terra', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-costao-rochoso-a-vida-entre-o-mar-e-a-terra-2ed/files/folheto_costao_rochoso_2ed.pdf', '2025-10-29 23:13:06', 3, 'Educação', 'Guias de Campo'),
(57, 'Recifes artificias', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-recifes-artificias/files/folheto-recifes-artificiais.pdf', '2025-10-29 23:13:51', 3, 'Educação', 'Guias de Campo'),
(58, 'Plâncton: pequenos gigantes', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-textos-didaticos-e-e-books-folheto-plancton-pequenos-gigantes-2ed/files/Folheto_Plancton_2ed.pdf', '2025-10-29 23:14:50', 3, 'Educação', 'Guias de Campo'),
(59, 'Bioincrustação', 'https://cebimar.usp.br/media/uploads/content/acervo-e-comunicacao-folhetos-educativos-folheto-bioincrustacao/files/Folheto_Bioincrustacao.pdf', '2025-10-29 23:16:05', 3, 'Educação', 'Guias de Campo'),
(60, 'Guia de identificação das principais espécies de raias e tubarões do Atlântico o', 'https://horizon.documentation.ird.fr/exl-doc/pleins_textes/divers18-05/010072287.pdf', '2025-10-29 23:19:36', 3, 'Educação', 'Guias de Campo'),
(61, 'Recifes artificiais e suas consequências para a vida marinha', 'https://repositorio.usp.br/item/003221618?utm_source=chatgpt.com', '2025-10-29 23:22:50', 3, 'Educação', 'Artigos'),
(62, 'Ciências Biológicas (Bacharelado e Licenciatura) - USP', 'https://www5.usp.br/ensino/graduacao/cursos-oferecidos/ciencias-biologicas/', '2025-10-29 23:26:15', 3, 'Educação', 'Cursos'),
(63, 'Ciências Biológicas (Bacharelado) - UNESP', 'https://www.clp.unesp.br/#!/graduacao/ciencias-biologicas---bacharelado/curso/', '2025-10-29 23:28:14', 3, 'Educação', 'Cursos'),
(64, 'Ciências Biológicas (Licenciatura) - UNESP', 'https://www.clp.unesp.br/#!/graduacao/ciencias-biologicas---licenciatura/curso/', '2025-10-29 23:29:10', 3, 'Educação', 'Cursos'),
(65, 'Ciências Biológicas - UNICAMP', 'https://upa.unicamp.br/ciencias-biologicas/', '2025-10-29 23:30:43', 3, 'Educação', 'Cursos'),
(66, 'Ciências Biológicas (Graduação) - UNIFESP', 'https://site.unifesp.br/cb/sobre/o-curso', '2025-10-29 23:32:49', 3, 'Educação', 'Cursos'),
(67, 'Ciências Biológicas - PUC', 'https://www.pucsp.br/sites/default/files/download/graduacao/cursos/cienciasbiologicas/c_biologicas.pdf?utm_source=chatgpt.com', '2025-10-29 23:34:08', 3, 'Educação', 'Cursos'),
(68, 'Ciências Biológicas (Bacharelado) - UNIP', 'https://www.unip.br/cursos/graduacao/tradicionais/ciencias_biologicas.aspx?utm_source=chatgpt.com', '2025-10-29 23:35:36', 3, 'Educação', 'Cursos'),
(69, 'Ciências Biológicas (Licenciatura) - UNIP', 'https://www.unip.br/cursos/graduacao/tradicionais/ciencias_biologicas_licenciatura.aspx?utm_source=chatgpt.com', '2025-10-29 23:36:35', 3, 'Educação', 'Cursos'),
(70, 'Ciências Biológicas (Licenciatura EAD) - UNISANTA', 'https://unisanta.br/graduacao/licenciatura-em-ciencias-biologicas/?utm_source=chatgpt.com', '2025-10-29 23:37:55', 3, 'Educação', 'Cursos'),
(71, 'Gestão Ambiental (Bacharelado) - USP', 'https://www.esalq.usp.br/graduacao/cursos/gestao-ambiental?utm_source=chatgpt.com', '2025-10-29 23:40:06', 3, 'Educação', 'Cursos'),
(72, 'Gestão Ambiental (Bacharelado) - FATEC', 'https://vestibular.fatec.sp.gov.br/unidades-cursos/curso.asp?c=226', '2025-10-29 23:41:36', 3, 'Educação', 'Cursos'),
(73, 'Gestão Ambiental (Tecnólogo) - UNIP', 'https://www.unip.br/cursos/graduacao/tecnologicos/gestao_ambiental.aspx?utm_source=chatgpt.com', '2025-10-29 23:42:53', 3, 'Educação', 'Cursos'),
(74, 'Gestão Ambiental (Técnologo) - Instituto Vianna Júnior', 'https://www.vianna.edu.br/gestao-ambiental/?utm_source=chatgpt.com', '2025-10-29 23:44:35', 3, 'Educação', 'Cursos'),
(75, 'Oceanografia (Bacharelado) - USP', 'https://www.io.usp.br/index.php/graduacao/bacharelado-iousp.html?utm_source=chatgpt.com', '2025-10-29 23:45:46', 3, 'Educação', 'Cursos'),
(76, 'Oceanografia (Bacharelado) - UNIFESP', 'https://unifesp.br/campus/san7/images/segrad-imar/PPC/PPC_Oceanografia_2024.pdf?utm_source=chatgpt.com', '2025-10-29 23:46:40', 3, 'Educação', 'Cursos'),
(77, 'Poluição nos Oceanos, Proteção da Vida Marinha,Sustentabilidade', 'https://youtu.be/gr5KPicyEmk?si=BKa-SjlrWmlcvmWo', '2025-10-29 23:48:26', 3, 'Educação', 'Podcasts'),
(78, 'Segredos do Recife | O Império Escondido que Precisa ser Preservado', 'https://youtu.be/fF8POBnDjs8?si=OBPP69jT0V_3G4XJ', '2025-10-29 23:50:25', 3, 'Educação', 'Podcasts'),
(79, 'Projeto Maui', 'https://projetomaui.com.br/', '2025-10-29 23:51:39', 3, 'Educação', 'Projetos para Conhecer'),
(80, 'Projeto Bióicos', 'https://www.bioicos.org.br/online?https://www.bioicos.org.br/online&gad_source=1&gad_campaignid=12978250076&gbraid=0AAAAABaJMoDCAsugo7SFMP5hw-dDkNH8p&gclid=Cj0KCQjw9obIBhCAARIsAGHm1mTzPUnUX99Szi_ytuv87_JE84s4fNqyuk-cHC3uNQ9CL2a9s2cFo7oaAqekEALw_wcB', '2025-10-29 23:53:27', 3, 'Educação', 'Projetos para Conhecer'),
(81, 'Projeto Tamar', 'https://www.tamar.org.br/', '2025-10-29 23:55:27', 3, 'Educação', 'Projetos para Conhecer'),
(82, 'Programa Bandeira Azul', 'https://bandeiraazul.org.br/', '2025-10-29 23:56:44', 3, 'Educação', 'Projetos para Conhecer'),
(83, 'Projeto Coral Vivo', 'https://coralvivo.org.br/quem-somos/', '2025-10-29 23:58:34', 3, 'Educação', 'Projetos para Conhecer'),
(84, 'Projeto Golfinho', 'https://www.caesb.df.gov.br/projeto-golfinho/', '2025-10-30 00:00:33', 3, 'Educação', 'Projetos para Conhecer'),
(85, 'Programa Guardiões do Mar', 'https://guardioesdomar.org.br/', '2025-10-30 00:01:59', 3, 'Educação', 'Projetos para Conhecer');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id` int(11) NOT NULL,
  `id_postagem` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_curtida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`id`, `id_postagem`, `id_usuario`, `data_curtida`) VALUES
(55, 64, 23, '2025-10-30 00:42:39'),
(56, 62, 23, '2025-10-30 00:42:41'),
(57, 61, 23, '2025-10-30 00:42:42'),
(58, 58, 23, '2025-10-30 00:42:44'),
(59, 57, 23, '2025-10-30 00:42:47'),
(60, 55, 23, '2025-10-30 00:42:49'),
(61, 52, 23, '2025-10-30 00:42:51'),
(62, 48, 23, '2025-10-30 00:42:55'),
(63, 47, 23, '2025-10-30 00:42:57'),
(64, 67, 24, '2025-10-30 00:53:51'),
(65, 66, 24, '2025-10-30 00:53:53'),
(66, 65, 24, '2025-10-30 00:53:56'),
(67, 63, 24, '2025-10-30 00:53:58'),
(68, 61, 24, '2025-10-30 00:53:59'),
(69, 58, 24, '2025-10-30 00:54:01'),
(70, 57, 24, '2025-10-30 00:54:03'),
(71, 54, 24, '2025-10-30 00:54:04'),
(72, 53, 24, '2025-10-30 00:54:06'),
(73, 51, 24, '2025-10-30 00:54:08'),
(75, 50, 24, '2025-10-30 00:54:11'),
(76, 49, 24, '2025-10-30 00:54:14'),
(77, 48, 24, '2025-10-30 00:54:16'),
(78, 47, 24, '2025-10-30 00:54:18'),
(79, 46, 24, '2025-10-30 00:54:19'),
(80, 41, 24, '2025-10-30 00:54:24'),
(81, 70, 24, '2025-10-30 00:54:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `denuncias`
--

CREATE TABLE `denuncias` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `data_denuncia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `denuncias`
--

INSERT INTO `denuncias` (`id`, `id_usuario`, `id_post`, `data_denuncia`) VALUES
(20, 12, 41, '2025-10-29 23:56:35'),
(22, 24, 67, '2025-10-30 00:56:25'),
(23, 12, 74, '2025-10-30 00:58:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `denuncias_comentarios`
--

CREATE TABLE `denuncias_comentarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  `data_denuncia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `denuncias_comentarios`
--

INSERT INTO `denuncias_comentarios` (`id`, `id_usuario`, `id_comentario`, `data_denuncia`) VALUES
(5, 6, 82, '2025-10-31 10:08:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_artigos`
--

CREATE TABLE `imagens_artigos` (
  `id` int(11) NOT NULL,
  `id_artigo` int(11) NOT NULL,
  `caminho_img` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `imagens_artigos`
--

INSERT INTO `imagens_artigos` (`id`, `id_artigo`, `caminho_img`, `legenda`) VALUES
(5, 20, '1759878722_68e59e42e01a7.png', ''),
(7, 22, '1759879051_68e59f8b5fc4b.png', ''),
(9, 26, '1759880124_68e5a3bcee97b.png', ''),
(10, 27, '1759880338_68e5a492c5252.png', ''),
(11, 28, '1761786417_6902ba316ecb2.png', ''),
(12, 29, '1761786551_6902bab7c96bc.jpg', ''),
(13, 30, '1761786750_6902bb7e1d6f9.jpg', ''),
(14, 31, '1761786840_6902bbd8cf67b.png', ''),
(15, 32, '1761787006_6902bc7e1c36e.jpg', ''),
(16, 33, '1761787296_6902bda053489.png', ''),
(17, 37, '1761787923_6902c013d1538.jpg', ''),
(18, 38, '1761788014_6902c06eed1e8.jpg', ''),
(19, 39, '1761788092_6902c0bc9c8f3.jpg', ''),
(20, 40, '1761788164_6902c10411ac8.jpg', ''),
(21, 43, '1761788582_6902c2a676e6d.jpeg', ''),
(22, 45, '1761788716_6902c32cbaabd.jpg', ''),
(23, 46, '1761788785_6902c3716c9c6.jpg', ''),
(24, 47, '1761788860_6902c3bcd4a2c.png', ''),
(25, 48, '1761788948_6902c414b6fef.jpg', ''),
(26, 49, '1761789018_6902c45a38c19.jpg', ''),
(27, 50, '1761789118_6902c4bec792b.jpg', ''),
(28, 51, '1761789219_6902c5235da93.png', ''),
(29, 52, '1761789295_6902c56f7064b.jpg', ''),
(30, 53, '1761789517_6902c64def791.jpg', ''),
(31, 54, '1761790234_6902c91a45816.jpg', ''),
(32, 55, '1761790267_6902c93bc8c3d.jpg', ''),
(33, 56, '1761790386_6902c9b2d1710.jpg', ''),
(34, 57, '1761790431_6902c9dfc94e8.jpg', ''),
(35, 58, '1761790490_6902ca1a2944a.jpg', ''),
(36, 59, '1761790565_6902ca65d9f0a.jpg', ''),
(37, 60, '1761790776_6902cb38adc63.png', ''),
(38, 61, '1761790970_6902cbfa7395a.jpg', ''),
(39, 62, '1761791175_6902ccc7013f3.jpg', ''),
(40, 63, '1761791294_6902cd3e3094d.png', ''),
(41, 64, '1761791350_6902cd7679660.png', ''),
(42, 65, '1761791443_6902cdd3dea21.png', ''),
(43, 66, '1761791569_6902ce51cdc43.png', ''),
(44, 67, '1761791648_6902cea06e6ec.png', ''),
(45, 68, '1761791736_6902cef8e85fe.png', ''),
(46, 69, '1761791795_6902cf33349de.png', ''),
(47, 70, '1761791875_6902cf8392d29.png', ''),
(48, 71, '1761792006_6902d006b2fb1.jpg', ''),
(49, 72, '1761792096_6902d060e4685.jpg', ''),
(50, 73, '1761792173_6902d0ad069b9.png', ''),
(51, 74, '1761792275_6902d113bd31b.jpg', ''),
(52, 75, '1761792346_6902d15a1b086.jpg', ''),
(53, 76, '1761792400_6902d19048cb6.png', ''),
(54, 78, '1761792625_6902d27130d96.jpg', ''),
(55, 79, '1761792699_6902d2bb0c190.png', ''),
(56, 80, '1761792807_6902d327eaef9.png', ''),
(57, 81, '1761792927_6902d39f20cb5.png', ''),
(58, 82, '1761793004_6902d3ec3aee9.jpg', ''),
(59, 83, '1761793114_6902d45a653fb.png', ''),
(60, 84, '1761793233_6902d4d17586c.png', ''),
(61, 85, '1761793319_6902d5270930e.jpg', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `img_conscientizacao`
--

CREATE TABLE `img_conscientizacao` (
  `id` int(11) NOT NULL,
  `id_artigo` int(11) NOT NULL,
  `caminho_img` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `img_conscientizacao`
--

INSERT INTO `img_conscientizacao` (`id`, `id_artigo`, `caminho_img`, `legenda`) VALUES
(5, 1, '1759882741_68e5adf5d34a2.png', '\'Globo Repórter\' mostra o renascimento da vida marinha no litoral paulista — Foto: Globo/divulgação'),
(6, 2, '1759921220_68e64444b0bad.png', 'logo da fundação de apoio á universidade federal de são paulo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `leituras`
--

CREATE TABLE `leituras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_artigo` int(11) DEFAULT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_conscientizacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `leituras`
--

INSERT INTO `leituras` (`id`, `id_usuario`, `id_artigo`, `data_leitura`, `id_conscientizacao`) VALUES
(15, 15, 15, '2025-10-08 22:57:31', NULL),
(23, 15, NULL, '2025-10-09 13:22:05', 1),
(24, 6, 16, '2025-10-10 11:12:09', NULL),
(25, 17, NULL, '2025-10-10 12:13:52', 1),
(26, 12, 20, '2025-10-10 13:58:33', NULL),
(27, 12, 25, '2025-10-28 02:27:37', NULL),
(28, 19, 15, '2025-10-30 00:11:59', NULL),
(29, 19, 16, '2025-10-30 00:12:28', NULL),
(30, 19, 25, '2025-10-30 00:12:47', NULL),
(31, 20, 27, '2025-10-30 00:21:47', NULL),
(32, 20, 25, '2025-10-30 00:22:04', NULL),
(33, 20, 24, '2025-10-30 00:22:24', NULL),
(34, 20, 20, '2025-10-30 00:22:46', NULL),
(35, 22, 24, '2025-10-30 00:40:11', NULL),
(36, 22, 16, '2025-10-30 00:40:29', NULL),
(37, 22, 26, '2025-10-30 00:40:49', NULL),
(38, 23, 20, '2025-10-30 00:49:17', NULL),
(39, 6, 61, '2025-10-31 13:54:52', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id_notificacao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_comentario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id_notificacao`, `id_usuario`, `id_post`, `mensagem`, `lida`, `data_envio`, `id_comentario`) VALUES
(10, 24, 74, 'Sua postagem foi denunciada e está sob análise por violar as diretrizes da comunidade.', 0, '2025-10-30 01:02:08', NULL),
(12, 23, 67, 'Sua postagem foi denunciada e está sob análise por violar as diretrizes da comunidade.', 0, '2025-10-31 13:57:46', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pergunta`
--

CREATE TABLE `pergunta` (
  `id_pergunta` int(11) NOT NULL,
  `turista_id` int(11) NOT NULL,
  `texto` text NOT NULL,
  `marcada_como_frequente` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas_quiz`
--

CREATE TABLE `perguntas_quiz` (
  `id` int(11) NOT NULL,
  `id_biologo` int(11) DEFAULT NULL,
  `pergunta` text NOT NULL,
  `opcao_a` varchar(255) NOT NULL,
  `opcao_b` varchar(255) NOT NULL,
  `opcao_c` varchar(255) NOT NULL,
  `opcao_d` varchar(255) NOT NULL,
  `resposta` char(1) NOT NULL,
  `dificuldade` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perguntas_quiz`
--

INSERT INTO `perguntas_quiz` (`id`, `id_biologo`, `pergunta`, `opcao_a`, `opcao_b`, `opcao_c`, `opcao_d`, `resposta`, `dificuldade`) VALUES
(1, 1, 'Qual é o maior animal do mundo?', 'Elefante africano', 'Baleia-azul', 'Tubarão-baleia', 'Girafa', 'B', 1),
(2, 2, 'O que são os corais?', 'Plantas marinhas', 'Animais invertebrados', 'Algas calcárias', 'Fungos marinhos', 'B', 1),
(3, 3, 'Qual peixe é conhecido por sua forma achatada e cauda venenosa?', 'Peixe-palhaço', 'Arraia', 'Cavalo-marinho', 'Moreia', 'B', 1),
(4, 1, 'O que a lula usa para se locomover rapidamente?', 'Barbatanas', 'Tentáculos', 'Jato propulsão', 'Nado ondulatório', 'C', 1),
(5, 2, 'Qual destes é um mamífero marinho?', 'Tubarão', 'Raia', 'Golfinho', 'Polvo', 'C', 1),
(6, 3, 'O que as baleias cospem para respirar?', 'Água', 'Sangue', 'Vapor d\'água', 'Ar', 'C', 1),
(7, 1, 'Qual é a função das brânquias nos peixes?', 'Digestão', 'Respiração', 'Locomoção', 'Reprodução', 'B', 1),
(8, 2, 'O que é o plâncton?', 'Organismos do fundo do mar', 'Organismos que nadam ativamente', 'Organismos que flutuam na água', 'Plantas marinhas', 'C', 1),
(9, 3, 'Qual destes animais é um crustáceo?', 'Polvo', 'Estrela-do-mar', 'Caranguejo', 'Água-viva', 'C', 1),
(10, 1, 'O que significa a palavra \"cetáceo\"?', 'Peixe grande', 'Mamífero marinho', 'Réptil marinho', 'Ave marinha', 'B', 1),
(11, 2, 'Qual é o principal alimento das baleias-jubarte?', 'Peixes grandes', 'Krill', 'Algas', 'Outras baleias', 'B', 1),
(12, 3, 'O que são os tentáculos do polvo?', 'Patas para andar', 'Estruturas sensoriais e de captura', 'Brânquias', 'Olhos', 'B', 1),
(13, 1, 'Qual destes peixes vive em recifes de coral?', 'Salmão', 'Atum', 'Peixe-palhaço', 'Bacalhau', 'C', 1),
(14, 2, 'O que é a ecdise nos crustáceos?', 'Reprodução', 'Troca de concha', 'Alimentação', 'Natação', 'B', 1),
(15, 3, 'Qual é o maior peixe do mundo?', 'Tubarão-branco', 'Tubarão-tigre', 'Tubarão-baleia', 'Tubarão-martelo', 'C', 1),
(16, 1, 'Qual fenômeno é responsável pela bioluminescência em muitos organismos marinhos?', 'Fotossíntese', 'Quimioluminescência', 'Bioluminescência', 'Fosforescência', 'C', 2),
(17, 2, 'O que é a zona afótica?', 'Zona de marés', 'Zona sem luz', 'Zona de recifes', 'Zona superficial', 'B', 2),
(18, 3, 'Qual destes peixes possui um sistema de eletrorrecepção?', 'Tubarão', 'Salmão', 'Atum', 'Baiacu', 'A', 2),
(19, 1, 'O que é o sifão nos cefalópodes?', 'Estrutura digestiva', 'Órgão reprodutor', 'Estrutura de propulsão', 'Sensor químico', 'C', 2),
(20, 2, 'Qual é a função dos estatocistos nos cnidários?', 'Digestão', 'Equilíbrio', 'Respiração', 'Reprodução', 'B', 2),
(21, 3, 'O que significa o termo \"ictiofauna\"?', 'Flora marinha', 'Fauna de peixes', 'Plâncton animal', 'Microorganismos', 'B', 2),
(22, 1, 'Qual destes é um exemplo de simbiose no recife de coral?', 'Tubarão e raia', 'Peixe-palhaço e anêmona', 'Polvo e lula', 'Baleia e krill', 'B', 2),
(23, 2, 'O que é a ressurgência?', 'Subida de águas profundas', 'Descida de águas superficiais', 'Corrente equatorial', 'Maré alta', 'A', 2),
(24, 3, 'Qual a principal ameaça aos recifes de coral?', 'Pesca predatória', 'Aquecimento global', 'Poluição sonora', 'Turismo excessivo', 'B', 2),
(25, 1, 'O que são os pólipos nos corais?', 'Estruturas reprodutivas', 'Unidades individuais do coral', 'Tentáculos de alimentação', 'Raízes de fixação', 'B', 2),
(26, 2, 'Qual destes animais realiza migração mais longa?', 'Tubarão-branco', 'Baleia-cinzenta', 'Atum-azul', 'Tartaruga-de-couro', 'D', 2),
(27, 3, 'O que é o fenômeno de El Niño?', 'Resfriamento do Pacífico', 'Aquecimento do Pacífico', 'Corrente do Golfo', 'Monção asiática', 'B', 2),
(28, 1, 'Qual a função dos ctenóforos na cadeia alimentar?', 'Produtores primários', 'Consumidores primários', 'Decompositores', 'Predadores de topo', 'B', 2),
(29, 2, 'O que é a camuflagem críptica?', 'Mimetismo sonoro', 'Camuflagem por cores', 'Camuflagem por forma', 'Bioluminescência', 'B', 2),
(30, 3, 'Qual destes é um peixe abissal?', 'Peixe-lua', 'Peixe-bruxa', 'Peixe-voador', 'Peixe-palhaço', 'B', 2),
(31, 1, 'Qual a principal adaptação dos peixes abissais à pressão hidrostática?', 'Esqueleto calcificado', 'Tecidos ricos em lipídios', 'Ausência de bexiga natatória', 'Corpo achatado', 'C', 3),
(32, 2, 'O que é a simbiose entre zooxantelas e corais?', 'Comensalismo', 'Mutualismo', 'Parasitismo', 'Predatismo', 'B', 3),
(33, 3, 'Qual enzima permite aos tubarões detectar sangue na água?', 'Amilase', 'Tripsina', 'Látex', 'Olfatina', 'D', 3),
(34, 1, 'O que é a migração vertical diária do zooplâncton?', 'Movimento para fugir de predadores', 'Busca por alimento', 'Resposta à maré', 'Adaptação à temperatura', 'A', 3),
(35, 2, 'Qual a função dos amebócitos nos poríferos?', 'Digestão', 'Distribuição de nutrientes', 'Reprodução', 'Defesa', 'B', 3),
(36, 3, 'O que é o sistema Haversiano nos ossos de mamíferos marinhos?', 'Sistema respiratório', 'Sistema vascular ósseo', 'Sistema nervoso', 'Sistema digestivo', 'B', 3),
(37, 1, 'Qual a adaptação das aves marinhas para excretar sal?', 'Glândulas sudoríparas', 'Glândulas de sal', 'Rins especializados', 'Pele impermeável', 'B', 3),
(38, 2, 'O que é a teoria endossimbióntica aplicada aos cloroplastos?', 'Origem bacteriana', 'Origem viral', 'Origem fúngica', 'Origem animal', 'A', 3),
(39, 3, 'Qual a importância dos manguezais para o ecossistema?', 'Produção de oxigênio', 'Proteção costeira', 'Fixação de nitrogênio', 'Sequestro de carbono', 'B', 3),
(40, 1, 'O que é a bomba de carbono biológica?', 'Fixação por fitoplâncton', 'Respiração animal', 'Decomposição bacteriana', 'Fotossíntese de algas', 'A', 3),
(41, 2, 'Qual destes é um indicador de saúde de recifes de coral?', 'Quantidade de peixes', 'Cobertura coral viva', 'Temperatura da água', 'Profundidade do recife', 'B', 3),
(42, 3, 'O que é o fenômeno de branqueamento de corais?', 'Morte do coral', 'Perda de zooxantelas', 'Crescimento acelerado', 'Reprodução em massa', 'B', 3),
(43, 1, 'Qual a adaptação dos peixes das cavernas à escuridão?', 'Desenvolvimento de bioluminescência', 'Redução dos olhos', 'Aumento das barbatanas', 'Mudança de coloração', 'B', 3),
(44, 2, 'O que é a neurotoxina da água-viva Chironex fleckeri?', 'Tetrodotoxina', 'Saxitoxina', 'Ciguatoxina', 'Maitotoxina', 'B', 3),
(45, 3, 'Qual a principal causa da zona morta do Golfo do México?', 'Derramamento de petróleo', 'Eutrofização', 'Aquecimento global', 'Pesca excessiva', 'B', 3),
(46, 1, 'O aumento da acidez dos oceanos, causado pelo CO2 atmosférico, afeta principalmente:', 'A cadeia alimentar pelágica', 'Organismos com conchas calcárias', 'Mamíferos marinhos', 'Peixes de recife', 'B', 4),
(47, 2, 'A sobrepesca do atum-azul no Atlântico é um exemplo de:', 'Tragédia dos comuns', 'Seleção artificial', 'Sucessão ecológica', 'Mutualismo', 'A', 4),
(48, 3, 'O fenômeno de magnificação trófica afeta mais intensamente:', 'Produtores primários', 'Consumidores primários', 'Consumidores terciários', 'Decompositores', 'C', 4),
(49, 1, 'A introdução do coral-sol no Brasil exemplifica:', 'Sucessão ecológica', 'Bioinvasão', 'Mutualismo', 'Coevolução', 'B', 4),
(50, 2, 'A criação de unidades de conservação marinhas visa principalmente:', 'Aumentar o turismo', 'Proteger biodiversidade', 'Regular a pesca', 'Promover pesquisa', 'B', 4),
(51, 3, 'O derramamento de petróleo no mar causa danos principalmente por:', 'Redução de oxigênio', 'Toxidade aos organismos', 'Aumento de temperatura', 'Mudança de salinidade', 'B', 4),
(52, 1, 'A pesca de arrasto é criticada por causar:', 'Seleção artificial', 'Destruição de habitat', 'Aumento de CO2', 'Redução de plâncton', 'B', 4),
(53, 2, 'O aquecimento global afeta os recifes de coral principalmente através:', 'Aumento do nível do mar', 'Branqueamento de corais', 'Acidificação dos oceanos', 'Mudanças nas correntes', 'B', 4),
(54, 3, 'A maricultura sustentável deve priorizar:', 'Espécies de alto valor', 'Espécies nativas', 'Espécies exóticas', 'Espécies de rápido crescimento', 'B', 4),
(55, 1, 'O protocolo de Nagoya trata principalmente de:', 'Pesca internacional', 'Recursos genéticos', 'Poluição marinha', 'Mudanças climáticas', 'B', 4),
(56, 2, 'A zona econômica exclusiva (ZEE) brasileira é importante para:', 'Exploração mineral', 'Pesca industrial', 'Turismo internacional', 'Defesa militar', 'B', 4),
(57, 3, 'O lixo plástico nos oceanos forma as chamadas:', 'Ilhas de plástico', 'Zonas de acumulação', 'Vórtices de detritos', 'Manchas de poluição', 'C', 4),
(58, 1, 'A conservação dos manguezais é crucial para:', 'Produção de oxigênio', 'Proteção costeira', 'Pesca esportiva', 'Turismo de luxo', 'B', 4),
(59, 2, 'O conceito de desenvolvimento sustentável aplicado aos oceanos prevê:', 'Exploração máxima', 'Preservação integral', 'Uso equilibrado', 'Proteção total', 'C', 4),
(60, 3, 'A poluição por microplásticos representa risco porque:', 'Altera a salinidade', 'Entra na cadeia alimentar', 'Reduz a transparência', 'Aumenta a temperatura', 'B', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontos_leituras`
--

CREATE TABLE `pontos_leituras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_artigo` int(11) DEFAULT NULL,
  `pontos` int(11) DEFAULT 10,
  `tipo` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_conscientizacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pontos_leituras`
--

INSERT INTO `pontos_leituras` (`id`, `id_usuario`, `id_artigo`, `pontos`, `tipo`, `categoria`, `data_registro`, `id_conscientizacao`) VALUES
(3, 15, 15, 5, 'Educação', 'Artigos', '2025-10-08 22:57:31', NULL),
(4, 15, NULL, 3, 'Conscientização', 'Noticias', '2025-10-09 13:22:05', 1),
(5, 6, 16, 5, 'Educação', 'Livros', '2025-10-10 11:12:09', NULL),
(6, 17, NULL, 3, 'Conscientização', 'Noticias', '2025-10-10 12:13:52', 1),
(7, 12, 20, 5, 'Educação', 'Documentarios', '2025-10-10 13:58:33', NULL),
(8, 12, 25, 5, 'Educação', 'Guias de Campo', '2025-10-28 02:27:37', NULL),
(9, 19, 15, 5, 'Educação', 'Artigos', '2025-10-30 00:11:59', NULL),
(10, 19, 16, 5, 'Educação', 'Livros', '2025-10-30 00:12:28', NULL),
(11, 19, 25, 5, 'Educação', 'Guias de Campo', '2025-10-30 00:12:47', NULL),
(12, 20, 27, 5, 'Educação', 'Projetos para Conhecer', '2025-10-30 00:21:47', NULL),
(13, 20, 25, 5, 'Educação', 'Guias de Campo', '2025-10-30 00:22:04', NULL),
(14, 20, 24, 5, 'Educação', 'Podcasts', '2025-10-30 00:22:24', NULL),
(15, 20, 20, 5, 'Educação', 'Documentarios', '2025-10-30 00:22:46', NULL),
(16, 22, 24, 5, 'Educação', 'Podcasts', '2025-10-30 00:40:11', NULL),
(17, 22, 16, 5, 'Educação', 'Livros', '2025-10-30 00:40:29', NULL),
(18, 22, 26, 5, 'Educação', 'Cursos', '2025-10-30 00:40:49', NULL),
(19, 23, 20, 5, 'Educação', 'Documentarios', '2025-10-30 00:49:17', NULL),
(20, 6, 61, 5, 'Educação', 'Artigos', '2025-10-31 13:54:52', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `postagens`
--

CREATE TABLE `postagens` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `legenda` varchar(150) DEFAULT NULL,
  `data_postagem` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `img` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `postagens`
--

INSERT INTO `postagens` (`id`, `id_usuario`, `legenda`, `data_postagem`, `img`) VALUES
(41, 17, 'oiiiiii', '2025-10-10 12:04:53', ''),
(46, 12, 'Hoje na praia me entregaram esse saquinho de lixo reciclável, achei muito legal as informações de conscientização! ', '2025-10-29 23:56:08', '30f2654e59e3294f9cb8c232071092c3.png'),
(47, 12, 'Gente, hoje no aquário eu vi esse peixinho igual a Dory, alguém sabe qual o nome dele? ', '2025-10-29 23:57:32', '04b60373b3bd5a6740da3929280de1f4.png'),
(48, 12, 'Passeio de barco por Ubatuba com esperanças de ver as baleias 😊', '2025-10-29 23:58:24', '8a408e5e6d7ef12f0b5559a72699353c.png'),
(49, 19, 'Amo o mar do litoral norte! Consegui ver arraias nas quase na areia hoje ❤️', '2025-10-30 00:01:22', 'aabb8242ee26c2c30751309804d95a9c.png'),
(50, 19, 'Pessoal, vou viajar pela primeira vez para a Praia Grande, alguém tem indicações de lugares bons para passear por lá? ', '2025-10-30 00:02:19', ''),
(51, 19, '📍Mirante do Camaroeiro em Caraguatatuba', '2025-10-30 00:04:29', '01586abdc53c733a855d6d02e29ce6dc.png'),
(52, 19, 'Alguém tem contatos de lugares que fazem mergulho com snorkel no Guarujá? ', '2025-10-30 00:06:25', ''),
(53, 20, 'Se esse bichinho fosse uma pessoa seria um senhorzinho simpático kkkkk', '2025-10-30 00:15:26', '367b1ba74e8b1ec21ce7896a096c2f23.png'),
(54, 20, 'Sabia que o polvo tem três corações e sangue azul? 💙🐙', '2025-10-30 00:17:09', ''),
(55, 20, 'As tartarugas voltam pra mesma praia onde nasceram pra colocar seus ovos. 🐢💛', '2025-10-30 00:17:35', ''),
(56, 20, 'Vista do píer de Itaguá a noite 🩵', '2025-10-30 00:18:23', 'a5549c35c83d9227e8de7da40b671938.png'),
(57, 21, 'Gente, olha que legal esse resumão de biologia marinha! Achei muito completo 🥰', '2025-10-30 00:25:20', '063eb9abb097cda0b690a9d3046f31bc.png'),
(58, 21, 'Cuidar do oceano é cuidar da nossa própria casa.', '2025-10-30 00:25:51', ''),
(59, 21, 'Cada pedacinho de plástico que cai no chão pode acabar virando comida pra um peixe. Pense nisso antes de jogar fora', '2025-10-30 00:26:09', ''),
(60, 21, 'Muito lindo o aquário de Ubatuba, super indico o passeio!!', '2025-10-30 00:26:46', '7c1f86d2cd8eabfb72c15a518da9d92e.png'),
(61, 22, 'Os corais parecem plantas, mas são animais que formam colônias enormes! 🪸', '2025-10-30 00:34:55', ''),
(62, 22, 'Sabia que os corais podem morrer por causa do aumento da temperatura da água? É o chamado ‘branqueamento’.', '2025-10-30 00:35:19', ''),
(63, 22, 'Pensamento intrusivo: pegar esse carinha na mão só pra ver o que acontece kkkkk', '2025-10-30 00:36:13', '78a0ce0d9c1a7be8890ba3e5869b3ce8.png'),
(64, 22, 'Vista da Praia do Lamberto hoje de manhã, consegui até ver alguns peixinhos na água', '2025-10-30 00:37:11', 'f5d8f64a7120ca42b128b5a6567151c3.png'),
(65, 23, '📍Praia do Lamberto, Ubatuba ', '2025-10-30 00:42:33', '67aeb49b5dd0e32bf1f232051d65f1ec.png'),
(66, 23, 'Curiosidade do dia: Existem mais estrelas no universo do que grãos de areia nas praias da Terra.', '2025-10-30 00:43:53', ''),
(67, 23, 'Conhecendo a famosa escada de Santa Rita 🧡', '2025-10-30 00:44:44', '6c5fc14dadfa5e09ea7bd0690ad70aba.png'),
(68, 23, 'Pessoal, tenho muito interesse em biologia marinha, alguém tem indicação de cursos bons pra essa área? \r\n', '2025-10-30 00:45:32', ''),
(70, 24, 'Vista linda com essa escuna no mar 🩵', '2025-10-30 00:53:44', 'f2c1d7011d97e181a12341088c22b492.png'),
(71, 24, 'Sabia que o sexo das tartarugas depende da temperatura da areia? Se estiver mais quente, nascem mais fêmeas!', '2025-10-30 00:55:00', ''),
(72, 24, 'Os recifes de corais abrigam cerca de 25% da vida marinha, mesmo ocupando menos de 1% do oceano.', '2025-10-30 00:55:40', ''),
(73, 24, 'Curiosidade: metade do oxigênio que respiramos vem do oceano!', '2025-10-30 00:56:13', ''),
(74, 24, 'Mergulhe sem equipamento, é mais natural!', '2025-10-30 00:57:38', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ranking_memoria`
--

CREATE TABLE `ranking_memoria` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tempo_segundos` int(11) NOT NULL,
  `data_jogada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ranking_memoria`
--

INSERT INTO `ranking_memoria` (`id`, `id_usuario`, `tempo_segundos`, `data_jogada`) VALUES
(1, 6, 48, '2025-09-07 20:04:55'),
(5, 14, 63, '2025-09-08 11:42:10'),
(6, 15, 5, '2025-10-08 22:41:22'),
(7, 12, 7, '2025-10-27 22:16:50'),
(8, 12, 50, '2025-10-27 22:18:38'),
(9, 12, 9, '2025-10-27 22:30:13'),
(10, 12, 45, '2025-10-28 12:44:31'),
(11, 19, 24, '2025-10-30 00:07:06'),
(12, 19, 26, '2025-10-30 00:07:45'),
(13, 20, 22, '2025-10-30 00:18:55'),
(14, 22, 30, '2025-10-30 00:39:33'),
(15, 23, 31, '2025-10-30 00:46:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ranking_quiz`
--

CREATE TABLE `ranking_quiz` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `acertos` int(11) NOT NULL,
  `tempo_segundos` int(11) NOT NULL,
  `data_realizacao` datetime DEFAULT current_timestamp(),
  `dificuldade` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ranking_quiz`
--

INSERT INTO `ranking_quiz` (`id`, `id_usuario`, `acertos`, `tempo_segundos`, `data_realizacao`, `dificuldade`) VALUES
(1, 6, 5, 14, '2025-09-10 19:46:15', 3),
(2, 6, 6, 16, '2025-09-10 19:47:11', 1),
(3, 6, 4, 15, '2025-09-10 19:47:33', 2),
(4, 6, 5, 14, '2025-09-10 19:47:51', 4),
(5, 6, 3, 37, '2025-09-11 11:53:27', 4),
(6, 16, 6, 194, '2025-09-12 07:31:36', 2),
(7, 12, 5, 100, '2025-09-20 20:45:08', 1),
(8, 12, 12, 94, '2025-09-20 21:03:00', 1),
(9, 12, 1, 30, '2025-09-20 21:06:54', 1),
(10, 15, 7, 147, '2025-10-08 11:33:35', 2),
(11, 6, 4, 46, '2025-10-08 12:13:42', 2),
(12, 15, 3, 22, '2025-10-08 12:21:00', 1),
(13, 17, 5, 153, '2025-10-10 09:08:26', 2),
(14, 18, 7, 192, '2025-10-10 11:13:56', 1),
(15, 12, 4, 69, '2025-10-27 18:56:30', 1),
(16, 12, 12, 67, '2025-10-27 19:02:59', 1),
(17, 12, 6, 40, '2025-10-27 19:10:29', 2),
(18, 19, 9, 118, '2025-10-29 21:11:06', 2),
(19, 20, 14, 52, '2025-10-29 21:21:17', 1),
(20, 22, 6, 38, '2025-10-29 21:38:42', 3),
(21, 23, 5, 35, '2025-10-29 21:47:27', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `resposta`
--

CREATE TABLE `resposta` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `biologo_id` int(11) NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_usuario`
--

CREATE TABLE `respostas_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `acertou` tinyint(1) NOT NULL,
  `tempo_resposta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `termo`
--

CREATE TABLE `termo` (
  `id` int(11) NOT NULL,
  `palavra` varchar(10) NOT NULL,
  `dificuldade` tinyint(4) NOT NULL,
  `dica` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `termo`
--

INSERT INTO `termo` (`id`, `palavra`, `dificuldade`, `dica`, `data_cadastro`) VALUES
(1, 'ONDAS', 1, 'mar', '2025-09-30 13:51:45'),
(2, 'BALEIA', 2, 'Grande mamífero marinho que migra para o litoral', '2025-09-30 22:56:14'),
(3, 'CORAIS', 2, 'Pequenos animais que formam recifes coloridos', '2025-09-30 22:57:08'),
(4, 'ESTRELA', 3, 'Equinodermo que vive no fundo do mar, com cinco ou mais braços.', '2025-09-30 22:58:39'),
(5, 'TUBARãO', 3, 'Predador marinho com esqueleto cartilaginoso.', '2025-09-30 23:01:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `termo_partidas`
--

CREATE TABLE `termo_partidas` (
  `id` int(11) NOT NULL,
  `jogador` varchar(50) DEFAULT NULL,
  `palavra_id` int(11) DEFAULT NULL,
  `tentativas` int(11) DEFAULT NULL,
  `data_jogo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `termo_partidas`
--

INSERT INTO `termo_partidas` (`id`, `jogador`, `palavra_id`, `tentativas`, `data_jogo`) VALUES
(1, 'vitor', 1, 1, '2025-09-30 17:55:59'),
(2, 'Ana luiza', 1, 1, '2025-09-30 18:04:54'),
(3, 'Ana luiza', 1, 1, '2025-09-30 18:10:32'),
(4, 'Ana luiza', 1, 6, '2025-09-30 18:11:21'),
(5, 'Ana luiza', 1, 1, '2025-09-30 19:15:45'),
(6, 'Ana luiza', 1, 2, '2025-09-30 19:15:55'),
(7, 'Ana luiza', 2, 2, '2025-09-30 20:02:22'),
(8, 'vitor', 4, 2, '2025-09-30 20:03:17'),
(9, 'Ana luiza', 1, 1, '2025-09-30 20:05:18'),
(10, 'Giulia', 1, 1, '2025-10-01 08:26:03'),
(11, 'Giulia', 1, 1, '2025-10-01 08:27:20'),
(12, 'Ana luiza', 1, 1, '2025-10-08 19:48:56'),
(13, 'kayk', 2, 2, '2025-10-10 09:09:40'),
(14, 'Giulia', 4, 3, '2025-10-10 11:00:32'),
(15, 'Miguel', 1, 2, '2025-10-10 11:18:35'),
(16, 'Giulia', 1, 3, '2025-10-27 19:54:45'),
(17, 'Giulia', 1, 1, '2025-10-27 19:55:06'),
(18, 'Giulia', 1, 1, '2025-10-27 19:55:33'),
(19, 'Giulia', 1, 1, '2025-10-27 19:55:49'),
(20, 'Giulia', 1, 1, '2025-10-27 19:56:27'),
(21, 'Giulia', 1, 1, '2025-10-27 19:57:10'),
(22, 'Giulia', 1, 1, '2025-10-27 19:57:33'),
(23, 'Giulia', 1, 1, '2025-10-27 19:59:47'),
(24, 'Giulia', 1, 1, '2025-10-27 20:00:08'),
(25, 'Giulia', 1, 1, '2025-10-27 20:02:56'),
(26, 'Giulia', 1, 1, '2025-10-27 20:03:16'),
(27, 'Giulia', 1, 1, '2025-10-27 20:03:40'),
(28, 'Giulia', 1, 1, '2025-10-27 20:05:54'),
(29, 'Giulia', 1, 1, '2025-10-27 20:06:22'),
(30, 'Giulia', 1, 1, '2025-10-27 20:07:09'),
(31, 'Giulia', 1, 1, '2025-10-27 20:07:30'),
(32, 'Giulia', 1, 1, '2025-10-27 20:13:01'),
(33, 'Giulia', 1, 1, '2025-10-27 20:13:45'),
(34, 'Giulia', 1, 1, '2025-10-27 20:16:06'),
(35, 'Giulia', 1, 1, '2025-10-27 20:17:08'),
(36, 'Giulia', 2, 2, '2025-10-27 20:22:42'),
(37, 'Giulia', 2, 1, '2025-10-27 20:24:34'),
(38, 'Giulia', 2, 1, '2025-10-27 20:25:10'),
(39, 'Giulia', 2, 1, '2025-10-27 20:25:45'),
(40, 'Giulia', 2, 1, '2025-10-27 20:27:31'),
(41, 'Giulia', 1, 1, '2025-10-27 20:30:27'),
(42, 'Giulia', 1, 1, '2025-10-27 20:30:43'),
(43, 'Giulia', 2, 1, '2025-10-27 20:31:15'),
(44, 'Giulia', 2, 1, '2025-10-27 20:31:36'),
(45, 'Giulia', 2, 1, '2025-10-27 20:32:56'),
(46, 'Giulia', 4, 1, '2025-10-27 20:37:17'),
(47, 'Giulia', 4, 1, '2025-10-27 20:37:47'),
(48, 'Giulia', 4, 1, '2025-10-27 20:37:58'),
(49, 'Giulia', 4, 1, '2025-10-27 20:38:23'),
(50, 'Giulia', 4, 1, '2025-10-27 20:39:36'),
(51, 'Giulia', 4, 1, '2025-10-27 20:40:07'),
(52, 'Giulia', 4, 1, '2025-10-27 20:40:14'),
(53, 'Giulia', 4, 1, '2025-10-27 20:40:24'),
(54, 'Giulia', 2, 1, '2025-10-27 20:40:44'),
(55, 'Giulia', 1, 1, '2025-10-27 22:48:08'),
(56, 'Giulia', 2, 1, '2025-10-27 22:48:23'),
(57, 'Giulia', 4, 1, '2025-10-27 22:48:35'),
(58, 'Eliana', 1, 3, '2025-10-29 21:08:42'),
(59, 'Mica', 3, 3, '2025-10-29 21:19:24'),
(60, 'Clarinha', 3, 3, '2025-10-29 21:31:56'),
(61, 'Gigi', 3, 2, '2025-10-29 21:47:39'),
(62, 'Gigi', 3, 1, '2025-10-29 21:47:44'),
(63, 'Gigi', 1, 1, '2025-10-29 21:47:56'),
(64, 'Gigi', 1, 1, '2025-10-29 21:48:01'),
(65, 'vitor', 1, 2, '2025-10-31 10:53:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `termo_ranking`
--

CREATE TABLE `termo_ranking` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pontuacao` int(11) NOT NULL DEFAULT 0,
  `partidas_jogadas` int(11) DEFAULT 0,
  `vitorias` int(11) DEFAULT 0,
  `melhor_tempo` time DEFAULT NULL,
  `ultima_partida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `termo_ranking`
--

INSERT INTO `termo_ranking` (`id`, `usuario_id`, `pontuacao`, `partidas_jogadas`, `vitorias`, `melhor_tempo`, `ultima_partida`) VALUES
(2, 15, 70, 7, 7, '00:00:00', '2025-10-08 19:48:56'),
(3, 6, 10, 1, 1, '00:00:16', '2025-09-30 20:03:17'),
(4, 12, 350, 35, 35, '00:00:00', '2025-10-27 20:40:24'),
(5, 17, 10, 1, 1, '00:00:58', '2025-10-10 09:09:40'),
(6, 18, 10, 1, 1, '00:00:45', '2025-10-10 11:18:35'),
(7, 19, 10, 1, 1, NULL, '2025-10-29 21:08:42'),
(8, 20, 10, 1, 1, NULL, '2025-10-29 21:19:24'),
(9, 21, 10, 1, 1, NULL, '2025-10-29 21:31:56'),
(10, 23, 10, 1, 1, NULL, '2025-10-29 21:47:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(220) NOT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `status` varchar(1) NOT NULL,
  `foto` varchar(70) DEFAULT NULL,
  `token_recuperacao` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `status`, `foto`, `token_recuperacao`, `token_expira`) VALUES
(1, 'edson', 'adrianpaxisto@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '1', '1', 'frontend/public/img/perfil1.jpg', NULL, NULL),
(2, 'clara', 'tcc@gmail.1com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '1', '1', NULL, NULL, NULL),
(3, 'GIU', 'Giulia@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '1', '1', 'frontend/public/img/perfil3.jpg', NULL, NULL),
(4, 'kevin', 'Vidamarinha@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', NULL, NULL, NULL),
(6, 'vitor', 'vitorcarvalho@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/tartaruga.jpg', 'a9f93b81ec9630eb6c290874cd09a577158f2459b85eb56b77a17a0126b7e49a', '2025-10-30 16:35:10'),
(8, 'Herminio', 'Herminio@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', '', NULL, NULL),
(10, 'Samuel', 'Samuel@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', '', NULL, NULL),
(11, 'Rebecca', 'Rebecca@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', '', NULL, NULL),
(12, 'Giulia', 'giu.favaro7@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7fbe0d934689a6d4b8fb7a6ae9a39aaaa13ce7283ad0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/tubaleia.jpg', NULL, NULL),
(14, 'Maria Clara', 'Mariaclara@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', '', NULL, NULL),
(15, 'Ana luiza', 'analuiza@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/tartaruga.jpg', NULL, NULL),
(16, 'Nicolas', 'nicolas@nicolas.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', '', NULL, NULL),
(17, 'kayk', 'kaikgtuv@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f39da2dbc512ba703338340c2445f9755077919bdd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '0', 'frontend/public/img/tartaruga.jpg', NULL, NULL),
(18, 'Miguel', 'miguelsouza@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f3b24a17a33b6b319df00ba71f970a8fe869affc3d0f59b242071be12249e63d2e6a858bcdcad1187', '0', '0', 'frontend/public/img/baleia.jpg', NULL, NULL),
(19, 'Eliana', 'eliana.cruz@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/tartaruga.jpg', NULL, NULL),
(20, 'Mica', 'micaelly.santos@yahoo.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/baleia.jpg', NULL, NULL),
(21, 'Clarinha', 'clara.cunha@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '1', 'frontend/public/img/tubaleia.jpg', NULL, NULL),
(22, 'Luciano ', 'lulu.favaro@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '0', 'frontend/public/img/tartaruga.jpg', NULL, NULL),
(23, 'Gigi', 'giovana.favaro10@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '0', 'frontend/public/img/baleia.jpg', NULL, NULL),
(24, 'Barbara', 'barbara.portifio@hotmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '0', '0', '', NULL, NULL),
(25, 'Michele', 'bio.michele@gmail.com', '4326999ffff76d4bae86b2b214f5f420bad0fe7f40bd001563085fc35165329ea1ff5c5ecbdbbeefd0f59b242071be12249e63d2e6a858bcdcad1187', '1', '0', 'frontend/public/img/perfil2.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `videos`
--

INSERT INTO `videos` (`id`, `id_autor`, `tipo`, `categoria`, `link`, `data`) VALUES
(5, 1, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/_67ak1R1wsc?si=vjZkXrGq_IC5cRu4\" title=\"YouTube video player', '2025-10-07 23:46:57'),
(6, 1, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/1P3ZgLOy-w8?si=qHwyYAwxCN2EGagB&amp;start=20', '2025-10-07 23:48:09'),
(7, 1, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/yiUQ-5DEO-w?si=1EiO1X0zJeZeyELo', '2025-10-07 23:49:13'),
(8, 1, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/d-1xU0VfJ-g?si=ad4WvLf_S4_N8h54&amp;start=12', '2025-10-07 23:50:47'),
(9, 1, 'Videos', 'Baleia', 'https://www.youtube.com/embed/eBSvl_gxO60?si=OsO9pFfZf0AR_00T&amp;start=3', '2025-10-07 23:51:52'),
(10, 1, 'Videos', 'Baleia', 'https://www.youtube.com/embed/DVVXQBp5pzU?si=dLI1DyteGgM9iI6r', '2025-10-07 23:53:01'),
(11, 1, 'Videos', 'Arraia', 'https://www.youtube.com/embed/nIoUAdtjHoc?si=RsDbGk_1hAD4fzox', '2025-10-07 23:54:44'),
(12, 1, 'Videos', 'Arraia', 'https://www.youtube.com/embed/edro1UBRBfg?si=vJGG6ve0Qu_-csuy', '2025-10-07 23:55:07'),
(13, 1, 'Videos', 'Golfinho', 'https://www.youtube.com/embed/_JHHXnL9dYc?si=trg__8hWa19QRZ5g', '2025-10-07 23:56:14'),
(14, 1, 'Videos', 'Golfinho', 'https://www.youtube.com/embed/AW5QHSltEDs?si=OLI8U1S27hR7Vths', '2025-10-07 23:57:27'),
(15, 1, 'Videos', 'Água-Viva', 'https://www.youtube.com/embed/P8QjXiTa9Rw?si=uZVqA_6WuwO0LdMO', '2025-10-07 23:58:31'),
(16, 1, 'Videos', 'Água-Viva', 'https://www.youtube.com/embed/ldHoenfvFLc?si=uxSHtsRptuk_82N_', '2025-10-07 23:59:21'),
(17, 1, 'Videos', 'Polvo', 'https://www.youtube.com/embed/UKSAX19Ot7A?si=McWQ74rw3BTnSV5J', '2025-10-07 23:59:51'),
(18, 2, 'Videos', 'Polvo', 'https://www.youtube.com/embed/IAxGRODbHlA?si=DFKKankoiYWZQWNs', '2025-10-08 00:00:39'),
(19, 2, 'Videos', 'Moreia', 'https://www.youtube.com/embed/Gx5XyoBcSRE?si=JC-E_QPrBPrpi8oW', '2025-10-08 00:01:20'),
(20, 2, 'Videos', 'Moreia', 'https://www.youtube.com/embed/x3tfZiRk2hg?si=wpV8EmC83xdhN2l6', '2025-10-08 00:01:39'),
(21, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/KYuL04d_0lo?si=1bT5H_Rz1RXiqrGl', '2025-10-30 03:06:32'),
(22, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/K1_KkTotxhU?si=KQW3rQQ9S4is_j4c', '2025-10-30 03:08:19'),
(23, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/vHn3VboTw1Y?si=IBBv67MxejonlctQ', '2025-10-30 03:09:24'),
(24, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/LgZDEeUfYKE?si=cuKzXKPOHE7QFQVH', '2025-10-30 03:11:07'),
(25, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/b3x4X6TRniE?si=fJK6IgVrSLnwddyj', '2025-10-30 03:13:02'),
(26, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/_lBzX7jm35A?si=aksg1mF2D_1CfAuR', '2025-10-30 03:13:36'),
(27, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/t8jY3PzCQow?si=1KCPacDi8NFrycy1', '2025-10-30 03:14:28'),
(28, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/EMDBReLLzYA?si=E-rWyYRWeK-iN1rT', '2025-10-30 03:15:26'),
(29, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/wloMYxi6XKc?si=J6vrHe0x4hrNr-JY', '2025-10-30 03:15:55'),
(30, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/0YAwW_2K3-Q?si=sSKKQ_8SqE3uTA3R', '2025-10-30 03:18:06'),
(31, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/8v3twpTEqj0?si=LVTgm-BmMbxandI0', '2025-10-30 03:19:38'),
(32, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/RD3yhhe7wYc?si=oCCludOxSsNv1cWg', '2025-10-30 03:20:08'),
(33, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/sLyemFr-qbU?si=4D_5C7LMsAbjbUuE', '2025-10-30 03:21:02'),
(34, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/JDoGLXNnqBI?si=WfF2Rl32jNFfnCHx', '2025-10-30 03:23:14'),
(35, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/L2aV5Jdsh9A?si=3oKXi9UffRk9_Nz_', '2025-10-30 03:24:21'),
(36, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/aIcLQn6HK_Q?si=ONh5E__9OsV1-YWE', '2025-10-30 03:25:28'),
(37, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/Tjnq_R6gis0?si=lRo-F08AlshpkhLX', '2025-10-30 03:26:18'),
(38, 25, 'Videos', 'Arraia', 'https://www.youtube.com/embed/T5URFtRpY0M?si=fVwmFEltVFzoPFIv', '2025-10-30 03:27:13'),
(39, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/8S5sstKZH0I?si=pn9WVc9v60bPuvx2', '2025-10-30 03:27:52'),
(40, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/VYNn8p_FuIs?si=d02B29aLEscwolq-', '2025-10-30 03:28:44'),
(41, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/DVVXQBp5pzU?si=77nigNRs3cfZpiMC', '2025-10-30 03:29:20'),
(42, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/5NFs5yyYwSk?si=bFRbm5tcxs4X6-UE', '2025-10-30 03:30:01'),
(43, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/PFnKxAKFb70?si=LOuYoLCbOvWhYsA7', '2025-10-30 03:30:30'),
(44, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/6GRW_n_o84g?si=NkizB7Au2VHXBPT0', '2025-10-30 03:30:57'),
(45, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/Y0BX1hA6uMo?si=GBo_BPSUQewBFt-r', '2025-10-30 03:32:35'),
(46, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/zoKGbobEHw0?si=bpPVl4pVg-tfw5M7', '2025-10-30 03:33:13'),
(47, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/ECL-7-uIBrU?si=bWtVfeijW1oVIWSG', '2025-10-30 03:33:58'),
(48, 25, 'Videos', 'Tubarão', 'https://www.youtube.com/embed/DDpqpAIuJTU?si=-ItQZysH5z1vxc_-', '2025-10-30 03:35:05'),
(49, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/hcfAq84Q8cU?si=DgkmUCOpggzffdC_', '2025-10-30 03:35:49'),
(50, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/lSvgDSOigTU?si=tWTf8BY0pGongr97', '2025-10-30 03:36:38'),
(51, 25, 'Videos', 'Baleia', 'https://www.youtube.com/embed/3FkXh7hmMAw?si=n9EWkthcQG_Tr6nQ', '2025-10-30 03:37:35'),
(52, 25, 'Videos', 'Tartaruga', 'https://www.youtube.com/embed/H_RMQCeiZgc?si=UVGVLEO2RxL8E24r', '2025-10-30 03:38:17'),
(53, 25, 'Videos', 'Água-Viva', 'https://www.youtube.com/embed/RVxS5AOk_ZQ?si=2jtsmqoet2Phzxto', '2025-10-30 03:39:55'),
(54, 25, 'Videos', 'Polvo', 'https://www.youtube.com/embed/025TjS52tlk?si=4pog0NEjSZnaJDKW', '2025-10-30 03:42:48'),
(55, 25, 'Videos', 'Polvo', 'https://www.youtube.com/embed/l_YcB1WZMvk?si=5jADqQVtUL_ZPmkt', '2025-10-30 03:43:18');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_comentarios_postagens` (`id_postagem`);

--
-- Índices de tabela `conscientizacao`
--
ALTER TABLE `conscientizacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conscientizacao_autor` (`id_autor`);

--
-- Índices de tabela `conteudos`
--
ALTER TABLE `conteudos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unica_curtida` (`id_postagem`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_denuncia` (`id_usuario`),
  ADD KEY `fk_post_denuncia` (`id_post`);

--
-- Índices de tabela `denuncias_comentarios`
--
ALTER TABLE `denuncias_comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_comentario` (`id_comentario`);

--
-- Índices de tabela `imagens_artigos`
--
ALTER TABLE `imagens_artigos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_artigo` (`id_artigo`);

--
-- Índices de tabela `img_conscientizacao`
--
ALTER TABLE `img_conscientizacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `img_conscientizacao_ibfk_1` (`id_artigo`);

--
-- Índices de tabela `leituras`
--
ALTER TABLE `leituras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_artigo` (`id_artigo`),
  ADD KEY `fk_conscientizacao` (`id_conscientizacao`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id_notificacao`),
  ADD KEY `fk_notif_usuario` (`id_usuario`),
  ADD KEY `fk_notif_post` (`id_post`);

--
-- Índices de tabela `pergunta`
--
ALTER TABLE `pergunta`
  ADD PRIMARY KEY (`id_pergunta`),
  ADD KEY `turista_id` (`turista_id`);

--
-- Índices de tabela `perguntas_quiz`
--
ALTER TABLE `perguntas_quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pergunta_quiz` (`id_biologo`);

--
-- Índices de tabela `pontos_leituras`
--
ALTER TABLE `pontos_leituras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_pontos` (`id_usuario`),
  ADD KEY `fk_artigo_pontos` (`id_artigo`),
  ADD KEY `id_conscientizacao` (`id_conscientizacao`);

--
-- Índices de tabela `postagens`
--
ALTER TABLE `postagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `ranking_memoria`
--
ALTER TABLE `ranking_memoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `ranking_quiz`
--
ALTER TABLE `ranking_quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `resposta`
--
ALTER TABLE `resposta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `biologo_id` (`biologo_id`);

--
-- Índices de tabela `respostas_usuario`
--
ALTER TABLE `respostas_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices de tabela `termo`
--
ALTER TABLE `termo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `termo_partidas`
--
ALTER TABLE `termo_partidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `palavra_id` (`palavra_id`);

--
-- Índices de tabela `termo_ranking`
--
ALTER TABLE `termo_ranking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_autor` (`id_autor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `conscientizacao`
--
ALTER TABLE `conscientizacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `conteudos`
--
ALTER TABLE `conteudos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `denuncias_comentarios`
--
ALTER TABLE `denuncias_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `imagens_artigos`
--
ALTER TABLE `imagens_artigos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `img_conscientizacao`
--
ALTER TABLE `img_conscientizacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `leituras`
--
ALTER TABLE `leituras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id_notificacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `pergunta`
--
ALTER TABLE `pergunta`
  MODIFY `id_pergunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas_quiz`
--
ALTER TABLE `perguntas_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `pontos_leituras`
--
ALTER TABLE `pontos_leituras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `postagens`
--
ALTER TABLE `postagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de tabela `ranking_memoria`
--
ALTER TABLE `ranking_memoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `ranking_quiz`
--
ALTER TABLE `ranking_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `resposta`
--
ALTER TABLE `resposta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `respostas_usuario`
--
ALTER TABLE `respostas_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `termo`
--
ALTER TABLE `termo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `termo_partidas`
--
ALTER TABLE `termo_partidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `termo_ranking`
--
ALTER TABLE `termo_ranking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_postagem`) REFERENCES `postagens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentarios_postagens` FOREIGN KEY (`id_postagem`) REFERENCES `postagens` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `conscientizacao`
--
ALTER TABLE `conscientizacao`
  ADD CONSTRAINT `fk_conscientizacao_autor` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `conteudos`
--
ALTER TABLE `conteudos`
  ADD CONSTRAINT `conteudos_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`id_postagem`) REFERENCES `postagens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `denuncias`
--
ALTER TABLE `denuncias`
  ADD CONSTRAINT `fk_post_denuncia` FOREIGN KEY (`id_post`) REFERENCES `postagens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario_denuncia` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `denuncias_comentarios`
--
ALTER TABLE `denuncias_comentarios`
  ADD CONSTRAINT `denuncias_comentarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `denuncias_comentarios_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imagens_artigos`
--
ALTER TABLE `imagens_artigos`
  ADD CONSTRAINT `imagens_artigos_ibfk_1` FOREIGN KEY (`id_artigo`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `img_conscientizacao`
--
ALTER TABLE `img_conscientizacao`
  ADD CONSTRAINT `img_conscientizacao_ibfk_1` FOREIGN KEY (`id_artigo`) REFERENCES `conscientizacao` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `leituras`
--
ALTER TABLE `leituras`
  ADD CONSTRAINT `fk_conscientizacao` FOREIGN KEY (`id_conscientizacao`) REFERENCES `conscientizacao` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `leituras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leituras_ibfk_2` FOREIGN KEY (`id_artigo`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_notif_post` FOREIGN KEY (`id_post`) REFERENCES `postagens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pergunta`
--
ALTER TABLE `pergunta`
  ADD CONSTRAINT `pergunta_ibfk_1` FOREIGN KEY (`turista_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `perguntas_quiz`
--
ALTER TABLE `perguntas_quiz`
  ADD CONSTRAINT `fk_pergunta_quiz` FOREIGN KEY (`id_biologo`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pontos_leituras`
--
ALTER TABLE `pontos_leituras`
  ADD CONSTRAINT `fk_artigo_pontos` FOREIGN KEY (`id_artigo`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario_pontos` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pontos_leituras_ibfk_1` FOREIGN KEY (`id_conscientizacao`) REFERENCES `conscientizacao` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `postagens`
--
ALTER TABLE `postagens`
  ADD CONSTRAINT `postagens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ranking_memoria`
--
ALTER TABLE `ranking_memoria`
  ADD CONSTRAINT `ranking_memoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ranking_quiz`
--
ALTER TABLE `ranking_quiz`
  ADD CONSTRAINT `ranking_quiz_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `resposta`
--
ALTER TABLE `resposta`
  ADD CONSTRAINT `resposta_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `pergunta` (`id_pergunta`) ON DELETE CASCADE,
  ADD CONSTRAINT `resposta_ibfk_2` FOREIGN KEY (`biologo_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `respostas_usuario`
--
ALTER TABLE `respostas_usuario`
  ADD CONSTRAINT `respostas_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `respostas_usuario_ibfk_2` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas_quiz` (`id`);

--
-- Restrições para tabelas `termo_partidas`
--
ALTER TABLE `termo_partidas`
  ADD CONSTRAINT `termo_partidas_ibfk_1` FOREIGN KEY (`palavra_id`) REFERENCES `termo` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `termo_ranking`
--
ALTER TABLE `termo_ranking`
  ADD CONSTRAINT `termo_ranking_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
