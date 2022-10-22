<?php 
session_start();
require('dbase.php');
if(isset($_POST['urunid']) && $_SESSION['logged_in'] ==false){
    $message="You need to logged in to shopping";
    $_SESSION['messagetoshow']=$message;
    echo $message;
}
if(isset($_POST['urunid']) && $_SESSION['logged_in'] ==true){
    $user_id=$_SESSION['id']; // halihazırda giriş yapan kullanıcının idsi alınır.
    $urunId=$conn->real_escape_string($_POST['urunid']);
    $quantityofptable=$conn->real_escape_string($_POST['quantityofptable']);//new added
  $quantity=$conn->real_escape_string($_POST['quantity']);
  $checkquery="SELECT * FROM cart_item WHERE product_id ='$urunId' AND  cart_item.User_id=$user_id";
  $check=mysqli_query($conn,$checkquery);
  if($check->num_rows !=0){  // eğer sepette üründen varsa alttaki kod çalışır.
    $row2 = mysqli_fetch_assoc($check);
    $quantity1= $row2['quantity'];
    if($row2['quantity']<$quantityofptable){
      $quantity1=$quantity1+1;
      $data="UPDATE cart_item SET quantity='$quantity1' WHERE product_id ='$urunId' AND  cart_item.User_id=$user_id";
      $dataset=mysqli_query($conn,$data);
      if($dataset){
       $message="Your product has already been on the cart so it quantity increased one";
       echo $message;
      /* $quantityofptable=$quantityofptable-1;//new added
       $data2="UPDATE product SET quantity='$quantityofptable' WHERE product_id ='$urunId'";//new added
      $dataset=mysqli_query($conn,$data2);//new added*/
      }
    }
    else{
      $message="
        <p>You reached the limit of product quantity</p>
               ";
               echo $message;
    }
   
  }
  else{
    $query="INSERT INTO cart_item (product_id,User_id) VALUES ('$urunId','$user_id')";
    $result=mysqli_query($conn,$query);
    if($result){
      $message= "Your product succesfully added to cart";
      echo $message;
      /*$quantityofptable=$quantityofptable-1;//new added
     $data2="UPDATE product SET quantity='$quantityofptable' WHERE product_id ='$urunId'";//new added
    $dataset=mysqli_query($conn,$data2);//new added*/
    }
  }
  
}