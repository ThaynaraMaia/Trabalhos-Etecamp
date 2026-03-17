<?php

class Hobbie {
    private $id;
    private $id_usuarios;
    private $nome;
    private $status;
    private $descricao;
    private $sentimento;  

   
    public function __construct($id, $id_usuarios, $nome, $status, $descricao, $sentimento = null) {
        $this->id = $id;
        $this->id_usuarios = $id_usuarios;
        $this->nome = $nome;
        $this->status = $status;
        $this->descricao = $descricao;
        $this->sentimento = $sentimento;  
    }

    

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setIdUsuarios($id_usuarios) {
        $this->id_usuarios = $id_usuarios;
    }

    public function getIdUsuarios() {
        return $this->id_usuarios;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }


    public function setSentimento($sentimento) {
        $this->sentimento = $sentimento;
    }

    public function getSentimento() {
        return $this->sentimento;
    }
}
?>
