<?php   
    
    /////////////////connection to db////////////////
    include 'connect.php';

    /////////////////routes//////////////////////////
    $lang = 'includes/languages/';                //langauge directory
    $tpl  = 'includes/templates/';               //template directory   
    $func = 'includes/functions/';
    $css  = 'layout/css/';                      //css directory
    $js   = 'layout/js/';                      //js directory


    /////////////////include important files/////////
    include $func."functions.php";
    include $lang."english.php";
    include $tpl.'header.php';
    if(!isset($noNavbar)){include $tpl.'navbar.php';}