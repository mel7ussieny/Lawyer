<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'القواضي';
        include 'init.php';
?>
    <div class="dashboard-bg">

    <div class="dashpage">
        <div class="row">
            <div class="col-11">
                <div class="container">
<?php
// Control the Container to chanage the content of pag

        $action = isset($_GET['action']) ? $_GET['action'] : "manage";
        if($action == "manage"){

        
?>
        <div class="row d-flex justify-content-center">
            <div class="col-8 col-md-6 col-lg-4  add-dispute d-flex justify-content-around">
                <i class="fas fa-gavel"></i>
                <a href="?action=add"><span class='arabicFont'>إضافه قضية</span></a>
            </div>
        </div>
        <div class="container">
            <div class='clients-info'>
                <div class="search-advanced">
                    <h3 class="arabicFont display-4 text-center">الــتوكيلات</h3>
                    <input type="text" style="direction:rtl" placeholder="الإسم" class="search-bar form-control arabicFont">
                </div>
                <div class="clients" id="clients" dir="rtl">

                </div>
            </div>
        </div>
<?php
        }elseif($action == "add"){
?>
      <form action="?action=insert" method="POST" enctype="multipart/form-data" dir="rtl" class="arabicFont">
          <div class="form-card form-group col-12 col-lg-6 input-form text-center">
              <span class="display-5 text-dark">إضافه قضية جديد</span>
              <hr style="text-dark">
              <select id="select-client" name="client_id" placeholder="اختار العميل">
                  <option value="0" disabled selected>أختار العميل</option>
<?php
        $stmt = $connect->prepare("SELECT client_id,name FROM clients ORDER BY client_id DESC LIMIT 5");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach($rows as $key => $value){
            echo "<option value='".$rows[$key]['client_id']."'>".$rows[$key]['name']."</option>";
        }
?>
              </select>
              <select id="client-status" name="client_status" placeholder="صفة العميل">
                <option value="0" disabled selected>صفته</option>
                <option value="1">مدعي</option>
                <option value="2">مدعي عليه</option>
                <option value="3">مجني</option>
                <option value="4">مدني عليه</option>
                <option value="5">مستأنف</option>
                <option value="6">متسأنف ضده</option>
              </select>
              <input type="text" name="dis_title" class="form-control" placeholder="عنوان القضيه" autocomplete="off" required>
              <input type="text" name="dis_court" class="form-control" placeholder="محكمة" autocomplete="off" required>
              <input type="text" name="dis_district" class="form-control" placeholder="دائرة" autocomplete="off" required>
              <input type="text" name="dis_number" class="form-control" placeholder="رقم الدعوي" autocomplete="off">
              <input type="text" name="en_name" class="form-control" placeholder="إسم الخصم" autocomplete="off">
              <input type="text" name="en_address" class="form-control" placeholder="عنوان الخصم" autocomplete="off">

              <select id="enemy-status" name="enemy_status" placeholder="صفة الخصم">
                <option value="0" disabled selected>صفته</option>
                <option value="1">مدعي</option>
                <option value="2">مدعي عليه</option>
                <option value="3">مجني</option>
                <option value="4">مدني عليه</option>
                <option value="5">مستأنف</option>
                <option value="6">متسأنف ضده</option>
              </select>
              <input type="text" name="en_lawyer" class="form-control" placeholder="محامي الخصم" autocomplete="off">
              <input type="date" name="dis_date" class="form-control" class="date">
              <input type="number" name="dis_price" name="price" class="form-control" placeholder="الأتعاب" autocomplete="off">
              <!-- <input type="text" name="tags" id="input-tags" placeholder="التصنيف"> -->
              <input type="Submit" value="إضافه" class="btn-success form-control">
        </div>
      </form>
<?php
        }elseif($action == "insert"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $number     =   $_POST['dis_number'];
                $court      =   $_POST['dis_court'];
                $title      =   $_POST['dis_title'];
                $district   =   $_POST['dis_district'];
                $date       =   $_POST['dis_date']; 
                $c_id       =   $_POST['client_id'];
                $c_status   =   $_POST['client_status'];
                $e_name     =   $_POST['en_name'];
                $e_address  =   $_POST['en_address'];
                $e_lawyer   =   $_POST['en_lawyer'];
                $e_status   =   $_POST['enemy_status'];
                $price      =   $_POST['dis_price'];

                $ErrorsCatch    = [];

                if($c_id == 0){
                    $ErrorsCatch = "يجب إختيار العميل";
                }
                if($c_status == 0){
                    $ErrorsCatch = "يجب إخيتار صفة العميل";
                }
                if($e_status == 0){
                    $ErrorsCatch = "يجب إختيار صفة الخصم";
                }

                if(empty($ErrorsCatch)){
                    $stmt = $connect->prepare("INSERT INTO disputes(ref_number,court,title,district,date,dclient_id,client_type,en_name,en_address,en_lawyer,en_type,price,dis_status)
                                                VALUES (:z_ref, :z_court, :z_title, :z_district, :z_date, :z_dclient, :z_client_type, :z_en_name, :z_en_address, :z_en_lawyer, :z_en_type, :z_price, :z_dis_status)
                    ");
                    $stmt->execute(array(
                        "z_ref"         => $number,
                        "z_court"       => $court,
                        "z_title"       => $title,
                        "z_district"    => $district,
                        "z_date"        => $date,
                        "z_dclient"     => $c_id,
                        "z_client_type" => $c_status,
                        "z_en_name"     => $e_name,
                        "z_en_address"  => $e_address,
                        "z_en_lawyer"   => $e_lawyer,
                        "z_en_type"     => $e_status,
                        "z_price"       => $price,
                        "z_dis_status"  => 1
                    ));
                }else{
                    foreach($ErrorsCatch as $erro){
                        echo "<div dir='rtl' class='text-center alert alert-danger mt-2 arabicFont'>".$error."</div>";
                    }
                }


                
            }else{
                header("Location:dashboard.php");
            }

        }elseif($action == "edit"){
            $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) == TRUE ? $_GET['UserID'] : 0;

        
            $stmt = $connect->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute(array($UserID));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count > 0){ 

?>
          <form action="?action=update" method="POST" style="direction:rtl" class="input-form">
          <div class="form-card form-group col-sm-12 col-md-6 arabicFont" >
              <span class="display-4 text-dark">تعديل البيانات</span>
              <hr style="text-dark">
              <input type="hidden" name="usrid" value="<?php echo $row['id']?>">
              <input type="text"  name="user" class="form-control"   value="<?php echo $row['user']?>" placeholder="إسم المستخدم" required>
              <input type="hidden" name="oldpass" value="<?php echo $row['pass']?>">
              <input type="password"  name="newpass" class="form-control" placeholder="كلمةالمرور">
              <input type="text"  name="full" class="form-control"   value="<?php echo $row['name']?>"  placeholder="الإسم بالكامل" required>
              <input type="Submit" value="تعديل" class="btn-primary form-control" required>
        </div>
      </form>
<?php
            }
        }elseif($action == "update"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $id     = $_POST['usrid'];
                $user   = $_POST['user'];
                $full   = $_POST['full'];
                $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : $_POST['newpass'];
                $hashedpass = sha1($pass);
                
                $ErrorsCatch = [];
      
                // Validation Cathces
                if(strlen($user) < 4){
                    $ErrorsCatch[] = "إسم المستخدم يجب ان يكون اكبر من 4 أحرف";
                  }
                if(strlen($pass) < 5){
                    $ErrorsCatch[] = "يرجي مراجعه كلمة المرور مره أخري";
                }
                if(strlen($full) < 7){
                    $ErrorsCatch[] = "يرجي مراجعة الإسم مره أخري";
                }  
      
                if(empty($ErrorsCatch)){
                  $stmt = $connect->prepare("UPDATE users SET user = ? , name = ? , pass = ? WHERE id = ?");
                  $stmt->execute(array($user,$full,$hashedpass,$id));
        
                  $msg_log = "تم التعديل بنجاح";
                  redirect($msg_log,"back",3);
                }else{
                  foreach($ErrorsCatch as $error){
                    echo "<div class='text-center alert alert-danger mt-2 arabicFont'>".$error."</div>";
                  }
                  $msg_log = "حدث خطأ اثناء التعديل";
                  redirect($msg_log,"back",3);                  
                }
      
            }else{
                // UPDATE NOT WITH POST REQUEST
                $msg = "<div class='alert alert-danger col-sm-5 mr-auto ml-auto mt-3'>You don't have permission to access this page</div>";
                redirectPage($msg,3,"members.php");
            }
        }elseif($action == "delete"){
            $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) == TRUE ? $_GET['UserID'] : 0;
            $check = checkItem("id","users",$UserID);
            
            if($check > 0){
              $stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
              $stmt->execute(array($UserID));
              $msg_log = "تم الحذف بنجاح";
              redirect($msg_log,"back",3);
            }else{
                $msg_log = "حدث خطأ اثناء الحذف";
                redirect($msg_log,"back",3);
            }
        }
?>                    

                </div>
            </div>
            <div class="col-1">
                <?php include $tmpl . "menubar.php" ?>
            </div>
        </div>
    </div>
<?php

    }else{
        header("location: login.php");
        include 'init.php';

    }
    include $tmpl . "footer.php";

?>