<?php

    session_start();
    session_unset(); //clear saved data 
    session_destroy(); //destroy the session
    
    header('Location: index.php');
    exit();