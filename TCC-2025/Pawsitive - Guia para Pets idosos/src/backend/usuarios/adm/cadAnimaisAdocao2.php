<?php
include_once '../../classes/class_AnimaisAdocao.php'; 
include_once '../../classes/class_IRepositorioAnimaisAdocao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome_animal'];
    $caracteristicas = $_POST['caracteristicas_animal'];
    $cidade = $_POST['cidade_animal'];
    $descricao = $_POST['descricao_animal'];
    $genero = $_POST['genero_animal'];
    $especie = $_POST['especie_animal'];
    $idade = intval($_POST['idade_animal']);
    $condicao = $_POST['condicao_saude'];
    $status = $_POST['status_animal'];

    // Upload da foto
    $uploadDir = '../../../imgAnimais/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto_animal']['name']));
    $caminhoCompleto = $uploadDir . $nomeArquivo;

    if (isset($_FILES['foto_animal']) && $_FILES['foto_animal']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['foto_animal']['tmp_name'], $caminhoCompleto)) {
            $foto = '../../../imgAnimais/' . $nomeArquivo; // Caminho relativo salvo no banco
        } else {
            die("Erro ao fazer upload da foto.");
        }
    } else {
        die("Arquivo de foto não enviado ou com erro.");
    }

    // Criar objeto Animal
    $animal = new Animal(
        null,          // id_animal é gerado automaticamente pelo banco
        $nome,
        $caracteristicas,
        $cidade,
        $descricao,
        $genero,
        $especie,
        $idade,
        $condicao,
        $foto,
        $status
    );

    // Instanciar repositório e cadastrar
    $repositorio = new RepositorioAnimaisAdocaoMYSQL();
    $sucesso = $repositorio->cadastrarAA($animal);

    // Redirecionar após o cadastro
    if ($sucesso) {
        header('Location: ganimais.php'); // redireciona para página de listagem
        exit();
    } else {
        echo "Erro ao cadastrar o animal.";
    }

} else {
    echo "Método inválido.";
}
?>
