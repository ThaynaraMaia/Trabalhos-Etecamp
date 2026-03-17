<?php
session_start();
include_once '../../../../classes/class_Conexao.php';
$conn = new Conexao('localhost', 'root', '', 'vidamarinha');
$conn->conectar();

$data = json_decode(file_get_contents("php://input"), true);
$usuario_id = $_SESSION['id_usuario'];
$palavra_id = $data['palavra_id'];
$tentativas = $data['tentativas'];
$tempo = $data['tempo'];
$vitoria = $data['vitoria'] ? 1 : 0;

// 1️⃣ Salvar partida
$sql = "INSERT INTO termo_partidas (jogador, palavra_id, tentativas, data_jogo)
        VALUES (?, ?, ?, NOW())";
$conn->executarQuery($sql, [$_SESSION['nome'], $palavra_id, $tentativas]);

// 2️⃣ Atualizar ranking
// Se o usuário já estiver no ranking, atualiza
$sqlCheck = "SELECT id FROM termo_ranking WHERE usuario_id = ?";
$res = $conn->executarQuery($sqlCheck, [$usuario_id]);

if (mysqli_num_rows($res) > 0) {
    $update = "UPDATE termo_ranking 
               SET pontuacao = pontuacao + ?, 
                   partidas_jogadas = partidas_jogadas + 1, 
                   vitorias = vitorias + ?,
                   melhor_tempo = LEAST(COALESCE(melhor_tempo, $tempo), $tempo),
                   ultima_partida = NOW()
               WHERE usuario_id = ?";
    $pontos = $vitoria ? 10 : 0;
    $conn->executarQuery($update, [$pontos, $vitoria, $usuario_id]);
} else {
    $insert = "INSERT INTO termo_ranking (usuario_id, pontuacao, partidas_jogadas, vitorias, melhor_tempo, ultima_partida)
               VALUES (?, ?, 1, ?, ?, NOW())";
    $pontos = $vitoria ? 10 : 0;
    $conn->executarQuery($insert, [$usuario_id, $pontos, $vitoria, $tempo]);
}

echo "Partida salva com sucesso!";
?>
