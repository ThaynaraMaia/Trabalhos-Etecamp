<?php
include_once 'class_Conexao.php';

interface IRepositorioTermo
{
    public function obterRankingTermo( $limite = 10);
}

class RepositorioTermoMYSQL implements IRepositorioTermo
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "vidamarinha");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }
    public function getConexao()
    {
        return $this->conexao->getConnection();
    }
    // Novo método para buscar ranking do Termo
    public function obterRankingTermo($limite = 10)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT u.nome, r.pontuacao  
                FROM termo_ranking r
                JOIN usuarios u ON u.id = r.usuario_id
                ORDER BY r.pontuacao DESC
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$respositorioTermo = new RepositorioTermoMYSQL();
