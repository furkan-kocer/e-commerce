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
    }  if($_SESSION['logged_in']==false) {
        echo "You need to login to reach this page you will redirect to the login page in 5 seconds";
        header("refresh:5;login.php");
    }

require_once ('dbase.php');
        $user_id=$_SESSION['id'];
    if(isset($_POST['takeorder'])){
            $name=$conn->real_escape_string($_POST['fname']);
            $surname=$conn->real_escape_string($_POST['lname']);
            $phone=$conn->real_escape_string($_POST['phone']);
           $dateofbirh=$conn->real_escape_string($_POST['dob']);
           $country=$conn->real_escape_string($_POST['countryofuser']);
             $city=$conn->real_escape_string($_POST['cityofuser']);
                $province=$conn->real_escape_string($_POST['provinceofuser']);
            $address=$conn->real_escape_string($_POST['addressofuser']);
           $query="UPDATE user SET First_name='$name', Last_name='$surname', phone='$phone', country='$country', date_of_birth='$dateofbirh', city='$city', province='$province', address='$address' WHERE User_id= $user_id";
           $update = mysqli_query($conn,$query);
           $total_value=$conn->real_escape_string($_POST['price']);
            $_SESSION['sendvalue']=$total_value;
           if($update){
            header("Location:usage.php");
           }
           else{
            $message="<p>Something went wrong try again</p> ";
           }
    }
?>
<?php
 if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
    if($_SESSION['usertype']=='admin'){
      header("Location:adminpanel.php");
    }
    $getrelatedidinfos="SELECT * FROM user WHERE User_id=$user_id";
    $getresult=mysqli_query($conn,$getrelatedidinfos);
    $row = mysqli_fetch_assoc($getresult);
    $finame=$row['First_name'];
    $laname=$row['Last_name'];
    $mophone=$row['phone'];
    $dob=$row['date_of_birth'];
    if($row['address'] !=null){
        $country=$row['country'];
        $address=$row['address'];
        $city=$row['city'];
        $province=$row['province'];
    }
    if(isset($_POST['goorder'])){
        $total_value=$conn->real_escape_string($_POST['total_value']);
        $_SESSION['total_value_send']=$total_value;
    }
    ?>
<!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Order</title>  
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
      <link rel="stylesheet" href="css/phpicin.css"> 
      <link rel="stylesheet" href="css/order.css"> 
    </head>
    <body> 
   
        <section>
                    <div class="orderboxwrapper">
                      <?php echo $message;?>
                    <div class="leftuserinfo">
                        <form method="post" action="">
                            <div class="flex-container">
                            <div class="field">
                         <span>Name:</span>
                         <input type="text" placeholder="name" name="fname" value="<?php echo $finame;?>" required>
                        </div>  
                        <div class="field">
                         <span>Surname:</span>
                         <input type="text" placeholder="surname" name="lname" value="<?php echo $laname;?>"  required> 
                        </div>   
                            </div>
                            <div class="flex-container">
                         <div class="field">
                         <span>Phone:</span>
                        <input type="phone" placeholder="phone number" name="phone" value="<?php echo $mophone;?>" required> 
                        </div>             
                        <div class="field">
                        <span>Date Of Birth:</span>
                        <input type="date"  name="dob" value="<?php echo $dob;?>" required> 
                        </div>
                        </div>
                        <div class="flex-container">
                        <div class="field">
                        <span>Country:</span>
                        <input type="text"  name="countryofuser" value="<?php echo $country;?>" required>
                        </div>
                        <div class="field">
                        <span>City:</span>
                        <input type="text"  name="cityofuser" value="<?php echo $city;?>" required>
                        </div>
                        <div class="field">
                        <span>Province:</span>
                        <input type="text"  name="provinceofuser" value="<?php echo $province;?>" required>
                        </div>
                        </div>
                        <div class="field">
                         <span>Address:</span>
                         <textarea class="field area" name="addressofuser" placeholder="Give specific adress" required><?php echo $address;?></textarea>     
                         <input type="hidden" name="price" value="<?php echo $_SESSION['total_value_send'];?>">               
                         <input type="submit" name="takeorder" value="Save and Continue" />
                       </div>
                       </div>
                        </form>
                        </div>
                        </div>
                   
                
        </section>
    </body>
    </html>
    <?php };?>