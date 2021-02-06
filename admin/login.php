<?php
    session_start();
    // Redirect to dashbord if already login 
    if(isset($_SESSION['id'])){
        header("Location:dashboard.php");
        exit;
    }
?>

<div class="login-bg">
<div class="container">
<?php
    $title = "تسجيل الدخول";
    include 'init.php';
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){
        $user = isset($_POST['username']) ? filter_var($_POST['username'],FILTER_SANITIZE_STRING) : 0;
        $pass = isset($_POST['password']) ? sha1($_POST['password']) : 0;
    
        $stmt = $connect->prepare("SELECT * FROM users WHERE user = ? AND pass = ? LIMIT 1");
        $stmt->execute(array($user,$pass));
        $count = $stmt->rowCount();
        if($count > 0){
            $row = $stmt->fetch();
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $msg_log = "تم تسجيل الدخول بنجاح";
            redirect($msg_log,"dashboard.php",3);
        }else{
            $msg = "<div class='text-center alert alert-danger alert-login' dir='rtl'>اسم المستخدم و كلمة المرور خطأ</div>";
        }
    }
?>
  
        <?php if(isset($msg)){echo $msg ;} ?>
        <div class="header-login">
            <div class="header-img">
                <img src="<?php echo $img . "courts-logo-png-6.png"?>" alt="Court" class="img-fluid">
            </div>
            <h3>مكـتب المحامي<br> حسام الشناوي</h3>
        </div>
        <div class="login">
            <div class="form-login" dir="rtl">
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                    <div class="input-field">
                        <label for="username"><i class="fas fa-user"></i></label>
                        <input id="username" name="username"  placeholder="اسم المستخدم او البريد الإليكتروني" type="text" class="form-control arabicFont" autocomplete="off">

                    </div>
                    <div>
                        <label for="password"><i class="fas fa-lock"></i></label>
                        <input id="password" name="password" placeholder="كلمة المرور" type="password" class="form-control arabicFont" autocomplete="current-password">
                    </div>
                    <input type="submit" name="submit" value="تسجيل دخول" class="form-control btn-primary arabicFont">
                </form>
            </div>
        </div>
    </div>
    </div>    
<?php
    include $tmpl . 'footer.php';
?>