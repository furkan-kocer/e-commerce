<?php

require('vendor/iyzico/iyzipay-php/IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('YourApiKeyHere');
        $options->setSecretKey('YourSecretKeyHere');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');

        return $options;
    }
}
