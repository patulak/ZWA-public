<?php

$timeout = 21600; //6h
//check if user is allowed
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}
if (isset($_SESSION['loged']) && $_SESSION['loged'] == true){
    if (time() - $_SESSION['last_action'] > $timeout){
        $_SESSION['loged'] = false;
        header("Location: login");
    }
    if ($required == "admin" && $_SESSION['role'] != "admin"){
        header("Location: profile");
    }
    if ($required == "moderator" && ($_SESSION['role'] != "admin" && $_SESSION['role'] != "moderator")){
        header("Location: profile");
    }
}
else{
    header("Location: login");
}
$_SESSION['last_action'] = time();
?>