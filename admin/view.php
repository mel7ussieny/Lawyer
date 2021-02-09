<?php
  session_start();
  if(isset($_SESSION['id'])){
    $title = "بيانات الموكل";
    include 'init.php';
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
    $check = checkItem("client_id","clients",$id);
    if($check > 0){
    $stmt = $connect->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->execute(array($id));
    $rows = $stmt->fetch();
?>
    <div class="dashboard-bg">
        <div class="container">
            <div class="infos arabicFont" dir="rtl">
                <!-- Personal -->
                <div class="personal">
                    <h3>البيانات الشخصيه</h3>
                    <ul>
                        <li>
                            <i class="fas fa-user"></i>
                            الإسم : 
                            <?php echo $rows['name']?>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            العنوان : 
                            <?php echo $rows['address']?></li>
                        <li>
                            <i class="fas fa-file-contract"></i>
                            رقم التوكيل : 
                            <?php echo $rows['contract']?></li>
                        <li>
                            <i class="fas fa-table"></i>
                            تاريخ الإنضمام : 
                            <?php echo $rows['date']?>
                        </li>
                        <li>رقم الهاتف
                            <ul class="phones">
<?php
    $stmt = $connect->prepare("SELECT phone FROM phones WHERE client_id = ?");
    $stmt->execute(array($id));
    $rows = $stmt->fetchAll();
    foreach($rows as $key => $value){
        echo "<li><i class='fas fa-phone'></i>
        ".$rows[$key]['phone']."
        </li>";
    }
?>    
                            </ul>
                        </li>

                    </ul>
                </div>
                <!-- Attachments -->
                <div class="attachments">
                    <h3>المرفقات</h3>
                    <div class="files">

<?php
    $stmt = $connect->prepare("SELECT name,date FROM identity WHERE client_id = ?");
    $stmt->execute(array($id));
    $rows = $stmt->fetchAll();
    foreach($rows as $key => $value){
?>
                <div class="img-content">
                    <img id="myImg" class="myImg" src="upload/imgs/<?php echo $rows[$key]['name']?>" alt="Snow" style="width:100%;max-width:30px">
                    
                    <div id="myModal" class="modal toModal">
                        <span class="close">&times;</span>
                        <img class="modal-content" id="img01">
                        <div id="caption" class="caption"></div>
                    </div>
                </div>
<?php
    }
?>
                    </div>
                </div>
                <!-- End Attachments -->

                <!-- Start Payments -->
                <div class="disputes">
                    <h3>القواضي</h3>
<?php 
    $stmt = $connect->prepare("SELECT * FROM disputes WHERE dclient_id = ?");
    $stmt->execute(array($id));
    $rows = $stmt->fetchAll();
?>

                    <div class="disputes">
                        <div class="dispute_tbl">
                        <!-- disputes Rows -->
<?php
    foreach($rows as $key => $value){
        
     //#Back Here To Clean The Code   
        // client type

        switch($rows[$key]["client_type"]){
            case 1:
                $c_type = "مدعي";
                break;
            case 2:
                $c_type = "مدعي عليه";
                break;
            case 3:
                $c_type = "مجني";
                break;
            case 4:
                $c_type = "مجني عليه";
                break;
            case 5:
                $c_type = "مستأنف";
                break;
            case 6:
                $c_type = "مستأنف ضده";
                break;
            default:
                $c_type = "غير معروف";
        }


        switch($rows[$key]["en_type"]){
            case 1:
                $e_type = "مدعي";
                break;
            case 2:
                $e_type = "مدعي عليه";
                break;
            case 3:
                $e_type = "مجني";
                break;
            case 4:
                $e_type = "مجني عليه";
                break;
            case 5:
                $e_type = "مستأنف";
                break;
            case 6:
                $e_type = "مستأنف ضده";
                break;
            default:
                $e_type = "غير معروف";
        }
        
?>
                        <div class="dispute arabicFont"> 
                            <div class="dispute-header">
                                <span class="title"><?php echo $rows[$key]["title"]?></span>
                                <a href="trace-dispute.php?action=view&dispute_id=<?php echo $rows[$key]['dispute_id']?>"><i class="far fa-eye show-dispute"></i></a>
                            </div>
                            <div class="content">
                                <ul>
                                    <li>رقم الدعوي : <?php echo $rows[$key]["ref_number"]?></li>
                                    <li>محكمة : <?php echo $rows[$key]["court"]?></li>
                                    <li>منطقة : <?php echo $rows[$key]["district"]?></li>
                                    <li>تاريح : <?php echo $rows[$key]["date"]?></li>
                                    <li>صفة العميل : <?php echo $c_type?></li>
                                    <li>إسم الخصم : <?php echo $rows[$key]["en_name"]?></li>
                                    <li>عنوان الخصم : <?php echo $rows[$key]["en_address"]?></li>
                                    <li>صفة الخصم : <?php echo $e_type?></li>
                                    <li>محامي الخصم : <?php echo $rows[$key]["en_lawyer"]?></li>
                                    <li>الأتعاب : <?php echo $rows[$key]["price"]?></li>
                                </ul>
                            </div>
                        </div>
<?php
    }
?>
                            <!-- Disputes Rows -->
                        </div>
                    </div>
                </div>
                <!-- End Payments -->

                <!-- Start Payments -->
                <div class="payments">
                    <h3>المدفوعات</h3>
                </div>
                <!-- End Payments -->
            </div>
        </div>
    </div>
    
<?php
    }else{
        $msg = "المستخدم غير موجود في قاعدة البيانات";
        redirect($msg,"dashboard.php",3);   
    }
    include $tmpl . "footer.php";
}else{
    header("Location: login.php");
}

?>