<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'لوحة التحكم';
        include 'init.php';
?>
    <div class="dashboard-bg">

    <div class="dashpage">
        <div class="row">
            <div class="col-11">
                <div class="container">
                    
                </div>
            </div>
            <div class="col-1">
                <?php include $tmpl . "menubar.php" ?>
            </div>
        </div>
    </div>
<?php
    }else{
        $title = 'حدث خطأ';
        include 'init.php';

    }
    include $tmpl . "footer.php";
?>