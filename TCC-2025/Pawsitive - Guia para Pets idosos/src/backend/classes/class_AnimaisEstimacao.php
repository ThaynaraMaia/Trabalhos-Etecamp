<?php

class AnimalE {
    private $id_animale;
    private $nome_animale;
    private $genero_animale;
    private $especie_animale;
    private $idade_animale;
    private $condicao_saudee;
    private $foto_animale;
    private $id_usuario;

    public function __construct($id_animale, $nome_animale, $genero_animale, $especie_animale, $idade_animale, $condicao_saudee, $foto_animale, $id_usuario) {
        $this->id_animale = $id_animale;
        $this->nome_animale = $nome_animale;
        $this->genero_animale = $genero_animale;
        $this->especie_animale = $especie_animale;
        $this->idade_animale = $idade_animale;
        $this->condicao_saudee = $condicao_saudee;
        $this->foto_animale = $foto_animale;
        $this->id_usuario = $id_usuario;
    }

    public function getIdAnimale() { return $this->id_animale; }
    public function setIdAnimale($id_animale) { $this->id_animale = $id_animale; }

    public function getNomeAnimale() { return $this->nome_animale; }
    public function setNomeAnimale($nome_animale) { $this->nome_animale = $nome_animale; }

    public function getGeneroAnimale() { return $this->genero_animale; }
    public function setGeneroAnimale($genero_animale) { $this->genero_animale = $genero_animale; }

    public function getEspecieAnimale() { return $this->especie_animale; }
    public function setEspecieAnimale($especie_animale) { $this->especie_animale = $especie_animale; }

    public function getIdadeAnimale() { return $this->idade_animale; }
    public function setIdadeAnimale($idade_animale) { $this->idade_animale = $idade_animale; }

    public function getCondicaoSaudee() { return $this->condicao_saudee; }
    public function setCondicaoSaudee($condicao_saudee) { $this->condicao_saudee = $condicao_saudee; }

    public function getFotoAnimale() { return $this->foto_animale; }
    public function setFotoAnimale($foto_animale) { $this->foto_animale = $foto_animale; }

    public function getIdUsuario() { return $this->id_usuario; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
}