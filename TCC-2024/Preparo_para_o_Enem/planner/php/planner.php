<?php  
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "enemplatd_bd"; // O banco de dados que você já criou

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Inserir nova tarefa
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO planner (task_name, description, due_date) VALUES ('$task_name', '$description', '$due_date')";
    $conn->query($sql);
}

// Editar tarefa
if (isset($_POST['edit_task'])) {
    $id = $_POST['id'];
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $sql = "UPDATE planner SET task_name='$task_name', description='$description', due_date='$due_date' WHERE id=$id";
    $conn->query($sql);
}

// Excluir tarefa
if (isset($_GET['delete_task'])) {
    $id = $_GET['delete_task'];
    $sql = "DELETE FROM planner WHERE id=$id";
    $conn->query($sql);
}

// Marcar como concluída
if (isset($_GET['complete_task'])) {
    $id = $_GET['complete_task'];
    $sql = "UPDATE planner SET is_completed=1 WHERE id=$id";
    $conn->query($sql);
}

// Buscar todas as tarefas
$tasks = $conn->query("SELECT * FROM planner");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <!-- Seção de Gerenciamento de Tarefas -->
    <div class="tasks">
        <a href="../../php/usuario_page.php" class="btn-voltar">Voltar</a>
        <h2>Gerenciar Tarefas</h2>
        <form method="post" action="">
            <input type="text" name="task_name" placeholder="Nome da tarefa" required>
            <textarea name="description" placeholder="Descrição da tarefa"></textarea>
            <input type="date" name="due_date" required>
            <button type="submit" name="add_task">Adicionar Tarefa</button>
        </form>

        <!-- Tabela de Tarefas -->
        <h2>Lista de Tarefas</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Data de Vencimento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $tasks->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['task_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td class="<?php echo $row['is_completed'] ? 'completed' : 'pending'; ?>">
                        <?php echo $row['is_completed'] ? 'Concluída' : 'Pendente'; ?>
                    </td>
                    <td class="actions">
                        <div>
                            <a href="?complete_task=<?php echo $row['id']; ?>" style="color: green; font-weight: bold;">Concluir</a>
                        </div>
                        <div>
                            <a href="?delete_task=<?php echo $row['id']; ?>" style="color: red; font-weight: bold;">Excluir</a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <br>
    <!-- Seção de Calendário -->
    <div class="calendar">
        <h2>Calendário</h2>
        <div id="calendar"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/lang/pt-br.js"></script>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        locale: 'pt-br', // Definindo o idioma para português
        events: [
            <?php
            $tasks->data_seek(0); // Reset cursor para o resultado
            while ($task = $tasks->fetch_assoc()) {
                echo "{
                    title: '" . htmlspecialchars($task['task_name']) . "',
                    start: '" . $task['due_date'] . "',
                    color: '" . ($task['is_completed'] ? '#28a745' : '#ff9f89') . "'
                },"; // Mantido em português
            }
            ?>
        ],
        height: 'auto', // Ajusta a altura automaticamente
        contentHeight: 'auto', // Ajusta o conteúdo para não ter rolagem
        views: {
            month: {
                titleFormat: 'MMMM YYYY', // Formatação do título
                columnFormat: 'ddd', // Formatação da coluna (dias da semana)
            },
            agendaWeek: {
                titleFormat: 'D [de] MMMM [de] YYYY', // Formatação do título para a agenda semanal
                columnFormat: 'ddd D/M' // Formatação da coluna na agenda semanal
            },
            agendaDay: {
                titleFormat: 'D [de] MMMM [de] YYYY', // Formatação do título para a agenda diária
                columnFormat: 'ddd D/M' // Formatação da coluna na agenda diária
            }
        },
    });
});
</script>

</body>
</html>

