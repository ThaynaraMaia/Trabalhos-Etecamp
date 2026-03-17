<?php

class Conexao {
    private $host;
    private $usuario;
    private $senha;
    private $banco;
    private $conexao;

    function __construct($host, $usuario, $senha, $banco) {
        $this->host = $host;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->banco = $banco;
    }

    function conectar() {
        $this->conexao = mysqli_connect(
            $this->host,
            $this->usuario,
            $this->senha,
            $this->banco
        );

        if ($this->conexao->connect_errno) {
            echo "Falha de Conexão MySQL: " . $this->conexao->connect_error;
            return false;
        } else {
            return true;
        }
    }

    function getConnection() {
        return $this->conexao;
    }

    // Este é o método que precisa ser ajustado.
    // Agora ele aceita um array de parâmetros ($params)
    function executarQuery($sql, $params = []) {
        // Se não houver parâmetros, use a função mysqli_query normal
        if (empty($params)) {
            return mysqli_query($this->conexao, $sql);
        }

        // Se houver parâmetros, use prepared statements
        $stmt = mysqli_prepare($this->conexao, $sql);
        if ($stmt === false) {
            die('Erro ao preparar a query: ' . mysqli_error($this->conexao));
        }

        // Determina os tipos dos parâmetros (i = integer, s = string, d = double, b = blob)
        // Isso é uma forma simples, mas pode precisar de ajustes dependendo dos seus dados
        $tipos = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $tipos .= 'i';
            } elseif (is_double($param)) {
                $tipos .= 'd';
            } else {
                $tipos .= 's';
            }
        }

        // Une os parâmetros ao statement
        mysqli_stmt_bind_param($stmt, $tipos, ...$params);

        // Executa a query
        $executou = mysqli_stmt_execute($stmt);

        // Se for um SELECT, retorna o resultado
        if ($executou && strtolower(substr(trim($sql), 0, 6)) == 'select') {
            return mysqli_stmt_get_result($stmt);
        }

        // Para outras operações (INSERT, UPDATE, DELETE), retorna o status
        return $executou;
    }

    // A função obtemPrimeiroregistroQuery está incompleta e não é necessária com a nova lógica
    // function obtemPrimeiroregistroQuery($query) {
    //     $linhas = $this->executarQuery($query);
    // }
}
?>