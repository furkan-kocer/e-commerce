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
require('dbase.php');
$message=NULL;
if(isset($_POST['login'])){
$name1= $password1='';
$name1=$conn->real_escape_string($_POST['username1']);
$password1=$conn->real_escape_string(md5($_POST['pass1']));
$query1="SELECT * FROM user WHERE username='$name1' AND password='$password1'
";
$result1=mysqli_query($conn,$query1);
if(mysqli_num_rows($result1)>0){
    while($row=mysqli_fetch_assoc($result1))
    {
        $id=$row['User_id'];
        $name1=$row["username"];
        $usertype=$row["usertype"];  
        $_SESSION['id']=$id;
        $name1=htmlspecialchars($name1);//XSS check
        $_SESSION['name']=$name1;
        $_SESSION['logged_in']=true;
        $_SESSION['usertype']=$usertype;
        if($_SESSION['usertype']=='user'){
          header("Location:index.php");
        }
        elseif($_SESSION['usertype']=='admin'){
          header("Location:adminpanel.php");
        }
    }
   
}
elseif(mysqli_num_rows($result1) == 0){
 $message="<div class='message-box'>
   <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
   <p>invalid username or password</p>
 </div>";
}}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
    <title>User Login Page & Sign In - Qualify</title>
    <meta name="author" content="Furkan KOÇER">
      <meta name="description" content="Login Qualify to buy collectibles,antiques,comics and more.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">
  </head> 
  <body>
  <?php include "header.php" ?>
    <div class="registera">
      
    <div id="sign_in">
      <form action="" method="post">
        <span>Log In</span>
        <?php echo $message; ?>
        <div id="field">
          <label> <i class="fa fa-user-o" aria-hidden="true"></i></label>
          <input type="text"  name="username1" placeholder="Username" required />
        </div>

        <div id="field">
          <label><i class="fa fa-lock" aria-hidden="true"></i></label>
          <input type="password" name="pass1" placeholder="Password" required />
        </div>
      
     

        <div id="field">
          <input type="submit" name="login" value="Log in" />
        </div>
      </form>

      <p>
        Not a member?
        <button ><a class="register-inside" href="register.php">Sign up now</a></button>
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
      </p>
    </div>
   
    </div>
    <div class="wrap-footer"><?php include "footer.php" ?></div> 
    </body>
</html>
