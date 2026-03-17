<?php

class carrosle
{
    private $imagem_1;
    private $imagem_2;
    private $imagem_3;

    public function __construct($imagem_1, $imagem_2, $imagem_3)
    {
        $this->imagem_1 = $imagem_1;
        $this->imagem_2 = $imagem_2;
        $this->imagem_3 = $imagem_3;
    }

    public function setImagem_1($imagem_1)
    {
        $this->imagem_1 = $imagem_1;
    }

    public function getImagem_1()
    { 
        return $this->imagem_1;
    }

    public function setImagem_2($imagem_2)
    {
        $this->imagem_2 = $imagem_2;
    }

    public function getImagem_2()
    { 
        return $this->imagem_2;
    }

    public function setImagem_3($imagem_3)
    {
        $this->imagem_3 = $imagem_3;
    }

    public function getImagem_3()
    { 
        return $this->imagem_3;
    }
}
?>
