<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../server/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/imgs/logo.png" alt="Logo" width="50">
            PruthCherry Admin
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_product.php">Adicionar Produto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_order.php">Adicionar Pedido</a>
                </li>
            </ul>
</nav>
<nav class="navbar navbar-expand-lg bg-light nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            PruthCherry Admin
        </a>
        <div class="d-flex">
            <?php if (isset($_SESSION['admin_name'])): ?>
                <span class="navbar-text me-3">Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm">Entrar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
</body>
</html>
