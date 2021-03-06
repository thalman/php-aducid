<?php
include_once "config.php";
include_once "aducid/aducid.php";
include_once "basic_operations.php";
include_once "room_operations.php";
include_once "personal_factor_operations.php";
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
  <li><a href=\"".AducidClient::currentURL() . "?action=enterroombyname\">Enter room by name</a></li>
</ul>
<h1>Payment</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=initpayment\">Init</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=initpaymentpf\">Init + PF</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtext\">Confirm text</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextcs\">Confirm text (cs)</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextpf\">Confirm text + PF</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmtextcspf\">Confirm text + PF (cs)</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmmoney\">Confirm money transfer</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=confirmmoneypf\">Confirm money transfer + PF</a></li>
</ul>
</div>
<div class=\"column\">
<h1>Personal factor</h1>
<ul>
  <li><a href=\"".AducidClient::currentURL() . "?action=initpf\">Init personal factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=changepf\">Change personal factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=deletepf\">Delete personal factor</a></li>
  <li><a href=\"".AducidClient::currentURL() . "?action=verifypf\">Verify personal factor</a></li>
</ul>
<h1>Personal link</h1>
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
    $aducid->setFromRequest();
    if( $aducid->verify() ) {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ok.png\" /></a> udi ".$aducid->getUserDatabaseIndex() . "<br />";
    } else {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ko.png\" /></a><br />";
    }
    $all = $aducid->getPSLAttributes(AducidAttributeSetName::ALL);
    echo "
    <h2>Authentication process</h2>
    <table>
      <tr> <td><b>Action</b></td> <td><b>statusAIM</b></td> <td><b>statusAuth</b></td> </tr>
      <tr> <td>getPSLAttributes(ALL)</td> <td>".$all["statusAIM"]."</td>  <td>".$all["statusAuth"]."</td> </tr>\n";
    if( ! $aducid->verify() ) {
        $err = $aducid->getPSLAttributes(AducidAttributeSetName::ERROR);
        echo "      <tr> <td>getPSLAttributes(ERR)</td> <td>".$err["statusAIM"]."</td>  <td>".$err["statusAuth"]."</td> </tr>\n";
    }
    echo "
    </table>

    <h2>PSL(ALL)</h2>
    <table>
      <tr> <td><b>Attribute</b></td> <td><b>Value</b></td></tr>
      ";
    foreach( $all as $key => $value ) {
        echo "      <tr> <td>".$key."</td> <td>".$value."</td></tr>\n";
    }
    echo "
    </table>
";
    echo "<br /><br /><a href=\"". AducidClient::currentURL() . "\">Back</a>\n";
}

function verifyTransactionPage() {
    echo "<h1>Operation result</h1>";
    $aducid = new AducidSessionClient($GLOBALS['aim']);
    $aducid->setFromRequest();
    $transaction = $aducid->verifyTransaction(); 
    if( $transaction[ "result" ] ) {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ok.png\" /></a> udi ".$aducid->getUserDatabaseIndex() . "<br />";
    } else {
        echo "<a href=\"".AducidClient::currentURL()."\"><img src=\"images/ko.png\" /></a><br />";
    }
    $all = $aducid->getPSLAttributes(AducidAttributeSetName::ALL);
    echo "
    <h2>Authentication process</h2>
    <table>
      <tr> <td><b>Action</b></td> <td><b>statusAIM</b></td> <td><b>statusAuth</b></td> </tr>
      <tr> <td>getPSLAttributes(ALL)</td> <td>".$all["statusAIM"]."</td>  <td>".$all["statusAuth"]."</td> </tr>\n";
    if( ! $aducid->verify() ) {
        $err = $aducid->getPSLAttributes(AducidAttributeSetName::ERROR);
        echo "      <tr> <td>getPSLAttributes(ERR)</td> <td>".$err["statusAIM"]."</td>  <td>".$err["statusAuth"]."</td> </tr>\n";
    }
    echo "
    </table>
    <h2>Transaction</h2>
    <table>
      <tr> <td><b>Parameter</b></td> <td><b>value</b></td> </tr>";
    foreach( $transaction as $key => $value ) {
        echo "    <tr><td>$key</td><td>". (
            gettype($value) == "boolean" ?
            ( $value ? "true" : "false" ) :
            implode( "<br />" , str_split($value,50) ) )
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
    case "initpf":
        action_initPF();
        break;
    case "changepf":
        action_changePF();
        break;
    case "deletepf":
        action_deletePF();
        break;
    case "verifypf":
        action_verifyPF();
        break;
    case "initpayment":
        action_initPayment(false);
        break;
    case "initpaymentpf":
        action_initPayment(true);
        break;
    case "confirmtext":
        action_confirmTextTransaction( "Too much <i>yellow</i> <b>horse</b>!", false);
        break;
    case "confirmtextpf":
        action_confirmTextTransaction( "Too much <i>yellow</i> <b>horse</b>!", true );
        break;
    case "confirmtextcs":
        action_confirmTextTransaction( "Příliš <i>žluťoučký</i> <b>kůň</b>!", false);
        break;
    case "confirmtextcspf":
        action_confirmTextTransaction( "Příliš <i>žluťoučký</i> <b>kůň</b>!", true );
        break;
    case "confirmmoney":
        action_confirmMoneyTransaction( false );
        break;
    case "confirmmoneypf":
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
