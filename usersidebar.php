<?php 
 if (!isset($_SESSION)) {
    session_start();
    }
    if($_SESSION['logged_in']==false) {
        echo "You need to login to reach this page you will redirect to the login page in 5 seconds";
        header("refresh:5;login.php");
    }
?>
<?php
 if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
    if($_SESSION['usertype']=='admin'){
      header("Location:adminpanel.php");
    }
?>
<aside class="left-nav">
                <div class="user-nav"> 
                <ul>  
                           <li><a href="addressinfo.php"><i class="bi bi-geo-alt"></i>Adress Information</span></a></li>
                          <li><a href="myuserinfo.php"><i class="bi bi-person"></i><span>User Information</span></a></li>
                          <li><a href="myorders.php"><i class="bi bi-bag"></i><span>Orders</span></a></li>
                    </ul>              
                </div>
</aside>
                <?php } ?>