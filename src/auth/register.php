<?php
include('../server/connection.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_password = password_hash($_POST['user_password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (user_name, user_email, user_password) 
              VALUES ('$user_name', '$user_email', '$user_password')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $user_name;
        header('Location: ../index.php');
        exit();
    } else {
        $error = "Erro ao cadastrar usuário. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Cadastro</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="user_name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <div class="mb-3">
            <label for="user_email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="user_email" name="user_email" required>
        </div>
        <div class="mb-3">
            <label for="user_password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="user_password" name="user_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>
</body>
</html>