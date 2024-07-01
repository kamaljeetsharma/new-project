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
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Reset Password</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }

                form {
                    background-color: #fff;
                    margin: 50px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    width: 300px;
                }

                label {
                    display: block;
                    margin-bottom: 10px;
                    color: #555;
                }

                input[type="password"] {
                    width: calc(100% - 22px);
                    padding: 10px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }

                button[type="submit"] {
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    cursor: pointer;
                }

                button[type="submit"]:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <h2>Reset Password</h2>
            <form action='posttest.php' method='post'>
                <input type='hidden' name='email' value='<?php echo htmlspecialchars($email); ?>'>
                <label for="password"> enternew Password:</label>
        <input type="password" id="new_password" name="new_password" pattern="^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9].*[0-9]).{6,}$" title="Password must contain at least one uppercase letter, one special character, two digits, and be at least 6 characters long"
            required><br>
    
                <button type='submit'>Reset Password</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Email address not found.";
    }
}
?>
