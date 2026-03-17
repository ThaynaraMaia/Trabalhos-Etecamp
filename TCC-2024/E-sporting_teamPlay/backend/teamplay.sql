-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 04:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teamplay`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `pin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `message`, `user_id`, `group_id`, `pin`) VALUES
(1, 'bugnas foi removido do grupo por swarm', 11, 3, 0),
(2, 'trevnas foi removido do grupo por swarm', 11, 3, 0),
(3, 'Teste', 11, 3, 0),
(4, '2', 11, 3, 0),
(5, 'Oi...', 21, 3, 0),
(6, 'trevnas deixou o grupo', 21, 3, 0),
(9, 'trevnas foi removido do grupo por swarm', 11, 3, 0),
(10, 'trevnas deixou o grupo', 21, 3, 0),
(11, 'Oi...', 21, 3, 0),
(12, 'Oi', 9, 3, 0),
(13, 'bugnas foi removido do grupo por swarm', 11, 3, 0),
(16, 'Message', 11, 4, 0),
(17, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(19, 'trevnas foi removido do grupo por swarm', 11, 4, 0),
(21, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(22, 'trevnas foi removido do grupo por swarm', 11, 4, 0),
(25, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(27, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(28, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(29, 'trevnas foi removido do grupo por swarm', 11, 4, 0),
(30, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(31, 'trevnas foi removido do grupo por swarm', 11, 4, 0),
(32, 'trevnas foi adicionado ao grupo por swarm', 21, 4, 0),
(33, 'bugnas foi adicionado ao grupo por swarm', 9, 4, 0),
(34, 'trevnas foi removido do grupo por swarm', 11, 4, 0),
(35, 'trevnas foi adicionado ao grupo por swarm', 11, 4, 0),
(36, 'bugnas foi removido do grupo por swarm', 11, 4, 0),
(37, 'bugnas foi adicionado ao grupo por swarm', 11, 4, 0),
(38, 'trevnas foi removido do grupo por swarm', 11, 3, 0),
(39, 'trevnas foi adicionado ao grupo por swarm', 11, 3, 0),
(40, 'trevnas foi removido do grupo por swarm', 11, 3, 0),
(41, 'bugnas foi adicionado ao grupo por swarm', 11, 3, 0),
(42, 'trevnas deixou o grupo', 21, 4, 0),
(43, 'trevnas foi adicionado ao grupo por swarm', 11, 4, 0),
(44, 'Aeee', 9, 4, 0),
(45, 'O', 11, 4, 0),
(46, 'Opa', 11, 4, 0),
(47, 'Ae2', 9, 4, 0),
(48, 'jorge deixou o grupo', 26, 8, 0),
(49, 'Adm foi adicionado ao grupo por jorge', 26, 7, 0),
(50, 'Alou', 15, 7, 0),
(51, 'Opa', 26, 7, 0),
(52, 'hello foi adicionado ao grupo por jorge', 26, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `friendship`
--

CREATE TABLE `friendship` (
  `id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `status` int(3) NOT NULL,
  `date_accepted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friendship`
--

INSERT INTO `friendship` (`id`, `user1`, `user2`, `status`, `date_accepted`) VALUES
(3, 11, 15, 1, '2024-10-02'),
(5, 11, 12, 0, '0000-00-00'),
(16, 9, 15, 1, '2024-10-05'),
(17, 11, 10, 0, '0000-00-00'),
(18, 21, 1, 1, '2024-10-04'),
(20, 21, 9, 1, '2024-10-04'),
(21, 9, 11, 1, '2024-10-04'),
(22, 21, 11, 1, '2024-10-04'),
(23, 25, 1, 1, '2024-10-17'),
(25, 26, 1, 1, '2024-10-17'),
(27, 26, 15, 1, '2024-10-16'),
(28, 15, 27, 1, '2024-10-16'),
(29, 27, 9, 0, '0000-00-00'),
(30, 15, 25, 0, '0000-00-00'),
(31, 15, 21, 0, '0000-00-00'),
(32, 15, 1, 1, '2024-10-17'),
(33, 15, 10, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `players` int(128) NOT NULL,
  `tournaments` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `description2` varchar(255) NOT NULL,
  `tecinfo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `picture`, `players`, `tournaments`, `description`, `description2`, `tecinfo`) VALUES
(0, 'Outros', '', 0, '', 'Jogos não registrados.', '-', ''),
(1, 'Call of Duty: Warzone', '', 0, '', 'Call of Duty: Warzone é um jogo eletrônico free-to-play do gênero battle royale desenvolvido pela Infinity Ward e Raven Software e publicado pela Activision.', 'Data de lançamento inicial: 10 de março de 2020\r\nPlataformas: PlayStation 4, Xbox One, Microsoft Windows\r\nIndicações: The Game Award para Melhor Multiplayer, MAIS\r\nGêneros: Tiro em primeira pessoa, Battle royale, Gratuito para jogar\r\nDesenvolvedores: Rave', ''),
(2, 'Overwatch 2', '', 0, '', 'Overwatch 2 é um jogo eletrônico multijogador de tiro em primeira pessoa publicado e distribuído pela Blizzard Entertainment. A Blizzard Entertainment anunciou Overwatch 2 durante a BlizzCon 2019.', 'Plataformas: PlayStation 5, PlayStation 4, Nintendo Switch, MAIS\r\nIndicações: BAFTA Video Games Award: Melhor Multiplayer, MAIS\r\nData de lançamento inicial: 4 de outubro de 2022\r\nDesenvolvedor: Blizzard Entertainment\r\nGêneros: Tiro em primeira pessoa, Fre', ''),
(3, 'Valorant', '', 0, '', 'Valorant é um jogo eletrônico multijogador gratuito para jogar de tiro em primeira pessoa desenvolvido e publicado pela Riot Games.', 'Data de lançamento inicial: 2 de junho de 2020\r\nPlataformas: PlayStation 5, Microsoft Windows, Xbox Series X e Series S, Android\r\nGênero: Jogo de tiro tático\r\nDesenvolvedor: Riot Games\r\nEstúdio: Riot Games\r\nPrêmios: The Game Award para Melhor Jogo de eSpo', ''),
(4, 'Fortnite', '', 0, '', 'Fortnite é um jogo eletrônico multijogador online revelado originalmente em 2011, desenvolvido pela Epic Games e lançado como diferentes modos de jogo que compartilham a mesma jogabilidade e motor gráfico de jogo.', 'Data de lançamento inicial: 21 de julho de 2017\r\nPlataformas: GeForce Now, Xbox Cloud Gaming, Xbox One, MAIS\r\nDesenvolvedores: Epic Games, People Can Fly\r\nGêneros: Battle royale, Jogo eletrônico de plataforma, MAIS\r\nIndicações: BAFTA Games Award for EE Mo', ''),
(5, 'League of Legends', '', 0, '', 'League of Legends é um jogo eletrônico do gênero multiplayer online battle arena desenvolvido e publicado pela Riot Games. Foi lançado em outubro de 2009 para Microsoft Windows e em março de 2013 para macOS.', 'Desenvolvedor: Riot Games\r\nGêneros: Arena de batalha multijogador em linha, RPG eletrônico de ação\r\nIndicações: The Game Award para Melhor Jogo de eSports, MAIS\r\nPrêmios: The Game Award para Melhor Jogo de eSports, MAIS\r\nProjetistas: Steve Feak, Mark Yett', ''),
(6, 'EA FC 24', '', 0, '', 'EA Sports FC 24 é um videojogo de futebol desenvolvido pela EA Canadá e EA Roménia, e publicado pela EA Sports. Este jogo marca o início da série EA Sports FC após a conclusão da parceria da EA com a FIFA, sendo o 31º título lançado da franquia ao todo.', 'Plataformas: PlayStation 5, PlayStation 4, Nintendo Switch, Xbox One, Xbox Series X e Series S, Microsoft Windows\r\nData de lançamento inicial: 22 de setembro de 2023\r\nIndicações: The Game Award para Melhor Jogo de Esportes ou Corrida\r\nGêneros: Jogo eletrô', '');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `total_chats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `creator_id`, `total_chats`) VALUES
(1, 'New Group1', 11, 0),
(2, 'New Group1', 11, 0),
(3, 'New Group1', 11, 5),
(4, 'Novo Grupo 2', 11, 5),
(5, 'Novo', 15, 0),
(6, 'Novo Grupo ADM', 15, 0),
(7, 'Novo grupo', 26, 2),
(8, 'Novo grupo', 26, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `titulo` char(255) NOT NULL,
  `descricao` char(255) DEFAULT NULL,
  `imagem` char(255) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `dat_criacao` date DEFAULT NULL,
  `id_jogo` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id_post`, `titulo`, `descricao`, `imagem`, `id_usuario`, `dat_criacao`, `id_jogo`, `likes`) VALUES
(1, 'Meu Primeiro Post', 'Olá, esse é o primeiro post da Teamplay.', NULL, 11, '2024-09-26', 0, 3),
(2, 'E se eu tiver teorias?', 'O que fazer se eu tiver teorias?', NULL, 11, '2024-09-27', 0, 2),
(24, 'Valorant dos noob hj ou nem?', 'Eu nem jogo ranked, então não espere que eu saiba jogar!', NULL, 22, '2024-10-05', 3, NULL),
(25, 'Pro clubs família bora?', 'Regras: Tem que flamenguista e morar no RJ, qeum tiver afim chama ai!: obs precisamos de centroavante e lateral direito', NULL, 22, '2024-10-05', 6, NULL),
(26, 'Alguém que joga de Mercy?', 'Precisamos de uma no time, é super importante que tenha uma mercy aqui! somos todos ouro, só precisamos do sup', NULL, 22, '2024-10-05', 2, NULL),
(27, 'Alguma moça pra fazer duo?', 'Jogo de ADC de vários champ, gosto de jogar junto com a Morgana pois se aproveitar muito bem as skill dela e combar, sou prata', NULL, 22, '2024-10-05', 5, NULL),
(28, 'Procura-se IGL para cash cup duo', 'Tem que saber economizar mats e ter uma excelente visão de jogo, para jogar cash cup e outros torneios valendo grana, sem crianças! mínimo 16Y', NULL, 22, '2024-10-05', 4, NULL),
(29, 'Warzone das mulheres hj?', 'Regras: Tem que ser mulher e jogar Regras: Tem que flamenguista e morar no RJ, qeum tiver afim chama ai!: obs precisamos de centroavante e lateral direitowarzone, e tem que ter mic sem fakes pfv!!!!!!!!!!!!!!!!', NULL, 22, '2024-10-05', 1, NULL),
(30, 'Quero encontrar uma duo mulher para jogar', 'Tem que ter 18y e jogar de sniper', NULL, 23, '2024-10-05', 1, NULL),
(31, 'Squadzão dos crias', 'quem tiver afim, só quem for ruim! kkkkkkk', NULL, 23, '2024-10-05', 1, NULL),
(32, 'Nerfaram a smg', 'Agr quero um duo que jogue de 12!', NULL, 23, '2024-10-05', 1, NULL),
(33, 'warzone dos estudantes', 'QUEM TIVER ESTUDANDO AINDA NO ENSINO MÉDIO VAMOS JOGAR!!!!!!!!!!!!!!!!!!!!!!!!!!!!\nRegras: Tem que flamenguista e morar no RJ, qeum tiver afim chama ai!: obs precisamos de centroavante e lateral direito\nRegras: Tem que flamenguista e morar no RJ, qeum tiv', NULL, 23, '2024-10-05', 1, NULL),
(34, 'PRO CLUBS SANTISTAS FC', 'apenas para santistas roxos! de preferência da torcida jovem', NULL, 23, '2024-10-05', 6, NULL),
(35, 'pro clubs coringão', 'pra quem é curíntia e joga de atacante igual o depay e o YB!!!!', NULL, 23, '2024-10-05', 6, NULL),
(36, 'x1 NO FIFA', 'Quem é o BRABO vem x1, eu sou legit!!!', NULL, 23, '2024-10-05', 0, NULL),
(37, 'Oláaaaaaaaaaaa amigos, vamos se divertir', 'eu sou ruim, horroroso, odeio esse jogo e estou sendo obrigado a jogar', NULL, 23, '2024-10-05', 5, NULL),
(38, 'Fortnite', 'VAMOS JOGAR FORTNITE, SOU NOVO NO GAME!', NULL, 23, '2024-10-05', 4, NULL),
(39, 'no build fortnite vamos??', 'não sei construir então tem que ser o modo sem', NULL, 23, '2024-10-05', 4, NULL),
(40, 'Pro clubs trevosos', 'Só a elite, divisão 1, somos o time trevoso, para o público gótico e fã de futebol', NULL, 21, '2024-10-05', 6, NULL),
(41, 'vavazinho elite', 'Só pros melhores players, estou sozinho então preciso do time completo, qualquer posição! sou meio tímido mas na hora da play eu rusho muito!!!!!!!!!!!!!!!!!!', NULL, 21, '2024-10-05', 3, NULL),
(42, 'Pessoa para jogar valorant', 'oi, gostaria de alguma menina que tenha 19y e jogue valorant cmg pfv', NULL, 21, '2024-10-05', 3, NULL),
(43, 'Vamos jogar fortnite sem armas', 'tenho um grupo de amigos que ganha partida só na picareta, é sério! quem for maluco entra aí!', NULL, 9, '2024-10-05', 4, NULL),
(44, 'Vamos jogar fortnite sem healing!', 'não vale se curar no meio da partida! obviamente partida casual! tenho um grupo de amigos que fazem vários desafios!', NULL, 9, '2024-10-05', 4, NULL),
(45, 'Vamos jogar fortnite sem enxergar!', 'a gente fica vendado sem enxergar nada, tenho um grupo de amigos que faz vários desafios!', NULL, 9, '2024-10-05', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `rfrom` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `rate` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `rfrom`, `rating`, `rate`) VALUES
(4, 15, 21, 0),
(5, 15, 9, 2),
(6, 15, 23, 5),
(7, 15, 22, 4),
(8, 26, 15, 5),
(9, 27, 15, 1),
(10, 27, 10, 1),
(11, 27, 12, 5),
(12, 27, 11, 4),
(13, 15, 24, 3),
(14, 15, 12, 4),
(15, 15, 10, 4),
(16, 26, 10, 4),
(17, 1, 10, 1),
(18, 26, 1, 4),
(19, 26, 21, 4),
(20, 26, 11, 2),
(21, 11, 9, 1),
(22, 15, 27, 3),
(23, 15, 26, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` int(3) NOT NULL,
  `organizer` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `date_creation` date NOT NULL,
  `current_score` varchar(128) NOT NULL,
  `status` int(3) NOT NULL,
  `players` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `game` int(11) NOT NULL,
  `region` varchar(2) NOT NULL,
  `winner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `title`, `type`, `organizer`, `description`, `date_start`, `date_end`, `date_creation`, `current_score`, `status`, `players`, `picture`, `game`, `region`, `winner`) VALUES
(1, 'Torneio Los Djous', 2, 9, 'Esse aqui é bom demais.', '2024-08-16', '2024-10-17', '2024-07-16', '', 1, '', '../assets/post_pictures/1 (4).jpg', 1, 'RJ', ''),
(2, 'Torneio dos Parças', 2, 9, 'Diferenciado.', '2024-09-30', '2024-10-18', '2024-07-17', '', 1, '', '../assets/post_pictures/1.jpg', 2, 'RJ', ''),
(3, 'Torneio dos DTrutas', 2, 9, 'Muito paia.', '2024-10-02', '2024-10-19', '2024-07-18', '', 0, '', '../assets/post_pictures/1.png', 3, 'RJ', ''),
(4, 'Torneio dos Manos', 1, 9, 'extremamente nocivos.', '2024-08-24', '2024-10-25', '2024-07-24', '', 2, '', '../assets/post_pictures/1 (1).gif', 5, 'RJ', ''),
(5, 'Torneio dos capangas', 1, 9, 'Quem eles vão ser essa noite?.', '2024-09-30', '2024-10-20', '2024-07-19', '', 1, '', '../assets/post_pictures/1 (1).jpg', 4, 'RJ', ''),
(6, 'Torneio das Maritacas', 0, 9, 'Descrição', '2024-08-21', '2024-10-22', '2024-07-20', '3 - 3', 0, '0, 20', '../assets/post_pictures/1 (5).png', 6, 'RJ', 'Vencedor'),
(7, 'Torneio sem sal', 1, 9, 'E sem sabor.', '2024-08-20', '2024-10-21', '2024-07-20', '', 0, '', '../assets/post_pictures/1 (3).jpg', 0, 'JK', ''),
(17, 'Torneio New', 1, 11, 'De Overwatch 2. ', '2024-09-27', '2024-10-11', '2024-10-02', '', 1, '', '../assets/post_pictures/1 (1).png', 0, 'SP', ''),
(72, 'Novo Torneio', 0, 11, 'Entre agora! Contato em: https://www.geeksforgeeks.org/how-to-simulate-a-click-with-javascript/\r\nhttps://www.geeksforgeeks.org/how-to-simulate-a-click-with-javascript/', '2024-09-27', '2024-09-26', '2024-10-03', '6 - 2', 1, '8,12', '../assets/post_pictures/1 (2).gif', 2, 'SP', ''),
(73, 'Torneio Ultra', 1, 11, 'Só para os melhores.', '2024-09-27', '2024-09-27', '2024-09-30', '', 1, '1,100', '', 4, 'SP', ''),
(74, 'Torn. Novo', 0, 11, 'Jogue com a gente!', '2024-10-02', '2024-09-01', '2024-10-10', '7 - 1', 0, '12,20', '../assets/post_pictures/1 (4).png', 6, 'SP', 'Jogador Mestre'),
(75, 'Mais Um Torneio↪', 0, 11, 'Jogue LOL conosco...', '2024-10-01', '2024-10-11', '2024-09-27', '', 0, '4,5', '../assets/post_pictures/1 (2).jpg', 5, 'SP', 'Super'),
(76, 'Novo Torneio 🙏', 0, 11, 'Aqui teremos diversão', '2024-09-30', '2024-09-30', '2024-09-27', '8 - 4', 1, '6,40', '../assets/post_pictures/1 (1).jpeg', 1, 'SP', 'Nova'),
(77, 'Torneio Jogos Legais', 0, 11, 'Entre para se divertir com jogos legais!', '2024-09-30', '2024-10-10', '2024-09-29', '', 1, '0,18', '', 6, 'SP', ''),
(78, 'Jogue Fortnite', 0, 11, 'Vamos jogar uma partida de Fortnite!', '2024-10-01', '2024-10-05', '2024-09-29', '', 1, '0,100', '../assets/post_pictures/1 (2).png', 4, 'SP', ''),
(79, 'Torneio das Trevas', 1, 21, 'Junte-se... a nós...', '2024-10-02', '2024-10-13', '2024-09-30', '', 0, '0, 15', '../assets/post_pictures/1 (4).jpg', 6, 'HE', ''),
(82, 'LoL Championship', 0, 24, 'Ocorrerá no discord, me chame no chat que eu explico mais detalhes sobre', '2024-10-05', '2024-10-06', '2024-10-05', '2x1', 2, '82,20', '../assets/post_pictures/1 (3).png', 5, 'RS', 'teamlol'),
(83, 'sad', 1, 15, 'sadad', '2024-10-17', '2024-11-01', '2024-10-18', '', 0, '0, 5', '', 2, 'SP', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(11) NOT NULL,
  `level` int(3) NOT NULL,
  `verified` int(3) NOT NULL,
  `status` int(3) NOT NULL,
  `birthday` date NOT NULL,
  `picture` varchar(255) NOT NULL,
  `region` varchar(2) NOT NULL,
  `join_date` date NOT NULL,
  `socials` varchar(1000) NOT NULL,
  `favorite_game` int(3) NOT NULL,
  `games` varchar(1000) NOT NULL,
  `gameplay` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reputation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nickname`, `email`, `password`, `token`, `level`, `verified`, `status`, `birthday`, `picture`, `region`, `join_date`, `socials`, `favorite_game`, `games`, `gameplay`, `description`, `reputation`) VALUES
(1, 'hello', 'hello', 'hello@world.com', 'fc5e038d38a57032085441e7fe7010b0', '6732', 1, 0, 1, '2024-08-21', '../assets/profile_pics/1 (1).jpg', 'SP', '2024-08-01', 'Play', 0, '[]', 'Jogo de Medic', 'Sou o Hello World', '2'),
(9, 'bugnas', 'Bugnas', 'bug@nas.com', 'Bgn12', '8784', 2, 0, 1, '0000-00-00', '../assets/profile_pics/1 (1).png', '', '2024-08-22', 'insta: bugnas, discord: bugnas', 1, '[{\"idCurr\":7,\"name\":\"EA FC24\",\"data\":[\"Bug\",\"Divisão 6\",[false,false,\"RB\",false,false,false,false,false,false,\"CF\",false]]},{\"idCurr\":4,\"name\":\"Valorant\",\"data\":[\"Bug97324\",\"Bronze\",[false,\"Duelist\",false,false]]},{\"idCurr\":5,\"name\":\"Fortnite\",\"data\":[\"BugnaFort\",\"Lenda\",[false,\"Fragger\",false]]}]', 'Tenho um grupo de amigos que faz vários desafios no fortnite', 'Sou o Bugnas, meio doidinho', '2.33'),
(10, 'mnl', 'Mano Léo', 'manol@gmail.com', 'manoleo12', '1125', 2, 1, 1, '2014-09-16', '../assets/profile_pics/1 (1).jpeg', '', '2024-08-26', '1, 2, 3, 4', 2, '', 'Jogo vários jogos.', 'Sou o Léo.', '2.55'),
(11, 'swarm', 'Swarm7619', 'pablorockid2015@gmail.com', '85f75035983b47e8407d67ddacb97da8', '0982', 2, 0, 1, '0000-00-00', '../assets/profile_pics/1 (2).png', 'SP', '2024-09-01', 'Array()', 1, '[{\"idCurr\":3,\"name\":\"Overwatch 2\",\"data\":[\"Swarm7619\",\"Bronze\",[false,\"Dano\",\"Suporte\"]]},{\"idCurr\":2,\"name\":\"Call of Duty: Warzone\",\"data\":[\"Swarm\",\"Bronze\"]},{\"idCurr\":7,\"name\":\"EA FC24\",\"data\":[\"Oz\",\"Divisão 2\",[\"GK\",\"CB\",\"RB\",\"LB\",\"CDM\",\"CM\",\"CAM\",\"RM/RW\",\"LM/LW\",\"CF\",\"ST\"]]}]', 'Também jogo Don\'t Starve Together, Team Fortress 2, YOMI Hustle, PVZ GW2, The Isle e Brawlhalla.', 'Meu nome é Ozório !! Diretamente do espaço afora !!', '3.3333333333333'),
(12, 'Nova', 'Nova', 'novo@mail.com', 'New123', '4833', 1, 0, 1, '0000-00-00', '../assets/profile_pics/1 (2).jpg', '', '2024-09-02', '', 0, '', '', '', '4'),
(15, 'Adm', 'Ade Miro', 'adimiro@.com', '32250170a0dca92d53ec9624f336ca24', '3285', 2, 1, 1, '1999-08-17', '../assets/profile_pics/1 (3).png', 'SP', '2024-08-01', 'STAFF', 2, '', 'Administro.', 'O verdadeiro e único Adm desse site.', '5'),
(21, 'trevnas', 'Trevnas', 'trev_nas@gmalius.com', 'TREV12', '7856', 1, 0, 1, '0000-00-00', '../assets/profile_pics/1 (3).jpeg', 'HE', '2024-09-30', 'https://www.w3schools.com/sql/sql_ref_as.asp', 1, '[{\"idCurr\":4,\"name\":\"Valorant\",\"data\":[\"Trevlorant\",\"Radiante\",[\"Controller\",\"Duelist\",false,\"Sentinel\"]]},{\"idCurr\":3,\"name\":\"Overwatch 2\",\"data\":[\"trevnossa\",\"Diamante\",[false,\"Dano\",false]]}]', 'Eu não gosto de jogos.', 'Trev...nas..... ', '4.6666666666667'),
(22, 'ryan', 'Ryan', 'ryan@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '8270', 1, 0, 1, '0000-00-00', '../assets/profile_pics/1 (3).jpg', 'SP', '2024-10-05', 'Array()', 1, '[]', 'Sou um player agressivo em todos os jogos e sempre linha de frente!', 'Oi! sou o Ryan!', '4'),
(23, 'renam', 'Renam', 'renam@gmail.com', '1234', '7300', 1, 0, 1, '0000-00-00', '../assets/profile_pics/1 (4).jpg', 'RJ', '2024-10-05', 'Array()', 1, '[]', 'Jogo de tudo de todas as formas!', 'Sou o Renam, sou bem parça', '5'),
(24, 'leandro', 'Leandro', 'leandro@gmail.com', '1234', '1187', 1, 0, 1, '0000-00-00', '../assets/profile_pics/1 (4).jpeg', 'RS', '2024-10-05', 'Array()', 1, '[]', 'Jogo mais passivo e de boa', 'Olá eu sou o Leandro, sou homem e é isso!', '4'),
(25, 'newadm', 'NewAdm', 'newadm@gmail.com', '95ed74ef1dd688121455863440055724', '9760', 2, 0, 1, '0000-00-00', '../assets/profile_pics/1 (5).jpeg', '--', '2024-10-06', '', 0, '[]', '-', '-', ''),
(26, 'jorge', 'Jorge', 'jorgemail@mail.com', '75364292a55268339ba9ad557f040ec6', '1310', 1, 0, 1, '0000-00-00', '../assets/user.png', '--', '2024-10-16', '', 6, '[{\"idCurr\":7,\"name\":\"EA FC24\",\"data\":[\"\",\"Divisão 4\",[\"GK\",false,\"RB\",false,false,\"CM\",\"CAM\",false,false,false,false]]}]', '-', '-', '3.5'),
(27, 'adm2', 'Adm2', 'adm2@gmail.com', '087ee8fc60d908e1578e159c69db46e3', '6282', 2, 1, 1, '2006-01-11', '../assets/profile_pics/adm2.png', 'SP', '2024-10-16', '', 6, '[{\"idCurr\":4,\"name\":\"Valorant\",\"data\":[\"Adilson22\",\"Diamante\",[false,\"Duelist\",false,\"Sentinel\"]]}]', 'Gerencio a TeamPlay', 'Primo do Ade Miro, Adilson Ministro Dois!', '2');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `users_groups_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `read_chats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`users_groups_id`, `user_id`, `group_id`, `read_chats`) VALUES
(1, 11, 3, 5),
(2, 11, 4, 5),
(25, 9, 4, 1),
(27, 9, 3, 5),
(28, 21, 4, 5),
(29, 15, 5, 0),
(30, 15, 6, 0),
(31, 26, 7, 2),
(33, 15, 7, 2),
(34, 1, 7, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `User ID` (`user_id`),
  ADD KEY `groups_id` (`group_id`);

--
-- Indexes for table `friendship`
--
ALTER TABLE `friendship`
  ADD PRIMARY KEY (`id`),
  ADD KEY `User_1` (`user1`),
  ADD KEY `User_2` (`user2`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `Creator_ID` (`creator_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_jogo` (`id_jogo`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `From` (`rfrom`),
  ADD KEY `Rating` (`rating`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Org` (`organizer`),
  ADD KEY `Game` (`game`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Fav_game` (`favorite_game`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`users_groups_id`),
  ADD KEY `Groups ID` (`group_id`),
  ADD KEY `User_ID` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `friendship`
--
ALTER TABLE `friendship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `users_groups_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `User ID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `groups_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);

--
-- Constraints for table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `User_1` FOREIGN KEY (`user1`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `User_2` FOREIGN KEY (`user2`) REFERENCES `users` (`id`);

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `Creator_ID` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `games` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `From` FOREIGN KEY (`rfrom`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `Rating` FOREIGN KEY (`rating`) REFERENCES `users` (`id`);

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `Game` FOREIGN KEY (`game`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `Org` FOREIGN KEY (`organizer`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `Fav_game` FOREIGN KEY (`favorite_game`) REFERENCES `games` (`id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `Groups ID` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
