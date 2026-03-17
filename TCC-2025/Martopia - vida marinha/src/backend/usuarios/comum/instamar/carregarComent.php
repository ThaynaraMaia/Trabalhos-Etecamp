<?php
include_once '../../../classes/class_IRepositorioUsuarios.php';
session_start();
$repositorio = new ReposiorioUsuarioMYSQL();
$id_postagem = $_GET['id_postagem'];
$id_usuario = $_SESSION['id_usuario'];

$comentarios = $repositorio->listarComentarios($id_postagem);

if (!empty($comentarios)) {
    foreach ($comentarios as $c) {
        // Se não tiver foto, usa a padrão
        $foto = !empty($c['foto']) ? "../../../../" . $c['foto'] : "../../../../frontend/public/img/fotoperfil.png";

        // Se for o dono do comentário → mostra "Excluir"
        if ($id_usuario == $c['id_usuario']) {
            $opcao = "<a href='excluirComent.php?id_coment=".$c['id']."'>❌ Excluir</a>";
        } 
        // Se for outro usuário → mostra "Denunciar"
        else {
           $opcao = "<a href='#' class='acao-denunciar' data-id='".$c['id']."'>🚩 Denunciar</a>";
        }

        echo "
        <div class='comment-item'>
            <img src='".$foto."' alt='Avatar'>
            <div>
                <span class='comment-username'>".$c['nome']."</span><br>
                <span>".$c['texto']."</span><br>
                <small>".$c['data_comentario']."</small>
            </div>

            <!-- Botão de opções -->
            <div class='pontos'>
                <button class='menu-btn' onclick='toggleMenu(this)'>
                    <img src='../../../../frontend/public/img/pontos.png' width='20px' alt=''>
                </button>

                <!-- MENU DROPDOWN -->
                <div class='menu-dropdown'>
                    $opcao
                </div>
            </div>
        </div>
        ";
    }
} else {
    echo "<p>Seja o primeiro a comentar!</p>";
}
?>
