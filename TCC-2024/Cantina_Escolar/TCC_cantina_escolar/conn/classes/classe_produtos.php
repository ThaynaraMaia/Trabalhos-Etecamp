<?php

class produtos
{
    private $id_produto;
    private $nome_produto;
    private $descricao_produto;
    private $descricao_curta;
    private $quantidade_estoque;
    private $img;

    public function __construct($id_produto, $nome_produto, $descricao_produto, $descricao_curta, $quantidade_estoque, $img)
    {
        $this->id_produto = $id_produto;
        $this->nome_produto;
        $this->descricao_produto;
        $this->descricao_curta;
        $this->quantidade_estoque;
        $this->img;
    }

    public function setId_produto($id_produto)
    {
        $this->id_produto = $id_produto;
    }

    public function getId_produto()
    {
        return $this->id_produto; 
    }

    public function setNome_produto($nome_produto)
    {
        $this->nome_produto = $nome_produto;
    }

    public function getNome_produto()
    { 
        return $this->nome_produto;
    }

    public function setDescricao_produto($descricao_produto)
    {
        $this->descricao_produto = $descricao_produto;
    }

    public function getDescricao_produto()
    {
        return $this->descricao_produto; 
    }

    public function setDescricao_curta($descricao_curta)
    {
        $this->descricao_curta = $descricao_curta;
    }

    public function getDescricao_curta()
    {
        return $this->descricao_curta; 
    }

    public function setQuantidade_estoque($quantidade_estoque)
    {
        $this->quantidade_estoque = $quantidade_estoque;
    }

    public function getQuantidade_estoqueo()
    {
        return $this->quantidade_estoque;
    }
    public function setImg($img)
    {
        $this->img = $img;
    }

    public function getImg()
    {
        return $this->img; 
    }
}
?>
