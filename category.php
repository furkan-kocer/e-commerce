
<?php 
require('dbase.php');
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
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
  include "afterlogin.php";
}
elseif(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !=true){
   header('Location:login.php');
  }




 // define how many results you want per page
 $results_per_page = 5;

 // find out the number of results stored in database
 $select="SELECT * FROM product_category";
 $que=mysqli_query($conn,$select);
 $number_of_results = mysqli_num_rows($que);
 
 $selectsub="SELECT * FROM product_sub_category";
 $subque=mysqli_query($conn,$selectsub);
 $number_of_results1 = mysqli_num_rows($subque);
 
 // determine number of total pages available
 $number_of_pages = ceil($number_of_results/$results_per_page);
 $number_of_pages1 = ceil($number_of_results1/$results_per_page);
 // determine which page number visitor is currently on
 
 if (!isset($_GET['page'])) {
   $page = 1;
 } 
 if(isset($_GET['page'])){
   $page = $_GET['page'];
   if($page > $number_of_pages){
     $page=1;
   }
 }
 
 if (!isset($_GET['page1'])) {
   $page1 = 1;
 } 
 if(isset($_GET['page1'])){
   $page1 = $_GET['page1'];
   if($page1 > $number_of_pages1){
     $page1=1;
   }
 }
 
 // determine the sql LIMIT starting number for the results on the displaying page
 $this_page_first_result = ($page-1)*$results_per_page;
 $this_page_first_result1 = ($page1-1)*$results_per_page;
 
 // retrieve selected results from database and display them on page
 $sql='SELECT * FROM product_category LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
 $resultselect = mysqli_query($conn, $sql);
 $sql2='SELECT * FROM product_sub_category LIMIT ' . $this_page_first_result1 . ',' .  $results_per_page;
 $resultselect2 = mysqli_query($conn, $sql2);
 
   if(isset($_GET['deletemain'])){
     $idmain=$_GET['deletemain'];
     mysqli_query($conn,"DELETE FROM product_category WHERE category_id=$idmain");
     header('location:category.php');
   }
   if(isset($_GET['deletesub'])){
     $idsub=$_GET['deletesub'];
     mysqli_query($conn,"DELETE FROM product_sub_category WHERE sub_id=$idsub");
     header('location:category.php');
   }
  
 
   if(isset($_POST['save'])){
     $cat_id=$conn->real_escape_string($_POST['cat_id']);
     $nofcategory=$conn->real_escape_string($_POST['name_cat']);
     $control=$conn->query("SELECT * FROM product_category WHERE name ='$nofcategory' OR category_id='$cat_id'");
     if($control->num_rows !=0){  
       $message="<div class='message-box'>
       <label><i class='fa fa-exclamation' aria-hidden='true'></i></label>
       <p>Already category name or id</p>
     </div>";
     header("refresh:1;category.php");
     }
     else{
       $query="INSERT INTO product_category (category_id,name) VALUES ('$cat_id','$nofcategory') ";
       $update = mysqli_query($conn,$query);
       if($update)
       {
         header("location:category.php");
       }
     }
   }
   $querysel="SELECT * FROM `e-commerce`.product_category";
 $resultsel=mysqli_query($conn,$querysel);
   $options0="";
   if($resultsel->num_rows>0){
     while ($resultrow = mysqli_fetch_array($resultsel)){
       $options0=$options0.'<option value="'.$resultrow["category_id"].'">'.$resultrow["name"] .'</option>';
     }
   }
   if(isset($_POST['save1'])){
     $cat1_id=$conn->real_escape_string($_POST['category_id']);
     $subofcategory=$conn->real_escape_string($_POST['name_sub']);
       $queryget="INSERT INTO product_sub_category (name,category_id) VALUES ('$subofcategory','$cat1_id') ";
       $updateget = mysqli_query($conn,$queryget);
       if($updateget)
       {
         header("location:category.php");
       }
     }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Category</title>
    <link rel="stylesheet" href="css/adminpanel.css">
  <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="css/user.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<?php include "sidebar.php"; ?>
  <div class="pop-up" id="user-pop-up">
  <div class="pop-up-content">
    <form action="" method="POST">
    <div id="field">
      <label>Category_id:</label>
      <input type="text" placeholder="Main Category_id" name="cat_id" pattern="\d*" required>
      </div>
      <div id="field">
      <label>Name:</label>
      <input type="text" placeholder="Name_Of_Category" name="name_cat" required>
      </div>
      <div id="field">
      <a class="buton-try2" id="backto">back</a>
          <input type="submit" name="save" value="add" />
    </div>
</form>
  </div>
</div>

<div class="pop-up" id="user-pop-up1">
  <div class="pop-up-content">
    <form action="" method="POST">
      <div id="field">
      <label>Name:</label>
      <input type="text" placeholder="Name_Of_Sub_Category" name="name_sub" pattern="[a-zA-Z'-'\s]*"   required>
      </div>
      <div id="field">
      <label>Select Main Category</label>
      <select value="category" name="category_id" required>
              <option value disabled selected>Select Main Category</option>
              <?php echo $options0;?> 
        </select>
      </div>
      <div id="field">
      <a class="buton-try2" id="backto1">back</a>
          <input type="submit" name="save1" value="add" />
    </div>
</form>
  </div>
</div>
      <!-- right side of site -->
      <div class="wrapper">
        <div class="wrapper-tables">
        <div class="first-table-div">
        <span class="header-text">Main Category</span>
        <?php echo  $message;?>
        <a class="button-try1 button-fontplus" id="goto"><i class="bi bi-plus"></i></a>
    <table class="table1">
        <thead>
            <tr>
              <th>Main_id</th>
              <th>Name</th>
              <th>Update&Delete</th>
          </tr>
        </thead>
        <tbody>
        <?php  while ($row = mysqli_fetch_assoc($resultselect)) {
            ?>
            <tr>
              <td><?php echo $row['category_id'];?></td>
              <td><?php echo $row['name'];?></td>
              <td class="button-row2">
              <a href="editcategory.php?editmain=<?php echo $row['category_id'];?>" class="button-try1 button-try3 button-font" id="click-btn-product"><i class="bi bi-arrow-repeat"></i></a>
             <a href="category.php?deletemain=<?php echo $row['category_id'];?>" onclick="return confirm('Are you sure you want to delete <?php echo $row['name'];?> ?') " class="button-try1 change-color-btn-red button-font1"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
          <?php }?>
          </tbody>
          <tfoot>
            <td colspan="3">
                <div class="pagination"><?php for ($page=1;$page<=$number_of_pages;$page++) {
                echo '<a href="category.php?page=' . $page . '">' . $page . '</a> ';
                  }?></div>
            </td>
            </tfoot>
    </table>
        </div>
   
    <div class="second-table-div">
    <span class="header-text">Sub Category</span>
    <a class="button-try1 button-fontplus" id="goto2"><i class="bi bi-plus"></i></a>
    <table class="table1">
        <thead>
            <tr>
              <th>Sub_id</th>
              <th>Name</th>
              <th>Category_id</th>
              <th>Update&Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php  while ($row1 = mysqli_fetch_assoc($resultselect2)) {
            ?>
            <tr>
              <td><?php echo $row1['sub_id'];?></td>
              <td><?php echo $row1['name'];?></td>
              <td><?php echo $row1['category_id'];?></td>
              <td class="button-row2">
             <a href="editcategory.php?editsub=<?php echo $row1['sub_id'];?>" class="button-try1 button-try3 button-font" id="click-btn-product"><i class="bi bi-arrow-repeat"></i></a>
             <a href="category.php?deletesub=<?php echo $row1['sub_id'];?>" onclick="return confirm('Are you sure you want to delete <?php echo $row1['name'];?> ?') " class="button-try1 change-color-btn-red button-font1"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
          <?php }?>
          </tbody>
          <tfoot>
            <td colspan="4">
           
                <div class="pagination">
                <div class="pagination">
              <?php   echo '<a href="category.php?page1=' . 1 . '">' . "|<" . '</a> ';?>
              </div>
                  <?php
                if($page1>2){
                  echo '<a href="category.php?page1=' . $page1-2 . '">' . $page1-2 . '</a> ';
                }
                if($page1>1){
                  echo '<a href="category.php?page1=' . $page1-1 . '">' . $page1-1 . '</a> ';
                }
                
                echo '<a href="category.php?page1=' . $page1. '">' . $page1 . '</a> ';
                if($page1<=$number_of_pages1-1){
                  echo '<a href="category.php?page1=' . $page1+1 . '">' . $page1+1 . '</a> ';
                }
                if($page1<=$number_of_pages1-2){
                 
                  echo '<a href="category.php?page1=' . $page1+2 . '">' . $page1+2 . '</a> ';
                }
              ?>
                   <div class="pagination">
                   <?php   echo '<a href="category.php?page1=' . $number_of_pages1 . '">' . ">|". '</a> ';?>
                   </div>
                  </div>
                 
            </td>
            </tfoot>
    </table>
    </div>
        </div>
       
    
  </div>
      
    <!--  </div>-->
    <script src="js/user.js"></script>
    <script src="js/subcategory.js"></script>
      
</body>

</html>
