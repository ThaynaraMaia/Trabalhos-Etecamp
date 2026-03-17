<?php

class Animal {
    private $id_animal;
    private $nome_animal;
    private $caracteristicas_animal;
    private $cidade_animal;
    private $descricao_animal;
    private $genero_animal;
    private $especie_animal;
    private $idade_animal;
    private $condicao_saude;
    private $foto_animal;
    private $status_animal;

    public function __construct($id_animal, $nome_animal, $caracteristicas_animal, $cidade_animal, $descricao_animal, $genero_animal, $especie_animal, $idade_animal, $condicao_saude, $foto_animal, $status_animal) {
        $this->id_animal = $id_animal;
        $this->nome_animal = $nome_animal;
        $this->caracteristicas_animal = $caracteristicas_animal;
        $this->cidade_animal = $cidade_animal;
        $this->descricao_animal = $descricao_animal;
        $this->genero_animal = $genero_animal;
        $this->especie_animal = $especie_animal;
        $this->idade_animal = $idade_animal;
        $this->condicao_saude = $condicao_saude;
        $this->foto_animal = $foto_animal;
        $this->status_animal = $status_animal;
    }

    public function getIdAnimal() { return $this->id_animal; }
    public function setIdAnimal($id_animal) { $this->id_animal = $id_animal; }

    public function getNomeAnimal() { return $this->nome_animal; }
    public function setNomeAnimal($nome_animal) { $this->nome_animal = $nome_animal; }

    public function getCaracteristicasAnimal() { return $this->caracteristicas_animal; }
    public function setCaracteristicasAnimal($caracteristicas_animal) { $this->caracteristicas_animal = $caracteristicas_animal; }

    public function getCidadeAnimal() { return $this->cidade_animal; }
    public function setCidadeAnimal($cidade_animal) { $this->cidade_animal = $cidade_animal; }

    public function getDescricaoAnimal() { return $this->descricao_animal; }
    public function setDescricaoAnimal($descricao_animal) { $this->descricao_animal = $descricao_animal; }

    public function getGeneroAnimal() { return $this->genero_animal; }
    public function setGeneroAnimal($genero_animal) { $this->genero_animal = $genero_animal; }

    public function getEspecieAnimal() { return $this->especie_animal; }
    public function setEspecieAnimal($especie_animal) { $this->especie_animal = $especie_animal; }

    public function getIdadeAnimal() { return $this->idade_animal; }
    public function setIdadeAnimal($idade_animal) { $this->idade_animal = $idade_animal; }

    public function getCondicaoSaude() { return $this->condicao_saude; }
    public function setCondicaoSaude($condicao_saude) { $this->condicao_saude = $condicao_saude; }

    public function getFotoAnimal() { return $this->foto_animal; }
    public function setFotoAnimal($foto_animal) { $this->foto_animal = $foto_animal; }

    public function getStatusAnimal() { return $this->status_animal; }
    public function setStatusAnimal($status_animal) { $this->status_animal = $status_animal; }
}

?>