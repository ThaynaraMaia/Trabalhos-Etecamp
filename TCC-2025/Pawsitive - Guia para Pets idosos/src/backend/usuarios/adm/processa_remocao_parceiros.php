<?php
include_once '../../classes/class_IRepositorioOng.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idOng'])) {
        $id = intval($_POST['idOng']);

        // Instancia o repositório
        $repositorio = new RepositorioOngMYSQL();

        // Tenta remover o local pelo ID
        $removido = $repositorio->removerOng($id);

        if ($removido) {
            // Redireciona para a página da lista para atualizar os dados após remoção
            header('Location: gong.php');
            exit();
        } else {
            echo "Erro ao remover parceiro.";
        }
    } else {
        echo "ID do parceiro não fornecido.";
    }
} else {
    echo "Método inválido.";
}
?>