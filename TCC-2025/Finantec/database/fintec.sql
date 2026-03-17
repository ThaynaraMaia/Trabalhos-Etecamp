-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/12/2025 às 02:40
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
-- Banco de dados: `fintec`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `calculo`
--

CREATE TABLE `calculo` (
  `id_calculo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_calculo` varchar(100) NOT NULL,
  `valor_principal` decimal(10,2) DEFAULT NULL,
  `taxa_juros` decimal(5,2) DEFAULT NULL,
  `tempo` int(11) DEFAULT NULL,
  `resultado` decimal(10,2) NOT NULL,
  `data_calculo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `calculo`
--

INSERT INTO `calculo` (`id_calculo`, `id_usuario`, `tipo_calculo`, `valor_principal`, `taxa_juros`, `tempo`, `resultado`, `data_calculo`) VALUES
(1, 3, 'LCI', 1000.00, 8.00, 3, 1259.71, '2025-09-29 11:19:19'),
(5, 12, 'Juros Simples', 1000.00, 5.00, 2, 1100.00, '2025-10-03 01:45:16'),
(8, 2, 'LCI', 10000.00, 90.00, 2, 12607.92, '2025-10-07 21:47:40'),
(9, 3, 'Ações (Juros Compostos)', 10000.00, 10.00, 5, 16453.09, '2025-10-09 02:36:02'),
(10, 14, 'LCI', 10000.00, 90.00, 2, 12607.92, '2025-10-10 03:08:57'),
(11, 17, 'Juros Compostos', 10000.00, 10.00, 5, 16453.09, '2025-10-10 11:50:59'),
(12, 18, 'Tesouro Direto', 10100.00, 10.00, 3, 12860.98, '2025-10-10 12:01:37'),
(13, 6, 'Ações (Juros Compostos)', 49000.00, 13.50, 13, 280625.73, '2025-10-22 15:19:53'),
(14, 19, 'Juros Simples', 3000.00, 2.00, 1, 3060.00, '2025-10-27 01:06:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_despesa`
--

CREATE TABLE `categoria_despesa` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria_despesa`
--

INSERT INTO `categoria_despesa` (`id_categoria`, `nome`, `id_usuario`) VALUES
(1, 'Saúde', NULL),
(2, 'Educação', NULL),
(3, 'Lazer', NULL),
(4, 'Alimentação', NULL),
(5, 'Transporte', NULL),
(6, 'Moradia', NULL),
(7, 'Outros', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudo`
--

CREATE TABLE `conteudo` (
  `id_conteudo` int(11) NOT NULL,
  `pagina` varchar(50) NOT NULL,
  `secao` varchar(100) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `texto` text NOT NULL,
  `data_postagem` date DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conteudo`
--

INSERT INTO `conteudo` (`id_conteudo`, `pagina`, `secao`, `titulo`, `texto`, `data_postagem`, `autor_id`) VALUES
(1, 'home', 'principal', 'Domine seu Dinheiro, Conquiste seu Futuro', '<p>Aprender educação financeira desde cedo é a chave para ter controle sobre sua vida. Saber como economizar, investir e planejar seus gastos evita dívidas e frustrações no futuro. Quanto mais você entende sobre dinheiro, mais liberdade e oportunidades terá para realizar suas metas.</p>', '2025-10-01', 1),
(2, 'informativaAcoes', 'introducao', 'O que são e<span class=\"accent\"> como funcionam:</span>', '<p><strong>As ações representam</strong> pequenas partes de uma empresa listada na Bolsa de Valores. Quando alguém compra ações, torna-se sócio daquela empresa, participando de seus lucros e também de seus riscos. O valor das ações varia de acordo com fatores como desempenho da empresa, situação do país e expectativas do mercado.</p><p>Investir em ações exige conhecimento e paciência, pois os preços podem subir e cair rapidamente. Contudo, no longo prazo, historicamente as ações estão entre os investimentos mais rentáveis. Para os jovens, começar cedo é uma grande vantagem, já que o tempo é um aliado poderoso no crescimento do patrimônio por meio da valorização e dos dividendos.</p>', '2025-10-01', 1),
(3, 'informativaAcoes', 'vantagens', 'Vantagens das ações', '<p class=\"lead\">A principal vantagem das ações é o potencial de altos ganhos, já que as empresas podem se valorizar bastante ao longo dos anos. Além disso, muitas companhias distribuem dividendos, que são partes do lucro repassadas aos acionistas, proporcionando uma renda passiva adicional. Outro benefício é a possibilidade de diversificação, já que a Bolsa de Valores reúne empresas de diversos setores, permitindo criar uma carteira equilibrada. Para os jovens, investir em ações também é uma forma de se conectar com grandes marcas que já fazem parte do dia a dia, como empresas de tecnologia, bancos e varejistas. É um investimento dinâmico, que proporciona aprendizado constante e pode trazer resultados expressivos no futuro.</p>', '2025-10-01', 1),
(4, 'informativaAcoes', 'desvantagens', 'Desvantagens das ações', '<p>O maior risco das ações é a volatilidade: os preços podem oscilar muito em curtos períodos, o que pode assustar investidores iniciantes. Além disso, exige tempo e dedicação para estudar empresas, balanços financeiros e notícias do mercado, o que pode ser desafiador para quem está começando. Outro ponto negativo é que não existe garantia de retorno, diferente da renda fixa ou de investimentos protegidos pelo FGC. Para os jovens, isso pode ser uma experiência de aprendizado valiosa, mas também arriscada se não houver estratégia. O ideal é começar com valores menores, buscar conhecimento e sempre pensar no longo prazo para não ser prejudicado pelas oscilações de curto prazo.</p>', '2025-10-01', 1),
(5, 'informativaCC', 'introducao', 'O que são e<span class=\"accent\"> como funcionam:</span>', '<p><strong>Câmbio é</strong> o nome que se dá à troca de uma moeda por outra, como trocar reais por dólares quando viajamos para outro país. A taxa de câmbio representa quanto custa comprar uma unidade de moeda estrangeira em relação à nossa. Ela pode subir ou cair todos os dias, de acordo com a economia, oferta e procura de moedas, decisões do governo e acontecimentos no mundo. Entender o câmbio é importante porque ele afeta os preços de produtos importados, viagens internacionais e até o valor de investimentos que dependem do dólar. Para os jovens, aprender sobre câmbio ajuda a entender como acontecimentos globais podem impactar o dia a dia, como o aumento de preços de eletrônicos ou de passagens aéreas.</p>', '2025-10-01', 1),
(6, 'informativaCC', 'criptomoedas', 'O que são criptomoedas:', '<p class=\"lead\">Criptomoedas são moedas digitais criadas na internet e protegidas por tecnologia de criptografia, que garante segurança e dificulta fraudes. A mais conhecida é o Bitcoin, mas existem milhares de outras, como Ethereum, Binance Coin e Solana. Diferente do dinheiro tradicional, elas não são emitidas por governos ou bancos centrais, funcionando de forma descentralizada em uma rede de computadores. O valor de uma criptomoeda varia bastante porque depende da confiança das pessoas e do interesse dos investidores. Para os jovens, esse é um tema muito popular, já que combina tecnologia e finanças, mas é importante entender bem antes de investir, pois o preço pode subir ou cair rapidamente.</p>', '2025-10-01', 1),
(7, 'informativaCC', 'cuidados', 'Cuidados e oportunidades ao investir', '<p class=\"lead\">As criptomoedas e o câmbio podem trazer oportunidades de lucro, mas também exigem cuidado. Como os preços variam bastante, é possível ganhar, mas também perder dinheiro em pouco tempo. Por isso, especialistas recomendam que, antes de investir, seja feito um bom planejamento financeiro e que apenas uma parte pequena do dinheiro seja colocada nesse tipo de investimento. Também é essencial usar plataformas seguras para comprar e vender moedas digitais, evitando golpes comuns. Uma boa dica é usar ferramentas como a Finantec, que ajudam a aprender sobre investimentos de forma prática e consciente, preparando o jovem para tomar decisões seguras no mercado financeiro.</p>', '2025-10-01', 1),
(8, 'informativaCDB', 'introducao', 'O que é e<span class=\"accent\"> como funciona:</span>', '<p><strong>O CDB é</strong> um título de renda fixa emitido pelos bancos, em que o investidor empresta dinheiro à instituição em troca de juros. Funciona como um “contrato” entre você e o banco: você aplica o valor e, após um prazo definido, recebe de volta o dinheiro com rendimento. Existem CDBs de liquidez diária, que permitem resgatar a qualquer momento, e outros de prazo fixo, que só liberam o dinheiro no vencimento. Os rendimentos podem ser prefixados (fixos), pós-fixados (atrelados ao CDI) ou híbridos (parte fixa e parte atrelada à inflação). O CDB também conta com a garantia do FGC, que cobre até R$ 250 mil por CPF e instituição, caso o banco quebre. Para os jovens, é uma forma simples e segura de aprender a aplicar fora da poupança e conhecer o mercado bancário como investidor.</p>', '2025-10-01', 1),
(9, 'informativaCDB', 'vantagens', 'Vantagens do CDB', '<p class=\"lead\">O CDB é um investimento muito atrativo pela combinação de segurança e boa rentabilidade. Em muitos casos, ele pode render mais que a poupança e até mais que o Tesouro Selic, especialmente quando emitido por bancos médios, que pagam taxas maiores para atrair investidores. Outra vantagem é a variedade de prazos e modalidades, que se adaptam a diferentes objetivos, desde reserva de emergência até metas de longo prazo. Além disso, com a liquidez diária em alguns CDBs, o investidor tem flexibilidade para resgatar quando precisar, mantendo o dinheiro sempre acessível. Para jovens, essa opção é prática porque está disponível diretamente nos bancos digitais, sem burocracia e com valores de entrada baixos. É um ótimo “meio termo” entre simplicidade e rentabilidade.</p>', '2025-10-01', 1),
(10, 'informativaCDB', 'desvantagens', 'Desvantagens do CDB', '<p class=\"lead\">Apesar dos benefícios, o CDB também tem pontos negativos que devem ser levados em conta. A primeira desvantagem é que ele não é isento de Imposto de Renda, e a taxa varia conforme o tempo que o dinheiro fica aplicado. Além disso, nos CDBs de prazo fixo, não é possível resgatar antes do vencimento, o que pode limitar a flexibilidade do investidor. Outro fator é que, nos grandes bancos, os CDBs geralmente oferecem rendimentos baixos, o que pode desanimar quem não pesquisa outras instituições. Para os jovens, isso ensina a importância de comparar ofertas e entender os prazos antes de investir. O CDB é uma ótima opção, mas precisa ser escolhido com atenção para que realmente valha a pena.</p>', '2025-10-01', 1),
(11, 'informativaCDI', 'introducao', 'O que é o CDI e<span class=\"accent\"> como funciona:</span>', '<p><strong>O CDI, ou Certificado de Depósito Interbancário, é</strong> uma taxa usada entre os bancos para emprestar dinheiro uns aos outros por curtos períodos, geralmente de um dia para o outro. Embora os investidores comuns não comprem o CDI diretamente, ele serve como referência para muitos investimentos de renda fixa, como CDBs, LCIs e fundos de investimento. Basicamente, ele indica a rentabilidade que um banco pode pagar por esses produtos. Entender o CDI é importante porque ele ajuda a comparar investimentos e a calcular quanto seu dinheiro pode render. Para jovens, aprender sobre o CDI ajuda a entender como o sistema financeiro funciona por trás dos investimentos que vemos na prática.</p>', '2025-10-01', 1),
(12, 'informativaCDI', 'importancia', 'Por que o CDI é importante para os investimentos', '<p class=\"lead\">O CDI é usado como um “padrão” de rentabilidade no mercado de renda fixa. Por exemplo, quando um CDB promete render 100% do CDI, isso significa que ele vai acompanhar de perto o desempenho dessa taxa. Investimentos atrelados ao CDI geralmente oferecem segurança e previsibilidade, já que a taxa segue a política de juros do país. Para jovens que estão começando a investir, entender o CDI é útil porque ajuda a comparar diferentes produtos e perceber qual oferece o melhor retorno sem aumentar demais o risco. Ele também é uma forma de acompanhar a economia, já que quando a taxa de juros sobe ou cai, isso impacta diretamente nos rendimentos.</p>', '2025-10-01', 1),
(13, 'informativaCDI', 'cuidados', 'Cuidados e oportunidades ao investir em produtos atrelados ao CDI', '<p class=\"lead\">Investimentos baseados no CDI são considerados de baixo risco e podem ser uma boa opção para começar a poupar ou formar uma reserva de emergência. No entanto, é importante lembrar que o rendimento acompanha a taxa básica de juros do país, então em períodos de juros muito baixos, os ganhos podem ser menores. Para aproveitar essas oportunidades, é recomendado usar plataformas como a Finantec, que ajudam a aprender sobre investimentos e a planejar estratégias de forma prática e segura. Dessa forma, jovens investidores podem começar a construir patrimônio, entender como funcionam os juros e tomar decisões mais conscientes sobre seu dinheiro.</p>', '2025-10-01', 1),
(14, 'informativaConsumo', 'introducao', 'Armadilhas do <span class=\"accent\"> consumo:</span>', '<p><strong> O consumo exagerado é</strong> incentivado o tempo todo por propagandas, promoções e até redes sociais. Muitas vezes compramos coisas por impulso, sem real necessidade, apenas pelo desejo de acompanhar tendências. Essas armadilhas podem comprometer o orçamento e impedir que objetivos maiores sejam alcançados. Para os jovens, aprender a refletir antes de comprar é um passo essencial. É importante entender que ter mais coisas não significa ter mais felicidade. O consumo consciente é o primeiro passo para a liberdade financeira.</p>', '2025-10-01', 1),
(15, 'informativaConsumo', 'sustentabilidade', 'Sustentabilidade financeira', '<p class=\"lead\">Sustentabilidade financeira é a capacidade de manter uma vida equilibrada sem comprometer o futuro. Isso envolve gastar de forma responsável, poupar, investir e evitar dívidas. Assim como o meio ambiente, as finanças também precisam ser cuidadas para não se esgotarem. Para os jovens, isso significa aprender a pensar não apenas no presente, mas também no amanhã. Ter sustentabilidade financeira garante segurança, tranquilidade e maior independência nas escolhas de vida. É como construir uma base sólida para qualquer projeto futuro.</p>', '2025-10-01', 1),
(16, 'informativaConsumo', 'independencia', 'Independência financeira (FIRE)', '<p class=\"lead\">O conceito de independência financeira significa ter recursos suficientes para viver sem depender de trabalho constante. O movimento FIRE (Financial Independence, Retire Early) defende que, com disciplina, é possível juntar dinheiro suficiente para se aposentar cedo e viver de rendimentos. Embora pareça distante para os jovens, o segredo está em começar cedo, poupar de forma consistente e investir com inteligência. Assim, o tempo trabalha a favor do crescimento do patrimônio. A independência financeira não significa deixar de trabalhar, mas sim ter a liberdade de escolher o que fazer.</p>', '2025-10-01', 1),
(17, 'informativaCreditos', 'introducao', 'O que é <span class=\"accent\"> crédito:</span>', '<p><strong>Crédito é</strong> a possibilidade de usar o dinheiro de outra pessoa ou instituição para pagar depois, como acontece em empréstimos ou no cartão de crédito. Ele pode ser útil em situações emergenciais ou para grandes compras, mas precisa ser usado com responsabilidade. Quando mal utilizado, o crédito se transforma em dívida e pode gerar altos juros. Para os jovens, é importante entender que crédito não é um dinheiro extra, mas sim um compromisso futuro. Usar crédito com consciência é fundamental para não comprometer o orçamento.</p>', '2025-10-01', 1),
(18, 'informativaCreditos', 'tipos_dividas', 'Tipos de dívidas', '<p class=\"lead\">As dívidas podem ser classificadas em boas e ruins. As boas são aquelas que geram algum benefício ou retorno, como um financiamento estudantil que aumenta as chances de conseguir um bom emprego. Já as ruins são aquelas feitas por impulso, como compras no cartão sem planejamento. Entender essa diferença ajuda a avaliar quando vale a pena assumir um compromisso financeiro. Para os jovens, reconhecer os sinais de endividamento precoce é essencial para não cair em armadilhas. Dívida descontrolada pode virar uma bola de neve difícil de parar.</p>', '2025-10-01', 1),
(19, 'informativaCreditos', 'como_evitar', 'Como evitar e como quitar', '<p class=\"lead\">Para evitar dívidas, é essencial planejar os gastos, gastar menos do que se ganha e usar o crédito apenas quando realmente necessário. Caso já exista dívida, o caminho é negociar com os credores, buscar condições de pagamento melhores e evitar fazer novas compras até se livrar do problema. A prioridade deve ser sempre quitar primeiro as dívidas com juros mais altos, como cartão de crédito e cheque especial. Para os jovens, isso ensina disciplina e responsabilidade, mostrando que o controle financeiro exige escolhas firmes. Quitar dívidas é recuperar liberdade.</p>', '2025-10-01', 1),
(20, 'informativaDespesa', 'introducao', 'Fixa X<span class=\"accent\"> Variável:</span>', '<div><p><strong>As despesas fixas são</strong> aquelas que acontecem todos os meses e costumam ter valores iguais ou bem parecidos. Exemplos são aluguel, mensalidades escolares, contas de luz, internet e transporte público. Elas são consideradas essenciais, pois não dá para deixar de pagar sem que isso traga consequências.</p> <p class=\"lead\"> Já as despesas variáveis são aquelas que mudam de valor de acordo com o consumo ou necessidade: alimentação fora de casa, passeios, compras de roupas, presentes e lazer. Entender essa diferença é fundamental para organizar o orçamento, pois mostra onde é possível cortar ou reduzir gastos quando o dinheiro aperta. Essa consciência ajuda os jovens a priorizarem o que é realmente importante e a criar hábitos financeiros mais saudáveis desde cedo. </p></div>', '2025-10-01', 1),
(21, 'informativaDespesa', 'gastos_superfluos', 'Como evitar gastos supérfluos', '<p class=\"lead\">Gastos supérfluos são aqueles que não são essenciais para viver, mas que acabam consumindo boa parte da renda quando feitos em excesso. Para controlá-los, o ideal é definir prioridades, planejar o que será comprado e evitar compras por impulso. Uma dica prática é sempre comparar preços e refletir antes de gastar, perguntando a si mesmo: “Eu realmente preciso disso agora ou posso esperar?” ou “Esse gasto vai me fazer falta no final do mês?”. Outra técnica eficiente é estabelecer um limite mensal para lazer e compras não essenciais, mantendo o equilíbrio entre aproveitar a vida e cuidar das finanças. Com o tempo, esse controle se torna um hábito e ajuda a guardar dinheiro para objetivos maiores, como viagens ou cursos.</p>', '2025-10-01', 1),
(22, 'informativaDespesa', 'tecnicas_controle', 'Técnicas de controle', '<div><p class=\"lead\">Controlar as despesas fica muito mais fácil com a ajuda de ferramentas simples. As planilhas financeiras permitem registrar manualmente todos os ganhos e gastos, facilitando a análise do orçamento ao final do mês. Já os aplicativos de finanças são ainda mais práticos para os jovens, pois funcionam direto no celular e muitos se conectam à conta bancária, classificando os gastos automaticamente.</p> <p class=\"lead\">Dessa forma, é possível ver em gráficos para onde está indo o dinheiro e identificar excessos. Essa visualização clara motiva a criar metas, como economizar uma porcentagem do salário ou mesada, e acompanhar o progresso ao longo do tempo. Para quem busca uma solução moderna e interativa, o <strong>Finantec</strong> é uma ótima aliada: a plataforma ajuda a aprender sobre planejamento financeiro e investimentos e ainda permite colocar tudo em prática de forma simples e acessível.</p></div>', '2025-10-01', 1),
(23, 'informativaEd', 'introducao', 'O que é e<span class=\"accent\"> por que é importante:</span>', '<p><strong>Educação financeira é</strong> a capacidade de compreender e organizar o uso do dinheiro de forma inteligente, buscando equilíbrio entre gastos, poupança e investimentos. Ter esse conhecimento é essencial porque ajuda a evitar dívidas, a lidar melhor com imprevistos e a conquistar sonhos com mais segurança. Para os jovens, aprender desde cedo traz autonomia e responsabilidade, preparando-os para decisões maiores no futuro, como independência financeira e conquistas pessoais. Quanto antes começarmos, mais fácil se torna criar bons hábitos que durarão por toda a vida. É como plantar uma semente: com tempo e cuidado, ela cresce e dá frutos.</p>', '2025-10-01', 1),
(24, 'informativaEd', 'desejo_necessidade', 'Diferença entre desejo e necessidade', '<p class=\"lead\">Um dos primeiros passos para controlar as finanças é diferenciar o que realmente precisamos do que apenas queremos. Necessidades são básicas, como alimentação, moradia, transporte, saúde e educação. Já desejos são vontades que não são essenciais, como roupas caras, fast food frequente ou o celular mais novo do mercado. Não é errado satisfazer desejos, mas o problema surge quando eles atrapalham as necessidades ou geram dívidas. Ter clareza sobre essa diferença ajuda a fazer escolhas conscientes e evitar compras por impulso. Uma boa dica é sempre se perguntar: “eu preciso disso ou só quero agora?”.</p>', '2025-10-01', 1),
(25, 'informativaEd', 'prazos', 'Conceitos de curto, médio e longo prazo', '<p class=\"lead\">Para organizar melhor as metas financeiras, é importante entender que elas podem ter diferentes prazos de realização. Metas de curto prazo são rápidas, como juntar para um ingresso ou uma peça de roupa. As de médio prazo duram de um a cinco anos, como comprar um computador, fazer um curso ou viajar. Já as de longo prazo são as mais ambiciosas, como comprar uma casa, abrir um negócio ou garantir aposentadoria. Essa divisão ajuda a manter o foco e a paciência, porque mostra que algumas conquistas exigem mais tempo e esforço. Assim, é possível planejar de forma realista e evitar frustrações.</p>', '2025-10-01', 1),
(26, 'informativaFI', 'introducao', 'O que são e<span class=\"accent\"> como funcionam:</span>', '<p><strong>Os Fundos Imobiliários (FIIs)</strong> são uma forma de investir no mercado imobiliário sem precisar comprar um imóvel. Ao aplicar em um FII, o investidor adquire cotas que representam uma fração de empreendimentos, como shoppings, prédios comerciais, galpões logísticos ou até títulos ligados ao setor. Os rendimentos vêm dos aluguéis recebidos ou da valorização dos imóveis, e são distribuídos mensalmente aos cotistas. Esse formato torna o investimento acessível, já que é possível começar comprando cotas por valores relativamente baixos na Bolsa de Valores. Os FIIs permitem que jovens invistam em imóveis de forma simples e prática, sem precisar de grandes quantias de dinheiro. </p>', '2025-10-01', 1),
(27, 'informativaFI', 'vantagens', 'Vantagens dos FIIs', '<p class=\"lead\">A maior vantagem dos FIIs é a geração de renda passiva: muitos fundos pagam dividendos mensais, geralmente isentos de Imposto de Renda, o que os torna atrativos para quem busca complementar a renda. Outra vantagem é a diversificação: com pouco dinheiro, é possível investir em diferentes tipos de imóveis e setores. Além disso, os FIIs oferecem liquidez, já que as cotas podem ser compradas e vendidas na Bolsa a qualquer momento. Para os jovens, isso é uma grande oportunidade de entender como funciona o mercado imobiliário sem precisar investir milhões em um imóvel físico. É uma porta de entrada acessível para criar patrimônio e receber rendimentos regulares.</p>', '2025-10-01', 1),
(28, 'informativaFI', 'desvantagens', 'Desvantagens dos FIIs', '<p class=\"lead\">Por estarem ligados ao mercado imobiliário e à Bolsa de Valores, os FIIs sofrem variações constantes no preço das cotas, o que pode gerar ganhos ou perdas de curto prazo. Além disso, os rendimentos mensais dependem da saúde do setor imobiliário: se houver vacância (imóveis vazios) ou queda nos aluguéis, o retorno pode diminuir. Outro ponto é que os dividendos não são garantidos, já que variam conforme o desempenho do fundo. Para jovens investidores, isso ensina que é preciso ter paciência e visão de longo prazo, entendendo que a renda pode oscilar. Apesar desses riscos, os FIIs continuam sendo uma opção muito procurada para quem deseja investir em imóveis de forma moderna e com baixo custo.</p>', '2025-10-01', 1),
(29, 'informativaInvestir', 'introducao', 'O que é <span class=\"accent\">investir:</span>', '<p><strong>Investir é </strong>aplicar o dinheiro em algo que pode gerar retorno no futuro. Diferente de apenas guardar, investir significa fazer o dinheiro “trabalhar” para você, trazendo rendimentos que aumentam seu patrimônio. Os investimentos podem ser mais conservadores, com menos risco, ou mais arrojados, buscando maior retorno. Para jovens, aprender a investir cedo é uma grande vantagem, pois o tempo é um aliado na multiplicação dos rendimentos.</p>', '2025-10-01', 1),
(30, 'informativaInvestir', 'perfil', 'Perfil do Investidor', '<div><p class=\"lead\">Cada pessoa tem um perfil de investidor, que pode ser conservador, moderado ou arrojado. <br> • O conservador prefere segurança, mesmo que o retorno seja menor. <br> • O moderado aceita um pouco de risco em troca de ganhos maiores. <br>• Já o arrojado busca altas rentabilidades, aceitando grandes riscos. </p> <p class=\"lead\">Conhecer o próprio perfil ajuda a escolher os investimentos mais adequados e evitar frustrações. Para jovens, é comum começar de forma conservadora e, com o tempo, ganhar confiança para arriscar mais. É como aprender a nadar: começa-se no raso antes de ir para o fundo.</p></div>', '2025-10-01', 1),
(31, 'informativaInvestir', 'trio', 'Risco, retorno e liquidez', '<p class=\"lead\">Todo investimento tem três características principais: risco, retorno e liquidez. O risco é a chance de perder dinheiro, o retorno é o quanto se pode ganhar e a liquidez é a facilidade de resgatar o valor aplicado. Normalmente, quanto maior o retorno, maior o risco e menor a liquidez. Entender esse equilíbrio é essencial para fazer boas escolhas. Para jovens, significa aprender a avaliar se vale a pena esperar mais tempo ou assumir mais riscos em busca de melhores resultados. É como decidir entre uma corrida rápida ou uma maratona.</p>', '2025-10-01', 1),
(32, 'informativaLCI_LCA', 'introducao', 'O que são e <span class=\"accent\">como funcionam:</span>', '<div><p><strong>As LCI e LCA são</strong> investimentos de renda fixa emitidos pelos bancos para financiar os setores imobiliário e do agronegócio. Quando alguém aplica nesses títulos, está emprestando dinheiro ao banco, que vai direcionar os recursos para financiar imóveis ou atividades agrícolas.</p><p class=\"lead\"> Em troca, o investidor recebe uma remuneração no vencimento, que pode ser prefixada, pós-fixada ou híbrida. Um dos maiores atrativos é que tanto a LCI quanto a LCA são isentas de Imposto de Renda para pessoas físicas, o que aumenta a rentabilidade líquida. Elas também contam com a garantia do FGC, o que traz segurança adicional. Porém, a maioria desses títulos tem prazos fixos, e o dinheiro só pode ser resgatado ao final.</p></div>', '2025-10-01', 1),
(33, 'informativaLCI_LCA', 'vantagens', 'Vantagens das LCI e LCA', '<p class=\"lead\">A isenção de Imposto de Renda é a principal vantagem, tornando as LCI e LCA mais rentáveis do que outros investimentos semelhantes, como o CDB. Além disso, são consideradas muito seguras, já que contam com a proteção do FGC até R$ 250 mil por CPF e instituição. Outro ponto positivo é que, por financiarem setores importantes da economia, têm relevância social e econômica, permitindo que o investidor contribua com o desenvolvimento do país. Para jovens que buscam diversificar a carteira com segurança, essa é uma ótima alternativa. O baixo risco, aliado à previsibilidade dos ganhos, faz das LCI/LCA uma escolha interessante para quem já tem uma reserva de emergência formada.</p>', '2025-10-01', 1),
(34, 'informativaLCI_LCA', 'desvantagens', 'Desvantagens das LCI e LCA', '<p class=\"lead\">O grande ponto negativo das LCI e LCA é a falta de liquidez: geralmente não é possível resgatar o dinheiro antes do vencimento, o que exige disciplina e planejamento. Outra limitação é que o investimento inicial mínimo costuma ser mais alto, variando conforme o banco, o que pode dificultar o acesso de quem está começando. Além disso, como são muito procuradas, os bancos maiores oferecem taxas de retorno mais baixas, tornando necessário pesquisar instituições menores para conseguir rendimentos melhores. Para os jovens, isso significa que nem sempre será o investimento mais prático, mas pode ser excelente para metas de médio prazo, como guardar para comprar um bem ou realizar um projeto específico.</p>', '2025-10-01', 1),
(35, 'informativaPoupança', 'introducao', 'Como funciona a <span class=\"accent\">poupança:</span>', '<div><p><strong>A poupança funciona como</strong> uma conta especial em que o banco guarda o seu dinheiro e paga um rendimento mensal. Esse rendimento é calculado de acordo com a taxa básica de juros do país (Selic) e algumas regras definidas pelo governo. Isso significa que, mesmo sem fazer nada, o dinheiro depositado na poupança cresce um pouco a cada mês.</p><p class=\"lead\"> É considerada um investimento de baixíssimo risco, já que os bancos são regulamentados e ainda existe a garantia do Fundo Garantidor de Créditos (FGC), que assegura até R$ 250 mil por pessoa em caso de falência do banco. Para os jovens, a poupança é prática, simples e acessível, pois basta abrir uma conta e começar a depositar. No entanto, é importante destacar que os ganhos são limitados e muitas vezes menores que a inflação, o que significa que o dinheiro pode perder valor de compra ao longo do tempo.</p></div>', '2025-10-01', 1),
(36, 'informativaPoupança', 'vantagens', 'Vantagens da poupança', '<p class=\"lead\">A principal vantagem da poupança é a sua simplicidade: não é preciso ter conhecimento avançado de finanças para utilizá-la. Outra vantagem é a liquidez, já que o dinheiro pode ser retirado a qualquer momento, sem burocracia, o que a torna ideal para emergências. Além disso, ela é isenta de imposto de renda, o que a torna ainda mais fácil de usar. Muitos pais incentivam os filhos a guardarem na poupança justamente por essa praticidade e segurança. Para quem está começando a vida financeira, essa é uma forma de aprender o valor do hábito de poupar, sem se preocupar com prazos ou taxas complicadas. Contudo, é importante lembrar que a poupança deve ser apenas o início da jornada, e não o destino final, já que existem opções mais rentáveis disponíveis no mercado.</p>', '2025-10-01', 1),
(37, 'informativaPoupança', 'desvantagens', 'Desvantagens da poupança', '<p class=\"lead\">Apesar de segura, a poupança tem uma grande desvantagem: seu rendimento é muito baixo, geralmente menor do que a inflação. Isso significa que, com o tempo, o dinheiro guardado pode até perder poder de compra. Por exemplo, se a inflação aumentar os preços em 10% ao ano e a poupança render apenas 6%, o investidor acaba ficando “mais pobre” em termos reais. Além disso, por ser tão fácil e acessível, muitas pessoas acabam acomodadas, acreditando que estão investindo de verdade, quando na realidade estão apenas deixando o dinheiro parado. Para os jovens, isso pode dar uma falsa sensação de segurança, fazendo com que deixem de aproveitar outras oportunidades mais vantajosas. Por isso, a poupança deve ser usada como um degrau inicial, mas nunca como única estratégia de crescimento financeiro.</p>', '2025-10-01', 1),
(38, 'informativaPP', 'introducao', 'Como definir <span class=\"accent\">metas financeiras:</span>', '<p><strong>Ter metas claras é</strong>o primeiro passo para organizar a vida financeira. Uma meta é mais do que apenas um desejo, ela precisa ser específica, ter prazo e valor definido, como “juntar R$ 500 em 3 meses para comprar um celular”. Essa clareza ajuda a manter a motivação e acompanhar o progresso. Para os jovens, definir metas também é uma forma de aprender disciplina e priorização. O importante é começar pequeno e ir aumentando aos poucos, evitando frustrações. Quanto mais bem planejada for a meta, maior a chance de alcançá-la.</p>', '2025-10-01', 1),
(39, 'informativaPP', 'orcamento', 'Orçamento pessoal', '<p class=\"lead\">O orçamento é como um mapa que mostra para onde vai o nosso dinheiro. Ele consiste em anotar todas as receitas (dinheiro que entra) e todas as despesas (dinheiro que sai). Isso ajuda a entender se estamos gastando mais do que recebemos e em quais áreas é possível economizar. Um bom orçamento pode ser feito em caderno, planilha ou até em aplicativos de celular, o importante é manter a disciplina. Para jovens, é essencial porque ajuda a organizar a mesada, a renda de trabalhos temporários ou estágios, e até mesmo presentes em dinheiro. Saber para onde o dinheiro vai evita surpresas no fim do mês.</p>', '2025-10-01', 1),
(40, 'informativaPP', 'reserva', 'Reserva de emergência', '<p class=\"lead\">A reserva de emergência é um dinheiro guardado para imprevistos, como problemas de saúde, perda de emprego ou consertos inesperados. O ideal é juntar pelo menos o equivalente a três a seis meses de despesas básicas. Mesmo que pareça difícil, começar guardando pouco já faz diferença. Essa reserva deve ficar em investimentos seguros e fáceis de resgatar, como poupança ou Tesouro Selic. Para os jovens, criar essa reserva traz segurança e tranquilidade, pois mostra que é possível enfrentar imprevistos sem precisar pedir ajuda ou se endividar. É como um colete salva-vidas financeiro.</p>', '2025-10-01', 1),
(41, 'informativaRenda', 'introducao', 'O que é <span class=\"accent\">renda:</span>', '<p><strong>Renda é</strong> todo o dinheiro que uma pessoa recebe em determinado período, podendo vir de diferentes fontes, como salário, mesada, trabalhos extras ou até investimentos. Entender de onde vem a renda é o primeiro passo para organizá-la melhor. A renda é o que permite pagar contas, realizar compras, poupar e investir. Para os jovens, pode ser desde a mesada recebida em casa até pequenos ganhos por serviços prestados ou trabalhos temporários. Quanto mais diversificada for a renda, mais segurança financeira a pessoa terá.</p>', '2025-10-01', 1),
(42, 'informativaRenda', 'ativa_passiva', 'Renda ativa x renda passiva', '<p class=\"lead\">A renda ativa é aquela que vem diretamente do esforço, como o salário ou a venda de um produto. Já a renda passiva é o dinheiro que entra sem necessidade de trabalho constante, como juros de investimentos ou aluguel de imóveis. A maioria das pessoas começa com renda ativa, mas o objetivo deve ser, com o tempo, construir também fontes de renda passiva. Para os jovens, entender essa diferença desde cedo ajuda a enxergar a importância de investir e planejar o futuro. É como plantar hoje para colher sempre.</p>', '2025-10-01', 1),
(43, 'informativaRenda', 'bruta_liquida', 'Receita bruta x líquida', '<p class=\"lead\">Receita bruta é o valor total que a pessoa recebe, sem nenhum desconto. Já a receita líquida é o que realmente sobra, após impostos, taxas e descontos. Muitas vezes, as pessoas se confundem e pensam que podem gastar tudo o que ganham, esquecendo que sempre há deduções. Para os jovens, é importante aprender isso desde cedo, para não criar expectativas erradas. Saber a diferença entre bruto e líquido ajuda a planejar melhor o orçamento e evitar frustrações. É como entender a diferença entre o preço cheio de um produto e o que realmente cabe no bolso.</p>', '2025-10-01', 1),
(44, 'informativaTesouro', 'introducao', 'O que é e <span class=\"accent\">como funciona:</span>', '<div><p><strong>O Tesouro Direto é</strong> um programa criado pelo governo federal para facilitar o acesso da população aos títulos públicos. Quando você investe nele, está emprestando dinheiro para o governo, que promete devolver no futuro com juros. Esse dinheiro é usado para financiar áreas como saúde, educação e infraestrutura. O mais interessante é que o Tesouro Direto permite começar com valores baixos, a partir de cerca de R$ 30, tornando-o acessível para qualquer pessoa.</p> <p>Existem três tipos principais de títulos: prefixados, que têm um rendimento fixo; atrelados à Selic, ideais para reserva de emergência; e atrelados à inflação, que garantem ganho acima da alta dos preços. Essa diversidade dá ao investidor a chance de escolher a melhor opção de acordo com seus objetivos. Para os jovens, é um investimento seguro, educativo e um dos melhores para dar os primeiros passos fora da poupança.</p></div>', '2025-10-01', 1),
(45, 'informativaTesouro', 'vantagens', 'Vantagens do Tesouro Direto', '<div><p class=\"lead\">Uma das principais vantagens do Tesouro Direto é a segurança, já que ele é garantido pelo próprio governo federal, considerado o investimento mais seguro do país. Outra vantagem é a variedade de prazos e rendimentos, permitindo planejar objetivos de curto, médio e longo prazo. Além disso, a liquidez diária nos títulos atrelados à Selic facilita o uso como reserva de emergência, já que é possível resgatar o dinheiro a qualquer momento em dias úteis. </p><p class=\"lead\">Outro ponto positivo é a acessibilidade: não é necessário muito dinheiro para começar, e todo o processo é digital, podendo ser feito em corretoras ou bancos. Para jovens, isso é um grande atrativo, pois une segurança, baixo valor inicial e facilidade de acesso pela internet. É uma forma prática de aprender a investir de verdade, com ganhos que superam os da poupança.</p></div>', '2025-10-01', 1),
(46, 'informativaTesouro', 'desvantagens', 'Desvantagens do Tesouro Direto', '<div><p class=\"lead\">Apesar de seguro, o Tesouro Direto tem algumas limitações que precisam ser consideradas. A primeira é que ele não é isento de impostos: há cobrança de Imposto de Renda sobre os lucros, variando conforme o tempo de aplicação, além de taxas da corretora (embora muitas já ofereçam taxa zero). Outra desvantagem é que, dependendo do título escolhido, pode haver perda de rendimento caso o investidor precise resgatar o dinheiro antes do prazo.</p> <p class=\"lead\"> Ou seja, é importante respeitar o tempo de cada título para não sair no prejuízo. Além disso, os rendimentos, embora melhores que os da poupança, não são tão altos quando comparados a investimentos de maior risco, como ações ou fundos imobiliários. Para os jovens, a principal lição é entender que o Tesouro é excelente para segurança e organização, mas não é suficiente sozinho para quem busca multiplicar o patrimônio de forma acelerada.</p></div>', '2025-10-01', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `despesa`
--

CREATE TABLE `despesa` (
  `id_despesa` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_despesa` date NOT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `despesa`
--

INSERT INTO `despesa` (`id_despesa`, `id_usuario`, `valor`, `descricao`, `data_despesa`, `id_categoria`) VALUES
(2, 4, 2500.00, 'Despesa mensal', '2025-08-12', NULL),
(3, 1, 900.00, 'Despesa mensal', '2025-08-12', NULL),
(4, 2, 4700.00, 'Despesa mensal', '2025-08-13', NULL),
(5, 6, 4500.00, 'Despesa mensal', '2025-08-13', NULL),
(6, 7, 3500.00, 'Despesa mensal', '2025-09-01', NULL),
(7, 10, 10000.00, 'Despesa mensal', '2025-09-03', NULL),
(17, 3, 15000.00, 'Depósito na meta ID 12', '2025-09-26', 7),
(22, 2, 4700.00, 'Despesa Principal (Atualizada)', '2025-09-28', NULL),
(23, 2, 500.00, 'Depósito na meta ID 11', '2025-09-28', 7),
(24, 3, 2000.00, 'Depósito na meta ID 18', '2025-09-29', 7),
(25, 3, 1400.00, 'Depósito na meta ID 18', '2025-09-30', 7),
(26, 3, 30.00, 'Despesa', '2025-09-30', 7),
(27, 11, 800.00, 'Despesa Principal', '2025-09-30', NULL),
(30, 11, 50.00, 'BALAS', '2025-09-30', 4),
(31, 12, 100.00, 'Despesa Principal', '2025-10-02', NULL),
(32, 3, 5000.00, 'Saúde', '2025-10-07', NULL),
(33, 3, 4500.00, 'Despesa Principal (Atualizada)', '2025-10-07', NULL),
(35, 13, 4500.00, 'Despesa Principal', '2025-10-08', NULL),
(38, 3, 4500.00, 'Despesa Principal (Atualizada)', '2025-10-09', NULL),
(41, 14, 200.00, 'Livros', '2025-10-10', 2),
(42, 17, 450.00, 'Depósito na meta ID 26', '2025-10-10', 7),
(43, 18, 2.00, 'Depósito na meta ID 28', '2025-10-10', 7),
(44, 6, 100.00, '', '2010-02-10', 2),
(47, 2, 300.00, 'Depósito na meta ID 8', '2025-10-26', 7),
(48, 2, 500.00, 'Depósito na meta ID 11', '2025-10-26', 7),
(49, 2, 120.00, 'Depósito na meta ID 8', '2025-10-26', 7),
(50, 2, 80.00, 'Depósito na meta ID 8', '2025-10-26', 7),
(51, 2, 200.00, 'Depósito na meta ID 9', '2025-10-26', 7),
(52, 2, 40.00, 'Depósito na meta ID 10', '2025-10-26', 7),
(53, 2, 40.00, 'Depósito na meta ID 8', '2025-10-26', 7),
(54, 2, 500.00, 'Depósito na meta ID 8', '2025-10-26', 7),
(55, 2, 500.00, 'Depósito na meta ID 4', '2025-10-26', 7),
(56, 19, 50.00, 'Presente mamae', '2025-10-26', 7),
(57, 19, 50.00, 'Depósito na meta ID 30', '2025-10-26', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `forca_palavras`
--

CREATE TABLE `forca_palavras` (
  `id_palavra` int(11) NOT NULL,
  `palavra` varchar(50) NOT NULL,
  `dica` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `forca_palavras`
--

INSERT INTO `forca_palavras` (`id_palavra`, `palavra`, `dica`) VALUES
(1, 'BANCO', 'Onde você guarda dinheiro e faz transações.'),
(2, 'POUPANCA', 'Uma forma comum de guardar dinheiro com baixo risco.'),
(3, 'MOEDAS', 'Dinheiro em formato metálico.'),
(4, 'DINHEIRO', 'O principal meio de troca na economia.'),
(5, 'RENDA', 'Todo valor que você recebe, como salário.'),
(6, 'INVESTIR', 'Aplicar dinheiro para obter retorno futuro.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `meta`
--

CREATE TABLE `meta` (
  `id_meta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor_objetivo` decimal(10,2) NOT NULL,
  `prazo` date DEFAULT NULL,
  `cor` varchar(7) DEFAULT '#4A90E2',
  `prazo_meses` int(11) DEFAULT NULL,
  `frequencia` varchar(20) DEFAULT 'mensal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `meta`
--

INSERT INTO `meta` (`id_meta`, `id_usuario`, `descricao`, `valor_objetivo`, `prazo`, `cor`, `prazo_meses`, `frequencia`) VALUES
(2, 4, 'Objetivo de economia', 5000.00, '2026-08-12', '#4A90E2', NULL, 'mensal'),
(3, 1, 'Objetivo de economia', 3000.00, '2026-08-12', '#4A90E2', NULL, 'mensal'),
(5, 6, 'Objetivo de economia', 10000.00, '2026-08-13', '#4A90E2', NULL, 'mensal'),
(6, 7, 'Objetivo de economia', 40000.00, '2026-09-01', '#4A90E2', NULL, 'mensal'),
(7, 10, 'Objetivo de economia', 150000.00, '2026-09-03', '#4A90E2', NULL, 'mensal'),
(9, 2, 'Meta definida via painel', 123456.00, '0000-00-00', '#4A90E2', NULL, 'mensal'),
(10, 2, 'Meta reestabelecida', 4000.00, '2025-10-09', '#4A90E2', NULL, 'mensal'),
(15, 3, 'Meta definida via painel', 5000.00, '2025-12-03', '#4A90E2', NULL, 'mensal'),
(16, 3, 'Meta de Economia', 15000.00, '2026-09-26', '#4A90E2', NULL, 'mensal'),
(19, 3, 'Comprar um celular', 3400.00, NULL, '#411466', NULL, 'mensal'),
(21, 11, 'Comprar um celular', 3500.00, NULL, '#df1111', NULL, 'mensal'),
(22, 12, 'Meta de Economia', 1000.00, '2026-10-02', '#4A90E2', NULL, 'mensal'),
(23, 13, 'Meta de Economia', 15000.00, '2026-10-08', '#4A90E2', NULL, 'mensal'),
(24, 14, 'Meta de Economia', 15000.00, '2026-10-09', '#4A90E2', NULL, 'mensal'),
(25, 16, 'Meta de Economia', 10000.00, '2026-10-10', '#4A90E2', NULL, 'mensal'),
(26, 17, 'Meta de Economia', 8000.00, '2026-10-10', '#4A90E2', NULL, 'mensal'),
(27, 17, 'celular', 1.00, NULL, '#141e66', NULL, 'diario'),
(29, 18, 'abrir um estabelecimento', 100000.00, NULL, '#146627', NULL, 'semanal'),
(30, 19, 'Meta de Economia', 3000.00, '2026-10-26', '#4A90E2', NULL, 'mensal');

-- --------------------------------------------------------

--
-- Estrutura para tabela `progresso`
--

CREATE TABLE `progresso` (
  `id_progresso` int(11) NOT NULL,
  `id_meta` int(11) NOT NULL,
  `valor_atual` decimal(10,2) NOT NULL,
  `percentual_alcancado` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `progresso`
--

INSERT INTO `progresso` (`id_progresso`, `id_meta`, `valor_atual`, `percentual_alcancado`) VALUES
(1, 16, 250.00, 0.00),
(4, 19, 2500.00, NULL),
(6, 21, 100.00, NULL),
(7, 22, 0.00, 0.00),
(8, 23, 0.00, 0.00),
(9, 24, 0.00, 0.00),
(10, 25, 0.00, NULL),
(11, 26, 450.00, NULL),
(12, 27, 0.00, NULL),
(14, 29, 0.00, NULL),
(17, 30, 50.00, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `quiz_opcoes`
--

CREATE TABLE `quiz_opcoes` (
  `id_opcao` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `texto_opcao` varchar(255) NOT NULL,
  `correta` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `quiz_opcoes`
--

INSERT INTO `quiz_opcoes` (`id_opcao`, `id_pergunta`, `texto_opcao`, `correta`) VALUES
(1, 1, 'Gastar tudo', 0),
(2, 1, 'Guardar parte do dinheiro', 1),
(3, 1, 'Pedir empréstimo', 0),
(4, 1, 'Comprar parcelado', 0),
(5, 2, 'Comprar por impulso', 0),
(6, 2, 'Pagar juros altos', 0),
(7, 2, 'Anotar gastos', 1),
(8, 2, 'Evitar guardar dinheiro', 0),
(9, 3, 'Não anotar nada', 0),
(10, 3, 'Usar cartão sem limite', 0),
(11, 3, 'Pedir empréstimo', 0),
(12, 3, 'Fazer orçamento', 1),
(13, 4, 'Emergências', 1),
(14, 4, 'Comprar sempre', 0),
(15, 4, 'Ficar sem nada', 0),
(16, 4, 'Gastar sem pensar', 0),
(17, 5, 'Acumular juros', 0),
(18, 5, 'Pagar contas em dia', 1),
(19, 5, 'Ignorar boletos', 0),
(20, 5, 'Pedir crédito sem necessidade', 0),
(21, 6, 'Dinheiro extra no banco', 0),
(22, 6, 'Investimento arriscado', 0),
(23, 6, 'Plano de como gastar e economizar', 1),
(24, 6, 'Empréstimo', 0),
(25, 7, 'Paga mais caro', 0),
(26, 7, 'Acumula dívidas', 0),
(27, 7, 'Nada muda', 0),
(28, 7, 'Ganha desconto', 1),
(29, 8, 'Anotar gastos', 1),
(30, 8, 'Ignorar despesas', 0),
(31, 8, 'Gastar por impulso', 0),
(32, 8, 'Usar crédito sem controle', 0),
(33, 9, 'Gastar mais', 0),
(34, 9, 'Economizar', 1),
(35, 9, 'Aumentar dívidas', 0),
(36, 9, 'Nada', 0),
(37, 10, 'Gastar sem pensar', 0),
(38, 10, 'Pedir empréstimos', 0),
(39, 10, 'Organizar receitas e despesas', 1),
(40, 10, 'Comprar parcelado sempre', 0),
(41, 11, 'Desconto em compras', 0),
(42, 11, 'Taxa fixa', 0),
(43, 11, 'Juros sobre juros', 1),
(44, 11, 'Imposto', 0),
(45, 12, 'Ações de startup', 0),
(46, 12, 'Criptomoedas', 0),
(47, 12, 'Opções de compra', 0),
(48, 12, 'Tesouro Selic', 1),
(49, 13, 'Investir de forma planejada', 1),
(50, 13, 'Gastar tudo', 0),
(51, 13, 'Ignorar finanças', 0),
(52, 13, 'Tomar empréstimos', 0),
(53, 14, 'Risco alto', 0),
(54, 14, 'Baixo risco', 1),
(55, 14, 'Ganho imediato', 0),
(56, 14, 'Sem planejamento', 0),
(57, 15, 'Desconectados', 0),
(58, 15, 'Sempre iguais', 0),
(59, 15, 'Relacionados', 1),
(60, 15, 'Independentes', 0),
(61, 16, 'Investir tudo', 0),
(62, 16, 'Pedir empréstimo', 0),
(63, 16, 'Comprar bem caro', 0),
(64, 16, 'Conhecer receitas e despesas', 1),
(65, 17, 'Reserva para imprevistos', 1),
(66, 17, 'Empréstimo do banco', 0),
(67, 17, 'Aplicação de alto risco', 0),
(68, 17, 'Cartão de crédito', 0),
(69, 18, 'Gastar sem pensar', 0),
(70, 18, 'Controlar gastos', 1),
(71, 18, 'Ignorar boletos', 0),
(72, 18, 'Parcelar tudo', 0),
(73, 19, 'Comprar supérfluos', 0),
(74, 19, 'Ignorar dívidas', 0),
(75, 19, 'Pagar contas essenciais', 1),
(76, 19, 'Gastar por impulso', 0),
(77, 20, 'Aumentar gastos', 0),
(78, 20, 'Acumular juros', 0),
(79, 20, 'Comprar sempre parcelado', 0),
(80, 20, 'Economizar e evitar dívidas', 1),
(81, 21, 'Concentrar em um ativo', 0),
(82, 21, 'Reduzir risco', 1),
(83, 21, 'Ganhar rápido', 0),
(84, 21, 'Evitar ganhos', 0),
(85, 22, 'Salário mensal', 0),
(86, 22, 'Empréstimo recebido', 0),
(87, 22, 'Ganho sem trabalho ativo', 1),
(88, 22, 'Lucro com venda', 0),
(89, 23, 'Criptomoedas', 0),
(90, 23, 'Ações voláteis', 0),
(91, 23, 'Opções', 0),
(92, 23, 'Tesouro Direto', 1),
(93, 24, 'Multiplicar dinheiro', 1),
(94, 24, 'Gastar rápido', 0),
(95, 24, 'Ficar endividado', 0),
(96, 24, 'Evitar planejamento', 0),
(97, 25, 'Vagos', 0),
(98, 25, 'Claros e mensuráveis', 1),
(99, 25, 'Imediatos e arriscados', 0),
(100, 25, 'Ignorados', 0),
(101, 26, 'Redução de preços', 0),
(102, 26, 'Lucro do banco', 0),
(103, 26, 'Aumento geral de preços', 1),
(104, 26, 'Dívida pública', 0),
(105, 27, 'Investimentos arriscados', 0),
(106, 27, 'Ganhos rápidos', 0),
(107, 27, 'Ações voláteis', 0),
(108, 27, 'Poder de compra', 1),
(109, 28, 'Investir em ativos que rendem mais', 1),
(110, 28, 'Guardar embaixo do colchão', 0),
(111, 28, 'Gastar tudo', 0),
(112, 28, 'Parcelar dívidas', 0),
(113, 29, 'Dívidas', 0),
(114, 29, 'Bens que geram valor', 1),
(115, 29, 'Contas a pagar', 0),
(116, 29, 'Compras supérfluas', 0),
(117, 30, 'Tesouro Selic', 0),
(118, 30, 'Ações arriscadas', 0),
(119, 30, 'CDB e fundos', 1),
(120, 30, 'Cartão de crédito', 0),
(121, 31, 'Lucro garantido', 0),
(122, 31, 'Risco zero', 0),
(123, 31, 'Investimento eterno', 0),
(124, 31, 'Facilidade de transformar em dinheiro', 1),
(125, 32, 'Poupança e Tesouro Selic', 1),
(126, 32, 'Imóveis', 0),
(127, 32, 'Ações de longo prazo', 0),
(128, 32, 'Fundos imobiliários', 0),
(129, 33, 'Gasto por impulso', 0),
(130, 33, 'Planejamento financeiro', 1),
(131, 33, 'Endividamento', 0),
(132, 33, 'Ignorar juros', 0),
(133, 34, 'Dívidas acumuladas', 0),
(134, 34, 'Salário mensal', 0),
(135, 34, 'Conjunto de bens e direitos', 1),
(136, 34, 'Cartão de crédito', 0),
(137, 35, 'Gastar tudo jovem', 0),
(138, 35, 'Ignorar finanças', 0),
(139, 35, 'Pedir empréstimos', 0),
(140, 35, 'Investimento contínuo', 1),
(141, 36, 'Gastar sem pensar', 0),
(142, 36, 'Aprender a gerir dinheiro', 1),
(143, 36, 'Tomar empréstimos', 0),
(144, 36, 'Evitar investimentos', 0),
(145, 37, 'Gastar mais', 0),
(146, 37, 'Parcelar sempre', 0),
(147, 37, 'Evitar dívidas', 1),
(148, 37, 'Acumular juros', 0),
(149, 38, '1 semana de gastos', 0),
(150, 38, 'Tudo em poupança', 0),
(151, 38, 'Sem planejamento', 0),
(152, 38, '3 a 6 meses de despesas', 1),
(153, 39, 'Forma de aumentar renda', 1),
(154, 39, 'Desperdício', 0),
(155, 39, 'Risco alto', 0),
(156, 39, 'Gastar sem retorno', 0),
(157, 40, 'Desconto em compras', 0),
(158, 40, 'Valor da melhor alternativa sacrificada', 1),
(159, 40, 'Taxa de juros', 0),
(160, 40, 'Gasto extra', 0),
(161, 41, 'Risco operacional', 0),
(162, 41, 'Juros compostos', 0),
(163, 41, 'Retorno sobre investimento', 1),
(164, 41, 'Perda financeira', 0),
(165, 42, 'Ganhos altos', 0),
(166, 42, 'Renda fixa', 0),
(167, 42, 'Inflação baixa', 0),
(168, 42, 'Perdas financeiras', 1),
(169, 43, 'Ações e fundos', 1),
(170, 43, 'Poupança imediata', 0),
(171, 43, 'Gastos impulsivos', 0),
(172, 43, 'Criptomoedas arriscadas', 0),
(173, 44, 'Lucro garantido', 0),
(174, 44, 'Variação de preços', 1),
(175, 44, 'Risco zero', 0),
(176, 44, 'Inflação baixa', 0),
(177, 45, 'Ignorar planejamento', 0),
(178, 45, 'Gastar impulsivamente', 0),
(179, 45, 'Aprender sempre', 1),
(180, 45, 'Tomar crédito sem necessidade', 0),
(181, 46, 'Gastar sem pensar', 0),
(182, 46, 'Pedir empréstimos', 0),
(183, 46, 'Ignorar investimentos', 0),
(184, 46, 'Viver sem depender de salário', 1),
(185, 47, 'Investir regularmente', 1),
(186, 47, 'Gastar tudo', 0),
(187, 47, 'Parcelar dívidas', 0),
(188, 47, 'Ignorar juros', 0),
(189, 48, 'Criptomoedas voláteis', 0),
(190, 48, 'Ativos com impacto positivo', 1),
(191, 48, 'Gastos supérfluos', 0),
(192, 48, 'Dívidas altas', 0),
(193, 49, 'Dívidas totais', 0),
(194, 49, 'Salário anual', 0),
(195, 49, 'Ativos menos passivos', 1),
(196, 49, 'Renda extra', 0),
(197, 50, 'Irrealizável', 0),
(198, 50, 'Investimento eterno', 0),
(199, 50, 'Gasto impulsivo', 0),
(200, 50, 'Alcançável em menos de 1 ano', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `quiz_perguntas`
--

CREATE TABLE `quiz_perguntas` (
  `id_pergunta` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `pontos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `quiz_perguntas`
--

INSERT INTO `quiz_perguntas` (`id_pergunta`, `nivel`, `pergunta`, `pontos`) VALUES
(1, 1, 'O que é economizar?', 20),
(2, 1, 'Qual é um bom hábito financeiro?', 20),
(3, 1, 'Qual é uma forma de controlar gastos?', 20),
(4, 1, 'Guardar dinheiro é importante para:', 20),
(5, 1, 'Evitar dívidas significa:', 20),
(6, 2, 'O que é orçamento pessoal?', 25),
(7, 2, 'Qual é a vantagem de comprar à vista?', 25),
(8, 2, 'O que ajuda a manter o orçamento?', 25),
(9, 2, 'Evitar desperdícios ajuda a:', 25),
(10, 2, 'Planejamento financeiro significa:', 25),
(11, 3, 'O que são juros compostos?', 30),
(12, 3, 'Qual investimento é de menor risco?', 30),
(13, 3, 'Para multiplicar dinheiro com segurança, você deve:', 30),
(14, 3, 'Investir em renda fixa significa:', 30),
(15, 3, 'Risco e retorno estão:', 30),
(16, 4, 'Primeira etapa do planejamento financeiro?', 35),
(17, 4, 'Fundo de emergência é:', 35),
(18, 4, 'Para evitar dívidas é importante:', 35),
(19, 4, 'O que é prioridade financeira?', 35),
(20, 4, 'Planejar compras ajuda a:', 35),
(21, 5, 'Diversificação de investimentos significa:', 40),
(22, 5, 'Renda passiva é:', 40),
(23, 5, 'Investimento de risco baixo:', 40),
(24, 5, 'Investir regularmente ajuda a:', 40),
(25, 5, 'Objetivo financeiro deve ser:', 40),
(26, 6, 'O que é inflação?', 45),
(27, 6, 'Inflação alta prejudica:', 45),
(28, 6, 'Para proteger dinheiro da inflação:', 45),
(29, 6, 'O que são ativos?', 45),
(30, 6, 'Investimento de médio risco:', 45),
(31, 7, 'O que é liquidez?', 50),
(32, 7, 'Investimentos de alta liquidez:', 50),
(33, 7, 'Investir pensando no futuro é:', 50),
(34, 7, 'O que é patrimônio?', 50),
(35, 7, 'Aposentadoria planejada requer:', 50),
(36, 8, 'O que é educação financeira?', 55),
(37, 8, 'Controle de gastos ajuda a:', 55),
(38, 8, 'Reserva de emergência deve cobrir:', 55),
(39, 8, 'Investir em conhecimento é:', 55),
(40, 8, 'O que é custo de oportunidade?', 55),
(41, 9, 'O que significa ROI?', 60),
(42, 9, 'Diversificação protege contra:', 60),
(43, 9, 'Investimento de longo prazo:', 60),
(44, 9, 'O que é volatilidade?', 60),
(45, 9, 'Educação financeira contínua significa:', 60),
(46, 10, 'O que é independência financeira?', 70),
(47, 10, 'Planejar aposentadoria significa:', 70),
(48, 10, 'O que são investimentos sustentáveis?', 70),
(49, 10, 'O que é patrimônio líquido?', 70),
(50, 10, 'Objetivo de curto prazo deve ser:', 70);

-- --------------------------------------------------------

--
-- Estrutura para tabela `renda`
--

CREATE TABLE `renda` (
  `id_renda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_recebimento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `renda`
--

INSERT INTO `renda` (`id_renda`, `id_usuario`, `valor`, `descricao`, `data_recebimento`) VALUES
(2, 4, 3500.00, 'Renda mensal', '2025-08-12'),
(3, 1, 1300.00, 'Renda mensal', '2025-08-12'),
(4, 2, 5900.00, 'Renda mensal', '2025-08-13'),
(5, 6, 5000.00, 'Renda mensal', '2025-08-13'),
(6, 7, 12000.00, 'Renda mensal', '2025-09-01'),
(7, 10, 15000.00, 'Renda mensal', '2025-09-03'),
(12, 3, 5000.00, 'Renda Principal', '2025-09-26'),
(14, 3, 10000.00, 'Renda Principal (Atualizada)', '2025-09-26'),
(15, 3, 500.00, 'Retirada da meta ID 16', '2025-09-26'),
(16, 3, 15500.00, 'Renda Principal (Atualizada)', '2025-09-26'),
(17, 3, 31000.00, 'Renda Principal (Atualizada)', '2025-09-26'),
(18, 2, 5500.00, 'Renda Principal (Atualizada)', '2025-09-28'),
(20, 3, 3400.00, 'Retirada da meta ID 18', '2025-09-30'),
(21, 11, 1000.00, 'Renda Principal', '2025-09-30'),
(22, 11, 100.00, 'Retirada da meta ID 20', '2025-09-30'),
(23, 11, 100.00, 'Retirada da meta ID 20', '2025-09-30'),
(24, 12, 150.00, 'Renda Principal', '2025-10-02'),
(26, 13, 5000.00, 'Renda Principal', '2025-10-08'),
(27, 3, 3000.00, 'FreeLance', '0025-10-08'),
(28, 3, 120.00, 'sabao', '2025-10-08'),
(31, 3, 5000.00, 'Renda Principal (Atualizada)', '2025-10-09'),
(34, 18, 1500.00, 'saalrio', '2025-10-08'),
(35, 2, 40.00, 'Retirada da meta ID 8', '2025-10-26'),
(36, 2, 500.00, 'Valor retornado da meta excluída: Meta reestabelecida', '2025-10-26'),
(37, 2, 500.00, 'Valor retornado da meta excluída: Objetivo de economia', '2025-10-26'),
(38, 19, 40.00, 'Limpar Quarto', '2025-10-26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `tipo_usuario` enum('estudante','administrador') NOT NULL DEFAULT 'estudante',
  `avatar_url` varchar(255) DEFAULT NULL,
  `game_level` int(11) NOT NULL DEFAULT 1,
  `game_coins` int(11) NOT NULL DEFAULT 100,
  `renda_mensal` decimal(10,2) DEFAULT 0.00,
  `despesa_mensal` decimal(10,2) DEFAULT 0.00,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo_usuario`, `avatar_url`, `game_level`, `game_coins`, `renda_mensal`, `despesa_mensal`, `password_reset_token`, `password_reset_expires`) VALUES
(1, 'Daniel', 'daniel@com', '$2a$10$hWwYGzev8dsB.pCvM1YmyeRPVXaBmqpxawyYSrSwnGz5779HRNdca', 'administrador', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(2, 'Aline', 'aline@com', '$2a$10$TPcky3XZqKrRIgm51Pt1K.CepPGujNCoyym/GZIqJenG0rWFEESjm', 'estudante', NULL, 1, 100, 800.00, 300.00, NULL, NULL),
(3, 'Gabriela ', 'gabi@com', '$2a$10$Te6RgS0keizigFEJLIRdpeuDmCgzQ6SQqxxaHKqv.OVUOKUnA1mKq', 'estudante', 'uploads/avatar-1759862724137.png', 1, 105, 4500.00, 3500.00, NULL, NULL),
(4, 'Marcelo', 'marcelo@com', '$2a$10$TDD7wmzcwiolxbhwRa8u6ePP2oPbofZqGjyLuk7vl/DsMCxSp2DbC', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(5, 'Administrador', 'adm@com', '$2a$10$TPcky3XZqkRIgm51Pt1KCeePGgUyNCosO5yCDplKzhInTQeCVUd/S', 'administrador', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(6, 'Maria Luiza', 'malu@com', '$2a$10$Yix3V01bjCJ2t9nhC9QgBe5QYxWKqYZkixMPFjm/jaVAuDyBxqYvG', 'estudante', NULL, 1, 61, 0.00, 0.00, NULL, NULL),
(7, 'Gabriela ', 'gabi@teste', '$2a$10$oymYkbY28DAoHPaCoo6/iO397XKEUsYPCuSUgI2qgcbScZzz1tPCq', 'administrador', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(9, 'Marcelo Macrino', 'marcelo@teste', '$2a$10$6PwUj8NSiWxA9cP.XOffeeELxJiTWZKQmxuwKU1RwMAoa6iMjRfjC', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(10, 'Marcelo Marcelo', 'marcelo@gmail.com', '$2a$10$ojd.Fv1axb1JBkcAG3882.f3HzADDw4/Eizpe3ux7N0wgMnHxReDC', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(11, 'Paloma ', 'paloma@gmail.com', '$2a$10$T8GFwVaYt1cPRin/QvXsnuTfzUYlBGbAgeWkF6QiKVr7rcnFmWFEi', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(12, 'Jurandir', 'jura@gmail.com', '$2a$10$2P/e4FhVRTefulAecJMhquKvyyTKZ6/47b70GTzi1boam1V.FjZHW', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(13, 'Julia Morais', 'juMorais@gmail.com', '$2a$10$lfYVmxsSv3gioAXKCKHz8uU8jvEyYUeSaMfiaiJOp0QdTZ6Eb951i', 'estudante', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(14, 'Marli Neri', 'maNeri@gmail.com', '$2a$10$sp3kTTrnfOlTkgY4EPt8mOCGrlYRPhxDf2XdV8q5SySjxsJLFFf1i', 'administrador', NULL, 1, 100, 45000.00, 1000.00, NULL, NULL),
(15, 'Daniel Silva', 'daniel@gmail.com', '$2a$10$vRmxnkL.HUJ.kzlE9t./.uweovCdq2sMP/OC6JZ1CFjS2A7CV8v9u', 'administrador', NULL, 1, 100, 0.00, 0.00, NULL, NULL),
(16, 'Daniel Silva', 'daniel01@gmail.com', '$2a$10$juHgRLnIWDRQCcyVNJFtp.DM2DGTqM50Eaf2q52TW4x/WnUapmFzi', 'estudante', NULL, 1, 100, 4500.00, 3500.00, NULL, NULL),
(17, 'Vitor Pinheiro Biazotti', 'Vitorbiazotti89@gmail.com', '$2a$10$vvoBcGDKoUN5Nlm3LnQITuelvAgznEtD4Hn74qTbdqfgh7hw4108u', 'estudante', NULL, 1, 109, 3000.00, 2500.00, NULL, NULL),
(18, 'billy', '123@gmail.com', '$2a$10$Y0Ij42RhpuDbPDFk6iREF.02bWFwMo2bkiQksniST/VoDT/fhGi36', 'estudante', NULL, 1, 151, 1500.00, 750.00, NULL, NULL),
(19, 'Alan Soares', 'alan@gmail.com', '$2a$10$Sst5tcqytlJ2jiwx7EAgmOQ4NpHw6q2Dhv/ibM3jbFUyMBLDxeBDi', 'estudante', NULL, 3, 145, 800.00, 650.00, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `calculo`
--
ALTER TABLE `calculo`
  ADD PRIMARY KEY (`id_calculo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `categoria_despesa`
--
ALTER TABLE `categoria_despesa`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `conteudo`
--
ALTER TABLE `conteudo`
  ADD PRIMARY KEY (`id_conteudo`);

--
-- Índices de tabela `despesa`
--
ALTER TABLE `despesa`
  ADD PRIMARY KEY (`id_despesa`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_despesa_categoria` (`id_categoria`);

--
-- Índices de tabela `forca_palavras`
--
ALTER TABLE `forca_palavras`
  ADD PRIMARY KEY (`id_palavra`);

--
-- Índices de tabela `meta`
--
ALTER TABLE `meta`
  ADD PRIMARY KEY (`id_meta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `progresso`
--
ALTER TABLE `progresso`
  ADD PRIMARY KEY (`id_progresso`),
  ADD KEY `id_meta` (`id_meta`);

--
-- Índices de tabela `quiz_opcoes`
--
ALTER TABLE `quiz_opcoes`
  ADD PRIMARY KEY (`id_opcao`),
  ADD KEY `id_pergunta` (`id_pergunta`);

--
-- Índices de tabela `quiz_perguntas`
--
ALTER TABLE `quiz_perguntas`
  ADD PRIMARY KEY (`id_pergunta`);

--
-- Índices de tabela `renda`
--
ALTER TABLE `renda`
  ADD PRIMARY KEY (`id_renda`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `calculo`
--
ALTER TABLE `calculo`
  MODIFY `id_calculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `categoria_despesa`
--
ALTER TABLE `categoria_despesa`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `conteudo`
--
ALTER TABLE `conteudo`
  MODIFY `id_conteudo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `despesa`
--
ALTER TABLE `despesa`
  MODIFY `id_despesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `forca_palavras`
--
ALTER TABLE `forca_palavras`
  MODIFY `id_palavra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `meta`
--
ALTER TABLE `meta`
  MODIFY `id_meta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `progresso`
--
ALTER TABLE `progresso`
  MODIFY `id_progresso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `quiz_opcoes`
--
ALTER TABLE `quiz_opcoes`
  MODIFY `id_opcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT de tabela `quiz_perguntas`
--
ALTER TABLE `quiz_perguntas`
  MODIFY `id_pergunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `renda`
--
ALTER TABLE `renda`
  MODIFY `id_renda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `calculo`
--
ALTER TABLE `calculo`
  ADD CONSTRAINT `calculo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `categoria_despesa`
--
ALTER TABLE `categoria_despesa`
  ADD CONSTRAINT `categoria_despesa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `despesa`
--
ALTER TABLE `despesa`
  ADD CONSTRAINT `despesa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `fk_despesa_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_despesa` (`id_categoria`);

--
-- Restrições para tabelas `meta`
--
ALTER TABLE `meta`
  ADD CONSTRAINT `meta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `progresso`
--
ALTER TABLE `progresso`
  ADD CONSTRAINT `progresso_ibfk_1` FOREIGN KEY (`id_meta`) REFERENCES `meta` (`id_meta`);

--
-- Restrições para tabelas `quiz_opcoes`
--
ALTER TABLE `quiz_opcoes`
  ADD CONSTRAINT `quiz_opcoes_ibfk_1` FOREIGN KEY (`id_pergunta`) REFERENCES `quiz_perguntas` (`id_pergunta`) ON DELETE CASCADE;

--
-- Restrições para tabelas `renda`
--
ALTER TABLE `renda`
  ADD CONSTRAINT `renda_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
