<?php
include('./layouts/header.php');
include('./server/connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php?redirect=checkout.php');
    exit();
}

// Processar checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $total_price = $_POST['total_price'];

    // Inserir pedido
    $order_query = "INSERT INTO orders (user_id, order_cost, shipping_address, order_status, order_date) 
                    VALUES (?, ?, ?, 'on_hold', NOW())";
    $stmt = $conn->prepare($order_query);
    if (!$stmt) {
        die("Erro ao preparar consulta de pedido: " . $conn->error);
    }
    $stmt->bind_param('ids', $user_id, $total_price, $address);

    if (!$stmt->execute()) {
        die("Erro ao executar consulta de pedido: " . $stmt->error);
    }

    $order_id = $stmt->insert_id; // Obter o ID do pedido inserido

    // Inserir itens do pedido
    $order_item_query = "INSERT INTO order_items (order_id, product_id, user_id, qnt, order_data) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_item_query);
    if (!$stmt) {
        die("Erro ao preparar consulta de item do pedido: " . $conn->error);
    }

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt->bind_param('iiii', $order_id, $product_id, $user_id, $quantity);
        if (!$stmt->execute()) {
            die("Erro ao executar consulta de item do pedido: " . $stmt->error);
        }
    }

    // Limpar carrinho
    unset($_SESSION['cart']);
    header("Location: order_success.php?order_id=$order_id");
    exit();
}
?>

<div class="container mt-5">
    <h2>Checkout</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="address" class="form-label">Endereço</label>
            <textarea id="address" name="address" class="form-control" required></textarea>
        </div>
        <h4>Resumo do Pedido</h4>
        <ul>
            <?php
            $total_price = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity):
                $query = "SELECT * FROM products WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    die("Erro ao preparar consulta de produto: " . $conn->error);
                }
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $total_item_price = $product['product_price'] * $quantity;
                $total_price += $total_item_price;
                echo "<li>{$product['product_name']} (x{$quantity}) - R$ " . number_format($total_item_price, 2, ',', '.') . "</li>";
            endforeach;
            ?>
        </ul>
        <h4>Total: R$ <?php echo number_format($total_price, 2, ',', '.'); ?></h4>
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <button type="submit" name="place_order" class="btn btn-success">Finalizar Pedido</button>
    </form>
</div>

