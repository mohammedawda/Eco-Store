<?php

    /*============================
    == items Page
    ============================*/

    ob_start(); //=>search
    
    session_start();
    
    //to print page title
    $pageTitle = 'Items';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        //manage pages by the variable do
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';

        //start manage page if variable do has no value from get request
        if($do == 'Manage'){
            
            $stmt = $con->prepare("SELECT items.*, categories.Name AS Category_Name, users.Username
                                   FROM items
                                   INNER JOIN categories ON categories.ID = items.Cat_ID
                                   INNER JOIN users ON users.UserID = items.Member_ID
                                   ORDER BY item_ID DESC");
            $stmt->execute();
            $items = $stmt->fetchAll();

            if(!empty($items)){
                echo '<h1 class="text-center">Manage Items</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Name</td>
                                <td>Description</td>
                                <td>Price</td>
                                <td>Category Name</td>
                                <td>Username</td>
                                <td>Adding Date</td>
                                <td>Control</td>
                            </tr>'; 
                            
                            //get user info form db
                            foreach($items as $item){
                                echo "<tr>";
                                    echo "<td>" . $item['item_ID'] . "</td>"; 
                                    echo "<td>" . $item['Name'] . "</td>";
                                    echo "<td>" . $item['Description'] . "</td>"; 
                                    echo "<td>" . $item['Price'] . "</td>";
                                    echo "<td>" . $item['Category_Name'] . "</td>";
                                    echo "<td>" . $item['Username'] . "</td>";
                                    echo "<td>" . $item['Add_Date'] . "</td>";
                                    echo "<td>
                                            <a href='items.php?do=Edit&itemid=". $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                            <a href='items.php?do=Delete&itemid=". $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                            if($item['Approve'] == 0){
                                                echo "<a href='items.php?do=Approve&itemid=". $item['item_ID'] . "' class='btn btn-info activate'>
                                                        <i class='fa fa-check'></i>Approve
                                                    </a>";
                                            }
                                    echo "</td>";
                                echo "<tr>";
                            } 
                            
                            echo 
                        '</table>
                    </div>
                    <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
                </div>';
            }
            else{
                echo '<div class="container">';
                    echo '<div class="alert-message">There\'s no ' . $pageTitle .  ' to show</div>';
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
                echo '</div>';
            }
        }

        //start add page if variable do has a value(Add) from get request
        elseif($do == 'Add'){?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!--start name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" required="required" placeholder="Item Name"/>
                        </div>
                    </div>
                    <!--end name field-->

                     <!--start description field-->          
                     <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" required="required" placeholder="Item description"/>
                        </div>
                    </div>            
                    <!--end description field-->

                    <!--start Price field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" required="required" placeholder="Item Price"/>
                        </div>
                    </div>            
                    <!--end Price field-->

                    <!--start country_made field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country Made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="country" required="required" placeholder="Item Country Made"/>
                        </div>
                    </div>            
                    <!--end country_made field-->

                    <!--start status field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>            
                    <!--end status field-->

                    <!--start members field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="member">
                                <option value="0">...</option>
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user){
                                        echo '<option value="' . $user['UserID'] . '">' . $user['Username'] .'</option>';
                                    }?>
                            </select>
                        </div>
                    </div>            
                    <!--end members field-->

                    <!--start categry field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="category">
                                <option value="0">...</option>
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll();
                                    foreach($categories as $category){
                                        echo '<option value="' . $category['ID'] . '">' . $category['Name'] .'</option>';
                                    }?>
                            </select>
                        </div>
                    </div>            
                    <!--end category field-->
                    
                    <!--start button field-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Add Item"/>
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
                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";
                
                //get the new info from the edit form
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $price    = $_POST['price'];
                $country  = $_POST['country'];
                $status   = $_POST['status'];
                $member   = $_POST['member'];
                $cat      = $_POST['category'];
                
                //validate the form
                $formErrors = array();
                
                if(empty($name)){
                    $formErrors[] = 'Name Field can\'t be empty';
                }

                if(empty($desc)){
                    $formErrors[] = 'Description Field can\'t be empty';
                }

                if(empty($price)){
                    $formErrors[] = 'Price Field can\'t be empty';
                }

                if(empty($country)){
                    $formErrors[] = 'Country Made Field can\'t be empty';
                }

                if($status == 0){
                    $formErrors[] = 'You Must Choose a Status for the Item';
                }

                if($member == 0){
                    $formErrors[] = 'You Must Choose a Member for the Item';
                }

                if($cat == 0){
                    $formErrors[] = 'You Must Choose a Category for the Item';
                }

                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>" ;
                }
                
                if(empty($formErrors)){
                    
                    //Insert into the db
                    $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Add_Date, Country_Made, Status, Cat_ID, Member_ID) VALUES(:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zcat, :zmember)");
                    $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zprice' => $price, 'zcountry' => $country, ':zstatus' => $status, ':zcat' => $cat, ':zmember' => $member)); 
                
                    //print success message
                    $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>' ;
                    redirect_home($errorMessage, "Back"); 
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
            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
            
            //search in db about the record that has the previous id
            $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
            $stmt->execute(array($itemid));
            
            //get the record from db and but it into an associative array
            $items = $stmt->fetch();  
            
            //count variable have a value (1) if there is a record in the db with the previous id
            $count = $stmt->rowCount();

            //if there a record in db show the edit form
            if($count > 0){?>
            
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
                    <!--start name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" value="<?php echo $items['Name'];?>" required="required" placeholder="Item Name"/>
                        </div>
                    </div>
                    <!--end name field-->

                     <!--start description field-->          
                     <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" value="<?php echo $items['Description'];?>" required="required" placeholder="Item description"/>
                        </div>
                    </div>            
                    <!--end description field-->

                    <!--start Price field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" value="<?php echo $items['Price'];?>" required="required" placeholder="Item Price"/>
                        </div>
                    </div>            
                    <!--end Price field-->

                    <!--start country_made field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country Made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="country" value="<?php echo $items['Country_Made'];?>" required="required" placeholder="Item Country Made"/>
                        </div>
                    </div>            
                    <!--end country_made field-->

                    <!--start status field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="status">
                                <option value="1" <?php if($items['Status'] == 1){echo 'selected';}?>>New</option>
                                <option value="2" <?php if($items['Status'] == 2){echo 'selected';}?>>Like New</option>
                                <option value="3" <?php if($items['Status'] == 3){echo 'selected';}?>>Used</option>
                                <option value="4" <?php if($items['Status'] == 4){echo 'selected';}?>>Old</option>
                            </select>
                        </div>
                    </div>            
                    <!--end status field-->

                    <!--start members field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="member">
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user){
                                        echo '<option value="' . $user['UserID'] . '"';
                                        if($items['Member_ID'] == $user['UserID']){echo 'selected';} 
                                        echo '>' . $user['Username'] .'</option>';
                                    }?>
                            </select>
                        </div>
                    </div>            
                    <!--end members field-->

                    <!--start categry field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class= "form-control" name="category">
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll();
                                    foreach($categories as $category){
                                        echo '<option value="' . $category['ID'] . '"';
                                        if($items['Cat_ID'] == $category['ID']){echo 'selected';}
                                        echo '>' . $category['Name'] .'</option>';
                                    }?>
                            </select>
                        </div>
                    </div>            
                    <!--end category field-->
                    
                    <!--start button field-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Save"/>
                        </div>
                    </div>
                    <!--end button field-->
                    
                    <?php 
                        $stmt = $con->prepare("SELECT comments.*, users.Username 
                                               FROM comments
                                               INNER JOIN users
                                               ON users.UserID = comments.user_id
                                               WHERE item_id = ?");
                        $stmt->execute(array($itemid));
                        $rows = $stmt->fetchAll();
                        
                        if(!empty($rows)){
                    ?>


                    <h1 class="text-center">Manage <?php echo $items['Name'];?> Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Username</td>
                                <td>Comment</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>'; 
                        
                            <?php
                                //get comment info form db
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>" . $row['Username'] . "</td>";
                                        echo "<td>" . $row['comment'] . "</td>";
                                        echo "<td>" . $row['comment_date'] . "</td>";
                                        echo "<td>
                                                <a href='comments.php?do=Edit&comid=". $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                                <a href='comments.php?do=Delete&comid=". $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                                if($row['status'] == 0){
                                                    echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                                }
                                        echo "</td>";
                                    echo "<tr>";
                                } 
                            ?>
                                
                        </table>
                    </div>
                    <?php 
                        } 
                    ?>    
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

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
           
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
                //get the new info from the edit form
                $id       = $_POST['itemid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $price    = $_POST['price'];
                $country  = $_POST['country'];
                $status   = $_POST['status'];
                $member   = $_POST['member'];
                $cat      = $_POST['category'];
                
                //validate the form
                $formErrors = array();
                
                if(empty($name)){
                    $formErrors[] = 'Name Field can\'t be empty';
                }

                if(empty($desc)){
                    $formErrors[] = 'Description Field can\'t be empty';
                }

                if(empty($price)){
                    $formErrors[] = 'Price Field can\'t be empty';
                }

                if(empty($country)){
                    $formErrors[] = 'Country Made Field can\'t be empty';
                }

                if($status == 0){
                    $formErrors[] = 'You Must Choose a Status for the Item';
                }

                if($member == 0){
                    $formErrors[] = 'You Must Choose a Member for the Item';
                }

                if($cat == 0){
                    $formErrors[] = 'You Must Choose a Category for the Item';
                }

                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>" ;
                }
                
                if(empty($formErrors)){
                    //update the db
                    $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Cat_ID = ?, Member_ID = ? WHERE item_ID  = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $id));
                
                    //print success message
                    $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>' ;
                    redirect_home($errorMessage, "Back"); 
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
            
            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";

           //validate itemid to be found and numeric value
           $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("item_ID", "items", $itemid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zitem");
                
                //assign userid variable to zuser parameter
                $stmt->bindParam(":zitem", $itemid);

                //execute delete query
                $stmt->execute();

                $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirect_home($errorMessage, "Back");
           }
           else{
               $errorMessage = "<div class='alert alert-danger'>this id is not found</div>";
               redirect_home($errorMessage, "Back");
           }

           echo "</div>";
        }

        //start Approve page if variable do has a value(Approve) from get request
        elseif($do == 'Approve'){
            
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

           //validate itemid to be found and numeric value
           $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("item_ID", "items", $itemid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");

                //execute delete query
                $stmt->execute(array($itemid));

                $errorMessage = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
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

    ob_end_flush();
?>