<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default.css">
    <link rel="stylesheet" href="styles/gallery.css">
    <script src="scripts/gallery.js"></script>
    <title>Lomy Amerika - Gallery</title>
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <main>
        <?php
            require_once 'db.php';
            GetAllGaleriesCards($conn);
        ?>
        
    </main>
    <footer>©Patrik Kolář</footer>

</body>
</html>