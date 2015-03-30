<?php

/* 
 * Copyright(c) 2015 Tomas Halman
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

include_once "aducid/aducid.php";
include_once "config.php";

// check the SDK version
try {
    aducidRequire(3.0);
} catch( Exception $e ) {
    echo $e->getMessage();
    exit;
}

function logged_in_page() {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
        "<html>\n".
        "<head><title>login demo</title><link rel=\"stylesheet\" type=\"text/css\" href=\"demo.css\"></head>\n" .
        "<body><div id=\"content\">".
        "<h1>Welcome " . $_SESSION["udi"] . "</h1>".
        "<p>Every user has its unique identifier in ADUCID, called user databese index (in Your case " . $_SESSION["udi"] . ").".
        "It is unique per AIM and it is persistent, so the application can distinguish particular user.</p>".
        "<p>You are loggen in.</p>".
        "<a href=\"?action=logout\">LOGOUT</a>\n".
        "</div></body>" .
        "</html>";
}

function login_page($error="") {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
        "<html>\n".
        "<head><title>login demo</title><link rel=\"stylesheet\" type=\"text/css\" href=\"demo.css\"></head>\n" .
        "<body><div id=\"content\">".
        "<h1>ADUCID login demo</h1>\n".
        "<p>This demo application shows how to login and logout with ADUCID. ".
        "Demo presumes, that You have PEIG and valid identity created on ".
        "<a href=\"".$GLOBALS["aim"]."UIM/\">AIM</a>.</p>";
    if($error != "") {
        echo "<p>Login failed with error code <b>". $error . "</b>!</p>";
    }
    echo
        "<p>You are not loggen in.</p>".
        "<a href=\"?action=login\">LOGIN</a>\n".
        "</div></body>" .
        "</html>";
}


session_start();

$action = "none";
if(isset($_REQUEST["action"]) ) { $action = $_REQUEST["action"]; }
error_log("ACTION: ".$action );
switch($action) {
case "login":
    $aducid = new AducidSessionClient($GLOBALS["aim"]);
    $aducid->open( AducidClient::currentURL() . "?action=verify" );
    $aducid->invokePeig();
    break;
case "verify":
    $aducid = new AducidSessionClient($GLOBALS["aim"]);
    $aducid->setFromRequest();
    if( $aducid->verify() ) {
        $_SESSION["udi"] = $aducid->getUserDatabaseIndex();
    } else {
        $result = $aducid->getPSLAttributes();
        error_log(var_export($result,true));
        login_page($result["statusAuth"]);
        exit;
    }
    break;
case "logout":
    $aducid = new AducidSessionClient($GLOBALS["aim"]);
    $aducid->close();
    session_unset();
}

if( isset($_SESSION["udi"]) ) {
    logged_in_page();
} else {
    login_page();
}

?>