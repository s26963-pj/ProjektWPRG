<?php
session_start();
require_once '../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script language="javascript">alert("Aby zobaczyć profil, musisz być zalogowany.")</script>';
    echo "<script>window.location.href = '../site/register.php'</script>";
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$description = isset($user['description']) ? $user['description'] : '';
?>

<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h1>Twój profil</h1>
    <br><br>
    <form action="../processes/update_profile_process.php" method="POST">
        <div class="form-group">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="description">Opis:</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
