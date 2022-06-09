<nav class="navbar navbar-inverse">
    <div class="container">
        
        <!--brand and toggle get grouped for better media display-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('home_admin');?></a>
        </div>
        
        <!-- collect the nav links, forms, and ather content for toggeling-->
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav">
                <li><a href="categories.php"><?php echo lang('categories');?></a></li>
                <li><a href="items.php"><?php echo lang('items');?></a></li>
                <li><a href="members.php"><?php echo lang('members');?></a></li>
                <li><a href="comments.php"><?php echo lang('comments');?></a></li>
                <li><a href="#"><?php echo lang('statistics');?></a></li>
                <li><a href="#"><?php echo lang('logs');?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['Username'];?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        
                        <li><a href="../index.php">Visit Shop</a></li>
                        
                        <!--href to edit page-->
                        <li>
                            <a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'];?>">
                                <?php echo lang('edit_profile');?>
                            </a>
                        </li>
                        <li><a href="#"><?php echo lang('settings');?></a></li>
                        
                        <!--href to logout from the site-->
                        <li><a href="logout.php"><?php echo lang('logout');?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>