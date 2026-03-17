<?php
include_once '../../classes/class_IRepositorioAnimaisAdocao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idAnimal'])) {
        $id = intval($_POST['idAnimal']);

        // Instancia o repositório
        $repositorio = new RepositorioAnimaisAdocaoMYSQL();

        // Tenta remover o animal pelo ID
        $removido = $repositorio->removerAnimalAd($id);

        if ($removido) {
            // Redireciona para a página da lista para atualizar os dados após remoção
            header('Location: ganimais.php');
            exit();
        } else {
            echo "Erro ao remover o animal.";
        }
    } else {
        echo "ID do animal não fornecido.";
    }
} else {
    echo "Método inválido.";
}
?>