<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'jammu';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (mysqli_sql_exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) { // Note: This is for demonstration purposes only; use hashing in real applications
            $_SESSION['email'] = $user['email'];
            echo 'Login successful. Welcome ' . $user['email'];
            // Redirect to a protected page or dashboard
            // header("Location: dashboard.php");
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo 'No user found with that email address.';
    }
}
?>

