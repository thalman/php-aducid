<?php

function action_initLF(){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->initPersonalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_changeLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->changePersonalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_deleteLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->deletePersonalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_verifyLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->verifyPersonalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}


?>