<?php
require('dbase.php');
  session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }

  // User Edit///
  if(isset($_GET['edituser'])){
    $iduser=$_GET['edituser'];
    $selectfuser="SELECT * FROM user WHERE User_id=$iduser";
    $queryselectorresult=mysqli_query($conn,$selectfuser);
    $row=mysqli_fetch_assoc($queryselectorresult);
  }
  if(isset($_GET['edituser']) && isset($_POST['updateuser'])){
    $updateuname=$conn->real_escape_string($_POST['Username']);
    $updatemail=$conn->real_escape_string($_POST['email']);
    $querycheck1=$conn->query("SELECT * FROM user WHERE username ='$updateuname'");
    $querycheck2=$conn->query("SELECT * FROM user WHERE email='$updatemail'");
    $userchanged=true;
    $emailchanged=true;
    /*check if user changed input or not first*/
    if($updateuname!=$row['username']){
        /*if changed do below statement*/
        if($querycheck1->num_rows !=0){  
            $message="<div class='message-box'>
            <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
             <p>user already exists</p>
            </div>";
            $userchanged=false;
            }
    }
    /*check if user changed email or not first*/
    if($updatemail!=$row['email']){
        /*if changed do below statement*/
        if($querycheck2->num_rows !=0){
            $message="<div class='message-box'>
            <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
             <p>email already exists</p>
            </div>";
            $emailchanged=false;
            }
    }
    /* Below statement can be executed three different actions which are if user not change any input, if user changed email or username and both then do below code*/
    if($userchanged==true && $emailchanged==true){
    $query="UPDATE user SET email='$updatemail', username='$updateuname' WHERE User_id=$iduser";
    $update = mysqli_query($conn,$query);
    if($update){
      $success="<div class='succ'>
      <label><i class='fa fa-check' aria-hidden='true'></i></label>
      <p >user successfully updated</p>
    </div>";
    header("refresh:1;user.php");
    }
}
     
  }
  ///////////////////////////////////////////////////////
  ?>
  <!DOCTYPE html>
<html lang="en">
  <head>
    <title>Edit User</title>
    <link rel="stylesheet" href="css/addproduct.css">
    <link rel="stylesheet" href="css/editcategory.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<?php if(isset($_GET['edituser'])){ ?>
    <div class="wrapper">
        <div class="product-content">
            <?php echo $success;?>
            <?php echo $message;?>
        <form action="" method="POST">
    <div class="first-div">
      <span>Username:</span>
      <input type="text" placeholder="Username" value="<?php echo $row['username'];?>" name="Username"  required>
      </div>
      <div class="first-div">
      <span>E-mail:</span>
      <input type="text" placeholder="E-mail" value="<?php echo $row['email'];?>" name="email" required>
      </div>
      <div class="first-container change">
      <a class="button-try" href="user.php">back</a>
          <input type="submit" name="updateuser" value="Update" class="button-try" />
    </div>
</form>
        </div>
    </div>
<?php };?>
</body>
</html>