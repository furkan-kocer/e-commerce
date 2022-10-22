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
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
  if($_SESSION['usertype']=='admin'){
    header("Location:adminpanel.php");
  }
}
$message=NULL;
if(isset($_POST['register'])){
require_once('dbase.php');
$name=$email=$password=$cpassword='';
 $name=$conn->real_escape_string(trim($_POST['username']));// prevent empty strings
 //$name=htmlspecialchars($name);//XSS protection->not good way use xss protection only outputting data of users!!!!
 $email=$conn->real_escape_string(trim($_POST['email']));// prevent empty strings
 if(!$name){
  $message="<div class='message-box'>
  <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
  <p>you need to fill your username.</p>
</div>";
 }
 else{
  $password=$conn->real_escape_string(md5($_POST['pass']));
  $cpassword=$conn->real_escape_string(md5($_POST['cpass']));
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
  elseif($password!=$cpassword){
    $message="<div class='message-box'>
     <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
     <p>passwords not matching</p>
   </div>";
  }
  else{
      $query = "INSERT INTO user (username, password,email)
      VALUES ('$name','$password','$email')";
     $insert = mysqli_query($conn,$query);
     if($insert){
      $success="<div class='succ'>
      <label><i class='fa fa-check' aria-hidden='true'></i></label>
      <p >you registered succesfully.</p>
    </div>";
    header("refresh:1;login.php");
  }
 }
}
}
else{
  $name=$email=$password=$cpassword='';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <title>User Register Page & Sign Up - Qualify</title>
    <meta name="author" content="Furkan KOÇER">
      <meta name="description" content="Register Qualify to buy collectibles,antiques,comics and more.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">
  </head>
  <?php include "header.php" ?>
  <body>
    <div class="registera">
    <div id="sign_up">
      <form action="" method="post">
        <span>Sign Up</span>
        <?php echo $message; ?>
        <?php echo  $success; ?>
        <div id="field">
          <label><i class="fa fa-user-o" aria-hidden="true"></i></label>
          <input type="text" name="username" placeholder="Username" value="<?php if($insert==true) {echo htmlspecialchars($name='');}  else {echo htmlspecialchars($name);} ?>" pattern=".{5,}" title="At least five characters" required />
        </div>

        <div id="field">
          <label><i class="fa fa-envelope-o" aria-hidden="true"></i></label>
          <input type="email" name="email" placeholder="Email Address" value="<?php if($insert==true) {echo htmlspecialchars($email='');}  else {echo htmlspecialchars($email);} ?>" required />
        </div>

        <div id="field">
          <label><i class="fa fa-lock" aria-hidden="true"></i></label>
          <input type="password" name="pass" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" title="Must contain at least one number and at least 6 or more characters" required />
        </div>

        <div id="field">
          <label><i class="fa fa-lock" aria-hidden="true"></i></label>
          <input type="password" name="cpass" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" title="Must contain at least one number and at least 6 or more characters" required />
        </div>
        
        <div id="field">
          <input type="submit" name="register" value="Register" />
        </div>
      </form>

      <p>
        Already a member?
        <button><a class="register-inside" href="login.php">Log in now</a></button>
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
      </p>
    </div>
  </div>
  </body>
  <div class="wrap-footer"><?php include "footer.php" ?></div>
</html>
