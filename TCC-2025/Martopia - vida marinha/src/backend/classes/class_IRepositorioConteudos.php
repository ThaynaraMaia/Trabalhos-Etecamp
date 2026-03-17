<?php
include_once 'class_Conexao.php';
include_once 'class_Conteudo.php';
include_once 'class_ImgConteudo.php';
include_once 'class_Videos.php';
include_once 'class_Conscientizacao.php';
include_once 'class_ImgConscientizacao.php';

interface IRepositorioConteudo
{
    public function cadastrarConteudo($conteudos);
    public function cadastrarVideos($video);
    public function cadastrarConscientizacao($conscientizacao);
    public function salvarImagemConteudo($img);
    public function salvarImagemConscientizacao($img);
    public function getConexao();
    public function ultimoIdInserido();
    // public function alterarConteudo($conteudo);
    public function listarTodosConteudos();
    public function listarConteudosportipo($tipo, $categoria);
    public function listarConscientizacaoportipo($tipo, $categoria);
    public function listarporCategoria($categoria);
    public function listarConteudosPorAutor($id_autor);
    public function listarConteudoLidoPorUsuario($id_usuario);
    public function deletarConteudo($id, $tabela);
    public function editarConteudo($id, $tabela, $dados, $novaImagem = null);
    public function marcarComoLido($id_usuario, $id_conteudo, $tipoConteudo);
    public function artigoJaLidoConst($id_usuario, $id_const);
    public function artigoJaLido($id_usuario, $id_artigo);
    public function obterRankingConteudos($limite = 10);
    public function obterEstatisticasUsuario($id_usuario);
}

class ReposiorioConteudoMYSQL implements IRepositorioConteudo
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


    public function cadastrarConteudo($conteudos)
    {
        $conn = $this->conexao->getConnection();

        $id = $conteudos->getId();
        $titulo = mysqli_real_escape_string($conn, $conteudos->getTitulo());
        $link = mysqli_real_escape_string($conn, $conteudos->getLink());
        $id_autor = $conteudos->getId_autor();
        $tipo = mysqli_real_escape_string($conn, $conteudos->getTipo());
        $categoria = mysqli_real_escape_string($conn, $conteudos->getCategoria());

        $sql = "INSERT INTO conteudos (id, titulo, link, id_autor, tipo, categoria)
            VALUES ('$id', '$titulo', '$link', '$id_autor', '$tipo', '$categoria')";

        return $this->conexao->executarQuery($sql);
    }

    public function cadastrarVideos($video)
    {

        $id = $video->getId();
        $id_autor = $video->getId_autor();
        $tipo = $video->getTipo();
        $categoria = $video->getCategoria();
        $link = $video->getLink();
        $data_publicacao = $video->getData_publicacao();

        $sql = "INSERT INTO videos (id, id_autor, tipo, categoria, link)
        VALUES ('$id', '$id_autor', '$tipo', '$categoria', '$link')";


        $salvaconteudo = $this->conexao->executarQuery($sql);

        return $salvaconteudo;
    }
    public function cadastrarConscientizacao($conscientizacao)
    {
        $conn = $this->conexao->getConnection();

        $id = $conscientizacao->getId();
        $titulo = mysqli_real_escape_string($conn, $conscientizacao->getTitulo());
        $link = mysqli_real_escape_string($conn, $conscientizacao->getLink());
        $id_autor = $conscientizacao->getId_autor();
        $tipo = mysqli_real_escape_string($conn, $conscientizacao->getTipo());
        $categoria = mysqli_real_escape_string($conn, $conscientizacao->getCategoria());
        $text = mysqli_real_escape_string($conn, $conscientizacao->getText());

        $sql = "INSERT INTO conscientizacao 
            (id, titulo, link, id_autor, tipo, categoria, texto)
            VALUES 
            ('$id', '$titulo', '$link', '$id_autor', '$tipo', '$categoria', '$text')";

        return $this->conexao->executarQuery($sql);
    }

    public function salvarImagemConteudo($img)
    {
        $conn = $this->conexao->getConnection();

        $id = $img->getId();
        $id_artigo = $img->getId_artigo();
        $legenda = $img->getLegenda();
        $imagem = $img->getArquivo(); // Array do arquivo upload

        if (isset($imagem) && $imagem['error'] == 0) {
            $pasta = "../../../../frontend/public/img_conteudos/";
            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            // Validação do arquivo
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $extensoesPermitidas)) {
                return "Tipo de arquivo não permitido";
            }

            $nomeArquivo = time() . "_" . uniqid() . "." . $extensao;
            $caminho_completo = $pasta . $nomeArquivo;

            if (move_uploaded_file($imagem['tmp_name'], $caminho_completo)) {
                // Atualiza o caminho da imagem no objeto
                $img->setCaminho_img($nomeArquivo);

                // Prepared statement para evitar erro de aspas
                $stmt = $conn->prepare(
                    "INSERT INTO imagens_artigos (id, id_artigo, caminho_img, legenda)
                 VALUES (?, ?, ?, ?)"
                );

                $stmt->bind_param("iiss", $id, $id_artigo, $nomeArquivo, $legenda);

                if ($stmt->execute()) {
                    return true;
                } else {
                    // Remove o arquivo se falhar no banco
                    unlink($caminho_completo);
                    return "Erro ao salvar imagem no banco: " . $stmt->error;
                }
            } else {
                return "Erro ao mover arquivo";
            }
        }

        return "Nenhuma imagem enviada";
    }

    public function salvarImagemConscientizacao($img1)
    {
        $conn = $this->conexao->getConnection();

        $id = $img1->getId();
        $id_conscientizacao = $img1->getId_conscientizacao();
        $legenda = $img1->getLegenda();
        $imagem = $img1->getArquivo(); // Array do arquivo upload

        if (isset($imagem) && $imagem['error'] == 0) {
            $pasta = "../../../../frontend/public/img_conscientizacao/";
            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            // Validação do arquivo
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $extensoesPermitidas)) {
                return "Tipo de arquivo não permitido";
            }

            $nomeArquivo = time() . "_" . uniqid() . "." . $extensao;
            $caminho_completo = $pasta . $nomeArquivo;

            if (move_uploaded_file($imagem['tmp_name'], $caminho_completo)) {
                // Atualiza o caminho da imagem no objeto
                $img1->setCaminho_img($nomeArquivo);

                // Prepared statement para evitar erro de aspas
                $stmt = $conn->prepare(
                    "INSERT INTO img_conscientizacao (id, id_artigo, caminho_img, legenda)
                 VALUES (?, ?, ?, ?)"
                );

                $stmt->bind_param("iiss", $id, $id_conscientizacao, $nomeArquivo, $legenda);

                if ($stmt->execute()) {
                    return true;
                } else {
                    // Remove o arquivo se falhar no banco
                    unlink($caminho_completo);
                    return "Erro ao salvar imagem no banco: " . $stmt->error;
                }
            } else {
                return "Erro ao mover arquivo";
            }
        }

        return "Nenhuma imagem enviada";
    }

    // MÉTODO PARA OBTER O ÚLTIMO ID INSERIDO
    public function ultimoIdInserido()
    {
        $sql = "SELECT LAST_INSERT_ID() as last_id";
        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && $row = mysqli_fetch_assoc($resultado)) {
            return $row['last_id'];
        }
        return 0;
    }
    public function listarTodosConteudos()
    {
        $sql = "SELECT a.id, a.titulo, a.data_publicacao, 
                       u.nome AS autor, i.caminho_img
                FROM conteudos a
                LEFT JOIN usuarios u ON a.id_autor = u.id
                LEFT JOIN imagens_artigos i ON a.id = i.id_artigo
                ORDER BY a.data_publicacao DESC";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudos[] = $row;
            }
        }
        return $conteudos;
    }

    public function listarConteudosportipo($tipo, $categoria)
    {

        $sql = "SELECT a.id, a.titulo,a.link, a.data_publicacao, 
                   u.nome AS autor, i.caminho_img
            FROM conteudos a
            LEFT JOIN usuarios u ON a.id_autor = u.id
            LEFT JOIN imagens_artigos i ON a.id = i.id_artigo
            WHERE a.tipo = '$tipo' and a.categoria = '$categoria'
            ORDER BY a.data_publicacao DESC";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudos[] = $row;
            }
        }
        return $conteudos;
    }
    public function listarConscientizacaoportipo($tipo, $categoria)
    {

        $sql = "SELECT a.id, a.titulo,a.link, a.data_publicacao, 
                   u.nome AS autor, i.caminho_img,i.legenda,a.texto
            FROM conscientizacao a
            LEFT JOIN usuarios u ON a.id_autor = u.id
            LEFT JOIN img_conscientizacao i ON a.id = i.id_artigo
            WHERE a.tipo = '$tipo' and a.categoria = '$categoria'
            ORDER BY a.data_publicacao DESC";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudos[] = $row;
            }
        }
        return $conteudos;
    }
    public function listarporCategoria($categoria)
    {

        $sql = "SELECT a.id, a.link, a.data, 
                   u.nome AS autor 
            FROM videos a
            LEFT JOIN usuarios u ON a.id_autor = u.id
            WHERE a.categoria = '$categoria'
            ORDER BY a.data DESC";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudos[] = $row;
            }
        }
        return $conteudos;
    }


    public function listarConteudosPorAutor($id_autor)
    {
        $id_autor = (int)$id_autor;

        $sql = "
        -- Conteúdos
        SELECT a.id, a.titulo, a.data_publicacao,null as texto, 
               u.nome AS autor, i.caminho_img,
               a.link, a.tipo, a.categoria, 'conteudos' AS origem
        FROM conteudos a
        LEFT JOIN usuarios u ON a.id_autor = u.id
        LEFT JOIN imagens_artigos i ON a.id = i.id_artigo
        WHERE a.id_autor = $id_autor

        UNION ALL

        -- Conscientização
        SELECT c.id, c.titulo, c.data_publicacao, c.texto,
               u.nome AS autor, i.caminho_img,
               c.link, c.tipo, c.categoria, 'conscientizacao' AS origem
        FROM conscientizacao c
        LEFT JOIN usuarios u ON c.id_autor = u.id
        LEFT JOIN img_conscientizacao i ON c.id = i.id_artigo
        WHERE c.id_autor = $id_autor

        UNION ALL

        -- Vídeos
        SELECT v.id, v.tipo AS titulo, v.data,null as texto,
               u.nome AS autor,NULL AS caminho_img,
               v.link, v.tipo, v.categoria, 'videos' AS origem
        FROM videos v
        LEFT JOIN usuarios u ON v.id_autor = u.id
        WHERE v.id_autor = $id_autor

        ORDER BY data_publicacao DESC
    ";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudos[] = $row;
            }
        }
        return $conteudos;
    }


    public function listarConteudoLidoPorUsuario($id_usuario)
    {
        $id_usuario = (int)$id_usuario;

        // A consulta SQL usa INNER JOIN para pegar apenas conteúdos que têm um registro na tabela 'leituras'
        $sql = "SELECT c.titulo, c.data_publicacao, u.nome AS autor
            FROM conteudos c
            INNER JOIN leituras l ON c.id = l.id_artigo
            LEFT JOIN usuarios u ON c.id_autor = u.id
            WHERE l.id_usuario = $id_usuario
            ORDER BY c.data_publicacao DESC";

        $resultado = $this->conexao->executarQuery($sql);

        $conteudosLidos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $conteudosLidos[] = $row;
            }
        }
        return $conteudosLidos;
    }

    public function deletarConteudo($id, $tabela)
    {
        $id = (int)$id;
        $tabela = mysqli_real_escape_string($this->conexao->getConnection(), $tabela);

        // Decide qual tabela e qual campo de imagem usar
        switch ($tabela) {
            case 'conteudos':
                $sqlSelect = "SELECT caminho_img FROM imagens_artigos WHERE id_artigo = $id";
                $sqlDeleteImg = "DELETE FROM imagens_artigos WHERE id_artigo = $id";
                $sqlDelete = "DELETE FROM conteudos WHERE id = $id";
                $pasta = "../../../../frontend/public/img_conteudos/";
                break;

            case 'conscientizacao':
                $sqlSelect = "SELECT caminho_img FROM img_conscientizacao WHERE id = $id";
                $sqlDeleteImg = "DELETE FROM img_conscientizacao WHERE id_artigo = $id";
                $sqlDelete = "DELETE FROM conscientizacao WHERE id = $id";
                $pasta = "../../../../frontend/public/img_conscientizacao/";
                break;

            case 'videos':
                $sqlSelect = null; // geralmente não tem imagem física
                $sqlDelete = "DELETE FROM videos WHERE id = $id";
                $pasta = null;
                $sqlDeleteImg = null;
                break;

            default:
                return false; // tabela inválida
        }

        // Busca a imagem antes de deletar
        if ($sqlSelect) {
            $res = $this->conexao->executarQuery($sqlSelect);
            if ($res && mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                if (!empty($row['caminho_img']) && $row['caminho_img'] !== 'educa.png') {
                    $filePath = $pasta . $row['caminho_img'];
                    if (file_exists($filePath)) {
                        unlink($filePath); // Exclui a imagem do servidor
                    }
                }
            }
        }

        // Se houver uma tabela de imagens separada, excluir primeiro
        if ($sqlDeleteImg) {
            $this->conexao->executarQuery($sqlDeleteImg);
        }

        // Exclui o registro principal
        return $this->conexao->executarQuery($sqlDelete);
    }

    public function editarConteudo($id, $tabela, $dados, $novaImagem = null)
    {
        $id = (int)$id;
        $conn = $this->conexao->getConnection();

        switch ($tabela) {
            case 'conteudos':
                // Atualiza os dados principais
                $sql = "UPDATE conteudos 
                    SET titulo = ?, link = ?, categoria = ?, tipo = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "ssssi",
                    $dados['titulo'],
                    $dados['link'],
                    $dados['categoria'],
                    $dados['tipo'],
                    $id
                );

                if (!$stmt->execute()) return false;

                // Se foi enviada uma nova imagem
                if ($novaImagem) {
                    $pasta = "../../../../frontend/public/img_conteudos/";
                    if (!is_dir($pasta)) mkdir($pasta, 0777, true);

                    // Verifica se já existe imagem associada
                    $sqlCheck = "SELECT caminho_img FROM imagens_artigos WHERE id_artigo = ?";
                    $stmtCheck = $conn->prepare($sqlCheck);
                    $stmtCheck->bind_param("i", $id);
                    $stmtCheck->execute();
                    $res = $stmtCheck->get_result();

                    if ($row = $res->fetch_assoc()) {
                        // Deleta a antiga
                        $antiga = $pasta . $row['caminho_img'];
                        if (file_exists($antiga) && !empty($row['caminho_img'])) {
                            unlink($antiga);
                        }

                        // Atualiza o caminho no banco
                        $sqlUpdate = "UPDATE imagens_artigos SET caminho_img = ? WHERE id_artigo = ?";
                        $stmtUp = $conn->prepare($sqlUpdate);
                        $stmtUp->bind_param("si", $novaImagem, $id);
                        $stmtUp->execute();
                    } else {
                        // Insere nova imagem se não existir
                        $sqlInsert = "INSERT INTO imagens_artigos (id_artigo, caminho_img) VALUES (?, ?)";
                        $stmtIns = $conn->prepare($sqlInsert);
                        $stmtIns->bind_param("is", $id, $novaImagem);
                        $stmtIns->execute();
                    }
                }
                break;

            case 'conscientizacao':
                // Atualiza os dados principais
                $sql = "UPDATE conscientizacao 
                    SET titulo = ?, texto = ?, link = ?, categoria = ?, tipo = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssssi",
                    $dados['titulo'],
                    $dados['texto'],
                    $dados['link'],
                    $dados['categoria'],
                    $dados['tipo'],
                    $id
                );

                if (!$stmt->execute()) return false;

                // Atualiza ou insere imagem
                if ($novaImagem) {
                    $pasta = "../../../../frontend/public/img_conscientizacao/";
                    if (!is_dir($pasta)) mkdir($pasta, 0777, true);

                    // Verifica se já existe imagem
                    $sqlCheck = "SELECT caminho_img FROM img_conscientizacao WHERE id_artigo = ?";
                    $stmtCheck = $conn->prepare($sqlCheck);
                    $stmtCheck->bind_param("i", $id);
                    $stmtCheck->execute();
                    $res = $stmtCheck->get_result();

                    if ($row = $res->fetch_assoc()) {
                        // Remove antiga se existir
                        $antiga = $pasta . $row['caminho_img'];
                        if (file_exists($antiga) && !empty($row['caminho_img'])) {
                            unlink($antiga);
                        }

                        // Atualiza
                        $sqlUpdate = "UPDATE img_conscientizacao SET caminho_img = ? WHERE id_artigo = ?";
                        $stmtUp = $conn->prepare($sqlUpdate);
                        $stmtUp->bind_param("si", $novaImagem, $id);
                        $stmtUp->execute();
                    } else {
                        // Insere se não existir
                        $sqlInsert = "INSERT INTO img_conscientizacao (id_artigo, caminho_img) VALUES (?, ?)";
                        $stmtIns = $conn->prepare($sqlInsert);
                        $stmtIns->bind_param("is", $id, $novaImagem);
                        $stmtIns->execute();
                    }
                }
                break;

            case 'videos':
                $sql = "UPDATE videos 
                    SET tipo = ?, link = ?, categoria = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssi",
                    $dados['tipo'],
                    $dados['link'],
                    $dados['categoria'],
                    $id
                );
                break;

            default:
                return false;
        }

        return $stmt->execute();
    }


   public function marcarComoLido($id_usuario, $id_conteudo, $tipoConteudo)
{
    $conn = $this->conexao->getConnection();

    // Validação
    if (!is_numeric($id_usuario) || !is_numeric($id_conteudo)) {
        return ['status' => 'erro', 'mensagem' => 'ID inválido.'];
    }

    $id_usuario = (int)$id_usuario;
    $id_conteudo = (int)$id_conteudo;

    // ✅ CORRIGIDO: Define os nomes corretos das tabelas
    if ($tipoConteudo === 'educacao') {
        $tabelaConteudo = 'conteudos';
        $colunaId = 'id_artigo';
    } elseif ($tipoConteudo === 'conscientizacao') {
        $tabelaConteudo = 'conscientizacao'; // ✅ SEM "s" no final
        $colunaId = 'id_conscientizacao';
    } else {
        return ['status' => 'erro', 'mensagem' => 'Tipo de conteúdo inválido.'];
    }

    $conn->autocommit(false);

    try {
        // Verifica se já foi lido
        $sqlCheck = "SELECT id FROM leituras WHERE id_usuario = ? AND $colunaId = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id_usuario, $id_conteudo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $conn->rollback();
            return ['status' => 'info', 'mensagem' => 'Você já marcou este conteúdo como lido!'];
        }

        // Busca tipo e categoria do conteúdo
        $sqlConteudo = "SELECT tipo, categoria FROM $tabelaConteudo WHERE id = ?";
        $stmtConteudo = $conn->prepare($sqlConteudo);
        $stmtConteudo->bind_param("i", $id_conteudo);
        $stmtConteudo->execute();
        $resConteudo = $stmtConteudo->get_result()->fetch_assoc();

        if (!$resConteudo) {
            $conn->rollback();
            return ['status' => 'erro', 'mensagem' => 'Conteúdo não encontrado.'];
        }

        $tipo = $resConteudo['tipo'];
        $categoria = $resConteudo['categoria'];

        // Define pontos conforme tipo
        $pontos = 0;
        if ($tipo === "Educação") {
            $pontos = 5;
        } elseif ($tipo === "Conscientização") {
            $pontos = 3;
        }

        // Insere leitura
        $sqlInsert = "INSERT INTO leituras (id_usuario, $colunaId) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("ii", $id_usuario, $id_conteudo);

        if (!$stmtInsert->execute()) {
            throw new Exception('Erro ao inserir leitura: ' . $conn->error);
        }

        // Insere pontos
        $sqlPontos = "INSERT INTO pontos_leituras (id_usuario, $colunaId, pontos, tipo, categoria, data_registro)
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmtPontos = $conn->prepare($sqlPontos);
        $stmtPontos->bind_param("iiiss", $id_usuario, $id_conteudo, $pontos, $tipo, $categoria);

        if (!$stmtPontos->execute()) {
            throw new Exception('Erro ao inserir pontos: ' . $conn->error);
        }

        $conn->commit();

        return [
            'status' => 'sucesso',
            'mensagem' => ucfirst($tipoConteudo) . ' marcado como lido com sucesso!',
            'pontos' => $pontos,
            'tipo' => $tipo,
            'categoria' => $categoria
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'erro', 'mensagem' => 'Erro ao processar: ' . $e->getMessage()];
    } finally {
        $conn->autocommit(true);
    }
}

    public function artigoJaLido($id_usuario, $id_artigo)
    {
        $conn = $this->conexao->getConnection();

        // Use prepared statements para segurança
        $sqlCheck = "SELECT id FROM leituras WHERE id_usuario = ? AND id_artigo = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id_usuario, $id_artigo);
        $stmt->execute();
        $stmt->store_result();

        // Retorna true se houver linhas (o artigo já foi lido), e false caso contrário
        return $stmt->num_rows > 0;
    }
    public function artigoJaLidoConst($id_usuario, $id_artigo)
    {
        $conn = $this->conexao->getConnection();

        // Use prepared statements para segurança
        $sqlCheck = "SELECT id FROM leituras WHERE id_usuario = ? AND id_conscientizacao = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id_usuario, $id_artigo);
        $stmt->execute();
        $stmt->store_result();

        // Retorna true se houver linhas (o artigo já foi lido), e false caso contrário
        return $stmt->num_rows > 0;
    }

    public function obterRankingConteudos($limite = 10)
    {
        $limite = (int)$limite;

        $sql = "SELECT u.nome, SUM(pl.pontos) as total_pontos
            FROM pontos_leituras pl
            INNER JOIN usuarios u ON pl.id_usuario = u.id
            WHERE pl.pontos > 0
            GROUP BY pl.id_usuario, u.nome
            ORDER BY total_pontos DESC, u.nome ASC
            LIMIT $limite";

        $resultado = $this->conexao->executarQuery($sql);

        $ranking = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $ranking[] = $row;
            }
        }
        return $ranking;
    }
    public function obterEstatisticasUsuario($id_usuario)
    {
        $id_usuario = (int)$id_usuario;

        $sql = "SELECT 
                COUNT(pl.id_artigo) as total_artigos_lidos,
                SUM(pl.pontos) as total_pontos,
                SUM(CASE WHEN pl.tipo = 'Educação' THEN 1 ELSE 0 END) as artigos_educacao,
                SUM(CASE WHEN pl.tipo = 'Conscientização' THEN 1 ELSE 0 END) as artigos_conscientizacao,
                MAX(pl.data_registro) as ultima_leitura,
                MIN(pl.data_registro) as primeira_leitura
            FROM pontos_leituras pl
            WHERE pl.id_usuario = $id_usuario AND pl.pontos > 0";

        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_assoc($resultado);
        }

        return [
            'total_artigos_lidos' => 0,
            'total_pontos' => 0,
            'artigos_educacao' => 0,
            'artigos_conscientizacao' => 0,
            'ultima_leitura' => null,
            'primeira_leitura' => null
        ];
    }
}

$respositorioConteudo = new ReposiorioConteudoMYSQL();
