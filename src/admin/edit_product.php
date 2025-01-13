<?php
session_start();
include('../server/connection.php');
// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do produto foi enviado via GET
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = (int)$_GET['id']; // Garantir que o ID seja um inteiro

// Buscar os dados do produto para exibir no formulário
$product_query = "SELECT * FROM products WHERE product_id = $product_id";
$product_result = mysqli_query($conn, $product_query);

if (!$product_result || mysqli_num_rows($product_result) != 1) {
    header('Location: products.php');
    exit();
}

$product = mysqli_fetch_assoc($product_result);

// Atualizar o produto ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = (float)$_POST['product_price'];
    $product_stock = (int)$_POST['product_stock'];
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);

    // Verificar se há imagens para upload
    $uploaded_images = [];
    for ($i = 1; $i <= 4; $i++) {
        if (isset($_FILES["product_image$i"]) && $_FILES["product_image$i"]['error'] == UPLOAD_ERR_OK) {
            $image_tmp_name = $_FILES["product_image$i"]['tmp_name'];
            $image_name = uniqid() . '-' . $_FILES["product_image$i"]['name'];
            $image_path = '../uploads/' . $image_name;
            move_uploaded_file($image_tmp_name, $image_path);
            $uploaded_images["product_image$i"] = $image_name;
        }
    }

    // Atualizar o banco de dados com as novas informações
    $update_query = "UPDATE products SET 
        product_name = '$product_name', 
        product_price = $product_price, 
        product_stock = $product_stock, 
        product_description = '$product_description'";
    
    // Adicionar as imagens na query de atualização
    foreach ($uploaded_images as $key => $value) {
        $update_query .= ", $key = '$value'";
    }

    $update_query .= " WHERE product_id = $product_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success_message'] = "Produto atualizado com sucesso.";
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar o produto.";
    }

    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Produto</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="product_name" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="product_price" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" value="<?php echo $product['product_price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="product_stock" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="product_stock" name="product_stock" value="<?php echo $product['product_stock']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="product_description" class="form-label">Descrição</label>
            <textarea class="form-control" id="product_description" name="product_description" rows="3" required><?php echo htmlspecialchars($product['product_description']); ?></textarea>
        </div>
        <div class="container">
        <h2>Carregar Imagens do Produto</h2>
        <form action="edit_product_image.php?product_id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_image" class="form-label">Imagem Principal</label>
                <img id="preview_product_image" src="../uploads/<?php echo $product['product_image']; ?>" alt="Imagem Principal" width="100">
                <input type="file" class="form-control" id="product_image" name="product_image" onchange="previewImage(this, 'preview_product_image')">
            </div>
            <div class="mb-3">
                <label for="product_image2" class="form-label">Imagem 2</label>
                <img id="preview_product_image2" src="../uploads/<?php echo $product['product_image2']; ?>" alt="Imagem 2" width="100">
                <input type="file" class="form-control" id="product_image2" name="product_image2" onchange="previewImage(this, 'preview_product_image2')">
            </div>
            <div class="mb-3">
                <label for="product_image3" class="form-label">Imagem 3</label>
                <img id="preview_product_image3" src="../uploads/<?php echo $product['product_image3']; ?>" alt="Imagem 3" width="100">
                <input type="file" class="form-control" id="product_image3" name="product_image3" onchange="previewImage(this, 'preview_product_image3')">
            </div>
            <div class="mb-3">
                <label for="product_image4" class="form-label">Imagem 4</label>
                <img id="preview_product_image4" src="../uploads/<?php echo $product['product_image4']; ?>" alt="Imagem 4" width="100">
                <input type="file" class="form-control" id="product_image4" name="product_image4" onchange="previewImage(this, 'preview_product_image4')">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="products.php" class="btn btn-secondary">Cancelar</a>
        </form>
</div>
</body>
</html>
