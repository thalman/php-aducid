<?php

include_once "aducid/aducid.php";

function action_init() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->init($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_open() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->open($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}


function action_change() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->change($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig( );
}

function action_delete() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->delete($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_reinit() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->reinit($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_rechange() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->rechange($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

    
?>