<?php
include 'db.php';
// Determina o semestre atual
$currentMonth = date('m');
$currentYear = date('Y');

// Calcula os meses a serem exibidos com base no semestre
if ($currentMonth <= 6) {
    $startMonth = 1;
    $endMonth = 6;
} else {
    $startMonth = 7;
    $endMonth = 12;
}

$months = range($startMonth, $endMonth);
$calendars = [];

foreach ($months as $month) {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
    $calendars[$month] = [
        'days' => $daysInMonth,
        'month' => $month,
        'year' => $currentYear,
        'events' => $pdo->query("SELECT * FROM events WHERE YEAR(event_date) = $currentYear AND MONTH(event_date) = $month ORDER BY event_date")->fetchAll(PDO::FETCH_ASSOC)
    ];
}
session_start();
$logged = false;
if (isset($_SESSION['on'])) {
    $logged = true;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/public/css/teste.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="icon" href="../../frontend/public/imagens/recursos/logo.png">
    <title>Eventos do Semestre</title>
    <style>
        *{
            text-align: center;
        }
        table{
            width: 80%;
            margin-left: 7rem;
        }
        td{
            border: solid 1px black;
            padding: 1%;
        }
        th{
            border: solid 1px black;
            padding: 1%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3 custom-navbar">
    <div class="container">

      <a class="navbar-brand" href="../../frontend/principal/index.php">
        <img src="../../frontend/public/imagens/recursos/logo.png " height="80rem" width="80rem" alt="Logo" height="40">
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
                <li class="nav-item">
            <a class="nav-link" href="../../backend/notificacoes/index.php"><img src="../../frontend/public/imagens/recursos/sino.png" width="20px"></a>
              </li>';
          }
          else{
            echo '<li class="nav-item">
                <a class="nav-link" href="../../backend/login/login.php">Faça Login</a>
              </li>';
          }
            ?>
        </ul>
      </div>
    </div>
  </nav><br>
    <br><br>
    <div class="content">
    <br>
    <style>
        table { width: 30%; border-collapse: collapse; display: inline-block; vertical-align: top; margin: 10px; }
        th, td { padding: 5px; text-align: center; border: 1px solid #ddd; }
        td a { text-decoration: none; }
        h2 { text-align: center; }
        .container { display: flex; flex-wrap: wrap; justify-content: center; }
    </style>

    <h1>Calendários de Eventos</h1><br>
    <div class="container">
        <?php foreach ($calendars as $calendar): ?>
            <table>
                <thead>
                    <tr>
                        <th colspan="7">
                            <?php echo date('F Y', mktime(0, 0, 0, $calendar['month'], 10, $calendar['year'])); ?>
                        </th>
                    </tr>
                    <tr>
                        <th>Dom</th>
                        <th>Seg</th>
                        <th>Ter</th>
                        <th>Qua</th>
                        <th>Qui</th>
                        <th>Sex</th>
                        <th>Sab</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $firstDayOfMonth = date('Y-m-01', strtotime($calendar['year'] . '-' . $calendar['month']));
                    $firstDayWeekday = date('w', strtotime($firstDayOfMonth));
                    
                    echo "<tr>";
                    for ($i = 0; $i < $firstDayWeekday; $i++) {
                        echo "<td></td>";
                    }

                    for ($day = 1; $day <= $calendar['days']; $day++) {
                        $date = $calendar['year'] . '-' . str_pad($calendar['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        echo "<td>";
                        echo "<a href='event_details.php?date=" . $date . "'>$day</a>";

                        foreach ($calendar['events'] as $event) {
                            if ($event['event_date'] == $date) {
                                echo "<br><strong>" . htmlspecialchars($event['title']) . "</strong>";
                            }
                        }
                        echo "</td>";

                        if (($day + $firstDayWeekday) % 7 == 0) {
                            echo "</tr><tr>";
                        }
                    }

                    // Preencher o resto da tabela com células vazias
                    $remainingCells = (7 - ($day + $firstDayWeekday) % 7) % 7;
                    for ($i = 0; $i < $remainingCells; $i++) {
                        echo "<td></td>";
                    }
                    echo "</tr>";
                    ?>
                </tbody>
            </table>
        <?php endforeach; ?><br><br>
    </div></div>
    <footer class="footer text-center text-lg-start d-flex fixed-bottom w-100 justify-content-between align-items-center">
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
