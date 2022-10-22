<?php
require('dbase.php');
  session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }

  // Main Category Edit///
  if(isset($_GET['editmain'])){
      //show category name and id if edit button is enabled
    $idmainedit=$_GET['editmain'];
    $title="Edit Main Category";
    $query="SELECT * FROM product_category WHERE category_id=$idmainedit";
    $queryselector=mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($queryselector);
    ///////////////////////////////////////////////////////
  }
  if(isset($_GET['editmain']) && isset($_POST['updatecategory'])){
    $cat_id=$conn->real_escape_string($_POST['category_id']);
    $nofcategory=$conn->real_escape_string($_POST['name_category']);
      $query="UPDATE product_category SET category_id='$cat_id', name='$nofcategory' WHERE category_id=$idmainedit";
      $update = mysqli_query($conn,$query);
      if($update){
        $success="<div class='succ'>
        <label><i class='fa fa-check' aria-hidden='true'></i></label>
        <p >category successfully updated</p>
      </div>";
      header("location:category.php");
      }
  }



   // Sub Category Edit///
   if(isset($_GET['editsub'])){
    //show category name and id if edit button is enabled
  $idsubedit=$_GET['editsub'];
  $title="Edit Main Category";
  $query="SELECT * FROM product_sub_category WHERE sub_id=$idsubedit";
  $queryselector=mysqli_query($conn,$query);
  $row=mysqli_fetch_assoc($queryselector);
  ///////////////////////////////////////////////////////
  $checkfromsub="SELECT * FROM product_category WHERE product_category.category_id= $row[category_id]";
            $queryfromsub=mysqli_query($conn,$checkfromsub);
          $output=mysqli_fetch_assoc($queryfromsub);
             $show=$output['name'];       
}
$querysel="SELECT * FROM `e-commerce`.product_category";
$resultsel=mysqli_query($conn,$querysel);
  $options0="";
  if($resultsel->num_rows>0){
    while ($resultrow = mysqli_fetch_array($resultsel)){
      $options0=$options0.'<option value="'.$resultrow["category_id"].'">'.$resultrow["name"] .'</option>';
    }
  }

if(isset($_GET['editsub']) && isset($_POST['updatesubcategory'])){
    $cat1_id=$conn->real_escape_string($_POST['category_id']);
  $nofcategorysub=$conn->real_escape_string($_POST['name_sub']);
    $query="UPDATE product_sub_category SET category_id='$cat1_id', name='$nofcategorysub' WHERE sub_id=$idsubedit";
    $update = mysqli_query($conn,$query);
    if($update){
    header("location:category.php");
    }
}


  ?>
  <!DOCTYPE html>
<html lang="en">
  <head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="css/addproduct.css">
    <link rel="stylesheet" href="css/editcategory.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<?php if(isset($_GET['editmain'])){ ?>
    <div class="wrapper">
        <div class="product-content">
        <form action="" method="POST">
    <div class="first-div">
      <span>Category_id:</span>
      <input type="text" placeholder="Main Category_id" value="<?php echo $row['category_id'];?>" name="category_id" pattern="\d*" required>
      </div>
      <div class="first-div">
      <span>Name:</span>
      <input type="text" placeholder="Name_Of_Category" value="<?php echo $row['name'];?>" name="name_category" required>
      </div>
      <div class="first-container change">
      <a class="button-try" href="category.php">back</a>
          <input type="submit" name="updatecategory" value="Update" class="button-try" />
    </div>
</form>
        </div>
    </div>
<?php };?>
   <?php if(isset($_GET['editsub'])){ ?>
    <div class="wrapper">
        <div class="product-content">
        <form action="" method="POST">
    <div class="first-div">
      <span>Name:</span>
      <input type="text" placeholder="Name_Of_Sub_Category" name="name_sub" value="<?php echo $row['name'];?>"pattern="[a-zA-Z'-'\s]*"   required>
      </div>
      <div class="first-div">
          <span>Select Main Category:</span>
      <select value="category" name="category_id" required>
              <option value="<?php echo $row['category_id'];?>" selected>Selected-><?php echo $show;?></option>
              <?php echo $options0;?> 
        </select>
      </div>
      <div class="first-container change">
      <a class="button-try" href="category.php">back</a>
          <input type="submit" name="updatesubcategory" value="update" class="button-try" />
    </div>
</form>
        </div>
    </div>
   <?php };?> 
</body>
</html>