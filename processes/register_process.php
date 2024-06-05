<?php
session_start();

include '../classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Sprawdź, czy hasła są identyczne
    if($password !== $confirm_password) {
        echo '<script language="javascript">alert("Hasla nie sa identyczne")</script>';
        echo "<script>window.location.href = '../site/register.php'</script>";
        exit;
    }

    // Sprawdź, czy użytkownik o podanej nazwie już istnieje
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        echo '<script language="javascript">alert("Uzytkownik o podanej nazwie juz istnieje")</script>';
        echo "<script>window.location.href = '../site/register.php'</script>";
        exit;
    }

    // Zapisz dane użytkownika do bazy danych
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if($stmt->execute()) {
        echo "Rejestracja zakończona sukcesem.";
    } else {
        echo "Wystąpił błąd podczas rejestracji.";
    }
}
?>
