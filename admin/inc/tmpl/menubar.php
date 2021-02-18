
    <!-- Menu bar -->
<div class='menubar'>
    <div class='menu' dir="rtl">      
        <div class='text-center'>
            <i class="fas fa-bars dashboard-icon"></i>

        </div>  
        <hr>
        <ul class="arabicFont">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span class='menu-item'>الرائيسية</span></a></li>
            <li><a href="members.php"><i class="fas fa-users-cog"></i><span class='menu-item'>المشرفين</span></a></li>
            <li><a href="clients.php"><i class="fas fa-users"></i><span class='menu-item'>الـموكلين</span></a></li>
            <li><a href="dispute.php"><i class="fas fa-gavel"></i><span class='menu-item'>القواضي</span></a></li>
            <li><a href="trace-dispute.php"><i class="fas fa-shoe-prints"></i><span class='menu-item'>تتبع القضيه</span></a></li>

            <li><a href="payments.php"><i class="fas fa-dollar-sign"></i><span class='menu-item'>المصاريف</span></a></li>
            <li class="list-noti"><span class="noti-numbers"><?php echo getCount("id","notes")?></span><a href="notifications.php"><i class="far fa-bell"></i><span class='menu-item'>الإشعارات</span></a></li>
            <li><a href="archive.php"><i class="fas fa-file-archive"></i><span class='menu-item'>الأرشيف</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span class='menu-item'>تسجيل خروج</span></a></li>
        </ul>
    </div>
</div>
    <!-- Menu bar -->
