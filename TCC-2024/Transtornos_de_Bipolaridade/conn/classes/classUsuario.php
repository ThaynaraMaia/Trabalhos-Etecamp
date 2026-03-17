<?php

class usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $tipo;
    private $status;
  

    public function __construct($id,$nome,$email,$senha,$tipo,$status){
        $this->id=$id;
        $this->nome=$nome;
        $this->email=$email;
        $this->senha=$senha;
        $this->tipo=$tipo;
        $this->status=$status;

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
   
}
?>