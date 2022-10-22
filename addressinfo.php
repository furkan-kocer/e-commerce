<?php
if (!isset($_SESSION)) {
  session_start();
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['postdata'] = $_POST;
  unset($_POST);
  header("Location: ".$_SERVER['PHP_SELF']);
  exit;
  }
  if ($_SESSION['postdata']){
  $_POST=$_SESSION['postdata'];
  unset($_SESSION['postdata']);
  }
    if($_SESSION['logged_in']==false) {
        echo "You need to login to reach this page you will redirect to the login page in 5 seconds";
        header("refresh:5;login.php");
    }
    require_once('dbase.php');
    $user_id=$_SESSION['id'];
if(isset($_POST['update-address'])){
 $country=$conn->real_escape_string($_POST['country']);
 $city=$conn->real_escape_string($_POST['city']);
 $province=$conn->real_escape_string($_POST['province']);
$address=$conn->real_escape_string($_POST['address']);
$query="UPDATE user SET country='$country', city='$city', address='$address', province='$province' WHERE User_id= $user_id";
$update = mysqli_query($conn,$query);
if($update){
  $success="<div class='succ'>
  <label><i class='fa fa-check' aria-hidden='true'></i></label>
  <p >your address successfully updated</p>
</div>";
header("refresh:1;addressinfo.php");
}
else{
  echo "Something went wrong.";
}
}

?>
<?php
 if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
    if($_SESSION['usertype']=='admin'){
      header("Location:adminpanel.php");
    }
    $getdata="SELECT * FROM user WHERE User_id=$user_id";
    $getdataque=mysqli_query($conn,$getdata);
    $rowget = mysqli_fetch_assoc($getdataque);
    if($rowget['country'] !=null){
        $countryu=$rowget['country'];
        $addressu=$rowget['address'];
        $cityu=$rowget['city'];
        $provinceu=$rowget['province'];
    }
    
?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>My Address Information</title>
        <link rel="stylesheet" href="css/phpicin.css">
      <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="css/usersidebar.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/footer.css">
    </head>
    <body> 
    <?php include "header.php"?>
    <section>
    <div class="flex-content">
      <?php include "usersidebar.php"?>
      <div class="give-margin-top">  
      <span class="headerprofile">My Address Information</span>
      <div class="right-content">
  <div class="pass-update center">
  <?php echo  $success;?>
    <form action="" method="post">
        <div class="field">
              <span>Country:</span>
              <input type="text"  name="country" value="<?php echo $countryu;?>" required>
            </div>
            <div class="field">
              <span>City:</span>
              <input type="text"  name="city" value="<?php echo $cityu;?>" required>
            </div>
            <div class="field">
              <span>Province:</span>
              <input type="text"  name="province" value="<?php echo $provinceu;?>" required>
            </div>
            <div class="field">
              <span>Address:</span>
              <textarea class="field area" name="address" placeholder="Give specific adress" required><?php echo $addressu;?></textarea>
            </div>
            <div class="field">
          <input type="submit" name="update-address" value="update" />
            </div>
  </form>
      </div>
      </div>
  </div>
  </div>
    </section> 
    <?php include "footer.php"; ?>
    </body>
</hmtl>
<?php } ?>