<?php
session_start();
include_once '../../../../classes/class_IRepositorioQuiz.php'; // Verifique se o caminho está correto

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Coleta dos dados do formulário
    $id = $_POST['id'];
    $id_biologo = $_POST['id_biologo'];
    $pergunta = $_POST['pergunta'];
    $opcao_a = $_POST['opcao_a'];
    $opcao_b = $_POST['opcao_b'];
    $opcao_c = $_POST['opcao_c'];
    $opcao_d = $_POST['opcao_d'];
    $resposta = $_POST['resposta'];
    $dificuldade = $_POST['dificuldade'];


    // Chama a função de edição
    $sucesso = $respositorioQuiz->editarPergunta($id, $id_biologo, $pergunta, $opcao_a, $opcao_b, $opcao_c, $opcao_d, $resposta, $dificuldade);

    if ($sucesso) {
        // Redireciona de volta para a página de listagem com uma mensagem de sucesso (opcional)
        $_SESSION['mensagem'] = "Pergunta atualizada com sucesso!";
    } else {
        // Redireciona com uma mensagem de erro (opcional)
        $_SESSION['mensagem_erro'] = "Erro ao atualizar a pergunta.";
    }

    // Redireciona para a página anterior
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();

} else {
    // Se não for POST, redireciona para a home
    header('Location: minhasPerguntas');
    exit();
}
?>