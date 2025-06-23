<?php
include 'config/db.php';

$registerMsg = ''; // Will hold our success or error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // For production use password_hash()

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $registerMsg = "<div class='success-msg'>Registered successfully. <a href='index.php'>Login here</a></div>";
    } else {
        $registerMsg = "<div class='error-msg'>Error: " . $conn->error . "</div>";
    }
}
include 'includes/header.php';
?>
<form method="POST" class="register-form">
    <h2>Create Your Account</h2>
    Name: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
</form>
<?php
// Show the message right below the form
if (!empty($registerMsg)) {
    echo $registerMsg;
}
include 'includes/footer.php';
?>
