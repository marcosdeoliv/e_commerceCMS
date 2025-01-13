<?php
// admin/products.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include('header.php');
include('../server/connection.php');

// Configurações de paginação
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Buscar produtos no banco de dados
$query = "SELECT * FROM products LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Erro na consulta SQL: " . mysqli_error($conn));
}

// Contar total de produtos para paginação
$total_query = "SELECT COUNT(*) AS total FROM products";
$total_result = mysqli_query($conn, $total_query);
$total_products = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_products / $items_per_page);
?>

<div class="container mt-4">
    <h2>Lista de Produtos</h2>
    <a href="add_product.php" class="btn btn-success mb-3">Adicionar Produto</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td>
                        <?php if (!empty($product['product_image'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Imagem do Produto" width="50" height="50">
                        <?php else: ?>
                            <span>Sem imagem</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td>R$ <?php echo number_format($product['product_price'], 2, ',', '.'); ?></td>
                    <td><?php echo $product['product_stock']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="products.php?page=<?php echo $i; ?>"> <?php echo $i; ?> </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include('../layouts/footer.php'); ?>
