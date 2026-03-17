<?php

class postagens {
    private $id;
    private $id_usuario;
    private $conteudo;
    private $foto_post;
    private $data_postagem;

    public function __construct($id,$id_usuario, $conteudo, $foto_post, $data_postagem){
        $this->id=$id;
        $this->id_usuario=$id_usuario;
        $this->conteudo=$conteudo;
        $this->foto_post=$foto_post;
        $this->data_postagem=$data_postagem;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setIdUsuario($id_usuario){
        $this->id_usuario=$id_usuario;
    }

    public function getIdUsuario(){
        return $this->id_usuario;
    }

    public function setConteudo($conteudo){
        $this->conteudo=$conteudo;
    }

    public function getConteudo(){
        return $this->conteudo;
    }

    public function setFotoPost($foto_post){
        $this->foto_post=$foto_post;
    }

    public function getFotoPost(){
        return $this->foto_post;
    }

    public function setDataPostagem($data_postagem){
        $this->data_postagem=$data_postagem;
    }

    public function getDataPostagem(){
        return $this->data_postagem;
    }
}