<?php

class Ong
{
    private $idOng;
    private $nomeOng;
    private $fundacaoOng;
    private $historiaOng;
    private $fotoOng;

    private $telefones = []; // array de strings ou objetos telefone
    private $enderecos = []; // array de arrays ou objetos endereço

    public function __construct($idOng, $nomeOng, $fundacaoOng, $historiaOng, $fotoOng, $telefones = [], $enderecos = [])
    {
        $this->idOng = $idOng;
        $this->nomeOng = $nomeOng;
        $this->fundacaoOng = $fundacaoOng;
        $this->historiaOng = $historiaOng;
        $this->fotoOng = $fotoOng;
        $this->telefones = $telefones;
        $this->enderecos = $enderecos;
    }

    // Getters e setters

    public function getIdOng()
    {
        return $this->idOng;
    }

    public function setIdOng($idOng)
    {
        $this->idOng = $idOng;
    }

    public function getNomeOng()
    {
        return $this->nomeOng;
    }

    public function setNomeOng($nomeOng)
    {
        $this->nomeOng = $nomeOng;
    }

    public function getFundacaoOng()
    {
        return $this->fundacaoOng;
    }

    public function setFundacaoOng($fundacaoOng)
    {
        $this->fundacaoOng = $fundacaoOng;
    }

    public function getHistoriaOng()
    {
        return $this->historiaOng;
    }

    public function setHistoriaOng($historiaOng)
    {
        $this->historiaOng = $historiaOng;
    }
    public function getFotoOng()
    {
        return $this->fotoOng;
    }

    public function setFotoOng($fotoOng)
    {
        $this->fotoOng = $fotoOng;
    }

    // Telefones

    public function getTelefones()
    {
        return $this->telefones;
    }

    public function addTelefone($telefone)
    {
        $this->telefones[] = $telefone;
    }

    public function removeTelefone($telefone)
    {
        $index = array_search($telefone, $this->telefones);
        if ($index !== false) {
            unset($this->telefones[$index]);
            $this->telefones = array_values($this->telefones);
        }
    }

    public function setTelefones(array $telefones)
    {
        $this->telefones = $telefones;
    }

    // Endereços

    public function getEnderecos()
    {
        return $this->enderecos;
    }

    public function addEndereco(array $endereco)
    {
        // Exemplo de $endereco: ['rua'=>'...', 'numero'=>'...', 'complemento'=>'...', 'cidade'=>'...', 'estado'=>'...', 'cep'=>'...']
        $this->enderecos[] = $endereco;
    }

    public function removeEndereco(array $endereco)
    {
        foreach ($this->enderecos as $key => $end) {
            if ($end == $endereco) {
                unset($this->enderecos[$key]);
                $this->enderecos = array_values($this->enderecos);
                break;
            }
        }
    }

    public function setEnderecos(array $enderecos)
    {
        $this->enderecos = $enderecos;
    }
}
