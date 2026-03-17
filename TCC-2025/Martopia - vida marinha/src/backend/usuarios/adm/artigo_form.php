<?php
include_once '../../classes/class_IRepositorioUsuarios.php';

$mensagem = "";
session_start();
if($_SESSION){
    $mensagem = $_SESSION['mensagem'];
} else {
    $mensagem = "";
}

$usuario = $_SESSION['nome'];
$id = $_SESSION['id_usuario'];

// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : '../../../frontend/public/img/fotoperfil.png';

$registroUsuario = $respositorioUsuario->listarTodosUsuarios();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Artigo</title>

    <!-- Estilos -->
    

    <!-- Ícones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

     <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
<body>


<style>
    :root{
    --corfundo:#dbf8ff;
}
/* ========================
   RESETA PADRÕES
======================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
body {
    height: 100vh;
    background-image: url('../../../frontend/public/img/baleia_fundo.jpg');
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
}


/* ========================
   HEADER
======================== */
.header {
    height: 120px;
    grid-area: header;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 1.6rem 1rem;
    display: flex;
    justify-content: space-between;
    z-index: 120;
}

.header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, .1);
    backdrop-filter: blur(50px);
    z-index: -1;
}
  .perfil{
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 1.5px solid #12ba2b;
    cursor: pointer;
    transition: transform 0.3s;
}

.logo {
    font-size: 2rem;
    color: #fff;
    text-decoration: none;
    font-weight: 700;
}
.navbar{
    width: 60%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.navbar a {
    justify-items: center;
    font-size: 1.15rem;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
}


#check { display: none; }
.icone {
    position: absolute;
    right: 0;
    font-size: 2.8rem;
    color: #fff;
    cursor: pointer;
    display: none;
}

main{
    position: relative;
    margin: 0rem 18rem;
    background: var(--corfundo);
    color: rgb(0, 0, 0);
    padding-top: 220px;
}

.form{
    text-align: center;
    padding: 20px;
    margin: auto;
    border: 1px solid;
    width: 790px;
}
.form label{
    
    font-size: 1.2rem;
    display: block;
    margin: 16px;
}

.input{
    padding: 8px;
    font-size: 1rem;
    width: 410px;
}

.text1{
    height: 500px;
}

.text ,.text1{
    padding: 8px;
    font-size: 1rem;
    width: 410px;

}
/* ========================
   FOOTER
======================== */


footer {
  position: relative;
  background-color: #052b5f;
  color: #fff;
  padding: 3rem 10%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  font-size: 0.9rem;
}

footer h3 {
  margin-bottom: 1rem;
  font-size: 1.3rem;
}

footer .contatos, footer .redes, footer .mapa {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

footer .redes a {
  color: #fff;
  font-size: 1.5rem;
  margin-right: 1rem;
  transition: color 0.3s;
}

footer .redes a:hover {
  color: #1da1f2; /* cor azul Twitter como exemplo */
  cursor: pointer;
}

footer .mapa iframe {
  border: none;
  width: 100%;
  height: 150px;
  border-radius: 10px;
}

/* Copyright */
footer .copyright {
  grid-column: 1 / -1;
  text-align: center;
  margin-top: 2rem;
  font-size: 0.85rem;
  color: #ccc;
  border-top: 1px solid #0b3971;
  padding-top: 1rem;
}

/* ========================
   RESPONSIVIDADE FOOTER
======================== */
@media (max-width: 900px) {
  footer {
    grid-template-columns: 1fr;
    text-align: center;
  }
  footer .redes a {
    margin: 0 0.5rem;
  }
  footer .mapa iframe {
    height: 200px;
    margin: 0 auto;
  }
}


</style>

<div class="container">
  
   <!-- NAVBAR -->
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
  
    <!-- <form action="artigo_salvar.php" method="POST" enctype="multipart/form-data"> -->
    <main>
      <div class="container">
        <form class="form" action="salvar_conteudo.php" method="POST" enctype="multipart/form-data">
            <h2>Cadastrar Artigo</h2>
          <label>Título:</label>
          <input class="input" type="text" name="titulo" required>
          <label>Descrição:</label>
          <textarea  class="text" name="descricao" rows="3"></textarea>
          <label>Conteúdo:</label>
          <textarea class="text1" name="conteudo" rows="6"></textarea>
          <label>Data de Publicação:</label>
          <input type="date" name="data_publicacao">
          <label>Autor:</label>
          <p><?php echo $_SESSION['nome'];?></p>
  
          <label>Tipo:</label>
          <input type="text" name="tipo">
          <label>Imagem:</label>
          <input type="file" name="imagem">
          <label>Legenda da imagem:</label>
          <input class="input" type="text" name="legenda">
          <button type="submit">Salvar</button>
          <p><?php echo $_SESSION['mensagem'];?></p>
        </form>
      </div>
    </main>
</div>

  
    <footer>
        <div class="contatos">
            <h3>Contatos</h3>
            <p>Email: contato@martopia.com.br</p>
            <p>Telefone: +55 11 99999-9999</p>
            <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
        </div>
        
        <div class="redes">
            <h3>Redes Sociais</h3>
            <div>
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
        </div>
        
        <div class="mapa">
            <h3>Localização</h3>
            <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr            allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Mapa do local">
            </iframe>
        </div>

        <div class="copyright">
            &copy; 2025 Projeto Martopia. Todos os direitos reservados.
        </div>
</footer>
</body>
</html>
