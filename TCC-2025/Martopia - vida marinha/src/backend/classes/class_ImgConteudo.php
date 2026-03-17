<?php

class ImgConteudo {
    private $id;
    private $id_artigo;
    private $caminho_img;
    private $legenda;
    private $arquivo; // Para o upload do arquivo

    public function __construct($id,$id_artigo,$caminho_img,$legenda,$arquivo = null){
        $this->id=$id;
        $this->id_artigo=$id_artigo;
        $this->caminho_img=$caminho_img;
        $this->legenda=$legenda;
        $this->arquivo = $arquivo;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }

    public function setId_artigo($id_artigo){
        $this->id_artigo=$id_artigo;
    }

    public function getId_artigo(){
        return $this->id_artigo;
    }

    public function setCaminho_img($caminho_img){
        $this->caminho_img=$caminho_img;
    }

    public function getCaminho_img(){
        return $this->caminho_img;
    }

    public function setLegenda($legenda){
        $this->legenda=$legenda;
    }

    public function getLegenda(){
        return $this->legenda;
    }
    public function setArquivo($arquivo){
        $this->arquivo=$arquivo;
    }

    public function getArquivo(){
        return $this->arquivo;
    }

    
}