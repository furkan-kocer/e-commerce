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
require('dbase.php');
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  if($_SESSION['usertype']=='admin'){
    header("Location:adminpanel.php");
  }
 if($_SESSION['usertype']=='user'){
    $user_id=$_SESSION['id'];
 }
  $takedata="SELECT * FROM product,cart_item WHERE product.product_id=cart_item.product_id AND  cart_item.User_id=$user_id";
  $select=mysqli_query($conn,$takedata);
  $showbox="SELECT * FROM cart_item WHERE cart_item.User_id=$user_id";
  $addshowbox=mysqli_query($conn,$showbox);
  if($addshowbox->num_rows ==0 ){
    $showcart="
    <div class='cartbox'>
     <div class='together'>
     <i class='bi bi-cart3'></i>
     <p>There is no item in the cart.</p>
     </div>
     <a href='index.php'>Start Shopping</a>
   </div>";
  }
 //increase or decrease quantity of product in cart session within buttons
  if(isset($_POST['plus'])){
    $productid=$conn->real_escape_string($_POST['getidofproduct']);
    $sendquantity=$conn->real_escape_string($_POST['sendquantity']);
    $totalquantity=$conn->real_escape_string($_POST['totalquantity']);
    if($sendquantity<$totalquantity){
      $increase=$sendquantity;
      $increase=$increase+1;
      $dataupdate="UPDATE cart_item SET quantity=$increase WHERE product_id =$productid AND  cart_item.User_id=$user_id ";
     $datasetupdate=mysqli_query($conn,$dataupdate);
     if($datasetupdate){
      header("location:mycart.php");
     }
    }
    else{
      $message="
      <p>You reached the limit of product quantity</p>
             ";
             header("refresh:1;mycart.php");
    }
   
  }

     if(isset($_POST['minus'])){
      $productid=$conn->real_escape_string($_POST['getidofproduct']);
      $sendquantity=$conn->real_escape_string($_POST['sendquantity']);
      if($sendquantity>1){
        $decrease=$sendquantity;
        $decrease=$decrease-1;
        $dataupdate="UPDATE cart_item SET quantity=$decrease WHERE product_id =$productid AND  cart_item.User_id=$user_id ";
       $datasetupdate=mysqli_query($conn,$dataupdate);
       if($datasetupdate){
        header("location:mycart.php");
       }
      }
     }
}
 
if(isset($_GET['deletecart'])){
  $id=$_GET['deletecart'];
  mysqli_query($conn,"DELETE FROM cart_item WHERE product_id='$id' AND cart_item.User_id=$user_id");
  header('location:mycart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
        <title>My Cart</title>  
        <meta name="author" content="Furkan KOÇER">
      <meta name="description" content="Add collectibles to the cart">
      <meta name="keywords" content="cart,collectibles,comic,digital art,comic book,trade,buy">  
      <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/footer.css">
      <link rel="stylesheet" href="css/cart.css">
      
</head>
<body>
<?php include "header.php"?>
<section>
  <div class="showmessage">
    <?php echo $message;?>
  </div>
  <div class="wrapper-tablecart">
    <?php if($_SESSION['logged_in']==false){
      ?>
      <div class='cartbox'>
     <div class='together'>
     <i class='bi bi-cart3'></i>
     <p>You must login to add product!!!</p>
     </div>
     <a href='login.php'>LOG IN</a>
   </div>
   <?php };?>
   <?php if($addshowbox->num_rows ==0 ){
     echo $showcart;}?>
<div class="wrapper-flex">
  <table class="tableforcart">
    <?php if($addshowbox->num_rows !=0 ){?>
      <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Amount</th>
        <th>Price</th>
        <th>Delete</th>
        </tr>
        <thead>
          <?php };?>
        <tbody>   
        <?php
         $total=0;
          if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
            if(mysqli_num_rows($select)>0){
          while ($row = mysqli_fetch_assoc($select)) {
            $getproid=$row['product_id'];
            $quantitycheck="SELECT * FROM product WHERE product_id=$getproid";
            $checkquantity=mysqli_query($conn,$quantitycheck);
            $rowquantity=mysqli_fetch_assoc($checkquantity);  
            $_SESSION['product_name']=$row['product_name'];
            $_SESSION['product_price']=$row['price'];
            $_SESSION['image']=$row['image_url'];
            $_SESSION['product_quantity']=$row['quantity'];
            $total+=$row['price']*$row['quantity'];
            $_SESSION['totalvalue']=$total;
            ?>
          <tr>
            <td><img src="images/product-images/<?php echo  $row['image_url'];?>" class="img-show"></td>
            <td><?php echo $row['product_name'];?></td>
            <td>
              <form method="post" action="mycart.php">
              <button type="submit" name="minus" class="minus">-</button>
              <?php echo $row['quantity'];?>
              <input type="hidden" name="totalquantity" value="<?php echo $rowquantity['quantity'];?>">
              <input type="hidden" name="sendquantity" value="<?php echo $row['quantity'];?>">
              <input type="hidden" value="<?php echo $row['product_id'];?>" name="getidofproduct">    
              <button type="submit" name="plus" class="plus">+</button>
          </form>
          </td>
            <td><?php echo  $row['price']*$row['quantity'];?> TL</td>
            <td><a href="mycart.php?deletecart=<?php echo $row['product_id'];?>" onclick="return confirm('Are you sure you want to delete : <?php echo $row['product_name'];?> ?')" class="button-try change-color-btn"><i class="bi bi-trash"></i></a></td>
        </tr>
<?php }}};?>
</tbody>
    </table>
    <?php if($addshowbox->num_rows !=0 ){?>
    <aside class="showboxofbuy">
      <div class="orderbox">
        <h1>ORDER SUMMARY</h1>
        <p>Total: <span><?php echo  $total?> TL</span></p>
        <form method="post" action="orderbefore.php">
          <input type="hidden" name="total_value" value="<?php echo $total;?>">
          <input type="submit" name="goorder" value="CHECKOUT" class="button-try button-try2 makecenter">
    </form>
      </div>
    </aside>
    <?php };?>
    </div>
  </div>
</section>
<?php include "footer.php" ?>
</body>
</html>