<?php
// Function Get Tile Of Page

    function getTitle(){
        global $title;
        $title = isset($title) ? $title : "Default";
        return $title;
    }

// Redirect Function

    function redirect($msg,$to,$time){
        $message = "<div class='text-center mt-3 alert alert-info arabicFont'>".$msg."</div>";
        echo $message;
        if(isset($_SERVER['HTTP_REFERER'])){
            if($to == "back"){
                $to = $_SERVER['HTTP_REFERER'];
            }
        }else{
            if($to == "back"){
                $to = "index.php";
            }
        }
        header("refresh:$time; url=$to");
    }

    function checkItem($select , $from , $value){
        global $connect;
        $statment = $connect->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();
    
        return $count;
      }

    function getCount($col,$tbl){
        global $connect;
        $statment = $connect->prepare("SELECT COUNT($col) AS number FROM $tbl");
        $statment->execute();
        $row = $statment->fetch();
        return $row['number'];
    }
?>