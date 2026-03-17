<?php
    include_once '../classes/class_repositorioCurtidas.php';
    include_once '../classes/class_repositorioObras.php';

    // Obtém o ID do usuário logado
    session_start();
    $usuario_id = $_SESSION['id'];

    if(isset($usuario_id) && !empty($usuario_id)){

        // Obtém o ID da obra do formulário
        $obra_id = $_POST['obra_id'];

        // Verifica se a curtida já existe
        $verificouCurtida = $repositorioCurtida->verificarCurtidas($usuario_id, $obra_id);
        
        if ($verificouCurtida->num_rows > 0) {
            // A curtida já existe 
            $registroCurtida = $verificouCurtida->fetch_object();
            $id_curtida = $registroCurtida->id;
       
            $repositorioCurtida->excluirCurtidas($id_curtida);
            $repositorioObra->atualizaExcluirCurtidas($obra_id);

            header('Location:../../frontend/paginas/exposições.php');
    
        } else {
            // Adiciona a nova curtida
            $novaCurtida = new Curtida('', $usuario_id, $obra_id);

            $incluiuCurtida = $repositorioCurtida->incluirCurtida($novaCurtida);
            $repositorioObra->atualizaAdicionarCurtidas($obra_id);
            
            // $numCurtidas = $repositorioCurtida->contarCurtidas($obra_id);
            // $repositorioObra->totalCurtidas($numCurtidas, $obra_id);

            if($incluiuCurtida) {
                echo "Obra curtida com sucesso!";
            
            } else {
                echo "Erro ao curtir a obra";
            }

           
            header('Location:../../frontend/paginas/exposições.php');
            
        }
        
    }
    else
    {
        header('Location:../../frontend/paginas/mostre_sua_arte-login.php');
    }

   
?>
