<?php
session_start();
$logged = false;
if (isset($_SESSION['on'])) {
    $logged = true;
}
include_once('../../backend/classes/conn.php')

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/teste.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="icon" href="../public/imagens/recursos/logo.png">
    <title>Usuário</title>
    <style>
      li{
        list-style: none;
      }
      .btn-custom-sm {
    width: 40%;
    padding: 10px;
    background-color: #81B973;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}
*{
  text-align: center;
}
.btn-custom-sm:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.btn-custom-sm:active {
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.showuser{
  width: 60%;
}
.userpfp{
  height: 300px;
  width: 300px;
  border-radius: 100%;
}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3 custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <img src="../../frontend/public/imagens/recursos/menu.png" width="20px" alt="">
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link active" aria-current="page" target="blank" href="../../frontend/public/docs/POR_2013_19_V2.pdf">Regulamento Interno</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/galeria/index.php">Galeria</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/Evento/index.php">Programação Semestral</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/mailer/fale.php">Fale Conosco</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../backend/forum/main.php">Fórum</a>
          </li>
          <?php 
          if($logged){
            if($_SESSION['tipo']>=1){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/loja/loja.php">Loja</a>
              </li>';
            }
            if($_SESSION['tipo']==2){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_mod.php">MOD</a>
              </li>';
            
            }
            if($_SESSION['tipo']==3){
              echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/adm/index_adm.php">ADM</a>
              </li>';
            
            }
            echo '<li class="nav-item">
            <a class="nav-link" href="../../frontend/principal/user.php?id='.$_SESSION["userid"].'">'.$_SESSION["nome"].'</a>
            </li>';
            echo '<li class="nav-item">
            <a class="nav-link" href="../../backend/notificacoes/index.php"><img src="../../frontend/public/imagens/recursos/sino.png" width="20px"></a>
            </li>';
            }
          else{
            echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/login/login.php"><strong>Faça Login</strong></a>
              </li>';
          }
            ?>
        </ul>
      </div>
    </div>
  </nav>
    <div class="content">
    <div class="showuser"><br>
    <?php
    $sql="SELECT * FROM tbl_usuarios WHERE id=".$_GET['id'];
    $res=$conn->query($sql);
    $linha=$res->fetch_assoc();
    ?>
        <li>
            <img src="../public/<?php echo('/imagens/usuarios/'.$linha['foto']);?>" class="userpfp" alt="">
        </li><br>
    <li>
        <h1><?php echo $linha['nome'];?></h1>
    </li><br>
    <?php
    
          if($linha['status']==1){
            echo('Aviso: Este usuário está banido por ter violado as regras.');
          }

    ?>
    <li class="userdescricao">
        <?php echo $linha['descricao'] ?>
    </li><br> 
    <?php
    if($linha['id']==$_SESSION['userid']){
        echo '<a href="../usuarios/edit_user.php"><button class="btn-custom-sm" id="Enviar">Editar Dados</button></a><br><br>';
        echo '<a href="../../backend/objeto/kill.php">Logout</a><br><br>';
    }
    ?>
     <h2>Lista de Tópicos</h2>

        <?php
    $sql = "SELECT * FROM topico WHERE userid=".$linha['id'];
    $res = $conn -> query($sql);
    if($res->num_rows>0){
      echo '
      <table class="table table-bordered">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Nome</th>
        <th scope="col">Data de Criação</th>';
        if($linha['id']==$_SESSION['userid']){
          echo '<th scope="col">Deletar</th>';
        }
        echo'</tr>
      </thead>
      <tbody>
      ';
      for($i = 0; $i < $res ->num_rows; $i++){
          $row = $res -> fetch_assoc();
          print "<tr><td><a href='../../backend/forum/topico.php?id=".$linha['id']."'>".$row['nome']."</a></td>";
          print "<td>".$row['data']."</td>";
          if($linha['id']==$_SESSION['userid']){
            print '<td> 
                        <a href="delete_topico.php?id='.$row["id"].'">
                        <button class="btn btn-danger btn-sm mx-1">
                            <i class="bi bi-trash"></i>
                        </button>
                        </a></td>';
          }
      }
    }
    else{
      echo "<br>O usuário não possui tópicos publicados.";
    }
    ?>
</tbody>
</table>

</div></div>
<footer class="footer fixed-bottom text-center text-lg-start d-flex justify-content-between align-items-center">
    <div class="container p-3 d-flex justify-content-between w-100">
      <span class="text-muted">© 2024 Grupo Escoteiro João Paulo II. Todos os direitos reservados.</span>
      <div class="social-icons">
        <a href="https://www.facebook.com/GrupoescoteirojoaopauloII?mibextid=ZbWKwL" target="_blank" class="text-muted me-3">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://youtube.com/@gejoaopauloii?si=8nJBM0zWxWBofH_V" target="_blank" class="text-muted me-3">
          <i class="bi bi-youtube"></i>
        </a>
        <a href="https://www.instagram.com/ge_joaopaulo2?igsh=MWFiOTA2OTFxYW11" target="_blank" class="text-muted me-3">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="https://www.tiktok.com/@ge._joaopauloii?_t=8nG7cVdsBGQ&_r=1" target="_blank" class="text-muted">
          <i class="bi bi-tiktok"></i>
        </a>
      </div>
    </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>