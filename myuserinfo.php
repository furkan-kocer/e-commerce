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
    $message=NULL;
    $message1=null;
    if(isset($_POST['save-form'])){
      $name=$conn->real_escape_string($_POST['name']);
      $surname=$conn->real_escape_string($_POST['surname']);
      $phone=$conn->real_escape_string($_POST['mphone']);
     $dateofbirh=$conn->real_escape_string($_POST['birth']);
     $gender=$conn->real_escape_string($_POST['gender']);
     $query="UPDATE user SET First_name='$name', Last_name='$surname', phone='$phone', gender='$gender', date_of_birth='$dateofbirh' WHERE User_id= $user_id";
     $update = mysqli_query($conn,$query);
     if($update){
       $success1="<div class='succ'>
       <label><i class='fa fa-check' aria-hidden='true'></i></label>
       <p >your user informations successfully updated</p>
     </div>";
     header("refresh:3;myuserinfo.php");
     }
     else{
       $message1= "Something went wrong.";
     }
     }
     if(isset($_POST['update-pass'])){
      $password=$conn->real_escape_string(md5($_POST['passwordofuser']));
      $cpassword=$conn->real_escape_string(md5($_POST['cpasswordofuser']));
      $npassword=$conn->real_escape_string(md5($_POST['npasswordofuser']));
      $query1=$conn->query("SELECT * FROM user WHERE User_id=$user_id AND password ='$password'");
      if($query1->num_rows ==0){  
        $message="<div class='message-box'>
        <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
        <p>invalid password try again</p>
      </div>";
      header("refresh:1;myuserinfo.php");
      }
      elseif($password!=$cpassword){
        $message="<div class='message-box'>
         <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
         <p>passwords not matching</p>
       </div>";
       header("refresh:1;myuserinfo.php");
      }
      else{
        $query2="UPDATE user SET password='$npassword' WHERE User_id= $user_id";
        $insert = mysqli_query($conn,$query2);
       if($insert){
        $success="<div class='succ'>
        <label><i class='fa fa-check' aria-hidden='true'></i></label>
        <p >your password changed succesfully.</p>
      </div>";
      header("refresh:3;myuserinfo.php");
    }
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
    if($rowget['First_name'] !=null){
        $fname=$rowget['First_name'];
        $lname=$rowget['Last_name'];
        $mphone=$rowget['phone'];
        $gen=$rowget['gender'];
        $maleselected="";
        $femaleselected="";
        if($gen =="male"){
          $maleselected="checked";
        }
        if($gen =="female"){
          $femaleselected="checked";
        }
        $dob=$rowget['date_of_birth'];
    }
   // else{
      //$name=$surname=$phone=$dateofbirh=$gender='';
    //}
?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Userinfo</title>
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
      <span class="headerprofile">My User Information</span>
      <div class="right-content">
      <div class="info">
      <?php echo  $success1;?>
      <?php echo  $message1;?>
      <form action="" method="post">
      <div class="con-flex">
      <div class="field space">
              <span>Name:</span>
              <input type="text"  name="name" placeholder="Name"  value="<?php echo $fname;?>" required>
            </div>
          
            <div class="field">
              <span>Surname:</span>
              <input type="text"  name="surname" placeholder="Surname" value="<?php echo $lname;?>" required>
            </div>
      </div>
            <div class="field">
              <span>Mobile Phone:</span>
              <input type="phone"  name="mphone" placeholder="Mobile Phone" value="<?php echo $mphone;?>" required>
           </div>
           <div class="field">
             <span>Dateofbirh:</span>
              <input type="date" placeholder="dateofbirh" name="birth" value="<?php echo $dob;?>" required>
            </div>
            <span>Gender:</span>
            <div class="field2">
             <input type="radio" name="gender" value="female" <?php echo $femaleselected;?>>
            <label for="female">Female</label><br>
            <input type="radio" name="gender" value="male" <?php echo $maleselected;?>>
            <label for="male">Male</label><br>
            </div>
            
            <div class="field">
          <input type="submit" name="save-form" value="save" />
            </div>
  </form>
      </div>
   
  <div class="pass-update">
  <?php echo  $success;?>
      <?php echo  $message;?>
    <form action="" method="post">
        <div class="field">
              <span>Current password:</span>
              <input type="password"  name="passwordofuser" required>
            </div>
            <div class="field">
              <span>New password:</span>
              <input type="password"  name="cpasswordofuser" required>
            </div>
            <div class="field">
              <span>New password again:</span>
              <input type="password"  name="npasswordofuser" required>
            </div>
            <div class="field">
          <input type="submit" name="update-pass" value="update" />
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