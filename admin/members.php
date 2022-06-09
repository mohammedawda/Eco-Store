<?php

    /*
    ==========================================
    == Manage members page
    == Add | Edit | Delete members from here
    ==========================================
    */

    session_start();
    $pageTitle = 'Members';
    
    if(isset($_SESSION['Username'])){
        include 'init.php';
        
        //manage pages by the variable do
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';
        
        
        //start manage page if variable do has no value from get request
        if($do == 'Manage'){

            //query variable help us to get pending users only in manage page 
            $query = '';
            if(isset($_GET['page']) && $_GET['page'] == "Pending"){
                $query = 'AND RegStatus = 0';
            }
            
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query  ORDER BY UserID DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
                echo '<h1 class="text-center">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Register Date</td>
                                <td>Control</td>
                            </tr>'; 
                            
                            //get user info form db
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['UserID'] . "</td>"; 
                                    echo "<td>" . $row['Username'] . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>"; 
                                    echo "<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . $row['Date'] . "</td>";
                                    echo "<td>
                                            <a href='members.php?do=Edit&userid=". $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                            <a href='members.php?do=Delete&userid=". $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                            if($row['RegStatus'] == 0){
                                                echo "<a href='members.php?do=Activate&userid=". $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                                            }
                                    echo "</td>";
                                echo "<tr>";
                            } 
                            
                            echo 
                        '</table>
                    </div>
                    <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>
                </div>';
                }
                else{
                    echo '<div class="container">';
                        echo '<div class="alert-message">There\'s no ' . $pageTitle .  ' to show</div>';
                        echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
                    echo '</div>';
                }
                
        }

        //start add page if variable do has a value(Add) from get request
        elseif($do == 'Add'){?>
            
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <!--start username field-->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username" autocomplete="off" required="required" placeholder="Username"/>
                        </div>
                    </div>
                    <!--end username field-->
                    
                    <!--start password field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" class="password form-control" name="password" autocomplete="new-password" required="required" placeholder="Password"/>
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>            
                    <!--end password field-->
                    
                    <!--start email field-->            
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" name="email" required="required" placeholder="Email"/>
                        </div>
                    </div>             
                    <!--end email field-->
                    
                    <!--start full name field-->             
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="full" required="required" placeholder="Fullname"/>
                        </div>
                    </div>
                    <!--end full name field-->
                    
                    <!--start button field-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Member"/>
                        </div>
                    </div>
                    <!--end button field-->
                </form>
            </div>
            <?php 
        }
        
        
        //insert page that get info from add page and add them to db
        elseif($do == 'Insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";
                
                //get the new info from the edit form
                $user       = $_POST['username'];
                $pass       = $_POST['password'];
                $hashedPass = sha1($_POST['password']);
                $email      = $_POST['email'];
                $name       = $_POST['full'];
                
                //validate the form
                $formErrors = array();

                if(strlen($user) < 4){
                    $formErrors[] = 'Username Field can\'t be less than 4 character';
                }

                if(strlen($user) > 20){
                    $formErrors[] = 'Username Field can\'t be more than 20 character';
                }
                
                if(empty($user)){
                    $formErrors[] = 'Username Field can\'t be empty';
                }

                if(empty($pass)){
                    $formErrors[] = 'password Field can\'t be empty';
                }

                if(empty($email)){
                    $formErrors[] = 'Email Field can\'t be empty';
                }

                if(empty($name)){
                    $formErrors[] = 'Full Name Field can\'t be empty';
                }

                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>" ;
                }
                
                if(empty($formErrors)){

                    //check if user exist in db
                    $check = check_item("Username", "users", $user);
                    
                    if($check == 1){
                        $errorMessage = "<div class='alert alert-danger'>User is already exist</div>";
                        redirect_home($errorMessage, "Back");
                    }
                    
                    else{
                        //Insert into the db
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date) VALUES(:zuser, :zpass, :zmail, :zname, 1, now())");
                    $stmt->execute(array('zuser' => $user, 'zpass' => $hashedPass, 'zmail' => $email, 'zname' => $name)); 
                
                    //print success message
                    $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>' ;
                    redirect_home($errorMessage); 
                }
            }
        }
            
            else{
                
                echo "<div class='container'>";
                
                $errorMessage = '<div class="alert alert-danger">Sorry you cannot browse this page directly</div>';
                redirect_home($errorMessage, "Back"); 
                
                echo "</div>";
            }

            echo "</div>";
        }


        //start edit page if variable do has a value(Edit) from get request
        elseif($do == 'Edit'){ 
            
            //validate userid to be found and numeric value
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
            
            //search in db about the record that has the previous id
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));
            
            //get the record from db and but it into an associative array
            $row = $stmt->fetch();  
            
            //count variable have a value (1) if there is a record in the db with the previous id
            $count = $stmt->rowCount();

            //if there a record in db show the edit form
            if($count > 0){?>
            
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <!--start username field-->
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid;?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username" value="<?php echo $row['Username'];?>" autocomplete="off" required="required"/>
                        </div>
                    </div>
                    <!--end username field-->
                    
                    <!--start password field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'];?>"/>
                            <input type="password" class="form-control" name="newpassword" autocomplete="new-password"/>
                        </div>
                    </div>            
                    <!--end password field-->
                    
                    <!--start email field-->            
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" name="email" value = "<?php echo $row['Email'];?>" required="required"/>
                        </div>
                    </div>             
                    <!--end email field-->
                    
                    <!--start full name field-->             
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="full" value = "<?php echo $row['FullName'];?>" required="required"/>
                        </div>
                    </div>
                    <!--end full name field-->
                    
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

            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
           
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
                //get the new info from the edit form
                $id     = $_POST['userid'];
                $user   = $_POST['username'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                //password update
                $pass   = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : sha1($_POST['newpassword']);
                
                //validate the form
                $formErrors = array();
                if(empty($user)){
                    $formErrors[] = 'Username cannot be empty';
                }

                if(empty($email)){
                    $formErrors[] = 'Email cannot be empty';
                }

                if(empty($name)){
                    $formErrors[] = 'Full Name cannot be empty';
                }

                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>" ;
                }
                
                if(empty($formErrors)){

                    //check if the new info have a username exist or not
                    $statement = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $statement->execute(array($user, $id));
                    $count = $statement->rowCount();

                    if($count > 0){
                        $errorMessage = "<div class='alert alert-danger'>Sorry This Username is Already Exist</div>"; 
                        redirect_home($errorMessage, "Back");
                    }
                    else{
                        //update the db
                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user, $email, $name, $pass, $id));
                
                        //print success message
                        $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>' ;
                        redirect_home($errorMessage, "Back");
                    } 
                }
            }
            else{  
                $errorMessage = "<div class='alert alert-danger'>Sorry you cannot browse this page directly</div>"; 
                redirect_home($errorMessage);
            }
            echo "</div>";
        }

        //start delete page if variable do has a value(Delete) from get request
        elseif($do == 'Delete'){
            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

           //validate userid to be found and numeric value
           $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("userid", "users", $userid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                
                //assign userid variable to zuser parameter
                $stmt->bindParam(":zuser", $userid);

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

        //start ACtivate page if variable do has a value(Activate) from get request
        elseif($do = 'Activate'){
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

           //validate userid to be found and numeric value
           $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("userid", "users", $userid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

                //execute delete query
                $stmt->execute(array($userid));

                $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirect_home($errorMessage);
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