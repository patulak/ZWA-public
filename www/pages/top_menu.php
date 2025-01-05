<link rel="stylesheet" href="styles/top_menu.css">
<img src="sources/LogoImg.png" class="top_img" alt="Lomy amerika">
<nav class="top_menu">
    <button class="burger-menu" onclick="toggleMenu()">☰</button>
    <ul>
        <li><a href="home">Domů</a></li>
        <!--<li><a href="order">Objednávky</a></li>-->
        <li><a href="gallery">Galerie</a></li>
        <li><a href="about">O nás</a></li>
        <li><a href="contact">Kontakt</a></li>
        <li><a href="login">Pro Průvodce</a></li>
        <?php
        if (session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }

        if (isset($_SESSION['loged']) && $_SESSION['loged'] == true){
            echo "<li><a href=\"profile\">Profil</a></li>";
            echo "<li><a href=\"calendar\">Kalendář</a></li>";
            if($_SESSION['role'] == "moderator" || $_SESSION['role'] == "admin"){
                echo "<li><a href=\"tours\">Prohlídky</a></li>";
            }
            if($_SESSION['role'] == "admin"){
                echo "<li><a href=\"admin\">Správa Webu</a></li>";
            }
        }
        ?>
    </ul>
</nav>
<script src="scripts/top_menu.js"></script>