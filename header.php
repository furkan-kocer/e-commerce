<?php
require('dbase.php');
 $show=" <div  class='icon-1'  id='show1'>
 <i class='fa fa-user-o' aria-hidden='true'></i>
 <p>Sign In</p>
 <div class='sub-icon'> 
   <ul>
         <li class='log-in'><a href='login.php'>Log In</a></li>
         <li  class='Sign-up'><a href='register.php'>Sign Up</a></li>
     </ul>
</div> ";
$show2=" <div  class='icon-1' id='show2'>
<i class='fa fa-user-o' aria-hidden='true'></i>
<p>My account</p>
<div class='sub-icon2'> 
<p class='session_name'>$_SESSION[name]</p>
  <ul>
  <li>
  <i class='fa fa-user'></i>
  <a href='myuserinfo.php'>My user information</a>
  </li>
  <li>
  <i class='fa fa-archive'></i>
  <a href='myorders.php'>My orders</a>
  </li>
  <li>
  <i class='fa fa-sign-out'></i>
  <a href='logout.php'>Log out</a>
  </li>
    </ul>
</div> ";
$count=0;
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true){
$user_id1=$_SESSION['id'];
$sql="SELECT * FROM cart_item WHERE cart_item.User_id=$user_id1";
if($result=mysqli_query($conn,$sql)){
    $count=mysqli_num_rows($result);
}}
$sql2="SELECT * FROM product_category";
$result2=mysqli_query($conn,$sql2);
?>
<div class="header">
               <div class="navbar-1">
                   <label class="logo"><a href="index.php">qualify</a></label>
                 <div class="search-box"> 
                     <form action="searchproduct.php" method="get">
                   <input type="search" name="searchproductget" class="search-bar" placeholder="Search" pattern=".{2,}"  oninvalid="this.setCustomValidity('At least two characters')" oninput="this.setCustomValidity('')" title="At least two characters" required>
                   <button type="submit" name="searchforproduct" class="dontshow"><i class="fa fa-search"></i></button>
                    </form>
                </div>  
                <?php
                if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ==true)
                {
                  echo $show2;
                }
                else{
                    echo $show;
                }
                ?>          
                </div>         
                <div  class="icon-2">
                    <a href="mycart.php" target="_self">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <p>my cart</p>
                        <span class="badge cart-count"><?php echo $count; ?></span>
                    </a>
                    <div class="sub-icon2-menu"></div>
                </div>
                <!-- Languages menu 
                <div class="lang-menu">
                 <label>languages:</label>
                <a href="#"><img class="image" src="images/turkey.png" alt="Turkey logo"></a>
                <a href="#"><img class="image" src="images/united-states-of-america.png" alt=" United States"></a>
                </div>
                -->
                </div>
               <div class="navbar-2">
                        <ul>
                                    <li><a href="index.php">Main Page</a></li>
                                    <?php while($row = mysqli_fetch_assoc($result2)){
                                        $deneme=$row['category_id'];
                                        $sql3="SELECT * FROM product_sub_category WHERE category_id=$deneme";
                                        $result3=mysqli_query($conn,$sql3);
                                        ?>
                                    <li><a href="categorizedproducts.php?takeproducts=<?php echo $row['category_id']?>"><?php echo $row['name'];?></a>
                                    <?php if($result3){ 
                                        ?>
                                        <div class="sub-menu">
                                            <ul>
                                                <?php while($row2 = mysqli_fetch_assoc($result3)){?>
                                                <li><a href="categorizedproducts.php?takeproductssub=<?php echo $row2['sub_id']?>"><?php echo $row2['name'];?></a></li>
                                                <?php }?>
                                            </ul>
                                    </div>  
                                    <?php 
                                }
                                ?>
                                    </li>
                                    <?php 
                                    }
                                    ?>
                          
                                                                
                        </ul>
               </div>
           </div> 
          