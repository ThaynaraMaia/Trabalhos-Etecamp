<?php
include_once "../classes/class_repositorioObras.php";

if (isset($_GET['categoria_id'])) {

    $categoria_id = intval($_GET['categoria_id']);


    // Prepare a consulta
    
    $filtra = $repositorioObra->filtrarObra($categoria_id);
    // $filtra->execute();
    // $result = $filtra->get_result();

    echo "<h2>Obras da Categoria Selecionada</h2>";
    while ($filtro = $filtra->fetch_assoc()) {
        echo "<p>" . $filtro['titulo'] . "</p>";
    }

    // $filtra->close();
}
?>