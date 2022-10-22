<?php

require_once(dirname(__DIR__).'/IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-RQBNY069ZOuPlVqxhScQyes3kSGNrsRf');
        $options->setSecretKey('sandbox-goG1J3Jh2ZzP7wdUez75Q8xdr99gYP59');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');

        return $options;
    }
}