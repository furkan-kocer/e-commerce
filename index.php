<?php 
session_start();
require('dbase.php');
if($_SESSION['usertype']=='admin'){
    header("Location:adminpanel.php");
  }
  $queryselectproduct="SELECT * FROM product WHERE quantity<10 ORDER BY created_at DESC";// Update 14.05.2022 quantity'e göre 10 quantityden düşük olan ürünleri listeledim.
  $take=mysqli_query($conn,$queryselectproduct);
?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Buy Collectibles,Antiques,Comics,Awarded Film&Books | Qualify</title>  
        <meta name="author" content="Furkan KOÇER">
      <meta name="description" content="Best Shopping Site Buy Collectibles,Antiques,Comics,Awarded Film&Books and more.">
      <meta name="keywords" content="collectible,comic,digital art,comic book,trade,buy,online shopping,awarded film&books,2D-3D digital art">  
        <link rel="stylesheet" href="css/phpicin.css">
      <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/footer.css">
    </head>
    <body> 
    <?php include "header.php"?>
        <section>
            <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true)
              {
                echo "<h1 class='welcome-user' >WELCOME -  $_SESSION[name]</h1>";
              }
              ?>
              <div class="hero-image">

              </div>
                    <div class="body-class">
                        <div id="leftArr" class="left-arrow">
                                    <img src="images/left.png">
                        </div>

                        <div class="images-div" >
                              <div class="images" id="image">
                                <img class="slide blur zoom" src="images/first.jpg">
                                <div class="content fade">Comics  New Products Buy It Now</div>
                               </div>
                                <div class="images" id="image">
                                <img class="slide blur zoom" src="images/second.jpg">
                                <div class="content fade">LOTR  New Products Buy It Now</div>
                               </div>
                                <div  class="images" id="image">
                                <img class="slide blur zoom" src="images/third.jpg">
                                <div class="content fade">Game Of Thrones New Products Buy It Now</div>   
                                </div>                  
                            </div>

                        <div id="rightArrow" class="right-arrow">
                        <img src="images/next.png">
                        </div>
                    </div>
                    <span id="success_message"></span> 
                    <div class="courasel-product-wrapper">
                    <span class="productlistspan">About The End:</span>
                    <span id="leftArr2" class="left-arrow2">
                    <i class="bi bi-chevron-left"></i> 
                     </span>
                      <div class="courasel-product"> 
                      <?php
                      while ($rowtake = mysqli_fetch_assoc($take)) {
                        if($rowtake['quantity']>0){//added at 14.05.2022 if product quantity 0 then dont show related product.
                        ?>
                        <div class="boxes-wrapper">
                        <a href="showproductindetail.php?show=<?php echo $rowtake['product_id'];?>" class="boxes" id="boxes">
                      <img src="images/product-images/<?php echo $rowtake['image_url'];?>" class="img-show">
                      <span class="nameofproduct"><?php echo $rowtake['product_name'];?></span>
                        <span class="priceofpro"><?php echo $rowtake['price'];?> TL</span>
                       <div class="sub">
                          <form method="post" action="cartoperations.php" class="ajax">
                          <input type="hidden" value="<?php echo $rowtake['product_id'];?>" name="urunid">    
                          <input type="hidden" value="<?php echo $rowtake['quantity'];?>" name="quantityofptable">  
                          <input type="submit" value="Add To Cart" class="button-try">
                      </form>
                        </div> 
                      </a>
                        </div>
                      <?php }} ?>
                      </div>
                      <div id="rightArrow2" class="right-arrow2">
                      <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>      
                   
        </section> 
        <?php include "footer.php"; ?>
        <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/cartoperations.js"></script>
</body>
</hmtl>
