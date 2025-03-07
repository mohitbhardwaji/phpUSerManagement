<?php 
include '../common/db.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Here, we would generate a token and send a reset link via email or we can use otp system as well for that need to store the otp in db .
            // for that we will require smtp connection or will need an email trhough which i will be triggering the mail
            // For now, we'll just display a success message.
            $success = "A password reset link has been sent to your email.";
        } else {
            $error = "Email not found!";
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
    <title>Forgot Password</title>
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

        .forgot-container {
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

        .forgot-container h2 {
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

        .forgot-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-links a {
            color: #6366F1;
            text-decoration: none;
        }

        .forgot-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="forgot-container">
    <h2>Forgot Password</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
    <p class="forgot-links"><a href="login.php">Back to Login</a></p>
</div>

</body>
</html>
