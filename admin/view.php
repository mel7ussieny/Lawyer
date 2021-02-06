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