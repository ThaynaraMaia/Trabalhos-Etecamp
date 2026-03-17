<?php

class Video {
    private $id;
    private $data_publicacao;
    private $id_autor;
    private $tipo;
    private $categoria;
    private $link; 

    public function __construct($id, $id_autor, $tipo, $categoria,$link,$data_publicacao) {
        $this->id = $id;
        $this->data_publicacao = $data_publicacao;
        $this->id_autor = $id_autor;
        $this->tipo = $tipo;
        $this->categoria = $categoria;
        $this->link = $link;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }
    public function setData_publicacao($data_publicacao){
        $this->data_publicacao=$data_publicacao;
    }

    public function getData_publicacao(){
        return $this->data_publicacao;
    }

     public function setId_autor($id_autor){
        $this->id_autor=$id_autor;
    }

    public function getId_autor(){
        return $this->id_autor;
    }

    public function setTipo($tipo){
        $this->tipo=$tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }
    
    public function setCategoria($categoria){
        $this->categoria=$categoria;
    }

    public function getCategoria(){
        return $this->categoria;
    }
    public function setLink($link){
        $this->link=$link;
    }

    public function getLink(){
        return $this->link;
    }
    
}