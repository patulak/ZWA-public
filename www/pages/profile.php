<?php
$required = "";
require_once "protected.php";
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/form.css">
    <title>Lomy Amerika - Profil</title>
    
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <?php require_once 'db.php'; ?>
    <main>
        <div class="info">
        <span>Jméno: <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
        <br>
        <span>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></span>
        </div>

        <form class="form_green" action="profile" method="POST">
            <input type="hidden" name="logout">
            <button type="submit">Odhlásit se</button>
        </form>
        <br>
        <form id="change_pass_form" action="profile" method="POST" autocomplete="off" class="form_green">
            <h2>Změna hesla</h2>
            <label for="password_one">Nové heslo</label>
            <input type="password" name="pass_one" id="password_one" placeholder="Heslo123" required>
            <label for="password_two">Potvrďte heslo</label>
            <input type="password" name="pass_two" id="password_two" placeholder="Heslo123" required>
            <span id="error_span"></span>
            <input type="submit" value="Potvrdit">
        </form>

        
    </main>
    <footer>©Patrik Kolář</footer>
    <script src="scripts/profile.js"></script>
</body>


</html>

<?php
//handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pass_one'])){
        $sql = "UPDATE logins SET password_hash = ? WHERE username = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", password_hash($_POST['pass_one'], PASSWORD_DEFAULT), $_SESSION['username']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['loged'] = false;
        header("Location: login");
        exit;
    }
    if (isset($_POST['logout'])){
        $_SESSION['loged'] = false;
        header("Location: login");
    }
}

?>