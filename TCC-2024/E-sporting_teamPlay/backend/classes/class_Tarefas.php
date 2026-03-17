<?php

class tarefas{
    private $id;
    private $titulo;
    private $tarefa;
    private $data_cadastro;
    private $data_inicio;
    private $data_fim;
    private $autor;
    private $status_tarefa;

    public function __construct($id,$titulo,$tarefa,$data_cadastro,$data_inicio,$data_fim,$autor,$status_tarefa){
        $this->id=$id;
        $this->titulo=$titulo;
        $this->tarefa=$tarefa;
        $this->data_cadastro=$data_cadastro;
        $this->data_inicio=$data_inicio;
        $this->data_fim=$data_fim;
        $this->autor=$autor;
        $this->status_tarefa=$status_tarefa;
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

    public function setTarefa($tarefa){
        $this->tarefa=$tarefa;
    }

    public function getTarefa(){
        return $this->tarefa;
    }

    public function setDataCadastro($data_cadastro){
        $this->data_cadastro=$data_cadastro;
    }

    public function getDataCadastro(){
        return $this->data_cadastro;
    }

    public function setDataInicio($data_inicio){
        $this->data_inicio=$data_inicio;
    }

    public function getDataInicio(){
        return $this->data_inicio;
    }

    public function setDataFim($data_fim){
        $this->data_fim=$data_fim;
    }

    public function getDataFim(){
        return $this->data_fim;
    }

    public function setAutor($autor){
        $this->autor=$autor;
    }

    public function getAutor(){
        return $this->autor;
    }

    public function setStatusTarefa($status_tarefa){
        $this->status_tarefa=$status_tarefa;
    }

    public function getStatusTarefa(){
        return $this->status_tarefa;
    }

}