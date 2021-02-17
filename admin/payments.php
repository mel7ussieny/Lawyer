<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = "المصاريف";
        include 'init.php';
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            // MANAGE POST REQUESTS
            if(isset($_POST['add-fees-office'])){
                $type = 0;
                $amount = $_POST['amount'];
                $pay_for = $_POST['pay_for'];
                $date = $_POST['prefix__dispute_date__suffix'];

                $stmt = $connect->prepare("INSERT INTO payments(type, amount, pay_for,date) VALUES(:z_type, :z_amount, :z_pay_for, :z_date)");
                $stmt->execute(array(
                    "z_type"    => $type,
                    "z_amount"  => $amount,
                    "z_pay_for" => $pay_for,
                    "z_date"      => $date  
                ));
                    
                if($stmt->rowCount() > 0){
                    $msg_log = "تمت الإضافة بنجاح";
                    redirect($msg_log,"back",1);
                }
            }elseif(isset($_POST['add-fees-dispute'])){
                $type = $_POST['type'];
                $dispute_id = $_POST['dispute'];
                $amount = $_POST['amount'];
                $pay_for = $_POST['pay_for'];
                $date = $_POST['prefix__dispute_date__suffix'];
                $check = checkItem("dispute_id","disputes",$dispute_id);
                if($check > 0){
                    $stmt = $connect->prepare("INSERT INTO payments(type,amount,pay_for,date,dispute_id,display) VALUE(:z_type, :z_amount, :z_pay_for, :z_date, :z_dispute,1)");
                    $stmt->execute(array(
                        "z_type"    => $type,
                        "z_amount"  => $amount,
                        "z_pay_for" => $pay_for,
                        "z_date"      => $date,
                        "z_dispute"   => $dispute_id
                    ));
                    $msg_log = "تمت الإضافة بنجاح";
                    redirect($msg_log,"back",3);
                }else{
                    $msg_log = "حدث خطأ أثناء العثور علي القضية";
                    redirect($msg_log,"back",3);
                }

            }   
        }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
            // MANAGE GET REQUESTS
            $manage = isset($_GET['action']) ? $_GET['action'] : "manage";
            if($manage == "manage"){
    ?>
        <div class="dashboard-bg">
            <div class="dashpage">
                <div class="row">
                    <div class="col-11">
                        <div class="container">
                            <div class="pay-type arabicFont">
                                <div class="pay-office">
                                    <a href="?action=fees&fees=office">مصاريف مكتب</a>
                                </div>
                                <div class="pay-dispute">
                                    <a href="?action=fees&fees=dispute">أتعاب و مصاريف قضية</a>
                                </div>
                                <div class="profit">
                                    <a href="?action=profit">الأرباح</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"><?php include $tmpl . 'menubar.php'?></div>
                </div>
            </div>
        </div>
<?php
        }elseif($manage == "fees"){
            if(isset($_GET['fees']) && $_GET['fees'] == "office"){
                $stmt = $connect->prepare("SELECT * FROM payments WHERE display = 0");
                $stmt->execute();
                $rows = $stmt->fetchAll();
?>
        <div class="dashboard-bg">
            <div class="dashpage">
                <div class="row">
                    <div class="col-11">
                        <div class="container">
                            <div class="office-fees arabicFont" dir="rtl">
                                <div class="fees-header">
                                    <h3>مصاريف المكتب<h3>
                                </div>              
                                <div class="fees-inputs">
                                    <form class="fees-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="number" name="amount" placeholder="المبلغ" class="form-control">
                                        <input type="text" name="pay_for"  placeholder="خاص ب" class="form-control">
                                        <input type="text" dir="rtl" name="dispute_date" id="date-picker-exchange" placeholder="تاريخ" class="form-control datepicker">
                                        <input type="hidden">
                                        <input type="submit" name="add-fees-office" value="إضافة" class="btn btn-primary">
                                    </form>
                                </div>
                                <div class="fees-office-table">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">التاريخ</th>
                                                <th scope="col">المدفوع</th>
                                                <th scope="col">خاص ب</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach($rows as $key => $value){
                                        ?>
                                            <tr>
                                                <td><?php echo $key + 1 ?></td>
                                                <td><?php echo $rows[$key]['date']?></td>
                                                <td><?php echo $rows[$key]['amount']?></td>
                                                <td><?php echo $rows[$key]['pay_for']?></td>
                                            </tr>
                                        <?php
                                            }
                                        
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"><?php include $tmpl . 'menubar.php'?></div>
                </div>
            </div>
        </div>
<?php
            }elseif(isset($_GET['fees']) && $_GET['fees'] == "dispute"){
?>
        <div class="dashboard-bg">
            <div class="dashpage">
                <div class="row">
                    <div class="col-11">
                        <div class="container">
                            <div class="office-fees arabicFont" dir="rtl">
                                <div class="fees-header">
                                    <h3>مصاريف قضية<h3>
                                </div>              
                                <div class="fees-inputs">
                                    <form class="fees-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <select name="type">
                                            <option selected disabled>نوع الصرف</option>
                                            <option value="1">أتعاب</option>
                                            <option value="0">مصروفات</option>
                                        </select>
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
                                        <input type="number" name="amount" placeholder="المبلغ" class="form-control">
                                        <input type="text" name="pay_for"  placeholder="خاص ب" class="form-control">
                                        <input type="text" dir="rtl" name="dispute_date" id="" placeholder="تاريخ" class="form-control date-picker-exchange datepicker">
                                        <input type="hidden">
                                        <input type="submit" name="add-fees-dispute" value="إضافة" class="btn btn-primary">
                                    </form>
                                </div>
                                <div class="fees-office-table">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">نوع</th>
                                                <th scope="col">التاريخ</th>
                                                <th scope="col">المدفوع</th>
                                                <th scope="col">خاص ب</th>
                                                <th scope="col">قضية</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $stmt = $connect->prepare("SELECT payments.*, disputes.title FROM payments INNER JOIN disputes ON 
                                            payments.dispute_id = disputes.dispute_id");
                                            $stmt->execute();
                                            $rows = $stmt->fetchAll();
                                            foreach($rows as $key => $value){
                                                $type = ($rows[$key]["type"] == 1 ) ? "أتعاب" : "مصاريف";
                                        ?>
                                            <tr>
                                                <td><?php echo $type?></td>
                                                <td><?php echo $rows[$key]['date']?></td>
                                                <td><?php echo $rows[$key]['amount']?></td>
                                                <td><?php echo $rows[$key]['pay_for']?></td>
                                                <td><a href="trace-dispute.php?action=view&dispute_id=<?php echo $rows[$key]['dispute_id']?>"><?php echo $rows[$key]['title']?></a></td>
                                            </tr>
                                        <?php
                                            }
                                        
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"><?php include $tmpl . 'menubar.php'?></div>
                </div>
            </div>
        </div>
<?php
            }else{
                header("Location:dashboard.php");
            }
        }elseif($manage == "profit"){
    ?>
    <div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                    <div class="container" dir="rtl">
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
                            <button class="get-profit btn btn-primary form-control">إظهار</button>
                        </div>
                        <div class="statistics">
                            <div class="total-profit">
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">نوع</th>
                                    <th scope="col">المبلغ</th>
                                    <th scope="col">خاص ب</th>
                                    <th scope="col">قضية</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-1"><?php include $tmpl . 'menubar.php'?></div>
            </div>
        </div>
    </div>
<?php                
        }
        }else{
            header("Loaction:login.php");
        }
    
    include $tmpl . 'footer.php';
    }else{
        header("Location:login.php");
    }
?>