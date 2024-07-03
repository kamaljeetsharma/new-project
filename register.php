<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jammu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $new_password = $conn->real_escape_string($_POST['new_password']);
    // var_dump($name);
    // var_dump($email);
    // var_dump($username);
    // var_dump($new_password);

    $checkSql = "SELECT * FROM customers WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Email or Username already exists.";
    }
    $stmt->close();

    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $insertSql = "INSERT INTO customers(name, email, username, password) VALUES (?, ?, ?, ?)";
        //var_dump($name,$email,$password,$username);
        // die($hashed_password);

        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssss", $name, $email, $username, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            echo "Registration successful";
            header("Location:login.html");
        } else {
            echo "Error: Registration failed " . $insertSql . "<br>" . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>