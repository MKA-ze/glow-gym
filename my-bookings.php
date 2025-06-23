<?php
session_start();
include 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle cancel action (POST)
if (isset($_POST['cancel_booking']) && isset($_POST['cancel_booking_id'])) {
    $cancel_id = intval($_POST['cancel_booking_id']);
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cancel_id, $user_id);
    $stmt->execute();
    echo "<p style='color:green;text-align:center;'>Booking cancelled.</p>";
}

// Query all the user's bookings, including class info
$sql = "SELECT bookings.id, classes.title, classes.class_date, classes.description, classes.price
        FROM bookings 
        JOIN classes ON bookings.class_id = classes.id 
        WHERE bookings.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>My Bookings</h2>";

if ($result->num_rows === 0) {
    echo "<p style='text-align:center; font-style:italic; color:gray; font-size:20px;'>You have no booked classes yet.</p>";
} else {
    echo "<div class='class-grid'>";
    while ($row = $result->fetch_assoc()) {
        $booking_id = $row['id'];
        $title = htmlspecialchars($row['title']);
        $date = htmlspecialchars($row['class_date']);
        $description = isset($row['description']) ? htmlspecialchars($row['description']) : '';
        $price = isset($row['price']) ? number_format($row['price'], 2) : 'N/A';

        echo "<div class='class-card'>
            <h3>$title</h3>
            <p><em>$description</em></p>
            <p>Date: $date</p>
            <p>Price: <b>\$$price</b></p>
            <form method='post' action=''>
                <input type='hidden' name='cancel_booking_id' value='$booking_id'>
                <input type='submit' name='cancel_booking' value='Cancel' class='cancel-btn'>
            </form>
        </div>";
    }
    echo "</div>";
}

include 'includes/footer.php';
?>
