<?php 
if($_SESSION['usertype']=='admin'){
  header("Location:adminpanel.php");
}
else{
  if (!isset($_SESSION)) {
    session_start();
    }
  require('dbase.php');
  if(isset($_GET['show'])){
      $idshow=(int)($_GET['show']);//-> validation only integer numbers
      $selective=mysqli_query($conn,"SELECT * FROM product WHERE product_id=$idshow");
      
      //// For title name only ////
      $idshow2=(int)($_GET['show']);//-> validation only integer numbers
      $selective2=mysqli_query($conn,"SELECT * FROM product WHERE product_id=$idshow2");   
      if(mysqli_num_rows($selective2)>0){
      $row2=mysqli_fetch_assoc($selective2);
      $title=$row2['product_name'];
      $title2=" Price";
    }
      else{
        $title="showproductindetail";
        $title2="";
      }
      ///////////////////////////       
    }
    if(isset($_POST['addtc'])){  
      if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
        $sendid=$conn->real_escape_string($_POST['idofpro']);
        $quantityofptable=$conn->real_escape_string($_POST['quantityofptable']);//new added
        $user_id=$_SESSION['id'];
        $checkquery="SELECT * FROM cart_item WHERE product_id ='$sendid' AND  cart_item.User_id=$user_id";
    $check=mysqli_query($conn,$checkquery);
    if($check->num_rows !=0){  
      $row2 = mysqli_fetch_assoc($check);
      if($row2['quantity']<$quantityofptable){
        $quantity1= $row2['quantity'];
        $quantity1=$quantity1+1;
        $data="UPDATE cart_item SET quantity='$quantity1' WHERE product_id ='$sendid' AND  cart_item.User_id=$user_id";
        $dataset=mysqli_query($conn,$data);
        if($dataset){
         $message="Your product has already been on the cart so its quantity increased one";
         header("refresh:2;mycart.php");
        }
      }
      else{
        $message="
        <p>You reached the limit of product quantity</p>
               ";
               header("refresh:1");
      }
     
      }
      else{ $query="INSERT INTO cart_item (product_id,User_id) VALUES ('$sendid','$user_id')";
        $result=mysqli_query($conn,$query);
        if($result){
          $message= "Your product succesfully added to cart";
          header("refresh:2;mycart.php");
        }}
     
    }
    if($_SESSION['logged_in'] ==false){
      $message="You must login to add product to cart";
      header("refresh:3;showproductindetail.php?show=$idshow");
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
        <title><?php echo $title,$title2;?></title>  
      <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/footer.css">
      <link rel="stylesheet" href="css/showindetail.css">
</head>
<body>
<?php include "header.php"?>
<section>
<?php
        echo "<p class='messagep'>$message</p>";
                    if(mysqli_num_rows($selective)>0){
                     while($row=mysqli_fetch_assoc($selective)) {
                       $title=$row['product_name'];
                        ?>
  <div class="flex-container">
  <div class="showdetailpro">
   <div class="showimgbigscreen">
   <img src="images/product-images/<?php echo  $row['image_url'];?>" class="show-img-big">
   </div>
   <div class="rghtdiv">
        <ul>
        <li class="li-color1"><?php echo $row['quantity']; ?> in stock</li>
          <li><?php echo $row['product_name']; ?></li>
          <li class="li-color"><?php echo $row['price'];?> TL</li>
    </ul>
    <div class="sendform">
    <form action="" method="POST">
      <input type="hidden" name="idofpro" value="<?php echo $idshow; ?>">
     <input type="hidden" value="<?php echo $row['quantity'];?>" name="quantityofptable">   
      <input type="submit" value="Add to cart" name="addtc" class="button-try">
    </form>
    </div>
    <div class="showdetails">
      <p><span>Details:</span><?php echo $row['description'];?></p>
    </div>
   </div>
 </div>
  </div>
 <?php  }} else{
     echo "<div class='showmessagesite'><span class='noresult'>There is no product matches for this id</span></div>";
 };?>
</section>
<?php include "footer.php" ?>
</body>
</html>