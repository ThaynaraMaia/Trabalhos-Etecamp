-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/10/2024 às 22:24
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `equilibrio`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudos`
--

CREATE TABLE `conteudos` (
  `id_conteudos` int(11) NOT NULL,
  `home` varchar(200) NOT NULL,
  `exercicio` varchar(200) NOT NULL,
  `nome_alongamento` varchar(200) NOT NULL,
  `alongamento` varchar(700) NOT NULL,
  `nome_respiracao` varchar(200) NOT NULL,
  `respiracao` varchar(700) NOT NULL,
  `nome_danca` varchar(200) NOT NULL,
  `danca` varchar(700) NOT NULL,
  `nome_caminhada` varchar(200) NOT NULL,
  `caminhada` varchar(700) NOT NULL,
  `nome_hiit` varchar(200) NOT NULL,
  `hiit` varchar(700) NOT NULL,
  `sono` varchar(200) NOT NULL,
  `nome_sonobipolaridade` varchar(700) NOT NULL,
  `sonobipolaridade` varchar(200) NOT NULL,
  `nome_sonodicas` varchar(700) NOT NULL,
  `sonodicas` varchar(200) NOT NULL,
  `nome_sonoregular` varchar(200) NOT NULL,
  `sonoregular` varchar(700) NOT NULL,
  `nome_pranayama` varchar(200) NOT NULL,
  `pranayama` varchar(500) NOT NULL,
  `nome_asanas` varchar(200) NOT NULL,
  `asanas` varchar(700) NOT NULL,
  `nome_restaurativas` varchar(200) NOT NULL,
  `restaurativas` varchar(700) NOT NULL,
  `nome_nidra` varchar(200) NOT NULL,
  `nidra` varchar(700) NOT NULL,
  `nome_meditacao` varchar(200) NOT NULL,
  `meditacao` varchar(700) NOT NULL,
  `nome_bipolaridade` varchar(200) NOT NULL,
  `bipolaridade` varchar(2000) NOT NULL,
  `nome_humor` varchar(200) NOT NULL,
  `humor` varchar(200) NOT NULL,
  `status` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conteudos`
--

INSERT INTO `conteudos` (`id_conteudos`, `home`, `exercicio`, `nome_alongamento`, `alongamento`, `nome_respiracao`, `respiracao`, `nome_danca`, `danca`, `nome_caminhada`, `caminhada`, `nome_hiit`, `hiit`, `sono`, `nome_sonobipolaridade`, `sonobipolaridade`, `nome_sonodicas`, `sonodicas`, `nome_sonoregular`, `sonoregular`, `nome_pranayama`, `pranayama`, `nome_asanas`, `asanas`, `nome_restaurativas`, `restaurativas`, `nome_nidra`, `nidra`, `nome_meditacao`, `meditacao`, `nome_bipolaridade`, `bipolaridade`, `nome_humor`, `humor`, `status`) VALUES
(1, '', '', 'Atenção!', 'Esses alongamentos são ideais para iniciantes, melhorando a flexibilidade e reduzindo a rigidez muscular (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Esse exercício ajuda a aliviar a ansiedade e o estresse, promovendo calma e clareza mental (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Esse exercício rápido ajuda a liberar endorfina, promovendo bem-estar e reduzindo o estresse (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Esses passos ajudam a tornar a caminhada mais eficaz e segura para melhorar o bem-estar físico e mental  (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção', 'Esse treino pode ser repetido por várias séries, conforme sua capacidade física (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', '', 'Atenção!', 'Seguir essas dicas pode ajudar a melhorar a qualidade do sono e promover um descanso mais restaurador (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Este resumo destaca os principais pontos abordados no vídeo para ajudar na compreensão da importância do sono no contexto do transtorno bipolar (é crucial buscar tratamento especializado cuide da sua ', 'Atenção!', 'Esses pontos abordam os principais fatores que afetam a capacidade de acordar cedo e como lidar com eles. (é crucial buscar tratamento especializado. Cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Essa prática ajuda a trazer equilíbrio e relaxamento, sendo parte importante da saúde mental e física dentro da yoga (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Esses movimentos ajudam a alongar e fortalecer os músculos da coluna, contribuindo para a correção da postura (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Esse alongamento é ideal para relaxar e acalmar o corpo e a mente, ajudando a liberar tensões acumuladas (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Este é um processo de meditação guiada projetado para proporcionar relaxamento profundo e regenerador, conhecido como \"sono consciente\" (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção!', 'Este é um processo de meditação guiada projetado para proporcionar relaxamento profundo e regenerador, conhecido como \"sono consciente\" (é crucial buscar tratamento especializado cuide da sua saúde mental, busque um especialista!).', 'Atenção! Cuidar da saúde mental é essencial!', 'Se você ou alguém que você conhece ou vive com transtorno afetivo bipolar, é crucial buscar tratamento especializado. Cuide da sua saúde mental, busque um especialista!', '', '', '1'),
(2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Tipos', 'De acordo com o DSM.IV e o CID-10 (manuais internacionais de classificação diagnóstica), o transtorno bipolar pode ser classificado nos seguintes tipos, transtorno bipolar tipo I: caracterizado por episódios maníacos intensos, transtorno bipolar tipo II: marcado por episódios de hipomania e depressão.', '', '', '1'),
(4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Transtorno bipolar Tipo I', 'O portador do distúrbio apresenta períodos de mania, que duram, no mínimo, sete dias, e fases de humor deprimido, que se estendem de duas semanas a vários meses. Tanto na mania quanto na depressão, os sintomas são intensos e provocam profundas mudanças comportamentais e de conduta, que podem comprometer não só os relacionamentos familiares, afetivos e sociais, como também o desempenho profissional, a posição econômica e a segurança do paciente e das pessoas que com ele convivem. O quadro pode ser grave a ponto de exigir internação hospitalar por causa do risco aumentado de suicídios e da incidência de complicações psiquiátricas.', '', '', '1'),
(6, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Transtorno bipolar Tipo II', 'O transtorno bipolar tipo II, que requer um ou mais episódios depressivos maiores e pelo \r\nmenos um episódio hipomaníaco durante o curso da vida, não é mais considerado uma condição \r\n“mais leve” que o transtorno bipolar tipo I, em grande parte em razão da quantidade de tempo \r\nque pessoas com essa condição passam em depressão e pelo fato de a instabilidade do humor vivenciada\r\nser tipicamente acompanhada de prejuízo grave no funcionamento profissional e social. \r\nHá uma alternância entre os episódios de depressão e os de hipomania (estado mais leve de euforia, excitação, otimismo e, às vezes, de agressividade), sem prejuízo maior para o comportamento e as atividades do portador.', '', '', '1'),
(8, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Transtorno bipolar misto', 'Os sintomas sugerem o diagnóstico de transtorno bipolar, mas não são suficientes nem em número nem no tempo de duração para classificar a doença em um dos dois tipos anteriores.', '', '', '1'),
(10, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Transtorno ciclotímico', 'É o quadro mais leve do transtorno bipolar, marcado por oscilações crônicas do humor, que podem ocorrer até no mesmo dia. O paciente alterna sintomas de hipomania e de depressão leve que, muitas vezes, são entendidos como próprios de um temperamento instável ou irresponsável.', '', '', '1'),
(12, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Causas', 'Ainda não foi determinada a causa efetiva do transtorno bipolar, mas já se sabe que fatores genéticos, alterações em certas áreas do cérebro e nos níveis de vários neurotransmissores estão envolvidos.\r\nDa mesma forma, já ficou demonstrado que alguns eventos podem precipitar a manifestação desse distúrbio do humor nas pessoas geneticamente predispostas. Entre eles, destacam-se: episódios frequentes de depressão ou início precoce dessas crises, puerpério, estresse prolongado, remédios inibidores do apetite (anorexígenos e anfetaminas), e disfunções da tireoide, como o hipertireoidismo e o hipotireoidismo.', '', '', '1'),
(14, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Diagnóstico', 'O diagnóstico do transtorno bipolar é clinico, baseado no levantamento da história e no relato dos sintomas pelo próprio paciente ou por um amigo ou familiar. Em geral, ele leva mais de dez anos para ser concluído, porque os sinais podem ser confundidos com os de doenças como esquizofrenia, depressão maior, síndrome do pânico, distúrbios da ansiedade. Daí a importância de estabelecer o diagnóstico diferencial antes de propor qualquer medida terapêutica.', '', '', '1'),
(16, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Sintomas', 'Entre os principais sintomas em cada fase do transtorno bipolar, estão:\r\n\r\nDepressão: humor deprimido, tristeza profunda, apatia, desinteresse pelas atividades que antes davam prazer, isolamento social, alterações do sono e do apetite, redução significativa da libido, dificuldade de concentração, cansaço, sentimentos recorrentes de inutilidade, culpa excessiva, frustração e falta de sentido para a vida, esquecimentos, ideias suicidas.\r\n\r\nMania: estado de euforia exuberante, com valorização da autoestima e da autoconfiança, pouca necessidade de sono, agitação psicomotora, descontrole ao coordenar as ideias, desvio da atenção, compulsão para falar, aumento da libido, irritabilidade e impaciência crescentes, comportamento agressivo, mania de grandeza. Nessa fase, o paciente pode tomar atitudes que reverterão em danos a si próprio e às pessoas próximas, como demissão do emprego, gastos descontrolados de dinheiro, envolvimentos afetivos apressados, atividade sexual aumentada e, em casos mais graves, delírios e alucinações.\r\nHipomania: os sintomas são semelhantes aos da mania, porém bem mais leves e com menor repercussão sobre as atividades e relacionamentos do paciente, que se mostra mais eufórico, mais falante, sociável e ativo do que o habitual. Em geral, a crise é breve, dura apenas uns poucos dias. Para efeito de diagnóstico, é preciso assegurar que a reação não foi induzida pelo uso de antidepressivos.', '', '', '1'),
(18, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Tratamento', 'Transtorno bipolar não tem cura, mas pode ser controlado. O tratamento inclui o uso de medicamentos, psicoterapia e mudanças no estilo de vida, tais como o fim do consumo de substâncias psicoativas (cafeína, anfetaminas, álcool e cocaína, por exemplo), o desenvolvimento de hábitos saudáveis de alimentação e sono e redução dos níveis de estresse.\r\n\r\nDe acordo com o tipo, gravidade e evolução da doença, a prescrição de medicamentos neurolépticos, antipsicóticos, anticonvulsivantes, ansiolíticos e estabilizadores de humor, especialmente o carbonato de lítio, tem-se mostrado útil para reverter os quadros agudos de euforia e evitar a recorrência das crises. A associação de lítio com antidepressivos e anticonvulsivantes tem demonstrado maior eficácia para prevenir recaídas. No entanto, os antidepressivos devem ser utilizados com cuidado, porque podem provocar uma guinada rápida da depressão para a euforia, ou acelerar a incidência das crises.\r\n\r\nA psicoterapia é outro recurso importante no tratamento da bipolaridade, uma vez que oferece suporte para o paciente superar as dificuldades impostas pelas características da doença, ajuda a prevenir a recorrência das crises e, especialmente, promove a adesão ao tratamento medicamentoso que, como ocorre na maioria das doenças crônicas, deve ser mantido por toda a vida.', '', '', '1'),
(20, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Recomendações', 'Portadores de transtorno bipolar e seus familiares precisam estar cientes de que:\r\n\r\nSeguir o tratamento à risca é a melhor forma de prevenir a instabilidade emocional e a recorrência das crises, o que assegura a possibilidade de levar vida praticamente normal;\r\nOs remédios podem não fazer o efeito desejado logo nas primeiras doses que, muitas vezes, precisam ser ajustadas ao longo do tratamento;\r\nCrises depressivas prolongadas sem tratamento adequado podem aumentar em 15% o risco de suicídio nos pacientes bipolares;\r\nO paciente pode procurar alívio para os sintomas no álcool e em outras drogas, solução que só ajuda a agravar o quadro;\r\nAlternar a fase de depressão com a de mania pode dar a falsa sensação de que a pessoa está curada e não precisa mais de tratamento;\r\nA família pode precisar também de acompanhamento psicoterápico, por duas diferentes razões: primeira, porque o distúrbio pode afetar todos que convivem diretamente com o paciente; segunda, porque precisa ser orientada sobre como lidar no dia a dia com os portadores do transtorno.', '', '', '1'),
(21, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Preparação e Postura: Sente-se em uma posição confortável com a coluna ereta. Pode ser em uma cadeira ou no chão, em posição de lótus ou pernas cruzadas. A coluna deve estar bem alinhada para facilita a respiração.', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(23, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Respiração Diafragmática (Abdominal): Comece respirando profundamente pelo abdômen. Inspire pelo nariz, expandindo o abdômen para fora, e expire lentamente, contraindo o abdômen. O foco aqui é permitir que o diafragma se mova livremente.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(25, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Respiração Torácica (Peitoral): Após a respiração abdominal, mova a respiração para o tórax. Ao inspirar, expanda o peito, elevando as costelas. Na expiração, relaxe o peito.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(27, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Respiração Clavicular: O próximo passo é expandir a respiração para a área das clavículas. Inspire profundamente, permitindo que os ombros e a clavícula se elevem levemente. Expire e relaxe essa área.', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(32, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Respiração Completa: Combine as três fases de respiração (abdominal, torácica e clavicular) em uma respiração longa e contínua. Inspire profundamente expandindo o abdômen, o tórax e, por fim, a clavícula, em uma sequência fluida. Expire lentamente, esvaziando cada parte na ordem inversa.', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(33, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Ritmo e Consciência: Mantenha um ritmo estável e consciente da respiração. O foco é manter uma respiração controlada e profunda, trazendo mais oxigênio para o corpo e promovendo calma mental.', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(34, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Prática Regular: O vídeo sugere que você pratique o pranayama regularmente, de preferência em um ambiente tranquilo, para obter os benefícios completos da técnica, que inclui maior oxigenação, redução do estresse e melhora da concentração.', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(35, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Aquecimento alongamento de pescoço e ombros.\r\nSentada com as pernas cruzadas, posicione-se de forma ereta. Comece inclinando a cabeça para os lados lentamente, alongando o pescoço. Em seguida, faça movimentos circulares com os ombros para frente e para trás para aliviar a tensão.', '', '', '', '', '', '', '', '', '', '', '1'),
(36, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Postura da Montanha (Tadasana), levante-se, com os pés juntos e braços ao lado do corpo. Alinhe a coluna, relaxe os ombros e sinta o corpo firme, com o peso distribuído uniformemente nos pés. Respire fundo, mantendo a postura.', '', '', '', '', '', '', '', '', '', '', '1'),
(37, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Postura da Cadeira (Utkatasana), apartir da Postura da Montanha, eleve os braços acima da cabeça e flexione os joelhos como se estivesse sentando em uma cadeira invisível. Mantenha o peito aberto e os ombros relaxados', '', '', '', '', '', '', '', '', '', '', '1'),
(38, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Postura do Gato e da Vaca (Marjaryasana e Bitilasana), fique de quatro no chão, com as mãos alinhadas aos ombros e os joelhos aos quadris. Inspire e arqueie a coluna para baixo (Postura da Vaca), depois expire e arredonde a coluna para cima (Postura do Gato). Repita algumas vezes para mobilizar a coluna.\r\n', '', '', '', '', '', '', '', '', '', '', '1'),
(39, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Postura do Cachorro Olhando para Baixo (Adho Mukha Svanasana), a partir da posição de quatro apoios, eleve o quadril para o alto, formando um \"V\" invertido com o corpo. Mantenha os pés afastados na largura dos quadris e as mãos na largura dos ombros. Alongue as costas, mantendo o pescoço relaxado.', '', '', '', '', '', '', '', '', '', '', '1'),
(40, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Postura da Prancha, da Postura do Cachorro Olhando para Baixo, desça o corpo até formar uma prancha, com os ombros alinhados com os pulsos. Engaje o abdômen para sustentar a postura.', '', '', '', '', '', '', '', '', '', '', '1'),
(41, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Postura da Cobra (Bhujangasana), deite-se de barriga para baixo, com as mãos abaixo dos ombros. Inspire e, lentamente, eleve o peito, mantendo os cotovelos próximos ao corpo e alongando a coluna.\r\n', '', '', '', '', '', '', '', '', '', '', '1'),
(42, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '8º', 'Relaxamento Final  Postura da Criança (Balasana), termine a prática na Postura da Criança: sente-se sobre os calcanhares e estique os braços à frente, com a testa no chão. Respire profundamente e relaxe completamente.', '', '', '', '', '', '', '', '', '', '', '1'),
(43, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Respiração profunda comece sentando-se confortavelmente, com a coluna ereta. Feche os olhos e inspire profundamente pelo nariz, enchendo o abdômen e os pulmões. Expire lentamente, relaxando os ombros e o rosto. Repita algumas vezes para acalmar o corpo e a mente.', '', '', '', '', '', '', '', '', '1'),
(44, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Alongamento lateral sentado com as pernas cruzadas, levante o braço direito acima da cabeça e incline o corpo para a esquerda, alongando a lateral do corpo. Mantenha a postura por alguns segundos, respirando profundamente. Retorne ao centro e repita para o outro lado.', '', '', '', '', '', '', '', '', '1'),
(45, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Rotação suave da coluna ainda sentado, coloque a mão esquerda no joelho direito e a mão direita atrás do quadril. Faça uma torção suave, olhando por cima do ombro direito. Mantenha a respiração lenta e profunda. Volte ao centro e repita para o outro lado.', '', '', '', '', '', '', '', '', '1'),
(46, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Postura da criança (Balasana), tente-se sobre os calcanhares e incline-se para frente, esticando os braços à frente no chão. Deixe a testa encostar no tapete e relaxe a coluna e os ombros. Respire profundamente e mantenha essa postura por alguns instantes.\r\n', '', '', '', '', '', '', '', '', '1'),
(47, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Cachorro olhando para baixo (Adho Mukha Svanasana), levante-se da postura anterior e fique de quatro. Empurre o quadril para cima, formando um \"V\" invertido com o corpo. Alongue a coluna, pressionando as palmas no chão, e tente manter os calcanhares próximos ao solo. Respire profundamente.', '', '', '', '', '', '', '', '', '1'),
(48, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Postura da esfinge (Salamba Bhujangasana), deite-se de bruços e apoie os antebraços no chão, mantendo os cotovelos abaixo dos ombros. Levante o peito suavemente, abrindo o tórax e alongando a parte frontal do corpo. Mantenha a respiração calma.\r\n', '', '', '', '', '', '', '', '', '1'),
(49, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Postura da borboleta (Baddha Konasana), sente-se com as solas dos pés juntas e os joelhos abertos para os lados. Segure os pés com as mãos e mantenha a coluna ereta. Respire fundo e tente trazer os pés mais perto do corpo, sentindo o alongamento nos quadris.', '', '', '', '', '', '', '', '', '1'),
(50, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '8º', 'Torção deitada, deite-se de costas, abrace os joelhos em direção ao peito e, em seguida, deixe os joelhos caírem para o lado direito, estendendo o braço esquerdo para o lado. Mantenha essa posição por alguns momentos e repita para o outro lado.', '', '', '', '', '', '', '', '', '1'),
(51, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '9º', 'Savasana (Relaxamento final), deite-se de costas, com os braços ao lado do corpo e as palmas voltadas para cima. Feche os olhos e relaxe todo o corpo. Respire suavemente e permita-se relaxar por alguns minutos.\r\n', '', '', '', '', '', '', '', '', '1'),
(52, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Preparação e Posicionamento, deite-se em uma posição confortável, de preferência na postura de Savasana (deitado de costas, com os braços ao lado do corpo e as pernas ligeiramente afastadas). Feche os olhos e relaxe completamente.', '', '', '', '', '', '', '1'),
(53, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Concentração na Respiração, a instrutora guia o foco para a respiração, pedindo que você observe o fluxo natural do ar entrando e saindo das narinas. Isso ajuda a acalmar a mente e trazer consciência ao momento presente.\r\n', '', '', '', '', '', '', '1'),
(54, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Relaxamento Progressivo do Corpo a prática começa com um relaxamento consciente de cada parte do corpo. Você será guiado a levar sua atenção a diferentes partes, começando pelos pés, subindo pelas pernas, abdômen, braços, até chegar à cabeça. Isso ajuda a liberar tensões acumuladas.', '', '', '', '', '', '', '1'),
(55, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Visualização de Sensações durante o Yoga Nidra, você será guiado a visualizar sensações específicas, como calor ou leveza em determinadas áreas do corpo. Isso aprofunda o estado de relaxamento', '', '', '', '', '', '', '1'),
(56, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Afirmações Positivas (Sankalpa), a instrutora sugere a repetição mental de uma afirmação ou desejo pessoal. Esse é o momento de plantar uma semente de intenção no subconsciente, algo que você deseja realizar ou transformar em sua vida.\r\n', '', '', '', '', '', '', '1'),
(57, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Conscientização do Silêncio Interno, ao longo da prática, você será guiado a mergulhar em um estado de quietude e consciência plena, permitindo que a mente relaxe profundamente sem esforço.\r\n', '', '', '', '', '', '', '1'),
(58, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Retorno Gradual, após o estado de relaxamento profundo, a instrutora orienta a trazer suavemente a consciência de volta ao corpo e ao ambiente, movendo os dedos das mãos e pés, e, quando estiver pronto, abrindo os olhos lentamente', '', '', '', '', '', '', '1'),
(59, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Preparação e Posicionamento, deite-se em uma posição confortável, de preferência na postura de Savasana (deitado de costas, com os braços ao lado do corpo e as pernas ligeiramente afastadas). Feche os olhos e relaxe completamente.', '', '', '', '', '1'),
(60, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Concentração na Respiração, a instrutora guia o foco para a respiração, pedindo que você observe o fluxo natural do ar entrando e saindo das narinas. Isso ajuda a acalmar a mente e trazer consciência ao momento presente.', '', '', '', '', '1'),
(61, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Relaxamento Progressivo do Corpo, a prática começa com um relaxamento consciente de cada parte do corpo. Você será guiado a levar sua atenção a diferentes partes, começando pelos pés, subindo pelas pernas, abdômen, braços, até chegar à cabeça. Isso ajuda a liberar tensões acumuladas.\r\n', '', '', '', '', '1'),
(62, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Visualização de Sensações, durante o Yoga Nidra, você será guiado a visualizar sensações específicas, como calor ou leveza em determinadas áreas do corpo. Isso aprofunda o estado de relaxamento.', '', '', '', '', '1'),
(63, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Afirmações Positivas (Sankalpa), a instrutora sugere a repetição mental de uma afirmação ou desejo pessoal. Esse é o momento de plantar uma semente de intenção no subconsciente, algo que você deseja realizar ou transformar em sua vida.', '', '', '', '', '1'),
(64, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Conscientização do Silêncio Interno, ao longo da prática, você será guiado a mergulhar em um estado de quietude e consciência plena, permitindo que a mente relaxe profundamente sem esforço.\r\n', '', '', '', '', '1'),
(65, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Retorno Gradual, após o estado de relaxamento profundo, a instrutora orienta a trazer suavemente a consciência de volta ao corpo e ao ambiente, movendo os dedos das mãos e pés, e, quando estiver pronto, abrindo os olhos lentamente.', '', '', '', '', '1'),
(66, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Entenda o ciclo circadiano, nosso corpo tem um relógio biológico interno que regula quando devemos dormir e acordar, chamado ciclo circadiano. Ele é influenciado pela luz solar, que regula a produção de melatonina, o hormônio do sono.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(67, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Genética influencia os horários, algumas pessoas têm predisposição genética para serem \"matutinas\" (acordam cedo e dormem cedo) ou \"noturnas\" (dormem tarde e acordam tarde). Isso afeta a facilidade com que acordam em determinados horários.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(68, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Exposição à luz, a luz artificial pode alterar o ciclo natural, mantendo você acordado por mais tempo e dificultando acordar cedo. É importante reduzir a exposição a telas à noite e tentar se expor à luz solar pela manhã.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(69, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Sono inadequado, a dificuldade em acordar cedo pode estar relacionada à falta de sono ou à má qualidade do sono. Fatores como estresse, alimentação e rotina antes de dormir influenciam diretamente.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(70, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Rotina regular de sono, manter um horário regular de sono, inclusive nos finais de semana, ajuda o corpo a se ajustar e a acordar mais facilmente no mesmo horário todos os dias.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(71, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(72, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Evitar cafeína e estimulantes, o consumo de cafeína ou outros estimulantes próximos à hora de dormir pode atrapalhar o sono e tornar mais difícil acordar cedo.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(73, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Estresse e ansiedade, esses fatores podem atrapalhar tanto o adormecer quanto o acordar, pois afetam o relaxamento necessário para uma noite de sono reparadora.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(74, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '8º', 'Dicas práticas, estabeleça uma rotina relaxante à noite, limite a exposição a luzes brilhantes e telas, e tente acordar sempre no mesmo horário para treinar o corpo a se ajustar.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(75, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Estabeleça uma rotina de sono, procure dormir e acordar no mesmo horário todos os dias, incluindo finais de semana, para regular o relógio biológico.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(76, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Crie um ambiente confortável, certifique-se de que o quarto esteja escuro, silencioso e com uma temperatura agradável. Isso facilita o relaxamento.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(77, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Evite estimulantes à noite, reduza o consumo de cafeína, nicotina e outras substâncias estimulantes, especialmente no final do dia.\r\n\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(78, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Limite as sonecas diurnas, evite dormir durante o dia, ou mantenha as sonecas curtas, para que não interfiram no sono noturno.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(79, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Exercite-se regularmente, praticar exercícios físicos ajuda a melhorar a qualidade do sono, mas evite fazer atividades muito intensas perto da hora de dormir.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(80, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Tenha uma alimentação leve, evite refeições pesadas antes de dormir. Opte por lanches leves e nutritivos à noite.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(81, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Evite o uso de eletrônicos na cama, a luz das telas de dispositivos eletrônicos pode prejudicar a produção de melatonina, o hormônio do sono. Desconecte-se antes de dormir.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(82, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '8º', 'Crie um ritual relaxante, estabeleça uma rotina de relaxamento antes de dormir, como ler, ouvir música tranquila ou fazer meditação.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(83, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '9º', 'Use a cama apenas para dormir, evite usar a cama para outras atividades, como trabalho ou assistir TV. Isso ajuda o cérebro a associar o local ao descanso.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(84, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '10º', 'Controle a ansiedade e o estresse, técnicas de respiração, meditação ou terapia podem ajudar a lidar com preocupações que afetam o sono.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(85, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '11º', 'Evite bebidas alcoólicas, embora o álcool possa dar a sensação de relaxamento, ele pode interromper os ciclos de sono e piorar a qualidade geral.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(86, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12º', 'Procure ajuda médica se necessário, se as dificuldades persistirem, é importante consultar um especialista para investigar causas subjacentes, como distúrbios do sono.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(87, '', '', '', '', '', '', '', '', '', '', '', '', '', '1º', 'Introdução ao tem, o Dr. Renato Silva explica que o sono é uma necessidade básica para todas as pessoas, mas especialmente crucial para quem tem transtorno bipolar, pois afeta diretamente o humor e a ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(88, '', '', '', '', '', '', '', '', '', '', '', '', '', '2º', 'Influência do sono no transtorno bipolar, ele destaca que a falta de sono pode desencadear episódios de mania ou depressão em pessoas bipolares, mostrando como o ciclo de sono está intimamente ligado ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(89, '', '', '', '', '', '', '', '', '', '', '', '', '', '3º', 'Consequências da privação de sono, o Dr. Silva menciona que a privação de sono pode aumentar a impulsividade e piorar os sintomas do transtorno bipolar, podendo também afetar o raciocínio e a capacida', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(90, '', '', '', '', '', '', '', '', '', '', '', '', '', '4º', 'Importância de manter uma rotina de sono regular, um ponto crucial é manter uma rotina de sono consistente. Ir para a cama e acordar no mesmo horário ajuda a regular o ritmo circadiano, evitando deseq', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(91, '', '', '', '', '', '', '', '', '', '', '', '', '', '5º', 'Dicas para melhorar o sono, o médico sugere estratégias práticas, como evitar estímulos intensos antes de dormir (como uso de dispositivos eletrônicos), criar um ambiente relaxante e praticar técnicas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(92, '', '', '', '', '', '', '', '', '', '', '', '', '', '6º', 'Tratamentos para distúrbios do sono, ele aborda que, em alguns casos, medicamentos podem ser necessários para auxiliar no controle do sono, mas sempre sob supervisão médica, especialmente para quem já', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(93, '', '', '', '', '', '', '', '', '', '', '', '', '', '7º', 'Monitoramento do sono, o Dr. Renato recomenda que os pacientes e seus médicos monitorem a qualidade do sono regularmente, para identificar possíveis sinais de que algo pode estar afetando negativament', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(94, '', '', '', '', '', '', '', '', '', '', '', '', '', '8º', 'Em conclusão: Ele reforça que o sono é essencial para a saúde mental de todos, mas para pessoas com transtorno bipolar, ele é um pilar no tratamento. Uma boa noite de sono pode prevenir crises e promo', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(95, '', '', '', '', '', '', '', '', '', '', '1º', 'Aquecimento (3 minutos): Comece com uma corrida leve no lugar, balançando os braços para aumentar a circulação. Em seguida, faça movimentos de joelho alto e elevação do calcanhar para aquecer as pernas.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(96, '', '', '', '', '', '', '', '', '', '', '2º', 'Primeiro exercício (40 segundos): Agachamento simples com salto. Agache-se e, ao subir, dê um pequeno salto, retornando imediatamente para o agachamento. Desça devagar e suba explosivamente.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(97, '', '', '', '', '', '', '', '', '', '', '3º', 'Descanso (20 segundos): Caminhe pelo espaço, recuperando o fôlego.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(98, '', '', '', '', '', '', '', '', '', '', '4º', 'Segundo exercício (40 segundos): Corrida no lugar com joelhos altos. Levante os joelhos o mais alto possível, alternando rapidamente.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(99, '', '', '', '', '', '', '', '', '', '', '5º', 'Descanso (20 segundos): Respire fundo e caminhe devagar para recuperar o fôlego.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(100, '', '', '', '', '', '', '', '', '', '', '6º', 'Terceiro exercício (40 segundos): Burpees adaptados para iniciantes. Agache-se, coloque as mãos no chão, estique as pernas para trás (posição de prancha), volte e levante.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(101, '', '', '', '', '', '', '', '', '', '', '7º', 'Descanso (20 segundos): Caminhe e recupere a respiração.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(102, '', '', '', '', '', '', '', '', '', '', '8º', 'Quarto exercício (40 segundos): Polichinelos. Abra e feche as pernas e braços em um ritmo constante, pulando de leve.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(103, '', '', '', '', '', '', '', '', '', '', '9º', 'Descanso (20 segundos): Caminhe e respire fundo.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(104, '', '', '', '', '', '', '', '', '', '', '10º', 'Quinto exercício (40 segundos): Mountain climbers. Na posição de prancha, traga os joelhos alternadamente para perto do peito, em ritmo acelerado.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(105, '', '', '', '', '', '', '', '', '', '', '11º', 'Descanso (20 segundos): Caminhe e recupere o fôlego.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(106, '', '', '', '', '', '', '', '', '', '', '12º', 'Alongamento e relaxamento (3 minutos): Termine com um alongamento leve, focando nas pernas, braços e quadril.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(107, '', '', '', '', '1º', 'Preparação, sente-se em um local tranquilo, com a coluna reta e os pés tocando o chão. Relaxe os ombros e apoie as mãos sobre as pernas.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(108, '', '', '', '', '2º', 'Feche os olhos, isso ajuda a reduzir distrações externas e promove maior foco interno.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(109, '', '', '', '', '3º', 'Respiração consciente, inspire lentamente pelo nariz, enchendo os pulmões completamente. Sinta o ar expandir seu abdômen.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(110, '', '', '', '', '4º', 'Expiração lenta, exale suavemente pela boca, sentindo o ar sair do seu corpo. A expiração deve ser mais longa que a inspiração.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(111, '', '', '', '', '5º', 'Padrão rítmico, continue o ciclo, inspirando por cerca de 4 segundos, segurando a respiração por 2 segundos, e exalando por 6 segundos.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(112, '', '', '', '', '6º', 'Foco na respiração, concentre sua atenção no movimento do ar entrando e saindo do corpo, percebendo as sensações da respiração.\r\n\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(113, '', '', '', '', '7º', 'Repita por alguns minutos, continue esse padrão respiratório por 5 a 10 minutos ou até sentir uma sensação de relaxamento profundo.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(114, '', '', '', '', '', '', '1º', 'Aquecimento: Comece movimentando o corpo suavemente, mexendo os ombros e balançando os braços de forma leve para soltar a musculatura. Siga as instruções para elevar a frequência cardíaca.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(115, '', '', '', '', '', '', '2º', 'Passos Laterais com Braços: Faça movimentos laterais, deslocando o peso do corpo de um lado para o outro. Levante os braços, acompanhando o ritmo da música e mantenha uma respiração regular.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(116, '', '', '', '', '', '', '3º', 'Movimentos de Quadril: Agora, concentre-se em movimentos de quadril, balançando de um lado para o outro. Siga a coreografia e mantenha a postura ereta.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(117, '', '', '', '', '', '', '4º', 'Deslocamento com Movimentos de Braço: Continue se movendo de forma suave, deslocando-se para frente e para trás enquanto faz gestos com os braços, como se estivesse \"nadando\" no ar.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(118, '', '', '', '', '', '', '5º', 'Movimentos em Círculo: Adicione giros suaves, fazendo movimentos circulares com o corpo, sempre seguindo o ritmo da música.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(119, '', '', '', '', '', '', '6º', 'Aceleração: Na metade da aula, aumente o ritmo dos movimentos, mantendo o foco na respiração. Movimente-se de forma mais rápida, com passos e giros dinâmicos.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(120, '', '', '', '', '', '', '7º', 'Passos com Salto Leve: Siga para uma sequência de passos com pequenos saltos para elevar ainda mais a energia.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(121, '', '', '', '', '', '', '8º', 'Movimentos Combinados: Integre movimentos de braços, quadril e passos laterais, criando uma coreografia fluida e animada.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(122, '', '', '', '', '9º', 'Desaceleração: Nos minutos finais, reduza o ritmo, voltando a passos suaves e movimentos mais lentos, como balançar os braços e alongar o corpo.\r\n\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(123, '', '', '', '', '', '', '10º', 'Alongamento Final: Termine a aula com um breve alongamento, focando nos braços, pernas e costas para relaxar o corpo.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(124, '', '', '1º', 'Alongamento do Pescoço, sente-se ou fique em pé com a coluna ereta, incline a cabeça para um lado, aproximando a orelha do ombro, e segure por 20-30 segundos. Repita do outro lado.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(125, '', '', '2º', 'Alongamento dos Ombros, estenda um braço à frente do corpo e, com o outro braço, puxe-o em direção ao peito.\r\nMantenha por 20-30 segundos e troque de braço.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(126, '', '', '3º', 'Alongamento dos Braços e Costas, entrelaçe as mãos atrás da cabeça e empurre suavemente os cotovelos para trás, abrindo o peito. Segure por 20-30 segundos.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(127, '', '', '4º', 'Alongamento dos Quadris, fique em pé, com os pés afastados na largura dos ombros. Coloque as mãos nos quadris e faça movimentos circulares com o quadril, primeiro em uma direção, depois na outra.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(128, '', '', '5º', 'Alongamento das Pernas (Isquiotibiais), sente-se no chão com as pernas estendidas à frente. Incline-se para frente, tentando alcançar os pés com as mãos, sem forçar. Mantenha por 20-30 segundos.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(129, '', '', '6º', 'Alongamento das Coxas (Quadríceps), fique em pé, dobre uma perna para trás, segurando o tornozelo com a mão.\r\nPuxe o calcanhar em direção ao glúteo e mantenha o equilíbrio por 20-30 segundos. Troque de perna.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(130, '', '', '7º', 'Alongamento dos Glúteos, deite-se de costas, dobre uma perna e cruze-a sobre o joelho da outra. Puxe suavemente a perna de baixo em direção ao peito e segure por 20-30 segundos.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(131, '', '', '8º', 'Alongamento da Coluna (Torção de Coluna), sente-se no chão com uma perna estendida e a outra cruzada sobre ela. gire o tronco na direção da perna cruzada, apoiando a mão no chão para intensificar a torção. Mantenha por 20-30 segundos e troque de lado.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(132, '', '', '9º', 'Alongamento dos Tornozelos, sente-se no chão e estenda as pernas. Gire os tornozelos em movimentos circulares, 10 vezes para cada direção.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(133, '', '', '10º', 'Respiração Profunda, ao final do alongamento, sente-se confortavelmente, inspire profundamente pelo nariz e expire lentamente pela boca. Faça 3-5 respirações completas para relaxar.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(134, '', '', '', '', '', '', '', '', '1º', 'Posição do corpo, mantenha uma postura ereta, com o tronco reto e alinhado, olhando para frente. Evite inclinar-se para frente ou para trás.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(135, '', '', '', '', '', '', '', '', '2º', 'Movimentação dos braços, os braços devem estar levemente dobrados a 90 graus. Balance os braços naturalmente durante a caminhada, mantendo o movimento coordenado com as pernas.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(136, '', '', '', '', '', '', '', '', '3º', 'Passo adequado, ao caminhar, dê passos naturais e regulares. O calcanhar deve tocar o chão primeiro, seguido pelo arco e, finalmente, a ponta dos pés.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(137, '', '', '', '', '', '', '', '', '4º', 'Respiração correta, a respiração deve ser controlada, com inspirações e expirações profundas e regulares. Tente respirar pelo nariz, usando o diafragma para maximizar a eficiência respiratória.\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(138, '', '', '', '', '', '', '', '', '5º', 'Ritmo e velocidade, comece devagar para aquecer o corpo, depois aumente gradualmente a velocidade conforme seu condicionamento. Mantenha uma velocidade confortável, sem forçar.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(139, '', '', '', '', '', '', '', '', '6º', 'Foco no abdômen, contraia levemente os músculos abdominais durante a caminhada. Isso ajuda a estabilizar o tronco e melhora o equilíbrio.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(140, '', '', '', '', '', '', '', '', '7º', 'Alongamento pós-caminhada, após a caminhada, faça alongamentos para relaxar os músculos e evitar lesões.\r\nAlongue especialmente as pernas, panturrilhas, e quadris.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1'),
(142, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(143, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Registre seu humor', 'Como está se sentindo hoje?', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id_mensagens` int(11) NOT NULL,
  `mensagens` varchar(700) NOT NULL,
  `tipo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id_mensagens`, `mensagens`, `tipo`) VALUES
(4, 'Que ótimo que você está se sentindo tão bem! Aproveite esse momento de paz e alegria. Lembre-se de que você merece cada instante de felicidade. Continue cultivando esses momentos positivos, eles fortalecem sua resiliência em tempos difíceis.', '1'),
(5, 'Fico feliz que você esteja se sentindo bem! Aproveite esse equilíbrio emocional. Mesmo nos dias tranquilos, é importante celebrar cada pequena vitória. Continue se cuidando e respeitando seu ritmo, você está indo muito bem!', '2'),
(6, 'É totalmente normal ter dias em que nos sentimos neutros. Lembre-se de que tudo bem não estar no auge o tempo todo. Aproveite esse momento de calma para refletir e se conectar consigo mesmo. Esse equilíbrio também faz parte da jornada.', '3'),
(7, 'Sei que dias tristes podem parecer difíceis, mas saiba que eles não vão durar para sempre. Dê-se o direito de sentir e expressar essas emoções, elas também são importantes. Você é mais forte do que pensa e, com o tempo, vai superar esses momentos com coragem e aprendizado.', '4'),
(8, 'Parece que o estresse está presente hoje, e isso pode ser desgastante. Respire fundo e tente se desconectar por um momento. Lembre-se de que o estresse não define quem você é, e você tem o poder de cuidar de si mesmo. Pequenos passos de autocuidado podem fazer uma grande diferença.', '5'),
(9, 'A ansiedade pode ser intensa, mas você não está sozinho. Respire profundamente e lembre-se de que essa sensação vai passar. Procure focar no que está sob seu controle e celebre pequenas conquistas. Você é corajoso e está fazendo o seu melhor, e isso é o que mais importa.', '6'),
(10, 'Que alegria saber que você está se sentindo tão bem! Continue aproveitando essa energia positiva e espalhe essa luz ao seu redor. Momentos assim são uma prova de que você pode superar qualquer desafio. Mantenha-se firme no seu caminho e celebre cada vitória, grande ou pequena!', '1'),
(11, 'É maravilhoso sentir-se bem e em equilíbrio. Lembre-se de que esses momentos de paz são frutos do seu esforço em cuidar de si mesmo. Continue dando atenção às suas necessidades e reconhecendo o quanto você avançou. Cada passo conta nessa jornada.', '2'),
(12, 'Dias neutros também são valiosos, pois nos dão uma oportunidade de refletir sem pressa. Se permita viver o presente, sem pressões. Nem todos os dias precisam ser intensos. Use esse tempo para se reenergizar e saber que tudo tem seu momento certo.', '3'),
(13, 'Nos dias tristes, é importante lembrar que tudo passa, inclusive a tristeza. Permita-se sentir, mas também saiba que dias melhores virão. Você já passou por desafios antes e saiu mais forte, e desta vez não será diferente. Cada dia é uma nova chance para recomeçar.', '4'),
(14, 'O estresse pode parecer avassalador, mas você tem o poder de mudar isso. Pare um momento, respire fundo e tente se reconectar com o que traz paz. Pequenos intervalos para cuidar de si podem reduzir a pressão. Você merece se sentir em paz, então dê um passo de cada vez.', '5'),
(15, 'A ansiedade pode ser difícil, mas não dura para sempre. Respire fundo e saiba que você é mais forte do que a sensação que está experimentando agora. Tente focar no presente e lembre-se de que você já superou momentos assim antes. Confie em si mesmo, você vai conseguir.', '6');

-- --------------------------------------------------------

--
-- Estrutura para tabela `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `humor` varchar(220) NOT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `registros`
--

INSERT INTO `registros` (`id`, `descricao`, `id_usuario`, `humor`, `data`) VALUES
(47, 'lllll', 14, 'Muito bem', '2024-09-28'),
(48, 'aolaklsal', 0, 'Muito bem', '2024-09-29'),
(49, 'hoje foi intenso', 14, 'Muito bem', '2024-09-30'),
(50, 'kskjskalj', 14, 'Muito bem', '2024-10-02'),
(51, 'me sinto bem!', 15, 'Muito bem', '2024-10-05'),
(52, 'eu estou perfeito', 15, 'Muito bem', '2024-10-05'),
(53, 'estou mal', 15, 'Estressado(a)', '2024-10-05'),
(54, 'fiquei bem', 15, 'Muito bem', '2024-10-05'),
(55, 'estou muito bem', 15, 'Muito bem', '2024-10-22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `status`) VALUES
(7, 'administrador', 'administrador@gmail.com', '1212', '1', '1'),
(15, 'usuario', 'usuario@gmail.com', '124', '0', '1');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `conteudos`
--
ALTER TABLE `conteudos`
  ADD PRIMARY KEY (`id_conteudos`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id_mensagens`);

--
-- Índices de tabela `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `conteudos`
--
ALTER TABLE `conteudos`
  MODIFY `id_conteudos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id_mensagens` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
