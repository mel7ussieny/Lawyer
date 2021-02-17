<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = "تتبع القضيه";
        include 'init.php';
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            // Upload updates of dispute
            if(isset($_POST['add-new'])){
                $roll = filter_var($_POST['dispute_roll'],FILTER_SANITIZE_STRING);
                $date = filter_var($_POST['prefix__dispute_date__suffix'],FILTER_SANITIZE_STRING);
                $description = filter_var($_POST['session_text'],FILTER_SANITIZE_STRING);
                $note = filter_var($_POST['dispute_note'],FILTER_SANITIZE_STRING) ;
                $id = isset($_POST['dispute_id']) && is_numeric($_POST['dispute_id']) ? $_POST['dispute_id'] : 0;


                    $stmt = $connect->prepare("INSERT INTO updates(dispute_id,roll_number,date,description,notes) VALUES (:z_id, :z_roll, :z_date, :z_description, :z_notes)");
                    $stmt->execute(array(
                        "z_id"      =>  $id,
                        "z_roll"    => $roll,
                        "z_date"    => $date,
                        "z_description" => $description,
                        "z_notes"   => $note
                    ));
                    $inserted = $stmt->rowCount();
                    if($inserted > 0){
                        $msg_log = "تم الإضافة بنجاح";
                        redirect($msg_log,"back",3); 
                    }

            }elseif(isset($_POST['add-new-file'])){

                $id = isset($_POST['dispute_id']) && is_numeric($_POST['dispute_id']) ? $_POST['dispute_id'] : 0;
                // New File To Dispute
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

                if(empty($ErrorsCatch)){
                    if(!empty($_FILES['files']['name'][0])){
                        $x = 0;
                        while($x < count($_FILES['files']['name'])){
                            $file_name = $_FILES['files']['name'][$x];
                            $file_type = $_FILES['files']['type'][$x];
                            $file_tmp  = $_FILES['files']['tmp_name'][$x];
                            $file_size = $_FILES['files']['size'][$x];
    
                            $avatar_name = rand(0,9999999) . "_" . $file_name;
                            move_uploaded_file($file_tmp,"upload\imgs\\".$avatar_name);
    
                            $stmt = $connect->prepare("INSERT INTO dispute_files(detail_id,name,type,date) VALUES (:z_id,:zname, :ztype, :zdate)");
                            $stmt->execute(array(
                                "z_id" => $id,
                                "zname" => $avatar_name,
                                "ztype" => $file_type,
                                "zdate" => date("Y-m-d"),
                            ));
                            $count = $x +1;
                            if($stmt->rowCount() > 0){
                                $msg_log = "تم إضافة الملفات $count بنجاح";
                            }else{
                                $msg_log = "حدث خطأ اثناء رفع ملف $count";
                            }
                            $message = "<div class='text-center mt-3 alert alert-info arabicFont'>".$msg_log."</div>";
                            echo $message;
                            $x++;    
                        }
                        header('refresh:3;url='.$_SERVER['HTTP_REFERER'].'');
                    }
                }else{
                    foreach($ErrorsCatch as $error){
                        echo "<div dir='rtl' class='text-center alert alert-danger mt-2 arabicFont'>".$error."</div>";
                    }
                }


            }elseif(isset($_POST['edit-dispute'])){
                $id = isset($_POST['dispute_id']) && is_numeric($_POST['dispute_id']) ? $_POST['dispute_id'] : 0;
                $check = checkItem("id","updates",$id);

                if($check > 0){
                    $roll = filter_var($_POST['dispute_roll'],FILTER_SANITIZE_STRING);
                    $date = filter_var($_POST['prefix__dispute_date__suffix'],FILTER_SANITIZE_STRING);
                    $description = filter_var($_POST['session_text'],FILTER_SANITIZE_STRING);
                    $note = filter_var($_POST['dispute_note'],FILTER_SANITIZE_STRING) ;
                    $id = isset($_POST['dispute_id']) && is_numeric($_POST['dispute_id']) ? $_POST['dispute_id'] : 0;

                
                    $stmt = $connect->prepare("UPDATE updates SET updates.roll_number = ?,
                                                updates.date = ?, updates.description = ?,
                                                updates.notes = ? 
                                                WHERE
                                                id = ?");
                    $stmt->execute(array($roll,$date,$description,$note,$id));
                    $inserted = $stmt->rowCount();
                    $msg_log = "تم الإضافة بنجاح";
                    redirect($msg_log,"back",3); 


                }else{
                    $msg_log = "القضية غير متوفرة";
                    redirect($msg_log,"back",3); 
                }

            }
            
        }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
            // View the page
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
                                <h3 class="arabicFont display-4 text-center">بحث عن قضية</h3>
                                <div class="advanced arabicFont">
                                    <i class="fas fa-caret-left"></i>
                                    <div class="search-types">
                                        <ul>
                                            <li><span>العنوان</span> <input custom-display="بحث بالإسم" name="search" value="title" type="radio" checked></li>
                                            <li><span>التاريخ</span><input custom-display="بحث بالتاريخ" name="search" value="date" type="radio"></li>
                                            <li><span>رقم الدعوي</span><input custom-display="بحث برقم الدعوي" name="search" value="ref_number" type="radio"></li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" class="searchwith" name="with" value="title">
                                <input type="text" style="direction:rtl" placeholder="بحث بالعنوان" class="search-dispute form-control arabicFont">
                                <div class="disputes-view" id="disputes" dir="rtl">

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
            }elseif($manage == "view" && isset($_GET['dispute_id'])){
                $dispute_id = is_numeric($_GET['dispute_id']) ? $_GET['dispute_id'] : 0;
                $check = checkItem("dispute_id","disputes",$dispute_id);
        
                if($check > 0){
                    $stmt = $connect->prepare("SELECT disputes.*, clients.name FROM disputes 
                    INNER JOIN clients 
                    ON
                    disputes.dclient_id = clients.client_id WHERE dispute_id = ?");
                    $stmt->execute(array($dispute_id));
                    $row = $stmt->fetch();
                    switch($row["client_type"]){
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
            
            
                    switch($row["en_type"]){
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
   <div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                    <div class="container arabicFont" dir="rtl">
                        <div class="disputes-control">
                            <a href="?action=close-dispute&dispute_id=<?php echo $row['dispute_id']?>" class="btn btn-info">إغلاق</a> 
                            <a href="dispute.php?action=edit-dispute&dispute_id=<?php echo $row['dispute_id']?>" class="btn btn-primary">تعديل</a> 

                        </div>
                        <!-- Dispute Infos -->
                        <div class="dispute-info">
                            <h3>بيانات القضيه</h3>
                            <ul>
                                <li>
                                    <span class="dis-info">موضوع القضية</span>
                                    <span><?php echo $row['title']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">رقم الدعوي</span>
                                    <span><?php echo $row['ref_number']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">محكمة</span>
                                    <span><?php echo $row['court']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">دائرة</span>
                                    <span><?php echo $row['district']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">إسم العميل</span>
                                    <span><?php echo $row['name']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">صفتة</span>
                                    <span><?php echo $c_type?></span>
                                </li>
                                <li>
                                   <span class="dis-info">إسم الخصم</span>
                                   <span> <?php echo $row['name']?> </span>
                                </li>
                                <li>
                                    <span class="dis-info">صفتة</span>
                                    <span><?php echo $e_type?></span>
                                </li>
                                <li>
                                    <span class="dis-info">عنوان</span>
                                    <span><?php echo $row['en_address']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">محامي الخصم</span>
                                    <span><?php echo $row['en_lawyer']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">تاريخ القضية</span>
                                    <span><?php echo $row['date']?></span>
                                </li>
                                <li>
                                    <span class="dis-info">الأتعاب</span>
                                    <span><?php echo $row['price']?></span>
                                </li>
                            </ul>
                        </div>
                        <!-- End disputes info -->
                        <!-- Start Payments -->
                        <div class="dispute-payments">
                                <h3>المدفوعات</h3>
                                
                        </div>
                        <!-- End Payments -->
                        <!-- Start Dispute Files -->
                        <div class="dispute-files">
                            <h3>ملفات القضيه</h3>
                            <?php 
                                $dispute_id = is_numeric($_GET['dispute_id']) ? $_GET['dispute_id'] : 0;
                                $stmt = $connect->prepare("SELECT name,id FROM dispute_files WHERE detail_id = ?");
                                $stmt->execute(array($dispute_id));
                                $attchs = $stmt->fetchAll();
                                foreach($attchs as $key => $value){
                            ?>
                                <div class="img-content d-flex mb-2">
                                    <span id="myImg" class="myImg" src="upload/imgs/<?php echo $attchs[$key]['name']?>">ملف <?php echo $key + 1?></span>
                                    <a href="?action=delete_img&img_id=<?php echo $attchs[$key]['id'] ?>&name=<?php echo $attchs[$key]['name']?>"><span class="delete-file">X</span></a>

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
                                        
                                           
                        <!-- End Dispute Fiels -->
<?php 
// SELECT DATA FOR DISPUTE
    $stmt = $connect->prepare("SELECT * FROM updates WHERE dispute_id = ?");
    $stmt->execute(array($dispute_id));
    $rows = $stmt->fetchAll();

?>
                        <!-- Start Dispute Trace -->
                        <div class="dispute-trace">
                            <h3>تتبعات القضيه</h3>
                            <div class="responsive-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th scope="col" style="width:10%">رقم الرول</th>
                                        <th scope="col" style="width:10%">تاريخ الجلسة</th>
                                        <th scope="col" style="width:35%">ما تم فيها من دفاع</th>
                                        <th scope="col" style="width:35%">ملاحظات</th>
                                        <th scope="col" style="width:10%">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($rows as $key => $value){
                                    ?>
                                        <tr>
                                            <td><?php echo $rows[$key]["roll_number"]?></td>
                                            <td><?php echo $rows[$key]["date"]?></td>
                                            <td><?php echo $rows[$key]["description"]?></td>
                                            <td><?php echo $rows[$key]["notes"]?></td>
                                    <?php
                                    // SELECT ATTACHMENTS

                                    
                                    ?>        
                                            <td><a class="btn btn-primary" href="?action=edit&id=<?php echo $rows[$key]["id"]?>">تعديل</a></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Dispute Trace -->
                        <!-- Start Attachments -->
                        <div class="add-new-file">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" method="POST" enctype="multipart/form-data">
                                <h3>إضافة ملفات</h3>
                                <div class="multifile-data">
                                    <i class="fas fa-plus add-file"></i>
                                    <input type="file" class="form-control" name="files[]">
                                </div>
                                <input type="hidden" name="dispute_id" value="<?php echo $dispute_id; ?>">
                                <input value="إضافة الملفات" type="submit" name="add-new-file" class="btn btn-primary">    
                            </form>
                        </div>
                        <!-- End Attachments -->
                        <!-- Start Dispute Trace -->
                        <div class="dispute-news">
                            <h3>إضافة مستجدات</h3>
                            <div class="add-to-dispute">
                                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" dir="rtl">
                                    <input type="text" name="dispute_roll" placeholder="رقم الرول" class="form-control">
                                    <input type="text" dir="rtl" name="dispute_date" placeholder="تاريخ" class="form-control date-picker-exchange datepicker">
                                    <input type="hidden">
                                    <textarea placeholder="ما تم فيها من دفاع" name="session_text" class="form-control" id="" cols="30" rows="10"></textarea>
                                    <input type="text" name="dispute_note" placeholder="ملاحظات" class="form-control">
                                    <input type="hidden" name="dispute_id" value="<?php echo $dispute_id; ?>">
                                    <input type="submit" name="add-new" value="إضافة" class="btn btn-success">
                                </form>
                            </div>
                        </div>
                        <!-- End Dispute Trace -->
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
                    $msg_log = "القضيه غير متوفره في قاعدة البيانات";
                    redirect($msg_log,"back",3);
                }

            }elseif($manage == "edit"){
                $dispute_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $check = checkItem("id","updates",$dispute_id);

                
                if($check > 0){
                    $stmt = $connect->prepare("SELECT * FROM updates WHERE id = ?");
                    $stmt->execute(array($dispute_id));
                    $rows = $stmt->fetch();
                    ?>
   <div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                    <div class="container">
                        <div class="edit-dispute">
                            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" dir="rtl">
                                        <input type="text" name="dispute_roll" placeholder="رقم الرول" value="<?php echo $rows['roll_number']?>" class="form-control">
                                        <input type="text" dir="rtl" name="dispute_date" value="<?php echo $rows['date']?>" placeholder="تاريخ" class="form-control date-picker-exchange datepicker">
                                        <input type="hidden">
                                        <textarea placeholder="ما تم فيها من دفاع" name="session_text" class="form-control" id="" cols="30" rows="10"><?php echo $rows['description']?></textarea>
                                        <input type="text" name="dispute_note" value="<?php echo $rows['notes']?>" placeholder="ملاحظات" class="form-control">
                                        <input type="hidden" name="dispute_id" value="<?php echo $dispute_id ?>">
                                        <input type="submit" name="edit-dispute" value="تعديل" class="btn btn-primary">
                            </form>
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
                    $msg_log = "القضيه غير متوفره في قاعدة البيانات";
                    redirect($msg_log,"back",3);
                }

            }elseif($manage == "delete_img"){
                $file_id = is_numeric($_GET['img_id']) ? $_GET['img_id'] : 0;
                $check = checkItem("id","dispute_files",$file_id);
                if($check > 0){
                    $name = $_GET['name'];
                    $stmt = $connect->prepare("DELETE FROM dispute_files WHERE id = ?");
                    $stmt->execute(array($file_id));
                    if($stmt->rowCount() > 0){
                        unlink("upload/imgs/".$name);
                        $msg_log = "تم الحذف بنجاح";
                        redirect($msg_log,"back",3);
                    }else{
                        $msg_log = "حدث خطأ اثناء الحذف";
                        redirect($msg_log,"back",3);
                    }
                }else{
                    $msg_log = "لم نجد الملف المطلوب";
                    redirect($msg_log,"back",3);
                }
            }elseif($manage == "close-dispute"){
                $dispute_id = is_numeric($_GET['dispute_id']) ? $_GET['dispute_id'] : 0;
                $check = checkItem("dispute_id","disputes",$dispute_id);
                if($check > 0){
                    $stmt = $connect->prepare("UPDATE disputes SET dis_status = 0 WHERE dispute_id = ?");
                    $stmt->execute(array($dispute_id));
                    if($stmt->rowCount() > 0){
                        $msg_log = "تم ارشفة القضية بنجاح";
                        redirect($msg_log,"trace-dispute.php",3);
                    }else{
                        $msg_log = "حدث خطأ اثناء الأرشفة";
                        redirect($msg_log,"back",3);
                    }
                }

            }else{
                $msg_log = "حدث خطأ اثناء البحث";
                redirect($msg_log,"back",3);
            }
        }
        include $tmpl . "footer.php";
    }else{
        $title = 
        header("Location:login.php");
    }

?>