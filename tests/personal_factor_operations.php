<?php

function action_initPF(){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->initPersonalFactor($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_changePF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->changePersonalFactor($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_deletePF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->deletePersonalFactor($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_verifyPF() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->verifyPersonalFactor($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}


?>