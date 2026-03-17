<?php
include_once '../../classes/class_Ong.php';
include_once '../../classes/class_IRepositorioOng.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação e coleta dos dados do formulário
    $nomeOng = $_POST['nome_ong'];
    $fundacaoOng = $_POST['fundacao_ong'];
    $historiaOng = $_POST['historia_ong'];


    // Foto da ONG
    $uploadDir = '../../../imgOngs/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto_ong']['name']));
    $caminhoCompleto = $uploadDir . $nomeArquivo;

    if (isset($_FILES['foto_ong']) && $_FILES['foto_ong']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['foto_ong']['tmp_name'], $caminhoCompleto)) {
            $fotoOng = 'imgOngs/' . $nomeArquivo; // Caminho relativo salvo no banco
        } else {
            die("Erro ao fazer upload da foto.");
        }
    } else {
        die("Arquivo de foto não enviado ou com erro.");
    }

    // $fotoOng = null; // inicializa

    // if (isset($_FILES['foto_ong']) && $_FILES['foto_ong']['error'] === UPLOAD_ERR_OK) {
    //     $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    //     $fileName = $_FILES['foto_ong']['name'];
    //     $fileTmpPath = $_FILES['foto_ong']['tmp_name'];

    //     // Pega a extensão em minúsculo
    //     $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    //     // Verifica se a extensão é permitida
    //     if (!in_array($fileExtension, $allowedExtensions)) {
    //         echo "Tipo de arquivo não permitido. Use JPG, PNG ou GIF.";
    //         exit;
    //     }

    //     // Diretório físico onde o PHP vai salvar o arquivo
    //     $diretorioUploadsFisico = __DIR__ . '/../../imgOngs/'; // caminho absoluto
    //     $diretorioUploadsParaBanco = 'imgOngs/'; // caminho salvo no banco

    //     // Criar diretório se não existir
    //     if (!is_dir($diretorioUploadsFisico)) {
    //         mkdir($diretorioUploadsFisico, 0755, true);
    //     }

    //     // Gerar nome único para a foto, limpar caracteres inválidos
    //     $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($fileName));
    //     $caminhoArquivoFisico = $diretorioUploadsFisico . $nomeArquivo;

    //     // Mover o arquivo
    //     if (move_uploaded_file($fileTmpPath, $caminhoArquivoFisico)) {
    //         // Caminho que será salvo no banco, relativo à raiz do projeto
    //         $fotoOng = $diretorioUploadsParaBanco . $nomeArquivo;
    //     } else {
    //         echo "Erro ao fazer upload da foto!";
    //         exit;
    //     }
    // }

    // Criar a instância da ONG
    $ong = new Ong(null, $nomeOng, $fundacaoOng, $historiaOng, $fotoOng);

    // Telefones
    $telefones = [];
    foreach ($_POST['telefone'] as $index => $telefone) {
        $telefones[] = ['telefone' => $telefone, 'tipo' => $_POST['tipo_telefone'][$index]];
    }
    $ong->addTelefone($telefones);

    // Endereços
    $enderecos = [];
    foreach ($_POST['rua'] as $index => $rua) {
        $enderecos[] = [
            'rua' => $rua,
            'numero' => $_POST['numero'][$index],
            'complemento' => $_POST['complemento'][$index],
            'cidade' => $_POST['cidade'][$index],
            'estado' => $_POST['estado'][$index],
            'cep' => $_POST['cep'][$index]
        ];
    }
    $ong->addEndereco($enderecos);

    // Inserir ONG no banco de dados
    $repositorioOng = new RepositorioOngMYSQL();
    if ($repositorioOng->inserirOng($ong)) {
        header('Location: gong.php');
        exit;
        // echo "ONG cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar ONG!";
    }
}
