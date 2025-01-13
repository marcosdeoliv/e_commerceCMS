<?php session_start(); ?>

<link href="./assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg bg-light nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="./assets/imgs/logo.png" width="100%" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./products.php">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contato</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Ícone do Carrinho -->
                <li class="nav-item">
                <a class="nav-link" href="cart.php" title="Carrinho">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge bg-primary">
                <img src="./assets/imgs/carrinho.png" width="10%">
                <?php echo array_sum($_SESSION['cart'] ?? []); ?>
                </span>
                </a>
                </li>
                <form class="d-flex me-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <!-- Ícone do Perfil do Usuário -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/account_details.php">
                            <i class="fas fa-user-circle"></i>
                            <span><img src="./assets/imgs/user.png" width="40%"></span>
                        </a>
                    </li>
                <?php endif; ?>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="./auth/logout.php" class="btn btn-danger btn-sm">Sign Out</a>
                <?php else: ?>
                    <a href="./auth/login.php" class="btn btn-primary btn-sm me-2">Sign In</a>
                    <a href="./auth/register.php" class="btn btn-secondary btn-sm">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>