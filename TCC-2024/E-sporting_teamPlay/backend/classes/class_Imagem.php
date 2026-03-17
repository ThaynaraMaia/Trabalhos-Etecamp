<?php

class img {
    private $id;
    private $nome;
    private $descricao;
    private $data;
    private $categoria;
    private $caminho;

    public function __construct($id,$nome,$descricao,$data,$categoria,$caminho){
        $this->id=$id;
        $this->nome=$nome;
        $this->descricao=$descricao;
        $this->data=$data;
        $this->categoria=$categoria;
        $this->caminho=$caminho;
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

    public function setDescricao($descricao){
        $this->descricao=$descricao;
    }

    public function getDescricao($descricao){
        return $this->descricao=$descricao;
    }

    public function setData($data){
        $this->data=$data;
    }

    public function getData(){
        return $this->data;
    }

    public function setCategoria($categoria){
        $this->categoria=$categoria;
    }

    public function getCategoria($categoria){
        return $this->categoria=$categoria;
    }

    public function setCaminho($caminho){
        $this->caminho=$caminho;
    }

    public function getCaminho(){
        return $this->caminho;
    }


}