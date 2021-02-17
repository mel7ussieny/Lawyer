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
        $stmt = $connect->prepare("SELECT client_id,name FROM clients ORDER BY client_id DESC");
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
                <option value="4">مجني عليه</option>
                <option value="5">مستأنف</option>
                <option value="6">متسأنف ضده</option>
              </select>
              <input type="text" name="dis_title" class="form-control" placeholder="موضوع القضية" autocomplete="off" required>
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
                <option value="4">مجني عليه</option>
                <option value="5">مستأنف</option>
                <option value="6">متسأنف ضده</option>
              </select>
              <input type="text" name="en_lawyer" class="form-control" placeholder="محامي الخصم" autocomplete="off">
              <input type="text" dir="rtl" name="dispute_date" placeholder="تاريخ" class="form-control date-picker-exchange datepicker" required>
              <input type="hidden">
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
                $date       =   $_POST['prefix__dispute_date__suffix']; 
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


                    $msg_log = "تم إضافه القضية بنجاح";
                    redirect($msg_log,"back",3);
                }else{
                    foreach($ErrorsCatch as $erro){
                        echo "<div dir='rtl' class='text-center alert alert-danger mt-2 arabicFont'>".$error."</div>";
                    }
                }


                
            }else{
                header("Location:dashboard.php");
            }

        }elseif($action == "edit-dispute"){
            $id = isset($_GET['dispute_id']) && is_numeric($_GET['dispute_id']) == TRUE ? $_GET['dispute_id'] : 0;

        
            $stmt = $connect->prepare("SELECT * FROM disputes WHERE dispute_id = ? LIMIT 1");
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count > 0){ 

?>
<form action="?action=update-dispute" method="POST" enctype="multipart/form-data" dir="rtl" class="arabicFont">
          <div class="form-card form-group col-12 col-lg-6 input-form text-center">
              <span class="display-5 text-dark">تعديل قضية</span>
              <hr style="text-dark">
              <input type="text" name="dis_title" class="form-control" value="<?php echo $row['title']?>" placeholder="موضوع القضية" autocomplete="off" required>
              <input type="text" name="dis_court" class="form-control" value="<?php echo $row['court']?>" placeholder="محكمة" autocomplete="off" required>
              <input type="text" name="dis_district" class="form-control" value="<?php echo $row['district']?>" placeholder="دائرة" autocomplete="off" required>
              <input type="text" name="dis_number" class="form-control" value="<?php echo $row['ref_number']?>" placeholder="رقم الدعوي" autocomplete="off">
              <input type="text" name="en_name" class="form-control" value="<?php echo $row['en_name']?>" placeholder="إسم الخصم" autocomplete="off">
              <input type="text" name="en_address" class="form-control" value="<?php echo $row['en_address']?>" placeholder="عنوان الخصم" autocomplete="off">
              <input type="text" name="en_lawyer" value="<?php echo $row['en_lawyer']?>" class="form-control" placeholder="محامي الخصم" autocomplete="off">
              <input type="hidden" name="dispute_id" value="<?php echo $row['dispute_id']?>">
              <input type="number" value="<?php echo $row['price']?>" name="dis_price" name="price" class="form-control" placeholder="الأتعاب" autocomplete="off">
              <input type="Submit" value="إضافه" class="btn-success form-control">
        </div>
      </form>
<?php
            }else{
                $msg_log = "القضيه غير متوفره في قاعدة البيانات";
                redirect($msg_log,"back",3);
            }
        }elseif($action == "update-dispute"){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $id     = isset($_POST['dispute_id']) && is_numeric($_POST['dispute_id']) ? $_POST['dispute_id'] : 0;
                $check = checkItem("dispute_id","disputes",$id);
                if($check > 0){
                    $number     =   $_POST['dis_number'];
                    $court      =   $_POST['dis_court'];
                    $title      =   $_POST['dis_title'];
                    $district   =   $_POST['dis_district'];
                    $e_name     =   $_POST['en_name'];
                    $e_address  =   $_POST['en_address'];
                    $e_lawyer   =   $_POST['en_lawyer'];
                    $price      =   $_POST['dis_price'];
                    
                    $stmt = $connect->prepare("UPDATE disputes SET ref_number = ?, court = ?, title = ?, district = ?, en_name = ?, en_address = ?, en_lawyer = ?, price = ? WHERE dispute_id = ?");
                    $stmt->execute(array($number,$court,$title,$district,$e_name,$e_address,$e_lawyer,$price,$id));
                    if($stmt->rowCount() > 0){
                        $msg_log = "تم التعديل بنجاح";
                        redirect($msg_log,"back",3);
                    }else{
                        $msg_log = "لا يوجد تعديل جديد";
                        redirect($msg_log,"back",3);
                    }
                }else{
                    $msg_log = "القضيه غير متوفره في قاعدة البيانات";
                    redirect($msg_log,"back",3);
                }
        }}
?>                    

                </div>
            </div>
            <div class="col-1">
                <?php include $tmpl . "menubar.php" ?>
            </div>
        </div>
    </div>
<?php
    include $tmpl . "footer.php";
    }else{
        header("location: login.php");
    }

?>