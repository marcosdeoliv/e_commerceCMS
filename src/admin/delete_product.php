<?php
session_start();
include('../server/connection.php');

// Verificar se o usuário está logado como admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do produto foi fornecido via GET
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = (int)$_GET['id'];

// Excluir o produto do banco de dados
$query = "DELETE FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);

if ($result) {
    $_SESSION['success_message'] = "Produto excluído com sucesso.";
} else {
    $_SESSION['error_message'] = "Erro ao excluir o produto: " . mysqli_error($conn);
}

header('Location: products.php');
exit();