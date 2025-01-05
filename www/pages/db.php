<?php 

/*
define("MYSQL_HOST", "127.0.0.1");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "Kolac123");
define("MYSQL_DB", "website");
*/


define("MYSQL_HOST", "db.dw141.webglobe.com");
define("MYSQL_USER", "hagen-morina.1");
define("MYSQL_PASS", "Kolac123");
define("MYSQL_DB", "hagenmorinacz1");



$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);


/**
 * select data for all cards, and use card.php to print
 *
 * @param  mixed $conn
 * @return void
 */
function GetAllCards($conn) { 
    $sql = "SELECT card_id, datetime_card, img_path, title, text_card FROM cards ORDER BY datetime_card DESC;";
    $result = mysqli_query($conn, $sql);
    if ($result != null){
        $rows = mysqli_num_rows($result);
        $data = mysqli_fetch_all($result, PDO::PARAM_INT);

        for($i=0; $i<$rows; $i++){
            $text = $data[$i]['text_card'];
            $img_path = $data[$i]['img_path'];
            $title = $data[$i]['title'];
            include 'card.php';
        }
    }
    else{
        echo "ERROR loading Cards!";
    }
}

/**
 * select data for all galeries, and use gallery_card.php to print
 *
 * @param  mixed $conn
 * @return void
 */
function GetAllGaleriesCards($conn){
    $sql = "SELECT gallery_id, description_gal, title, datetime_gal FROM gallery ORDER BY gallery_id ASC;";
    $result = mysqli_query($conn, $sql);
    if ($result != null){
        $rows = mysqli_num_rows($result);
        $data = mysqli_fetch_all($result, PDO::PARAM_INT);

        for($i=0; $i<$rows; $i++){
            $gallery_id = $data[$i]['gallery_id'];
            $sql2 = "SELECT path_to_img FROM img_paths WHERE (gallery_id =" . $gallery_id . ") ORDER BY path_to_img ASC;";
            $result2 = mysqli_query($conn, $sql2);
            if($result2 != null){
                $data2 = mysqli_fetch_all($result2, PDO::PARAM_INT);
                $title = $data[$i]['title'];
                $description = $data[$i]['description_gal'];
                $sources = $data2;
                include 'gallery_card.php';
            }
        }
    }
    else{
        echo "ERROR loading Galeries!";
    }
}

/**
 * get all ganeries to fill options in select
 *
 * @param  mixed $conn
 * @return void
 */
function GetAllGaleriesOption($conn){
    $sql = "SELECT gallery_id, title FROM gallery ORDER BY datetime_gal ASC;";
    $result = mysqli_query($conn, $sql);
    if ($result != null){
        $rows = mysqli_num_rows($result);
        $data = mysqli_fetch_all($result, PDO::PARAM_INT);

        for($i=0; $i<$rows; $i++){
            $gallery_id = $data[$i]['gallery_id'];
            $title = $data[$i]['title'];
            echo "<option value=\"" . htmlspecialchars($gallery_id) . "\">" . htmlspecialchars($title) . "</option>";
        }
    }
}