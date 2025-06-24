<!DOCTYPE html>
<html>
<head>
    <title>Glow Gym</title>
    <link rel="stylesheet" href="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '../' : ''; ?>assets/css/style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Glow Gym</h1>
        <?php
        $currentFile = basename($_SERVER['PHP_SELF']);

        if ($currentFile !== 'index.php') {
            echo '<a href="javascript:history.back()" class="back-link">Back</a>';
        }

        if ($currentFile === 'index.php') {
            echo '<a href="register.php" class="header-register-link">Register here</a>';
        }

        if (isset($_SESSION['user_id'])) {
            $logoutPath = (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '../logout.php' : 'logout.php';
            echo '<a href="' . $logoutPath . '" class="logout-link">Logout</a>';
        }
        ?>
    </div>
    <main>
