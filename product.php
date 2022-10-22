<?php 
require('dbase.php');
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }
  $queryselect="SELECT * FROM product";
  $resultselect=mysqli_query($conn,$queryselect);
  if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM product WHERE product_id=$id");
    header('location:product.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Product</title>
    <link rel="stylesheet" href="css/adminpanel.css">
    <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
<?php include "sidebar.php"; ?>
  <div class="wrapper">
    <span class="header-text">Product table</span>
  <a href="addproduct.php" class="button-try">Add Product</a>
    <table class="table1">
        <thead>
            <tr>
              <th>P_id</th>
              <th>Main Category</th>
              <th>Sub Category</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Discount</th>
              <th>Quantity</th>
              <th>Image</th>
              <th>Description</th>
              <th>Created at</th>
              <th>Update&Delete</th>
        </thead>
        <tbody>
          <?php
          while ($row = mysqli_fetch_assoc($resultselect)) {
             // Aşağıdaki kod bölümü ürünlerin sayfada id değeri yerine o id'ye karşılık gelen adlarını gösterilmesini sağlıyor.
             $checkfrommain="SELECT * FROM product_category WHERE product_category.category_id= $row[category_id]";
             $queryfrommain=mysqli_query($conn,$checkfrommain);
             while($output=mysqli_fetch_assoc($queryfrommain))
              {
              $show=$output['name'];
              }
             $checkfromsub="SELECT * FROM product_sub_category WHERE product_sub_category.sub_id= $row[sub_id]";
             $querryfromsub=mysqli_query($conn, $checkfromsub);
             while($output2=mysqli_fetch_assoc($querryfromsub))
             {
              $show2=$output2['name'];
             }
            $checkfromdiscount="SELECT * FROM discount WHERE discount.discount_id= $row[discount_id]";
            $querryfromdiscount=mysqli_query($conn, $checkfromdiscount);
            while($output3=mysqli_fetch_assoc($querryfromdiscount))
             {
              $show3=$output3['dis_name'];
             }
            ?>
          <tr>
          <td>#<?php echo $row['product_id'];?></td>
            <td><?php echo $show;?></td>
            <td><?php echo $show2;?></td>
            <td><?php echo $row['product_name'];?></td>
            <td><?php echo $row['price'];?> TL</td>
            <td><?php echo $show3;?></td>
            <td><?php echo $row['quantity'];?></td>
            <td><img src="images/product-images/<?php echo $row['image_url'];?>" class="img-show"></td>
            <td><?php echo $row['description'];?></td>
            <td><?php echo $row['created_at'];?></td>
            <td class="button-row">
             <a href="addproduct.php?edit=<?php echo $row['product_id'];?>" class="button-try button-try2" id="click-btn-product">update</a>
             <a href="product.php?delete=<?php echo $row['product_id'];?>" onclick="return confirm('Are you sure you want to delete <?php echo $row['product_name'];?> ?')" class="button-try button-try2 change-color-btn">delete</a>
            </td>
</tr>
<?php } ?>
</tbody>
    </table>
  </div>
</body>

</html>