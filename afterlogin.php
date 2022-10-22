<?php

/*if(isset($_SESSION['logged_in'])){
    header("location:index.php");
}*/

if($_SESSION['usertype']=='user'){
    header("Location:index.php");
  }

?>