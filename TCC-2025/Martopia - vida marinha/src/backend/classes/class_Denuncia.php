<?php

class Denuncia {
    private $id;
    private $id_usuario;
    private $id_postagem;

    public function __construct($id,$id_usuario,$id_postagem){
        $this->id=$id;
        $this->id_usuario=$id_usuario;
        $this->id_postagem=$id_postagem;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setId_usuario($id_usuario){
        $this->id_usuario=$id_usuario;
    }

    public function getId_usuario(){
        return $this->id_usuario;
    }
    public function setId_postagem($id_postagem){
        $this->id_postagem=$id_postagem;
    }

    public function getId_post(){
        return $this->id_postagem;
    }

    
}