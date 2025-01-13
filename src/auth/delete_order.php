<?php
session_start();
include('../server/connection.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do pedido foi fornecido
if (!isset($_GET['order_id'])) {
    die("Erro: ID do pedido não fornecido.");
}

$order_id = (int)$_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Verificar se o pedido pertence ao usuário
$query = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Pedido não encontrado ou você não tem permissão para excluí-lo.");
}

// Excluir o pedido
$delete_query = "DELETE FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param('i', $order_id);

if ($stmt->execute()) {
    header('Location: account_details.php?success=Pedido excluído com sucesso');
    exit();
} else {
    die("Erro ao excluir pedido: " . $conn->error);
}
if (isset($_GET['message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
}
?>
