// forgot_password.php
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


    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo "<form action='posttest.php' method='post'>";
        echo "<input type='hidden' name='email' value='" . htmlspecialchars($email) . "'>";
        echo "<label for='new_password'>Enter your new password:</label>";
        echo "<input type='password' id='new_password' name='new_password' required>";
        echo "<button type='submit'>Reset Password</button>";
        echo "</form>";
    } else {
        echo "Email address not found.";
    }
}
?>
