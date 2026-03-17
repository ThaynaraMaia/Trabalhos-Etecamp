<?php include 'db_connect.php'; ?>

<table border="1">
    <tr>
        <th>Task Name</th>
        <th>Description</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM planner");
    while($row = $result->fetch_assoc()) {
    ?>
    <tr>
        <td><?php echo $row['task_name']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['due_date']; ?></td>
        <td><?php echo $row['is_completed'] ? 'Completed' : 'Pending'; ?></td>
        <td>
            <a href="edit_task.php?id=<?php echo $row['id']; ?>">Edit</a> | 
            <a href="delete_task.php?id=<?php echo $row['id']; ?>">Delete</a> | 
            <a href="mark_complete.php?id=<?php echo $row['id']; ?>">Mark as Complete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
<body>
    
</body>
</html>