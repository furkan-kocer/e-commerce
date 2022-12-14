<?php
 session_start();
 require_once ('dbase.php');
require_once('config.php');
$value= $_SESSION['sendvalue'];
$id= $_SESSION['id'];

$checkuserinfo="SELECT * FROM user WHERE User_id=$id";
$checkresult=mysqli_query($conn,$checkuserinfo);
$rowget=mysqli_fetch_assoc($checkresult);

$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("123456789");
$request->setPrice("1");
$request->setPaidPrice($value);
$request->setCurrency(\Iyzipay\Model\Currency::TL);
$request->setBasketId("B67832");
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("http://localhost:4000/OneDrive/Masaüstü/ecommercegraduationproject/afterusage.php?user=$id&total_value=$value");
$request->setEnabledInstallments(array(2, 3, 6, 9));

$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId($id);
$buyer->setName($rowget['First_name']);
$buyer->setSurname($rowget['Last_name']);
$buyer->setGsmNumber($rowget['phone']);
$buyer->setEmail($rowget['email']);
$buyer->setIdentityNumber("74300864791");
$buyer->setLastLoginDate("2015-10-05 12:43:35");
$buyer->setRegistrationDate("2013-04-21 15:12:09");
$buyer->setRegistrationAddress($rowget['address']);
$buyer->setIp("85.34.78.112");
$buyer->setCity($rowget['city']);
$buyer->setCountry($rowget['country']);
$buyer->setZipCode("34732");

$request->setBuyer($buyer);
$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName($rowget['First_name']);
$shippingAddress->setCity($rowget['city']);
$shippingAddress->setCountry($rowget['country']);
$shippingAddress->setAddress($rowget['address']);
$shippingAddress->setZipCode("34742");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName($rowget['First_name']);
$billingAddress->setCity($rowget['city']);
$billingAddress->setCountry($rowget['country']);
$billingAddress->setAddress($rowget['address']);
$billingAddress->setZipCode("34742");
$request->setBillingAddress($billingAddress);

$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("BI101");
$firstBasketItem->setName("Binocular");
$firstBasketItem->setCategory1("Collectibles");
$firstBasketItem->setCategory2("Accessories");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice("0.3");
$basketItems[0] = $firstBasketItem;

$secondBasketItem = new \Iyzipay\Model\BasketItem();
$secondBasketItem->setId("BI102");
$secondBasketItem->setName("Game code");
$secondBasketItem->setCategory1("Game");
$secondBasketItem->setCategory2("Online Game Items");
$secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
$secondBasketItem->setPrice("0.5");

$basketItems[1] = $secondBasketItem;
$thirdBasketItem = new \Iyzipay\Model\BasketItem();
$thirdBasketItem->setId("BI103");
$thirdBasketItem->setName("Usb");
$thirdBasketItem->setCategory1("Electronics");
$thirdBasketItem->setCategory2("Usb / Cable");
$thirdBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$thirdBasketItem->setPrice("0.2");
$basketItems[2] = $thirdBasketItem;
$request->setBasketItems($basketItems);

$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, Config::options());
echo $checkoutFormInitialize->getCheckoutFormContent();
?>

