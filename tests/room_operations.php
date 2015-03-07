<?php

function action_createRoomByName(){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->createRoomByName("testingroom",$aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_enterRoomByName() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->enterRoomByName("testingroom",$aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}

function action_createRoomByStory() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->createRoomByStory($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}
function action_enterRoomByStory() {
    $aducid = new AducidSessionClient($GLOBALS['aim'], "temp");
    $aducid->enterRoomByStory($aducid->currentURL() . "?action=verify");
    $aducid->invokePeig();
}


?>