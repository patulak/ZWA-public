<?php
$required = "moderator";
require_once "protected.php";
require_once 'db.php';

if (isset($_GET['gallery_id']) && is_numeric($_GET['gallery_id'])) {
    $gallery_id = intval($_GET['gallery_id']);

    $sql = "SELECT title, description_gal FROM gallery WHERE gallery_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $gallery_id);
        $stmt->execute();
        $stmt->bind_result($title, $description);

        if ($stmt->fetch()) {
            echo json_encode([
                'title' => $title,
                'description' => $description
            ]);
        } else {
            echo json_encode([
                'error' => 'Gallery not found.'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'error' => 'Database query failed.'
        ]);
    }
} else {
    echo json_encode([
        'error' => 'Invalid or missing gallery ID.'
    ]);
}

exit;
?>