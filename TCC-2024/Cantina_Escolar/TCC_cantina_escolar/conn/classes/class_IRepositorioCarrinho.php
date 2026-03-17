<?php

include_once 'conexao.php';
include_once 'class_Carrinho.php';
include_once 'classe_produtos.php';
include_once 'classe_usuario.php';

interface IRepositorioCarrinho
{
    public function verificarCarrinho($id_carrinho);
    public function adicionarCarrinho($id_produto, $id_usuario, $qtde, $preco);
    public function listarTodosCarrinho($id_usuario);
    public function mostrarPedido($id_usuario);
    public function removerCarrinho($id_carrinho);
    public function somaProduto($id_usuario);
    public function mostrar_itens_pedidos($id_pedidos);
    public function listarPedidos($id_usuario);
    public function finalizar_compra($id_usuario);
    public function totalProduto($id_usuario);
    public function aumentarCarrinho($id_produto);
    public function diminuirCarrinho($id_produto);
    public function buscarProduto($id_produto);
    public function verificarQuantidade($id_produto, $id_usuario);
    public function buscarPedido($id_usuario, $id_pedidos);
    public function aumentarProduto_individual($id_produto, $qtde);
    public function atualizarQuantidade($id_produto, $id_usuario);
    public function adicionarquantidade_personalizada($id_produto, $id_usuario, $qtde);
    public function diminuirProduto_individual($id_produto, $qtde);
}

class ReposiorioCarrinhoMYSQL implements IRepositorioCarrinho
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost", "root", "", "cantina_escolar");
        if ($this->conexao->conectar() == false) {
            echo "Erro" . mysqli_connect_error();
        }
    }

    public function listarPedidos($id_usuario)
    {
        $sql = "SELECT * FROM tbl_pedidos WHERE id_usuario = '$id_usuario' ORDER BY data_pedido DESC";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function somaProduto($id_usuario)
    {
        $sql = "SELECT SUM(preco * quantidade) AS total FROM carrinho WHERE id_usuario ='$id_usuario'";
        $result = $this->conexao->executarQuery($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['total'];
        }
        return 0;
    }

    public function totalProduto($id_usuario)
    {
        $sql = "SELECT SUM(quantidade * preco) AS total FROM carrinho WHERE id_usuario='$id_usuario'";
        $result = $this->conexao->executarQuery($sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        } else {
            return 0;
        }
    }

    public function buscarPedido($id_usuario, $id_pedidos)
    {
        $sql = "SELECT * FROM tbl_pedidos WHERE id_usuario = '$id_usuario' AND id_pedido = '$id_pedidos'";
        $listagem = $this->conexao->executarQuery($sql);
        return $listagem;
    }

    public function mostrarPedido($id_usuario)
    {

        $sql = "SELECT * FROM tbl_pedidos WHERE id_usuario = '$id_usuario' ORDER BY (status = 'Andamento') DESC, data_pedido DESC";

        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function mostrar_itens_pedidos($id_pedidos)
    {
        $sql = "SELECT * FROM tbl_itens_pedido WHERE id_pedidos = '$id_pedidos'";
        $resultado = $this->conexao->executarQuery($sql);

        return $resultado;
    }

    public function finalizar_compra($id_usuario)
    {
        $sql = "SELECT SUM(quantidade * preco) AS total FROM carrinho WHERE id_usuario = $id_usuario";
        $resultTotal = $this->conexao->executarQuery($sql);

        $rowTotal = mysqli_fetch_assoc($resultTotal);
        $total = $rowTotal['total'];

        date_default_timezone_set('America/Sao_Paulo');
        echo "Hora atual do servidor: " . date('Y-m-d ');

        $data_pedido = date('Y-m-d ');
        echo "Data do pedido: " . $data_pedido;

        $status = 'Andamento';
        $codigo = uniqid();

        $sqlPedido = "INSERT INTO tbl_pedidos (id_usuario, data_pedido, status, preco_total, codigo) 
                      VALUES ('$id_usuario', '$data_pedido', '$status', '$total', '$codigo')";

        $this->conexao->executarQuery($sqlPedido);

        $sqlUltimoIdPedido = "SELECT MAX(id_pedido) AS ultimo_id FROM tbl_pedidos WHERE id_usuario = '$id_usuario' AND data_pedido = '$data_pedido'";
        $resultUltimoIdPedido = $this->conexao->executarQuery($sqlUltimoIdPedido);

        if ($rowUltimoIdPedido = mysqli_fetch_assoc($resultUltimoIdPedido)) {
            $id_pedido = $rowUltimoIdPedido['ultimo_id'];
        } else {
            return "Erro ao obter o ID do pedido.";
        }

        $sqlItens = "SELECT * FROM carrinho WHERE id_usuario = $id_usuario";
        $resultItens = $this->conexao->executarQuery($sqlItens);

        while ($item = mysqli_fetch_assoc($resultItens)) {
            $id_produto = $item['id_produto'];
            $quantidade = $item['quantidade'];
            $preco_unitario = $item['preco'];
            $preco_total = $quantidade * $preco_unitario;

            $sqlBuscarProduto = "SELECT nome_produto, quantidade_estoque FROM tbl_produto WHERE id_produto = '$id_produto'";
            $resultProduto = $this->conexao->executarQuery($sqlBuscarProduto);

            if ($produto = mysqli_fetch_assoc($resultProduto)) {
                $nome_produto = $produto['nome_produto'];
                $quantidade_estoque = $produto['quantidade_estoque'];

                $nova_quantidade_estoque = $quantidade_estoque - $quantidade;
                $sqlAtualizarEstoque = "UPDATE tbl_produto SET quantidade_estoque = '$nova_quantidade_estoque' WHERE id_produto = '$id_produto'";
                $this->conexao->executarQuery($sqlAtualizarEstoque);

                $sqlInserirItem = "INSERT INTO tbl_itens_pedido (id_pedidos, nome_produto, id_usuario, quantidade, preco_unitario, preco_total) 
                VALUES ('$id_pedido', '$nome_produto', '$id_usuario','$quantidade', '$preco_unitario', '$preco_total')";
                $this->conexao->executarQuery($sqlInserirItem);
            }

            $sqlLimparCarrinho = "DELETE FROM carrinho WHERE id_usuario = $id_usuario";
            $this->conexao->executarQuery($sqlLimparCarrinho);
            header("Location: ../../html/pagamento/comprovante.php?id_pedido=" . $id_pedido);
        }
    }

    public function verificarCarrinho($id_carrinho)
    {
        $sql = "SELECT * FROM carrinho";
        $result = $this->conexao->executarQuery($sql);
        return $result;
    }

    public function aumentarCarrinho($id_produto)
    {
        $sql = "SELECT quantidade FROM carrinho WHERE id_produto = '$id_produto'";
        $result = $this->conexao->executarQuery($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $quantidadeAtualCarrinho = $row['quantidade'];

            $sqlEstoque = "SELECT quantidade_estoque FROM tbl_produto WHERE id_produto = '$id_produto'";
            $resultEstoque = $this->conexao->executarQuery($sqlEstoque);
            if ($rowEstoque = mysqli_fetch_assoc($resultEstoque)) {
                $estoqueDisponivel = $rowEstoque['quantidade_estoque'];

                if ($quantidadeAtualCarrinho < $estoqueDisponivel) {
                    $novaQuantidade = $quantidadeAtualCarrinho + 1;
                    $sqlUpdate = "UPDATE carrinho SET quantidade = '$novaQuantidade' WHERE id_produto = '$id_produto'";
                    $this->conexao->executarQuery($sqlUpdate);
                } else {
                    echo "Não há estoque suficiente para aumentar a quantidade do produto.";
                }
            }
        }
    }

    public function diminuirCarrinho($id_produto)
    {
        $sql = "SELECT quantidade FROM carrinho WHERE id_produto = '$id_produto'";
        $result = $this->conexao->executarQuery($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $quantidadeAtual = $row['quantidade'];
            if ($quantidadeAtual > 1) {
                $novaQuantidade = $quantidadeAtual - 1;
                $sqlUpdate = "UPDATE carrinho SET quantidade = '$novaQuantidade' WHERE id_produto = '$id_produto'";
                $this->conexao->executarQuery($sqlUpdate);
            }
        }
    }

    public function diminuirProduto_individual($id_produto, $qtde)
    {
        if ($qtde > 1) {
            $novaQuantidade = $qtde - 1;
            header("Location: ../produtos/produto_individual.php?id_produto=" . $id_produto . "&novaQuantidade=" . $novaQuantidade);
            exit();
        }
    }

    public function aumentarProduto_individual($id_produto, $qtde)
    {
        $sqlEstoque = "SELECT quantidade_estoque FROM tbl_produto WHERE id_produto = '$id_produto'";
        $resultEstoque = $this->conexao->executarQuery($sqlEstoque);

        if ($rowEstoque = mysqli_fetch_assoc($resultEstoque)) {
            $estoqueDisponivel = $rowEstoque['quantidade_estoque'];

            if ($qtde < $estoqueDisponivel) {
                $novaQuantidade = $qtde + 1;

                header("Location: ../produtos/produto_individual.php?id_produto=" . $id_produto . "&novaQuantidade=" . $novaQuantidade);
                exit();
            } else {
                echo "Não há estoque suficiente para aumentar a quantidade do produto.";
            }
        }
    }

    public function adicionarCarrinho($id_produto, $id_usuario, $qtde, $preco)
    {

        $sql = "INSERT INTO carrinho (id_produto,id_usuario,quantidade,preco) VALUES ('$id_produto','$id_usuario','$qtde','$preco') ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function listarTodosCarrinho($id_usuario)
    {
        $sql = "SELECT * FROM carrinho  WHERE id_usuario ='$id_usuario'  ";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function removerCarrinho($id_produto)
    {
        $sql = "DELETE FROM carrinho WHERE id_produto = '$id_produto'";

        $resultado = $this->conexao->executarQuery($sql);
        return $resultado;
    }

    public function buscarProduto($id_produto)
    {
        $sql = "SELECT * FROM tbl_produto WHERE id_produto = '$id_produto'";
        $registro = $this->conexao->executarQuery($sql);
        return $registro;
    }

    public function verificarQuantidade($id_produto, $id_usuario)
    {
        $sql = "SELECT quantidade FROM carrinho WHERE id_produto = '$id_produto' And id_usuario = '$id_usuario'";
        $encontrou = $this->conexao->executarQuery($sql);
        return $encontrou;
    }

    public function adicionarquantidade_personalizada($id_produto, $id_usuario, $qtde)
    {
        $sql = "SELECT quantidade FROM carrinho WHERE id_produto = '$id_produto'";
        $result = $this->conexao->executarQuery($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $quantidadeAtualCarrinho = $row['quantidade'];

            $sqlEstoque = "SELECT quantidade_estoque FROM tbl_produto WHERE id_produto = '$id_produto'";
            $resultEstoque = $this->conexao->executarQuery($sqlEstoque);
            if ($rowEstoque = mysqli_fetch_assoc($resultEstoque)) {
                $estoqueDisponivel = $rowEstoque['quantidade_estoque'];


                if ($quantidadeAtualCarrinho < $estoqueDisponivel) {
                    $novaQuantidade = $quantidadeAtualCarrinho + $qtde;
                    $sqlUpdate = "UPDATE carrinho SET quantidade = '$novaQuantidade' WHERE id_produto = '$id_produto'";
                    $this->conexao->executarQuery($sqlUpdate);
                } else {
                    echo "Não há estoque suficiente para aumentar a quantidade do produto.";
                }
            }
        }
    }

    public function atualizarQuantidade($id_produto, $id_usuario)
    {
        $sql = "SELECT quantidade FROM carrinho WHERE id_produto = '$id_produto' AND id_usuario = '$id_usuario'";
        $result = $this->conexao->executarQuery($sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $quantidadeAtualCarrinho = $row['quantidade'];

            $sqlEstoque = "SELECT quantidade_estoque FROM tbl_produto WHERE id_produto = '$id_produto' ";
            $resultEstoque = $this->conexao->executarQuery($sqlEstoque);
            if ($rowEstoque = mysqli_fetch_assoc($resultEstoque)) {
                $estoqueDisponivel = $rowEstoque['quantidade_estoque'];

                if ($quantidadeAtualCarrinho < $estoqueDisponivel) {
                    $novaQuantidade = $quantidadeAtualCarrinho + 1;
                    $sqlUpdate = "UPDATE carrinho SET quantidade = '$novaQuantidade' WHERE id_produto = '$id_produto' AND id_usuario = '$id_usuario'";
                    $this->conexao->executarQuery($sqlUpdate);
                } else {
                    echo "Não há estoque suficiente para aumentar a quantidade do produto.";
                }
            }
        }
    }
}
$respositorioCarrinho = new ReposiorioCarrinhoMYSQL();
?>
