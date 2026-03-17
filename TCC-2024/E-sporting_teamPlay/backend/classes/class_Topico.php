<?php

class Topico {
    private $id;
    private $nome;
    private $body;
    private $data;
    private $id_autor;
    private $anexos;

    public function __construct($id,$nome,$body,$data,$id_autor,$anexos){
        $this->id=$id;
        $this->nome=$nome;
        $this->body=$body;
        $this->data=$data;
        $this->id_autor=$id_autor;
        $this->anexos=$anexos;
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

    public function setbody($body){
        $this->body=$body;
    }

    public function getbody(){
        return $this->body;
    }

    public function setdata($data){
        $this->data=$data;
    }

    public function getdata(){
        return $this->data;
    }

    public function setid_autor($id_autor){
        $this->id_autor=$id_autor;
    }

    public function getid_autor(){
        return $this->id_autor;
    }

    public function setanexos($anexos){
        $this->anexos=$anexos;
    }

    public function getanexos(){
        return $this->anexos;
    }
}