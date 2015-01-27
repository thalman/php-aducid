<?php
include "aducid/aducid.php";

$aim = "http://orangebox.example.com";
$uim = "https://orangebox.example.com/UIM/";

aducidRequire(2.0);

/**
 * returns session variable or "" if not present
 */
function sessionvar($name) {
    if( isset($_SESSION[$name]) ) return $_SESSION[$name];
    return "";
}

/**
 * prints html page head
 */
function head($title){
echo
"<html>
  <head>
    <title>$title</title>
  </head>
<body>";
}

/**
 * prints html page foot
 */
function foot(){
  echo "\n<hr>\n<a href=\"".$GLOBALS['uim']."\">Go to UIM</a>\n";
  echo "\n</body>\n</html>\n";
}


/**
 * this function is called on login attempt
 * it starts the ADUCID session if not yet started.
 * Otherways it checks the aducid result
 */
function action_login() {
    $stage = 1;
    if( isset($_GET['stage']) ) { $stage = $_GET['stage']; }
    if( $stage == 1 ) {
        // stage 1 - start authentication
        // we don't have authId, lets start authentication
        $aducid = new AducidSessionClient($GLOBALS['aim']);
        $aducid->open("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
        $aducid->invokePeig(
            AducidTransferMethod::REDIRECT,
            NULL
        );
    } else {
        // stage 2 - verify authentication
        $aducid = new AducidSessionClient($GLOBALS['aim']);
        $aducid->setFromRequest();
        if( $aducid->verify() ) {
            // authentication is ok, lets read user attributes
            $attributes = $aducid->getAttributes();
            if( $attributes != NULL ) {
                // we are interested in email
                if( isset($attributes["mail"]) ) {
                    $email = gettype($attributes["mail"]) == "array" ? implode(",",$attributes["mail"]) : $attributes["mail"];
                    $_SESSION["email"] = $email;
                }
            }
            // udi is usefull
            $_SESSION["udi"] = $aducid->getUserDatabaseIndex();
        }
    }
}

function action_change() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->change("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
    $aducid->invokePeig(
        AducidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when identity rechange is requested.
 */
function action_rechange() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->rechange("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
    $aducid->invokePeig(
        AducidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when we want update an email
 * email can contain more addresses separated by ","
 */
function action_changeEmail() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $newEmail = isset($_GET["newemail"]) ? $_GET["newemail"] : "";
    if( $newEmail != "" ) {
        // New email is set, lets update information in AIM
        if(
            $aducid->setAttributes(
                "UIM",
                array("mail" => explode(",",$newEmail), "cn" => "default cn", "sn" => "default sn" )
            )
        ) {
            // save it to session if updated successfully
            $_SESSION["email"] = $newEmail;
        }
    }
}

/**
 * this function is called on logout
 */
function action_logout(){
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->close();

    unset($_SESSION["email"]);
    unset($_SESSION["udi"]);
}

/**
 * this function is called when new identity should be created.
 */
function action_create() {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->init("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']);
    $aducid->invokePeig(
        AducidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when we want to delete identity.
 */
function action_delete() {
    unset($_SESSION["email"]);
    unset($_SESSION["udi"]);

    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->delete("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=logout");
    $aducid->invokePeig(
        AducidTransferMethod::REDIRECT,
        NULL
    );
}

// what action do we do?
session_start();

$action = "";
if( isset($_POST["action"]) ) { $action = $_POST["action"]; }
if( isset($_GET["action"]) ) { $action = $_GET["action"]; }

head("php demo");
echo "<b>action:</b> ".$action."<br>";

// do the action
switch($action) {
    case "logout":
        action_logout();
        break;
    case "login":
        action_login();
        break;
    case "create":
        action_create();
        break;
    case "change":
        action_change();
        break;
    case "rechange":
        action_rechange();
        break;
    case "delete":
        action_delete();
        break;
    case "fill":
        action_fill();
        break;
    case "changeEmail":
        action_changeEmail();
        break;
}

// print the page body
if(sessionvar("udi") == "") {
    // not logged in
    echo "<b>status: </b> not logged in<br/><hr>";
    echo "<b>authId:</b> " . sessionvar("aducidAuthId") . "<br/>";
    echo "<hr />";
    echo '<form action="index.php"><input type="submit" value="login" /> <input type="hidden" name="action" value="login" /></form>';
} else {
    // logged in
    echo "<b>status: </b> logged in<br/><br/>";
    echo "<hr/>";
    echo "<b>authKey:</b> " . sessionvar("aducidAuthKey") . "<br>";
    echo "<b>authId:</b> " . sessionvar("aducidAuthId") . "<br>";
    echo "<b>udi:</b> " . sessionvar("udi") . "<br>";
    echo "<b>email:</b> " . sessionvar("email") . "<br>";
    echo "<hr/>";
    echo '<form action="index.php"><input type="submit" value="logout" /> <input type="hidden" name="action" value="logout" /></form>';
    echo '<form action="index.php"><input type="input" size="80" name="newemail" value="'.sessionvar("email").'"><input type="submit" value="Update email" /> <input type="hidden" name="action" value="changeEmail" /></form>';
}


// handle ADUCID error codes
if(sessionvar("aducidAuthId") != "") {
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $result = $aducid->getResult();
    echo "<b>Status auth:</b> " ;
    print_r( $result );
    echo "<br>\n";
    switch ($result["statusAuth"]) {
        case AducidAuthStatus::OK:
            // all is ok
            echo '<hr>aducid login result is OK ';
            echo '<form action="index.php">'.
                 '<button type="submit" name="action" value="change">change identity</button><button type="submit" name="action" value="delete">delete identity</button></form><hr>';
            break;
        case AducidAuthStatus::UU:
            //user unknown
            //create identity
            echo '<hr>identity problem - <a href="'.$GLOBALS['uim'].'/">do reinit on UIM</a><hr>';
            break;
        case AducidAuthStatus::UI:
            // rechange needed
            echo '<hr>identity expired<hr>';
            echo '<form action="index.php"><input type="hidden" name="action" value="rechange">'.
                 '<input type="submit" value="rechange identity"></form><hr>';
            break;
        case AducidAuthStatus::NAU:
            // user refused authentiction
            echo '<hr>You refused authentication<hr>';
            break;
        case AducidAuthStatus::USP:
            // new user = user doesn't have identity yet
            echo '<hr>You are not registered. Create Your identity on <a href="'.$GLOBALS['uim'].'/">UIM</a><hr>';
            break;
        case AducidAuthStatus::PPNP:
            // peig is not available
            echo '<hr>Switch Your PEIG on, please<hr>';
            break;
        case AducidAuthStatus::STO:
            // communication problem with peig proxy
            echo '<hr>Communication problem, please check peigproxy and network connectivity<hr>';
            break;
        case AducidAuthStatus::PTO:
            // authentication timeout
            echo '<hr>Authentication timeout, please buy better internet access<hr>';
            break;
    }
}

// print session, just for fun
echo "<b>Session:</b> "; print_r($_SESSION);

//print the page bottom
foot();

?>
