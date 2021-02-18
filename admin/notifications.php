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
                        <div class="notification-page" dir="rtl">
                            <select id="select-type" name="select-type" class="select-type">
                            <option value="0"  selected disabled>النوع</option>
                            <option value="1">جلسات</option>
                            <option value="2">شغل إداري</option> 
                            </select>


                            <form class="sessions noti-type" action="?action=insert" method="POST">
                                <select name="dispute">
                                    <option value="0" selected disabled>إختر القضية</option>
                                    <?php   
                                        $stmt = $connect->prepare("SELECT dispute_id,title,ref_number FROM disputes");
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();
                                        print_r($rows);
                                        foreach($rows as $key => $value){
                                            echo "<option value='".$rows[$key]["dispute_id"]."'>".$rows[$key]["title"] . " | ".$rows[$key]['ref_number']."</option>";
                                        }
                                    ?>
                                </select>
                                <input type="text" name="note" class="form-control" placeholder="ملاحظات">
                                <input type="text" dir="rtl" name="dispute_date" placeholder="تاريخ" class="form-control date-picker-exchange datepicker" required>
                                <input type="submit" name="sessions" class="btn btn-success" value="إضافة">
                            </form>

                            <form class="administrative noti-type" action="?action=insert" method="POST">
                                <input type="text" name="note" class="form-control" placeholder="ملاحظات">
                                <input type="text" dir="rtl" name="dispute_date" placeholder="تاريخ" class="form-control date-picker-exchange datepicker" required>
                                <input type="submit" name="administrative" class="btn btn-success" value="إضافة">

                            </form>
                        </div>


                        <div class="all-notifications" dir="rtl">
                            <div class="static-date">
                                <div class="determine-date arabicFont">
                                <div class="start-date">
                                        <label for="date-end">من</label>
                                        <input type="text" name="date-start" dir="rtl" name="profit-date-start" placeholder="تاريخ" class="date-picker-exchange form-control datepicker">
                                        <input type="hidden">
                                </div>
                                <div class="end-date">
                                        <label for="date-start">الي</label>
                                        <input type="text" name="date-end" dir="rtl" name="profit-date-end" placeholder="تاريخ" class="date-picker-exchange form-control datepicker">
                                        <input type="hidden">
                                    </div>
                                </div>
                                <select name="show-type" class="show-type" >
                                    <option value="1">الكل</option>
                                    <option value="2">جلسات</option>
                                    <option value="3">شغل إداري</option>
                                </select>
                                <button class="get-notifications btn btn-primary form-control">إظهار</button>
                            </div>
                            <div class="statistics">
                                <div class="total-notifications">
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                        <th scope="col">التاريخ</th>
                                        <th scope="col">نوع</th>
                                        <th scope="col">ملاحظة</th>
                                        <th scope="col">قضية</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
        }elseif($manage == "insert"){
            if(isset($_POST['administrative'])){ 
                $note = $_POST['note'];
                $date = $_POST['prefix__dispute_date__suffix'];
                
                $stmt = $connect->prepare("INSERT INTO notes(note,date) VALUES(:z_note, :z_date)");
                $stmt->execute(array(
                    "z_note" => $note,
                    "z_date" => $date
                ));
                if($stmt->rowCount() > 0){
                    $msg_log = "تم الإضافة بنجاح";
                    redirect($msg_log,"back",1);
                }else{
                    $msg_log = "حدث خطأ اثناء الإضافة";
                    redirect($msg_log,"back",1);
                }
            }elseif(isset($_POST['sessions'])){
                $note = $_POST['note'];
                $date = $_POST['prefix__dispute_date__suffix'];
                $dispute_id = $_POST['dispute'];

                $check = checkItem("dispute_id","disputes",$dispute_id);
                if($check > 0){
                    $stmt = $connect->prepare("INSERT INTO notes(note,date,dispute_id) VALUES(:z_note, :z_date, :z_dispute_id)");
                    $stmt->execute(array(
                        "z_note" => $note,
                        "z_date" => $date,
                        "z_dispute_id" => $dispute_id
                    ));
                    if($stmt->rowCount() > 0){
                        $msg_log = "تم الإضافة بنجاح";
                        redirect($msg_log,"back",1);
                    }else{
                        $msg_log = "حدث خطأ اثناء الإضافة";
                        redirect($msg_log,"back",1);
                    }
                }else{
                    $msg_log = "حدث خطأ اثناء الإضافة";
                    redirect($msg_log,"back",1);
                }

            }else{
                header("Location:dashboard.php");
            }
        }
    }else{
        $title = 'حدث خطأ';
        include 'init.php';
        header("Location:login.php");

    }
    include $tmpl . "footer.php";
?>