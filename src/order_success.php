<?php
include('./layouts/header.php');
include('./server/connection.php');

// Verificar se o ID do pedido foi passado via GET
if (!isset($_GET['order_id'])) {
    die("Erro: ID do pedido não fornecido.");
}

$order_id = (int)$_GET['order_id'];

// Buscar detalhes do pedido no banco de dados
$query = "
    SELECT orders.*, users.user_name 
    FROM orders 
    JOIN users ON orders.user_id = users.user_id 
    WHERE orders.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Pedido não encontrado.");
}

$order = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2 class="text-center text-success">Pedido Concluído com Sucesso!</h2>
    <p class="text-center">Obrigado pela sua compra, <?php echo htmlspecialchars($order['user_name']); ?>.</p>

    <div class="card mt-4">
        <div class="card-body">
            <h4>Detalhes do Pedido</h4>
            <ul>
                <li><strong>ID do Pedido:</strong> <?php echo $order['order_id']; ?></li>
                <li><strong>Total:</strong> R$ <?php echo number_format($order['order_cost'], 2, ',', '.'); ?></li>
                <li><strong>Status:</strong> <?php echo ucfirst($order['order_status']); ?></li>
                <li><strong>Endereço de Entrega:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></li>
                <li><strong>Data do Pedido:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></li>
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <h5>Itens do Pedido</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Buscar os itens do pedido
                $items_query = "
                    SELECT order_items.*, products.product_name, products.product_price 
                    FROM order_items 
                    JOIN products ON order_items.product_id = products.product_id 
                    WHERE order_items.order_id = ?";
                $items_stmt = $conn->prepare($items_query);
                $items_stmt->bind_param('i', $order_id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();

                while ($item = $items_result->fetch_assoc()):
                    $total_price = $item['qnt'] * $item['product_price'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['qnt']; ?></td>
                        <td>R$ <?php echo number_format($item['product_price'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($total_price, 2, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="products.php" class="btn btn-primary">Continuar Comprando</a>
    </div>
</div>

<?php include('./layouts/footer.php'); ?>

