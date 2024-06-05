<?php

session_start();
require_once '../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script language="javascript">alert("Aby edytować profil trzeba być zalogowanym")</script>';
    echo "<script>window.location.href = '../site/register.php'</script>";
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $description, $user_id);

    if ($stmt->execute()) {
        echo '<script language="javascript">alert("Profil zaktualizowany pomyślnie")</script>';
        echo "<script>window.location.href = '../site/profile.php'</script>";
    } else {
        echo "Wystąpił błąd podczas aktualizacji profilu: " . $conn->error;
    }
}


?>
