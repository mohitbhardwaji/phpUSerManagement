<?php 
include '../common/db.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
        
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "role" => $user['role']
                ];
                if($user['role'] == "admin"){
                    header("Location: admin.php");
                    exit();
                }
                else{
                    header("Location: dashboard.php");
                    exit();
                }
               
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1E293B; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 2px, transparent 2px);
            background-size: 20px 20px; 
            z-index: 0;
        }

        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .login-container h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            background-color: #E5E7EB;
            border: none;
            height: 50px;
        }

        .btn-primary {
            width: 100%;
            background-color: #3B82F6;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #2563EB;
        }

        .login-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-links a {
            color: #6366F1;
            text-decoration: none;
        }

        .login-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="login-links">
        <a href="forgot_password.php">Forgot Password?</a>
    </p>
    <p class="login-links">Don't have an account? <a href="register.php">Sign up</a></p>
</div>

</body>
</html>
