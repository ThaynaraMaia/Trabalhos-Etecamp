
<?php include 'bd.php';

if (isset($_POST['submit'])){
    $question_mumber = $_POST['question_number'];
    $question_text = $_POST ['question_text'];
    $question_choise = $_POST['correct_choise'];

    $choise = array();
    $choise[1] = $_POST['choise1'];
    $choise[2] = $_POST['choise2'];
    $choise[3] = $_POST['choise3'];
    $choise[4] = $_POST['choise4'];
    $choise[5] = $_POST['choise5'];

    $query = "INSERT INTO questions (";
    $query.="question_number, text )";
    $query.= "VALUES (";
    $query.= " '($question_number)','($question_text)' ";
    $query.= ")";

    $result=mysqli_query($conection, $query);
    // validade query
    if ($result){
        foreach($choise as $opition => $value){
            if($value!=""){
                if ($correct_choise == $opition){
                    $is_correct = 1;
                }else{
                    $is_correct = 0;
                }
            }
            $query = "INSERT INTO choises (";
            $query.= "question_number,is_correct,text";
            $query.= "VALUES (";
            $QUERY.= "'{$question_number}','{$is_correct}','{$value}'";

            $insert_row = mysqli_query($conection, $query);

            if($insert_row){
                continue;
            }else{
                die("2nd Query for choises could not be executed");
            }
        }
    }
    $mensage = "Question has been added successfully";
}

    
    $query = "SELECT * FROM questions";
    $questions = mysqli_query( $conection, $query);
    $total = mysqli_num_rows($questions);
    $next = $total+1;






?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/quiz.css">
</head>
<body>
    <header>
        <div class="container">
            <p>PHP Quiz</p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Add A Question</h2>

            <form method="$_POST" action="add.php">
            <p><label>Question Number</label>
                <input type="number" name="question_number" value=""></p>

             <p><label>Question Text:</label>
            <input type="text" name="question_text"></p>

            <p><label>choise 1:</label>
            <input type="text" name="choise1"></p>
            
            <p><label>choise 2:</label>
            <input type="text" name="choise2"></p>
            
            <p><label>choise 3:</label>
            <input type="text" name="choise3"></p>

            <p><label>choise 4:</label>
            <input type="text" name="choise4"></p>
            
            <p><label>choise 5:</label>
            <input type="text" name="choise5"></p>

            <p>
                <label>Correct Opition Number:</label>
                <input type="number" name="correct_choise">
            </p>
            <input type="submit" name="submit" value="submit">
            
            </form>
        </div>
    </main>
</body>
</html>