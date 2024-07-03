<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'jammu';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // Enable MySQLi exception mode for proper exception handling
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            return $this->conn;
        } catch (mysqli_sql_exception $exception) {
            throw new Exception("Database connection error: " . $exception->getMessage());
        }
    }
}

// Start or resume a session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email format and non-empty password
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        try {
            // Create database instance and establish connection
            $database = new Database();
            $db = $database->getConnection();

            if ($db) {
                // Prepare SQL statement to fetch user by email
                $stmt = $db->prepare("SELECT * FROM customers WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Fetch user data
                    $user = $result->fetch_assoc();
                    $stored_password = $user['password'];

                    // Verify password
                    if (password_verify($password, $stored_password)) {
                        // Password is correct, set session and redirect
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['username'] = $username;
                        echo 'Login successful. Welcome ' . htmlspecialchars($user['email']);

                        // Redirect to a protected page or dashboard
                        header("Location: index.html");
                        exit(); // Important: terminate script after redirect
                    } else {
                        echo 'Invalid password.';
                    }
                } else {
                    echo 'No user found with that email address.';
                }

                $stmt->close();
            } else {
                echo 'Database connection failed.';
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Invalid email or password.';
    }
}
?>
