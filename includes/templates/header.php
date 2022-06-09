<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php get_title(); ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css.map"/>
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>frontend.css"/>
    </head>
    <body>
        <div class="upper-bar">
            <div class="container">
                <a href="login.php">
                    <span class="pull-right">Login/SignUp</span>
                </a>
            </div>
        </div>
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
                    <a class="navbar-brand" href="index.php">Home</a>
                </div>
                
                <!-- collect the nav links, forms, and ather content for toggeling-->
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="nav navbar-nav navbar-right">
                       <?php 
                            $cats = get_categories();
  
                            foreach($cats as $cat){
                                echo '<li><a href="categories.php?pageid=' . $cat['ID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']). '">' . $cat['Name'] . '</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>