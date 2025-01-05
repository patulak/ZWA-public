<?php
$required = "admin";
require_once "protected.php";
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//error variables
$card_err = "";
$gallery_err = "";
$image_err = "";
$card_color = "var(--c-text)";
$gallery_color = "var(--c-text)";
$image_color = "var(--c-text)";

$card_title_old = "";
$card_text_old = "";


//unseting for one use only
if (isset($_SESSION['card_err']) && isset($_SESSION['card_color'])){
    $card_err = $_SESSION['card_err'];
    $card_color = $_SESSION['card_color'];
    unset($_SESSION['card_err']);
    unset($_SESSION['card_color']);
}
if (isset($_SESSION['gallery_err']) && isset($_SESSION['gallery_color'])){
    $gallery_err = $_SESSION['gallery_err'];
    $gallery_color = $_SESSION['gallery_color'];
    unset($_SESSION['gallery_err']);
    unset($_SESSION['gallery_color']);
}
if (isset($_SESSION['image_err']) && isset($_SESSION['image_color'])){
    $image_err = $_SESSION['image_err'];
    $image_color = $_SESSION['image_color'];
    unset($_SESSION['image_err']);
    unset($_SESSION['image_color']);
}
if (isset($_SESSION['card_title_old'])){
    $card_title_old = $_SESSION['card_title_old'];
    unset($_SESSION['card_title_old']);
}
if (isset($_SESSION['card_text_old'])){
    $card_text_old = $_SESSION['card_text_old'];
    unset($_SESSION['card_text_old']);
}


$uploadDir = __DIR__ . "/../sources/uploads/";
$fileSizeLimit = 5000000; //5mb

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['add_image'])){ //add image form handle
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $finalPath = "";
            if (isset($_FILES['images']) && $_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $failed = false;

                $fileTmpName = $_FILES['images']['tmp_name'][$key];
                $fileName = basename($_FILES['images']['name'][$key]);
                $fileSize = $_FILES['images']['size'][$key];
                $filePath = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $finalPath = $uploadDir . $filePath;

                if ($fileSize < $fileSizeLimit){
                    if (move_uploaded_file($fileTmpName, $finalPath)) {
                        $gallery_id = htmlspecialchars(trim($_POST['gallery']));

                        $sql = "INSERT INTO img_paths (gallery_id, path_to_img) VALUES (?, ?);";
                        $stmt = $conn->prepare($sql);
                        $path_to_img = "/sources/uploads/" . $filePath;
                        $stmt->bind_param("is", $gallery_id, $path_to_img);
                        $stmt->execute();
                        if ($stmt->store_result()){
                            $_SESSION['image_err'] = "Obrázek se nahrál úspěšně";
                            $_SESSION['image_color'] = "green";
                        }
                        else{
                            $_SESSION['image_err'] = "Obrázek se nenašel";
                            $_SESSION['image_color'] = "red";
                            $failed = true;
                        }
                        $stmt->close();
                    }
                    else {
                        $_SESSION['image_err'] = "Chyba manipulace s obrázkem";
                        $_SESSION['image_color'] = "red";
                        $failed = true;
                    }
                }
                else {
                    $_SESSION['image_err'] = "Obrázek je příliž veliký (max 5MB)";
                    $_SESSION['image_color'] = "red";
                    $failed = true;
                }
            }
            else {
                $_SESSION['image_err'] = "Chyba nahrávání";
                $_SESSION['image_color'] = "red";
                $failed = true;
            }
            if ($failed){
                if (file_exists($finalPath)) {
                    unlink($finalPath);
                }
            }
        }
        header("Location: admin?side=gallery");
        exit;
    }
    if (isset($_POST['image_remove_id'])){ //remove image for handle
        $path_to_remove = $_POST['image_remove_id'];
        $sql = "DELETE FROM img_paths WHERE (path_to_img = ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $path_to_remove);
        $stmt->execute();
        $stmt->close();
        header("Location: admin?side=gallery");
        exit;
    }
    if (isset($_POST['card_remove_id'])){ //remove card handle
        $id_to_remove = $_POST['card_remove_id'];
        $sql = "DELETE FROM cards WHERE (card_id = ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_to_remove);
        $stmt->execute();
        $stmt->close();
        header("Location: admin");
        exit;
    }
    if (isset($_POST['add_gallery'])){ //add gallery handle
        $title = $_POST['gallery_title'];
        $description = $_POST['gallery_desc'];
        
        if (empty($title)){
            $_SESSION['card_err'] = "Titulek nesmí být prázdný";
            $_SESSION['card_color'] = "red";
            header("Location: admin?side=gallery");
            exit;
        }

        $sql = "INSERT INTO gallery (description_gal, title) VALUES (?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $description, $title);
        $stmt->execute();
        if ($stmt->store_result()){
            $_SESSION['gallery_err'] = "Galerie vytvořena";
            $_SESSION['gallery_color'] = "green";
        }
        else{
            $_SESSION['gallery_err'] = "Neplatná data pro vytvoření";
            $_SESSION['gallery_color'] = "red";
        }
        $stmt->close();
        header("Location: admin?side=gallery");
        exit;
    }
    if (isset($_POST['remove_gallery']) && isset($_POST['gallery_out'])) {
        $gallery_id = intval($_POST['gallery_out']);
    
        $conn->begin_transaction();
    
        try {
            $sql = "SELECT path_to_img FROM img_paths WHERE gallery_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $gallery_id);
            $stmt->execute();
            $stmt->bind_result($path_to_img);
    
            $image_paths = [];
            while ($stmt->fetch()) {
                $image_paths[] = $path_to_img;
            }
            $stmt->close();
    
            foreach ($image_paths as $image_path) {
                $full_path = __DIR__ . "/.." . $image_path;
                if (file_exists($full_path)) {
                    unlink($full_path);
                }
            }
    
            $sql = "DELETE FROM img_paths WHERE gallery_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $gallery_id);
            $stmt->execute();
            $stmt->close();
    
            $sql = "DELETE FROM gallery WHERE gallery_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $gallery_id);
            $stmt->execute();
            $stmt->close();
            $conn->commit();
    
            $_SESSION['err_mess'] = "Galerie byla úspěšně odstraněna spolu s jejími obrázky.";
            $_SESSION['err_color'] = "green";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['err_mess'] = "Chyba při odstraňování galerie: " . $e->getMessage();
            $_SESSION['err_color'] = "red";
        }
    
        header("Location: admin?side=gallery");
        exit;
    }
    if (isset($_POST['add_card'])){ //add card handle
        $title = $_POST['card_title'];
        $text = $_POST['card_text'];
        $_SESSION['card_title_old'] = $title;
        $_SESSION['card_text_old'] = $text;
        $failed = false;

        if (empty($title)){
            $_SESSION['card_err'] = "Titulek nesmí být prázdný";
            $_SESSION['card_color'] = "red";
            $failed = true;
        }

        $finalPath = "";
        if (!$failed && isset($_FILES['card_image']) && $_FILES['card_image']['error'] === UPLOAD_ERR_OK){
            $fileTmpName = $_FILES['card_image']['tmp_name'];
                $fileName = basename($_FILES['card_image']['name']);
                $fileSize = $_FILES['card_image']['size'];
                $filePath = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $finalPath = $uploadDir . $filePath;

                if ($fileSize < $fileSizeLimit){
                    if (move_uploaded_file($fileTmpName, $finalPath)) {
                        $sql = "INSERT INTO cards (img_path, title, text_card) VALUES (?, ?, ?);";
                        $stmt = $conn->prepare($sql);
                        $path_to_img = "/sources/uploads/" . $filePath;
                        $stmt->bind_param("sss", $path_to_img, $title, $text);
                        $stmt->execute();
                        if ($stmt->store_result()){
                            $_SESSION['card_err'] = "Příspěvek vytvořen";
                            $_SESSION['card_color'] = "green";
                            unset($_SESSION['card_title_old']);
                            unset($_SESSION['card_text_old']);
                        }
                        else{
                            $_SESSION['card_err'] = "Obrázek se nenašel";
                            $_SESSION['card_color'] = "red";
                            $failed = true;
                        }
                        $stmt->close();
                    }
                    else {
                        $_SESSION['card_err'] = "Chyba manipulace s obrázkem";
                        $_SESSION['card_color'] = "red";
                        $failed = true;
                    }
                }
                else {
                    $_SESSION['card_err'] = "Obrázek je příliž veliký (max 5MB)";
                    $_SESSION['card_color'] = "red";
                    $failed = true;
                }
        }
        else {
            $_SESSION['card_err'] = "Chyba nahrávání";
            $_SESSION['card_color'] = "red";
            $failed = true;
        }
        if ($failed){
            if (file_exists($finalPath)) {
                unlink($finalPath);
            }
        }
    }
    header("Location: admin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/form.css">
    <title>Lomy Amerika - Správa Webu</title>
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <?php require_once 'db.php'; ?>
    <main>
        <div class="blockCenter">
            <button id="swapForms">Příspěvky</button>
        </div>
        <div class="center switchable switchedOff">
            <div class="left">
                <div class="vertical">
                <form id="add_images_form" class="form_green" action="admin" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <h2>Přidat fotky do Galerie</h2>    
                    <label for="gallery_in">Galerie</label>
                    <select id="gallery_in" name="gallery" onchange="loadGallery(this.value)">
                        <?php GetAllGaleriesOption($conn) ?>
                    </select>
                    <label for="img_in">Obrázky</label>
                    <input id="img_in" type="file" name="images[]" accept=".jpg, .jpeg, .png" multiple>

                    <input type="hidden" name="add_image" value="true">
                    <span style="color: <?php echo htmlspecialchars($image_color);?>;"><?php echo htmlspecialchars($image_err);?></span>
                    <input type="submit" value="Potvrdit">
                </form>

                <form id="add_gallery_form" class="form_green" action="admin" method="POST" autocomplete="off">
                    <h2>Přidat novou Galerii</h2>      
                    <label for="gallery_title_in">Název nové galerie</label>
                    <input type="text" name="gallery_title" required id="gallery_title_in" placeholder="Nová galerie">
                    <label for="gallery_desc_in">Popisek</label>
                    <input type="text" name="gallery_desc" id="gallery_desc_in" placeholder="Popisek nové galerie">

                    <input type="hidden" name="add_gallery" value="true">
                    <span style="color: <?php echo htmlspecialchars($gallery_color);?>;"><?php echo htmlspecialchars($gallery_err);?></span>
                    <input type="submit" value="Potvrdit">
                </form>

                <form id="remove_gallery_form" class="form_green" action="admin" method="POST" autocomplete="off">
                    <h2>Smazat Galerii</h2>    
                    <label for="gallery_out">Galerie</label>
                    <select id="gallery_out" name="gallery_out" onchange="loadGallery(this.value)">
                        <?php GetAllGaleriesOption($conn) ?>
                    </select>
                    <input type="hidden" name="remove_gallery" value="true">
                    <input type="submit" value="Potvrdit">
                </form>
                </div>

                
            </div>
            <div id="images_container">

            </div>
        </div>
        <div class="blockCenter switchable center" id="cardsMenu">
            <div class="left">
            <form id="add_card_form" class="form_green" action="admin" method="POST" autocomplete="off" enctype="multipart/form-data">
                <h2>Přidat příspěvek</h2>
                <label for="title_in">Titulek</label>
                <input type="text" id="title_in" name="card_title" required value="<?php echo htmlspecialchars($card_title_old) ?>" placeholder="Nový příspěvek">
                <label for="text_in">Text</label>
                <textarea id="text_in" name="card_text" style="height: 5rem;" value="<?php echo htmlspecialchars($card_text_old) ?>" placeholder="Text pod novým příspěvkem"></textarea>
                <label for="img_in_card">Obrázky</label>
                <input id="img_in_card" type="file" name="card_image" accept=".jpg, .jpeg, .png">

                <input type="hidden" name="add_card" value="true">
                <span style="color: <?php echo htmlspecialchars($card_color);?>;"><?php echo htmlspecialchars($card_err);?></span>
                <input type="submit" value="Potvrdit">
            </form>
            </div>
            <ul id="cards_container">
                <h2>Smazat příspěvek</h2>
                
            </ul>
        </div>
    </main>
    <footer>©Patrik Kolář</footer>
    <script src="scripts/admin.js"></script>
</body>

</html>


