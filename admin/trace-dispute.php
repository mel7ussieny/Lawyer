<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = "تتبع القضيه";
        include 'init.php';
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            // Upload updates of dispute
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
                        <!-- Dispute Infos -->
                        <div class="dispute-info">
                            <h3>بيانات القضيه</h3>
                            <ul>
                                <li>
                                    <span class="dis-info">عنوان القضية</span>
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
                        <!-- Start Dispute Trace -->
                        <div class="dispute-trace">
                            <h3>تتبعات القضيه</h3>
                            <div class="responsive-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th scope="col" style="width:10%">رقم الرول</th>
                                        <th scope="col" style="width:10%">تاريخ الجلسة</th>
                                        <th scope="col" style="width:40%">ما تم فيها من دفاع</th>
                                        <th scope="col" style="width:30%">ملاحظات</th>
                                        <th scope="col" style="width:10%">ملفات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>FLYing</td>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                            <td>s</td>
                                        </tr>
                                        <tr>
                                            <td>FLYing</td>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                            <td>s</td>
                                        </tr>
                                        <tr>
                                            <td>FLYing</td>
                                            <td>Larry</td>
                                            <td>the Bird</td>
                                            <td>Fly</td>
                                            <td>s</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Dispute Trace -->
                        <!-- Start Dispute Trace -->
                        <div class="dispute-news">
                            <h3>إضافة مستجدات</h3>
                            <div class="add-to-dispute">
                                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data" dir="rtl">
                                    <input type="text" name="dispute_roll" placeholder="رقم الرول" class="form-control">
                                    <input type="text" dir="rtl" name="dispute_date" id="date-picker-exchange" placeholder="تاريخ" class="form-control datepicker">
                                    <textarea placeholder="ما تم فيها من دفاع" name="session_text" class="form-control" id="" cols="30" rows="10"></textarea>
                                    <input type="text" name="dispute_note" placeholder="ملاحظات" class="form-control">
                                    <div class="multifile-data">
                                        <i class="fas fa-plus add-file"></i>
                                        <input type="file" class="form-control" name="files[]">
                                    </div>
                                    <input type="submit" value="إضافة" class="btn btn-primary">
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

            }
        }
        include $tmpl . "footer.php";
    }else{
        $title = 
        header("Location:login.php");
    }

?>