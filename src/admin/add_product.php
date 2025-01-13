<?php
session_start();

// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include('../server/connection.php');

// Lógica para adicionar um produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_category = mysqli_real_escape_string($conn, $_POST['product_category']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $product_price = (float)$_POST['product_price'];
    $product_stock = (int)$_POST['product_stock'];
    
    // Upload das imagens
    $uploaded_images = [];
    $image_fields = ['product_image', 'product_image2', 'product_image3', 'product_image4'];

    foreach ($image_fields as $image_field) {
        if (isset($_FILES[$image_field]) && $_FILES[$image_field]['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES[$image_field]['tmp_name'];
            $file_name = basename($_FILES[$image_field]['name']);
            $file_path = '../uploads/' . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $uploaded_images[$image_field] = $file_name;
            } else {
                $uploaded_images[$image_field] = null;
            }
        } else {
            $uploaded_images[$image_field] = null;
        }
    }

    $query = "INSERT INTO products (product_name, product_category, product_description, product_price, product_stock, product_image, product_image2, product_image3, product_image4) 
              VALUES ('$product_name', '$product_category', '$product_description', $product_price, $product_stock, '{$uploaded_images['product_image']}', '{$uploaded_images['product_image2']}', '{$uploaded_images['product_image3']}', '{$uploaded_images['product_image4']}')";

    if (mysqli_query($conn, $query)) {
        header('Location: products.php');
        exit();
    } else {
        $error = "Erro ao adicionar o produto: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adicionar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('header.php'); ?>
<div class="container mt-4">
    <h2>Adicionar Produto</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?php echo $error; ?> </div>
    <?php endif; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="product_name" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>
        <div class="mb-3">
            <label for="product_category" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="product_category" name="product_category" required>
        </div>
        <div class="mb-3">
            <label for="product_description" class="form-label">Descrição</label>
            <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="product_price" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
        </div>
        <div class="mb-3">
            <label for="product_stock" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="product_stock" name="product_stock" required>
        </div>
        <div class="mb-3">
            <label for="product_image" class="form-label">Imagem Principal</label>
            <input type="file" class="form-control" id="product_image" name="product_image">
        </div>
        <div class="mb-3">
            <label for="product_image2" class="form-label">Imagem Secundária 1</label>
            <input type="file" class="form-control" id="product_image2" name="product_image2">
        </div>
        <div class="mb-3">
            <label for="product_image3" class="form-label">Imagem Secundária 2</label>
            <input type="file" class="form-control" id="product_image3" name="product_image3">
        </div>
        <div class="mb-3">
            <label for="product_image4" class="form-label">Imagem Secundária 3</label>
            <input type="file" class="form-control" id="product_image4" name="product_image4">
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Produto</button>
        <a href="products.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
