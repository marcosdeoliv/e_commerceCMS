<?php
// Incluindo conexão com o banco e cabeçalho
include('./layouts/header.php');
include('./server/connection.php');

// Verificar se o ID do produto foi passado via GET
if (!isset($_GET['id'])) {
    die("Erro: ID do produto não fornecido.");
}

$product_id = (int)$_GET['id'];

// Buscar dados do produto no banco de dados
$query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Produto não encontrado.");
}

$product = $result->fetch_assoc();
?>

<div class="container mt-5">
    <div class="row">
        <!-- Imagem principal do produto -->
        <div class="col-md-6">
        <div id="main-image-container">
    <img id="main-image" src="./uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Produto" class="img-fluid">
</div>
<!-- Imagem do banco de dados -->
<div class="mt-3 d-flex">
    <?php
    $thumbnails = [
        $product['product_image'],
        $product['product_image2'],
        $product['product_image3'],
        $product['product_image4'],
    ];

    foreach ($thumbnails as $thumb) {
        if (!empty($thumb)) {
            echo '<img src="./uploads/' . htmlspecialchars($thumb) . '" class="thumbnail img-fluid me-2" style="width: 80px; cursor: pointer;" alt="Miniatura">';
        }
    }
                ?>
            </div>
        </div>

        <!-- Detalhes do produto -->
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
            <h4>R$ <?php echo number_format($product['product_price'], 2, ',', '.'); ?></h4>
            <p><?php echo htmlspecialchars($product['product_description']); ?></p>

        <form method="POST" action="cart.php">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <div class="mb-3">
        <label for="quantity" class="form-label">Quantidade</label>
        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
        </div>
        <button type="submit" name="add_to_cart" class="btn btn-primary">Adicionar ao Carrinho</button>
        </form>

        </div>
    </div>
</div>
</div>
<?php include('./layouts/footer.php'); ?>
<!-- Incluindo o script de imagens -->
<script src="./assets/js/images.js"></script>