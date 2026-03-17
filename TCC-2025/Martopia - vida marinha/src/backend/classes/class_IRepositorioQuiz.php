<?php

include_once 'class_Conexao.php';
include_once 'class_Quiz.php';

interface IRepositorioQuiz
{
    public function adicionarPerguntas($perguntas);
    public function listarPerguntasPorBiologo($id);
    public function listarPerguntasPorNivel($nivel);
    public function salvarResultadoQuiz($id_usuario, $acertos, $tempo_segundos, $dificuldade);
    public function obterEstatisticasGeraisPorUsuario($limite = 10);
    public function obterMelhorResultadoPorDificuldade($id_usuario);
    public function editarPergunta($id, $id_biologo, $pergunta, $opcao_a, $opcao_b, $opcao_c, $opcao_d, $resposta, $dificuldade);
}

class ReposiorioQuizMYSQL implements IRepositorioQuiz
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "vidamarinha");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }
    public function getConexao()
    {
        return $this->conexao->getConnection();
    }

    public function adicionarPerguntas($perguntas)
    {
        $id = $perguntas->getId();
        $id_biologo = $perguntas->getId_biologo();
        $pergunta = $perguntas->getPergunta();
        $opcao_a = $perguntas->getOpcao_a();
        $opcao_b = $perguntas->getOpcao_b();
        $opcao_c = $perguntas->getOpcao_c();
        $opcao_d = $perguntas->getOpcao_d();
        $resposta = $perguntas->getResposta();
        $dificuldade = $perguntas->getDificuldade();

        $sql = "INSERT INTO perguntas_quiz (id,id_biologo,pergunta,opcao_a,opcao_b,opcao_c,opcao_d,resposta,dificuldade)
         VALUES ('$id','$id_biologo','$pergunta','$opcao_a','$opcao_b','$opcao_c','$opcao_d','$resposta','$dificuldade')";

        $salvaQuiz = $this->conexao->executarQuery($sql);

        return $salvaQuiz;
    }

    public function listarPerguntasPorBiologo($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM perguntas_quiz WHERE id_biologo = $id ORDER BY id DESC";
        $resultado = $this->conexao->executarQuery($sql);

        $perguntas = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $perguntas[] = $row;
            }
        }

        return $perguntas;
    }

    public function listarPerguntasPorNivel($nivel)
    {
        $nivel = (int)$nivel; // segurança, só número
        $sql = "SELECT * FROM perguntas_quiz WHERE dificuldade = $nivel ORDER BY RAND()";
        $resultado = $this->conexao->executarQuery($sql);

        $perguntas = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            $perguntas[] = [
                "question" => $row['pergunta'],
                "answers" => [
                    ["id" => "A", "text" => $row['opcao_a'], "correct" => ($row['resposta'] == 'A')],
                    ["id" => "B", "text" => $row['opcao_b'], "correct" => ($row['resposta'] == 'B')],
                    ["id" => "C", "text" => $row['opcao_c'], "correct" => ($row['resposta'] == 'C')],
                    ["id" => "D", "text" => $row['opcao_d'], "correct" => ($row['resposta'] == 'D')],
                ]
            ];
        }
        return $perguntas;
    }
    public function salvarResultadoQuiz($id_usuario, $acertos, $tempo_segundos, $dificuldade)
    {
        $id_usuario = (int)$id_usuario;
        $acertos = (int)$acertos;
        $tempo_segundos = (int)$tempo_segundos;
        $dificuldade = (int)$dificuldade;

        $sql = "INSERT INTO ranking_quiz (id_usuario, acertos, tempo_segundos, dificuldade) 
                VALUES ('$id_usuario', '$acertos', '$tempo_segundos', '$dificuldade')";

        $resultado = $this->conexao->executarQuery($sql);

        return $resultado;
    }
    public function obterEstatisticasGeraisPorUsuario($limite = 10)
    {
        $conn = $this->getConexao();

        // Query para somar os acertos e o tempo, agrupando por usuário.
        $query = "SELECT 
                  u.nome, 
                  rq.id_usuario,
                  SUM(rq.acertos) as total_acertos, 
                  SUM(rq.tempo_segundos) as total_tempo
              FROM 
                  ranking_quiz rq
              INNER JOIN 
                  usuarios u ON rq.id_usuario = u.id 
              GROUP BY 
                  rq.id_usuario, u.nome
              ORDER BY 
                  total_acertos DESC, total_tempo ASC
              LIMIT ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $limite);
        $stmt->execute();

        $result = $stmt->get_result();
        $estatisticas = [];

        while ($row = $result->fetch_assoc()) {
            $estatisticas[] = $row;
        }

        return $estatisticas;
    }

    public function obterMelhorResultadoPorDificuldade($id_usuario)
    {
        $conn = $this->getConexao();

        // A consulta SQL que encontra o melhor resultado (acertos) para cada dificuldade
        // para um usuário específico.
        $sql = "SELECT dificuldade, data_realizacao, tempo_segundos,MAX(acertos) AS melhor_acertos
            FROM ranking_quiz
            WHERE id_usuario = ?
            GROUP BY dificuldade
            ORDER BY dificuldade ASC";

        $stmt = $conn->prepare($sql);

        // Verifica se a preparação da query falhou
        if (!$stmt) {
            // Se houver um erro, você pode tratar de forma mais robusta,
            // mas para este exemplo, um array vazio é suficiente.
            return [];
        }

        // Vincula o ID do usuário ao placeholder da query
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $resultados = [];
        if ($result) {
            // Itera sobre os resultados e armazena em um array
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
        }

        // Fecha o statement para liberar recursos
        $stmt->close();

        // Retorna o array de resultados
        return $resultados;
    }

    public function deletarPerguntas($id) {
            // Deleta o usuário normalmente
    $sql = "DELETE FROM perguntas_quiz WHERE id = $id";
    $delete = $this->conexao->executarQuery($sql);

    return $delete;
    }

   public function editarPergunta($id, $id_biologo, $pergunta, $opcao_a, $opcao_b, $opcao_c, $opcao_d, $resposta, $dificuldade)
{
    // Pega a conexão mysqli, como em suas outras funções
    $conn = $this->getConexao();

    // 1. SQL com o nome da tabela correto ('perguntas_quiz') e placeholders '?'
    $sql = "UPDATE perguntas_quiz SET 
                id_biologo = ?, 
                pergunta = ?, 
                opcao_a = ?, 
                opcao_b = ?, 
                opcao_c = ?, 
                opcao_d = ?, 
                resposta = ?, 
                dificuldade = ? 
            WHERE id = ?";
            
    // 2. Prepara a consulta
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Se a preparação falhar, retorna falso.
        // Você pode adicionar um log de erro aqui, se desejar.
        // error_log('MySQLi prepare() failed: ' . $conn->error);
        return false;
    }

    // 3. Define a string de tipos e as variáveis para o bind_param.
    // i = integer (inteiro), s = string (texto)
    // A ordem deve ser EXATAMENTE a mesma dos '?' na consulta SQL.
    $tipos = "issssssii"; 
    $stmt->bind_param(
        $tipos,
        $id_biologo,
        $pergunta,
        $opcao_a,
        $opcao_b,
        $opcao_c,
        $opcao_d,
        $resposta,
        $dificuldade,
        $id // O 'id' do WHERE vem por último
    );

    // 4. Executa e armazena o resultado (true ou false).
    $sucesso = $stmt->execute();

    // 5. Fecha o statement para liberar recursos.
    $stmt->close();

    return $sucesso;
}
}
$respositorioQuiz = new ReposiorioQuizMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.
