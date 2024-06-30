// reset_password.php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jammu";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Your password has been successfully reset.";
    } else {
        echo "Failed to reset password. Please try again.";
    }
}
?>
