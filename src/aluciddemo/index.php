<?php
include "alucid/alucid.php";

$aim = "http://orangebox.example.com";
$uim = "https://orangebox.example.com/UIM/";

alucidRequire(2.0);

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
 * it starts the ALUCID session if not yet started.
 * Otherways it checks the alucid result
 */
function action_login() {
    $stage = 1;
    if( isset($_GET['stage']) ) { $stage = $_GET['stage']; }
    if( $stage == 1 ) {
        // stage 1 - start authentication
        // we don't have authId, lets start authentication
        $alucid = new AlucidSessionClient($GLOBALS['aim']);
        $alucid->open("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
        $alucid->invokePeig(
            AlucidTransferMethod::REDIRECT,
            NULL
        );
    } else {
        // stage 2 - verify authentication
        $alucid = new AlucidSessionClient($GLOBALS['aim']);
        $alucid->setFromRequest();
        if( $alucid->verify() ) {
            // authentication is ok, lets read user attributes
            $attributes = $alucid->getAttributes();
            if( $attributes != NULL ) {
                // we are interested in email
                if( isset($attributes["mail"]) ) {
                    $email = gettype($attributes["mail"]) == "array" ? implode(",",$attributes["mail"]) : $attributes["mail"];
                    $_SESSION["email"] = $email;
                }
            }
            // udi is usefull
            $_SESSION["udi"] = $alucid->getUserDatabaseIndex();
        }
    }
}

function action_change() {
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $alucid->change("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
    $alucid->invokePeig(
        AlucidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when identity rechange is requested.
 */
function action_rechange() {
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $alucid->rechange("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=login&stage=2");
    $alucid->invokePeig(
        AlucidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when we want update an email
 * email can contain more addresses separated by ","
 */
function action_changeEmail() {
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $newEmail = isset($_GET["newemail"]) ? $_GET["newemail"] : "";
    if( $newEmail != "" ) {
        // New email is set, lets update information in AIM
        if(
            $alucid->setAttributes(
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
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $alucid->close();

    unset($_SESSION["email"]);
    unset($_SESSION["udi"]);
}

/**
 * this function is called when new identity should be created.
 */
function action_create() {
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $alucid->init("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']);
    $alucid->invokePeig(
        AlucidTransferMethod::REDIRECT,
        NULL
    );
}

/**
 * this function is called when we want to delete identity.
 */
function action_delete() {
    unset($_SESSION["email"]);
    unset($_SESSION["udi"]);

    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $alucid->delete("http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?action=logout");
    $alucid->invokePeig(
        AlucidTransferMethod::REDIRECT,
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
    echo "<b>authId:</b> " . sessionvar("alucidAuthId") . "<br/>";
    echo "<hr />";
    echo '<form action="index.php"><input type="submit" value="login" /> <input type="hidden" name="action" value="login" /></form>';
} else {
    // logged in
    echo "<b>status: </b> logged in<br/><br/>";
    echo "<hr/>";
    echo "<b>authKey:</b> " . sessionvar("alucidAuthKey") . "<br>";
    echo "<b>authId:</b> " . sessionvar("alucidAuthId") . "<br>";
    echo "<b>udi:</b> " . sessionvar("udi") . "<br>";
    echo "<b>email:</b> " . sessionvar("email") . "<br>";
    echo "<hr/>";
    echo '<form action="index.php"><input type="submit" value="logout" /> <input type="hidden" name="action" value="logout" /></form>';
    echo '<form action="index.php"><input type="input" size="80" name="newemail" value="'.sessionvar("email").'"><input type="submit" value="Update email" /> <input type="hidden" name="action" value="changeEmail" /></form>';
}


// handle ALUCID error codes
if(sessionvar("alucidAuthId") != "") {
    $alucid = new AlucidSessionClient($GLOBALS['aim']);
    $result = $alucid->getResult();
    echo "<b>Status auth:</b> " ;
    print_r( $result );
    echo "<br>\n";
    switch ($result["statusAuth"]) {
        case AlucidAuthStatus::OK:
            // all is ok
            echo '<hr>alucid login result is OK ';
            echo '<form action="index.php">'.
                 '<button type="submit" name="action" value="change">change identity</button><button type="submit" name="action" value="delete">delete identity</button></form><hr>';
            break;
        case AlucidAuthStatus::UU:
            //user unknown
            //create identity
            echo '<hr>identity problem - <a href="'.$GLOBALS['uim'].'/">do reinit on UIM</a><hr>';
            break;
        case AlucidAuthStatus::UI:
            // rechange needed
            echo '<hr>identity expired<hr>';
            echo '<form action="index.php"><input type="hidden" name="action" value="rechange">'.
                 '<input type="submit" value="rechange identity"></form><hr>';
            break;
        case AlucidAuthStatus::NAU:
            // user refused authentiction
            echo '<hr>You refused authentication<hr>';
            break;
        case AlucidAuthStatus::USP:
            // new user = user doesn't have identity yet
            echo '<hr>You are not registered. Create Your identity on <a href="'.$GLOBALS['uim'].'/">UIM</a><hr>';
            break;
        case AlucidAuthStatus::PPNP:
            // peig is not available
            echo '<hr>Switch Your PEIG on, please<hr>';
            break;
        case AlucidAuthStatus::STO:
            // communication problem with peig proxy
            echo '<hr>Communication problem, please check peigproxy and network connectivity<hr>';
            break;
        case AlucidAuthStatus::PTO:
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
