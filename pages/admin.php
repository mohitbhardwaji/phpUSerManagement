<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit();
}

include '../common/db.php';

// Handle Category Creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $category_name]);
}

// Handle Subcategory Creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subcategory_name'], $_POST['parent_category'])) {
    $subcategory_name = $_POST['subcategory_name'];
    $parent_category = $_POST['parent_category'];
    $stmt = $conn->prepare("INSERT INTO subcategories (name, category_id) VALUES (:name, :category_id)");
    $stmt->execute(['name' => $subcategory_name, 'category_id' => $parent_category]);
}

// Handle User Category Assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['category_id'], $_POST['subcategory_id'])) {
    $user_id = $_POST['user_id'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $stmt = $conn->prepare("UPDATE users SET category_id = :category_id, subcategory_id = :subcategory_id WHERE id = :user_id");
    $stmt->execute(['category_id' => $category_id, 'subcategory_id' => $subcategory_id, 'user_id' => $user_id]);
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $conn->query("SELECT * FROM subcategories")->fetchAll(PDO::FETCH_ASSOC);
$users = $conn->query("SELECT id, name FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showSection(section) {
            document.getElementById("category-section").style.display = (section === 'category') ? 'block' : 'none';
            document.getElementById("subcategory-section").style.display = (section === 'subcategory') ? 'block' : 'none';
            document.getElementById("assign-section").style.display = (section === 'assign') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <?php include '../common/navbar.php'; ?>
    <?php include '../common/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Admin Panel</h2>
        <div class="btn-group mb-3">
            <button class="btn btn-primary" onclick="showSection('category')">Manage Categories</button>
            <button class="btn btn-secondary" onclick="showSection('subcategory')">Manage Subcategories</button>
            <button class="btn btn-success" onclick="showSection('assign')">Assign Users</button>
        </div>
        <div id="category-section">
            <h3>Create Category</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" class="form-control" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>

            <h3 class="mt-4">Categories List</h3>
            <ul class="list-group">
                <?php foreach ($categories as $cat) { ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($cat['name']); ?></li>
                <?php } ?>
            </ul>
        </div>
        <div id="subcategory-section" style="display: none;">
            <h3>Create Subcategory</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Subcategory Name</label>
                    <input type="text" class="form-control" name="subcategory_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Parent Category</label>
                    <select class="form-control" name="parent_category" required>
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Add Subcategory</button>
            </form>

            <h3 class="mt-4">Subcategories List</h3>
            <ul class="list-group">
                <?php foreach ($subcategories as $sub) { ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($sub['name']); ?></li>
                <?php } ?>
            </ul>
        </div>
        <div id="assign-section" style="display: none;">
            <h3>Assign Category & Subcategory to Users</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select User</label>
                    <select class="form-control" name="user_id" required>
                        <?php foreach ($users as $user) { ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <select class="form-control" name="category_id" required>
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Subcategory</label>
                    <select class="form-control" name="subcategory_id" required>
                        <?php foreach ($subcategories as $sub) { ?>
                            <option value="<?php echo $sub['id']; ?>"><?php echo htmlspecialchars($sub['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Assign</button>
            </form>
        </div>
    </div>

    <?php include '../common/footer.php'; ?>
</body>
</html>
