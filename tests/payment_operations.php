<?php

function action_initPayment($useLF){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->initPayment( $useLF, AducidClient::currentURL() . "?action=verify" );
    $aducid->invokePeig();
}

function action_confirmTextTransaction( $text, $useLF ){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->confirmTextTransaction( $text, $useLF, AducidClient::currentURL() . "?action=verifytransaction" );
    $aducid->invokePeig();
}

function action_confirmMoneyTransaction( $useLF ){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->confirmMoneyTransaction( "YourAcount", "TomsAccount", 1000000, $useLF, AducidClient::currentURL() . "?action=verifytransaction" );
    $aducid->invokePeig();
}

?>