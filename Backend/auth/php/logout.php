<?php
    require '../game/php/destroy_session.php';
    session_start();
    session_destroy();
    $_SESSION = [];
    exit();
?>