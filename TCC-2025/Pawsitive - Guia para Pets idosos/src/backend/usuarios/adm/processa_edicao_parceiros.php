<?php
include_once '../../classes/class_IRepositorioOng.php';
include_once '../../classes/class_Ong.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['idOng']);

    $repositorio = new RepositorioOngMYSQL();
    $ong = $repositorio->buscarPorId($id); // busca o objeto existente

    if (!$ong) {
        echo "ONG não encontrada.";
        exit;
    }

    // Atualiza campos básicos
    $ong->setNomeOng($_POST['nomeOng'] ?? '');
    $ong->setFundacaoOng($_POST['fundacaoOng'] ?? '');
    $ong->setHistoriaOng($_POST['historiaOng'] ?? '');

    // Telefones
    $telefones = [];
    if (!empty($_POST['telefones'])) {
        foreach ($_POST['telefones'] as $i => $tel) {
            $telefones[] = [
                'telefone' => $tel,
                'tipo' => $_POST['tipos'][$i] ?? ''
            ];
        }
    }
    $ong->setTelefones($telefones);

    // Endereços
    $enderecos = [];
    if (!empty($_POST['enderecos']['rua'])) {
        foreach ($_POST['enderecos']['rua'] as $i => $rua) {
            $enderecos[] = [
                'rua' => $rua,
                'numero' => $_POST['enderecos']['numero'][$i] ?? '',
                'complemento' => $_POST['enderecos']['complemento'][$i] ?? '',
                'bairro' => $_POST['enderecos']['bairro'][$i] ?? '',
                'cidade' => $_POST['enderecos']['cidade'][$i] ?? '',
                'estado' => $_POST['enderecos']['estado'][$i] ?? '',
                'cep' => $_POST['enderecos']['cep'][$i] ?? ''
            ];
        }
    }
    $ong->setEnderecos($enderecos);

    // Upload da foto
    // if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    //     $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    //     $fotoPath = "imgOngs/" . "." . $ext;
    //     if (!is_dir("imgOngs/")) {
    //         mkdir("imgOngs/", 0777, true);
    //     }
    //     move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);
    //     $ong->setFotoOng($fotoPath);
    // }

    // Upload da foto da ONG
    // $uploadDir = 'imgOngs/'; // Caminho relativo para armazenar as imagens

    // // Cria o diretório se não existir
    // if (!is_dir($uploadDir)) {
    //     mkdir($uploadDir, 0755, true);
    // }

    // // Verifica se o arquivo foi enviado corretamente
    // if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    //     // Limpa e gera nome único para o arquivo
    //     $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto']['name']));
    //     $caminhoCompleto = $uploadDir . $nomeArquivo;

    //     // Move o arquivo para o diretório
    //     if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
    //         // Caminho salvo no banco de dados (relativo ao projeto)
    //         $fotoPath = $uploadDir . $nomeArquivo;

    //         // Define no objeto ONG (ajuste para seu código)
    //         $ong->setFotoOng($fotoPath);
    //     } else {
    //         die("Erro ao mover a foto para o diretório de uploads.");
    //     }
    // } else {
    //     die("Arquivo da foto não enviado ou com erro.");
    // }

    $uploadDir = '../../../imgOngs/'; // caminho relativo à pasta do script atual

    // Garantir que o diretório existe
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($_FILES['foto']['name']));
        $caminhoCompleto = $uploadDir . $nomeArquivo;
        $caminhoCompleto1 = "imgOngs/" . $nomeArquivo;
    
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
            // Atualiza o caminho da foto no objeto ONG
            $ong->setFotoOng($caminhoCompleto1);
        } else {
            echo "Falha ao mover o arquivo.";
        }
    } else {
        echo "Arquivo não enviado ou erro no envio.";
    }

    // Atualiza no banco
    $atualizado = $repositorio->atualizarOng($ong);

    if ($atualizado) {
        header("Location: gong.php?msg=Ong atualizada com sucesso");
        exit;
    } else {
        echo "Erro ao atualizar ONG.";
    }
} else {
    echo "Método inválido.";
}
