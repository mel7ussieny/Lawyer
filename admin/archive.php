<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'لوحة التحكم';
        include 'init.php';
        $manage = isset($_GET['action']) ? $_GET['action'] : "manage";

        if($manage == "manage"){
?>
<div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                    <div class="container">
                        <div class="disputes-trace">
                            <div class="search-advanced">
                                <h3 class="arabicFont display-4 text-center">الأرشيف</h3>
                                <div class="disputes-view" id="disputes" dir="rtl">
<?php
    $stmt = $connect->prepare("SELECT disputes.*,clients.name FROM disputes INNER JOIN 
                                clients 
                                ON 
                                disputes.dclient_id = clients.client_id
                                WHERE disputes.dis_status = 0");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    foreach($rows as $key => $value){
        ?>
        <div class="dispute-view arabicFont dispute-archive">
                <span class="client_name"><?php echo $rows[$key]["name"]?></span>
                <span class="name"><?php echo $rows[$key]["title"]?></span>
                <span class="ref_number"><?php echo $rows[$key]["ref_number"]?></span>
                <span class="date"><?php echo $rows[$key]["date"]?></span>
                <div class="btn-return d-inline">
                    <a class="btn btn-success" href="?action=open&dispute_id=<?php echo $rows[$key]["dispute_id"]?>">فتح القضية</a>
                </div>
        </div>
        <?php
    }
?>
                                </div>    
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <?php include $tmpl . "menubar.php" ?>
                </div>
            </div>
        </div>
    </div>
<?php
        }elseif($manage == "open"){
            $dispute_id = isset($_GET['dispute_id']) && is_numeric($_GET['dispute_id']) ? $_GET['dispute_id'] : 0;
            $check = checkItem("dispute_id","disputes",$dispute_id);
            if($check > 0){
                $stmt = $connect->prepare("UPDATE disputes SET dis_status = 1 WHERE dispute_id = ?");
                $stmt->execute(array($dispute_id));
                if($stmt->rowCount() > 0){
                    $msg_log = "تم فتح القضية";
                    redirect($msg_log,"trace-dispute.php",1);
                }
            }else{
                $msg_log = "حدث خطأ اثناء فتح القضية";
                redirect($msg_log,"archive.php",1);
            }
        }else{
            $msg_log = "حدث خطأ اثناء فتح القضية";
            redirect($msg_log,"archive.php",1);
        }
    }else{
        $title = 'حدث خطأ';
        include 'init.php';
        header("Location:login.php");

    }
    include $tmpl . "footer.php";
?>