<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

echo "<h2>Admin Dashboard</h2>";
echo "<div class='admin-btn-group'>
    <a href='manage-classes.php' class='admin-btn'>Manage Classes</a>
    <a href='view-bookings.php' class='admin-btn'>View Bookings</a>
</div>";

include '../includes/footer.php';
?>
