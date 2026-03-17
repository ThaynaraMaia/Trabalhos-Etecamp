<?php

class Local {
    private $idLocal;
    private $nomeLocal;
    private $descricaoLocal;
    private $endereco; // objeto Endereco
    private $horarioAbertura;
    private $horarioFechamento;
    private $tipo;

    public function __construct($idLocal, $nomeLocal, $descricaoLocal, Endereco $endereco, $horarioAbertura, $horarioFechamento, $tipo) {
        $this->idLocal = $idLocal;
        $this->nomeLocal = $nomeLocal;
        $this->descricaoLocal = $descricaoLocal;
        $this->endereco = $endereco;
        $this->horarioAbertura = $horarioAbertura;
        $this->horarioFechamento = $horarioFechamento;
        $this->tipo = $tipo;
    }

    // Getters e setters

    public function getIdLocal() {
        return $this->idLocal;
    }
    public function setIdLocal($idLocal) {
        $this->idLocal = $idLocal;
    }

    public function getNomeLocal() {
        return $this->nomeLocal;
    }
    public function setNomeLocal($nomeLocal) {
        $this->nomeLocal = $nomeLocal;
    }

    public function getDescricaoLocal() {
        return $this->descricaoLocal;
    }
    public function setDescricaoLocal($descricaoLocal) {
        $this->descricaoLocal = $descricaoLocal;
    }

    public function getEndereco() {
        return $this->endereco;
    }
    public function setEndereco(Endereco $endereco) {
        $this->endereco = $endereco;
    }

    public function getHorarioAbertura() {
        return $this->horarioAbertura;
    }
    public function setHorarioAbertura($horarioAbertura) {
        $this->horarioAbertura = $horarioAbertura;
    }

    public function getHorarioFechamento() {
        return $this->horarioFechamento;
    }
    public function setHorarioFechamento($horarioFechamento) {
        $this->horarioFechamento = $horarioFechamento;
    }

    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
}