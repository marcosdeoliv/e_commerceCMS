<?php
session_start();
include('./connection.php');

// Verificar se os parâmetros foram passados
if (!isset($_GET['transaction_id']) || !isset($_GET['order_id'])) {
    die("Erro: Parâmetros inválidos.");
}

$transaction_id = mysqli_real_escape_string($conn, $_GET['transaction_id']);
$order_id = (int)$_GET['order_id'];

// Verificar se o pedido existe
$query = "SELECT * FROM orders WHERE order_id = ? AND order_status = 'on_hold'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Pedido não encontrado ou já pago.");
}

// Atualizar o status do pedido
$update_query = "UPDATE orders SET order_status = 'paid' WHERE order_id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param('i', $order_id);
if (!$stmt->execute()) {
    die("Erro ao atualizar status do pedido: " . $stmt->error);
}

// Inserir o pagamento na tabela payments
$payment_query = "INSERT INTO payments (order_id, transaction_id, payment_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($payment_query);
$stmt->bind_param('is', $order_id, $transaction_id);

if ($stmt->execute()) {
    // Redirecionar para a página de sucesso
    header("Location: ../order_success.php?order_id=$order_id&payment=success");
    exit();
} else {
    die("Erro ao registrar pagamento: " . $stmt->error);
}
?>
