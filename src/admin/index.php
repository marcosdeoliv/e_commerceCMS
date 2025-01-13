<?php
// admin/index.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include('header.php');
?>

<div class="container mt-4">
    <h1>Bem-vindo ao Painel Admin</h1>
    <p>Aqui você pode gerenciar produtos, pedidos e muito mais.</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produtos</h5>
                    <p class="card-text">Visualize e gerencie seus produtos.</p>
                    <a href="products.php" class="btn btn-primary">Ver Produtos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pedidos</h5>
                    <p class="card-text">Gerencie os pedidos realizados no sistema.</p>
                    <a href="add_order.php" class="btn btn-primary">Adicionar Pedido</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Usuários</h5>
                    <p class="card-text">Gerencie os usuários registrados no sistema.</p>
                    <a href="list_users.php" class="btn btn-primary">Ver Usuários</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sair</h5>
                    <p class="card-text">Desconectar do sistema.</p>
                    <a href="login.php?logout=true" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
        <p>Aqui você pode gerenciar produtos, pedidos e muito mais.</p>
    </div>
</div>

<?php include('../layouts/footer.php'); ?>

