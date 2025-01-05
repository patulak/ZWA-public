<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default.css">
    <link rel="stylesheet" href="styles/home.css">
    <title>Lomy Amerika - Home</title>
</head>
<body>
    <header></header>
    <?php include 'top_menu.php'; ?> <!-- NAVIGATION -->
    <main>
        <?php
            require_once 'db.php';
            GetAllCards($conn);
        ?>
        
    </main>
    <footer>©Patrik Kolář</footer>

</body>
</html>
