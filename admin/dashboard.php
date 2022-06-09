<?php
     
    //output buffering start  
    ob_start(); //=>search
    
    session_start();
   
    if(isset($_SESSION['Username'])){
      include 'init.php';
      
      //to print page title
      $pageTitle = 'Dashboard';
      
      //to print number of latest user that i want
      $numUsers = 3;

      //to print number of latest items that i want
      $numItems = 3;

      //to print number of latest comments that i want
      $numComments = 3;

      //get needed latest users from db
      $latestUsers = get_latest("*", "users", "UserID", $numUsers);

      if(empty($latestUsers)){
        $numUsers = '';
      }

      //get needed latest items from db
      $latestItems = get_latest("*", "items", "item_ID", $numItems);

      if(empty($latestItems)){
        $numItems = '';
      }
      

      /*start Dashboard Page*/
      ?>
      
      <div class="home-stats">
        <div class="container  text-center">
          <h1>Dashboard</h1>
          <div class="row">
            <div class="col-md-3">
              <div class="stat st-members">
                <i class="fa fa-users"></i>
                <div class="info">
                  Total Members 
                  <span>
                    <a href="members.php"><?php echo count_items("UserID", "users"); ?></a>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-pending">
                <i class="fa fa-user-plus"></i>
                <div class="info">
                  Pending Members
                  <span>
                    <a href="members.php?do=Manage&page=Pending"><?php echo check_item("RegStatus", "users", 0);?></a>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-items">
                <i class="fa fa-tag"></i>
                <div class="info">
                  Total Items 
                  <span>
                    <a href="items.php"><?php echo count_items("item_ID", "items"); ?></a>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-comments">
                <i class="fa fa-comments"></i>
                <div class="info">
                  Total Comments 
                    <span>
                      <a href="comments.php"><?php echo count_items("c_id", "comments"); ?></a>
                    </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="latest">
        <div class="container">

          <!--start latest users and items container-->
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-users"></i>
                  Latest <?php echo $numUsers?> Registerd Users
                  <span class="toggle-info pull-right">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                      <?php 
                        if(!empty($latestUsers)){
                            foreach($latestUsers as $user){
                              echo '<li>' ;
                                echo $user["Username"] ;
                                echo '<a href="members.php?do=Edit&userid=' . $user["UserID"] . '">';
                                  echo'<span class="btn btn-success pull-right">';
                                    echo'<i class="fa fa-edit"></i>Edit';
                                    if($user['RegStatus'] == 0){
                                      echo "<a href='members.php?do=Activate&userid=". $user['UserID'] . "' class='btn btn-info pull-right activate'>
                                              <i class='fa fa-check'></i>Activate
                                            </a>";
                                    }
                                  echo'</span>';
                                echo '</a>';
                              echo'</li>';
                          }
                        }
                        else{
                          echo '<div class="alert-message">There\'s no Record to show</div>';
                        }
                      ?>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-tag"></i>
                  Latest <?php echo $numItems?> Items
                  <span class="toggle-info pull-right">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                    <?php 
                    if(!empty($latestItems)){
                      foreach($latestItems as $item){
                        echo '<li>' ;
                          echo $item["Name"] ;
                          echo '<a href="items.php?do=Edit&itemid=' . $item["item_ID"] . '">';
                            echo'<span class="btn btn-success pull-right">';
                              echo'<i class="fa fa-edit"></i>Edit';
                              if($item['Approve'] == 0){
                                echo "<a href='items.php?do=Approve&itemid=". $item['item_ID'] . "' class='btn btn-info pull-right activate'>
                                        <i class='fa fa-check'></i>Approve
                                      </a>";
                              }
                            echo'</span>';
                          echo '</a>';
                        echo'</li>';
                      }
                    }
                    else{
                      echo '<div class="alert-message">There\'s no Record to show</div>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!--end latest users and items container-->

          <!--start latest comments container-->
          
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-comments-o"></i>
                  Latest <?php echo $numComments?> Comments
                  <span class="toggle-info pull-right">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="panel-body">
                  <?php  
                    $stmt = $con->prepare("SELECT comments.*, users.Username 
                                           FROM comments
                                           INNER JOIN users
                                           ON users.UserID = comments.user_id
                                           ORDER BY c_id DESC
                                           LIMIT $numComments");
                    $stmt->execute();
                    $comments = $stmt->fetchAll();
                    if(!empty($comments)){
                      foreach($comments as $comment){
                        echo '<div class="comment-box">';
                                echo '<a href="members.php?do=Edit&userid='. $comment['user_id']. '" class="member-n">' . $comment['Username'] . '</a>';
                                echo '<p class="member-c">' . $comment['comment'];
                                  echo '<span class="pull-right">';
                                    echo "<a href='comments.php?do=Edit&comid=". $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                            <a href='comments.php?do=Delete&comid=". $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                            if($comment['status'] == 0){
                                                echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                            }; 
                                            echo '</span>';
                              echo '</p>';
                        echo '</div>';
                      }
                    }
                    else{
                      echo '<div class="alert-message">There\'s no Record to show</div>';
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!--end latest comments-->

        </div>
      </div>
      <?php
      include $tpl.'footer.php';
    }
    else{
      header('Location: index.php');
      exit();
    }

    ob_end_flush();
?>