<?php
include_once '../../../../classes/class_IRepositorioQuiz.php';
include_once '../../../../classes/class_Quiz.php'; // Inclua a nova classe

session_start();

// pega os dados
$id_usuario  = $_SESSION['id_usuario'];
$pergunta = $_POST['pergunta'];
$opcao_a = $_POST['opcao_a'];
$opcao_b = $_POST['opcao_b'];
$opcao_c = $_POST['opcao_c'];
$opcao_d = $_POST['opcao_d'];
$resposta = $_POST['resposta'];
$dificuldade = $_POST['dificuldade'];

// Criando objeto conteudo 
$perguntas = new quiz('', $id_usuario, $pergunta, $opcao_a, $opcao_b, $opcao_c, $opcao_d, $resposta, $dificuldade);

// Cadastra o Conteudo
$salvaQuiz = $respositorioQuiz->adicionarPerguntas($perguntas);
if($salvaQuiz){
         $_SESSION['mensagem'] = "pergunta salva com sucesso";
            header('Location:="../listar_tudo.php');
}
else{
    header('Location:quiz_form.php');
    exit();
}


?>