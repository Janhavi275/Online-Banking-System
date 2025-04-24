<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = floatval($_POST['amount']);
    $user_id = $_SESSION['user_id'];

    if ($amount > 0) {
        $conn->query("UPDATE users SET balance = balance + $amount WHERE id = $user_id");
        $conn->query("INSERT INTO transactions (sender_id, receiver_id, amount, type) VALUES ($user_id, $user_id, $amount, 'deposit')");
        $msg = "Deposit successful!";
    } else {
        $msg = "Amount must be greater than zero.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deposit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
    <h3 class="mb-3"><i class="bi bi-wallet2 me-2"></i>Deposit Money</h3>
        <?php if ($msg): ?>
            <div class="alert alert-info"><?= $msg ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="amount" class="form-label">Amount ($):</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <button class="btn btn-success">
                <i class="bi bi-cash-stack me-1"></i> Deposit
            </button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>
