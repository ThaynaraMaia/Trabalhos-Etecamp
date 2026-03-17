-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 08:09 PM
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
-- Database: `banco`
--

-- --------------------------------------------------------

--
-- Table structure for table `adm`
--

CREATE TABLE `adm` (
  `id_adm` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adm`
--

INSERT INTO `adm` (`id_adm`, `nome`, `email`, `senha`) VALUES
(1, 'Yasmin Adm', 'yasminadm@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `cuidados`
--

CREATE TABLE `cuidados` (
  `id` int(11) NOT NULL,
  `cuidado` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `concluido` tinyint(1) NOT NULL,
  `id_semana` int(11) DEFAULT 0,
  `id_gravida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuidados`
--

INSERT INTO `cuidados` (`id`, `cuidado`, `descricao`, `concluido`, `id_semana`, `id_gravida`) VALUES
(0, '1', '', 1, 2, 2),
(193, '1', '', 1, 2, 13),
(194, '2', '', 1, 2, 13),
(195, '3', '', 1, 2, 13),
(196, '4', '', 1, 2, 13),
(197, '5', '', 1, 2, 13),
(198, '6', '', 1, 2, 13),
(199, '1', '', 1, 2, 13);

-- --------------------------------------------------------

--
-- Table structure for table `questionario`
--

CREATE TABLE `questionario` (
  `id` int(11) NOT NULL,
  `quantidade_respostas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questionario`
--

INSERT INTO `questionario` (`id`, `quantidade_respostas`) VALUES
(1, 7),
(2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `status` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data`, `status`) VALUES
(1, 'Yasmin', 'yasmin@gmail.com', '1234', '2024-08-01', '0'),
(2, 'Marianne', 'marianne@gmail.com', '1234', '2024-07-01', '0'),
(3, 'Teste', 'testeadm@gmail.com', '1234', '2024-10-01', '0'),
(4, 'Fulana', 'fulana@gmail.com', '1234', '2024-06-01', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adm`
--
ALTER TABLE `adm`
  ADD PRIMARY KEY (`id_adm`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adm`
--
ALTER TABLE `adm`
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
