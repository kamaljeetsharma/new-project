<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <h2>Update Profile</h2>
    <?php
    session_start();
  ////print_r( $_SESSION['email']); //array ki specific value dega
  //print_r( $_SESSION);   ///arravy

    if (!isset($_SESSION['email'])) {
        header("Location: login.html");
        exit();
    }

    // Include the Database class and establish connection
     // Adjust the path as per your directory structure
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
    $database = new Database();
    $db = $database->getConnection();

    $username = $_SESSION['username'];

    // Fetch current user details including hashed password
    $stmt = $db->prepare("SELECT name, email FROM customers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    $stmt->fetch();
    $stmt->close();
    ?>
    <form action="update.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php print_r($_SESSION['username']); ?>"><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php print_r($_SESSION['email']); ?>"><br>

        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password"><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" pattern="^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9].*[0-9]).{6,}$"
            title="Password must contain at least one uppercase letter, one special character, two digits, and be at least 6 characters long" required><br>
        <!-- Pattern attribute enforces password complexity rules -->

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>