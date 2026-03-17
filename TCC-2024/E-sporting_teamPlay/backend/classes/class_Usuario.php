<?php

class usuario {
    private $id;
    private $nome;
    private $nick;
    private $email;
    private $senha;
    private $tipo;
    private $status;
    private $foto;
    private $descricao;
    private $region;
    private $gameplay;
    private $socials;

    public function __construct($id,$nome,$nick,$email,$senha,$tipo,$status,$foto,$descricao,$gameplay,$socials, $region){
        $this->id=$id;
        $this->nome=$nome;
        $this->nick=$nick;
        $this->email=$email;
        $this->senha=$senha;
        $this->tipo=$tipo;
        $this->status=$status;
        $this->foto=$foto;
        $this->descricao=$descricao;
        $this->gameplay=$gameplay;
        $this->socials=$socials;
        $this->region=$region;
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
    public function getNick(){
        return $this->nick;
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

    public function setFoto($foto){
        $this->foto=$foto;
    }

    public function getFoto(){
        return $this->foto;
    }
    public function getDesc(){
        return $this->descricao;
    }

    public function getGameplay(){
        return $this->gameplay;
    }
    public function getRegion(){
        return $this->region;
    }

    public function getSocials(){
        return $this->socials;
    }
}