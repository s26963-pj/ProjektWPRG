<?php
session_start();

include '../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script language="javascript">alert("Aby utworzyc wątek musisz być zalogowany!")</script>';
    echo "<script>window.location.href = '../site/login.php'</script>";
    exit;
}

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    // Sprawdzenie, czy temat już istnieje
    $stmt = $conn->prepare("SELECT id FROM topics WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Temat o podanym tytule już istnieje.'); window.location.href='../site/create_topic.php';</script>";
    } else {
        // Wstawienie nowego tematu do bazy danych
        $stmt = $conn->prepare("INSERT INTO topics (title, content, date, author_id) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("ssi", $title, $content, $author_id);

        if ($stmt->execute()) {
            header("Location: ../site/index.php");
            exit;
        } else {
            echo "Błąd: " . $conn->error;
        }
    }
}
?>
