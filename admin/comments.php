<?php

    /*
    ===============================================
    == Manage comments page
    == Approve | Edit | Delete comments from here
    ===============================================
    */

    session_start();
    $pageTitle = 'Comments';
    
    if(isset($_SESSION['Username'])){
        include 'init.php';
        
        //manage pages by the variable do
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';
        
        
        //start manage page if variable do has no value from get request
        if($do == 'Manage'){
            
        $stmt = $con->prepare("SELECT comments.*, users.Username, items.Name AS Item_name
                                FROM comments
                                INNER JOIN users
                                ON users.UserID = comments.user_id
                                INNER JOIN items
                                ON items.item_ID = comments.item_id 
                                ORDER BY c_id DESC");
        $stmt->execute();
        $comments = $stmt->fetchAll();   

        if(!empty($comments)){
            echo '<h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Item Name</td>
                            <td>Comment</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>'; 
                        
                        //get comment info form db
                        foreach($comments as $comment){
                            echo "<tr>";
                                echo "<td>" . $comment['c_id'] . "</td>"; 
                                echo "<td>" . $comment['Username'] . "</td>";
                                echo "<td>" . $comment['Item_name'] . "</td>"; 
                                echo "<td>" . $comment['comment'] . "</td>";
                                echo "<td>" . $comment['comment_date'] . "</td>";
                                echo "<td>
                                        <a href='comments.php?do=Edit&comid=". $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                        <a href='comments.php?do=Delete&comid=". $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                        if($comment['status'] == 0){
                                            echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                        }
                                echo "</td>";
                            echo "<tr>";
                        }
                        echo 
                    '</table>
                </div>    
            </div>';
        }
        else{
            echo '<div class="container">';
                echo '<div class="alert-message">There\'s no ' . $pageTitle .  ' to show</div>';
            echo '</div>';
        }
    }

        //start edit page if variable do has a value(Edit) from get request
        elseif($do == 'Edit'){ 
            
            //validate userid to be found and numeric value
            $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0 ;
            
            //search in db about the record that has the previous id
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
            $stmt->execute(array($comid));
            
            //get the record from db and but it into an associative array
            $row = $stmt->fetch();  
            
            //count variable have a value (1) if there is a record in the db with the previous id
            $count = $stmt->rowCount();

            //if there a record in db show the edit form
            if($count > 0){?>
            
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <!--start comment field-->
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid;?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea class="form-control" name="comment"><?php echo $row['comment'];?></textarea>
                        </div>
                    </div>
                    <!--end comment field-->
                    
                    <!--start button field-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="Save"/>
                        </div>
                    </div>
                    <!--end button field-->
                </form>
            </div>
            <?php 
            }
            
            //if no record in db show an error message
            else{
                echo "<div class='container'>";
                
                $errorMessage = '<div class="alert alert-danger">there is no such ID</div>';
                redirect_home($errorMessage); 
                
                echo "</div>";
            }
        }

        //start Update page if variable do has a value(Update) from get request
        elseif($do == 'Update'){

            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";
           
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
                //get the new info from the edit form
                $comid     = $_POST['comid'];
                $comment   = $_POST['comment'];
                
                
            //update the db
            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment, $comid));
        
            //print success message
            $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>' ;
            redirect_home($errorMessage, "Back"); 
            }
            else{  
                $errorMessage = "<div class='alert alert-danger'>Sorry you cannot browse this page directly</div>"; 
                redirect_home($errorMessage);
            }
            echo "</div>";
        }

        //start delete page if variable do has a value(Delete) from get request
        elseif($do == 'Delete'){
            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

           //validate comid to be found and numeric value
           $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("c_id", "comments", $comid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcomment");
                
                //assign comid variable to zcomment parameter
                $stmt->bindParam(":zcomment", $comid);

                //execute delete query
                $stmt->execute();

                $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirect_home($errorMessage, "Back");
           }
           else{
               $errorMessage = "<div class='alert alert-danger'>this id is not found</div>";
               redirect_home($errorMessage);
           }

           echo "</div>";
        }

        //start Approve page if variable do has a value(Approve) from get request
        elseif($do = 'Approve'){
            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";

           //validate userid to be found and numeric value
           $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("c_id", "comments", $comid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

                //execute delete query
                $stmt->execute(array($comid));

                $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Comment Approved</div>';
                redirect_home($errorMessage, "Back");
           }
           else{
               $errorMessage = "<div class='alert alert-danger'>this id is not found</div>";
               redirect_home($errorMessage);
           }

           echo "</div>";
        }

        include $tpl.'footer.php';
    }
    
    else{
        header('Location: index.php');
        exit();
    }
?>