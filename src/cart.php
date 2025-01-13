<?php
include('./layouts/header.php');
include('./server/connection.php');


// Inicializar o carrinho, se não existir
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Adicionar produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

// Verificar se o produto já está no carrinho
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
    } else {
    $_SESSION['cart'][$product_id] = $quantity;
    }

// Redirecionar para a página do carrinho
header('Location: cart.php');
exit();
}

// Atualizar quantidade no carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header('Location: cart.php');
    exit();
}

// Remover produto do carrinho
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php');
    exit();
}
?>

<div class="container mt-5">
    <h2>Carrinho de Compras</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $product_id => $quantity):
                        $query = "SELECT * FROM products WHERE product_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('i', $product_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_assoc();
                        $total_item_price = $product['product_price'] * $quantity;
                        $total_price += $total_item_price;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>R$ <?php echo number_format($product['product_price'], 2, ',', '.'); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" class="form-control" style="width: 70px;">
                            </td>
                            <td>R$ <?php echo number_format($total_item_price, 2, ',', '.'); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $product_id; ?>" class="btn btn-danger btn-sm">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Total:</td>
                        <td colspan="2">R$ <?php echo number_format($total_price, 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <button type="submit" name="update_cart" class="btn btn-primary">Atualizar Carrinho</button>
            <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
        </form>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
        <a href="products.php" class="btn btn-secondary">Voltar às Compras</a>
    <?php endif; ?>
</div>

