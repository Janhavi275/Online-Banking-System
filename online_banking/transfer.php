<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_email = $_POST['email'];
    $amount = floatval($_POST['amount']);

    if ($amount <= 0 || $amount > $user['balance']) {
        $error = "Invalid amount or insufficient balance.";
    } else {
        $result = $conn->query("SELECT * FROM users WHERE email='$recipient_email'");
        $recipient = $result->fetch_assoc();

        if ($recipient) {
            $conn->begin_transaction();
            try {
                // Deduct from sender
                $conn->query("UPDATE users SET balance = balance - $amount WHERE id = $user_id");

                // Add to recipient
                $conn->query("UPDATE users SET balance = balance + $amount WHERE id = {$recipient['id']}");

                // Record transaction
                $conn->query("INSERT INTO transactions (sender_id, receiver_id, amount) VALUES ($user_id, {$recipient['id']}, $amount)");

                $conn->commit();
                $success = "Transfer successful!";
                $user['balance'] -= $amount; // update locally
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Transfer failed. Please try again.";
            }
        } else {
            $error = "Recipient not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transfer Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3 class="mb-3">Transfer Money</h3>
        <p>Your Balance: $<?= number_format($user['balance'], 2) ?></p>
        <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="post">
            <div class="mb-3">
                <label>Recipient Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Amount ($)</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
            <a href="dashboard.php" class="btn btn-secondary ms-2">Back</a>
        </form>
    </div>
</div>
</body>
</html>
