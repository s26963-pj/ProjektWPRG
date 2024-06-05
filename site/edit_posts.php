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
    if ($post_data['author_id'] != $user_id) {
        echo "You are not authorized to edit this post.";
        exit;
    }
    $post = new Post($post_data['id'], $conn, $post_data['date'], $post_data['author_id'], $post_data['title'], $post_data['content']);
} else {
    echo "Post not found.";
    exit;
}

// Aktualizacja posta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $text = $_POST['text'];

    if (!empty($title) && !empty($text)) {
        $stmt = $conn->prepare("UPDATE topics SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $text, $post_id);
        if ($stmt->execute()) {
            header("Location: post_details.php?post_id=$post_id");
        } else {
            echo "Error updating post: " . $conn->error;
        }
    } else {
        echo "Title and content cannot be empty.";
    }
}
?>

<?php include '../templates/header.php'; ?>

<section>
    <div class="container mt-5">
        <h1>Edit Post</h1>
        <form action="edit_posts.php?post_id=<?php echo $post_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post->getTitle()); ?>">
            </div>
            <div class="form-group">
                <label for="text">Content</label>
                <textarea class="form-control" id="text" name="text" rows="5"><?php echo htmlspecialchars($post->getText()); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</section>

<?php include '../templates/footer.php'; ?>
