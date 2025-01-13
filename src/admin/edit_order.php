<?php
// Inclui o arquivo de conexão com o banco de dados
include '../server/connection.php';
include 'header.php';

// Verifica se o ID do pedido foi fornecido via GET
if (!isset($_GET['id'])) {
    die("Erro: ID do pedido não fornecido.");
}

$order_id = (int)$_GET['id'];

// Busca os dados do pedido no banco de dados
$query = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Pedido não encontrado.");
}

$order = $result->fetch_assoc();

// Atualizar o pedido se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $shipping_city = mysqli_real_escape_string($conn, $_POST['shipping_city']);
    $shipping_uf = mysqli_real_escape_string($conn, $_POST['shipping_uf']);
    $shipping_address = mysqli_real_escape_string($conn, $_POST['shipping_address']);

    $update_query = "UPDATE orders SET order_status = ?, shipping_city = ?, shipping_uf = ?, shipping_address = ? WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssssi', $order_status, $shipping_city, $shipping_uf, $shipping_address, $order_id);

    if ($update_stmt->execute()) {
        header('Location: add_order.php?success=Pedido atualizado com sucesso!');
        exit();
    } else {
        $error = "Erro ao atualizar o pedido: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Pedido</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit_order.php?id=<?php echo $order_id; ?>" method="POST">
        <div class="mb-3">
            <label for="order_cost" class="form-label">Custo do Pedido</label>
            <input type="text" class="form-control" id="order_cost" value="<?php echo number_format($order['order_cost'], 2, ',', '.'); ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="order_status" class="form-label">Status do Pedido</label>
            <select class="form-select" id="order_status" name="order_status" required>
                <option value="on_hold" <?php echo ($order['order_status'] === 'on_hold') ? 'selected' : ''; ?>>Em análise</option>
                <option value="paid" <?php echo ($order['order_status'] === 'paid') ? 'selected' : ''; ?>>Pago</option>
                <option value="shipped" <?php echo ($order['order_status'] === 'shipped') ? 'selected' : ''; ?>>Enviado</option>
                <option value="delivered" <?php echo ($order['order_status'] === 'delivered') ? 'selected' : ''; ?>>Entregue</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="shipping_city" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="<?php echo htmlspecialchars($order['shipping_city']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="shipping_uf" class="form-label">UF</label>
            <input type="text" class="form-control" id="shipping_uf" name="shipping_uf" value="<?php echo htmlspecialchars($order['shipping_uf']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="shipping_address" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="shipping_address" name="shipping_address" value="<?php echo htmlspecialchars($order['shipping_address']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Pedido</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
