<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
include '../server/connection.php';
include 'header.php';
// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Processa o formulário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);

    // Validação básica
    if (empty($user_name) || empty($user_email) || empty($user_password)) {
        $error = "Todos os campos são obrigatórios!";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido!";
    } else {
        // Hash da senha
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Inserção no banco de dados
        $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $user_name, $user_email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Usuário adicionado com sucesso!";
        } else {
            $error = "Erro ao adicionar usuário: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Adicionar Usuário</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="add_user.php" method="POST">
            <div class="mb-3">
                <label for="user_name" class="form-label">Nome do Usuário</label>
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
            <div class="mb-3">
                <label for="user_role" class="form-label">Tipo de Usuário</label>
                <select class="form-control" id="user_role" name="user_role" required>
                    <option value="user">Usuário</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Usuário</button>
        </form>
    </div>
</body>
</html>
