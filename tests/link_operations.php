<?php

function action_replica( $whitch ) {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    switch( $whitch ) {
    case "primary":
        $aducid->peigLocalLink( "PrimaryReplica", AducidClient::currentURL() . "?action=verify" );
        break;
    case "secondary":
        $aducid->peigLocalLink( "SecondaryReplica", AducidClient::currentURL() . "?action=verify" );
        break;
    case "usb":
        $aducid->peigLocalLink("ConnectionUSB", AducidClient::currentURL() . "?action=verify" );
        break;
    }
    $aducid->invokePeig();
}

?>