<?php
session_start();

include '../classes/Database.php';
include '../classes/User.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT topics.id, topics.title, topics.content, topics.date, topics.author_id, users.username 
        FROM topics 
        INNER JOIN users ON topics.author_id = users.id 
        ORDER BY topics.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1 id="forum-btn">Forum</h1>
    <br>
    <?php include 'navigation.php'; ?>
</header>

<main id="main">
    <section>
        <h2>Tematy z Forum</h2>
        <ul>
            <?php
            // Wyświetlanie tematów z forum
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li class=''>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
                    echo "<p>Opublikowane: " . htmlspecialchars($row['date']) . " przez " . htmlspecialchars($row['username']) . "</p>";
                    echo "<a href='./post_details.php?post_id=" . $row['id'] . "' class='btn'>Przejdź do szczegółów</a>";
                    echo "</li>";
                    echo "<br>";
                }
            } else {
                echo "Brak tematów do wyświetlenia.";
            }
            ?>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2024 Forum</p>
</footer>

<script src="../js/scripts.js"></script>
</body>
</html>
