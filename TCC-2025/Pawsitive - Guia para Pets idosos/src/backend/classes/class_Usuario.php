<?php

class Usuario {
    private $idUsuario;
    private $nomeUsuario;
    private $emailUsuario;
    private $senhaUsuario;
    private $tipoUsuario;
    private $statusUsuario;
    private $fotoUsuario;

    public function __construct($idUsuario, $nomeUsuario, $emailUsuario, $senhaUsuario, $tipoUsuario, $statusUsuario, $fotoUsuario) {
        $this->idUsuario = $idUsuario;
        $this->nomeUsuario = $nomeUsuario;
        $this->emailUsuario = $emailUsuario;
        $this->senhaUsuario = $senhaUsuario;
        $this->tipoUsuario = $tipoUsuario;
        $this->statusUsuario = $statusUsuario;
        $this->fotoUsuario = $fotoUsuario;
    }

    // Getters e setters de todos os atributos

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
    }

    public function getEmailUsuario() {
        return $this->emailUsuario;
    }

    public function setEmailUsuario($emailUsuario) {
        $this->emailUsuario = $emailUsuario;
    }

    public function getSenhaUsuario() {
        return $this->senhaUsuario;
    }

    public function setSenhaUsuario($senhaUsuario) {
        $this->senhaUsuario = $senhaUsuario;
    }

    public function getTipoUsuario() {
        return $this->tipoUsuario;
    }

    public function setTipoUsuario($tipoUsuario) {
        $this->tipoUsuario = $tipoUsuario;
    }

    public function getStatusUsuario() {
        return $this->statusUsuario;
    }

    public function setStatusUsuario($statusUsuario) {
        $this->statusUsuario = $statusUsuario;
    }

    public function getFotoUsuario() {
        return $this->fotoUsuario;
    }

    public function setFotoUsuario($fotoUsuario) {
        $this->fotoUsuario = $fotoUsuario;
    }
}
