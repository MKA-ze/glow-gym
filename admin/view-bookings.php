<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['cancel_booking']) && isset($_POST['cancel_booking_id'])) {
    $booking_id = intval($_POST['cancel_booking_id']);
    $del = $conn->query("DELETE FROM bookings WHERE id = $booking_id");
    if ($del) {
        echo "<script>alert('Booking canceled successfully.');window.location.href='view-bookings.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error canceling booking.');</script>";
    }
}

$sql = "SELECT bookings.id, users.username, classes.title, classes.class_date, bookings.booking_date 
        FROM bookings
        JOIN users ON bookings.user_id = users.id
        JOIN classes ON bookings.class_id = classes.id
        ORDER BY bookings.booking_date DESC";

$res = $conn->query($sql);

echo "<h2>All Bookings</h2>";

if ($res->num_rows === 0) {
    echo "<p style='text-align:center; font-style:italic; color:gray; font-size:20px;'>No classes booked yet.</p>";
} else {
    echo "<div class='class-grid'>";
    while ($row = $res->fetch_assoc()) {
        echo "<div class='class-card'>
            <b>{$row['username']}</b> booked <i>{$row['title']}</i><br>
            on <b>{$row['class_date']}</b><br>
            <span style='font-size:0.98em;color:#6a3574;'>Booked: {$row['booking_date']}</span>
            <form method='post' action='' style='margin-top:10px;'>
                <input type='hidden' name='cancel_booking_id' value='{$row['id']}'>
                <input type='submit' name='cancel_booking' value='Cancel' class='cancel-btn'>
            </form>
        </div>";
    }
    echo "</div>";
}

include '../includes/footer.php';
?>
