<?php session_start(); ?>

<style>
    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        top: 0;
        right: 0;
        background-color: #333;
        overflow-x: hidden;
        transition: 0.3s;
        padding-top: 60px;
        z-index: 50;
    }
    .sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: white;
        display: block;
        transition: 0.2s;
    }
    .sidebar a:hover {
        background-color: #575757;
    }
    .sidebar .closebtn {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 24px;
    }
</style>

<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">×</a>
    <a href="../index.php">Home</a>

    <?php if (isset($_SESSION['user'])): ?>
        <a href="../logout.php">Logout</a>
    <?php else: ?>
        <a href="pages/login.php">Login</a>
    <?php endif; ?>
</div>

<script>
    function openSidebar() {
        document.getElementById("mySidebar").style.width = "250px";
    }
    function closeSidebar() {
        document.getElementById("mySidebar").style.width = "0";
    }
</script>
