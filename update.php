<?php
session_start();

// Check if user is logged in
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
$stmt = $db->prepare("SELECT name, email, password FROM customers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email, $hashed_password);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email)) {
        echo "Name and email fields cannot be empty.";
        exit();
    }

    // Verify old password
    if (!empty($old_password)) {
        if (password_verify($old_password, $hashed_password)) {
            // Old password verification successful
            if (!empty($new_password) && !empty($confirm_password)) {
                // Validate new password strength
                if (!preg_match("/^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9].*[0-9]).{6,}$/", $new_password)) {
                    echo "New password must contain at least one uppercase letter, one special character, two digits, and be at least 6 characters long.";
                    exit();
                }

                // Check if new passwords match
                if ($new_password !== $confirm_password) {
                    echo "New passwords do not match.";
                    exit();
                }

                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Prepare the SQL update statement with new password
                $stmt = $db->prepare("UPDATE customers SET name = ?, email = ?, password = ? WHERE username = ?");
                $stmt->bind_param("ssss", $name, $email, $hashed_new_password, $username);
            } else {
                // Prepare the SQL update statement without changing password
                $stmt = $db->prepare("UPDATE customers SET name = ?, email = ? WHERE username = ?");
                $stmt->bind_param("sss", $name, $email, $username);
            }

            // Execute the update statement
            if ($stmt->execute()) {
                echo "Profile updated successfully.";
            } else {
                echo "Error updating profile: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Incorrect old password.";
        }
    } else {
        echo "Please enter your old password for verification.";
    }

    $db->close();
}
?>