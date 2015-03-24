<?php

function action_initLF(){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->initLocalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_changeLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->changeLocalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_deleteLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->deleteLocalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}

function action_verifyLF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->verifyLocalFactor($aducid->currentURL() . "?action=verifytransaction");
    $aducid->invokePeig();
}


?>