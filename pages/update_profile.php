<?php
session_start();
include '../common/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";

$stmt = $conn->prepare("SELECT name, photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $photo_name = $user['photo'];

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "../assets/images/profile_pics/";

        // Ensure the directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create if not exists
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $photoExt = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($photoExt, $allowedTypes)) {
            $photo_name = time() . "_" . basename($_FILES['photo']['name']);
            $targetFile = $uploadDir . $photo_name;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $stmt = $conn->prepare("UPDATE users SET name = ?, photo = ? WHERE id = ?");
                if ($stmt->execute([$name, $photo_name, $user_id])) {
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['photo'] = $photo_name;
                    $message = '<div class="alert alert-success">Profile updated successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Failed to update profile!</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">Error uploading file. Check permissions.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Invalid file type. Only JPG, PNG, and GIF are allowed.</div>';
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        if ($stmt->execute([$name, $user_id])) {
            $_SESSION['user']['name'] = $name;
            $message = '<div class="alert alert-success">Profile updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to update profile!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <?php include '../common/navbar.php'; ?>
    <?php include '../common/sidebar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Update Profile</h2>
                <?php echo $message; ?>
                <div class="card p-4 shadow">
                    <div class="text-center">
                        <img src="../assets/images/profile_pics/<?php echo htmlspecialchars($user['photo'] ?: 'default.png'); ?>" alt="Profile Pic" class="profile-img" >
                    </div>

                    <form method="POST" enctype="multipart/form-data" class="mt-3">
                        <div class="mb-3">
                            <label class="form-label">Name:</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Picture:</label>
                            <input type="file" name="photo" class="form-control">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../common/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
