<?php
// Routes File
    $tmpl   = "inc/tmpl/";
    $fun    = "inc/func/";
    $css    = "layout/css/";
    $js     = "layout/js/";
    $img    = "layout/img/";
    $lib    = "inc/lib/"; 
    // Connect To Databse Name : lawyerdb
    include 'connect.php';
    // Include HeaderFlie - Menuebar With Function 
    include $fun .  "function.php";
    include $tmpl . "header.php";

    if(isset($menubar) && $menubar == 1){
        include $tmpl . "menubar.php";
    }

?>