<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $pass);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-3">Welcome to Online Banking System</h2>
    <div class="card mx-auto mb-5 shadow" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="card-title mb-4 text-center">Register</h4>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" type="text" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
                <p class="text-center mt-3">Already have an account? <a href="index.php">Login</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>