<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = "بيانات الموكل";
        include 'init.php';        
        $manage = isset($_GET['action']) ? $_GET['action'] : "manage";
        if($manage == "manage"){
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
        $check = checkItem("client_id","clients",$id);
        if($check > 0){
            $stmt = $connect->prepare("SELECT * FROM clients WHERE client_id = ?");
            $stmt->execute(array($id));
            $rows = $stmt->fetch();
?>
<div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                <div class="container">
                    <div class="user-control">
                        <a class="btn btn-primary" href="?action=edit_client&id=<?php echo $id?>">تعديل الموكل</a>                        
                    </div>
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
        $stmt = $connect->prepare("SELECT identity_id,name,date FROM identity WHERE client_id = ?");
        $stmt->execute(array($id));
        $rows = $stmt->fetchAll();
        foreach($rows as $key => $value){
    ?>
                    <div class="img-content">
                    <span id="myImg" class="myImg" src="upload/imgs/<?php echo $rows[$key]['name']?>">ملف <?php echo $key + 1?></span>
                    <a href="?action=delete_img&img_id=<?php echo $rows[$key]['identity_id'] ?>&name=<?php echo $rows[$key]['name']?>"><span class="delete-file">X</span></a>

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
    }else{
        $msg_log = "حدث خطأ اثناء";
        redirect($msg_log,"back",3);
    }
        }elseif($manage == "delete_img"){
            $file_id = is_numeric($_GET['img_id']) ? $_GET['img_id'] : 0;
            $check = checkItem("identity_id","identity",$file_id);
            if($check > 0){
                $name = $_GET['name'];
                $stmt = $connect->prepare("DELETE FROM identity WHERE identity_id = ?");
                $stmt->execute(array($file_id));
                if($stmt->rowCount() > 0){
                    unlink("upload/imgs/".$name);
                    $msg_log = "تم الحذف بنجاح";
                    redirect($msg_log,"back",3);
                }else{
                    $msg_log = "حدث خطأ اثناْ الحذف";
                    redirect($msg_log,"back",3);
                }
            }
        }elseif($manage == "edit_client"){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
            $check = checkItem("client_id","clients",$id);
            if($check > 0){
                $stmt = $connect->prepare("SELECT * FROM clients WHERE client_id = ?");
                $stmt->execute(array($id));
                $row = $stmt->fetch();
                $stmt = $connect->prepare("SELECT * FROM phones WHERE client_id = ?");
                $stmt->execute(array($id));
                $row_phones = $stmt->fetchAll();
?>
<div class="dashboard-bg">
    <div class="dashpage">
        <div class="row">
            <div class="col-11">
                <div class="container">
                    <form action="?action=update_client" method="POST" enctype="multipart/form-data" dir="rtl" class="arabicFont">
                        <div class="form-card form-group col-12 col-lg-6 input-form text-center">
                        <span class="display-5 text-dark">تعديل تــوكيل</span>
                        <hr style="text-dark">
                        <input type="text" name="name" value="<?php echo $row["name"]?>" class="form-control" placeholder="الإسم كامل" autocomplete="off" required>
                        <input type="text" name="address" value="<?php echo $row["address"]?>" class="form-control" placeholder="العنوان" autocomplete="off" required>
                        <input type="text" name="contract" value="<?php echo $row["contract"]?>" class="form-control" placeholder="رقم التوكيل" autocomplete="off">
                            <?php
                                foreach($row_phones as $key => $value){
                            ?>
                                <input type="number" value="<?php echo $row_phones[$key]["phone"]?>" name="phones[]" class="form-control" placeholder="رقم الهاتف" autocomplete="off">
                                <input type="hidden" value="<?php echo $row_phones[$key]["phone_id"]?>" name="ids">
                            <?php
                                }
                            ?>
                        <div class="multifile-data">
                            <i class="fas fa-plus add-file"></i>
                            <input type="file" class="form-control" name="files[]">
                        </div>
                        <input type="hidden" name="client_id" value="<?php echo $row["client_id"]?>">

                        <input type="Submit" value="إضافه" class="btn-success form-control">
                    </div>
                    </form>
                </div>
            </div>
            <div class="col-1">
                <?php include $tmpl . "menubar.php" ?>
            </div>
        </div>
    </div>
</div>
<?php               
            }
        }elseif($manage == "update_client"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $id = isset($_POST['client_id']) && is_numeric($_POST['client_id']) ? $_POST['client_id'] : 0;
                $check = checkItem("client_id","clients",$id);
                if($check > 0){
                    $name = $_POST["name"];
                    $address = $_POST['address'];
                    $contract = $_POST['contract'];
                    
                    // Update Infos To DB
                    $stmt = $connect->prepare("UPDATE clients SET name = ?, contract = ?, address = ? WHERE client_id = ? ");
                    $stmt->execute(array($name,$contract,$address,$id));
                    
                    // Update Phones
                    foreach($_POST['phones'] as $key => $value){
                        $stmt = $connect->prepare("UPDATE phones SET phone = ? WHERE phone_id = ?");
                        $stmt->execute(array($_POST['phones'][$key],$_POST['ids'][$key]));    
                    }
                    $ErrorsCatch = [];


                    $x = 0;
                    while($x < count($_FILES['files']['name'])){
                        
                        $file_name = $_FILES['files']['name'][$x];
                        $file_size = $_FILES['files']['size'][$x];
                        
                        $allowed_ext = array("jpeg","jpg","png","gif");
                        $file_ext = explode(".",$file_name);
                        $ext = strtolower($file_ext[count($file_ext) -1]);
                        
    
                        if(!empty($file_name) && !in_array($ext,$allowed_ext)){
                            $ErrorsCatch[] = "يرجي مراجعة الملفات المرفوعه";
                        }
                        if($file_size > 4194304){
                            $ErrorsCatch[] = "حجم الملف ازيد من 4 ميجا";
                        }
                        
                        $x++;
                        
                    }

                    if(!empty($_FILES['files']['name'][0]) && empty($ErrorsCatch)){
                        $x = 0;
                        while($x < count($_FILES['files']['name'])){
                            $file_name = $_FILES['files']['name'][$x];
                            $file_type = $_FILES['files']['type'][$x];
                            $file_tmp  = $_FILES['files']['tmp_name'][$x];
                            $file_size = $_FILES['files']['size'][$x];
    
                            $avatar_name = rand(0,9999999) . "_" . $file_name;
                            move_uploaded_file($file_tmp,"upload\imgs\\".$avatar_name);
    
                            $stmt = $connect->prepare("INSERT INTO identity(name,type,date,client_id) VALUES (:zname, :ztype, :zdate, :zcl_id)");
                            $stmt->execute(array(
                                "zname" => $avatar_name,
                                "ztype" => $file_type,
                                "zdate" => date("Y-m-d"),
                                "zcl_id" => $id,
                            ));
                            $x++;
                        }
                    }
                    $msg_log = "تم التعديل بنجاح";
                    redirect($msg_log,"back",3);
                }else{
                    $msg_log = "الموكل غير موجود";
                    redirect($msg_log,"back",3);

                }
                
            }
        }
?>
    
<?php
    include $tmpl . "footer.php";
}else{
    header("Location: login.php");
}
?>