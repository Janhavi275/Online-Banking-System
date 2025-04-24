<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
$balance = $user['balance'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
    <h5><i class="bi bi-person-circle me-2"></i>Welcome, <?= htmlspecialchars($user['name']); ?></h5>
            <h4>
        Balance: ₹ <?= number_format($balance, 2) ?>
        <i class="bi bi-question-circle text-secondary" data-bs-toggle="tooltip" title="This is your current available balance."></i>
        </h4>
        <div class="mt-4">
            <a href="deposit.php" class="btn btn-success me-2">
            <i class="bi bi-wallet2 me-1"></i> Deposit
            </a>
            <a href="withdraw.php" class="btn btn-warning me-2">
            <i class="bi bi-cash-coin me-1"></i> Withdraw
            </a>
            <a href="transfer.php" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left-right me-1"></i> Transfer
            </a>
            <a href="transaction.php" class="btn btn-info me-2">
            <i class="bi bi-clock-history me-1"></i> Transactions
            </a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Initialize all tooltips on the page
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

</body>
</html>
