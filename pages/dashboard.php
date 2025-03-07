<?php
include '../common/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Fetching user details with category and subcategory
$stmt = $conn->prepare("
    SELECT users.*, 
           categories.name AS category_name, 
           subcategories.name AS subcategory_name 
    FROM users
    LEFT JOIN categories ON users.category_id = categories.id
    LEFT JOIN subcategories ON users.subcategory_id = subcategories.id
    WHERE users.id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .card {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            
        }
    </style>
</head>
<body>
    <?php include '../common/navbar.php'; ?>
    <?php include '../common/sidebar.php'; ?>

    <div class="container mt-5">
        <div class="card">
            <h2 class="card-title">Welcome, <?php echo htmlspecialchars($userData['name']); ?></h2>
            <img src="../assets/images/profile_pics/<?php echo htmlspecialchars($userData['photo'] ?: 'default.jpg'); ?>" alt="Profile Pic" class="profile-img">
            <div class="card-body">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($userData['category_name'] ?? 'Not Assigned'); ?></p>
                <p><strong>Subcategory:</strong> <?php echo htmlspecialchars($userData['subcategory_name'] ?? 'Not Assigned'); ?></p>

                <div class="">
                    <a href="update_profile.php" class="btn btn-primary btn-custom">Update Profile</a>
                    <a href="../logout.php" class="btn btn-danger btn-custom">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../common/footer.php'; ?>
</body>
</html>
