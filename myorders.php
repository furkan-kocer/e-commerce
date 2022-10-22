<?php
if (!isset($_SESSION)) {
    session_start();
    }
    if($_SESSION['logged_in']==false) {
        echo "You need to login to reach this page you will redirect to the login page in 5 seconds";
        header("refresh:5;login.php");
    }
    
?>
<?php
require_once ('dbase.php');
 if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
    if($_SESSION['usertype']=='admin'){
      header("Location:adminpanel.php");
    }
    $userid=$_SESSION['id'];
    $getuser="SELECT * FROM user WHERE User_id=$userid";
    $getresultuser=mysqli_query($conn,$getuser);
    $getorder="SELECT * FROM order_details WHERE User_id=$userid";
    $getresult=mysqli_query($conn,$getorder);
    $rownum=mysqli_num_rows($getresult);

?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>MyOrders</title>
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
    <div class="margin-top">  
      <span class="headerprofile margintop">My Orders</span>
        <?php
        if($rownum>0){//if there is an order then 
          while ($row = mysqli_fetch_assoc($getresult)){  
            $row_qua_cnt=0;
            $order_id=$row['order_id'];
            $search2="SELECT * FROM order_items WHERE order_id=$order_id";
            $searched2=mysqli_query($conn,$search2);
              while($row3=mysqli_fetch_assoc($searched2)){
                $row_qua_cnt+=$row3['quantity'];
                $product_id=$row3['product_id'];
              }
              $row_cnt = $searched2->num_rows;
              while($row2= mysqli_fetch_assoc($getresultuser)){
                $fname=$row2['First_name'];
                $lname=$row2['Last_name'];
              }
            
            
          ?>
          <div class="contentbox">
     <div class="order-box">
       <div class="order-header">
         <div class="order-info">
           <p>Order Date:</p>
           <b><?php echo $row['created_at'];?></b>
         </div>
         <div class="order-info">
         <p>Order Details:</p>
         <b><?php echo "total product:",$row_cnt,",piece:",$row_qua_cnt;?></b>
         </div>
         <div class="order-info">
         <p>Customer:</p>
         <b><?php echo $fname," ",$lname;?></b>
         </div>
         <div class="order-info">
         <p>Total:</p>
           <b><?php echo $row['total'];?> TL</b>
         </div>
       </div>
     </div>
     <div class="order-details-wrapper">
  <?php 
   $order2_id=$row['order_id'];
    $search3="SELECT * FROM order_items WHERE order_id=$order2_id";
    $searched3=mysqli_query($conn,$search3);
      while($row4=mysqli_fetch_array($searched3)){
        $product_id=$row4['product_id'];
        $getproductname="SELECT * FROM product WHERE product_id=$product_id";
             $getproductresult=mysqli_query($conn,$getproductname);
             $rowgetproduct=mysqli_fetch_assoc($getproductresult);
             if($row4['quantity']>1){
               $p="pieces";
             }
             else{
               $p="piece";
             }
       ?>
       <div class="order-details">
      <div class="centerall"><a href="showproductindetail.php?show=<?php echo $rowgetproduct['product_id'];?>"><img src="images/product-images/<?php echo $rowgetproduct['image_url'];?>" class="showimgsmall"></a></div>
        <div class="flex-content order-item centerall"><b>product name:</b><p><?php echo $rowgetproduct['product_name'];?></p></div>
        <div class="flex-content order-item centerall"><b>bought:</b><p><?php echo $row4['quantity']," ",$p;?></p></div>
        <div class="flex-content order-item centerall"><b>total price:</b><p><?php echo $row4['quantity']*$rowgetproduct['price'];?> TL</p></div>
      </div> 
      <?php };?>  
      </div>
     </div>
     <?php }//while loop finishes here
     }//rownum finishes here
     else{
      echo "<div class='flexiv'><span class='noresult'>You dont have order history</span></div>";
     }
     ;?>
 
  </div>
  </div>
    </section> 
    <?php include "footer.php"; ?>
  
    </body>
</hmtl>
<?php } ?>