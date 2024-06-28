<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "jammu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $username = $conn->real_escape_string($_POST["username"]);
    $password = password_hash($conn->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
