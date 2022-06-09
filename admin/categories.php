<?php

    /*============================
    == Categories Page
    ============================*/

    ob_start(); //=>search
    
    session_start();
    
    //to print page title
    $pageTitle = 'Categories';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        //manage pages by the variable do
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';

        //start manage page if variable do has no value from get request
        if($do == 'Manage'){

            $sort = 'ASC';
            $sortArray = array('ASC', 'DESC');

            if(isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)){
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll();
            if(!empty($cats)){
            ?>

                <h1 class="text-center">Manage Categories</h1>
                <div class="container categries">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-edit"></i>Manage Categories
                            <div class="option pull-right">
                                <i class="fa fa-sort"></i>Ordering:
                                [<a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a>
                                |
                                <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a>]
                                <i class="fa fa-eye"></i>View:
                                [<span class="active" data-view="full">Full</span>
                                |
                                <span data-view="classic">Classic</span>]
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php foreach($cats as $cat){
                                echo '<div class="cat">';
                                    echo '<div class="hidden-buttons">';
                                        echo '<a href="categories.php?do=Edit&catid=' . $cat['ID'] . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</a>';
                                        echo '<a href="categories.php?do=Delete&catid=' . $cat['ID'] . '" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i>Delete</a>';
                                    echo '</div>';
                                    echo '<h3>' . $cat['Name'] . '</h3>';
                                    echo '<div class="full-view">';
                                        echo '<p>' ;
                                        if($cat['Description'] == ''){
                                            echo 'this Category has no Description';
                                        }
                                        
                                        else{
                                            echo $cat['Description'];
                                        }
                                        echo '</p>';
                                        
                                        if($cat['Visibility'] == 1){
                                            echo '<span class="visibility cat-span"><i class="fa fa-eye"></i>Hidden</span> ';
                                        }

                                        if($cat['Allow_Comment'] == 1){
                                            echo '<span class="commenting cat-span"><i class="fa fa-close"></i>Comment Disabled</span> ';
                                        }

                                        if($cat['Allow_Ads'] == 1){
                                            echo '<span class="advertises cat-span"><i class="fa fa-close"></i>Ads Disabled</span>';
                                        }
                                    echo '</div>';
                                echo '</div>';
                                echo '<hr/>';
                            }?>
                        </div>
                    </div>
                    <a class="btn btn-primary add-category" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
                </div>
                <?php
                }
                else{
                    echo '<div class="container">';
                        echo '<div class="alert-message">There\'s no ' . $pageTitle .  ' to show</div>';
                        echo '<a class="btn btn-primary add-category" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>';
                    echo '</div>';
                }
            }

        //start add page if variable do has a value(Add) from get request
        elseif($do == 'Add'){?>
            
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!--start name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" autocomplete="off" required="required" placeholder="Category Name"/>
                        </div>
                    </div>
                    <!--end name field-->
                    
                    <!--start description field-->          
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Category description"/>
                        </div>
                    </div>            
                    <!--end description field-->
                    
                    <!--start ordering field-->            
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Order</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="ordering" placeholder="Category Order"/>
                        </div>
                    </div>             
                    <!--end ordering field-->
                    
                    <!--start visibility field-->             
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked/>
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1"/>
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end visibility field-->

                    <!--start allow comment field-->             
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Comments</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked/>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1"/>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end visibility field-->

                     <!--start allow ads field-->             
                     <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked/>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"/>
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end visibility field-->
                    
                    <!--start button field-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Category"/>
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
                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";
                
                //get the new info from the edit form
                $name      = $_POST['name'];
                $desc      = $_POST['description'];
                $order     = $_POST['ordering'];
                $visible   = $_POST['visibility'];
                $comment   = $_POST['commenting'];
                $ads       = $_POST['ads'];
                
                //check if category exist in db
                $check = check_item("Name", "categories", $name);
                
                if($check == 1){
                    $errorMessage = "<div class='alert alert-danger'>Category is already exist</div>";
                    redirect_home($errorMessage, "Back");
                }
                
                else{
                    //Insert into the db
                    $stmt = $con->prepare("INSERT INTO categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");
                    $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zorder' => $order, 'zvisible' => $visible, 'zcomment' => $comment, 'zads' => $ads)); 
            
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
            
            //validate catid to be found and numeric value
            $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0 ;
            
            //search in db about the record that has the previous id
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
            $stmt->execute(array($catid));
            
            //get the record from db and but it into an associative array
            $cat = $stmt->fetch();  
            
            //count variable have a value (1) if there is a record in the db with the previous id
            $count = $stmt->rowCount();

            //if there a record in db show the edit form
            if($count > 0){?>
                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid;?>"/>
                        <!--start name field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="name" value="<?php echo $cat['Name'];?>" required="required" placeholder="Category Name"/>
                            </div>
                        </div>
                        <!--end name field-->
                        
                        <!--start description field-->          
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="description" value="<?php echo $cat['Description'];?>" placeholder="Category Description"/>
                            </div>
                        </div>            
                        <!--end description field-->
                        
                        <!--start ordering field-->            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="ordering" value="<?php echo $cat['Ordering'];?>" placeholder="Category Order"/>
                            </div>
                        </div>             
                        <!--end ordering field-->
                        
                        <!--start visibility field-->             
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visibile</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked';}?>/>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo 'checked';}?>/>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--end visibility field-->

                        <!--start allow comment field-->             
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Comments</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';}?>/>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked';}?>/>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--end visibility field-->

                        <!--start allow ads field-->             
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';}?>/>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';}?>/>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--end visibility field-->
                        
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

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
           
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
                //get the new info from the edit form
                $id         = $_POST['catid']; 
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $order      = $_POST['ordering'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];
                
              
                //update the db
                $stmt = $con->prepare("UPDATE 
                                            categories
                                            SET
                                            Name          = ?,
                                            Description   = ?, 
                                            Ordering      = ?, 
                                            Visibility    = ?,
                                            Allow_Comment = ?,
                                            Allow_Ads     = ? 
                                            WHERE ID      = ?");
                $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));
            
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
            
            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";

           //validate catid to be found and numeric value
           $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0 ;
           
           //check if the selected id is fount or not
           $check = check_item("ID", "categories", $catid);

           //if there a record in db 
           if($check > 0){
                //get the record
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                
                //assign userid variable to zuser parameter
                $stmt->bindParam(":zid", $catid);

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

        include $tpl.'footer.php';
    }
    
    else{
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
?>