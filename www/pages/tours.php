<?php
$required = "moderator";
require_once "protected.php";
//variables to old forms data
$err_mess = "";
$err_color = "var(--c-text)";

$old_datetime = "";
$old_capacity = "";
$old_state = "";
$old_paid_status = "";
$old_pay_method = "";
$old_description = "";
$old_guides = "";

if (isset($_SESSION['old_datetime'])) {
    $old_datetime = $_SESSION['old_datetime'];
    unset($_SESSION['old_datetime']);
}

if (isset($_SESSION['old_capacity'])) {
    $old_capacity = $_SESSION['old_capacity'];
    unset($_SESSION['old_capacity']);
}

if (isset($_SESSION['old_state'])) {
    $old_state = $_SESSION['old_state'];
    unset($_SESSION['old_state']);
}

if (isset($_SESSION['old_paid_status'])) {
    $old_paid_status = $_SESSION['old_paid_status'];
    unset($_SESSION['old_paid_status']);
}

if (isset($_SESSION['old_pay_method'])) {
    $old_pay_method = $_SESSION['old_pay_method'];
    unset($_SESSION['old_pay_method']);
}

if (isset($_SESSION['old_description'])) {
    $old_description = $_SESSION['old_description'];
    unset($_SESSION['old_description']);
}

if (isset($_SESSION['old_guides'])) {
    $old_guides = $_SESSION['old_guides'];
    unset($_SESSION['old_guides']);
}

if (isset($_SESSION['err_color']) && isset($_SESSION['err_mess'])){
    $err_color = $_SESSION['err_color'];
    $err_mess = $_SESSION['err_mess'];
    unset($_SESSION['err_color']);
    unset($_SESSION['err_mess']);
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_type'])){
    $actionType = $_POST['action_type'];
    $tourDatetime = $_POST['tour_datetime'];
    $capacity = intval($_POST['capacity']);
    $state = $_POST['state'];
    $paymentStatus = $_POST['payment_status'];
    $paymentMethod = $_POST['payment_method'];
    $description = $_POST['description'];
    $numberOfGuides = intval($_POST['number_of_guides']);
    $tourId = isset($_POST['tour_id']) ? intval($_POST['tour_id']) : null;

    $_SESSION['old_datetime'] = $tourDatetime;
    $_SESSION['old_capacity'] = $capacity;
    $_SESSION['old_state'] = $state;
    $_SESSION['old_paid_status'] = $paymentStatus;
    $_SESSION['old_pay_method'] = $paymentMethod;
    $_SESSION['old_description'] = $description;
    $_SESSION['old_guides'] = $numberOfGuides;
    
    if (!is_numeric($numberOfGuides) || intval($numberOfGuides) < 0){
        $_SESSION['err_mess'] = "Počet průvodců nesmí být záporný";
        $_SESSION['err_color'] = "red";
        header("Location: tours?state=open");
        exit;
    }
    if (!is_numeric($capacity) || intval($capacity) < 0) {
        $_SESSION['err_mess'] = "Počet návštěvníků nesmí být záporný";
        $_SESSION['err_color'] = "red";
        header("Location: tours?state=open");
        exit;
    }

    }

    if ($actionType === 'add') { //handle form for adding a tour
        $sql = "INSERT INTO tours (tour_datetime, capacity, state, payment_status, payment_method, description, number_of_guides)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssi", $tourDatetime, $capacity, $state, $paymentStatus, $paymentMethod, $description, $numberOfGuides);

        if ($stmt->execute()) {
            $_SESSION['err_mess'] = "Nová prohlídka vytvořena";
            $_SESSION['err_color'] = "green";

            unset($_SESSION['old_datetime']);
            unset($_SESSION['old_capacity']);
            unset($_SESSION['old_state']);
            unset($_SESSION['old_paid_status']);
            unset($_SESSION['old_pay_method']);
            unset($_SESSION['old_description']);
            unset( $_SESSION['old_guides']);

            header("Location: tours?state=open");
            exit;

        } else {
            $_SESSION['err_mess'] = "Nepovedlo se prohlídku vytvořit";
            $_SESSION['err_color'] = "red";
            header("Location: tours?state=open");
            exit;
        }
        $stmt->close();

    } elseif ($actionType === 'edit' && $tourId !== null) { //handle a form for editing a tour
        $sql = "UPDATE tours 
                SET tour_datetime = ?, capacity = ?, state = ?, payment_status = ?, payment_method = ?, description = ?, number_of_guides = ? 
                WHERE tour_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssii", $tourDatetime, $capacity, $state, $paymentStatus, $paymentMethod, $description, $numberOfGuides, $tourId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['err_mess'] = "Prohlídka upravena";
            $_SESSION['err_color'] = "green";

            unset($_SESSION['old_datetime']);
            unset($_SESSION['old_capacity']);
            unset($_SESSION['old_state']);
            unset($_SESSION['old_paid_status']);
            unset($_SESSION['old_pay_method']);
            unset($_SESSION['old_description']);
            unset( $_SESSION['old_guides']);

            header("Location: tours?state=open");
            exit;
        } else {
            $_SESSION['err_mess'] = "Nepovedlo se prohlídku upravit";
            $_SESSION['err_color'] = "red";
            header("Location: tours?state=open");
            exit;
        }
        $stmt->close();

    } elseif(isset($_POST['remove'])) {
        $tour_id = $_POST['remove'];
        $sql = "DELETE FROM tours WHERE tour_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $tour_id);

            if ($stmt->execute()) {
                $_SESSION['err_mess'] = "Úspěšně smazáno.";
                $_SESSION['err_color'] = "green";
            } else {
                $_SESSION['err_mess'] = "Chyba mazání.";
                $_SESSION['err_color'] = "red";
            }

            $stmt->close();
        } else {
            $_SESSION['err_mess'] = "Chyba mazání.";
            $_SESSION['err_color'] = "red";
        }

        header("Location: tours");
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
    <link rel="stylesheet" href="styles/tours.css">
    <link rel="stylesheet" href="styles/form.css">
    <title>Lomy Amerika - Prohlídky</title>
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <main>
        <div id="grid-container">
            <table id="tours-grid">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Datum prohlídky</th>
                        <th>Obsazenost</th>
                        <th>Status</th>
                        <th>Platba</th>
                        <th>Typ platby</th>
                        <th>Popisek</th>
                        <th>Počet průvodců</th>
                        <th>Upraveno</th>
                        <th>Vytvořeno</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div id="pagination">

            </div>
            <button id="add-button">Přidat prohlídku</button>
            <form id="add-form" style="display: none;" method="POST" class="form_green" action="tours" autocomplete="on">
                <input id="id_tour_id" name="tour_id" type="hidden" value="<?php echo htmlspecialchars($old_tour_id ?? ''); ?>">

                <label for="id_tour_datetime">Datum a čas prohlídky</label>
                <input id="id_tour_datetime" type="datetime-local" required name="tour_datetime" value="<?php echo htmlspecialchars($old_datetime); ?>">

                <label for="id_capacity">Kapacita</label>
                <input id="id_capacity" type="number" name="capacity" required placeholder="Kapacita" min="0" value="<?php echo htmlspecialchars($old_capacity); ?>">

                <label for="id_state">Stav</label>
                <select id="id_state" name="state">
                    <option value="open" <?php echo ($old_state === 'open') ? 'selected' : ''; ?>>Otevřená</option>
                    <option value="closed" <?php echo ($old_state === 'closed') ? 'selected' : ''; ?>>Uzavřená</option>
                    <option value="canceled" <?php echo ($old_state === 'canceled') ? 'selected' : ''; ?>>Zrušeno</option>
                </select>

                <label for="id_payment_status">Status platby</label>
                <select id="id_payment_status" name="payment_status">
                    <option value="paid" <?php echo ($old_paid_status === 'paid') ? 'selected' : ''; ?>>Zaplaceno</option>
                    <option value="not_paid" <?php echo ($old_paid_status === 'not_paid') ? 'selected' : ''; ?>>Neplaceno</option>
                </select>

                <label for="id_payment_method">Způsob platby</label>
                <select id="id_payment_method" name="payment_method">
                    <option value="invoice" <?php echo ($old_pay_method === 'invoice') ? 'selected' : ''; ?>>Faktura</option>
                    <option value="cash" <?php echo ($old_pay_method === 'cash') ? 'selected' : ''; ?>>Hotově</option>
                </select>

                <label for="id_description">Popisek</label>
                <input id="id_description" type="text" name="description" placeholder="Popisek" value="<?php echo htmlspecialchars($old_description); ?>">

                <label for="id_number_of_guides">Počet průvodců</label>
                <input id="id_number_of_guides" required type="number" name="number_of_guides" placeholder="Počet průvodců" min="0" value="<?php echo htmlspecialchars($old_guides); ?>">

                <span style="color: <?php echo htmlspecialchars($err_color); ?>;"><?php echo htmlspecialchars($err_mess); ?></span>

                <button id="id_submit" type="submit">Přidat</button>
                <input id="id_action_type" type="hidden" name="action_type" value="add">
            </form>
        </div>
    </main>
    <footer>©Patrik Kolář</footer>
    <script src="scripts/tours.js"></script>
</body>

</html>