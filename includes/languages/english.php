<?php   
    function lang($phrase){
        
        static $lang = array(
            //Dashboard phrases
            'home_admin'    => 'Home',
            'categories'    => 'Categories',
            'items'         => 'Items',
            'members'       => 'Members',
            'statistics'    => 'Statistics',
            'comments'      => 'Comments',
            'logs'          => 'Logs',
            'edit_profile'  => 'Edit Profile',
            'settings'      => 'Settings',
            'logout'        => 'Logout'
        );
       
       return $lang[$phrase];
    } 