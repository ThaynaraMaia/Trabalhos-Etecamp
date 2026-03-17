<?php

class Tarefa { //Criação da classe
    private $titulo;
    private $tarefa;
    private $dataCadastro;
    private $dataInicio;
    private $dataFim;
    private $autor;
    private $statusTarefa;

    //Conexão com os valores passados pelo obj
    public function __construct($titulo, $tarefa, $dataCadastro, $dataInicio, $dataFim, $autor, $statusTarefa) {
        $this->titulo = $titulo;
        $this->tarefa = $tarefa;
        $this->dataCadastro = $dataCadastro;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
        $this->autor = $autor;
        $this->statusTarefa = $statusTarefa;
    }


    //getters e setters
    public function getTitulo() {
        return $this->titulo;
    }
    public function getTarefa() {
        return $this->tarefa;
    }
    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getStatusTarefa() {
        return $this->statusTarefa;
    }
}
