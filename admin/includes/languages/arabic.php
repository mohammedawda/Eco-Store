<?php   
    function lang($phrase){
        
        static $lang = array('message' => 'مرحبا',
        'admin' => 'عودة');

        return $lang[$phrase];
    } 