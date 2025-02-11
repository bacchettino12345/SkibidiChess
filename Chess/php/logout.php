<?php
    session_start();
    session_destroy();
    $_SESSION = [];
    echo "SESSION DESTROYED";
    exit();
?>