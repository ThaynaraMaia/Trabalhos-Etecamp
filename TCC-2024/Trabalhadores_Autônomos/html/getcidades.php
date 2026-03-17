<?php
include_once('../backend/Conexao.php');

try {
    
    $sql = "SELECT id_area, cidade FROM area_atuação";
    $result = $conn->query($sql);

    $cidades = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cidades[] = array(
                'id_area' => $row['id_area'],  
                'cidade' => $row['cidade'] 
            );
        }
    }

   
    echo json_encode($cidades);
} catch (Exception $e) {
    
    echo json_encode(array('error' => 'Erro ao buscar áreas: ' . $e->getMessage()));
} finally {
  
    $conn->close();
}
?>
