<?php

class carrinho
{
    private $id_carrinho;
    private $id_usuario;
    private $id_produto;
    private $quantidade;
    private $preco;

    public function __construct($id_carrinho, $id_usuario, $id_produto, $quantidade, $preco)
    {
        $this->id_carrinho = $id_carrinho;
        $this->id_usuario = $id_usuario;
        $this->id_produto = $id_produto;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
    }

    public function setId_carrinho($id_carrinho)
    {
        $this->id_carrinho = $id_carrinho;
    }

    public function getId_carrinho()
    {
        return $this->id_carrinho;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getId_usuario()
    {
        $this->id_usuario;
    }

    public function setId_produto($id_produto)
    {
        $this->id_produto = $id_produto;
    }

    public function getId_produto()
    {
        $this->id_produto; 
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade; 
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function getPreco()
    {
        return $this->preco;
    }
}
?>
