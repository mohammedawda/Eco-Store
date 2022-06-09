<?php

    /*============================
    == Template Page
    ============================*/

    ob_start(); //=>search
    
    session_start();
    
    //to print page title
    $pageTitle = '';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        //manage pages by the variable do
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';

        //start manage page if variable do has no value from get request
        if($do == 'Manage'){

        }

        //start add page if variable do has a value(Add) from get request
        elseif($do == 'Add'){

        }
        
        //insert page that get info from add page and add them to db
        elseif($do == 'Insert'){

        }

        //start edit page if variable do has a value(Edit) from get request
        elseif($do == 'Edit'){

         }

        //start Update page if variable do has a value(Update) from get request
        elseif($do == 'Update'){

        }

        //start delete page if variable do has a value(Delete) from get request
        elseif($do == 'Delete'){

        }

        //start ACtivate page if variable do has a value(Activate) from get request
        elseif($do == 'Activate'){
            
        }

        include $tpl.'footer.php';
    }
    
    else{
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
?>