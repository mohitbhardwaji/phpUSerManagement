<?php include 'common/db.php'; ?>
<?php 
include 'common/db.php';
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: /pages/admin.php");
    } else {
        header("Location: /pages/dashboard.php"); 
    }
    exit();
}
$stmt = $conn->prepare("SELECT name, photo FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-wrapper {
            margin-top: 80px;
        }

        .carousel-box {
            width: 80%;
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .carousel-area {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            transition: transform 1s ease-in-out;
        }

        .carousel-index {
            width: 250px;
            height: 400px;
            transition: 0.5s;
            position: relative;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .carousel-index img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
            transition: 0.5s ease-in-out;
            border-radius: 15px;
        }

        .carousel-index.active img {
            filter: none;
            transform: scale(1.1);
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: grey;
            color: white;
            border: none;
            font-size: 24px;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
        }
        .left-arrow { left: 10px; }
        .right-arrow { right: 20px; }

        .left-arrow:hover{
            background:#d4a373;
        }
        .right-arrow:hover{
            background:#d4a373;
        }

         .user-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            background: white;
        }

        .user-card:hover {
            transform: scale(1.05);
        }

        .user-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

    </style>
</head>
<body>
<?php include 'common/sidebar.php'; ?>
<?php include 'common/navbar.php'; ?>


<div class="carousel-wrapper">
    <div class="carousel-box">
        <button class="arrow left-arrow" onclick="moveLeft()">&#8592;</button>
        <div class="carousel-area">
            <div class="carousel-index"><img src="https://img.freepik.com/free-photo/vertical-shot-water-stream-middle-cliffs-waterfall-distance_181624-2388.jpg?t=st=1741278081~exp=1741281681~hmac=74b1984c24fef1b13b2e9d13e45aed06dcbc24ceae8a415f2f0d9f80c4333f6c&w=1060" alt="1"></div>
            <div class="carousel-index"><img src="https://img.freepik.com/free-photo/beautiful-waterfall-streaming-into-river-surrounded-by-greens_181624-46318.jpg?t=st=1741278311~exp=1741281911~hmac=c7ada9c097ac7209c79da19bc8730a974fc96bf5829c2dcc706e35b6ee70f7bd&w=1060" alt="2"></div>
            <div class="carousel-index active"><img src="https://img.freepik.com/free-photo/misurina-sunset_181624-34793.jpg?t=st=1741278201~exp=1741281801~hmac=f38aca03865de1cb9a006d989b96c919ce8dc701fdb153ffdd7d6f6acad8d72d&w=2000" alt="3"></div>
            <div class="carousel-index"><img src="https://img.freepik.com/free-photo/vertical-shot-river-surrounded-by-mountains-meadows-scotland_181624-27881.jpg?t=st=1741278418~exp=1741282018~hmac=7797c3d14289ca8d1bf5828ce68d3ff0492d2a242da17c74bacdbd916b2d415f&w=996" alt="4"></div>
            <div class="carousel-index"><img src="https://img.freepik.com/free-photo/vertical-high-angle-shot-valbona-valley-national-park-clear-blue-sky-albania_181624-46470.jpg?t=st=1741278455~exp=1741282055~hmac=e63d7bc436f81a6d0e06dceb93ec67ec41602acb71a85264831cdbf4302e6a06&w=996" alt="5"></div>
        </div>
        <button class="arrow right-arrow" onclick="moveRight()">&#8594;</button>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Users List</h2>
    <div class="row">
        <?php foreach ($users as $user): ?>
            <div class="col-md-3 mb-4">
                <div class="user-card p-3 shadow">
                <img src="assets/images/profile_pics/<?php echo htmlspecialchars($user['photo']) ?: 'default.jpg'; ?>" 
                        alt="User Image" class="user-img" 
                       >
                        <h5 class="mt-2"><?php echo htmlspecialchars($user['name']); ?></h5>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'common/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const track = document.querySelector('.carousel-area');
    let items = document.querySelectorAll('.carousel-index');

    function moveRight() {
        track.style.transition = "transform 0.8s ease-in-out";
        track.style.transform = `translateX(-${items[0].clientWidth + 20}px)`;

        setTimeout(() => {
            track.appendChild(items[0]);
            track.style.transition = "none";
            track.style.transform = "translateX(0)";

            updateActiveImage();
        }, 800);
    }

    function moveLeft() {
        track.style.transition = "none";
        track.insertBefore(items[items.length - 1], items[0]); 
        track.style.transform = `translateX(-${items[0].clientWidth + 20}px)`;

        setTimeout(() => {
            track.style.transition = "transform 0.8s ease-in-out";
            track.style.transform = "translateX(0)";

            updateActiveImage();
        }, 50);
    }

    function updateActiveImage() {
        items = document.querySelectorAll('.carousel-index');
        items.forEach((item, index) => item.classList.remove("active"));
        items[2].classList.add("active"); 
    }

    setInterval(moveRight, 6000); 
</script>

</body>
</html>
