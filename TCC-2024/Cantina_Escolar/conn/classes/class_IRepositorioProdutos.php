<?php

include_once 'conexao.php';
include_once 'classe_produtos.php';

interface IRepositorioProduto
{
    public function excluir_produto($id_produto);
    public function listarTodosProduto();
    public function pedidos_andamento();
    public function buscarProduto($id_produto);
    public function verificaFoto($foto);
    public function alterar_status_pedido($id_pedido, $status);
    public function pedidos_concluido();
    public function pesquisarProdutos($pesquisar);
    public function atualizar_produto_sem_foto($id_produto, $nome_produto, $descricao_produto, $descricao_curta, $preco, $quantidade_estoque);
    public function produtoIndividual($id_produto);
    public function obterQuatroProdutosAleatorios();
    public function atualizar_quantidade_estoque($id_produto, $quantidade_estoque);
    public function atualizar_produto($id_produto, $nome_produto, $descricao_produto, $descricao_curta, $preco, $quantidade_estoque, $foto);
}

class ReposiorioProdutoMYSQL implements IRepositorioProduto
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "cantina_escolar");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }

    public function atualizar_produto($id_produto, $nome_produto, $descricao_produto, $descricao_curta, $preco, $quantidade_estoque, $foto)
    {
        $sql = "UPDATE tbl_produto SET nome_produto='$nome_produto', descricao_produto = '$descricao_produto', descricao_curta ='$descricao_curta', preco='$preco', quantidade_estoque='$quantidade_estoque', img='$foto' WHERE id_produto='$id_produto'";
        $altera = $this->conexao->executarQuery($sql);
    }

    public function atualizar_produto_sem_foto($id_produto, $nome_produto, $descricao_produto, $descricao_curta, $preco, $quantidade_estoque)
    {
        $sql = "UPDATE tbl_produto SET nome_produto='$nome_produto', descricao_produto = '$descricao_produto', descricao_curta ='$descricao_curta', preco='$preco', quantidade_estoque='$quantidade_estoque' WHERE id_produto='$id_produto'";
        $altera = $this->conexao->executarQuery($sql);
    }

    public function excluir_produto($id_produto)
    {
        $sql = "DELETE FROM carrinho WHERE id_produto = '$id_produto'";
        $this->conexao->executarQuery($sql);
        $sql = "DELETE FROM tbl_produto WHERE id_produto = '$id_produto'";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function listarTodosProduto()
    {
        $sql = "SELECT * FROM tbl_produto ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function alterar_status_pedido($id_pedido, $status)
    {
        $sql = "UPDATE tbl_pedidos SET status='$status' WHERE id_pedido='$id_pedido'";
        $altera = $this->conexao->executarQuery($sql);
    }

    public function atualizar_quantidade_estoque($id_produto, $quantidade_estoque)
    {
        $sql = "UPDATE tbl_produto SET quantidade_estoque='$quantidade_estoque' WHERE id_produto='$id_produto'";
        $altera = $this->conexao->executarQuery($sql);
    }

    public function pedidos_andamento()
    {
        $sql = "SELECT * FROM tbl_pedidos WHERE status = 'Andamento' ORDER BY data_pedido DESC";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function pedidos_concluido()
    {
        $sql = "SELECT * FROM tbl_pedidos WHERE status = 'Concluído' ORDER BY data_pedido DESC";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function buscarProduto($id_produto)
    {
        $sql = "SELECT * FROM tbl_produto WHERE id_produto = '$id_produto'";

        $registro = $this->conexao->executarQuery($sql);

        return $registro;
    }

    public function pesquisarProdutos($pesquisar)
    {
        $sql = "SELECT * FROM tbl_produto WHERE nome_produto LIKE '%$pesquisar%' LIMIT 5";
        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function produtoIndividual($id_produto)
    {
        $sql = "SELECT id_produto, nome_produto, descricao_produto, preco, img FROM tbl_produto WHERE id_produto = '$id_produto'";

        $resultado = $this->conexao->executarQuery($sql);

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    public function obterQuatroProdutosAleatorios()
    {
        $sql = "SELECT id_produto, nome_produto, descricao_curta, preco, img FROM tbl_produto ORDER BY RAND() LIMIT 4";

        $resultado = $this->conexao->executarQuery($sql);
        $produtos = [];

        if ($resultado->num_rows > 0) {
            while ($produto = $resultado->fetch_assoc()) {
                $produtos[] = $produto;
            }
        }
        return $produtos;
    }

    public function adicionar_produto($nome_produto, $descricao_produto, $descricao_curta, $preco, $quantidade_estoque, $foto)
    {
        $sql = "INSERT INTO tbl_produto  (id_produto, nome_produto, descricao_produto, descricao_curta , preco, quantidade_estoque, img)
         VALUES ('','$nome_produto', '$descricao_produto', '$descricao_curta', '$preco', '$quantidade_estoque', '$foto')";

        $this->conexao->executarQuery($sql);
    }

    public function verificaFoto($foto)
    {
        $fotoRecebida = explode(".", $foto['name']);
        $tamanhoArquivo = 2097152;
        $pastaFotoDestino = "../img/";
        if ($foto['error'] == 0) {
            $extensao = $fotoRecebida['1'];
            if (in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))) {
                if ($foto['size'] > $tamanhoArquivo) {
                    $mensagem = "Arquivo Enviado é muito Grande";
                    $_SESSION['mensagem'] = $mensagem;
                } else {
                    $novoNome = md5(time()) . "." . $extensao;
                    echo $_FILES['foto']['tmp_name'];
                    echo "<br>";
                    echo $foto['tmp_name'];
                    $enviou = move_uploaded_file($_FILES['foto']['tmp_name'], $pastaFotoDestino . $novoNome);
                    if ($enviou) {
                        return ($novoNome);
                    } else {
                        return false;
                    }
                }
            } else {
                $mensagem = "Somente arquivos do tipo 'jpg', 'jpeg', 'gif', 'png' são permitidos!!!";
                $_SESSION['mensagem'] = $mensagem;
            }
        } else {
            $mensagem = "Um arquivo deve ser enviado!!!!";
            $_SESSION['mensagem'] = $mensagem;
        }
    }
}

$respositorioProduto = new ReposiorioProdutoMYSQL();
?>

