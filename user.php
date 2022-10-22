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
require_once 'dbase.php';
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }
  $selectuser="SELECT * FROM user ORDER BY usertype ASC";
  $resultselectuser=mysqli_query($conn,$selectuser);
  if(isset($_GET['deleteuser'])){
    $id=$_GET['deleteuser'];
    mysqli_query($conn,"DELETE FROM user WHERE User_id=$id");
    header('location:user.php');
  }
  if(isset($_POST['changeusertype'])){
    $usertypechange=$conn->real_escape_string($_POST['usertypevalue']);
    $getidofuser=$conn->real_escape_string($_POST['getidofuser']);
    if($usertypechange =='admin'){
      $usertypechange='user';
      $sqlupdateusertype = "UPDATE user SET usertype='$usertypechange' WHERE User_id='$getidofuser'";
      $resultsql=mysqli_query($conn,$sqlupdateusertype);
      if($resultsql){
        header("location:user.php");
      }
    }
    else{
      $usertypechange='admin';
      $sqlupdateusertype = "UPDATE user SET usertype='$usertypechange' WHERE User_id='$getidofuser'";
      $resultsql=mysqli_query($conn,$sqlupdateusertype);
      if($resultsql){
        header("location:user.php");
      }
    }
    
  }
  //////////////////////////////////////////////////////////////

  $message=NULL;
if(isset($_POST['saveuservalues'])){
require_once('dbase.php');
$name=$email=$password=$usertype='';
 $name=$conn->real_escape_string($_POST['Username']);
 $email=$conn->real_escape_string($_POST['email']);
 $password=$conn->real_escape_string(md5($_POST['password']));
 $cpassword=$conn->real_escape_string(md5($_POST['cpassword']));
 $usertype=$conn->real_escape_string($_POST['typeselection']);
$query=$conn->query("SELECT * FROM user WHERE username ='$name'");
$query2=$conn->query("SELECT * FROM user WHERE email='$email'");
if($query->num_rows !=0){  
  $message="<div class='message-box'>
  <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
  <p>user already exists</p>
</div>";
}
elseif($query2->num_rows !=0){
  $message="<div class='message-box'>
   <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
   <p>email already exists</p>
 </div>";
}
elseif($password !=$cpassword){
  $message="<div class='message-box'>
   <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
   <p>Passwords are not matching</p>
 </div>";
}
else{
  $queryinsertuser = "INSERT INTO user (username, password,email,usertype) VALUES ('$name','$password','$email','$usertype')";
 $insertuser = mysqli_query($conn,$queryinsertuser);
 if($insertuser){
  $success="<div class='succ'>
  <label><i class='fa fa-check' aria-hidden='true'></i></label>
  <p >you added user succesfully.</p>
</div>";
header("refresh:1;user.php");
}
}
}
/////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>User</title>
    <link rel="stylesheet" href="css/adminpanel.css">
  <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="css/user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<?php include "sidebar.php"; ?>
<!-- add admin-->
<div class="pop-up" id="user-pop-up">
  <div class="pop-up-content changes">
    <form action="" method="POST">
    <div class="changefield" >
      <span>Username:</span>
      <input type="text" placeholder="Username" name="Username" value="<?php echo $name;?>" required>
      </div>
      <div  class="changefield">
      <span>E-mail:</span>
      <input type="email" placeholder="E-mail Address" name="email" value="<?php echo $email;?>" required>
      </div>
      <div  class="changefield">
      <span>Password:</span>
      <input type="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" title="Must contain at least one number and at least 6 or more characters" required />
      </div>
      <div  class="changefield">
      <span>Confirm Password:</span>
      <input type="password" name="cpassword" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" title="Must contain at least one number and at least 6 or more characters" required />
      </div>
      <div class="changefield">
      <span>Usertype:</span>
     <select name="typeselection" required>
     <option selected value="" disabled="">Select User Type</option>
       <option value="user">user</option>
       <option value="admin">admin</option>
    </select>
      </div>
      <div id="field">
      <a class="buton-try2" id="backto">back</a>
          <input type="submit" name="saveuservalues" value="add" />
    </div>
</form>
  </div>
</div>
<!--//////////////////////////////////////////////////////-->
<div class="wrapper">
    <span class="header-text">User table</span>
    <?php echo  $message; ?>
    <?php echo  $success;?>
  <a id="goto" class="button-try pointer">Add Admin</a>
    <table class="table1 table1change">
        <thead>
            <tr>
              <th>User_id</th>
              <th>Username</th>
              <th>E-mail</th>
              <th>Usertype</th>
              <th>Update&Delete</th>
        </thead>
        <tbody>
        <?php
          while ($row = mysqli_fetch_assoc($resultselectuser)) {
            $row['username']=htmlspecialchars($row['username']);//XSS protection
            $row['email']=htmlspecialchars($row['email']);//XSS protection
            $gettypeuser=$row['usertype'];
            if($gettypeuser=='admin'){
              $btncolor="button-color-for-admin";
            }
            else{
              $btncolor="";
            }
            ?>
          <tr>
          <td>#<?php echo $row['User_id'];?></td>
          <td><?php echo $row['username'];?></td>
          <td><?php echo $row['email'];?></td>
          <td>
            <form action="user.php" method="post">
              <input type="hidden" value="<?php echo $row['usertype'];?>" name="usertypevalue">
              <input type="hidden" value="<?php echo $row['User_id'];?>" name="getidofuser">  
            <button type="submit"  name="changeusertype" onclick="return confirm('Are you sure you want to change named <?php echo $row['username'];?> usertype ?')" class="button-try1 <?php echo $btncolor;?>"><?php echo $row['usertype'];?></button>
          </form>
          </td>
            <td class="button-row2">
             <a href="edituser.php?edituser=<?php echo $row['User_id'];?>" class="button-try1 button-try3 button-font" id="click-btn-product"><i class="bi bi-arrow-repeat"></i></a>
             <a href="user.php?deleteuser=<?php echo $row['User_id'];?>" onclick="return confirm('Are you sure you want to delete <?php echo $row['username'];?> ?')" class="button-try1 change-color-btn-red button-font1"><i class="bi bi-trash"></i></a>
            </td>
</tr>
<?php };?>
</tbody>
    </table>
  </div>
  <script src="js/user.js"></script>
</body>

</html>