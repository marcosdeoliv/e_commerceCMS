<?php
include('../server/connection.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o ID do usuário foi fornecido
if (!isset($_GET['id'])) {
    header('Location: list_users.php');
    exit();
}

$user_id = (int)$_GET['id'];

// Buscar os dados do usuário no banco de dados
$query = "SELECT user_id, user_name, user_email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: list_users.php');
    exit();
}

$user = $result->fetch_assoc();

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);

    // Validação básica
    if (empty($user_name) || empty($user_email)) {
        $error = "Todos os campos são obrigatórios!";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido!";
    } else {
        // Atualizar os dados do usuário no banco de dados
        $update_query = "UPDATE users SET user_name = ?, user_email = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('ssi', $user_name, $user_email, $user_id);

        if ($update_stmt->execute()) {
            $success = "Usuário atualizado com sucesso!";
        } else {
            $error = "Erro ao atualizar o usuário: " . $conn->error;
        }

        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Usuário</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
        <div class="mb-3">
            <label for="user_name" class="form-label">Nome do Usuário</label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="user_email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="list_users.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>
