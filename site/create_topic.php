<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script language="javascript">alert("Aby utworzyc wątek musisz być zalogowany!")</script>';
    echo "<script>window.location.href = '../site/login.php'</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utwórz temat</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1 id="forum-btn">Forum</h1>
    <br>
    <?php include './navigation.php'; ?>
</header>

<main id="main">
    <section>
        <h2>Nowy temat</h2>
        <form action="../processes/create_post.php" method="POST">
            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Treść:</label>
            <textarea id="content" name="content" required></textarea>

            <input type="submit" value="Utwórz temat">
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2024 Forum</p>
</footer>
<script src="../js/scripts.js"></script>
</body>
</html>
