-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/10/2024 às 21:57
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
-- Banco de dados: `cantina_escolar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id_produto`, `id_usuario`, `quantidade`, `preco`) VALUES
(10, 59, 1, 1.99);

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrosel`
--

CREATE TABLE `carrosel` (
  `id` int(11) NOT NULL,
  `imagem_1` varchar(300) NOT NULL,
  `imagem_2` varchar(300) NOT NULL,
  `imagem_3` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrosel`
--

INSERT INTO `carrosel` (`id`, `imagem_1`, `imagem_2`, `imagem_3`) VALUES
(1, '1.png', '2.png', '3.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rodape`
--

CREATE TABLE `rodape` (
  `id_rodape` int(11) NOT NULL,
  `telefone` varchar(30) NOT NULL,
  `celular` varchar(30) NOT NULL,
  `instagram` varchar(30) NOT NULL,
  `facebook` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `rodape`
--

INSERT INTO `rodape` (`id_rodape`, `telefone`, `celular`, `instagram`, `facebook`) VALUES
(1, '+55 (11) 4039-7898', 'Etec de Campo Limpo Paulista', 'etecamp_oficial		', 'Etec de Campo Limpo Paulista');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_funcionarios`
--

CREATE TABLE `tbl_funcionarios` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(300) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(8) NOT NULL,
  `tipo` varchar(11) NOT NULL,
  `status` varchar(1) NOT NULL,
  `foto` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_funcionarios`
--

INSERT INTO `tbl_funcionarios` (`id`, `nome_completo`, `cpf`, `email`, `senha`, `tipo`, `status`, `foto`) VALUES
(1, 'Administrador', '78945632145', 'administrador@gmail.com', '123456', '1', '1', 'perfilimg.jpg'),
(2, 'Funcionário', '96336985973', 'funcionario@gmail.com', '852367', '2', '1', 'perfilimg.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_itens_pedido`
--

CREATE TABLE `tbl_itens_pedido` (
  `id_pedidos` int(11) NOT NULL,
  `nome_produto` varchar(150) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `quantidade` int(200) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `preco_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_itens_pedido`
--

INSERT INTO `tbl_itens_pedido` (`id_pedidos`, `nome_produto`, `id_usuario`, `quantidade`, `preco_unitario`, `preco_total`) VALUES
(49, 'Chocolate Lacta Oreo 90g', 51, 1, 6.00, 6.00),
(49, 'Bala fini de beijos 15g', 51, 1, 2.00, 2.00),
(49, 'Bala fini Dentaduras 500g', 51, 1, 7.00, 7.00),
(50, ' Regrigerante Coca cola 250ml', 51, 1, 2.50, 2.50),
(50, 'Bala fini de beijos 15g', 51, 1, 2.00, 2.00),
(51, ' Regrigerante Coca cola 250ml', 71, 1, 2.50, 2.50),
(51, 'Chocolate Lacta Oreo 90g', 71, 1, 6.00, 6.00),
(51, 'Bala fini de beijos 15g', 71, 1, 2.00, 2.00),
(52, 'Bala fini de beijos 15g', 51, 1, 2.00, 2.00),
(53, 'Bala fini de beijos 15g', 51, 1, 2.00, 2.00),
(54, ' Regrigerante Coca cola 250ml', 51, 1, 2.50, 2.50),
(54, 'Chocolate Lacta Oreo 90g', 51, 1, 6.00, 6.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_pedidos`
--

CREATE TABLE `tbl_pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `preco_total` decimal(10,2) NOT NULL,
  `codigo` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_pedidos`
--

INSERT INTO `tbl_pedidos` (`id_pedido`, `id_usuario`, `data_pedido`, `status`, `preco_total`, `codigo`) VALUES
(49, 51, '2024-09-28', 'Andamento', 15.00, '66f73e25'),
(50, 51, '2024-10-02', 'Andamento', 4.50, '66fd3b87'),
(51, 71, '2024-10-02', 'Concluído', 10.50, '66fd4ceb'),
(53, 51, '2024-10-02', 'Concluído', 2.00, '66fd4fd8'),
(54, 51, '2024-10-02', 'Concluído', 8.50, '66fd9bd0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_produto`
--

CREATE TABLE `tbl_produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(300) NOT NULL,
  `descricao_produto` text NOT NULL,
  `descricao_curta` text NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `quantidade_estoque` int(200) NOT NULL,
  `img` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_produto`
--

INSERT INTO `tbl_produto` (`id_produto`, `nome_produto`, `descricao_produto`, `descricao_curta`, `preco`, `quantidade_estoque`, `img`) VALUES
(3, 'Chocolate Lacta Oreo 90g', 'A Barra de Chocolate Lacta Oreo Ao Leite combina a suavidade do chocolate ao leite com os pedaços crocantes de biscoito Oreo. O sabor é uma mistura deliciosa de doce e cremoso do chocolate ao leite com o toque característico e levemente ácido dos biscoitos Oreo. A textura é rica e indulgente, com a crocância dos pedaços de Oreo proporcionando um contraste agradável ao chocolate macio. ', 'Barra de chocolate Lacta Oreo, sabor irresistível.', 6.00, 18, 'Chocolate_oreo.png'),
(4, ' Regrigerante Coca cola 250ml', 'A Coca-Cola de 250 ml oferece o sabor clássico e refrescante que é conhecido mundialmente. Cada gole traz a combinação equilibrada de doçura e leve acidez, com um toque de caramelo que caracteriza a Coca-Cola. A carbonatação proporciona uma sensação efervescente e vibrante, tornando-a uma escolha popular para quem busca uma bebida refrescante e revitalizante.', 'A clássica Coca-Cola em uma garrafa pequena, refrescante em qualquer ocasião.', 2.50, 11, 'coca.png'),
(5, 'Bala fini de beijos 15g', 'A Bala Fini Beijos é uma guloseima popular conhecida por seu sabor doce e sua textura macia. Cada bala tem um formato de beijo, o que a torna visualmente atraente e divertida de comer. O sabor é geralmente uma mistura de frutas, com um toque de doçura que agrada a diferentes paladares. A embalagem colorida e o tamanho individual de cada bala fazem dela uma opção prática para uma rápida indulgência. ', 'Balinhas de gelatina Fini sabor beijos, uma doçura leve e divertida.', 2.00, 55, 'fini_beijos.png'),
(6, 'Bala fini Dentaduras 500g', 'A Bala Fini Dentaduras é um doce que se destaca pelo seu formato peculiar e divertido, imitando dentaduras. O sabor é doce e frutado, frequentemente variando entre diferentes sabores de frutas, como morango, laranja, e maçã. A textura é macia e um pouco elástica, proporcionando uma experiência de mastigação agradável. ', 'Balinhas de gelatina Fini sabor Dentadura, uma doçura leve e divertida.', 7.00, 19, 'fini_dentaduras.png'),
(7, 'Salgadinho fofura cebola 90g', 'O Salgadinho Fofura Cebola é um petisco conhecido pelo seu sabor distinto e divertido. Apesar de seu nome, o sabor é geralmente uma combinação saborosa de cebola com um toque adocicado e um leve toque de especiarias, criando uma experiência saborosa e equilibrada.\r\n', 'Salgadinhos crocantes com sabor intenso de cebola, para satisfazer sua vontade.', 3.50, 15, 'fofura_cebola.png'),
(8, 'Salgadinho fofura churrasco 90g', 'O salgadinho Fofura sabor Churrasco é um petisco irresistível que combina crocância e um sabor delicado e cremoso. Cada pedaço oferece uma explosão de sabor de churrasco, com notas suaves e ricas que remetem ao sabor que todos adoramos. A textura leve e areada do salgadinho é ideal para um snack rápido, proporcionando uma sensação de satisfação sem pesar.           ', 'Salgadinhos crocantes com sabor intenso de cebola, para satisfazer sua vontade.', 3.50, 38, 'fofura_churrasco.png'),
(9, 'Chocolate Kit Kat ao leite', 'O chocolate Kit Kat é conhecido por seu sabor doce e equilibrado. Ele combina camadas de wafer crocante com uma cobertura de chocolate ao leite suave e cremoso. A textura do wafer proporciona um contraste agradável com o chocolate,\r\ncriando uma experiência de mastigação crocante e indulgente. O sabor é doce e aveludado, com o chocolate derretendo na boca e o wafer adicionando uma camada extra de crocância.', 'Chocolate Kit Kat ao leite, delicioso e com uma camada de biscoito crocante e magnífico.', 3.50, 39, 'kit kat.png'),
(10, 'Regrigerante Pepsi 200ml', 'O refrigerante Pepsi é conhecido pelo seu sabor doce e refrescante, com um toque distintivo de cola. Ele tem uma carbonatação vibrante que proporciona uma sensação efervescente e leve ao paladar. A Pepsi tende a ter um sabor ligeiramente mais doce e menos ácido do que alguns outros refrigerantes de cola, o que a torna uma escolha popular para quem busca um gosto mais suave e doce. ', 'A clássica Pepsi em uma garrafa pequena, refrescante em qualquer ocasião.', 1.99, 24, 'pepsi1.png'),
(11, 'Pipoca Salgada Vovozinha 50g', 'A Pipoca Salgada Vovozinha é um petisco popular conhecido por sua crocância e sabor delicioso. Feita com milho de pipoca estourado, ela é temperada com sal, proporcionando um gosto salgado e saboroso que agrada a diversos paladares.\r\nA textura é leve e crocante, fazendo dela uma opção ideal para lanches e ocasiões informais..', 'A clássica Pipoca vovozinha, salgadinha e saborosa, perfeita para matar sua fome.', 2.50, 30, 'pipoca_salgada1.png'),
(12, ' Pirulito Big Big sabor Morango ', 'O Pirulito Big Big Morango é um doce conhecido por seu sabor intenso e doce de morango. Este pirulito oferece um gosto frutado e refrescante, que captura a essência do morango em uma forma vibrante e divertida. A textura é sólida e o sabor de morango é tanto doce quanto levemente ácido, proporcionando um equilíbrio saboroso que agrada a diferentes paladares. ', 'Pirulito Big Big individual sabor Morango , com recheio delicioso de chiclete.', 1.00, 54, 'pirulito_big.png'),
(13, 'Salgadinho Fofura Sabor Requeijão 90g', 'O salgadinho Fofura sabor Requeijão é um petisco irresistível que combina crocância e um sabor delicado e cremoso. Cada pedaço oferece uma explosão de sabor de requeijão, com notas suaves e ricas que remetem ao queijo cremoso que todos adoramos. A textura leve e areada do salgadinho é ideal para um snack rápido, proporcionando uma sensação de satisfação sem pesar. O Fofura sabor Requeijão é uma escolha prática e deliciosa para quem busca um sabor diferenciado e reconfortante.', 'Salgadinhos crocantes com sabor intenso de Requeijão, para satisfazer sua vontade.', 3.50, 20, 'saladinho-requeijao.png'),
(14, 'Suco Del Valle sabor Laranja 250ml', 'O Suco Del Valle Sabor Laranja 250 ml é uma bebida refrescante e saborosa, ideal para quem busca um toque natural de laranja. Com um sabor fresco e ligeiramente doce, o suco captura a essência da fruta, oferecendo uma experiência de sabor que é tanto agradável quanto revitalizante. A embalagem de 250 ml é prática e conveniente, perfeita para uma dose individual de suco, seja no café da manhã, durante o lanche ou em qualquer momento do dia. Feito com ingredientes de qualidade, o suco proporciona uma alternativa saudável e saborosa para hidratação e energia, mantendo o frescor e a autenticidade do sabor da laranja.', 'Suco Del Valle sabor Laranja 250ml, uma bebida com experiência completa.', 2.50, 40, 'suco_del_valle-.png'),
(15, 'Salgadinho Torcida sabor Churrasco 70g', 'O Salgadinho Torcida Sabor Churrasco é conhecido por seu sabor intenso e delicioso, que remete ao gosto de um churrasco. Os salgadinhos são crocantes e possuem um tempero robusto, que combina notas defumadas e um toque de especiarias, simulando o sabor típico de carnes grelhadas. A textura crocante e o sabor marcante fazem deste salgadinho que proporcionando uma experiência saborosa e satisfatória.', 'Salgadinho Torcida sabor Churrasco, crocante e com um sabor maravilhoso.', 3.00, 35, 'torcida_churrasco.png'),
(16, 'Trento bites branco 40g', 'O Trento Bites Branco 40g é um chocolate que combina a suavidade do chocolate branco com pequenos pedaços crocantes. Cada mordida oferece uma experiência doce e cremosa, com a textura rica do chocolate branco complementada por\r\ncrocâncias que adicionam um contraste agradável. A embalagem de 40g é prática, ideal para um lanche rápido ou para um momento de indulgência. ', 'recheado sabor chocolate Wafer meio amargo coberto com chocolate branco.', 3.00, 56, 'trento_branco.png'),
(28, 'Salgadinho Doritos Sabor Queijo Nacho 20g', 'Doritos Queijo Nacho é famoso por sua crocância e sabor marcante de queijo com um toque apimentado. Cada pedaço oferece uma experiência intensa e saborosa, perfeita para ser consumido sozinho ou acompanhado de molhos e patês. O tempero icônico de queijo faz desse snack uma escolha irresistível para momentos de descontração.', 'Doritos sabor Queijo Nacho, crocante e com tempero intenso. ', 2.49, 20, 'doritos.jfif'),
(29, 'Bala fini de ursinhos 15g', 'Os ursinhos de gelatina da Fini são um sucesso entre os fãs de doces. Com uma variedade de cores vibrantes e sabores frutados, esses ursinhos são macios e agradáveis ao paladar, garantindo um sabor doce e refrescante. Perfeitos para compartilhar ou para saborear em qualquer momento do dia, eles proporcionam uma experiência divertida e deliciosa.', 'Fini Ursinhos de Gelatina, doces, macios e coloridos.  ', 2.00, 13, 'dcf97dcdf22e52632528c60df69ce7d9.jpeg'),
(30, 'Salgadinho Cheetos Sabor Requeijão 20g', 'Cheetos Requeijão combina a tradicional crocância do snack com o sabor suave e cremoso do requeijão. Cada mordida é uma explosão de sabor, trazendo a textura leve e crocante que derrete na boca. Ideal para qualquer ocasião, desde um lanche rápido até uma festa com amigos.', 'Cheetos sabor Requeijão, crocante e com um toque cremoso.  ', 2.49, 14, 'f14217d0449bc959db11d630db8408d8.jpeg'),
(31, 'Refrigerante Fanta Uva 250ml', 'O Refrigerante Fanta Uva traz um sabor marcante e inconfundível que encanta os apaixonados pela fruta. Com uma mistura perfeita de doçura e frescor, cada gole é uma explosão de sabor que lembra a uva suculenta. Ideal para acompanhar refeições, festas ou momentos de lazer, a Fanta Uva é uma escolha divertida e refrescante. Sua cor vibrante e efervescência tornam cada experiência ainda mais prazerosa, garantindo que você sempre queira repetir!', ' Refrigerante Fanta Uva, sabor intenso e doce, perfeito para refrescar a qualquer hora.', 1.89, 9, '81052067c459ff6f084cd2f10e90169a.jpeg'),
(32, ' Bala Yogurte sabor de morango.', 'A Bala Yogurte é conhecida por seu sabor delicado e cremoso, que proporciona uma experiência única a cada mordida. Com um toque levemente ácido, essa bala remete ao sabor refrescante do iogurte, tornando-se uma opção irresistível para quem aprecia doces com um perfil mais suave. Além do sabor, a Bala Yogurte é valorizada por sua textura macia, que derrete na boca e traz um prazer instantâneo. Perfeita para momentos de descontração, essa bala é uma escolha saborosa para todas as idades!', 'Bala Yogurte, sabor suave e cremoso, deliciosa e fácil de mastigar.', 0.45, 28, 'a184a17fb076cbddf58479687359210a.jpeg'),
(33, 'Refrigerante Fanta Laranja 200ml', 'O Refrigerante Fanta Laranja é a combinação perfeita de doçura e acidez, capturando o verdadeiro sabor das laranjas frescas. Com sua cor vibrante e efervescência, cada gole é uma explosão de frescor que revitaliza e alegra. Ideal para acompanhar refeições, festas ou simplesmente para um momento de prazer, a Fanta Laranja é uma escolha clássica que agrada a todos os públicos. Sinta o refresco e a energia que essa bebida traz a cada ocasião!', ' Refrigerante Fanta Laranja, sabor cítrico e refrescante, uma explosão de sabor em cada gole.', 1.89, 12, 'ba69cd7a9dd01c8763d5dcda7e371a3e.jpeg'),
(34, 'Chocolate Lacta Ouro Branco 90g', 'O Chocolate Lacta Ouro Branco é uma verdadeira indulgência para os amantes de chocolate. Com sua textura suave e cremosa, ele derrete na boca, proporcionando uma experiência única a cada pedaço. Seu sabor doce e aveludado é característico, combinando a riqueza do chocolate branco com notas de baunilha que encantam o paladar. Perfeito para momentos de prazer, seja em um lanche ou como um doce para compartilhar, o Lacta Ouro Branco é uma escolha que transforma qualquer ocasião em um momento especial. Sinta o prazer de se deliciar com cada quadradinho!', ' Chocolate Lacta Ouro Branco, irresistível e cremoso, com sabor doce e aveludado.', 6.00, 33, '1e456f02806327647b943bb285923693.jpeg'),
(35, ' Chocolate Trento Massimo Morango 30g', 'O Chocolate Trento Massimo Morango é uma verdadeira explosão de sabor que une o prazer do chocolate ao leite com um recheio cremoso de morango. Cada pedaço oferece uma combinação perfeita de doçura e frescor, trazendo a essência da fruta para cada mordida. Sua textura suave e rica, aliada ao sabor intenso, faz desse chocolate uma escolha irresistível para quem busca um momento de indulgência. Ideal para compartilhar ou saborear sozinho, o Trento Massimo Morango transforma qualquer ocasião em uma experiência deliciosa e especial. Sinta a alegria de se deliciar com esse chocolate único!', ' Chocolate Trento Massimo Morango, recheado e cremoso, combina chocolate ao leite com sabor intenso de morango', 2.99, 15, '0e411d13e600966813a61b75be01a211.jpeg'),
(36, ' Refrigerante Fanta Guaraná 200ml', 'O Refrigerante Fanta Guaraná combina o sabor exótico do guaraná com a efervescência refrescante de um refrigerante clássico. Com um gosto adocicado e levemente amargo, essa bebida é perfeita para quem busca um sabor distinto e energizante. Cada gole traz uma explosão de frescor, tornando a Fanta Guaraná uma escolha ideal para festas, encontros com amigos ou momentos de lazer. Sua cor vibrante e sabor envolvente vão conquistar seu paladar!', 'Refrigerante Fanta Guaraná, sabor autêntico e refrescante, ideal para qualquer ocasião.', 1.89, 23, '57f6d5848d088a6171dd8a1c490a05ee.jpeg'),
(37, 'Pirulito Fantasy Festa Azedinho', 'O Pirulito Fantasy Festa Azedinho é uma explosão de sabor que vai encantar os amantes de doces com seu toque ácido e divertido. Com uma variedade de sabores que misturam o azedinho com a doçura, esses pirulitos são ideais para festas, aniversários ou simplesmente para dar um up no dia a dia. Cada pirulito tem uma textura crocante por fora e um recheio surpreendente que derrete na boca, proporcionando uma experiência única.', ' Pirulito Fantasy Festa Azedinho, sabor intenso e divertido, perfeito para alegrar qualquer celebração.', 0.79, 47, 'e42fa65f916927f5fee3180374916034.jpeg'),
(38, 'Bala Fini Salada de Frutas 80g', 'A Bala Fini Salada de Frutas é uma deliciosa combinação de sabores que traz a alegria da fruta para cada mordida. Com uma textura macia e agradável, essas balas oferecem uma explosão de sabores frutados que lembram uma verdadeira salada de frutas. Cada pedaço é uma mistura de notas doces e ácidas, proporcionando uma experiência refrescante e divertida. ', 'Bala Fini Salada de Frutas, mistura de sabores frutados e refrescantes, irresistivelmente mastigável.', 2.50, 12, '5efa9520054b126fb0ad50462fb4144f.jpeg'),
(39, 'Salgadinho Cheetos Lua Sabor Parmesão 20g', 'Cheetos Lua Sabor Parmesão oferece uma experiência única com sua textura leve e crocante, combinada com o sabor intenso e marcante do queijo parmesão. Cada mordida traz uma explosão de sabor que vai conquistar os amantes de queijo. Perfeito para petiscar a qualquer hora, seja em festas, reuniões ou como um lanche prático, esses snacks são uma escolha deliciosa e divertida. Sinta a crocância e o prazer do parmesão a cada pacotinho e transforme seus momentos de lanche em uma verdadeira festa de sabor!', 'Cheetos Lua Sabor Parmesão, snacks crocantes com o irresistível sabor do queijo parmesão.', 2.49, 0, 'acfe2ca4d07df5ee0189f829aace7032.jpeg'),
(40, 'Bis Xtra Ao Leite 45g', 'Bis Xtra Ao Leite é a combinação perfeita de textura e sabor. Com camadas de wafer crocante envoltas em um rico e cremoso chocolate ao leite, cada pedaço oferece uma experiência de sabor única e deliciosa. A crocância do wafer contrasta perfeitamente com a suavidade do chocolate, tornando-o o snack ideal para qualquer momento do dia. Seja para acompanhar um café, em um lanche rápido ou para adoçar sua pausa, Bis Xtra Ao Leite é uma escolha que vai satisfazer sua vontade de doce de maneira surpreendente. Experimente e deixe-se envolver por essa deliciosa combinação!', 'Bis Xtra Ao Leite, wafer crocante recheado com um cremoso chocolate ao leite, irresistível a cada mordida.', 3.59, 20, '1063af0f906efaab5918d74455eb8077.jpeg'),
(42, 'Bala 7 Belo sabor Framboesa', 'A bala 7 Belo Framboesa é conhecido por seu sabor marcante e refrescante. A versão de framboesa é particularmente apreciada por seu gosto doce e ligeiramente ácido, que remete ao sabor real da fruta. Além do sabor, a 7 Belo é reconhecida por sua textura macia e agradável ao paladar. ', 'Bala 7 Belo individual sabor framboesa, deliciosa e mastigável.', 0.50, 36, '63116ec309a0a0c8c618ebf582d8baf0.png'),
(43, 'Tortuqueta de Chocolate Preto 18g', 'A Tortuqueta de Chocolate Preto oferece a combinação perfeita entre a crocância do biscoito e a cremosidade do recheio de chocolate preto. O sabor intenso do chocolate, com um toque amargo característico, torna este produto irresistível para quem aprecia doces sofisticados. Ideal para acompanhar um café ou para aquele momento especial do dia.', 'Biscoito crocante com recheio de chocolate preto.', 1.79, 30, '7671bce170f669ed003b0cf755e0a51c.jpg'),
(44, 'Suco Del Valle de Uva 250ml', 'O Suco Del Valle de Uva é feito com as melhores uvas, trazendo um sabor autêntico e fresco da fruta para sua mesa. Sem adição de conservantes e com um sabor naturalmente doce, este suco é a escolha perfeita para qualquer refeição, oferecendo a qualidade e o frescor que você merece.', 'Suco natural de uva, sem conservantes.', 2.50, 40, '9a9bf0a1b65e76d107e01f5f0c5fe701.jpg'),
(45, 'Salgadinho Torcida de Cebola 70g', 'Torcida de Cebola é o petisco ideal para quem ama um sabor forte e marcante. Cada pedaço oferece uma explosão de sabor de cebola em uma textura leve e crocante, perfeito para acompanhar um filme, um lanche rápido ou para compartilhar com os amigos em momentos de descontração.', 'Snack crocante com sabor de cebola.', 3.00, 34, 'e82c4d0b6d91cf263c6debf9a1cb7a01.jpg'),
(46, 'Bis Xtra Branco 45g', 'O BIS Extra Branco é o clássico BIS em uma versão ainda mais indulgente, com uma camada extra de chocolate branco cremoso que se derrete na boca. Sua textura crocante, combinada com o doce suave do chocolate branco, faz desse snack uma tentação a cada mordida.', 'Camadas crocantes com cobertura de chocolate branco extra.', 3.59, 34, '5118f598674997b30dedc3ba4b247720.jpg'),
(47, 'Salgadinho Cheetos Mix de Queijo 36g', 'Cheetos Mix de Queijo traz a união perfeita de diferentes texturas e sabores de queijo em um só pacote. Cada mordida oferece uma experiência única, com o clássico sabor de queijo que só Cheetos sabe fazer. Perfeito para quem adora variar nas texturas e curte um snack saboroso e crocante para qualquer momento do dia.', 'Mix crocante de snacks com sabor intenso de queijo.', 3.20, 40, 'a3624daa526f21c24d6c46f84eb065a2.jpg'),
(48, 'Refrigerante Sprite 250ml', 'Sprite é um refrigerante à base de limão, conhecido por sua leveza e refrescância. Com um sabor equilibrado entre o cítrico e o doce, é a bebida ideal para acompanhar refeições ou ser consumida sozinha, proporcionando uma sensação refrescante a cada gole. Sem cafeína, é uma opção perfeita para qualquer ocasião.', 'Refrigerante de limão, leve e refrescante.', 1.89, 39, '282906c0d661cc1f727131a2ff928357.jpg'),
(49, 'Treto de Chocolate Preto 32g', 'O Treto de Chocolate Preto é perfeito para quem busca o sabor puro e intenso do chocolate amargo. Feito com cacau de alta qualidade, esse chocolate proporciona uma experiência rica e profunda, com o toque amargo característico do chocolate preto. Ideal para apreciar como sobremesa ou acompanhamento de bebidas quentes.', 'Chocolate preto de alta qualidade, com sabor intenso.', 2.00, 34, '3e8c77ef7ffa6fc717b297b342eebfbf.jpg'),
(50, 'Salgadinho Torcida de Queijo70g', 'A Torcida de Queijo é um petisco irresistível para quem ama o sabor marcante de queijo. Cada unidade oferece uma textura crocante e um sabor forte, perfeito para ser consumido em momentos de lazer, assistindo a filmes ou compartilhado com amigos. Uma opção deliciosa para qualquer hora.', 'Snack crocante com sabor intenso de queijo.', 3.00, 34, '9180af42833055cacff03c07d168d5e2.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(200) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `senha` varchar(12) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `foto` varchar(400) NOT NULL,
  `saldo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id`, `nome_completo`, `cpf`, `email`, `senha`, `tipo`, `status`, `foto`, `saldo`) VALUES
(51, 'Ana Gabrielly de Almeida ', '74196324785', 'ana@gmail.com', '787878', '0', '1', 'd83833bce7d918119e1726d7c6620289.jpg', 17.02),
(59, 'Rhafaela Fernandes Pereira', '78965485973', 'rhafaa@gmail.com', '040406', '0', '1', 'perfilimg.jpg\r\n', 50.00),
(60, 'Maria Julia Da Silva Melo', '47066553829', 'maria@gmail.com', '191206', '0', '1', 'perfilimg.jpg\r\n', 55.00),
(61, 'Alanis Castro Brasões', '5858987321', 'alanis@gmail.com', '963258', '0', '0', 'perfilimg.jpg\r\n', 0.00),
(71, 'Pedro Henry Ferreira', '74125874125', 'pedro@gmail.com', '121212', '0', '1', 'perfilimg.jpg', 89.50);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrosel`
--
ALTER TABLE `carrosel`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rodape`
--
ALTER TABLE `rodape`
  ADD PRIMARY KEY (`id_rodape`);

--
-- Índices de tabela `tbl_funcionarios`
--
ALTER TABLE `tbl_funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `tbl_produto`
--
ALTER TABLE `tbl_produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrosel`
--
ALTER TABLE `carrosel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `rodape`
--
ALTER TABLE `rodape`
  MODIFY `id_rodape` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbl_funcionarios`
--
ALTER TABLE `tbl_funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `tbl_produto`
--
ALTER TABLE `tbl_produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  ADD CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tbl_usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
