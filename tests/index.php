<?php
include_once "config.php";
include_once "aducid/aducid.php";
include_once "basic_operations.php";
include_once "room_operations.php";
include_once "local_factor_operations.php";
include_once "payment_operations.php";
include_once "link_operations.php";

aducidRequire(3.0);

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
    <link rel=\"stylesheet\" type=\"text/css\" href=\"testing.css\">
  </head>
<body>
  <div id=\"content\">";
}

/**
 * prints html page foot
 */
function foot(){
  echo "\n  </div>\n</body>\n</html>\n";
}

function startingPage() {
    echo "
<div class=\"column\">
<h1>Basic operations</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=init\">Init</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=open\">Open</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=change\">Change</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=delete\">Delete</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=reinit\">Reinit</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=rechange\">Rechange</a></li>
</ul>
<h1>Meetings</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=createroombystory\">Create room by story</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=enterroombystory\">Enter room by story</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=createroombyname\">Create room by name</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=enterroombystory\">Enter room by name</a></li>
</ul>
<h1>Payment</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=initpayment\">Init</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=initpaymentlf\">Init + LF</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtext\">Confirm text</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextcs\">Confirm text (cs)</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextlf\">Confirm text + LF</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextcslf\">Confirm text + LF (cs)</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmmoney\">Confirm money transfer</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmmoneylf\">Confirm money transfer + LF</a></li>
</ul>
</div>
<div class=\"column\">
<h1>Local factor</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=initlf\">Init local factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=changelf\">Change local factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=deletelf\">Delete local factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=verifylf\">Verify local factor</a></li>
</ul>
<h1>Local link</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=primaryreplica\">Primary replica</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=secondaryreplica\">Secondary replica</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=connectionusb\">Connection USB</a></li>
</ul>
</div>
";
}

function verifyPage() {
    echo "<h1>Operation result</h1>";
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    if( $aducid->verify() ) {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ok.png\" /></a> udi ".$aducid->getUserDatabaseIndex() . "<br />";
    } else {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ko.png\" /></a><br />";
    }
    $all = $aducid->getResult(AducidAttributeSetName::ALL);
    $err = $aducid->getResult(AducidAttributeSetName::ERROR);
    echo "
    <h2>Authentication process</h2>
    <table>
      <tr> <td><b>Action</b></td> <td><b>statusAIM</b></td> <td><b>statusAuth</b></td> </tr>
      <tr> <td>getResult(ALL)</td> <td>".$all["statusAIM"]."</td>  <td>".$all["statusAuth"]."</td> </tr>
      <tr> <td>getResult(ERR)</td> <td>".$err["statusAIM"]."</td>  <td>".$err["statusAuth"]."</td> </tr>      
    </table>
";
    echo "<br /><br /><a href=\"". AducidClient::currentURL() . "\">Back</a>\n";
}

function verifyTransactionPage() {
    echo "<h1>Operation result</h1>";
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $transaction = $aducid->verifyTransaction(); 
    if( $transaction[ "result" ] ) {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ok.png\" /></a> udi ".$aducid->getUserDatabaseIndex() . "<br />";
    } else {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ko.png\" /></a><br />";
    }
    $all = $aducid->getResult(AducidAttributeSetName::ALL);
    $err = $aducid->getResult(AducidAttributeSetName::ERROR);
    echo "
    <h2>Authentication process</h2>
    <table>
      <tr> <td><b>Action</b></td> <td><b>statusAIM</b></td> <td><b>statusAuth</b></td> </tr>
      <tr> <td>getResult(ALL)</td> <td>".$all["statusAIM"]."</td>  <td>".$all["statusAuth"]."</td> </tr>
      <tr> <td>getResult(ERR)</td> <td>".$err["statusAIM"]."</td>  <td>".$err["statusAuth"]."</td> </tr>      
    </table>
    <h2>Transaction</h2>
    <table>
      <tr> <td><b>Parameter</b></td> <td><b>value</b></td> </tr>";
    foreach( $transaction as $key => $value ) {
        echo "    <tr><td>$key</td><td>". (
            gettype($value) == "boolean" ?
            ( $value ? "true" : "false" ) :
            implode( "<br />" , str_split($value,30) ) )
            ." (" .gettype($value).") </td></tr>\n";
    }
    echo "    </table>\n";
    echo "<br /><br /><a href=\"". AducidClient::currentURL() . "\">Back</a>\n";
}



// what action do we do?
session_start();

$action = "";
if( isset($_POST["action"]) ) { $action = $_POST["action"]; }
if( isset($_GET["action"]) ) { $action = $_GET["action"]; }

// do the action
switch($action) {
    case "verify":
        head("PHP Testing application - verification");
        verifyPage();
        foot();
        exit;
    case "verifytransaction":
        head("PHP Testing application - verification");
        verifyTransactionPage();
        foot();
        exit;
    case "init":
        action_init();
        break;
    case "open":
        action_open();
        break;
    case "change":
        action_change();
        break;
    case "delete":
        action_delete();
        break;
    case "reinit":
        action_reinit();
        break;
    case "rechange":
        action_rechange();
        break;
    case "createroombyname":
        action_createRoomByName();
        break;
    case "enterroombyname":
        action_enterRoomByName();
        break;
    case "createroombystory":
        action_createRoomByStory();
        break;
    case "enterroombystory":
        action_enterRoomByStory();
        break;
    case "initlf":
        action_initLF();
        break;
    case "changelf":
        action_changeLF();
        break;
    case "deletelf":
        action_deleteLF();
        break;
    case "verifylf":
        action_verifyLF();
        break;
    case "initpayment":
        action_initPayment(false);
        break;
    case "initpaymentlf":
        action_initPayment(true);
        break;
    case "confirmtext":
        action_confirmTextTransaction( "Too much <i>yellow</i> <b>horse</b>!", false);
        break;
    case "confirmtextlf":
        action_confirmTextTransaction( "Too much <i>yellow</i> <b>horse</b>!", true );
        break;
    case "confirmtextcz":
        action_confirmTextTransaction( "Příliš <i>žluťoučký</i> <b>kůň</b>!", false);
        break;
    case "confirmtextczlf":
        action_confirmTextTransaction( "Příliš <i>žluťoučký</i> <b>kůň</b>!", true );
        break;
    case "confirmmoney":
        action_confirmMoneyTransaction( false );
        break;
    case "confirmmoneylf":
        action_confirmMoneyTransaction( true );
        break;
    case "primaryreplica":
        action_replica("primary");
        break;
    case "secondaryreplica":
        action_replica("secondary");
        break;
    case "connectionusb":
        action_replica("usb");
        break;
}

head("PHP Testing application");
startingPage();
foot();

?>
