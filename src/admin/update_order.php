<?php
session_start();
include('../server/connection.php');

// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do pedido foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);

    // Atualizar o status do pedido
    $query = "UPDATE orders SET order_status = '$order_status' WHERE order_id = $order_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Pedido atualizado com sucesso.";
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar o pedido.";
    }

    header('Location: index.php');
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>
