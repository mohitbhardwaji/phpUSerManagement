<?php 
include '../common/db.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already registered!";
        } else {
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $res = $stmt->execute([$name, $email, $password]);

            if ($res) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed!";
            }
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .register-container {
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

        .register-container h2 {
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

        .register-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-links a {
            color: #6366F1;
            text-decoration: none;
        }

        .register-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
    <p class="register-links">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
