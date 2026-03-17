<?php
include_once '../../classes/class_Conexao.php';
include_once '../../classes/class_IRepositorioUsuarios.php';
session_start();
$con = new Conexao("localhost","root","","vidamarinha");
$con->conectar();

$sql = "SELECT a.id, a.titulo, a.descricao, a.data_publicacao, u.nome AS autor, i.caminho_img
        FROM conteudos a
        LEFT JOIN usuarios u ON a.id_autor = u.id
        LEFT JOIN imagens_artigos i ON a.id = i.id_artigo
        ORDER BY a.data_publicacao DESC";
$res = $con->executarQuery($sql);

$id = $_SESSION['id_usuario'];

// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : '../../../frontend/public/img/fotoperfil.png';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Artigos</title>
      <link rel="stylesheet" href="../../../frontend/public/css/homeAdm.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

     <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
<body>
  <div class="container">
     <header class="header">
                <input type="checkbox" id="check">
            <label for="check" class="icone">
                <i class="bi bi-list" id="menu-icone"></i> 
                <i class="bi bi-x" id="sair-icone"></i>   
            </label>
            <a href="#" class="logo">Logo</a>
            <nav class="navbar">
                <a href="#" style="--i:1;">Perguntas</a>
                <a href="inicial.html" style="--i:2;">Ranking</a>
                <a href="artigo_listar.php" style="--i:2;">Meus Conteudos</a>
                <!-- <a href="addConteudos.php" style="--i:3;">Adicionar conteúdos</a> -->
                <a href="artigo_form.php" style="--i:3;">Adicionar conteúdos</a>

              
                  <a href="../../trocar/trocarperfil.php" ><img src="../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil"  class="perfil"></a>
            </nav>
        </header>
    <main>
      <h2>Lista de Artigos</h2>
      <a href="artigo_form.php">+ Novo Artigo</a>
      <table cellpadding="5">
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Data</th>
          <th>Imagem</th>
          <th>Ações</th>
        </tr>
        <?php while($r = mysqli_fetch_assoc($res)){ ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= $r['titulo'] ?></td>
            <td><?= $r['autor'] ?></td>
            <td><?= $r['data_publicacao'] ?></td>
            <td>
              <?php if($r['caminho_img']){ ?>
                <img src="../../../frontend/public/img_conteudos/<?= $r['caminho_img'] ?>" width="80">
              <?php } ?>
            </td>
            <td>
              <a href="artigo_excluir.php?id=<?= $r['id'] ?>">Excluir</a>
            </td>
          </tr>
        <?php } ?>
      </table>
    </main>
  </div>
</body>
</html>
