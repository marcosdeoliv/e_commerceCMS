<?php
// Inclui o arquivo de conexão com o banco de dados
include '../server/connection.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_order'])) {
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_cost = 0;

    // Define o user_id como 2 (padrão)
    $user_id = 2; // Substitua pelo ID correto

    $shipping_city = mysqli_real_escape_string($conn, $_POST['shipping_city']);
    $shipping_uf = mysqli_real_escape_string($conn, $_POST['shipping_uf']);
    $shipping_address = mysqli_real_escape_string($conn, $_POST['shipping_address']);

    // Processar os produtos e calcular o custo total
    foreach ($product_ids as $index => $product_id) {
        $quantity = (int)$quantities[$index];
        $product_query = "SELECT product_price, product_stock FROM products WHERE product_id = $product_id";
        $product_result = mysqli_query($conn, $product_query);
        $product = mysqli_fetch_assoc($product_result);

        if ($quantity > $product['product_stock']) {
            die("Erro: Quantidade excede o estoque disponível para o produto ID $product_id.");
        }

        $new_stock = $product['product_stock'] - $quantity;
        $update_stock_query = "UPDATE products SET product_stock = $new_stock WHERE product_id = $product_id";
        mysqli_query($conn, $update_stock_query);

        $total_cost += $product['product_price'] * $quantity;
    }

    // Inserir o pedido na tabela orders
    $order_query = "INSERT INTO orders (order_cost, order_status, user_id, shipping_city, shipping_uf, shipping_address, order_date)
                    VALUES ($total_cost, 'on_hold', $user_id, '$shipping_city', '$shipping_uf', '$shipping_address', NOW())";

    if (!mysqli_query($conn, $order_query)) {
        die("Erro ao inserir o pedido: " . mysqli_error($conn));
    }

    $order_id = mysqli_insert_id($conn);

    // Inserir os itens do pedido na tabela order_items
    foreach ($product_ids as $index => $product_id) {
        $quantity = (int)$quantities[$index];
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity) 
                             VALUES ($order_id, $product_id, $quantity)";
        mysqli_query($conn, $order_item_query);
    }

    // Redirecionar após a criação do pedido
    header('Location: index.php');
    exit();
}

// Configurações de paginação para pedidos
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Buscar pedidos no banco de dados com limite para paginação
$query = "SELECT * FROM orders LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta SQL: " . mysqli_error($conn));
}

// Contar total de pedidos para calcular a paginação
$total_query = "SELECT COUNT(*) AS total FROM orders";
$total_result = mysqli_query($conn, $total_query);

if (!$total_result) {
    die("Erro ao contar pedidos: " . mysqli_error($conn));
}

$total_orders = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_orders / $items_per_page);

// Buscar produtos disponíveis
$product_query = "SELECT * FROM products WHERE product_stock > 0";
$product_result = mysqli_query($conn, $product_query);

// Calcular o custo total de todos os pedidos
$total_cost_query = "SELECT SUM(order_cost) AS total_cost FROM orders";
$total_cost_result = mysqli_query($conn, $total_cost_query);

$total_cost = $total_cost_result ? mysqli_fetch_assoc($total_cost_result)['total_cost'] : 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid mt-4">
    <div class="row">
        <?php include '../layouts/sidemenu.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Pedidos</h2>
            <!-- Listagem de Pedidos -->
            <h3>Lista de Pedidos</h3>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Custo</th>
                        <th>Status</th>
                        <th>Usuário</th>
                        <th>Cidade</th>
                        <th>UF</th>
                        <th>Endereço</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo number_format($order['order_cost'], 2, ',', '.'); ?></td>
                            <td><?php echo ucfirst($order['order_status']); ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><?php echo $order['shipping_city']; ?></td>
                            <td><?php echo $order['shipping_uf']; ?></td>
                            <td><?php echo $order['shipping_address']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                            <td>
                                <a href="edit_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este pedido?');">
                                Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Paginação -->
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <h4>Total de Custos dos Pedidos: R$ <?php echo number_format($total_cost, 2, ',', '.'); ?></h4>

            <!-- Formulário para Adicionar Pedido -->
            <form method="POST" action="" class="mt-4">
                <h4>Adicionar Pedido</h4>
                <div id="product-list">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_id[]" class="form-label">Produto</label>
                            <select name="product_id[]" class="form-select" required>
                                <option value="" disabled selected>Selecione um produto</option>
                                <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
                                    <option value="<?php echo $product['product_id']; ?>">
                                        <?php echo htmlspecialchars($product['product_name']) . " (Estoque: " . $product['product_stock'] . ")"; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quantity[]" class="form-label">Quantidade</label>
                            <input type="number" name="quantity[]" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger mt-4 remove-product">Remover</button>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="shipping_city" class="form-label">Cidade</label>
                        <input type="text" name="shipping_city" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="shipping_uf" class="form-label">UF</label>
                        <input type="text" name="shipping_uf" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label for="shipping_address" class="form-label">Endereço</label>
                        <input type="text" name="shipping_address" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="save_order" class="btn btn-primary">Criar Pedido</button>
            </form>
        </main>
    </div>
</div>
</body>
</html>



