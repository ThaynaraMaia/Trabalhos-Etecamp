<?php

class usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $telefone;
    private $tipo;
    private $status;
    private $foto;

    public function __construct($id,$nome,$email,$senha,$telefone,$tipo,$status,$foto){
        $this->id=$id;
        $this->nome=$nome;
        $this->email=$email;
        $this->senha=$senha;
        $this->telefone=$telefone;
        $this->tipo=$tipo;
        $this->status=$status;
        $this->foto=$foto;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNome($nome){
        $this->nome=$nome;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setEmail($email){
        $this->email=$email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setSenha($senha){
        $this->senha=$senha;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setTelefone($telefone){
        $this->telefone=$telefone;
    }

    public function getTelefone(){
        return $this->telefone;
    }

    public function setTipo($tipo){
        $this->tipo=$tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setStatus($status){
        $this->status=$status;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setFoto($foto){
        $this->foto=$foto;
    }

    public function getFoto(){
        return $this->foto;
    }
}