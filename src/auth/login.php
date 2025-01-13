<?php
// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../server/connection.php');

// Inicializar variáveis
$email = '';
$password = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar se os campos estão definidos
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Verificar se os campos estão preenchidos
    if (empty($email) || empty($password)) {
        $error = "Preencha todos os campos.";
    } else {
        // Consultar o banco de dados
        $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verificar a senha
                if (password_verify($password, $user['user_password'])) {
                    // Configurar sessão
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['user_name'];

                    // Redirecionar após login bem-sucedido
                    header('Location: ../index.php');
                    exit();
                } else {
                    $error = "Senha incorreta.";
                }
            } else {
                $error = "Usuário não encontrado.";
            }
        } else {
            $error = "Erro no banco de dados: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }

        .card {
            width: 100%;
            max-width: 360px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
<div class="card">
    <h2 class="text-center">Login</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
</body>
</html>