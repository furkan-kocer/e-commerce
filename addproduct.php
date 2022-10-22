<?php
session_start();
//For back button if back button clicked return product page.
if(isset($_POST['back'])){
  header('Location:product.php');
}
//Sessions below determine admin or user login status.
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
//If user or admin not login then below code begining to work and it directs one to login page.
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
  header('Location:login.php');
 }
require_once("dbase.php");
$submit="submit";
$title="Add Product";
//
if(isset($_GET['edit'])){
  $id=$_GET['edit'];
  $submit="update";
  $title="Update Product";
}
$query="SELECT * FROM `e-commerce`.product_category";
$result=mysqli_query($conn,$query);
$options0="";
$disoptions="";
$message1="";
$success1="";
$querry="SELECT * FROM `e-commerce`.discount";
$disresult=mysqli_query($conn,$querry);
if($result->num_rows>0){
  while ($row = mysqli_fetch_array($result)){
    $options0=$options0.'<option value="'.$row["category_id"].'">'.$row["name"] .'</option>';
  }
}
if($disresult->num_rows>0){
  while ($rowdis = mysqli_fetch_array($disresult)){
    $disoptions=$disoptions.'<option value="'.$rowdis["discount_id"].'">'.$rowdis["dis_name"] .'</option>';
  }
}


  if(!isset($_GET['edit']) && isset($_POST['submit'])){
    $pname=$conn->real_escape_string($_POST['pname']);
    $pnamevalid=$conn->query("SELECT * FROM product WHERE product_name ='$pname'");
    $price=$conn->real_escape_string($_POST['price']);
    $category=$conn->real_escape_string($_POST['category']);
    $scategory=$conn->real_escape_string($_POST['scategory']);
    $discount=$conn->real_escape_string($_POST['dc']);
    $quantity=$conn->real_escape_string($_POST['qua']);
    $description=$conn->real_escape_string($_POST['desc']);
    $product_image_name=$conn->real_escape_string($_FILES['imgfile']['name']);
    $product_image_size=$conn->real_escape_string($_FILES['imgfile']['size']);
    $error=$_FILES['imgfile']['error'];
    if($pnamevalid->num_rows !=0){  
      $message1="<div class='message-box'>
      <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
      <p>this item name already has taken.</p>
    </div>";}
    if($error===0){
      if($product_image_size >2500000)
      {
        $message1="<div class='message-box'>
      <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
      <p>your file is too large. File size can not be larger than 2.5 MB.</p>
    </div>";
      }
      elseif($product_image_size < 2500000){
       $acceptedext=array("jpg", "png", "jpeg");
       $extension=pathinfo($product_image_name, PATHINFO_EXTENSION);
        if(!in_array($extension, $acceptedext))
        {
          $message1="<div class='message-box'>
          <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
          <p>you cant upload this type of file.</p>
        </div>";
        }
        else{
          $query3="INSERT INTO product (category_id,sub_id,product_name,price,discount_id,description,quantity,image_url,created_at) VALUES ('$category','$scategory','$pname','$price','$discount','$description','$quantity','$product_image_name',NOW())";
          $insert = mysqli_query($conn,$query3);
         if($insert){
            $success1="<div class='succ'>
            <label><i class='fa fa-check' aria-hidden='true'></i></label>
            <p >your product added succesfully.</p>
          </div>";
          mysqli_close($conn);
          //$showimage= "<img src='images/$product_image_name'>";
          header('Location:product.php');
        }
        }
      }
     
    }
    else {
      $message1="<div class='message-box'>
      <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
      <p>No choosen file or there is a mistake on file.Pls try again.</p>
    </div>";
    }
  }
  if(isset($_GET['edit']) && isset($_POST['submit'])){
    $pname=$conn->real_escape_string($_POST['pname']);
    $pnamevalid=$conn->query("SELECT * FROM product WHERE product_name ='$pname'");
    $price=$conn->real_escape_string($_POST['price']);
    $category=$conn->real_escape_string($_POST['category']);
    $scategory=$conn->real_escape_string($_POST['scategory']);
    $discount=$conn->real_escape_string($_POST['dc']);
    $quantity=$conn->real_escape_string($_POST['qua']);
    $description=$conn->real_escape_string($_POST['desc']);
    $product_image_name=$conn->real_escape_string($_FILES['imgfile']['name']);
    $product_image_size=$conn->real_escape_string($_FILES['imgfile']['size']);
    $error=$_FILES['imgfile']['error'];
    if($error===0){
      if($product_image_size >2500000)
      {
        $message1="<div class='message-box'>
      <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
      <p>your file is too large. File size can not be larger than 2.5 MB.</p>
    </div>";
      }
      elseif($product_image_size < 2500000){
       $acceptedext=array("jpg", "png", "jpeg");
       $extension=pathinfo($product_image_name, PATHINFO_EXTENSION);
        if(!in_array($extension, $acceptedext))
        {
          $message1="<div class='message-box'>
          <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
          <p>you cant upload this type of file.</p>
        </div>";
        }
       else{
        $query3="UPDATE product SET category_id='$category', sub_id='$scategory', product_name='$pname', price='$price', discount_id='$discount', description='$description', quantity='$quantity', image_url='$product_image_name'
        WHERE product_id= $id";
        $update = mysqli_query($conn,$query3);
       if($update){
          $success1="<div class='succ'>
          <label><i class='fa fa-check' aria-hidden='true'></i></label>
          <p >your product updated succesfully.</p>
        </div>";
        mysqli_close($conn);
        header('Location:product.php');
       }
       }
    }  
  }
 
  elseif($error===1) {
    $message1="<div class='message-box'>
    <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
    <p>No choosen file or there is a mistake on file.Pls try again.</p>
  </div>";
  }
  else{
    $selective=mysqli_query($conn,"SELECT * FROM product WHERE product_id=$id");
    while($row255 =mysqli_fetch_assoc($selective)){
      $img_url=$row255['image_url'];
    $query4="UPDATE product SET category_id='$category', sub_id='$scategory', product_name='$pname', price='$price', discount_id='$discount', description='$description', quantity='$quantity', image_url='$img_url'
    WHERE product_id= $id";
    $update = mysqli_query($conn,$query4);
   if($update){
    mysqli_close($conn);
    header('Location:product.php');
   }
   }
  } 
}

  if(! empty($_POST["category_id"])){
    $query1="SELECT * FROM  product_sub_category WHERE category_id= '" . $_POST["category_id"] . "'";
    $result1=mysqli_query($conn,$query1);
    ?>
    <?php
   if(!isset($_GET['edit'])){
    ?>
    <option value disabled selected>Select Sub Category</option>
    <?php }?>
    <?php
    foreach($result1 as $sub){
      ?>
      <option value="<?php echo $sub["sub_id"]; ?>"><?php echo $sub["name"]; ?></option>
      <?php
    }
  }
  ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="css/addproduct.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
        <div class="wrapper">
        <div class="product-content">
        <?php
        if(!isset($_GET['edit'])){
          ?>
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="first-img-div">
          <img id="output" class="img-show">
          </div>
          <div class="first-div">
          <input type="file" name="imgfile" accept=".jpg, .jpeg, .png" onchange="loadFile(event)" class="box">
          </div>
          <?php echo $message1;?>
          <?php echo $success1;
          ?>
          <div class="first-container">
          <div class="first-div">
              <span>Product Name:</span>
              <input type="text"  name="pname" placeholder="Product Name"  value="<?php echo $pname;?>" minlength="1" maxlength="50" required>
            </div>
          
            <div class="first-div">
              <span>Price:</span>
              <input type="number"  name="price" placeholder="Price of Product"  min="0" max="1000000" step=".01" required>
            </div>
          </div>
              <div class="first-container">
              <div class="first-div">
              <span>Main Category:</span>
              <select value="category" name="category" onchange="getSub(this.value)" required>
              <option value disabled selected>Select Main Category</option>
              <?php echo $options0;?> 
                </select>
            </div>
            <div class="first-div">
              <span>Sub Category:</span>
              <select value="scategory" name="scategory" id="s-list" required>
                <option value disabled selected>Select Sub Category</option>
                </select>
            </div>
                 </div>
         <div class="first-container">
         <div class="first-div">
              <span>Discount:</span>
              <select value="dc" name="dc"  required>
              <option value disabled selected>Select discount amount</option>
              <?php echo $disoptions;?> 
                </select>
            </div>
            <div class="first-div">
              <span>Quantity:</span>
              <input type="number"  name="qua" min="0" required> 
            </div>
         </div>
            <div class="second-div">
              <span>Description:</span>
              <textarea class="field area" name="desc" placeholder="Description"></textarea>
            </div>
            <div class="second-container">
            <div>
            <a href="product.php" class="button-try">back</a>
              </div>
            <div>
              <input type="submit"  name="submit" value="<?php echo $submit;?>">
            </div>
            </div>
          </form>
          <?php  };?>
          <?php
        if(isset($_GET['edit'])){
          $select=mysqli_query($conn,"SELECT * FROM product WHERE product_id=$id");
          while($row =mysqli_fetch_assoc($select)){
            $cate_id=$row['category_id'];
            $sub_cat_id=$row['sub_id'];
            $discount_change_id=$row['discount_id'];
          $category_result="SELECT * FROM product_category WHERE category_id=$cate_id";
          $sub_category_result="SELECT * FROM product_sub_category WHERE sub_id=$sub_cat_id";
          $dis_result="SELECT * FROM discount WHERE discount_id=$discount_change_id";
           $select2=mysqli_query($conn,$category_result);
           $select3=mysqli_query($conn, $sub_category_result);
           $select4=mysqli_query($conn, $dis_result);
                if($select2->num_rows>0){
                  while ($row14 = mysqli_fetch_array($select2)){
                    $options45=$options45.'<option   selected value="'.$row14["category_id"].'">selected->'.$row14["name"] .'</option>';
                  }
                }
                if($select3->num_rows>0){
                  while ($row15 = mysqli_fetch_array($select3)){
                    $options46=$options46.'<option   selected value="'.$row15["sub_id"].'">selected->'.$row15["name"] .'</option>';
                  }
                }
                if($select4->num_rows>0){
                  while ($row16 = mysqli_fetch_array($select4)){
                    $options47=$options47.'<option  selected value="'.$row16["discount_id"].'">selected->'.$row16["dis_name"] .'</option>';
                  }
                }
          ?>
           <form action="" method="POST" enctype="multipart/form-data">
           <div class="first-img-div">
          <img id="output" src="images/product-images/<?php echo $row['image_url'];?>" class="img-show">
         
          </div>
          <div class="first-div">
          <input type="file" name="imgfile" class="box" onchange="loadFile(event)" accept=".jpg, .jpeg, .png" >
          </div>
          <?php echo $message1;?>
          <?php echo $success1;
          ?>
          <div class="first-container">
          <div class="first-div">
              <span>Product Name:</span>
              <input type="text"  name="pname" placeholder="Product Name"  value="<?php echo $row['product_name'];?>" minlength="1" maxlength="50" required>
            </div>
          
            <div class="first-div">
              <span>Price:</span>
              <input type="number"  name="price" placeholder="Price of Product" value="<?php echo $row['price'];?>"  min="0" max="1000000" step=".01" required>
            </div>
          </div>
              <div class="first-container">
              <div class="first-div">
              <span>Main Category:</span>
              <select value="category" name="category" onchange="getSub(this.value)" required>
              <?php echo  $options45;?>
              <?php echo $options0;?> 
                </select>
            </div>
            <div class="first-div">
              <span>Sub Category:</span>
              <select value="scategory" name="scategory" id="s-list" required>
              <?php echo  $options46;?>
              <?php echo $options;?> 
                </select>
            </div>
                 </div>
         <div class="first-container">
         <div class="first-div">
              <span>Discount:</span>
              <select value="dc" name="dc"  required>
              <?php echo  $options47;?>
              <?php echo $disoptions;?> 
                </select>
            </div>
            <div class="first-div">
              <span>Quantity:</span>
              <input type="number" value="<?php  echo $row['quantity'];?>" name="qua" min="0" required> 
            </div>
         </div>
            <div class="second-div">
              <span>Description:</span>
              <textarea class="field area" name="desc" placeholder="Description"><?php echo $row['description'];?></textarea>
            </div>
            <div class="second-container">
            <div>
            <a href="product.php" class="button-try">back</a>
              </div>
            <div>
              <input type="submit"  name="submit" value="<?php echo $submit;?>">
            </div>
            </div>
          </form>
          <?php  }}; ?>
       

        </div>
</div>
<script src="js/jquery.main.js" type="text/javascript"></script>
<script type="text/javascript">
          function getSub(val){
            $.ajax({
              type: "POST",
              url: "addproduct.php",
              data:'category_id='+val,
              success: function(data){
                $("#s-list").html(data);
              }
            })
          }
          var loadFile = function(event) {
	        var image = document.getElementById('output');
	        image.src = URL.createObjectURL(event.target.files[0]);
};
</script>
</body>
</html>