<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['user'])){
        header("Location: ../login.html");
    }
    else
    {
        if(!isset($_SESSION['admin'])){
            header("Location: ./not_authorized.php");
        }
    }
?>