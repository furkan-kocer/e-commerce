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

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Order details</title>
    <link rel="stylesheet" href="css/adminpanel.css">
  <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="css/user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<?php include "sidebar.php"; ?>
<!--//////////////////////////////////////////////////////-->
<div class="wrapper">
    <span class="header-text">Order table</span>
    <table class="table1 table1change">
        <thead>
            <tr>
              <th>Order_id</th>
              <th>User_id</th>
              <th>total</th>
              <th>Order date</th>
        </thead>
        <tbody>
        <?php
        $get="SELECT * FROM order_details";
        $getresults=mysqli_query($conn,$get);
          while ($row = mysqli_fetch_assoc($getresults)) {  
            ?>
          <tr>
          <td>#<?php echo $row['order_id'];?></td>
          <td><?php echo $row['User_id'];?></td>
          <td><?php echo $row['total'];?> TL</td>
          <td><?php echo $row['created_at'];?></td>
</tr>
<?php };?>
</tbody>
    </table>
    <span class="header-text">Order Items</span>
    <table class="table1 table1change">
        <thead>
            <tr>
              <th>id</th>
              <th>Order_id</th>
              <th>product_id</th>
              <th>quantity</th>
        </thead>
        <tbody>
        <?php
        $getorderitems="SELECT * FROM order_items";
        $getresultsorder=mysqli_query($conn,$getorderitems);
          while ($row2 = mysqli_fetch_assoc($getresultsorder)) {
            $checkfrompro="SELECT * FROM product WHERE product_id= $row2[product_id]";
            $queryfrompro=mysqli_query($conn,$checkfrompro);
            while($output=mysqli_fetch_assoc($queryfrompro))
             {
             $show=$output['product_name'];
             }
            ?>
          <tr>
          <td>#<?php echo $row2['id'];?></td>
          <td><?php echo $row2['order_id'];?></td>
          <td><?php echo $show;?></td>
          <td><?php echo $row2['quantity'];?></td>
</tr>
<?php };?>
  </div>
</body>

</html>