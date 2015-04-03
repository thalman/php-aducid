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

function logged_in_page( $aducid ) {
    $ATTRS = $aducid->getAttributes("UIM");
    $mail = ( isset($ATTRS["mail"]) ? $ATTRS["mail"] : "" );
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
        "<html>\n".
        "<head><title>attribute demo</title><link rel=\"stylesheet\" type=\"text/css\" href=\"demo.css\"></head>\n" .
        "<body><div id=\"content\">".
        "<h1>Welcome " . $_SESSION["udi"] . " (". ( $mail == "" ? "email not set" : $mail )  .")</h1>".
        "<form action=\"attributes.php\" method=\"GET\" >" .
        "<input type=\"text\" name=\"mail\" value=\"" . $mail . "\" />" .
        "<input type=\"submit\" value=\"update\" />" .
        "<input type=\"hidden\" name=\"action\" value=\"update\" />" .
        "</form>".
        "<p>You are logged in.</p>".
        "<a href=\"?action=logout\">LOGOUT</a>\n".
        "</div></body>" .
        "</html>";
}

function login_page($error="") {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n" .
        "<html>\n".
        "<head><title>attribute demo</title><link rel=\"stylesheet\" type=\"text/css\" href=\"demo.css\"></head>\n" .
        "<body><div id=\"content\">".
        "<h1>ADUCID attribute demo</h1>\n".
        "<p>This demo application shows how to login and logout with ADUCID. ".
        "Then it allows to read/update user mail attribute stored in AIM. " .
        "Demo presumes, that you have PEIG and valid identity created on ".
        "<a href=\"".$GLOBALS["aim"]."/UIM/\">AIM</a>.</p>";
    if($error != "") {
        echo "<p>Login failed with error code <b>". $error . "</b>!</p>";
    }
    echo
        "<p>You are not logged in.</p>".
        "<a href=\"?action=login\">LOGIN</a>\n".
        "</div></body>" .
        "</html>";
}


session_start();

$action = "none";
if(isset($_REQUEST["action"]) ) { $action = $_REQUEST["action"]; }
error_log("ACTION: ".$action );
$aducid = new AducidSessionClient($GLOBALS["aim"]);

switch($action) {
case "login":
    $aducid->open( AducidClient::currentURL() . "?action=verify" );
    $aducid->invokePeig();
    break;
case "verify":
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
case "update":
    $ATTRS = $aducid->getAttributes("UIM");
    if( count( $ATTRS ) == 0 ) {
        // user is not registered (has no attributes) so we have to provide all mandatory attributes
        // by default it is common name and surename, but it depends on LDAP configuration
        $aducid->setAttributes("UIM", array( "mail" => $_REQUEST["mail"],  "sn" => $_REQUEST["mail"], "cn" => $_REQUEST["mail"] ) );
    } else {
        $aducid->setAttributes("UIM", array( "mail" => $_REQUEST["mail"] ) );
    }
    break;
case "logout":
    $aducid->close();
    session_unset();
    break;
}

if( isset($_SESSION["udi"]) ) {
    logged_in_page( $aducid );
} else {
    login_page();
}

?>