<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT 
        t.id, 
        u1.name AS sender_name, 
        u2.name AS receiver_name, 
        t.amount, 
        t.type,
        t.created_at 
    FROM transactions t 
    JOIN users u1 ON t.sender_id = u1.id 
    JOIN users u2 ON t.receiver_id = u2.id 
    WHERE t.sender_id = $user_id OR t.receiver_id = $user_id 
    ORDER BY t.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
    <h3><i class="bi bi-journal-text me-2"></i>Transaction History</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Amount ($)</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['sender_name']) ?></td>
                    <td><?= htmlspecialchars($row['receiver_name']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <?php
                            if ($row['type'] === 'deposit') {
                                echo '<i class="bi bi-arrow-down-circle text-success"></i> Deposit';
                            } elseif ($row['type'] === 'withdraw') {
                                echo '<i class="bi bi-arrow-up-circle text-warning"></i> Withdraw';
                            } else {
                                echo '<i class="bi bi-arrow-left-right text-primary"></i> Transfer';
                            }
                        ?>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
