<?php

    /*=====================================================================
    ==get_title function v1.0
    ==get_title function that prints the page title
    ==if it has a variable called pageTitle otherwise, prints default value
    =====================================================================*/

    function get_title(){
        global $pageTitle;

        if(isset($pageTitle)){
            echo $pageTitle;
        }
        else{
            echo 'Default';
        }
    }

    /*==============================================================================
    ==redirect_home function v2.0
    ==redirect_home function that prints erros messages
    ==accepts two parameters: --$Message => prints the error/success/warning message
                              --$url     => link you want to redirect
                              --$seconds => #of seconds before redirect
    ==============================================================================*/

    function redirect_home($errorMessage, $url = null, $seconds = 3){
        
        if($url === null){
            $url = 'index.php';
            $link = "Home Page";
        }

        else{
            
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
                $url = $_SERVER['HTTP_REFERER'];
                $link = "Previous Page";
            }
            
            else{
                $url = 'index.php';
                $link = "Home Page";
            }
        }

        echo $errorMessage;
        echo "<div class='alert alert-info'>You will be Redirected to $link after $seconds Seconds.</div>";
        header("refresh:$seconds;url=$url");
        exit();     
    }

     /*=======================================================================================
    ==check_item function v1.0
    ==check_item function that checks if the item exist in db before add it
    ==accepts three parameters: --$select => the item to select [example: user, item, category]
                                --$table   => the table to select from [example: users, items]
                                --$value  => the value of select
    ========================================================================================*/

    function check_item($select, $table, $value){
        global $con;
        $statement = $con->prepare("SELECT $select FROM $table WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();

        return $count;
    }

    /*========================================================================
    ==count_items function v1.0
    ==count_items function that counts #of item rows
    ==accepts two parameters: --$item => the item to count
                                --$table   => the table that contain the item
    ========================================================================*/

    function count_items($item, $table){
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();
    }

    /*========================================================================
    ==get_latest function v1.0
    ==get_latest function that get latest item from database
    ==accepts two parameters: --$select => the item to select [example: user, item, category]
                              --$table  => the table to select from [example: users, items]
                              --$order  => the column that we will order by it
                              --$limit  => #of records to get
    ========================================================================*/

    function get_latest($select, $table, $order, $limit = 5){
        global $con;
        if($table == "users"){
            $add = 'WHERE GroupID != 1';
        }
        else{
            $add = '';
        }
        $getStmt = $con->prepare("SELECT $select FROM $table $add ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();
        $rows = $getStmt->fetchAll();

        return $rows;
    }