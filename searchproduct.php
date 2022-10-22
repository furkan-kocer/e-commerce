<?php 
  session_start();
require('dbase.php');
if($_SESSION['usertype']=='admin'){
    header("Location:adminpanel.php");
  }
  $title="Search";
  if(isset($_GET['searchforproduct'])){
    $searchpro=$conn->real_escape_string(trim($_GET['searchproductget']));//dont allow empty string
    $searchpro=htmlspecialchars($searchpro);//XSS
    if(empty($searchpro)){
      $sendmessage="you need to type something to search product";
      $title="Search";
    }
    else{
      $request2="category_id ASC";
      if(isset($_POST['fetchvalue2'])){
        $request2=$conn->real_escape_string($_POST['fetchvalue2']);
        $selected="selected";
      }
      $searchsql="SELECT * FROM product WHERE product_name LIKE '%$searchpro%' OR description LIKE '%$searchpro%' ORDER BY $request2";
      $resultsql=$conn->query($searchsql);
      $queryresult=mysqli_num_rows($resultsql); 
      $queryresult=htmlspecialchars($queryresult);//XSS
      $title=$searchpro;
      if($queryresult>0)
       $showresults=" <div class='listedproduct'><span class='color'>'$searchpro'</span><span>$queryresult products are listed</span></div>";
    }
    
}
?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title;?> | Qualify</title>  
        <meta name="author" content="Furkan KOÇER">
        <meta name="description" content="Search Products to Buy Collectibles,Antiques,Comics,Awarded Film&Books and more.">
      <meta name="keywords" content="search,collectible,awarded film&books,comic,digital art,comic book,trade,buy,online shopping,2D-3D digital art"> 
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
                         if($queryresult>0){?>
        <div class="selectivity">
                    <form method="post" action="" id="selectivity2">
                  <select name="fetchvalue2" required>
                 <option value="" disabled="" selected="">Select</option>
                  <option value="price DESC" <?php if($request2=='price DESC')echo $selected;?> >Price: Descending</option>
                   <option value="price ASC"<?php if($request2=='price ASC')echo $selected;?>>Price: Ascending</option>
                   <option value="created_at DESC"<?php if($request2=='created_at DESC')echo $selected;?>>New First</option>
                   <option value="created_at ASC"<?php if($request2=='created_at ASC')echo $selected;?>>Old First</option>
                  </select>
                  <input type="submit" value="Sort by"/>
                    </form>
                  </div>
<?php }?>
                  <?php echo $showresults;?>
                        <div class="wrap">
                      <?php
                         if($queryresult>0){
                            while ( $rowtake1 = mysqli_fetch_assoc($resultsql)) {
                                if($rowtake1['quantity']>0){//added at 14.05.2022 if product quantity 0 then dont show related product.
                                   
                        ?>
                        <div class="boxes-wrapper">
                        <a href="showproductindetail.php?show=<?php echo $rowtake1['product_id'];?>" class="boxes" id="boxes" target="_blank">
                      <img src="images/product-images/<?php echo $rowtake1['image_url'];?>" class="img-show">
                      <span class="nameofproduct"><?php echo $rowtake1['product_name'];?></span>
                        <span class="priceofpro"><?php echo $rowtake1['price'];?> TL</span>
                      </a>
                        </div>
                      <?php }}}
                       else{
                        if(!empty($searchpro)){
                                echo "<div class='flexiv'><span class='noresult'>There are no results matching your search !</span></div>";
                            }
                            else{
                              echo "<div class='flexiv'><span class='noresult'> $sendmessage !</span></div>";
                            }}
                             ?>
                      </div>                
        </section> 
        <?php include "footer.php"; ?>
        <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script src="js/selectivity2.js"></script>
</body>
</hmtl>
