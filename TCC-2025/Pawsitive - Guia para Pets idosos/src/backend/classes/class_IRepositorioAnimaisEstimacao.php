<?php

// include_once '../classes/class_conexao.php';
// include_once '../classes/class_AnimaisEstimacao.php';

include_once __DIR__ . '/class_conexao.php';
include_once __DIR__ . '/class_AnimaisEstimacao.php';

interface IRepositorioAnimalEstimacao
{
    public function listarAnimaisPorUsuario($idUsuario);
    public function removerAnimal($id, $idUsuario);
    public function cadastrarAnimal(AnimalE $animal, $idUsuario);
    public function atualizarAnimal(AnimalE $animal, $idUsuario);
    public function buscarAnimalPorId($id, $idUsuario);
}

class RepositorioAnimalEstimacaoMYSQL implements IRepositorioAnimalEstimacao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new mysqli("localhost", "root", "", "pawsitive");
        if ($this->conexao->connect_errno) {
            echo "Erro ao conectar: " . $this->conexao->connect_error;
        }
    }

    public function listarAnimaisPorUsuario($idUsuario)
    {
        $sql = "SELECT * FROM tblanimaisestimacao WHERE id_usuario = ?";
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

    public function removerAnimal($id, $idUsuario)
    {
        // Verificar se o animal pertence ao usuário
        $stmt = $this->conexao->prepare("SELECT id_usuario FROM tblanimaisestimacao WHERE id_animale = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($resultado['id_usuario'] != $idUsuario) {
            echo "Você não pode remover este animal.";
            return false;
        }

        // Remover o animal
        $stmt = $this->conexao->prepare("DELETE FROM tblanimaisestimacao WHERE id_animale = ?");
        $stmt->bind_param("i", $id);
        $executou = $stmt->execute();
        $stmt->close();

        return $executou;
    }

    public function cadastrarAnimal(AnimalE $animal, $idUsuario)
    {
        $stmt = $this->conexao->prepare("
            INSERT INTO tblanimaisestimacao (nome_animale, genero_animale, especie_animale, idade_animale, condicao_saudee, foto_animale, id_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }

        $nome = $animal->getNomeAnimale();
        $genero = $animal->getGeneroAnimale();
        $especie = $animal->getEspecieAnimale();
        $idade = $animal->getIdadeAnimale();
        $condicao = $animal->getCondicaoSaudee();
        $foto = $animal->getFotoAnimale();

        $stmt->bind_param("ssssssi", $nome, $genero, $especie, $idade, $condicao, $foto, $idUsuario);

        $executou = $stmt->execute();
        $stmt->close();

        return $executou;
    }

public function atualizarAnimal(AnimalE $animal, $idUsuario)
{
    // Verificar se o animal pertence ao usuário
    $stmt = $this->conexao->prepare("SELECT id_usuario FROM tblanimaisestimacao WHERE id_animale = ?");
    $idAnimal = $animal->getIdAnimale();
    $stmt->bind_param("i", $idAnimal);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($resultado['id_usuario'] != $idUsuario) {
        echo "Você não pode atualizar este animal.";
        return false;
    }

    // Atualizar o animal
    $stmt = $this->conexao->prepare("
    UPDATE tblanimaisestimacao 
    SET nome_animale = ?, genero_animale = ?, especie_animale = ?, idade_animale = ?, condicao_saudee = ?, foto_animale = ?
    WHERE id_animale = ?
    ");
    if (!$stmt) {
        echo "Erro na preparação: " . $this->conexao->error;
        return false;
    }

    $nome = $animal->getNomeAnimale();
    $genero = $animal->getGeneroAnimale();
    $especie = $animal->getEspecieAnimale();
    $idade = $animal->getIdadeAnimale();
    $condicao = $animal->getCondicaoSaudee();
    $foto = $animal->getFotoAnimale();
    $idAnimal = $animal->getIdAnimale();

    $stmt->bind_param("ssssssi", $nome, $genero, $especie, $idade, $condicao, $foto, $idAnimal);

    $executou = $stmt->execute();
    $stmt->close();

    return $executou;
}

    public function buscarAnimalPorId($id, $idUsuario)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM tblanimaisestimacao WHERE id_animale = ? AND id_usuario = ?");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }

        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_object();
        $stmt->close();

        return $resultado;
    }
}
