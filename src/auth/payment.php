<?php
session_start();
include('../server/connection.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php?redirect=payment.php');
    exit();
}

// Verificar se o pedido foi passado
if (!isset($_GET['order_id'])) {
    die("Erro: ID do pedido não fornecido.");
}

$order_id = (int)$_GET['order_id'];

// Buscar detalhes do pedido
$query = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $order_id, $_SESSION['user_id']);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    die("Erro: Pedido não encontrado ou não pertence ao usuário.");
}

$order = $order_result->fetch_assoc();
$total_cost = $order['order_cost'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pagamento</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AaGrR5OJj1axzCrWoZGVZccoYfIRNjbVi9Y7AtdhHfuoMdRLthNuY3LKgpaYTC1e41BA7rH0LYeQF8l1&currency=BRL"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Pagamento do Pedido</h2>
    <p><strong>ID do Pedido:</strong> <?php echo $order['order_id']; ?></p>
    <p><strong>Total:</strong> R$ <?php echo number_format($total_cost, 2, ',', '.'); ?></p>

    <div id="paypal-button-container"></div>
</div>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $total_cost; ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                const transactionId = details.id;
                const orderId = <?php echo $order['order_id']; ?>;

                // Redirecionar para complete_payment.php
                window.location.href = "./server/complete_payment.php?transaction_id=" + transactionId + "&order_id=" + orderId;
            });
        },
        onError: function(err) {
            console.error(err);
            alert('Erro ao processar o pagamento. Tente novamente.');
        }
    }).render('#paypal-button-container');
</script>
</body>
</html>
