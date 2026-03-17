<?php
function up_imagem($file)
{
   
    $explode = explode(".",$file['name']);
    // print_r($explode);
    $maxSize = 2097152;
    $dir = "../../../public/assets/profile_pics/";
    if ($file['error'] == 0) {
        $ext = $explode['1'];
        if(in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))){
            if ($file['size'] > $maxSize){
                $msg = "Arquivo Enviado muito Grande";
            } else {
                $new_name = md5(time()).".".$ext;
                // echo "Nome Novo: ".$new_name;
                $sent = move_uploaded_file($_FILES['arquivo']['tmp_name'],$dir.$new_name);
                if($sent){
                    $msg = "<strong>Sucesso!</strong> Arquivo enviado corretamente.";
                    return($new_name);
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