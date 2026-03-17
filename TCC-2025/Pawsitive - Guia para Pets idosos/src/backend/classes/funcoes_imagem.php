<?php
function salvarImagemUsuario($arquivo, $user_id, &$erro = null)
{
    if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
        $erro = "Nenhum arquivo foi enviado ou houve um erro no upload.";
        return false;
    }

    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($arquivo['type'], $tipos_permitidos)) {
        $erro = "Tipo de imagem não permitido.";
        return false;
    }

    // Caminho relativo para o navegador (HTML)
    $caminho_relativo = '/imgUsuarios/user_' . $user_id . '.png';

    // Caminho absoluto no servidor para salvar a imagem
    $caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . $caminho_relativo;

    // Verifica se a pasta existe e pode ser escrita
    $pasta = dirname($caminho_absoluto);
    if (!is_dir($pasta)) {
        if (!mkdir($pasta, 0755, true)) {
            $erro = "Erro ao criar a pasta de upload: $pasta";
            return false;
        }
    }

    if (!move_uploaded_file($arquivo['tmp_name'], $caminho_absoluto)) {
        $erro = "Falha ao mover a imagem.";
        return false;
    }

    return $caminho_relativo; // Caminho usado para armazenar no banco de dados
}