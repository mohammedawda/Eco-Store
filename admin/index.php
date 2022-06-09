<?php
      
      session_start();
      //print_r($_SESSION);
      
      //variable noNavbar for not include the navigation bar in pages include it
      $noNavbar  = '';

      //variable pageTitle that prints the title of the page that include it
      $pageTitle = 'Login';

      if(isset($_SESSION['Username'])){
      //redirect to dashboard page
      header('Location: dashboard.php');
    }
      
      include "init.php";
      //echo lang('message')." ".lang('admin');
      
      //check if user coming from http post request
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username   = $_POST['user'];
        $password   = $_POST['pass'];
        $hashedpass = sha1($password);
        
        //check if user exist in database
        $stmt = $con->prepare("SELECT 
                                    UserID, Username, Password
                               FROM 
                                    users
                               WHERE 
                                    Username = ? 
                               AND 
                                    Password = ?
                               AND 
                                    GroupID = 1
                               LIMIT 1");
        $stmt->execute(array($username, $hashedpass));
        $row = $stmt->fetch();  
        $count = $stmt->rowCount();
      
        if($count > 0){
          echo $count;
          
          //register session ID
          $_SESSION['ID']       = $row['UserID'];
          
          //register session name
          $_SESSION['Username'] = $username; 
      
          //redirect to dashboard page
          header('Location: dashboard.php');
          exit();
        }
      }

    ?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
      <h4 class="text-center">Admin Login</h4>
      <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
      <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password"/>
      <input class="btn btn-primary btn-block" type="submit" value="login"/>      
    </form>
    <!-- <div class="btn btn-danger btn-block">test</div><br/> -->
    <!-- <i class="fa fa-home fa-5x"></i> -->
<?php include $tpl.'footer.php';?>