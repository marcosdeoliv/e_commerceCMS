<?php
// Verificação de login do administrador
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

include '../server/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);

    // Verificar se o e-mail e senha estão preenchidos
    if (empty($admin_email) || empty($admin_password)) {
        $error = "Preencha todos os campos!";
    } else {
        // Preparar e executar a consulta
        $sql = "SELECT * FROM admins WHERE admin_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            // Verificar se a senha está no formato antigo (MD5)
            if (md5($admin_password) === $admin['admin_password']) {
                // Atualizar para um hash seguro
                $new_hash = password_hash($admin_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE admins SET admin_password = ? WHERE admin_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('si', $new_hash, $admin['admin_id']);
                $update_stmt->execute();

                // Logar o usuário
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['admin_name'];
                header('Location: index.php');
                exit();
            }

            // Verificar se a senha já está no formato seguro
            if (password_verify($admin_password, $admin['admin_password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['admin_name'];
                header('Location: index.php');
                exit();
            } else {
                $error = "Senha incorreta.";
            }
        } else {
            $error = "E-mail não encontrado.";
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
    <title>Login do Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .card h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .alert {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login do Administrador</h2>

        <?php if (!empty($error)): ?>
            <div class="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="admin_email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
            </div>
            <div class="mb-3">
                <label for="admin_password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>