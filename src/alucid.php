<?php


include_once "alucidenums.php";

/**
 * \brief Function returns sdk level
 */
function alucidVersion() {
    return 2.51;
}

/**
 *
 * AlucidMessageSender implements methods for accessing R4 interface
 *
 */
class AlucidMessageSender {
    /**
     *
     * returns array with PSL attributes
     *
     */
    function callGetPSLAttributes($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.alucid.anect.com",
                    "style"    => SOAP_RPC,
                    "use"      => SOAP_ENCODED,
                    "exceptions" => true,
                    "trace"    => 1
        ));
        $soapParams = array();
        if( isset($request["authId"]) && ( $request["authId"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["authId"],"authId") );
        }
        if( isset($request["bindingId"]) && ( $request["bindingId"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["bindingId"],"bindingId") );
        }
        if( isset($request["AIMName"]) && ( $request["AIMName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["AIMName"],"AIMName") );
        }
        if( isset($request["authKey"]) && ( $request["authKey"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["authKey"],"authKey") );
        }
        if( isset($request["attributeSetName"]) && ( $request["attributeSetName"] != NULL )  ) {
            array_push($soapParams, new SoapParam($request["attributeSetName"],"attributeSetName") );
        }
        try {
            $result = $soap->__call(
                "AIMgetPSLAttributes",
                $soapParams
                );
            //error_log("PSL result: ".var_export($result,true));
            if( isset( $result["personalObject"]->personalObjectAttribute ) ) {
                //error_log("PSL result: ".var_export($result,true));
                $xml=$soap->__getLastResponse();
                //error_log("reply: " . var_export($x,true));
                $po = $this->parsePOReply($xml);
                //error_log("reply parsed: " . var_export($x,true));
                //$result["personalObject"] = $x;
                $result["personalObject"]->personalObjectAttribute = $po;
                //error_log( "result: ". var_export($result,true) );
            }
        } catch ( Exception $e ) {
            $result = array();
            $result["statusAuth"] == "SDK_ERROR";
            $result["statusAIM"] == $e->getMessage();
        }
        return $result;
    }
    /**
     *
     * Starts operation on aim
     *
     */
    function callRequestOperation($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.alucid.anect.com",
                    "style"    => SOAP_RPC,
                    "use"      => SOAP_ENCODED,
                    "exceptions" => true
        ));
        $soapParams = array();
        if( isset($request["operationName"]) && ( $request["operationName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["operationName"],"operationName") );
        }
        if( isset($request["AIMName"]) && ( $request["AIMName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["AIMName"],"AIMName") );
        }
        if( isset($request["authId"]) && ( $request["authId"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["authId"],"authId") );
        }
        if( isset($request["bindingKey"]) && ( $request["bindingKey"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["bindingKey"],"bindingKey") );
        }
        if( isset($request["methodName"]) && ( $request["methodName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["methodName"],"methodName") );
        }
        if( isset($request["methodParameter"])  && ( $request["methodParameter"] != NULL ) ) {
            $xml = $this->methodParamsToXML($request["methodParameter"]);
            if( $xml != "" ) {
                array_push($soapParams, new SoapVar($xml,XSD_ANYXML) );
            }
        }
        if( isset($request["personalObject"]) && ( $request["personalObject"] != NULL ) ) {
            $poname  = isset($request["personalObject"]["personalObjectName"])
                ? $request["personalObject"]["personalObjectName"] : NULL;
            $poattrs = isset($request["personalObject"]["personalObjectTypeName"])
                ? $request["personalObject"]["personalObjectTypeName"] : NULL;
            $alg     = isset($request["personalObject"]["personalObjectAlgorithmName"])
                ? $request["personalObject"]["personalObjectAlgorithmName"] : NULL;
            // createPersonalObjectXML($name, $type, $algorithm)
            array_push($soapParams, new SoapVar($this->createPersonalObjectXML($poname, $poattrs, $alg),XSD_ANYXML) );
        }
        if( isset($request["AAIM2"]) && ( $request["AAIM2"] != NULL ) )  {
            array_push($soapParams, new SoapParam($request["AAIM2"],"AAIM2") );
        }
        if( isset($request["ilData"]) && ( $request["ilData"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["ilData"],"ilData") );
        }
        if( isset($request["peigReturnName"]) && ( $request["peigReturnName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["peigReturnName"],"peigReturnName") );
        }
        try {
            $result = $soap->__call(
                "AIMrequestOperation",
                $soapParams
                );
        } catch ( Exception $e ) {
            $result = array();
            $result["statusAuth"] == "SDK_ERROR";
            $result["statusAIM"] == $e->getMessage();
        }
        return $result;
    }
    /**
     *
     * Closes the AIM session
     *
     */
    function callCloseSession($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.alucid.anect.com",
                    "style"    => SOAP_RPC,
                    "use"      => SOAP_ENCODED
        ));
        $soapParams = array();
        if( isset($request["authId"])  && ( $request["authId"] != NULL )  ) {
            array_push($soapParams, new SoapParam($request["authId"],"authId") );
        }
        if( isset($request["AIMName"]) && ( $request["AIMName"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["AIMName"],"AIMName") );
        }
        if( isset($request["authKey"]) && ( $request["authKey"] != NULL ) ) {
            array_push($soapParams, new SoapParam($request["authKey"],"authKey") );
        }
        try {
            $result = $soap->__call(
                "AIMcloseSession",
                $soapParams
            );
        } catch (Exception $e) {
            $result = array();
            $result["statusAuth"] == "SDK_ERROR";
            $result["statusAIM"] == $e->getMessage();
        }
        return $result;
    }
    /**
     *
     * returns name of the DOM node
     *
     */
    private function getNameFromNode($node) {
        $attrs = $node->attributes;
        for($i = 0; $i<$attrs->length; $i++) {
            $name = $attrs->item($i)->name;
            if( $name == "attributeName" ) {
                return $attrs->item($i)->nodeValue;
            }
        }
        return NULL;
    }
    /**
     *
     * returns value of the DOM node
     *
     */
    private function getValueFromNode($node) {
        $childs = $node->childNodes;
        for($i = 0; $i<$childs->length; $i++) {
            $node = $childs->item($i);
            if($node->nodeName == "attributeValue") {
                return $node->nodeValue;
            }
        }
        return NULL;
    }
    /**
     *
     * parses XML reply and returns personal object as an array
     *
     */
    private function parsePOReply($xmlstring){
        $result = array();
        $dom = new DOMDocument();
        $dom->loadXML($xmlstring);
        $nodes = $dom->getElementsByTagName("personalObjectAttribute");
        foreach( $nodes as $node ) {
            $name = $this->getNameFromNode($node);
            $value = $this->getValueFromNode($node);
            if($name != NULL) {
                if( isset($result[$name]) ) {
                    if( gettype($result[$name]) == "array" ) {
                        array_push($result[$name],$value);
                    } else {
                        $a = array( $result[$name], $value);
                        $result[$name] = $a;
                    }
                } else {
                    $result[$name] = $value;
                }
            }
        }
        return $result;
    }
    private function methodParamsToXML($params) {
        $xml = "";
        if(is_array($params) ) {
            foreach( $params as $name => $value ) {
                $xml .=  "<methodParameter>\n".
                         "    <parameterName xsi:type=\"xsd:string\">".$name."</parameterName>\n".
                         "    <parameterValue xsi:type=\"xsd:string\">".$value."</parameterValue>\n".
                         "</methodParameter>\n";
            }
        }
        return $xml;
    }
    private function createPersonalObjectXML($name, $type, $algorithm) {
        /*
         <personalObject xsi:type="xsd:string">
             <personalObjectName xsi:type="xsd:string">servis24.csas.cz</personalObjectName>
             <personalObjectTypeName xsi:type="xsd:string">payment</personalObjectTypeName>
             <personalObjectAlgorithmName xsi:type="xsd:string">PAYMENT</personalObjectAlgorithmName>
         </personalObject>
        */
        $xml = "<personalObject>\n<personalObjectName xsi:type=\"xsd:string\">" . $name . "</personalObjectName>\n" ;
        if($type != NULL ) {$xml .= "<personalObjectTypeName xsi:type=\"xsd:string\">".$type."</personalObjectTypeName>\n"; }
        if($algorithm != NULL ) {$xml .= "<personalObjectAlgorithmName xsi:type=\"xsd:string\">".$algorithm."</personalObjectAlgorithmName>\n"; }
        $xml .= "</personalObject>\n";
        return $xml;
    }
    /**
     *
     * implements READ operation for personalObject attributes
     *
     */
    private function readPersonalObject($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.alucid.anect.com",
                    "style"    => SOAP_RPC,
                    "use"      => SOAP_ENCODED,
                    "trace"    => 1,
                    "exceptions" => true
        ));
        if( ! isset($request["personalObject"]) ) {
            return NULL;
        };
        $params = array();
        if( isset($request["authId"] ) && ($request["authId"] != NULL) ) {
            array_push($params, new SoapParam($request["authId"],"authId") );
        };
        if( isset($request["AIMname"] ) && ($request["AIMname"] != NULL) ) {
            array_push($params, new SoapParam($request["AIMname"],"AIMname") );
        };
        if( isset($request["authKey"] ) && ($request["authKey"] != NULL) ) {
            array_push($params, new SoapParam($request["authKey"],"authKey") );
        };
        array_push($params, new SoapParam(AlucidPersonalObjectMethod::READ,"methodName"));
        $personalObject = $request["personalObject"];
        $pon = isset($personalObject["personalObjectName"])
            ? new SoapVar("<personalObject><personalObjectName>".$personalObject["personalObjectName"]."</personalObjectName></personalObject>",XSD_ANYXML)
            : NULL;
        if( $pon != NULL) { array_push($params, $pon ); }
        try {
            $result = $soap->__call(
                "AIMexecutePersonalObject",
                $params
            );
            $x=$soap->__getLastResponse();
            // error_log("reply: " . var_export($x,true));
            $x = $this->parsePOReply($x);
            // error_log("reply parsed: " . var_export($x,true));
            $result["personalObject"] = $x;
        } catch ( Exception $e ) {
            $result = array();
            $result["statusAuth"] == "SDK_ERROR";
            $result["statusAIM"] == $e->getMessage();
        }
        return $result;
    }
    /**
     *
     * implements WRITE operation for personalObject attributes
     *
     */
    private function writePersonalObject($R4URL,$request) {
        if( ! isset($request["personalObject"]) ) {
            return NULL;
        };
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.alucid.anect.com",
                    "style"    => SOAP_RPC,
                    "use"      => SOAP_ENCODED,
                    "trace"    => 1,
                    "exceptions" => true
        ));
        $params = array();
        if( isset($request["authId"] ) and ($request["authId"] != NULL) ) {
            array_push($params, new SoapParam($request["authId"],"authId"));
        }
        if( isset($request["AIMname"] ) && ($request["AIMname"] != NULL) ) {
            array_push($params, new SoapParam($request["AIMname"],"AIMname") );
        };
        if( isset($request["authKey"] ) and ($request["authKey"] != NULL) ) {
            array_push($params,new SoapParam($request["authKey"],"authKey"));
        }
        array_push($params,new SoapParam(AlucidPersonalObjectMethod::WRITE,"methodName"));
        
        $personalObject = $request["personalObject"];
        $xml="<personalObject>\n<personalObjectName>" . $personalObject["personalObjectName"] . "</personalObjectName>\n" ;
        while( list($key,$val) = each($personalObject["personalObject"]) ) {
            if( gettype($val) == "array" ) {
                foreach($val as $value) {
                    $xml .= "<personalObjectAttribute ns1:attributeName=\"".$key."\">\n";
                    $xml .= "  <attributeValue xmlns:s115=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:s116=\"http://www.w3.org/2001/XMLSchema\" s115:type=\"s116:string\">".$value."</attributeValue>\n";
                    $xml .= "</personalObjectAttribute>\n";
                }
            } else {
                $xml .= "<personalObjectAttribute ns1:attributeName=\"".$key."\">\n";
                $xml .= "  <attributeValue xmlns:s115=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:s116=\"http://www.w3.org/2001/XMLSchema\" s115:type=\"s116:string\">".$val."</attributeValue>\n";
                $xml .= "</personalObjectAttribute>\n";
            }
        }
        $xml .= "</personalObject>\n";
        $po = new SoapVar($xml,XSD_ANYXML);

        array_push($params,$po);
        try {
            $result = $soap->__call(
                "AIMexecutePersonalObject",
                $params
            );
        } catch ( Exception $e ) {
            $result = array();
            $result["statusAuth"] == "SDK_ERROR";
            $result["statusAIM"] == $e->getMessage();
        }
        return $result;
    }
    /**
     *
     * implements operation with directory object
     *
     * Function checks requested operation and call appropriate method.
     * Implemented operations are READ and WRITE. Otherwise Exception is
     * raised.
     *
     */
    function callExecutePersonalObject($R4URL,$request) {
        if(! isset($request["methodName"]) ) {
            throw new Exception('method not specified');
        }
        $method = $request["methodName"];
        switch($method) {
            case AlucidPersonalObjectMethod::READ:
                return $this->readPersonalObject($R4URL,$request);
            case AlucidPersonalObjectMethod::WRITE:
                return $this->writePersonalObject($R4URL,$request);
            default:
                throw new Exception('Method '.$method.' is not implemented');
        }
        throw new Exception('callExecutePersonalObject not implemented');
    }
}

/**
 *
 * Basic class for working with ALUCID
 *
 */
class AlucidClient {
    protected $sender;
    protected $AIM;
    protected $R4;

    public $authId;
    public $authKey;
    public $AIMName;
    public $bindingId;
    public $bindingKey;

    /**
     *
     * Parameter $AIM is address of aim server. It can be DNS name, IP address or URL.
     * Valid values are for example "aim.example.com", "10.0.0.42:8080" or
     * "https://aim.example.com:8443/AIM/services/R4".
     *
     * Parameters authId and authKey are ALUCID credentials. If they are NULL, constructor
     * attempts to fill them from $_REQUEST["authId"] and $_REQUEST["authKey"]
     *
     * Parameter $AIMName is name of virtual AIM.
     *
     */
    function __construct($AIM,$authId=NULL,$authKey=NULL, $bindingId=NULL, $bindingKey=NULL, $AIMName=NULL) {
        $parts = $this->normalizeR4URL($AIM);
        $this->AIM = $parts[1];
        $this->R4  = $parts[0]."://".$parts[1]."/".$parts[2];
        $this->authId = NULL;
        $this->authKey = NULL;
        $this->AIMName = NULL;
        $this->bindingId = NULL;
        $this->bindingKey = NULL;

        $this->sender = new AlucidMessageSender();

        $this->setFromRequest();

        if( $authId != NULL ) {
            $this->authId = $authId;
            $this->authKey = $authKey;
            $this->bindingId = $bindingId;
            $this->bindingKey = $bindingKey;
        }
        $this->AIMName = $AIMName;
    }
    function setFromRequest() {
        if( isset($_REQUEST["authId"]) ) {
            if( $this->authId != $_REQUEST["authId"] ) {
                // authId changed => forgot old values
                $this->authKey = NULL;
                $this->bindingId = NULL;
                $this->bindingKey = NULL;
            }
            $this->authId = $_REQUEST["authId"];
            if( isset($_REQUEST["authKey"]) ) {
                $this->authKey = $_REQUEST["authKey"];
            };
            if( isset($_REQUEST["bindingId"]) ) {
                $this->bindingId = $_REQUEST["bindingId"];
            };
            if( isset($_REQUEST["bindingKey"]) ) {
                $this->bindingKey = $_REQUEST["bindingKey"];
            };
        } else {
            $this->authId = NULL;
            $this->authKey = NULL;
            $this->bindingId = NULL;
            $this->bindingKey = NULL;
        }
    }
    /**
     *
     * Method converts given aim address in form descripted in constructor
     * to uniform parts of URL. It returns array of three items - protocol,
     * host (eventually with portnumber) and location. For example
     * ( "http", "aim.example.com:8080", "AIM/services/R4" ).
     *
     */
    private function normalizeR4URL($URL) {
        $protocol = "http";
        $location = "AIM/services/R4";
        $parts = preg_split("/:\/\//",$URL,2);
        if( count($parts) == 2 ) {
            $protocol = $parts[0];
            $rest = $parts[1];
        } else {
            $rest = $URL;
        }
        $parts = preg_split("/\//",$rest,2);
        $host = $parts[0];
        if( count($parts) == 2 and ( strlen($parts[1]) > 0 ) ) {
            $location = $parts[1];
        }
        return array( $protocol, $host, $location );
    }
    /**
     *
     * Method stores ALUCID credentials into object properties,
     * if they are not NULL.
     *
     */
    protected function saveCredentials($authId=NULL,$authKey=NULL,$bindingId=NULL,$bindingKey=NULL) {
        if($authId != NULL) {
            $this->authId = $authId;
            $this->authKey = $authKey;
            $this->bindingId = $bindingId;
            $this->bindingKey = $bindingKey;
        }
    }
    /**
     *
     * Method starts AIM session for requested operation like "open"
     *
     * Returns authId or NULL if it fails.
     *
     */
    function requestOperation($operation, $methodName=NULL, $methodParameter=NULL, $personalObject=NULL, $AAIM2=NULL, $ilData=NULL, $peigReturnName=NULL ) {
        $reply = $this->sender->callRequestOperation(
            $this->R4,
            array(
                "operationName" => $operation,
                "AIMName"       => $this->AIMName,
                "authId"        => NULL,
                "methodName"    => $methodName,
                "methodParameter" => $methodParameter,
                "personalObject"=> $personalObject,
                "AAIM2"         => $AAIM2,
                "ilData"        => $ilData,
                "peigReturnName" => $peigReturnName
            )
        );
        $this->authId = NULL;
        $this->authKey = NULL;
        $this->bindingId = NULL;
        $this->bindingKey = NULL;
        if( isset($reply["bindingId"]) ) {
            $this->bindingId = $reply["bindingId"];
        }
        if( isset($reply["bindingKey"]) ) {
            $this->bindingKey = $reply["bindingKey"];
        }
        if( isset($reply["authId"]) ) {
            $this->saveCredentials($reply["authId"],NULL,$this->bindingId,$this->bindingKey);
        } else {
            $this->authId = NULL;
        }
        return $this->authId;
    }
    /**
     *
     * Method starts operation "open".
     *
     * Returns authId or NULL if it fails.
     *
     */
    function open($peigReturnName=NULL) {
        return $this->requestOperation("open",NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     *
     * Method starts operation "init".
     *
     * Returns authId or NULL if it fails.
     *
     */
    function init($peigReturnName=NULL) {
        return $this->requestOperation("init",NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     *
     * Method starts operation "change".
     *
     * Returns authId or NULL if it fails.
     *
     */
    function change($peigReturnName=NULL) {
        return $this->requestOperation("change",NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     *
     * Method starts operation "rechange".
     *
     * Returns authId or NULL if it fails.
     *
     */
    function rechange($peigReturnName=NULL) {
        return $this->requestOperation("rechange",NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     *
     * Method starts operation "delete".
     *
     * Returns authId or NULL if it fails.
     *
     */
    function delete($peigReturnName=NULL) {
        return $this->requestOperation("delete",NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     *
     * Method closes AIM session, created earlier (for example with method open()).
     *
     * Returns true if successfully closed.
     *
     */
    function close() {
        $reply = $this->sender->callCloseSession(
            $this->R4,
            array(
                "AIMName"       => $this->AIMName,
                "authId"        => $this->authId,
                "authKey"        => $this->authKey
            )
        );
        $closedSuccessfully = ( $reply["statusAIM"] == "end" ) and ( $reply["statusAuth"] == "OK" );
        if( $closedSuccessfully ) {
            $this->authId = NULL;
            $this->authKey = NULL;
        }
        return $closedSuccessfully;
    }
    /**
     *
     * Method starts operation with Directory Personal Object, like reading personal
     * attributes.
     *
     */
    function callDPO($method, $personalObject=NULL, $authId=NULL, $authKey=NULL) {
        $this->saveCredentials($authId,$authKey,$this->bindingId,$this->bindingKey);
        return $this->sender->callExecutePersonalObject(
            $this->R4,
            array(
                "authId" => $this->authId,
                "authKey" => $this->authKey,
                "AIMName" => $this->AIMName,
                "methodName" => $method,
                "personalObject" => $personalObject
            )
        );
    }
    /**
     *
     * Method returns an array with results or status. The content
     * of the array depends on $attributeSetName and status of the
     * current operation.
     *
     */
    public function getResult($attributeSetName=AlucidPSLAttributesSet::ALL,$authId=NULL,$authKey=NULL,$bindingId=NULL) {
        $this->saveCredentials($authId,$authKey,$bindingId,$this->bindingKey);
        if( $bindingId != NULL ) { $this->bindingId = $bindingId; }
        return $this->sender->callGetPSLAttributes(
                    $this->R4,
                    array(
                        "authId"  => $this->authId,
                        "bindingId" => $this->bindingId,
                        "AIMName" => $this->AIMName,
                        "authKey" => $this->authKey,
                        "attributeSetName" => $attributeSetName
                    )
                );
    }
    /**
     *
     * Method returns URL of current page. It tries detect ballancer and
     * provide right address wisible in browser.
     *
     */
    private function currentURL() {
        if( isset($_SERVER["HTTP_X_FORWARDED_HOST"]) ) {
            //we are begind reverse proxy
            $proto = "http";
            if( isset($_SERVER["Front-End-Https"]) and ($_SERVER["Front-End-Https"] == "on" ) ) { $proto = "https"; }
            if( isset($_SERVER["X-Forwarded-Proto"]) and ($_SERVER["X-Forwarded-Proto"] == "https" ) ) { $proto = "https"; }
            return $proto."://".$_SERVER["HTTP_X_FORWARDED_HOST"].$_SERVER["REQUEST_URI"];
        }
        $proto = "http";
        if( isset($_SERVER["HTTPS"]) && ( $_SERVER["HTTPS"] == "on" ) ) { $proto = "https"; }
        return $proto."://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
    }
    /**
     *
     * Method returns URL of AIM proxy. It expects AIM proxy installend on AIM server.
     * Protocol for communication with AIM-proxy should be http.
     *
     */
    private function AIMProxyURL() {
        return "http://". $this->AIM . "/AIM-proxy/";
    }
    /**
     *
     * Method starts transfer of authId to PEIG.
     *
     */
    function invokePeig($method = "REDIRECT", $parameters=NULL, $authId=NULL, $bindingId=NULL, $bindingKey=NULL) {
        if( $authId == NULL )     { $authId = $this->authId; }
        if( $bindingId == NULL )  { $bindingId = $this->bindingId; }
        if( $bindingKey == NULL ) { $bindingKey = $this->bindingKey; }
        if( $authId == NULL ) { throw new Exception("authId must be specified"); }
        if( $method == "REDIRECT" ) {
            //$returnURL = isset($parameters["returnURL"]) ? $parameters["returnURL"] : $this->currentURL();
            $AIMProxy  = isset($parameters["AIMProxy"])  ? $parameters["AIMProxy"]  : $this->AIMProxyURL();
            header(
                'Location: ' . $AIMProxy . "process?" .
                "authId=" . urlencode($this->authId) .
                ( ($bindingId != NULL) ? "&bindingId=". urlencode($bindingId) : "" ) .
                ( ($bindingKey != NULL) ? "&bindingKey=".urlencode($bindingKey) : "" )
                /* "&ReturnUrl=" . urlencode($returnURL) */
            );
            header('HTTP/1.0 302 Moved temporarily');
            exit;
        }
        throw new Exception("Method " . $method . " not implemented");
    }
    /**
     *
     * Method returns user attributes. This method simplified
     * calling callDPO for READ operation.
     *
     */
    function getAttributes($attributeSet="default") {
        $response = $this->callDPO(
            AlucidPersonalObjectMethod::READ,
            array(
                "personalObjectName" => $attributeSet
            )
        );
        if( isset($response["personalObject"]) ) {
            return $response["personalObject"];
        }
        return NULL;
    }
    /**
     *
     * Method writes user attributes. This method simplified
     * calling callDPO for WRITE operation.
     *
     */
    function setAttributes($attributeSet,$attributes) {
        $response = $this->callDPO(
            AlucidPersonalObjectMethod::WRITE,
            array(
                "personalObjectName" => $attributeSet,
                "personalObject" => $attributes
            )
        );
        return ( $response["statusAIM"] == "active" ) and ( $response["statusAuth"] == "OK" );
    }
    /**
     *
     * Method returns userDatabaseIndex.
     *
     */
    function getUserDatabaseIndex() {
        $result = $this->getResult(AlucidPSLAttributesSet::ALL);
        return isset($result["userDatabaseIndex"]) ? $result["userDatabaseIndex"] : NULL;
    }
}


/**
 *
 * AlucidSessionClient extends AlucidClient of few functionalities.
 * <li> handling authKey2
 * <li> using session for autofilling authId and authKey
 * <li> caching getResult replies
 */
class AlucidSessionClient extends AlucidClient {
    private $cache;
    private $sessionPrefix;

    /**
     *
     * Parameter $AIM is address of aim server. It can be DNS name, IP address or URL.
     * Valid values are for example "aim.example.com", "10.0.0.42:8080" or
     * "https://aim.example.com:8443/AIM/services/R4".
     *
     * Parameters authId and authKey are ALUCID credentials. If they are NULL, constructor
     * attempts to fill them from $_REQUEST["authId"] and $_REQUEST["authKey"]. If they are
     * not set in $_REQUEST, $_SESSION["alucidAuthId"] and $_SESSION["alucidAuthKey"].
     *
     * Parameter $AIMName is name of virtual AIM.
     *
     */
    function __construct($AIM,$sessionPrefix=NULL,$authId=NULL,$authKey=NULL,$bindingId=NULL,$bindingKey=NULL,$AIMName=NULL) {
        isset($_SESSION) or session_start();
        parent::__construct($AIM,$authId,$authKey,$bindingId,$bindingKey,$AIMName);
        $this->authId = $authId;
        $this->authKey = $authKey;
        $this->bindingId = $bindingId;
        $this->bindingKey = $bindingKey;
        $this->cleanCache();
        $this->sessionPrefix = ( $sessionPrefix == NULL || $sessionPrefix == "" ) ? "alucid" : $sessionPrefix ;
        if( isset($_SESSION[ $this->sessionPrefix . "AuthId"] ) ) {
            // we have something in session
            if( ( $this->authId == NULL ) or ( $this->authId == $_SESSION[$this->sessionPrefix . "AuthId"] ) ) {
                // authId is not set or it is set to the same value as it is in session
                // let's read session
                $this->authId = $_SESSION[$this->sessionPrefix ."AuthId"];
                $this->authKey = (isset($_SESSION[$this->sessionPrefix ."AuthKey"]) ? $_SESSION[$this->sessionPrefix ."AuthKey"] : $this->authKey);
                $this->bindingId = (isset($_SESSION[$this->sessionPrefix ."BindingId"]) ? $_SESSION[$this->sessionPrefix ."BindingId"] : $this->bindingId);
                $this->bindingKey = (isset($_SESSION[$this->sessionPrefix ."BindingKey"]) ? $_SESSION[$this->sessionPrefix ."BindingKey"] : $this->bindingKey);
            }
        }
        $this->saveCredentials($this->authId,$this->authKey,$this->bindingId,$this->bindingKey);
    }
    function setFromRequest() {
        parent::setFromRequest();
        $this->saveCredentials($this->authId,$this->authKey,$this->bindingId,$this->bindingKey);
    }
    /**
     *
     * Method stores ALUCID credentials into object properties and $_SESSION,
     * if they are not NULL.
     *
     */
    protected function saveCredentials($authId=NULL,$authKey=NULL,$bindingId=NULL,$bindingKey=NULL) {
        if($authId != NULL) {
    	    //error_log("authid: " . var_export($authId,true));
            $this->authId = $authId;
            $this->authKey = $authKey;
            $this->bindingId = $bindingId;
            $this->bindingKey = $bindingKey;
            $_SESSION[ $this->sessionPrefix ."AuthId"] = $authId;
            if( $authKey == NULL ) {
                if(isset($_SESSION[$this->sessionPrefix ."AuthKey"])) { unset($_SESSION[$this->sessionPrefix ."AuthKey"]); }
            } else {
                $_SESSION[$this->sessionPrefix ."AuthKey"] = $authKey;
            }
            if( $bindingId == NULL ) {
                if(isset($_SESSION[$this->sessionPrefix ."BindingId"])) { unset($_SESSION[$this->sessionPrefix ."BindingId"]); }
            } else {
                $_SESSION[$this->sessionPrefix ."BindingId"] = $bindingId;
            }
            if( $bindingKey == NULL ) {
                if(isset($_SESSION[$this->sessionPrefix ."BindingKey"])) { unset($_SESSION[$this->sessionPrefix ."BindingKey"]); }
            } else {
                $_SESSION[$this->sessionPrefix ."BindingKey"] = $bindingKey;
            }
        }
    }
    /**
     *
     * Method closes AIM session, created earlier (for example with method open())
     * and deletes credentials from $_SESSION.
     *
     * Returns true if successfully closed.
     *
     */
    function close() {
        $result = parent::close();
        if(isset($_SESSION[$this->sessionPrefix ."AuthId"]) )  { unset($_SESSION[$this->sessionPrefix ."AuthId"]); }
        if(isset($_SESSION[$this->sessionPrefix ."AuthKey"]) ) { unset($_SESSION[$this->sessionPrefix ."AuthKey"]); }
        if(isset($_SESSION[$this->sessionPrefix ."BindingId"]) )  { unset($_SESSION[$this->sessionPrefix ."BindingId"]); }
        if(isset($_SESSION[$this->sessionPrefix ."BindingKey"]) ) { unset($_SESSION[$this->sessionPrefix ."BindingKey"]); }
        return $result;
    }
    /**
     *
     * Method returns an array with results or status. The content
     * of the array depends on $attributeSetName and status of the
     * current operation. Result is cached for next call;
     *
     */
    function getResult($attributeSetName=AlucidPSLAttributesSet::ALL,$authId=NULL,$authKey=NULL,$bindingId=NULL) {
        $this->saveCredentials($authId,$authKey,$bindingId,$this->bindingKey);
        if($this->authId == NULL) { return NULL; }
        if($bindingId == NULL) { $bindingId = $this->bindingId; }
        if( ! isset( $this->cache[$attributeSetName] ) ) {
            $result = parent::getResult($attributeSetName, $authId, $authKey, $bindingId );
            if( $result == NULL ) { return NULL; }
            if( gettype($result) != "array" ) {
                //echo "getResult " .$attributeSetName . "*" .$authId."*".$authKey ."*<br>";
                //echo gettype($result);
                //var_dump($result);
                return NULL;
            }
            //echo "result " . gettype($result)." ".$result."<br>";
            if( isset($result["authKey2"]) ) {
                $this->saveCredentials($this->authId,$result["authKey2"],$this->bindingId,$this->bindingKey);
                unset($result["authKey2"]);
            }
            $this->cache[$attributeSetName] = $result;
        }
        return $this->cache[$attributeSetName];
    }
    /**
     *
     * Method returns true, if authentication has been successfull.
     *
     */
    function verify() {
        $result = $this->getResult(AlucidPSLAttributesSet::ALL);
        if( isset($result["statusAuth"]) and isset($result["statusAIM"]) ) {
            if( $result["statusAuth"] == "OK" and $result["statusAIM"] == "active" ) {
                return true;
            }
        }
        return false;
    }
    /**
     *
     * Method cleans the cache of getResult calls
     *
     */
    function cleanCache() {
        $cache = array();
    }
}
?>

