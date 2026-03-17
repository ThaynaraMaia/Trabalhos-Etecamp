<?php 
include_once 'class_Conexao.php';

interface IRepositorioMemoria {
    public function salvarResultadoMemoria($id_usuario, $tempo_segundos);
    public function salvarOuAtualizarMelhorTempo($id_usuario, $tempo_segundos);
    public function obterRankingMemoria($limite = 10);
    public function obterMelhorTempoUsuario($id_usuario);
    public function obterHistoricoUsuario($id_usuario, $limite = 5);
}

class RepositorioMemoriaMYSQL implements IRepositorioMemoria {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","vidamarinha");
        if($this->conexao->conectar() == false) 
        {
            echo "Erro".mysqli_connect_error();
        }
    }
    
    public function getConexao() {
        return $this->conexao->getConnection();
    }

    public function salvarResultadoMemoria($id_usuario, $tempo_segundos) {
        $conn = $this->getConexao();
        
        $stmt = $conn->prepare("INSERT INTO ranking_memoria (id_usuario, tempo_segundos) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_usuario, $tempo_segundos);
        
        return $stmt->execute();
    }
    
  public function obterRankingMemoria($limite = 10) {
    $conn = $this->getConexao();
    
    $query = "SELECT 
                 u.nome, 
                 MIN(rm.tempo_segundos) as melhor_tempo,
                 MAX(rm.data_jogada) as ultima_jogada
              FROM ranking_memoria rm 
              INNER JOIN usuarios u ON rm.id_usuario = u.id 
              GROUP BY rm.id_usuario, u.nome
              ORDER BY melhor_tempo ASC 
              LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $ranking = [];
    
    while ($row = $result->fetch_assoc()) {
        $ranking[] = $row;
    }
    
    return $ranking;
}
    
    public function obterMelhorTempoUsuario($id_usuario) {
    $conn = $this->getConexao();
    
    $query = "SELECT MIN(tempo_segundos) as melhor_tempo 
              FROM ranking_memoria 
              WHERE id_usuario = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result && $row = $result->fetch_assoc()) {
        // Retornar apenas o valor numérico, não o array completo
        return (int)$row['melhor_tempo'];
    }
    
    return null;
}
    
    public function obterHistoricoUsuario($id_usuario, $limite = 5) {
        $conn = $this->getConexao();
        
        $query = "SELECT tempo_segundos, data_jogada 
                  FROM ranking_memoria 
                  WHERE id_usuario = ? 
                  ORDER BY data_jogada DESC 
                  LIMIT ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $limite);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $historico = [];
        
        while ($row = $result->fetch_assoc()) {
            $historico[] = $row;
        }
        
        return $historico;
    }

      public function salvarOuAtualizarMelhorTempo($id_usuario, $tempo_segundos) {
        $conn = $this->getConexao();
        
        // Usa INSERT ... ON DUPLICATE KEY UPDATE
        // Se o usuário já existe, atualiza APENAS se o novo tempo for menor
        $query = "INSERT INTO ranking_memoria (id_usuario, tempo_segundos, data_jogada) 
                  VALUES (?, ?, NOW())
                  ON DUPLICATE KEY UPDATE 
                    tempo_segundos = IF(VALUES(tempo_segundos) < tempo_segundos, VALUES(tempo_segundos), tempo_segundos),
                    data_jogada = IF(VALUES(tempo_segundos) < tempo_segundos, NOW(), data_jogada)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $tempo_segundos);
        
        return $stmt->execute();
    }
}

$respositorioMemoria = new RepositorioMemoriaMYSQL();
?>