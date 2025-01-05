<?php
$required = "moderator";
require_once "protected.php";
require_once 'db.php';

if (isset($_GET['gallery'])) { //get image with button to remove
    $gallery_id = $_GET['gallery'];
    $sql = "SELECT path_to_img FROM img_paths WHERE gallery_id = ? ORDER BY path_to_img ASC;";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $stmt->bind_result($path_to_img);

    while ($stmt->fetch()) {
        echo '
            <form action="admin" method="POST" onsubmit="return confirmDelete()">
                <input type="hidden" name="image_remove_id" value="' . htmlspecialchars($path_to_img) . '">
                <button type="submit" name="delete_image">
                    <img src="' . htmlspecialchars($path_to_img) . '" alt="Delete Image" style="width:100px;height:auto;">
                </button>
            </form>
        ';
    }
    $stmt->close();
}

if(isset($_GET['cards'])){ //get card with option to delete
    $sql = "SELECT card_id, title FROM cards;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($id, $title);

    while ($stmt->fetch()) {
        echo '
            <form action="admin" method="POST" onsubmit="return confirmDelete()">
                <input type="hidden" name="card_remove_id" value="' . htmlspecialchars($id) . '">
                <button type="submit" name="delete_item">
                    ' . htmlspecialchars($title) . '
                </button>
            </form>
        ';
    }
    $stmt->close();
}
?>