<?php

class Quiz {
    private $id;
    private $id_biologo;
    private $pergunta;
    private $opcao_a;
    private $opcao_b;
    private $opcao_c;
    private $opcao_d;
    private $resposta;
    private $dificuldade;

    public function __construct($id,$id_biologo,$pergunta,$opcao_a,$opcao_b,$opcao_c,$opcao_d,$resposta,$dificuldade){
        $this->id=$id;
        $this->id_biologo=$id_biologo;
        $this->pergunta=$pergunta;
        $this->opcao_a=$opcao_a;
        $this->opcao_b=$opcao_b;
        $this->opcao_c=$opcao_c;
        $this->opcao_d=$opcao_d;
        $this->resposta=$resposta;
        $this->dificuldade=$dificuldade;

        // $this->opcao_c=$opcao_c;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getId(){
        return $this->id;
    }
    public function setId_biologo($id_biologo){
        $this->id_biologo=$id_biologo;
    }

    public function getId_biologo(){
        return $this->id_biologo;
    }

    public function setPergunta($pergunta){
        $this->pergunta=$pergunta;
    }

    public function getPergunta(){
        return $this->pergunta;
    }

    public function setOpcao_a($opcao_a){
        $this->opcao_a=$opcao_a;
    }

    public function getOpcao_a(){
        return $this->opcao_a;
    }

    public function setOpcao_b($opcao_b){
        $this->opcao_b=$opcao_b;
    }

    public function getOpcao_b(){
        return $this->opcao_b;
    }

    public function setOpcao_c($opcao_c){
        $this->opcao_c=$opcao_c;
    }

    public function getOpcao_c(){
        return $this->opcao_c;
    }

     public function setOpcao_d($opcao_d){
        $this->opcao_d=$opcao_d;
    }

    public function getOpcao_d(){
        return $this->opcao_d;
    }

    public function setResposta($resposta){
        $this->resposta=$resposta;
    }

    public function getResposta(){
        return $this->resposta;
    }
    public function setDificuldade($dificuldade){
        $this->dificuldade=$dificuldade;
    }

    public function getDificuldade(){
        return $this->dificuldade;
    }
    
}