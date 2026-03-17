<?php

include_once 'class_conexao.php';
include_once 'class_AnimaisAdocao.php';

interface IRepositorioAnimaisAdocao
{
    public function listarTodosAnimaisAd();
    public function removerAnimalAd($id);
    public function cadastrarAA(Animal $animal);
    public function atualizarStatus($id, $status);
    public function buscarAnimalporId($id);
    public function atualizarAnimal(Animal $animal);

    public function adicionarFavorito($idAnimal, $idUsuario);
    public function removerFavorito($idAnimal, $idUsuario);
    public function usuarioJaFavoritou($idAnimal, $idUsuario);
    public function contarFavoritos($idAnimal);
    public function listarAnimaisCurtidosPorUsuario($idUsuario);
}

class RepositorioAnimaisAdocaoMYSQL implements IRepositorioAnimaisAdocao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new mysqli("localhost", "root", "", "pawsitive");
        if ($this->conexao->connect_errno) {
            echo "Erro ao conectar: " . $this->conexao->connect_error;
        }
    }

    public function listarTodosAnimaisAd()
    {
        $sql = "SELECT * FROM tblanimaisadocao"; // Ajuste nome da tabela do banco
        $registros = $this->conexao->query($sql);
        return $registros;
    }

    public function removerAnimalAd($id)
    {
        $stmt = $this->conexao->prepare("DELETE FROM tblanimaisadocao WHERE id_animal = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }
        $stmt->bind_param("i", $id);
        $executou = $stmt->execute();
        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
        }
        $stmt->close();
        return $executou;
    }

    public function cadastrarAA(Animal $animal)
    {
        $stmt = $this->conexao->prepare("
            INSERT INTO tblanimaisadocao (nome_animal, caracteristicas_animal, cidade_animal, descricao_animal, genero_animal, especie_animal, idade_animal, condicao_saude, foto_animal, status_animal)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }

        $nome = $animal->getNomeAnimal();
        $caracteristicas = $animal->getCaracteristicasAnimal();
        $cidade = $animal->getCidadeAnimal();
        $descricao = $animal->getDescricaoAnimal();
        $genero = $animal->getGeneroAnimal();
        $especie = $animal->getEspecieAnimal();
        $idade = $animal->getIdadeAnimal();
        $condicao = $animal->getCondicaoSaude();
        $foto = $animal->getFotoAnimal();
        $status = $animal->getStatusAnimal();

        $stmt->bind_param("ssssssiiss", $nome, $caracteristicas, $cidade, $descricao, $genero, $especie, $idade, $condicao, $foto, $status);

        $executou = $stmt->execute();
        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
        }

        $stmt->close();
        return $executou;
    }

    public function atualizarStatus($id, $status)
    {
        // Preparar statement para evitar injeção SQL
        $stmt = $this->conexao->prepare("UPDATE tblanimaisadocao SET status_animal = ? WHERE id_animal = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }
        $stmt->bind_param("si", $status, $id); // status string, id inteiro
        $executou = $stmt->execute();
        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
        }
        $stmt->close();
        return $executou;
    }

    public function buscarAnimalporId($id)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM tblanimaisadocao WHERE id_animal = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $animal = $resultado->fetch_object();
        $stmt->close();
        return $animal;
    }

    public function atualizarAnimal(Animal $animal)
{
    $stmt = $this->conexao->prepare("
        UPDATE tblanimaisadocao 
        SET nome_animal = ?, 
            caracteristicas_animal = ?, 
            cidade_animal = ?, 
            descricao_animal = ?, 
            genero_animal = ?, 
            especie_animal = ?, 
            idade_animal = ?, 
            condicao_saude = ?, 
            foto_animal = ?, 
            status_animal = ?
        WHERE id_animal = ?
    ");

    if (!$stmt) {
        echo "Erro na preparação: " . $this->conexao->error;
        return false;
    }

    $id = $animal->getIdAnimal();
    $nome = $animal->getNomeAnimal();
    $caracteristicas = $animal->getCaracteristicasAnimal();
    $cidade = $animal->getCidadeAnimal();
    $descricao = $animal->getDescricaoAnimal();
    $genero = $animal->getGeneroAnimal();
    $especie = $animal->getEspecieAnimal();
    $idade = $animal->getIdadeAnimal();
    $condicao = $animal->getCondicaoSaude();
    $foto = $animal->getFotoAnimal();
    $status = $animal->getStatusAnimal();

    $stmt->bind_param(
        "ssssssiissi", 
        $nome, $caracteristicas, $cidade, $descricao, $genero, $especie, $idade, $condicao, $foto, $status, $id
    );

    $executou = $stmt->execute();

    if (!$executou) {
        echo "Erro na execução: " . $stmt->error;
    }

    $stmt->close();
    return $executou;
}


    public function adicionarFavorito($idAnimal, $idUsuario)
    {
        $sql = "INSERT INTO tblfavoritos (id_animal, id_usuario) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idAnimal, $idUsuario);
        return $stmt->execute();
    }

    public function removerFavorito($idAnimal, $idUsuario)
    {
        $sql = "DELETE FROM tblfavoritos WHERE id_animal = ? AND id_usuario = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idAnimal, $idUsuario);
        return $stmt->execute();
    }

    public function usuarioJaFavoritou($idAnimal, $idUsuario)
    {
        $sql = "SELECT COUNT(*) as total FROM tblfavoritos WHERE id_animal = ? AND id_usuario = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idAnimal, $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'] > 0;
    }

    public function contarFavoritos($idAnimal)
    {
        $sql = "SELECT COUNT(*) as total FROM tblfavoritos WHERE id_animal = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idAnimal);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'];
    }

    public function listarAnimaisCurtidosPorUsuario($idUsuario)
    {
        $sql = "
        SELECT 
            t2.id_animal,
            t2.nome_animal,
            t2.caracteristicas_animal,
            t2.cidade_animal,
            t2.descricao_animal,
            t2.genero_animal,
            t2.especie_animal,
            t2.idade_animal,
            t2.condicao_saude,
            t2.foto_animal,
            t2.status_animal
        FROM 
            tblfavoritos AS t1
        JOIN 
            tblanimaisadocao AS t2
        ON 
            t1.id_animal = t2.id_animal
        WHERE 
            t1.id_usuario = ?
    ";

        $stmt = $this->conexao->prepare($sql);

        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $animais = [];
        while ($animal = $resultado->fetch_object()) {
            $animais[] = $animal;
        }

        $stmt->close();

        return $animais;
    }
}

$respositorioAnimaisAdocao = new RepositorioAnimaisAdocaoMYSQL();
