<?php
session_start();
require_once('dbase.php');
require_once('config.php');
$token=$_POST["token"];
$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("123456789");
$request->setToken($token);
$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());
//print_r($checkoutForm->getStatus());
//print_r($checkoutForm->getPaymentStatus());
//print_r($checkoutForm->getErrorMessage());
if(($checkoutForm->getPaymentStatus()=="SUCCESS")){
    $user_id=$conn->real_escape_string($_GET['user']);
    $total_value=$conn->real_escape_string($_GET['total_value']);
    //-------------------------------------------------------------------//
        // order_details tablosuna toplam ücreti ve kullanıcı idsini gönderen kısım//
        $insertorderque = "INSERT INTO order_details (User_id, total, created_at) VALUES ('$user_id','$total_value',NOW())";
        $insertorder = mysqli_query($conn,$insertorderque);
       //-------------------------------------------------------------------//
      //order_details tablosundan eklenen order_id değerini alıp order_itemsa ekliyor//
      $search="SELECT * FROM order_details WHERE User_id=$user_id";
       $searched=mysqli_query($conn,$search);
      //Eğer order_details tablosunda user_id yoksa, aşağıdaki kod user_id'ye order_id'yi alıp order_items tablosuna ekliyor.//
      if(mysqli_num_rows($searched)==1){
       $rowshow1=mysqli_fetch_assoc($searched);
       $_SESSION['order_id']=$rowshow1['order_id'];
       }
       //-------------------------------------------------------------------//
       //Eğer order_details tablosunda user_id 1 den fazla varsa, aşağıdaki kod user_id'ye göre en son oluşturulan order_id'yi alıp order_items tablosuna ekliyor.//
      if(mysqli_num_rows($searched)>1){
      $search1="SELECT * FROM order_details WHERE User_id=$user_id ORDER BY order_id DESC LIMIT 1";
       $searched1=mysqli_query($conn,$search1);
      $rowshow1=mysqli_fetch_assoc($searched1);
      $_SESSION['order_id']=$rowshow1['order_id'];
      }
      //giriş yapmış olan kullanıcının idsine göre select işlemi//
      $showbox="SELECT * FROM cart_item WHERE cart_item.User_id=$user_id";
      $addshowbox=mysqli_query($conn,$showbox);
      //-------------------------------------------------------------------//
      // Eğer sepette ürün varsa aşağıdaki işlemi yap//
       if(mysqli_num_rows($addshowbox)>0){
      While($rowshow=mysqli_fetch_assoc($addshowbox)){
       $items_array=$rowshow['product_id']; 
       $items_arrayque=$rowshow['quantity'];
      $takerowoforderid=$_SESSION['order_id'];
      $insertorderitemsque = "INSERT INTO order_items (order_id, product_id, quantity)
       VALUES ('$takerowoforderid','$items_array','$items_arrayque')";
       $insertorderitems = mysqli_query($conn,$insertorderitemsque);
       }
    }
          //Eğer ürün siparişi tamamlanmışsa aşağıdaki işlemi yap//
//-------------------------------------------------------------------//
    if($insertorderitems){
        $check="SELECT * FROM order_details WHERE order_id=$takerowoforderid";
        $checkquery=mysqli_query($conn,$check);
        while($rowarray=mysqli_fetch_assoc($checkquery)){
            $getarrayofquantityall=0;
            $getarrayofquantity=0;
          $selectrelatedorderid="SELECT * FROM order_items WHERE order_id=$rowarray[order_id]";
          $relatedorderidque=mysqli_query($conn,$selectrelatedorderid);
         while($rowarray2=mysqli_fetch_assoc($relatedorderidque)){
            $getarrayofquantity=$rowarray2['quantity'];
            //echo "</br>quantityoforderitems: ",$getarrayofquantity;
            $quantitycheck="SELECT * FROM product WHERE product_id = $rowarray2[product_id]";
            $quantitycheckque=mysqli_query($conn,$quantitycheck);
            $rowproductarrayqua=mysqli_fetch_assoc($quantitycheckque);
            //echo "</br> productidproducttable:",$rowproductarrayqua['product_id'];
            //echo "</br> productidproducttablequantiy:",$rowproductarrayqua['quantity'];
            $getarrayofquantityall=$rowproductarrayqua['quantity'];
           $getarrayofquantityall=$getarrayofquantityall-$getarrayofquantity;
          // echo "</br> result:", $getarrayofquantityall;
           $setdatas="UPDATE product SET quantity=$getarrayofquantityall WHERE product_id=$rowarray2[product_id]";
           $setsql=mysqli_query($conn,$setdatas);          
           if($setsql){
            $erase_items_from_cart = "DELETE  FROM cart_item WHERE cart_item.User_id=$user_id";
            $eraseresult=mysqli_query($conn,$erase_items_from_cart);
            $success="Thank you for shopping";
            $searchgetorder="SELECT * FROM order_details WHERE User_id=$user_id ORDER BY order_id DESC LIMIT 1";
            $searchedorder=mysqli_query($conn,$searchgetorder);
            $rowdetails=mysqli_fetch_assoc($searchedorder);
           }
         }
        }
    }
}
else{
    echo $checkoutForm->getErrorMessage();
    echo "you will be redirected to main page after 10 seconds";
    header("refresh:10;index.php");
}
?>
<?php
if(($checkoutForm->getPaymentStatus()=="SUCCESS")){
    $getrelatedidinfos="SELECT * FROM user WHERE User_id=$user_id";
             $getresult=mysqli_query($conn,$getrelatedidinfos);
             $rowgetuserinfo = mysqli_fetch_assoc($getresult);
             $order_id=$rowdetails['order_id'];
             $search2="SELECT * FROM order_items WHERE order_id=$order_id";
             $searched2=mysqli_query($conn,$search2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
        <title>Order Details</title>  
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> 
      <link rel="stylesheet" href="css/phpicin.css"> 
      <link rel="stylesheet" href="css/order.css"> 
</head>
<body>
        <section>
        <div class="orderboxwrapper">
        <div class="orderinformation">
        <p><?php echo $success;?><p>
        <p><?php echo $rowgetuserinfo['First_name']?></p>
        <p><?php echo $rowgetuserinfo['Last_name']?></p>
        <p><?php echo $rowgetuserinfo['city']?></p>
        <p><?php echo $rowgetuserinfo['Country']?></p>
        <p><?php echo $rowgetuserinfo['address']?></p>
         <p><?php echo $rowgetuserinfo['province']?></p>
         <p>Items you bought</p>
            <?php while($rowshow2=mysqli_fetch_assoc($searched2))
            {
                $getproductname="SELECT * FROM product WHERE product_id=$rowshow2[product_id]";
             $getproductresult=mysqli_query($conn,$getproductname);
             $rowgetproductname=mysqli_fetch_assoc($getproductresult);
             ?>
             <div value="<?php echo $rowshow2['product_id'];?>"> <?php echo $rowgetproductname['product_name'];?> , <?php echo $rowshow2['quantity'];?> pieces</div>
           <?php };?> 
        <p>Order date: <?php echo $rowdetails['created_at'];?></p>
        <p>total: <?php echo $rowdetails['total'];?>TL</p>
        <a href="index.php" class="button-try">OK</a>
        </div>
        </div>
        </section>
</body>
</html>
<?php };?>