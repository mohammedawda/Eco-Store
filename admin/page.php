<?php
    
    //splite categories to reach to her pages by get request 

    $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';
    
    if($do == 'Manage'){
        echo 'welcome in Manage page ';
        echo '<a href="?do=Add">Add New Category</a>';
    }
    
    elseif($do == 'Add'){
        echo 'welcome in Add page';
    }

    elseif($do == 'Insert'){
        echo 'welcome in Insert page';
    }

    else{
        echo 'there is no page with this name';
    }