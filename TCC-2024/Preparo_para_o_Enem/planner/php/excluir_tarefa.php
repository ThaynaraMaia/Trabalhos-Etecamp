<?php include 'db_connect.php'; ?>

<?php
$id = $_GET['id'];
$sql = "DELETE FROM planner WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Task deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
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