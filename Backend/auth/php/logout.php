<?php
    require '../../game/php/destroy_session.php';
    if(!isset($_SESSION))
    {
        session_start();
    }
    session_destroy();
    $_SESSION = [];
    header("Location: ../../../public/index.php");
?>