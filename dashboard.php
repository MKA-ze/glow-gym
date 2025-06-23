<?php
session_start();
include 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['book'])) {
    $class_id = intval($_GET['book']);
    
    $spots_res = $conn->query("SELECT available_spots FROM classes WHERE id = $class_id");
    if ($spots_row = $spots_res->fetch_assoc()) {
        $total_spots = $spots_row['available_spots'];

        $booked_res = $conn->query("SELECT COUNT(*) as booked FROM bookings WHERE class_id = $class_id");
        $booked = $booked_res->fetch_assoc()['booked'];
        $remaining_spots = $total_spots - $booked;
        if ($remaining_spots > 0) {
            
            $already = $conn->query("SELECT id FROM bookings WHERE user_id = $user_id AND class_id = $class_id");
            if ($already->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO bookings (user_id, class_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $class_id);
                $stmt->execute();
                echo "<p style='color:green;'>Class booked!</p>";
            } else {
                echo "<p style='color:orange;'>You already booked this class.</p>";
            }
        } else {
            echo "<p style='color:red;'>Sorry, this class is full.</p>";
        }
    }
}

$res = $conn->query("SELECT * FROM classes ORDER BY class_date ASC");
echo "<h2>Available Classes</h2>";
echo "<div class='class-grid'>";
while ($row = $res->fetch_assoc()) {
    $class_id = $row['id'];
    $title = htmlspecialchars($row['title']);
    $date = htmlspecialchars($row['class_date']);
    $description = htmlspecialchars($row['description']);
    $price = isset($row['price']) ? number_format($row['price'],2) : 'N/A';
    $total_spots = isset($row['available_spots']) ? $row['available_spots'] : 0;

    $booked_res = $conn->query("SELECT COUNT(*) as booked FROM bookings WHERE class_id = $class_id");
    $booked = $booked_res->fetch_assoc()['booked'];
    $remaining_spots = $total_spots - $booked;

    echo "<div class='class-card'>
        <h3>$title</h3>
        <p><em>$description</em></p>
        <p>Date: $date</p>
        <p>Price: <b>\$$price</b></p>
        <p>Available spots: <b>";
    if ($remaining_spots > 0) {
        echo "$remaining_spots";
    } else {
        echo "<span class='full-msg'>Full</span>";
    }
    echo "</b></p>";

    if ($remaining_spots > 0) {
        echo "<a href='?book=$class_id' class='book-btn'>Book</a>";
    }
    echo "</div>";
}
echo "</div>";

echo "<div class='center'><a href='my-bookings.php' class='admin-btn'>My Bookings</a></div>";

include 'includes/footer.php';
?>
