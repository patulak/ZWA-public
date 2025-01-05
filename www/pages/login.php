<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

//variables for old values
$user_err = "var(--c-text)";
$pass_err = "var(--c-text)";
$err_msg = "";
$err_color = "var(--c-text)";
$last_user = "";
if (isset($_SESSION['last_user'])){
    $last_user = $_SESSION['last_user'];
    unset($_SESSION['last_user']);
}

if (isset($_SESSION['error_type']) && isset($_SESSION['error_msg'])) {
    switch ($_SESSION['error_type']) {
        case 'wrong_user':
            $user_err = "red";
            $err_color = "red";
            break;
        case 'wrong_pass':
            $pass_err = "red";
            $err_color = "red";
            break;
        case 'empty_user':
            $user_err = "orange";
            $err_color = "orange";
            break;
        case 'empty_pass':
            $pass_err = "orange";
            $err_color = "orange";
            break;
    }
    $err_msg = $_SESSION['error_msg'];

    unset($_SESSION['error_type']);
    unset($_SESSION['error_msg']);
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default.css">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="styles/form.css">
    <title>Lomy Amerika - Přihlášení</title>
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <?php if(!isset($_SESSION['loged']) || (isset($_SESSION['loged']) && $_SESSION['loged'] == false)): ?>
    <main>
        <form id="login" action="login" method="POST" autocomplete="on" class="form_green">
            <h2>Přihlášení</h2>
            <label for="username_in" style="color: <?php echo htmlspecialchars($user_err);?>;">Uživatelské jméno</label>
            <input id="username_in" type="text" name="username" required autocomplete="on" placeholder="Jméno" value="<?php echo htmlspecialchars($last_user) ?>">
            
            <label for="password_in" style="color: <?php echo htmlspecialchars($pass_err);?>;">Heslo</label>
            <input id="password_in" type="password" name="password" placeholder="Heslo123" required>
            
            <span style="color: <?php echo htmlspecialchars($err_color);?>;"><?php echo htmlspecialchars($err_msg);?></span>
            <input type="submit" value="Potvrdit">
        </form>
    </main>
    <?php else:?>
    <main>
        <h1>Přihlášen jako <?php echo htmlspecialchars($_SESSION['fullname'])?></h1>
    </main>
    <?php endif;?>
    <footer>©Patrik Kolář</footer>
</body>
</html>

<?php
require_once 'db.php';

//handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $_SESSION['error_type'] = 'empty_user';
        $_SESSION['error_msg'] = "Vyplňte uživatele";
        header("Location: login");
        exit;
    } else if (empty($password)) {
        $_SESSION['last_user'] = $username;
        $_SESSION['error_type'] = 'empty_pass';
        $_SESSION['error_msg'] = "Vyplňte heslo";
        header("Location: login");
        exit;
    } else {
        $_SESSION['last_user'] = $username;
        $sql = "SELECT password_hash, fullname, role FROM logins WHERE username = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($dbs_hash, $fullname, $role);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($password, $dbs_hash)) {
                $_SESSION['loged'] = true;
                $_SESSION['last_action'] = time();
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['role'] = $role;
                unset($_SESSION['error_type']);
                unset($_SESSION['error_msg']);
                header("Location: profile");
                exit;
            } else {
                $_SESSION['error_type'] = 'wrong_pass';
                $_SESSION['error_msg'] = "Neplatné heslo";
                header("Location: login");
                exit;
            }
        } else {
            $_SESSION['error_type'] = 'wrong_user';
            $_SESSION['error_msg'] = "Neplatný uživatel";
            header("Location: login");
            exit;
        }
    }
}
?>
