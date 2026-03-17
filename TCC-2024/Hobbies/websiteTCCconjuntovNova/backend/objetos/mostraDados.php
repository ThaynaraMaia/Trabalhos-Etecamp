<?php

include_once '../backend/classes/class_Conexao.php';
include_once '../backend/classes/class_Usuario.php';

$conexao = new Conexao("localhost","root","","mercury");
$conexao->conectar();

$usuario = new usuario(1,"João","joao_gomes@gmail.com","senha123","../public/imagens");

$sql = "INSERT INTO usuarios (id,nome,email,senha,foto_perfil)";

$conexao->executarQuery($sql);

echo "ID Usuario: ".$usuario->getId();
echo "<br>";
echo "Nome Usuario: ".$usuario->getNome();
echo "<br>";
echo "Email: ".$usuario->getEmail();
echo "<br>";
echo "Senha: ".$usuario->getSenha();
echo "<br>";
// echo "Foto: ".$usuario->getFotoPerfil();
// echo "<br>";
?>