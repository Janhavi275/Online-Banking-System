<?php
$conn = new mysqli("127.0.0.1", "root", "root","online_banking",3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
