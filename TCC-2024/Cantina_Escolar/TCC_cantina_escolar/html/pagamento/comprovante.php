<?php

include_once '../../conn/classes/class_IRepositorioProdutos.php';
include_once '../../conn/classes/class_IRepositorioCarrinho.php';
include_once '../../conn/classes/class_IRepositorioUsuarios.php';
session_start();

$id_pedidos = $_GET['id_pedido'];
$id_usuario = $_SESSION['id'];
$resultado = $respositorioCarrinho->mostrar_itens_pedidos($id_pedidos);
$encontrou = $respositorioUsuario->buscarUsuario($id_usuario);
$usuario = $encontrou->fetch_object();
$listagem = $respositorioCarrinho->buscarPedido($id_usuario, $id_pedidos);
$pedido = $listagem->fetch_object();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Compra</title>
    <link rel="shortcut icon" href="../../img/logo2.png" />
    <link rel="stylesheet" href="../../css/comprovante.css">
</head>

<body>
    <div class="recibo-container">
        <h1 class="recibo-title">Cantina Etecamp</h1>
        <p class="recibo-subtitle">Comprovante de Compra</p>
        <div class="recibo-details">
            <p><strong>Data:</strong> <?php echo $data_formatada = date('d/m/Y', strtotime($pedido->data_pedido)); ?></p>
            <p><strong>Número do Pedido:</strong> <?php echo $id_pedidos; ?> </p>
            <p><strong>Nome do Cliente:</strong> <?php echo $usuario->nome_completo; ?></p>
        </div>
        <table class="recibo-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($resultado)) {
                    foreach ($resultado as $registro) {
                ?>
                        <tr>
                            <td><?php echo $registro['nome_produto']; ?></td>
                            <td><?php echo $registro['quantidade']; ?></td>
                            <td>R$ <?php echo number_format((float)($registro['preco_unitario']), 2, ".", ""); ?></td>
                            <td>R$ <?php echo number_format((float)($registro['preco_total']), 2, ".", ""); ?></td>
                        </tr>
            </tbody>
    <?php
                    }
                } else {
                    echo "<p>Você não tem pedidos.</p>";
                }
    ?>
    <tfoot>
        <tr>
            <td colspan=""><strong>Total</strong></td>
            <td><strong>R$ <?php echo $pedido->preco_total; ?></strong></td>
        </tr>
    </tfoot>
        </table>
        <div class="retirada-numero">
            <p><strong>Número de Retirada:</strong>
                <?php echo $pedido->codigo; ?>
            </p>
        </div>
        <div class="button-container">
            <a href="../home.php" class="return-button">Voltar para tela inicial</a>
        </div>
        <p class="recibo-footer">Obrigado pela sua compra!</p>
    </div>
</body>

</html>