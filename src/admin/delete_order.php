<?php
session_start();
include('../server/connection.php');

// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do pedido foi enviado via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "ID do pedido não fornecido ou inválido.";
    header('Location: add_order.php');
    exit();
}

$order_id = (int)$_GET['id']; // Garantir que o ID seja um inteiro

// Validar se o pedido existe antes de tentar excluir
$check_order_query = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($check_order_query);

if (!$stmt) {
    $_SESSION['error_message'] = "Erro ao preparar a consulta de validação: " . $conn->error;
    header('Location: add_order.php');
    exit();
}

$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Pedido não encontrado.";
    header('Location: add_order.php');
    exit();
}

// Excluir os itens relacionados ao pedido
$item_delete_query = "DELETE FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($item_delete_query);

if (!$stmt) {
    $_SESSION['error_message'] = "Erro ao preparar a exclusão de itens: " . $conn->error;
    header('Location: add_order.php');
    exit();
}

$stmt->bind_param('i', $order_id);

if (!$stmt->execute()) {
    $_SESSION['error_message'] = "Erro ao excluir itens do pedido: " . $stmt->error;
    header('Location: add_order.php');
    exit();
}

// Excluir o pedido
$order_delete_query = "DELETE FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($order_delete_query);

if (!$stmt) {
    $_SESSION['error_message'] = "Erro ao preparar a exclusão do pedido: " . $conn->error;
    header('Location: add_order.php');
    exit();
}

$stmt->bind_param('i', $order_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Pedido excluído com sucesso.";
} else {
    $_SESSION['error_message'] = "Erro ao excluir o pedido: " . $stmt->error;
}

header('Location: add_order.php');
exit();
