<?php 
  session_start();
require('dbase.php');
if($_SESSION['usertype']=='admin'){
    header("Location:adminpanel.php");
  }
  
 $request="category_id ASC";
  if(isset($_POST['fetchvalue'])){
    $request=$conn->real_escape_string($_POST['fetchvalue']);

    $selected="selected";
  }
  if (isset($_GET['takeproducts'])){
    $pageid=(int)$_GET['takeproducts'];
    $querryselected="SELECT * FROM product_category WHERE category_id=$pageid";
    $takeselected=mysqli_query($conn,$querryselected);
    $rowselectedid1=mysqli_fetch_assoc($takeselected);
    $queryselectproduct="SELECT * FROM product WHERE category_id=$pageid ORDER BY $request";
  $take=mysqli_query($conn,$queryselectproduct);
  $rownum=$take->num_rows;//if you get problem this one added last
  $title="$rowselectedid1[name]";//title name
  $title2=" Products & Prices";
  }

  if (isset($_GET['takeproductssub'])){
    $pagesubid=(int)$_GET['takeproductssub'];
    $querryselected="SELECT * FROM product_sub_category WHERE sub_id=$pagesubid";
    $takeselected=mysqli_query($conn,$querryselected);
    $rowselectedid=mysqli_fetch_assoc($takeselected);
    if(mysqli_num_rows($takeselected)>0){
      $queryselect="SELECT * FROM product_category WHERE category_id= $rowselectedid[category_id]";
      $takeselect=mysqli_query($conn,$queryselect);
      $rowselect=mysqli_fetch_assoc($takeselect);
      $queryselectproduct="SELECT * FROM product WHERE sub_id=$pagesubid ORDER BY $request";
    $take=mysqli_query($conn,$queryselectproduct);
    $rownum=$take->num_rows;//if you get problem this one added last
    }
   
  $title="$rowselectedid[name]";//title name
  $title2=" Products & Prices";
  }
  
?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title,$title2?></title>
        <meta name="author" content="Furkan KOÇER">
        <meta name="description" content="Buy Collectibles,Antiques,Comics,Awarded Film&Books and more.">
      <meta name="keywords" content="collectible,awarded film&books,comic,digital art,comic book,trade,buy,online shopping,2D-3D digital art">  
        <link rel="stylesheet" href="css/categorizedproducts.css">
      <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/footer.css">
    </head>
    <body> 
    <?php include "header.php"?>
        <section>
          <?php 
           if($rownum>0){//if there is an item then?>
                  <div class="linktothesite">
                    <a href="index.php">Main Page</a>
                    <?php if (isset($_GET['takeproductssub'])){ 
                     echo "<span>></span>";}
                    ?>
                    <a href="categorizedproducts.php?takeproducts=<?php echo $rowselect['category_id'];?>"> <?php 
                    if (isset($_GET['takeproductssub'])){
                      echo $rowselect['name'];
                     }
                    ?></a>
                   <?php if (isset($_GET['takeproductssub'])){ 
                     echo "<span>></span>";}
                    ?>
                    <span><?php if (isset($_GET['takeproducts'])){ 
                     echo "<span>></span>";
                      echo  $rowselectedid1['name'];}?></span>
                    <span><?php echo $rowselectedid['name'];?></span>
                  </div>
                  <div class="selectivity">
                    <form method="post" action="" id="selectivity">
                  <select name="fetchvalue" required>
                 <option value="" disabled="" selected="">Select</option>
                  <option value="price DESC" <?php if($request=='price DESC')echo $selected;?> >Price: Descending</option>
                   <option value="price ASC"<?php if($request=='price ASC')echo $selected;?>>Price: Ascending</option>
                   <option value="created_at DESC"<?php if($request=='created_at DESC')echo $selected;?>>New First</option>
                   <option value="created_at ASC"<?php if($request=='created_at ASC')echo $selected;?>>Old First</option>
                  </select>
                  <input type="submit" value="Sort by"/>
                    </form>
                  </div>
                        <div class="wrap">
                      <?php
                      while ($rowtake = mysqli_fetch_assoc($take)) {
                        if($rowtake['quantity']>0){//added at 14.05.2022 if product quantity 0 then dont show related product.
                        ?>
                        <div class="boxes-wrapper">
                        <a href="showproductindetail.php?show=<?php echo $rowtake['product_id'];?>" class="boxes" id="boxes" target="_blank">
                      <img src="images/product-images/<?php echo $rowtake['image_url'];?>" class="img-show">
                      <span class="nameofproduct"><?php echo $rowtake['product_name'];?></span>
                        <span class="priceofpro"><?php echo $rowtake['price'];?> TL</span>
                      </a>
                        </div>
                      <?php }} ?>
                      </div> 
                      <?php }
                      else{
                        echo "<div class='showmessagesite'><span class='noresult'>There is no product in this category</span></div>";
                      }
                      ?>
                                     
        </section> 
        <?php include "footer.php"; ?>
        <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script src="js/selectivity.js"></script>
</body>
</hmtl>
