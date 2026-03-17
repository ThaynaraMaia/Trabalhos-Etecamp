<?php
include_once '../../classes/classIRepositorioLocais.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idLocal'])) {
        $id = intval($_POST['idLocal']);

        // Instancia o repositório
        $repositorio = new RepositorioLocalMYSQL();

        // Tenta remover o local pelo ID
        $removido = $repositorio->removerLocal($id);

        if ($removido) {
            // Redireciona para a página da lista para atualizar os dados após remoção
            header('Location: glocais.php');
            exit();
        } else {
            echo "Erro ao remover local.";
        }
    } else {
        echo "ID do local não fornecido.";
    }
} else {
    echo "Método inválido.";
}
?>