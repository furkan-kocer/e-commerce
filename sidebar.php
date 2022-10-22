<?php 
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }
?>
<div class="left-nav">
        <div class="logo-div">
          <label class="logo">qualify</label>
          <?php echo "<label class='session-name'>Welcome Admin:$_SESSION[name]</label>";?>
         </div>
                <div class="admin-nav"> 
                <ul>  
                           <li><a href="adminpanel.php"><i class="bi bi-house"></i><span>Admin panel</span></a></li>
                           <li><a href="category.php"><i class="bi bi-grid"></i><span>Category</span></a></li>
                          <li><a href="user.php"><i class="bi bi-person"></i><span>User</span></a></li>
                          <li><a href="product.php"><i class="bi bi-bag"></i><span>Product</span></a></li>
                          <li><a href="orderpagefadmin.php"><i class="bi bi-bag"></i><span>Order</span></a></li>
                          <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i><span>Log out</span></a></li>
                    </ul>              
                </div>
                <div class="profile_content">
                  <div class="profile">
                    <div class="profile_details">
                      <img src="images/profile.png" alt="">
                      <div class="name_job">
                        <div class="name">Furkan Koçer</div>
                        <div class="job">Computer Engineer</div>
                          </div>
                          </div>
                          </div>
                </div>
      </div>