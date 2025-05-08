<?php 
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "GeatasyaMySQL29.";
$dbname = "buku_tamu";
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;

            $log = "$username berhasil login pada " . date("Y-m-d H:i:s") . "\n";
            file_put_contents("log_login.txt", $log, FILE_APPEND);
            
            header("Location: buku_tamu.php");
            exit();
        }
    }
    
    $errorMessage = "Username atau password salah. Silakan coba lagi.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Buku Tamu</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            display: flex;
            width: 800px;
            background: #1e1e1e;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .login-image {
            flex: 1;
            background: #bb86fc;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-image img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.5));
        }

        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: #bb86fc;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #333;
            border-radius: 8px;
            background: #2d2d2d;
            font-size: 14px;
            color: #e0e0e0;
            box-sizing: border-box;
        }

        input::placeholder {
            color: #888;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #bb86fc;
            border: none;
            color: #121212;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #9d65d0;
            color: #fff;
        }

        .error {
            color: #ff6e6e;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 90%;
            }
            
            .login-image {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-image">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Login Image">
        </div>
        <div class="login-form">
            <h2>User Login</h2>
            
            <?php if (isset($errorMessage)) { echo "<p class='error'>$errorMessage</p>"; } ?>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
