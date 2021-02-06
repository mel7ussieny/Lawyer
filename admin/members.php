<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'المشرفين';
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
            <div class="col-8 col-md-6 col-lg-4  add-member d-flex justify-content-around">
                <i class="fas fa-user-plus"></i>
                <a href="?action=add"><span class='arabicFont'>إضافه مشرف</span></a>
            </div>
        </div>
        <div class="container">
        <div class="table-responsive mt-5">
        <table class="table table-bordered text-center table-sm arabicFont">
          <thead class="thead-light">
            <tr>
              <th>ID</th>
              <th>إسم المستخدم</th>
              <th>الإسم</th>
              <th>تاريخ التسجيل</th>
              <th>حذف / تعديل</th>
            </tr>  
          <thead>
          <tbody>
    <!-- START DATA OF USERS -->
<?php
foreach($row as $key => $value){
  echo "<tr class='text-center'>";
  echo "<td>".$row[$key]['id']."</td>";
  echo "<td>".$row[$key]['user']."</td>";
  echo "<td>".$row[$key]['name']."</td>";
  echo "<td>".$row[$key]['date']."</td>";
  echo "<td>
          <a href='?action=delete&UserID=".$row[$key]['id']."' class='confirm btn btn-danger btn-sm'>
            <i class='fas fa-user-times'></i>  
            حذف
          </a>
          <a href='?action=edit&UserID=".$row[$key]['id']."' class='btn btn-primary btn-sm'>
            <i class='fas fa-edit'></i>
            تعديل
          </a>";
  echo "</td>";
  echo "</tr>";

?>
    <!-- END DATA OF USERS -->

            
<?php } // END THE FOREACH LOOP OF EXECUTE DATA


?>
          </tbody>
        </table>
      </div>
    </div>
<?php
        }elseif($action == "add"){
?>
      <form action="?action=insert" method="POST" enctype="multipart/form-data" dir="rtl" class="arabicFont">
          <div class="form-card form-group col-sm-12 col-md-6 input-form text-center">
              <span class="display-5 text-dark">إضافه مشرف جديد</span>
              <hr style="text-dark">
              <input type="text" name="user" class="form-control" placeholder="اسم المستخدم" autocomplete="off" required>
              <input type="password" name="newpass" class="form-control" placeholder="كلمة المرور" autocomplete="new-password">
              <input type="text" name="full" class="form-control" placeholder="الإسم بالكامل" autocomplete="off" required>
              <input type="Submit" value="إضافه" class="btn-success form-control">
        </div>
      </form>
<?php
        }elseif($action == "insert"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $user   = $_POST['user'];
                $full   = $_POST['full'];
                $pass   = $_POST['newpass'];
                $hashedpass = sha1($pass);
                $ErrorsCatch = [];
                
                $count = checkItem("user","users",$user);
                if($count > 0){
                    $ErrorsCatch[] = "يرجي مراجعة البيانات";
                }else{
                    if(strlen($user) < 4){
                        $ErrorsCatch[] = "إسم المستخدم يجب ان يكون اكبر من 4 أحرف";
                      }
                      if(strlen($pass) < 5){
                        $ErrorsCatch[] = "يرجي مراجعه كلمة المرور مره أخري";
                      }
                      if(strlen($full) < 7){
                        $ErrorsCatch[] = "يرجي مراجعة الإسم مره أخري";
                      }  
                }
             if(empty($ErrorsCatch)){
                $stmt = $connect->prepare("INSERT INTO 
                users(user, pass, name,perimission,date) 
                VALUES(:zuser, :zpass, :zfull,1,:zdate) ");
                $stmt->execute(array(
                "zuser" => $user,
                "zpass" => $hashedpass,
                "zfull" => $full,
                "zdate" => date("Y-m-d"),
                ));

                $msg_log = "تم التسجيل بنجاح";
                redirect($msg_log,"back",3);
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