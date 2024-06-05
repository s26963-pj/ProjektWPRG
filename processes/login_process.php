<?php
session_start();

include '../classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../site/index.php");
            exit;
        } else {
            echo '<script language="javascript">alert("Nieprawidlowe haslo")</script>';
            echo "<script>window.location.href = '../site/login.php'</script>";
            exit;
        }
    } else {
        echo '<script language="javascript">alert("Nieprawidlowe haslo")</script>';
        echo "<script>window.location.href = '../site/login.php'</script>";
        exit;
    }
}
?>
