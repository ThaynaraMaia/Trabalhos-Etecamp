<?php
session_start();

// Inclui o repositório
include_once '../../classes/class_IRepositorioInstamar.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
    exit();
}

// Verifica se o ID da notificação foi enviado
if (!isset($_POST['id_notificacao'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID da notificação não fornecido.']);
    exit();
}

$id_notificacao = intval($_POST['id_notificacao']);
$id_usuario = intval($_SESSION['id_usuario']);

// Chama a função para marcar como lida
$sucesso = $respositorioInstamar->marcarNotificacaoComoLida($id_notificacao, $id_usuario);

if ($sucesso) {
    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Notificação marcada como lida com sucesso!']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Não foi possível marcar a notificação como lida.']);
}
?>