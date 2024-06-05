<?php
session_start();

include '../classes/Database.php';
include '../classes/User.php';

$db = new Database();
$conn = $db->getConnection();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get('error');
            if (errorMessage) {
                alert(errorMessage);
            }
        }
    </script>
</head>
<body>
<header>
    <h1 id="forum-btn">Forum</h1>
    <br>
    <?php include 'navigation.php'; ?>
</header>

<main id="main">
    <section>
        <h2>Zaloguj się</h2>
        <form action="../processes/login_process.php" method="post">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <input type="submit" name="login" value="Zaloguj się">
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2024 Forum</p>
</footer>

<script src="../js/scripts.js"></script>
</body>
</html>
