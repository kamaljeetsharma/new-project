<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jammu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Hash the new password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Your password has been successfully reset.";
        header("Location: login.html");
    } else {
        echo "Failed to reset password. Please try again.";
    }
}
?>
