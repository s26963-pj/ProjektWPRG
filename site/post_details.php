<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Post.php';

$db = new Database();
$conn = $db->getConnection();

$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$post_id) {
    echo "Post ID not provided.";
    exit;
}

// Pobranie danych posta
$stmt = $conn->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $post_data = $result->fetch_assoc();
    $post = new Post($post_data['id'], $conn, $post_data['date'], $post_data['author_id'], $post_data['title'], $post_data['content']);
} else {
    echo "Post not found.";
    exit;
}

// Dodawanie komentarza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if ($user_id) {
        $comment = $_POST['comment'];
        $stmt = $conn->prepare("INSERT INTO comments (topic_id, author_id, content, date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $post_id, $user_id, $comment);
        if ($stmt->execute()) {
            header("Location: post_details.php?post_id=$post_id");
            exit();
        } else {
            echo "Error adding comment: " . $conn->error;
        }
    } else {
        echo "You need to be logged in to add comments.";
    }
}

// Pobranie komentarzy
$comments = [];
$stmt = $conn->prepare("SELECT c.content, c.date, u.username FROM comments c JOIN users u ON c.author_id = u.id WHERE c.topic_id = ? ORDER BY c.date DESC");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post->getTitle()); ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<header>
    <h1 id="forum-btn">Forum</h1>
    <br>
    <?php include 'navigation.php'; ?> <!-- Dodanie nawigacji -->
</header>

<main id="main">
    <section>
        <h1><?php echo htmlspecialchars($post->getTitle()); ?></h1>
        <p><small>Posted on: <?php echo $post->getDate(); ?></small></p>
        <br><br>
        <p><?php echo htmlspecialchars($post->getText()); ?></p>

        <!-- Edytowanie posta przez właściciela -->
        <?php if ($user_id == $post->getOwner()): ?>
            <a href="edit_posts.php?post_id=<?php echo $post->getId(); ?>"><button class="btn">Edytuj wpis</button></a>
        <?php endif; ?>

        <!-- Sekcja komentarzy -->
        <div class="comments mt-5">
            <br><br><br>
            <h3>Komentarze</h3>
            <?php if ($user_id): ?>
                <form action="post_details.php?post_id=<?php echo $post_id; ?>" method="post">
                    <div class="form-group">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj komentarz</button>
                </form>
                <br>
            <?php else: ?>
                <p>You need to be logged in to add comments.</p>
            <?php endif; ?>

            <?php foreach ($comments as $comment): ?>
                <div class="comment mt-3">
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    <p><small><?php echo htmlspecialchars($comment['username']); ?> - <?php echo $comment['date']; ?></small></p>
                    <br>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Forum</p>
</footer>
<script src="../js/scripts.js"></script>
</body>
</html>
