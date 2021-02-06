<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'التوكيلات';
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
        $stmt = $connect->prepare("SELECT * FROM users");
        $stmt->execute();
        $row = $stmt->fetchAll();
        
?>
        <div class="row d-flex justify-content-center">
            <div class="col-8 col-md-6 col-lg-4  add-contract d-flex justify-content-around">
                <i class="fas fa-user-plus"></i>
                <a href="?action=add"><span class='arabicFont'>إضافه تـوكيل</span></a>
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
              <span class="display-5 text-dark">إضافه تــوكيل جديد</span>
              <hr style="text-dark">
              <input type="text" name="name" class="form-control" placeholder="الإسم كامل" autocomplete="off" required>
              <input type="text" name="address" class="form-control" placeholder="العنوان" autocomplete="off" required>
              <input type="text" name="contract" class="form-control" placeholder="رقم التوكيل" autocomplete="off">
              <div class="multiphone-data">
                <i class="fas fa-plus add-phone"></i>
                <input type="number" name="phones[]" class="form-control" placeholder="رقم الهاتف" autocomplete="off">
              </div>
              <div class="multifile-data">
                <i class="fas fa-plus add-file"></i>
                <input type="file" class="form-control" name="files[]">
              </div>

              <input type="Submit" value="إضافه" class="btn-success form-control">
        </div>
      </form>
<?php
        }elseif($action == "insert"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                /*
                    Insert user 
                    1 - Insert the data to clients table
                    2 - get the last id that added to table
                    3 - add the attachments to identity table
                    4 - add the numbers to phones table
                */
                
                $name   = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
                $address   = filter_var($_POST['address'],FILTER_SANITIZE_STRING);
                $contract   = filter_var($_POST['contract'],FILTER_SANITIZE_NUMBER_INT);
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


                $count = checkItem("name","clients",$name);
                if($count > 0){
                    $ErrorsCatch[] = "يرجي مراجعة البيانات";
                }else{
                    if(strlen($name) < 4){
                        $ErrorsCatch[] = "الإسم يجب ان يكون اكبر من 4 أحرف";
                    }
                    if(strlen($address) < 5){
                        $ErrorsCatch[] = "يرجي مراجعه العنوان مره أخري";
                    }
                }
            if(empty($ErrorsCatch)){
                $stmt = $connect->prepare("INSERT INTO 
                clients(name, contract, address,date) 
                VALUES(:zname, :zcontract, :zaddress,:zdate) ");
                $stmt->execute(array(
                    "zname" => $name,
                    "zcontract" => $contract,
                    "zaddress" => $address,
                    "zdate" => date("Y-m-d"),
                ));
                $inserted = $stmt->rowCount();
                if($inserted > 0){
                    $stmt = $connect->prepare("SELECT client_id FROM clients ORDER BY client_id DESC LIMIT 1");
                    $stmt->execute();
                    $id = $stmt->fetch()[0];

                    // Insert Phones
                    foreach($_POST['phones'] as $key => $value){
                        $stmt = $connect->prepare("INSERT INTO phones(phone,client_id,date) VALUES (:zphone, :zc_id, :zdate)");
                        $stmt->execute(array(
                            "zphone"    => filter_var($value,FILTER_SANITIZE_NUMBER_INT),
                            "zc_id"     => $id,
                            "zdate"     => date("Y-m-d"),
                        ));
                    }

                    // Insert Files
                    if(!empty($_FILES['files']['name'][0])){
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
                }
                $msg_log = "تم التسجيل بنجاح";
                redirect($msg_log,"back",10);
             }else{
                foreach($ErrorsCatch as $error){
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