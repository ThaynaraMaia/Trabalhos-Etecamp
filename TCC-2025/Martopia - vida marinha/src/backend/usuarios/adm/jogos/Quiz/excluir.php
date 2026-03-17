<?php
include_once '../../../../classes/class_IRepositorioQuiz.php';



if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $excluir=$respositorioQuiz->deletarPerguntas($id);

    header("Location:minhasPerguntas.php"); // volta para a lista depois de deletar
    exit;
}
