<?php
include_once 'class_Conexao.php';
include_once 'class_InstaMar.php';

interface IRepositorioInstamar
{
    public function excluirpostagens($id_usuario, $id_postagem);
    public function adicionarDenuncia($denuncia);
    public function listarDenuncias();
    public function listarDenunciasPorUsuario($id_usuario);
    public function verificarDenunciaExistente($id_usuario, $id_postagem);
    public function removerDenuncia($id_usuario, $id_postagem);
    public function contarDenuncias($id_postagem);
    public function removerPostagem($id_postagem, $id_usuario);
    public function removerComentarios($id_usuario,$id_coment);
    public function removerPostagemADM($id_postagem);
    public function PegardadosUsuario($id);
    public function buscarPostagemDenunciadaPorId($id_post);
    public function registrarNotificacaoExclusao($id_post, $mensagem);
    public function buscarNotificacoesUsuario($id_usuario);
    public function marcarNotificacaoComoLida($id_notificacao, $id_usuario);
    public function adicionarDenunciaComentario($id_usuario, $id_comentario);
    public function verificarDenunciaComentarioExistente($id_usuario, $id_comentario);
    public function buscarComentarioPorId($id_comentario);
    public function listarDenunciasComentarios();
    public function listarDenunciaComentario($id);
    public function removerDenunciaComentario($id_usuario, $id_comentario);
    public function contarDenunciasComentario($id_comentario);
    public function removerComentarioADM($id_comentario);
}

class ReposiorioInstamarMYSQL implements IRepositorioInstamar
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "vidamarinha");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }

    public function getConexao()
    {
        return $this->conexao->getConnection();
    }

    public function excluirpostagens($id_usuario, $id_postagem)
    {
        $conn = $this->conexao->getConnection();

        // Primeiro, verificar se o usuário é o dono da postagem
        $sql_check = "SELECT id FROM postagens WHERE id = ? AND id_usuario = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id_postagem, $id_usuario);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            $stmt_check->close();
            return false; // Usuário não é dono da postagem ou postagem não existe
        }
        $stmt_check->close();

        // Iniciar transação para garantir consistência
        $conn->begin_transaction();

        try {
            // 1. Primeiro excluir todas as denúncias relacionadas à postagem
            $sql_denuncias = "DELETE FROM denuncias WHERE id_post = ?";
            $stmt_denuncias = $conn->prepare($sql_denuncias);
            $stmt_denuncias->bind_param("i", $id_postagem);
            $stmt_denuncias->execute();
            $stmt_denuncias->close();

            // 2. Excluir comentários relacionados à postagem (se houver tabela de comentários)
            $sql_comentarios = "DELETE FROM comentarios WHERE id_postagem = ?";
            $stmt_comentarios = $conn->prepare($sql_comentarios);
            $stmt_comentarios->bind_param("i", $id_postagem);
            $stmt_comentarios->execute();
            $stmt_comentarios->close();

            // 3. Excluir curtidas relacionadas à postagem (se houver tabela de curtidas)
            $sql_curtidas = "DELETE FROM curtidas WHERE id_postagem = ?";
            $stmt_curtidas = $conn->prepare($sql_curtidas);
            $stmt_curtidas->bind_param("i", $id_postagem);
            $stmt_curtidas->execute();
            $stmt_curtidas->close();

            // 4. Finalmente excluir a postagem
            $sql_postagem = "DELETE FROM postagens WHERE id = ?";
            $stmt_postagem = $conn->prepare($sql_postagem);
            $stmt_postagem->bind_param("i", $id_postagem);
            $stmt_postagem->execute();

            if ($stmt_postagem->affected_rows > 0) {
                $conn->commit();
                $stmt_postagem->close();
                return true;
            } else {
                $conn->rollback();
                $stmt_postagem->close();
                return false;
            }
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public function removerComentarios($id,$id_usuario)
    {
          // Deleta o usuário normalmente
    $sql = "DELETE FROM comentarios WHERE id = $id AND id_usuario = $id_usuario";
    $delete = $this->conexao->executarQuery($sql);

    return $delete;
    }


    public function adicionarDenuncia($denuncia)
    {
        $conn = $this->conexao->getConnection();
        $id_usuario = $denuncia->getId_usuario();
        $id_post = $denuncia->getId_post();

        // Verifica se já existe denúncia
        $sql_check = "SELECT id FROM denuncias WHERE id_usuario = ? AND id_post = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id_usuario, $id_post);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return false; // Denúncia já existe
        }
        $stmt_check->close();

        // Insere a denúncia
        $sql = "INSERT INTO denuncias (id_usuario, id_post) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ii", $id_usuario, $id_post);
        $sucesso = $stmt->execute();
        $stmt->close();

        return $sucesso;
    }

    public function listarDenuncias()
    {
        $sql = "SELECT d.id, d.id_usuario, u.nome AS usuario, d.id_post, p.legenda AS post, d.data_denuncia
                FROM denuncias d
                INNER JOIN usuarios u ON d.id_usuario = u.id
                INNER JOIN postagens p ON d.id_post = p.id
                ORDER BY d.data_denuncia DESC";
        $result = $this->conexao->executarQuery($sql);
        return $result;
    }

    public function listarPorPost($id_post)
    {
        $sql = "SELECT * FROM denuncias WHERE id_post = '$id_post'";
        return $this->conexao->executarQuery($sql);
    }

    // VERIFICAR SE DENÚNCIA EXISTE


    // REMOVER DENÚNCIA
    public function removerDenuncia($id_usuario, $id_postagem)
    {
        $conn = $this->conexao->getConnection();

        $sql = "DELETE FROM denuncias 
                WHERE id_usuario = ? AND id_post = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_postagem);
        $sucesso = $stmt->execute();
        $stmt->close();

        return $sucesso;
    }

    public function verificarDenunciaExistente($id_usuario, $id_postagem)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT id FROM denuncias WHERE id_usuario = ? AND id_post = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ii", $id_usuario, $id_postagem);
        $stmt->execute();
        $stmt->store_result();

        $existe = $stmt->num_rows > 0;

        $stmt->close();
        return $existe;
    }

    // CONTAR DENÚNCIAS DE UMA POSTAGEM - CORRIGIDO
    public function contarDenuncias($id_postagem)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT COUNT(*) as total FROM denuncias WHERE id_post = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return 0;
        }

        $stmt->bind_param("i", $id_postagem);
        $stmt->execute();

        // Maneira alternativa mais segura
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['total'] ?? 0;

        $stmt->close();
        return (int)$total;
    }

    public function contarTodasDenuncias()
    {
        $sql = "SELECT COUNT(*) as total FROM denuncias";

        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
            return (int)$linha['total'];
        }

        return 0; // Caso não haja denúncias ou erro
    }
    public function contarTodasDenunciasComent()
    {
        $sql = "SELECT COUNT(*) as total FROM denuncias_comentarios";

        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
            return (int)$linha['total'];
        }

        return 0; // Caso não haja denúncias ou erro
    }

    // MÉTODO ADICIONAL: Obter denúncias por usuário
    public function listarDenunciasPorUsuario($id_usuario)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT d.id, d.id_post, p.legenda, d.data_denuncia 
                FROM denuncias d 
                INNER JOIN postagens p ON d.id_post = p.id 
                WHERE d.id_usuario = ? 
                ORDER BY d.data_denuncia DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $denuncias = [];

        while ($row = $result->fetch_assoc()) {
            $denuncias[] = $row;
        }

        $stmt->close();
        return $denuncias;
    }

    public function removerPostagem($id_postagem, $id_usuario)
    {
        $id_postagem = intval($id_postagem);
        $id_usuario = intval($id_usuario);

        // Busca a imagem da postagem
        $sql = "SELECT img FROM postagens WHERE id = $id_postagem AND id_usuario = $id_usuario";
        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $post = mysqli_fetch_assoc($resultado);
            $imagem = $post['img'];

            // Apaga do banco
            $sqlDelete = "DELETE FROM postagens WHERE id = $id_postagem AND id_usuario = $id_usuario";
            $apagou = $this->conexao->executarQuery($sqlDelete);

            if ($apagou) {
                // Remove imagem do servidor se existir
                if (!empty($imagem)) {
                    $caminho = "../../../../frontend/public/img_instamar/" . $imagem;
                    if (file_exists($caminho)) {
                        unlink($caminho);
                    }
                }
                return true;
            }
        }
        return false;
    }
    public function removerPostagemADM($id_postagem)
    {
        $id_postagem = intval($id_postagem);

        // Busca a imagem da postagem
        $sql = "SELECT img FROM postagens WHERE id = $id_postagem";
        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $post = mysqli_fetch_assoc($resultado);
            $imagem = $post['img'];

            // Apaga do banco
            $sqlDelete = "DELETE FROM postagens WHERE id = $id_postagem";
            $apagou = $this->conexao->executarQuery($sqlDelete);

            if ($apagou) {
                // Remove imagem do servidor se existir
                if (!empty($imagem)) {
                    $caminho = "../../../../frontend/public/img_instamar/" . $imagem;
                    if (file_exists($caminho)) {
                        unlink($caminho);
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function PegardadosUsuario($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = '$id'";

        $registro = $this->conexao->executarQuery($sql);
        // Verifica se há resultados e retorna como array
        if ($registro && $registro->num_rows > 0) {
            return $registro->fetch_assoc(); // Retorna uma linha como array associativo
        }
        return false;
    }

    public function buscarPostagemDenunciadaPorId($id_post)
    {
        $conn = $this->conexao->getConnection(); // usa o mysqli
        $id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

        $sql = "SELECT p.id, p.legenda, p.img AS imagem_post,
                   p.data_postagem,
                   u.id AS id_usuario_post, 
                   u.nome AS autor, 
                   u.foto AS foto_usuario,
                   COUNT(c.id) AS total_curtidas,
                   EXISTS(
                       SELECT 1 
                       FROM curtidas 
                       WHERE curtidas.id_postagem = p.id 
                       AND curtidas.id_usuario = ?
                   ) AS usuario_curtiu
            FROM postagens p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            LEFT JOIN curtidas c ON p.id = c.id_postagem
            WHERE p.id = ?
            GROUP BY p.id
            LIMIT 1";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Erro no prepare: " . $conn->error);
        }

        $stmt->bind_param("ii", $id_usuario, $id_post);
        $stmt->execute();

        return $stmt->get_result();
    }
    public function registrarNotificacaoExclusao($id_post, $mensagem)
    {
        $conn = $this->getConexao();

        // 1. Buscar o dono do post
        $sqlUsuario = "SELECT id_usuario FROM postagens WHERE id = ?";
        $stmt = $conn->prepare($sqlUsuario);

        if (!$stmt) {
            return ["status" => "erro", "mensagem" => "Erro no prepare (buscar usuário)."];
        }

        $stmt->bind_param("i", $id_post);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado) {
            $stmt->close();
            return ["status" => "erro", "mensagem" => "Erro ao buscar usuário."];
        }

        if ($row = $resultado->fetch_assoc()) {
            $id_usuario = intval($row['id_usuario']);
            $stmt->close();

            // 2. Verifica se a notificação já existe
            $sqlCheck = "SELECT id_notificacao FROM notificacoes WHERE id_usuario = ? AND id_post = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("ii", $id_usuario, $id_post);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $stmtCheck->close();
                return ["status" => "info", "mensagem" => "Notificação já enviada para este post."];
            }
            $stmtCheck->close();

            // 3. Inserir notificação
            $sqlInsert = "INSERT INTO notificacoes (id_usuario, id_post, mensagem) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);

            if (!$stmtInsert) {
                return ["status" => "erro", "mensagem" => "Erro ao preparar inserção da notificação."];
            }

            $stmtInsert->bind_param("iis", $id_usuario, $id_post, $mensagem);
            $sucesso = $stmtInsert->execute();
            $stmtInsert->close();

            if ($sucesso) {
                return ["status" => "sucesso", "mensagem" => "Notificação enviada para o usuário."];
            } else {
                return ["status" => "erro", "mensagem" => "Erro ao registrar notificação."];
            }
        } else {
            $stmt->close();
            return ["status" => "erro", "mensagem" => "Post não encontrado."];
        }
    }

    public function buscarNotificacoesUsuario($id_usuario)
    {
        $conn = $this->getConexao();

        // A consulta busca todas as colunas da tabela 'notificacoes' para o usuário logado
        $sql = "SELECT * FROM notificacoes WHERE id_usuario = ? ORDER BY data_envio DESC";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            // Em caso de erro na preparação da consulta, retorne um array vazio
            return [];
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $notificacoes = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $notificacoes[] = $row;
            }
        }

        $stmt->close();
        return $notificacoes;
    }
    public function marcarNotificacaoComoLida($id_notificacao, $id_usuario)
    {
        $conn = $this->getConexao();

        // A query atualiza a coluna 'lida' para 1, mas só se a notificação
        // pertencer ao usuário logado, garantindo segurança.
        $sql = "UPDATE notificacoes SET lida = 1 WHERE id_notificacao = ? AND id_usuario = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $id_notificacao, $id_usuario);
        $sucesso = $stmt->execute();
        $stmt->close();

        return $sucesso;
    }

      public function adicionarDenunciaComentario($id_usuario, $id_comentario)
    {
        $conn = $this->conexao->getConnection();

        // Verifica se já existe denúncia
        if ($this->verificarDenunciaComentarioExistente($id_usuario, $id_comentario)) {
            return false; // Denúncia já existe
        }

        // Insere a denúncia
        $sql = "INSERT INTO denuncias_comentarios (id_usuario, id_comentario, data_denuncia) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ii", $id_usuario, $id_comentario);
        $sucesso = $stmt->execute();
        $stmt->close();

        return $sucesso;
    }

    /**
     * Verifica se o usuário já denunciou o comentário
     */
    public function verificarDenunciaComentarioExistente($id_usuario, $id_comentario)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT id FROM denuncias_comentarios WHERE id_usuario = ? AND id_comentario = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ii", $id_usuario, $id_comentario);
        $stmt->execute();
        $stmt->store_result();

        $existe = $stmt->num_rows > 0;

        $stmt->close();
        return $existe;
    }

    /**
     * Busca um comentário por ID
     */
    public function buscarComentarioPorId($id_comentario)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT c.*, u.nome 
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id
                WHERE c.id = ?";
        
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("i", $id_comentario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comentario = $result->fetch_assoc();
        $stmt->close();

        return $comentario;
    }

    /**
     * Lista todas as denúncias de comentários
     */
    public function listarDenunciaComentario($id)
    {
        $conn = $this->conexao->getConnection();
        
        $sql = "SELECT dc.id, dc.id_usuario, u.nome AS usuario, 
                       dc.id_comentario, c.texto AS comentario, 
                       dc.data_denuncia, cu.nome AS autor_comentario
                FROM denuncias_comentarios dc
                INNER JOIN usuarios u ON dc.id_usuario = u.id
                INNER JOIN comentarios c ON dc.id_comentario = c.id
                INNER JOIN usuarios cu ON c.id_usuario = cu.id
                where dc.id_comentario = $id
                ORDER BY dc.data_denuncia DESC";
        
        $result = $this->conexao->executarQuery($sql);
        return $result;
    }
    public function listarDenunciasComentarios()
    {
        $conn = $this->conexao->getConnection();
        
        $sql = "SELECT dc.id, dc.id_usuario, u.nome AS usuario, 
                       dc.id_comentario, c.texto AS comentario, 
                       dc.data_denuncia, cu.nome AS autor_comentario
                FROM denuncias_comentarios dc
                INNER JOIN usuarios u ON dc.id_usuario = u.id
                INNER JOIN comentarios c ON dc.id_comentario = c.id
                INNER JOIN usuarios cu ON c.id_usuario = cu.id
                ORDER BY dc.data_denuncia DESC";
        
        $result = $this->conexao->executarQuery($sql);
        return $result;
    }

    /**
     * Remove uma denúncia de comentário
     */
    public function removerDenunciaComentario($id_usuario, $id_comentario)
    {
        $conn = $this->conexao->getConnection();

        $sql = "DELETE FROM denuncias_comentarios 
                WHERE id_usuario = ? AND id_comentario = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_comentario);
        $sucesso = $stmt->execute();
        $stmt->close();

        return $sucesso;
    }

    /**
     * Conta quantas denúncias um comentário tem
     */
    public function contarDenunciasComentario($id_comentario)
    {
        $conn = $this->conexao->getConnection();

        $sql = "SELECT COUNT(*) as total FROM denuncias_comentarios WHERE id_comentario = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return 0;
        }

        $stmt->bind_param("i", $id_comentario);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['total'] ?? 0;

        $stmt->close();
        return (int)$total;
    }

    /**
     * Remove um comentário como administrador
     */
    public function removerComentarioADM($id_comentario)
    {
        $conn = $this->conexao->getConnection();

        // Iniciar transação
        $conn->begin_transaction();

        try {
            // 1. Primeiro remove todas as denúncias do comentário
            $sql_denuncias = "DELETE FROM denuncias_comentarios WHERE id_comentario = ?";
            $stmt_denuncias = $conn->prepare($sql_denuncias);
            $stmt_denuncias->bind_param("i", $id_comentario);
            $stmt_denuncias->execute();
            $stmt_denuncias->close();

            // 2. Remove o comentário
            $sql_comentario = "DELETE FROM comentarios WHERE id = ?";
            $stmt_comentario = $conn->prepare($sql_comentario);
            $stmt_comentario->bind_param("i", $id_comentario);
            $sucesso = $stmt_comentario->execute();
            
            if ($sucesso && $stmt_comentario->affected_rows > 0) {
                $conn->commit();
                $stmt_comentario->close();
                return true;
            } else {
                $conn->rollback();
                $stmt_comentario->close();
                return false;
            }
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
}

$respositorioInstamar = new ReposiorioInstamarMYSQL();
