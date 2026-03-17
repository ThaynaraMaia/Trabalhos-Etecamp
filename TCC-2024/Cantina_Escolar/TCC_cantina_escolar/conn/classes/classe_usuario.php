<?php

class usuario
{
    private $id;
    private $nome_completo;
    private $cpf;
    private $email;
    private $senha;
    private $tipo;
    private $status;
    private $foto;
    private $saldo;

    public function __construct($id, $nome_completo, $cpf, $email, $senha, $tipo, $status, $foto, $saldo)
    {
        $this->id = $id;
        $this->nome_completo = $nome_completo;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo = $tipo;
        $this->status = $status;
        $this->foto = $foto;
        $this->saldo = $saldo;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    { 
        return $this->id;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getCpf()
    { 
        return $this->cpf;
    }

    public function setNome_completo($nome_completo)
    {
        $this->nome_completo = $nome_completo;
    }

    public function getNome_completo()
    { 
        return $this->nome_completo;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    { 
        return $this->email;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getSenha()
    { 
        return $this->senha;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getTipo()
    { 
        return $this->tipo;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    { 
        return $this->status;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function getFoto()
    {
        return $this->foto; 
    }

    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    public function getSaldo()
    {
        return $this->saldo; 
    }
}
?>

