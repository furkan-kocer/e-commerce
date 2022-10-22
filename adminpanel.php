
<?php 
session_start();
if($_SESSION['logged_in']==false){
  echo "You dont have permission to reach this page";
  header("refresh:2;login.php");
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }
 
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Admin Panel</title>
  <link rel="stylesheet" href="css/adminpanel.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
  <?php include "sidebar.php"; ?>
  
      <!-- right side of site -->
      <div class="main_content">
      <?php 
      echo "<div class='header'>This is Admin Panel Admin Name: $_SESSION[name] </div>"; 
      ?>
      <div class="center">
     
      <p class="large"> This is Admin Panel, in this panel you can  add, delete, update products, categories, admin and users. Furthermore you can see orders.</p>
      </div>
     
      </div>
      
</body>

</html>
