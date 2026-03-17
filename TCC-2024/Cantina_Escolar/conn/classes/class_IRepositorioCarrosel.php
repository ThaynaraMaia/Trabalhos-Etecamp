<?php

include_once 'conexao.php';
include_once 'class_carrosel.php';

interface IRepositorioCarrosel
{
    public function mostrar_rodape();
    public function mostrar_imagens();
    public function salvarImagem($imagem, $caminho);
    public function atualizarImagensNoBanco($nomeImagem1, $nomeImagem2, $nomeImagem3);
    public function atualizar_rodape($telefone, $celular, $instagram, $facebook);
}

class ReposiorioCarroselMYSQL implements IRepositorioCarrosel
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "cantina_escolar");
        if ($this->conexao->conectar() == false) {
            echo "Erro: " . mysqli_connect_error();
        }
    }

    public function atualizar_rodape($telefone, $celular, $instagram, $facebook)
    {
        $sql = "UPDATE rodape SET telefone = '$telefone', celular = '$celular', instagram = '$instagram	', facebook='$facebook' WHERE id_rodape = 1";
        $altera = $this->conexao->executarQuery($sql);
        return $altera;
    }

    public function mostrar_rodape()
    {
        $sql = "SELECT * FROM rodape WHERE id_rodape = 1";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function mostrar_imagens()
    {
        $sql = "SELECT * FROM carrosel WHERE id = 1";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function salvarImagem($imagem, $caminho)
    {
        $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
        $nomeUnico = uniqid() . '.' . $extensao;
        $caminhoArquivo = $caminho . $nomeUnico;

        if (move_uploaded_file($imagem['tmp_name'], $caminhoArquivo)) {
            return $nomeUnico;
        } else {
            return false;
        }
    }

    public function atualizarImagensNoBanco($nomeImagem1, $nomeImagem2, $nomeImagem3)
    {
        $sql = "UPDATE carrosel SET imagem_1 = ?, imagem_2 = ?, imagem_3 = ? WHERE id = 1";

        $stmt = $this->conexao->getConnection()->prepare($sql);

        if ($stmt === false) {
            die('Erro ao preparar a query: ' . $this->conexao->getConnection()->error);
        }

        $stmt->bind_param("sss", $nomeImagem1, $nomeImagem2, $nomeImagem3);

        if ($stmt->execute()) {
            echo "Imagens atualizadas com sucesso!";
        } else {
            echo "Erro ao atualizar imagens no banco de dados: " . $stmt->error;
        }

        $stmt->close();
    }
}

$repositorioCarrosel = new ReposiorioCarroselMYSQL();
?>
