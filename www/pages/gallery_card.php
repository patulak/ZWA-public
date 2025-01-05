
<div class="gallery">
    <h1><?php echo htmlspecialchars($title) ?></h1>
    <div class="container">
        <?php
        for($j=0; $j<count($sources); $j++){
            echo "<button onclick=\"enlargeImg(this)\"><img alt=\"" . htmlspecialchars($title) . "\" src=\"" . htmlspecialchars($sources[$j]['path_to_img']) . "\"></button>";
        }
        ?>
    </div>
    <p><?php echo htmlspecialchars($description) ?></p>
</div>