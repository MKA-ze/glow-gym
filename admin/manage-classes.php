<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle adding a class
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['class_date'];
    $price = $_POST['price'];
    $spots = $_POST['available_spots'];

    $stmt = $conn->prepare("INSERT INTO classes (title, description, class_date, price, available_spots) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdi", $title, $desc, $date, $price, $spots);
    $stmt->execute();
    echo "<p>Class added.</p>";
}

// Handle deleting a class
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM classes WHERE id = $id");
    echo "<p>Class deleted.</p>";
}

echo "<h2>Manage Classes</h2>";

echo "<form method='POST'>
    Title: <input type='text' name='title' required><br>
    Description: <textarea name='description'></textarea><br>
    Date: <input type='date' name='class_date' required><br>
    Price: <input type='number' name='price' min='0' step='0.01' required><br>
    Available Spots: <input type='number' name='available_spots' min='0' required><br>
    <button type='submit'>Add Class</button>
</form>";

$res = $conn->query("SELECT * FROM classes ORDER BY class_date ASC");
echo "<h3>All Classes</h3><ul>";
while ($row = $res->fetch_assoc()) {
    echo "<li>{$row['title']} ({$row['class_date']}) - \$" . number_format($row['price'], 2) . " | Spots: {$row['available_spots']} <a href='?delete={$row['id']}'>Delete</a></li>";
}
echo "</ul>";

include '../includes/footer.php';
?>
