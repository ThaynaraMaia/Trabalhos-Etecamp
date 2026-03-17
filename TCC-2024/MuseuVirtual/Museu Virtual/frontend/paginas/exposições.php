<!DOCTYPE html>
<html lang="pt-br">
<?php
session_start();

include_once '../../backend/classes/class_repositorioObras.php';
include_once '../../backend/classes/class_repositorioCategorias.php';
include_once '../../backend/classes/class_repositorioCurtidas.php';

$registros = $repositorioObra->listarTodasObras();
$registrosCategorias = $repositorioCategoria->listarCategorias();
if (isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id'];

    include_once '../../backend/classes/class_repositorioUsuarios.php';
    $registroUsuario = $repositorioUsuario->buscarUsuario($_SESSION['id']);
    $listagemUsuario = $registroUsuario->fetch_object();
}


?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/exposicoes.css">
    <link rel="stylesheet" href="../bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Museu Virtual - Exposições</title>
</head>

<body style="background-color: lightgoldenrodyellow;">

    <header>
        <div class="col-md-2">
            <div class="logo">
                <img src="../img/Logo - ETECAMP CV.png">
            </div>
        </div>

        <div class="col-md-9">
            <div class="navbar">
                <a href="inicio.php">INÍCIO</a>
                <a href="">EXPOSIÇÕES</a>
                <?php
                //Se o usuário esiver logado, aparecerá a página que contém as obras dele em "Mostre sua arte", senão, aparecerá a página de login.
                if (isset($_SESSION['nome'])) {
                    if ($_SESSION['tipo'] == 1) {
                        echo "<a href=\"paginasAdm/gerenciarUsuarios.php\">USUÁRIOS</a>";
                    } else if ($_SESSION['tipo'] == 0) {
                        echo "<a href=\"paginasAluno/mostre_sua_arte-obras.php\">MOSTRE SUA ARTE</a>";
                    }
                } else {
                    echo "<a href=\"mostre_sua_arte-login.php\">MOSTRE SUA ARTE</a>";
                }
                ?>
                <?php
                //Se o usuário esiver logado, aparecerá a página que contém as obras dele em "Mostre sua arte", senão, aparecerá a página de login.
                if (isset($_SESSION['nome'])) {
                    if ($_SESSION['tipo'] == 1) {
                        echo "<a href=\"paginasAdm/gerenciarObras.php\">OBRAS</a>";
                    }
                }
                ?>
                <?php
                if (isset($_SESSION['nome'])) {
                    if ($_SESSION['tipo'] == 1) {
                        echo "<a href=\"paginasAdm/gerenciarCategorias.php\">CATEGORIAS</a>";
                    }
                }
                ?>
                <?php
                if (!isset($_SESSION['logado']) || isset($_SESSION['nome']) && $_SESSION['tipo'] != 1) {
                    echo "<a href=\"sobre.php\">SOBRE</a>";
                }
                ?>
            </div>
        </div>

        <?php

        //Código que só será exibido se o usuário estiver logado (acesso à página de perfil).

        if (isset($_SESSION['nome'])) {
            echo "<div class=\"col-md-1\">";
            echo "<div class=\"foto-perfil\">";
            echo "<a href=\"perfil.php\"><img src=\"../uploadsImg/{$listagemUsuario->foto}\" alt=\"Foto de perfil do usuário\" style=\"border: solid white; border-radius: 50%;\"></a>";
            echo "</div>";
            echo "</div>";
        }
        ?>

    </header>

    <container>

        <div class="filtros">
            <form method="GET" action="#">
                <label for="Filtro" style = "font-weight: bold;">Exibir por:</label>
                <select name="filtro_id" id="Filtro" onchange="mostrarCategorias()">
                    <option value=''>Todos</option>
                    <option value='curtidas'>Mais curtidas</option>
                    <option value='recentes'>Mais recentes</option>
                    <option value='categorias'>Categorias</option>
                </select>

                <div id="categoriasContainer" style="display: none; margin-left: 10px;">
                    <select name="categoria_id" id="categoria">
                        <?php
                        echo "<option value=''>Todos</option>";
                        while ($listaCategorias = $registrosCategorias->fetch_assoc()) {
                            echo "<option value='" . $listaCategorias['id'] . "'>" . $listaCategorias['nome'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit">Exibir</button>
            </form>
        </div>

        <script>
            function mostrarCategorias() {
                var selectFiltro = document.getElementById('Filtro');
                var categoriasContainer = document.getElementById('categoriasContainer');

                if (selectFiltro.value === 'categorias') {
                    categoriasContainer.style.display = 'inline-block'; // Mostra o container de categorias
                } else {
                    categoriasContainer.style.display = 'none'; // Esconde o container de categorias
                }
            }
        </script>

        <div class="conteudoObras" id="conteudoObras">


            <h1>TRABALHOS ARTÍSTICOS</h1>

            <div class="imagens">

                <?php

                //Quando o usuário escolhe filtrar obras por categoria
                if (isset($_GET['categoria_id']) && !empty($_GET['categoria_id'])) {

                    $categoria_id = intval($_GET['categoria_id']);

                    $filtra = $repositorioObra->filtrarObra($categoria_id);

                    while ($filtrouObra = $filtra->fetch_object()) {

                        if ($filtrouObra->status == 1) {
                            if($filtrouObra->categoria == 12){
                                echo "<a href=\"informacoesObra.php?id={$filtrouObra->id}\" class=\"exposicoes\" >";
                                echo "<video src=\"../uploadsVideos/{$filtrouObra->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\"></video>";
                                echo "<br>";
                            }else{
                                echo "<a href=\"informacoesObra.php?id={$filtrouObra->id}\" class=\"exposicoes\">";
                                echo "<img src=\"../uploadsImg/{$filtrouObra->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\">";
                                echo "<br>";
                            }
                            $numCurtidas = $repositorioCurtida->contarCurtidas($filtrouObra->id);
                            if (isset($_SESSION['id'])) {
                                $curtido = $repositorioCurtida->verificarCurtidas($usuario_id, $filtrouObra->id);

                                if ($curtido->num_rows > 0) {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$filtrouObra->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtido.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                } else {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$filtrouObra->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                }
                            }else{
                                echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$filtrouObra->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";
                                    echo "</a>";
                            }
                        }
                    }

                //Quando o usuário escolhe filtrar obras por mais recentes
                }else if(isset($_GET['filtro_id']) && ($_GET['filtro_id'])=="recentes"){
                    
                    $ordemRecente = $repositorioObra->listarMaisRecente();
                    
                    while ($ordenaObraRecente = $ordemRecente->fetch_object()) {

                        if ($ordenaObraRecente->status == 1) {
                            if($ordenaObraRecente->categoria == 12){
                                echo "<a href=\"informacoesObra.php?id={$ordenaObraRecente->id}\" class=\"exposicoes\" >";
                                echo "<video src=\"../uploadsVideos/{$ordenaObraRecente->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\"></video>";
                                echo "<br>";
                            }else{
                                echo "<a href=\"informacoesObra.php?id={$ordenaObraRecente->id}\" class=\"exposicoes\">";
                                echo "<img src=\"../uploadsImg/{$ordenaObraRecente->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\">";
                                echo "<br>";
                            }
                            $numCurtidas = $repositorioCurtida->contarCurtidas($ordenaObraRecente->id);
                            if (isset($_SESSION['id'])) {
                                $curtido = $repositorioCurtida->verificarCurtidas($usuario_id, $ordenaObraRecente->id);

                                if ($curtido->num_rows > 0) {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraRecente->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtido.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                } else {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraRecente->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                }
                            }else{
                                echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraRecente->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";
                                    echo "</a>";
                            }
                        }
                    }
                    
                //Quando o usuário escolhe filtrar obras por mais curtidas
                }else if(isset($_GET['filtro_id']) && ($_GET['filtro_id'])=="curtidas"){
                    
                    
                    $ordemCurtidas = $repositorioObra->listarMaisCurtida();
                    
                    while ($ordenaObraMaisCurtida = $ordemCurtidas->fetch_object()) {

                        if ($ordenaObraMaisCurtida->status == 1) {
                            if($ordenaObraMaisCurtida->categoria == 12){
                                echo "<a href=\"informacoesObra.php?id={$ordenaObraMaisCurtida->id}\" class=\"exposicoes\" >";
                                echo "<video src=\"../uploadsVideos/{$ordenaObraMaisCurtida->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\"></video>";
                                echo "<br>";
                            }else{
                                echo "<a href=\"informacoesObra.php?id={$ordenaObraMaisCurtida->id}\" class=\"exposicoes\">";
                                echo "<img src=\"../uploadsImg/{$ordenaObraMaisCurtida->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\">";
                                echo "<br>";
                            }
                            $numCurtidas = $repositorioCurtida->contarCurtidas($ordenaObraMaisCurtida->id);
                            if (isset($_SESSION['id'])) {
                                $curtido = $repositorioCurtida->verificarCurtidas($usuario_id, $ordenaObraMaisCurtida->id);

                                if ($curtido->num_rows > 0) {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraMaisCurtida->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtido.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                } else {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                          <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraMaisCurtida->id};\">
    
                                          <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                          </form>";
                                    echo "</a>";
                                }
                            }else{
                                echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$ordenaObraMaisCurtida->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";
                                    echo "</a>";
                            }
                        }
                    }

                    //Quando o usuário não escolhe filtrar obras
                    }else{
                    while ($listagem = $registros->fetch_object()) {

                        if ($listagem->status == 1) {

                            if($listagem->categoria == 12){
                                echo "<a href=\"informacoesObra.php?id={$listagem->id}\" class=\"exposicoes\" >";
                                echo "<video src=\"../uploadsVideos/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\"></video>";
                            }else{
                                echo "<a href=\"informacoesObra.php?id={$listagem->id}\" class=\"exposicoes\" >";
                                echo "<img src=\"../uploadsImg/{$listagem->trabalho_artistico}\" alt=\"Trabalho artístico\" style=\"border: 10px solid #ffff;\">";
                            }
                            $numCurtidas = $repositorioCurtida->contarCurtidas($listagem->id);
                            if (isset($_SESSION['id'])) {

                                $curtido = $repositorioCurtida->verificarCurtidas($usuario_id, $listagem->id);


                                if ($curtido->num_rows > 0) {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$listagem->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtido.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";

                                    echo "</a>";
                                } else {
                                    echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$listagem->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px;\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";
                                    echo "</a>";
                                }
                            }else{
                                echo "<form method=\"POST\" action=\"../../backend/curtidas/curtir.php\">
                                      <input type=\"hidden\" name=\"obra_id\" value=\"{$listagem->id};\">
                                      
                                      <button type=\"submit\" class=\"btn btn-light\" style=\"margin-top: 4px; padding-bottom: 6px\"><img src=\"../img/curtir.png\" alt=\"icone de curtida\" style=\"width: 28px;\"> {$numCurtidas}</button>
                                      <br>
                                      </form>";
                                    echo "</a>";
                            }
                        }
                    }
                }
                ?>

            </div>

        </div>

    </container>
</body>

</html>