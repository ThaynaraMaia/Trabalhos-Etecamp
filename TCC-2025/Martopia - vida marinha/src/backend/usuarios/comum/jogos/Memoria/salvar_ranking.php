<?php
session_start();
header('Content-Type: application/json');

// Incluir os repositórios necessários
include_once '../../../../classes/class_IRepositorioMemoria.php';

// LOG DE DEBUG - Ver o que está chegando
file_put_contents('log_sessao.txt', "SESSION: " . print_r($_SESSION, true) . PHP_EOL, FILE_APPEND);

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// CRÍTICO: Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado. Faça login novamente.',
        'debug' => 'Sessão não contém id_usuario'
    ]);
    exit;
}

// Receber dados JSON
$input = file_get_contents('php://input');
$dados = json_decode($input, true);

// LOG dos dados recebidos
file_put_contents('log_teste.txt', "INPUT: " . json_encode($dados) . PHP_EOL, FILE_APPEND);

// Validar dados recebidos
if (!isset($dados['tempo_segundos']) || !is_numeric($dados['tempo_segundos'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Tempo inválido ou não informado'
    ]);
    exit;
}

// USAR O ID DA SESSÃO (mais seguro que confiar no JavaScript)
$id_usuario = (int)$_SESSION['id_usuario'];
$tempo_segundos = (int)$dados['tempo_segundos'];

// Validar que os valores são válidos
if ($id_usuario <= 0 || $tempo_segundos <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos',
        'debug' => "ID: $id_usuario, Tempo: $tempo_segundos"
    ]);
    exit;
}

try {
    // Verificar se o usuário já possui um tempo salvo
    $tempoAnterior = $respositorioMemoria->obterMelhorTempoUsuario($id_usuario);
    
    // LOG antes de salvar
    file_put_contents('log_teste.txt', "Salvando: ID=$id_usuario, Tempo=$tempo_segundos" . PHP_EOL, FILE_APPEND);
    
    // Salvar no banco
    $resultado = $respositorioMemoria->salvarResultadoMemoria($id_usuario, $tempo_segundos);
    
    if ($resultado) {
        $response = [
            'success' => true,
            'message' => 'Resultado salvo com sucesso',
            'id_usuario' => $id_usuario,
            'tempo' => $tempo_segundos
        ];
        
        // Verificar se é um novo recorde
        if ($tempoAnterior === null || $tempoAnterior === 0) {
            $response['novo_recorde'] = true;
            $response['tipo_recorde'] = 'primeiro';
            $response['message'] = 'Primeiro tempo registrado!';
        } elseif ($tempo_segundos < $tempoAnterior) {
            $response['novo_recorde'] = true;
            $response['tipo_recorde'] = 'melhorou';
            $response['tempo_anterior'] = $tempoAnterior;
            $response['diferenca'] = $tempoAnterior - $tempo_segundos;
            $response['message'] = 'Novo recorde pessoal!';
        } elseif ($tempo_segundos === $tempoAnterior) {
            $response['novo_recorde'] = false;
            $response['tipo_recorde'] = 'empatou';
            $response['message'] = 'Você empatou seu melhor tempo!';
        } else {
            $response['novo_recorde'] = false;
            $response['tipo_recorde'] = 'nao_melhorou';
            $response['tempo_anterior'] = $tempoAnterior;
            $response['diferenca'] = $tempo_segundos - $tempoAnterior;
            $response['message'] = 'Continue tentando melhorar!';
        }
        
        echo json_encode($response);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao executar query no banco de dados'
        ]);
    }
    
} catch (Exception $e) {
    // Log do erro
    error_log('Erro ao salvar ranking: ' . $e->getMessage());
    file_put_contents('log_teste.txt', "ERRO: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar solicitação: ' . $e->getMessage()
    ]);
}
?>