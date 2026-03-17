<?php

class MarImg {
    private $id;
    private $id_usuario;
    private $foto;
    private $legenda;
    private $data_publicacao;

    public function __construct($id,$id_usuario,$legenda,$data_publicacao,$foto){
        $this->id=$id;
        $this->id_usuario=$id_usuario;
        $this->legenda=$legenda;
        $this->data_publicacao=$data_publicacao;
        $this->foto=$foto;
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
    public function setLegenda($legenda){
        $this->legenda=$legenda;
    }

    public function getLegenda(){
        return $this->legenda;
    }
 
    public function setData_publicacao($data_publicacao){
        $this->data_publicacao=$data_publicacao;
    }

    public function getData_publicacao(){
        return $this->data_publicacao;
    }

   public function setFoto($foto){
        $this->foto=$foto;
    }

    public function getFoto(){
        return $this->foto;
    }

    
}