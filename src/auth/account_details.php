<?php
session_start();
include('../server/connection.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$error = '';
$success = '';

// Buscar detalhes do usuário
$query = "SELECT user_name, user_email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    die("Erro: Usuário não encontrado.");
}

$user = $user_result->fetch_assoc();

// Atualizar senha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $update_query = "UPDATE users SET user_password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $new_password, $user_id);

    if ($stmt->execute()) {
        $success = "Senha atualizada com sucesso!";
    } else {
        $error = "Erro ao atualizar a senha. Tente novamente.";
    }
}

// Buscar pedidos do usuário
$order_query = "SELECT order_id, order_cost, order_status, order_date FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Minha Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="../index.php"><img src="../assets/imgs/user.png">Go Back PufCherry</a>
    <h2>Minha Conta</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Detalhes do Usuário -->
    <h4>Detalhes do Usuário</h4>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($user['user_email']); ?></p>

    <!-- Formulário para Atualizar Senha -->
    <h4>Atualizar Senha</h4>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="new_password" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="update_password" class="btn btn-primary">Atualizar Senha</button>
    </form>
<!-- Lista de Pedidos -->
<h4 class="mt-5">Meus Pedidos</h4>
<?php if ($order_result->num_rows > 0): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID do Pedido</th>
                <th>Custo</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $order_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td>R$ <?php echo number_format($order['order_cost'], 2, ',', '.'); ?></td>
                    <td><?php echo ucfirst($order['order_status']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                    <td><a href="delete_order.php?order_id=<?php echo $order['order_id']; ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Tem certeza que deseja excluir este pedido?');">
                            Excluir Pedido
                        </a>
                    </td>
                    <td>
                        <?php if ($order['order_status'] === 'on_hold'): ?>
                            <a href="payment.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-success btn-sm">Pagar</a>
                        <?php else: ?>
                            <span class="text-muted">Pago</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>
</div>
</body>
</html>