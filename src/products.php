<?php
// Incluindo conexão com o banco de dados e cabeçalho
include('./layouts/header.php');
include('./server/connection.php');

// Configurações de paginação
$items_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Buscar produtos no banco de dados com limite para paginação
$query = "SELECT * FROM products LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $offset, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Erro ao buscar produtos: " . mysqli_error($conn));
}

// Contar total de produtos para paginação
$total_query = "SELECT COUNT(*) AS total FROM products";
$total_result = mysqli_query($conn, $total_query);
$total_products = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_products / $items_per_page);
?>
<?php

// Buscar todos os produtos no banco de dados
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <h1 class="text-center">Nossos Produtos</h1>
    <p class="text-center">Confira abaixo nossos produtos disponíveis</p>
    <div class="container mt-5">
    <!-- Botões para filtrar categorias -->
    <div class="d-flex justify-content-center mb-4">
        <button class="btn btn-primary filter-btn" data-category="all">Todos</button>
        <button class="btn btn-secondary filter-btn" data-category="Alimentos">Alimentos</button>
        <button class="btn btn-secondary filter-btn" data-category="Livraria">Livraria</button>
        <button class="btn btn-secondary filter-btn" data-category="Puffs">Puffs</button>
        <button class="btn btn-secondary filter-btn" data-category="Roupas">Roupas</button>
        <button class="btn btn-secondary filter-btn" data-category="Cursos">Cursos</button>
    </div>
    <!-- Produtos -->
    <div class="row" id="product-container">
        <?php
        $query = "SELECT * FROM products";
        $result = mysqli_query($conn, $query);

        while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-3 product" data-category="<?php echo $product['product_category']; ?>">
                <div class="card mb-4">
                    <img src="./uploads/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text">R$ <?php echo number_format($product['product_price'], 2, ',', '.'); ?></p>
                        <div class="rating">
                            <span class="star" data-value="5">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="1">★</span>
                        </div>
                        <a href="single_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary mt-3">Detalhes</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <script src="./assets/js/images.js"></script>
    </div>
</div>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="./uploads/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text">R$ <?php echo number_format($product['product_price'], 2, ',', '.'); ?></p>
                        <a href="single_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Paginação -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="products.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include('./layouts/footer.php'); ?>
<script src="./assets/js/filter.js"></script>