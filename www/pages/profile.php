<?php
$required = "";
require_once "protected.php";
require_once 'db.php';

$err_color = "red";
$err_msg = "";

$last_user_r = "";
$last_name = "";
$last_role = "";

if (isset($_SESSION['last_user_r'])) {
    $last_user_r = $_SESSION['last_user_r'];
    unset($_SESSION['last_user_r']);
}

if (isset($_SESSION['last_role'])) {
    $last_role = $_SESSION['last_role'];
    unset($_SESSION['last_role']);
}

if (isset($_SESSION['last_name'])) {
    $last_name = $_SESSION['last_name'];
    unset($_SESSION['last_name']);
}

if (isset($_SESSION['err_msg'])) {
    $err_msg = $_SESSION['err_msg'];
    unset($_SESSION['err_msg']);
}

if (isset($_SESSION['err_color'])) {
    $err_color = $_SESSION['err_color'];
    unset($_SESSION['err_color']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pass_one'])) {
        $sql = "UPDATE logins SET password_hash = ? WHERE username = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", password_hash($_POST['pass_one'], PASSWORD_DEFAULT), $_SESSION['username']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['loged'] = false;
        header("Location: login");
        exit;
    }

    if (isset($_POST['logout'])) {
        $_SESSION['loged'] = false;
        header("Location: login");
        exit;
    }

    if (isset($_POST['register_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fullname = $_POST['fullname'];
        $role = $_POST['role'];

        $_SESSION['last_user_r'] = $username;
        $_SESSION['last_name'] = $fullname;
        $_SESSION['last_role'] = $role;

        $sql = "SELECT username FROM logins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['err_msg'] = "Uživatelské jméno již existuje.";
            $_SESSION['err_color'] = "red";
            $stmt->close();
            header("Location: profile");
            exit;
        }
        $stmt->close();

        $sql = "INSERT INTO logins (username, password_hash, fullname, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $hashed_password, $fullname, $role);

        if ($stmt->execute()) {
            $_SESSION['err_msg'] = "Nový uživatel byl úspěšně vytvořen.";
            $_SESSION['err_color'] = "green";
            unset($_SESSION['last_user_r']);
        } else {
            $_SESSION['err_msg'] = "Chyba při vytváření uživatele.";
            $_SESSION['err_color'] = "red";
        }

        $stmt->close();
        header("Location: profile");
        exit;
    }
}
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
        
        <form id="change_pass_form" action="profile" method="POST" autocomplete="off" class="form_green">
            <h2>Změna hesla</h2>
            <label for="password_one">Nové heslo</label>
            <input type="password" name="pass_one" id="password_one" placeholder="Heslo123" required>
            <label for="password_two">Potvrďte heslo</label>
            <input type="password" name="pass_two" id="password_two" placeholder="Heslo123" required>
            <span id="error_span"></span>
            <input type="submit" value="Potvrdit">
        </form>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <form id="register_form" action="profile" method="POST" autocomplete="off" class="form_green">
            <h2>Registrace nového uživatele</h2>
            <label for="username">Uživatelské jméno</label>
            <input type="text" value="<?php echo htmlspecialchars($last_user_r) ?>" name="username" id="username" placeholder="Nové uživatelské jméno" required value="<?php echo htmlspecialchars($last_user); ?>">

            <label for="password">Heslo</label>
            <input type="password" name="password" id="password" placeholder="Heslo" required>

            <label for="fullname">Celé jméno</label>
            <input type="text" value="<?php echo htmlspecialchars($last_name) ?>" name="fullname" id="fullname" placeholder="Celé jméno" required>

            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="guide" <?php echo ($last_role === 'guide') ? 'selected' : ''; ?>>Průvodce</option>
                <option value="admin" <?php echo ($last_role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="moderator" <?php echo ($last_role === 'moderator') ? 'selected' : ''; ?>>Moderátor</option>
            </select>


            <span style="color: <?php echo htmlspecialchars($err_color); ?>;"><?php echo htmlspecialchars($err_msg); ?></span>

            <input type="hidden" name="register_user" value="true">
            <button type="submit">Registrovat</button>
        </form>
        <?php endif; ?>
        
    </main>
    <footer>©Patrik Kolář</footer>
    <script src="scripts/profile.js"></script>
</body>
</html>