<?php
include('header.php');
include('../server/connection.php');
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Configurações de paginação
$items_per_page = 10; // Número de usuários por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Buscar usuários no banco de dados com limite para paginação
$query = "SELECT user_id, user_name, user_email FROM users LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta SQL: " . mysqli_error($conn));
}

// Contar total de usuários para calcular a paginação
$total_query = "SELECT COUNT(*) AS total FROM users";
$total_result = mysqli_query($conn, $total_query);

if (!$total_result) {
    die("Erro ao contar usuários: " . mysqli_error($conn));
}

$total_users = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_users / $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Usuários</h2>
        <a href="add_user.php" class="btn btn-success">Adicionar Usuário</a>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm">Excluir</a>
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
                    <a class="page-link" href="list_users.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>
