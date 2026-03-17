<?php

class Comentario {
    private $id;
    private $id_postagem;
    private $id_usuario;
    private $texto;
    private $data_comentario;

    public function __construct($id, $id_postagem, $id_usuario, $texto, $data_comentario = null) {
        $this->id = $id;
        $this->id_postagem = $id_postagem;
        $this->id_usuario = $id_usuario;
        $this->texto = $texto;
        $this->data_comentario = $data_comentario;
    }

    // Getters e Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setId_postagem($id_postagem) {
        $this->id_postagem = $id_postagem;
    }

    public function getId_postagem() {
        return $this->id_postagem;
    }

    public function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setData_comentario($data_comentario) {
        $this->data_comentario = $data_comentario;
    }

    public function getData_comentario() {
        return $this->data_comentario;
    }
}
?>