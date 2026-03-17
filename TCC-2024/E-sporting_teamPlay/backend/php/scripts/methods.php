<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Métodos</title>
</head>
<body>
<?php
function imagem($arquivo)
{
    
   
    $explode = explode(".",$arquivo['name']);
    print_r($explode);
    $tamanhoPermitido = 2097152;
    $diretorio = "../../../../frontend/public/imagens/tarefas/";
    if ($arquivo['error'] == 0) {
        $extensao = $explode['1'];
        if(in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))){
            if ($arquivo['size'] > $tamanhoPermitido){
                $msg = "Arquivo Enviado muito Grande";
            } else {
                $novo_nome = md5(time()).".".$extensao;
                echo "Nome Novo: ".$novo_nome;
                $enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'],$diretorio.$novo_nome);
                if($enviou){
                    $msg = "<strong>Sucesso!</strong> Arquivo enviado corretamente.";
                    return($novo_nome);
                }else{
                    $msg = "<strong>Erro!</strong> Falha ao enviar o arquivo.";
                }
            }
        } else {
            $msg = "<strong>Erro!</strong> Somente arquivos tipo imagem 'jpg', 'jpeg', 'gif', 'png' são permitidos.";
        }
    } else {
        $msg = "<strong>Atenção!</strong> Você deve enviar um arquivo.";
    }
}

?>
</body>
</html>