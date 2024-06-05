<nav>
    <ul>
        <li><a href="index.php">Strona główna</a></li>
        <li><a href="create_topic.php">Utwórz temat</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php">Mój profil</a></li>
            <li><a href="../processes/logout.php">Wyloguj się</a></li>
        <?php else: ?>
            <li><a href="login.php">Zaloguj się</a></li>
            <li><a href="register.php">Zarejestruj się</a></li>
        <?php endif; ?>
    </ul>
</nav>
