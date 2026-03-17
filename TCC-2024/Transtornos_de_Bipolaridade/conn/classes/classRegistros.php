<?php

class registros {
    private $id;
    private $descricao;
    private $id_usuario;
    private $humor;
    private $data;
  
  

    public function __construct($id,$descricao,$id_usuario,$humor,$data){
        $this->id=$id;
        $this->descricao=$descricao;
        $this->id_usuario=$id_usuario;
        $this->humor=$humor;
        $this->data=$data;



    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setDescricao($descricao){
        $this->descricao=$descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setId_usuario($id_usuario){
        $this->id_usuario=$id_usuario;
    }

    public function getId_usuario(){
        return $this->id_usuario;
    }

    public function setHumor($humor){
        $this->humor=$humor;
    }

    public function getHumor(){
        return $this->humor;
    }

    public function setData($data){
        $this->data=$data;
    }

    public function getData(){
        return $this->data;
    }
}
?>