<?php

class Conteudo {
    private $id;
    private $titulo;
    private $link;
    private $data_publicacao;
    private $id_autor;
    private $tipo;
    private $categoria; // << novo campo

    public function __construct($id, $titulo, $link, $data_publicacao, $id_autor, $tipo, $categoria) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->link = $link;
        $this->data_publicacao = $data_publicacao;
        $this->id_autor = $id_autor;
        $this->tipo = $tipo;
        $this->categoria = $categoria; // << atribui
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setTitulo($titulo){
        $this->titulo=$titulo;
    }

    public function getTitulo(){
        return $this->titulo;
    }

    public function setLink($link){
        $this->link=$link;
    }

    public function getLink(){
        return $this->link;
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
    
}