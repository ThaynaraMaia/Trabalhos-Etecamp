<?php

include_once 'class_Conexao.php';
include_once 'class_Tarefa.php';

interface IRepositorioTarefa {
    public function cadastrarTarefa($Tarefa);
    public function alterarTarefa($Tarefa);
    public function listarTodasTarefas();
    public function buscarTarefa($id);
    public function removerTarefa($id);
}

class ReposiorioTarefaMYSQL implements IRepositorioTarefa {
    
    public function cadastrarTarefa($Tarefa)
    {
        
    }

    public function alterarTarefa($Tarefa)
    {
        
    }

    public function listarTodasTarefas()
    {
        
    }

    public function buscarTarefa($id)
    {
        
    }

    public function removerTarefa($id)
    {
        
    }
}

