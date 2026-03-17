<?php
include_once '../../classes/classIRepositorioLocais.php';
include_once '../../classes/class_Locais.php';
include_once '../../classes/class_EnderecoLocais.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método inválido.";
    exit;
}

$idLocal = intval($_POST['idLocal']);
$idEndereco = intval($_POST['idEndereco']);

$repositorio = new RepositorioLocalMYSQL();
$localExistente = $repositorio->buscarPorId($idLocal);

if (!$localExistente) {
    echo "Local não encontrado.";
    exit;
}

// Atualiza dados do endereço
$endereco = new Endereco(
    $idEndereco,
    $_POST['rua'] ?? '',
    $_POST['numero'] ?? '',
    $_POST['bairro'] ?? '',
    $_POST['cidade'] ?? '',
    $_POST['cep'] ?? '',
    $_POST['estado'] ?? '',
    $_POST['complemento'] ?? ''
);

// Atualiza dados do local
$local = new Local(
    $idLocal,
    $_POST['nomeLocal'] ?? '',
    $_POST['descricaoLocal'] ?? '',
    $endereco,
    $_POST['horarioAbertura'] ?? '',
    $_POST['horarioFechamento'] ?? '',
    $_POST['tipo'] ?? ''
);

// Atualiza no banco
$atualizado = $repositorio->atualizarLocal($local);

if ($atualizado) {
    header("Location: glocais.php");
    exit;
} else {
    echo "Erro ao atualizar o local.";
}