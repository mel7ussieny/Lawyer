<?php
    session_start();
    if(isset($_SESSION['id'])){
        $title = 'لوحة التحكم';
        include 'init.php';
?>
    <div class="dashboard-bg">
        <div class="dashpage">
            <div class="row">
                <div class="col-11">
                    <div class="container">
                        <!-- Start ShortCuts -->
                        <div class="shortcuts arabicFont">
                            <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="dash-content content-disputes">
                                        <i class="fas fa-file"></i>
                                            <div class="include">
                                                <h3>القواضي</h3>
                                                <a href="trace-dispute.php"><span><?php echo getCount("dispute_id","disputes")?></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="dash-content content-clients p-4">
                                            <i class="fas fa-users"></i>
                                            <div class="include">
                                                <h3>الموكلين</h3>
                                                <a href="clients.php"><span><?php echo getCount("client_id","clients")?></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-4">
                                        <div class="dash-content content-updates">
                                            <i class="fas fa-recycle"></i>
                                            <div class="include">
                                                <h3>الجلسات</h3>
                                                <a href="trace-dispute.php"><span><?php echo getCount("id","updates")?></span></a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="notifications" dir="rtl">
                                        <div class="noti-header">
                                            <i class="fas fa-bell"></i>
                                            <span>الإشعارات (الجلسات)</span>
                                        </div>
                                        <div class="noti-content">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td scope="col" width="5%">#</td>
                                                    <td scope="col" width="10%">تاريخ</td>
                                                    <td scope="col" width="70%">ملحوظة</td>
                                                    <td scope="col" width="15%">القضية</td>
                                                </tr>
                                                <?php
                                                    $stmt = $connect->prepare("SELECT notes.*,disputes.title,disputes.dispute_id FROM notes
                                                    INNER JOIN disputes
                                                    ON
                                                    disputes.dispute_id = notes.dispute_id  
                                                    WHERE notes.dispute_id IS NOT NULL AND notes.date = ?");
                                                    $stmt->execute(array(date("Y-m-d")));
                                                    $rows = $stmt->fetchAll();
                                                ?>
                                                <tbody>
                                                    <?php
                                                        foreach($rows as $key => $value){
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $key + 1 ?></th>
                                                        <td><?php echo $rows[$key]["date"]?></td>
                                                        <td><?php echo $rows[$key]["note"]?></td>
                                                        <td><a href="trace-dispute.php?action=view&dispute_id=<?php echo $rows[$key]['dispute_id']?>"><?php echo $rows[$key]["title"]?></a></td>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="notifications" dir="rtl">
                                        <div class="noti-header">
                                            <i class="fas fa-bell"></i>
                                            <span>الإشعارات (شغل إداري)</span>
                                        </div>
                                        <div class="noti-content">
                                        <table class="table table-striped">
                                                <tr>
                                                    <td scope="col" width="5%">#</td>
                                                    <td scope="col" width="10%">تاريخ</td>
                                                    <td scope="col" width="85%">ملحوظة</td>
                                                </tr>
                                                <?php
                                                    $stmt = $connect->prepare("SELECT * FROM notes 
                                                    WHERE dispute_id IS NULL AND notes.date = ?");
                                                    $stmt->execute(array(date("Y-m-d")));
                                                    $rows = $stmt->fetchAll();
                                                ?>
                                                <tbody>
                                                    <?php
                                                        foreach($rows as $key => $value){
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $key + 1 ?></th>
                                                        <td><?php echo $rows[$key]["date"]?></td>
                                                        <td><?php echo $rows[$key]["note"]?></td>
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
                        </div>
                        <!-- End  ShortCuts-->
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
        $title = 'حدث خطأ';
        include 'init.php';
        header("Location:login.php");

    }
    include $tmpl . "footer.php";
?>