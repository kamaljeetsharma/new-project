<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jammu";
 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    // Prepare and bind
    $stmt = $conn->prepare("SELECT  email, password, username FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
 
    $stmt->execute();       //    Email           | password  | username   |
                           // 999..@gmail.com      xxxxowe      1234
    $stmt->bind_result($email2, $hashed_password,$username2);
 
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Password is correct, start a new session
        $_SESSION['email'] = $email2;
        $_SESSION['username'] = $username2;
        header("Location: index.php");
        exit();
    } 
    else {
        // Invalid credentials
        echo "Invalid email or password.";
    }
 
    $stmt->close();
}
$conn->close();
?>


