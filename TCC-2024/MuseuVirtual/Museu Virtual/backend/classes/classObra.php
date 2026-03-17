<?php

    // Classe Obra, que contém todos os atributos que uma obra deve possuir.

    class Obra{
        private $id;
        private $titulo;
        private $categoria;
        private $descricao;
        private $trabalhoArtistico;
        private $autor;
        private $data;
        private $curtidas;
        private $texto;

        public function __construct($id, $titulo, $categoria, $descricao, $trabalhoArtistico, $autor, $data, $curtidas, $texto){
            $this->id=$id;
            $this->titulo=$titulo;
            $this->categoria=$categoria;
            $this->descricao=$descricao;
            $this->trabalhoArtistico=$trabalhoArtistico;
            $this->autor=$autor;
            $this->data=$data;
            $this->curtidas=$curtidas;
            $this->texto=$texto;
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

        public function setCategoria($categoria){
            $this->categoria=$categoria;
        }
        public function getCategoria(){
            return $this->categoria;
        }

        public function setDescricao($descricao){
            $this->descricao=$descricao;
        }
        public function getDescricao(){
            return $this->descricao;
        }

        public function setTrabalhoArtistico($trabalhoArtistico){
            $this->trabalhoArtistico=$trabalhoArtistico;
        }
        public function getTrabalhoArtistico(){
            return $this->trabalhoArtistico;
        }

        public function setAutor($autor){
            $this->autor=$autor;
        }
        public function getAutor(){
            return $this->autor;
        }

        public function setData($data){
            $this->data=$data;
        }
        public function getData(){
            return $this->data;
        }

        public function setCurtidas($curtidas){
            $this->curtidas=$curtidas;
        }
        public function getCurtidas(){
            return $this->curtidas;
        }

        public function setTexto($texto){
            $this->texto=$texto;
        }
        public function getTexto(){
            return $this->texto;
        }

    }

?>