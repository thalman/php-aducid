<?php
/* 
 * Copyright(c) 2012 ANECT a.s.
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

include_once "aducidenums.php";

/**
 * \brief Function returns sdk level.
 *
 * Function returns the PHP SDK version. Number before the decimal point
 * represents major changes in API without backward compatibility.
 *
 * Minor version (after decimal point) shows the API "features".
 */
function aducidVersion() {
    return 3.000;
}

/**
 * \brief Function checks SDK level
 *
 * This function check the requested API level. It throws an exception if
 * requested API level is not sufficient. For example if installed version
 * is 2.051 and requested API level is:
 *   - 1.0 - exception API 2.x is not compatible with 1.x
 *   - 3.0 - exception API 2.x is not compatible with 3.x
 *   - 2.03 - ok 2.051 >= 2.03
 */
function aducidRequire($apilevel) {
    if( floor($apilevel) != floor(aducidVersion()) ) {
        // Major version of API is different
        throw new Exception(
            'Wrong major ADUCID API version (requested '.$apilevel.', installed '.aducidVersion().').'
        );
    }
    // major is the same, lets compare
    if( $apilevel > aducidVersion() ) {
        throw new Exception(
            'Wrong minor ADUCID API version (requested '.$apilevel.', installed '.aducidVersion().').'
        );
    }
    return true;
}

/**
 * \brief ADUCID R4 soap client
 *
 * AducidMessageSender implements methods for accessing R4 interface.
 * It is used by AducidClient and AducidSessionClient.
 */
class AducidMessageSender {
    /**
     * \brief SOAP request for getting PSL attributes
     *
     * \param R4URL - URL for R4 interface on AIM server
     * \param request - array with different parameters
     * \return an array with PSL attributes
     *
     * See ADUCID general documentation for deailed explanation.
     * Supported request parameters are:
     *   - authId
     *   - authKey
     *   - bindingId
     *   - AIMName
     *   - attributeSetName
     */
    function callGetPSLAttributes($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.aducid.com",
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
     * \brief Starts operation on AIM server.
     *
     * \param R4URL - URL for R4 interface on AIM server.
     * \param request - array with different parameters.
     * \return an array with newly created AIM session attributes.
     *
     * See ADUCID general documentation for deailed explanation.
     * Supported request parameters are:
     *   - operationName
     *   - authId
     *   - bindingKey
     *   - methodName
     *   - methodParameter
     *   - personalObject
     *   - peigReturnName
     *   - AAIM2
     *   - ilData
     *   - AIMName
     */
    function callRequestOperation($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.aducid.com",
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
     * \brief Closes the AIM session
     *
     * See ADUCID general documentation for deailed explanation.
     * Supported request parameters are:
     *   - authId
     *   - AIMName
     *   - authKey
     */
    function callCloseSession($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.aducid.com",
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
     * \brief Returns name of the DOM node.
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
     * \brief Returns value of the DOM node.
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
     * \brief Parses XML reply and returns personal object as an array.
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
    /**
     * \brief Creates xml string for methodParameter in SOAP request.
     */
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
    /**
     * \brief Creates xml string for with personalObject for SOAP request.
     */
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
     * \brief Implements READ operation for personalObject attributes.
     */
    private function readPersonalObject($R4URL,$request) {
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.aducid.com",
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
        array_push($params, new SoapParam(AducidMethodName::READ,"methodName"));
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
     * \brief Implements WRITE operation for personalObject attributes.
     */
    private function writePersonalObject($R4URL,$request) {
        if( ! isset($request["personalObject"]) ) {
            return NULL;
        };
        $soap = new SoapClient(NULL,
            array ( "location" => $R4URL,
                    "uri"      => "http://iface.aducid.com",
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
        array_push($params,new SoapParam(AducidMethodName::WRITE,"methodName"));

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
     * \brief Implements operation with directory object.
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
            case AducidMethodName::READ:
                return $this->readPersonalObject($R4URL,$request);
            case AducidMethodName::WRITE:
                return $this->writePersonalObject($R4URL,$request);
            default:
                throw new Exception('Method '.$method.' is not implemented');
        }
        throw new Exception('callExecutePersonalObject not implemented');
    }
}

/**
 * \brief AducidClient class implements high-level API for using ADUCID.
 *
 * AducidClient is simple object for using ADUCID. It can be used for interaction
 * between PHP web application and AIM.
 */
class AducidClient {
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
     * Parameter $AIM is an address of aim server. It can be DNS name, IP address or URL.
     * Valid values are for example "aim.example.com", "10.0.0.42:8080" or
     * "https://aim.example.com:8443/AIM/services/R4".
     *
     * Parameters authId, authKey, bindingId and bindingKey are ADUCID credentials.
     * If they are NULL, constructor attempts to fill them from $_REQUEST["authId"],
     * $_REQUEST["authKey"], $_REQUEST["bindingId"] and $_REQUEST["bindingKey"] (this
     * is useful when AIMProxy is used).
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

        $this->sender = new AducidMessageSender();

        $this->setFromRequest();

        if( $authId != NULL ) {
            $this->authId = $authId;
            $this->authKey = $authKey;
            $this->bindingId = $bindingId;
            $this->bindingKey = $bindingKey;
        }
        $this->AIMName = $AIMName;
    }
    /**
     * Method sets ADUCID credential from http request params.
     * Method is called from constructor too.
     */
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
     * Method converts given aim address in form descripted in constructor
     * to uniform parts of URL. It returns array of three items - protocol,
     * host (eventually with portnumber) and location. For example
     * ( "http", "aim.example.com:8080", "AIM/services/R4" ).
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
     * Method stores ADUCID credentials into object properties,
     * if they are not NULL.
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
                "peigReturnName" => ( $peigReturnName != NULL ? $peigReturnName : $this->currentURL() )
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
     * Method starts operation "open".
     *
     * Returns authId or NULL if it fails.
     */
    function open($peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::OPEN,NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     * Method starts operation "init".
     *
     * Returns authId or NULL if it fails.
     */
    function init($peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::INIT,NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     * Method starts operation "change".
     *
     * Returns authId or NULL if it fails.
     */
    function change($peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::CHANGE,NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     * Method starts operation "rechange".
     *
     * Returns authId or NULL if it fails.
     */
    function rechange($peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::RECHANGE,NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     * Method starts operation "delete".
     *
     * Returns authId or NULL if it fails.
     */
    function delete($peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::DELETE,NULL, NULL, NULL, NULL, NULL, $peigReturnName);
    }
    /**
     * Method starts operation "exuse".
     *
     * Returns authId or NULL if it fails.
     */
    function exuse($methodName,$methodParameters,$personalObject,$peigReturnName=NULL) {
        return $this->requestOperation(AducidOperationName::EXUSE,$methodName, $methodParameters, $personalObject, NULL, NULL, $peigReturnName);
    }
    /**
     * different exuse cases
     */
    function localFactorManagementOperation($operation, $useLocalFactor, $peigReturnURL=NULL) {
        $params = array();
        if( $useLocalFactor ) { $params["UseLocalFactor"] = "1"; }
        return $this->exuse(
            $operation,
            $params,
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL
        );
    }
    function initLocalFactor($peigReturnURL=NULL) {
        return $this->localFactorManagementOperation(AducidMethodName::INIT, true, $peigReturnURL);
    }
    function changeLocalFactor($peigReturnURL=NULL) {
        return $this->localFactorManagementOperation(AducidMethodName::CHANGE, true, $peigReturnURL);
    }
    function deleteLocalFactor($peigReturnURL=NULL) {
        return $this->localFactorManagementOperation(AducidMethodName::DELETE, true, $peigReturnURL);
    }
    function verifyLocalFactor($localFactorName,$peigReturnURL=NULL) {
        return $this->localFactorManagementOperation(AducidMethodName::VERIFY_LF, true, $peigReturnURL);
    }
    /**
     * Payment
     */
    function paymentOperation($operation,$useLocalFactor,$peigReturnURL=NULL) {
        $methodParameters = array();
        if($useLocalFactor) { $methodParameters["UseLocalFactor"] = "1"; }
        return $this->exuse(
            $operation,
            $methodParameters,
            array(
                "personalObjectName"=> "payment",
                "personalObjectTypeName"=> "payment",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PAYMENT
            ),
            $peigReturnURL
        );
    }
    function initPayment($useLocalFactor,$peigReturnURL=NULL) {
        return $this->paymentOperation(AducidMethodName::INIT,$useLocalFactor,$peigReturnURL);
    }
    function confirmTextTransaction($text,$useLocalFactor,$peigReturnURL=NULL) {
        $methodParameters = array( "PaymentMessage" => urlencode($text) );
        if($useLocalFactor) { $methodParameters["UseLocalFactor"] = "1"; }
        return $this->exuse(
            AducidMethodName::CONFIRM_TRANSACTION,
            $methodParameters,
            array(
                "personalObjectName"=> "payment",
                "personalObjectTypeName"=> "payment",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PAYMENT
            ),
            $peigReturnURL
        );
    }
    function confirmMoneyTransaction($from,$to,$amount,$useLocalFactor,$peigReturnURL=NULL) {
        $methodParameters = array(
                "PaymentAmount" => $amount,
                "PaymentFromAccount" => $from,
                "PaymentToAccount" => $to
        );
        if($useLocalFactor) { $methodParameters["UseLocalFactor"] = "1"; }
        return $this->exuse(
            AducidMethodName::CONFIRM_TRANSACTION,
            $methodParameters,
            array(
                "personalObjectName"=> "payment",
                "personalObjectTypeName"=> "payment",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PAYMENT
            ),
            $peigReturnURL
        );
    }
    function verifyTransaction() {
        $transactionResult = array( "result" => false, "Return_Status" => NULL );
        if( $this->verify() ) {
            $result = $this->getResult(AducidAttributeSetName::ALL);
            $po = $result["personalObject"];
            $poa = $po->personalObjectAttribute;
            if( $poa["Return_Status"] == "ConfirmedByUser" ) {
                $transactionResult["result"] = true;
                // is local factor OK ?
                if( isset( $poa["UseLocalFactor"] ) && ($poa["UseLocalFactor"] != "OK") ) {
                    $transactionResult["result"] = false;
                }
            }
            if( $transactionResult["result"] ) {
                foreach( array("PaymentSignature","PaymentMessage","PaymentAmount","PaymentFromAccount", "PaymentToAccount") as $key ) {
                    if( isset($poa[$key]) ) { $transactionResult[$key] = $poa[$key]; }
                }
            }
            foreach( array("Return_Status", "UseLocalFactor") as $key ) {
                if( isset($poa[$key]) ) { $transactionResult[$key] = $poa[$key]; }
            }
        }
        return $transactionResult;
    }
    /**
     * rooms
     */
    function createRoomByName($name, $peigReturnURL=NULL) {
        return $this->exuse(
            AducidMethodName::CREATE_ROOM_BY_NAME,
            array( "MeetingRoomName" => $name),
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL
        );
    }
    function enterRoomByName($name,  $peigReturnURL=NULL) {
        return $this->exuse(
            AducidMethodName::ENTER_ROOM_BY_NAME,
            array( "MeetingRoomName" => $name),
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL
        );
    }
    function createRoomByStory($peigReturnURL=NULL) {
        return $this->exuse(
            AducidMethodName::CREATE_ROOM_BY_STORY,
            NULL,
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL);
    }
    function enterRoomByStory($peigReturnURL=NULL) {
        return $this->exuse(
            AducidMethodName::ENTER_ROOM_BY_STORY,
            NULL,
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL);
    }
    /**
     * Peig Local Link
     * \param $linkType can be one of "PrimaryReplica", "SecondaryReplica" or "ConnectionUSB"
     */
    function peigLocalLink($linkType,$peigReturnURL=NULL) {
        $params = array();
        switch( strtolower($linkType) ) {
        case "primaryreplica":
            $params["PrimaryReplica"] = 1;
            break;
        case "secondaryreplica":
            $params["SecondaryReplica"] = 1;
            break;
        case "connectionusb":
            $params["Connection"] = "Usb";
            break;
        }
        if( ! count( $params ) ) { return NULL; }
        return $this->exuse(
            AducidMethodName::PEIG_LOCAL_LINK,
            $params,
            array(
                "personalObjectTypeName"=> "peigMgmt",
                "personalObjectAlgorithmName"=> AducidAlgorithmName::PEIG_MGMT
            ),
            $peigReturnURL);
    }
    /**
     * Method closes AIM session, created earlier (for example with method open()).
     *
     * Returns true if successfully closed.
     */
    function close() {
        $reply = $this->sender->callCloseSession(
            $this->R4,
            array(
                "AIMName" => $this->AIMName,
                "authId"  => $this->authId,
                "authKey" => $this->authKey
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
     * Method starts operation with Directory Personal Object, like reading personal
     * attributes.
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
     * Method returns an array with results or status. The content
     * of the array depends on $attributeSetName and status of the
     * current operation.
     */
    public function getResult($attributeSetName=AducidAttributeSetName::ALL,$authId=NULL,$authKey=NULL,$bindingId=NULL) {
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
     * Method returns URL of current page. It tries detect ballancer and
     * provide right address wisible in browser.
     */
    function currentURL() {
        $items = explode('?',$_SERVER["REQUEST_URI"],2);
        $script = $items[0];
        if( isset($_SERVER["HTTP_X_FORWARDED_HOST"]) ) {
            //we are begind reverse proxy
            $proto = "http";
            if( isset($_SERVER["Front-End-Https"]) and ($_SERVER["Front-End-Https"] == "on" ) ) { $proto = "https"; }
            if( isset($_SERVER["X-Forwarded-Proto"]) and ($_SERVER["X-Forwarded-Proto"] == "https" ) ) { $proto = "https"; }
            return $proto."://".$_SERVER["HTTP_X_FORWARDED_HOST"].$script;
        }
        $proto = "http";
        if( isset($_SERVER["HTTPS"]) && ( $_SERVER["HTTPS"] == "on" ) ) { $proto = "https"; }
        return $proto."://".$_SERVER['HTTP_HOST'].$script;
    }
    /**
     * Method returns URL of AIM proxy. It expects AIM proxy installend on AIM server.
     * Protocol for communication with AIM-proxy should be http.
     */
    private function AIMProxyURL() {
        return "http://". $this->AIM . "/AIM-proxy/";
    }
    /**
     * Method starts transfer of authId to PEIG.
     */
    function invokePeig($method = AducidTransferMethod::REDIRECT, $parameters=NULL, $authId=NULL, $bindingId=NULL, $bindingKey=NULL) {
        if( $authId == NULL )     { $authId = $this->authId; }
        if( $bindingId == NULL )  { $bindingId = $this->bindingId; }
        if( $bindingKey == NULL ) { $bindingKey = $this->bindingKey; }
        if( $authId == NULL ) { throw new Exception("authId must be specified"); }
        if( $method == AducidTransferMethod::REDIRECT ) {
            //$returnURL = isset($parameters["returnURL"]) ? $parameters["returnURL"] : $this->currentURL();
            $AIMProxy  = isset($parameters["AIMProxy"])  ? $parameters["AIMProxy"]  : $this->AIMProxyURL();
            header(
                'Location: ' . $AIMProxy . "process?" .
                "authId=" . urlencode($this->authId) .
                ( ($bindingId != NULL) ? "&bindingId=". urlencode($bindingId) : "" ) .
                ( ($bindingKey != NULL) ? "&bindingKey=".urlencode($bindingKey) : "" )
            );
            header('HTTP/1.0 302 Moved temporarily');
            exit;
        }
        throw new Exception("Method " . $method . " not implemented");
    }
    /**
     * Method returns user attributes. This method simplified
     * calling callDPO for READ operation.
     */
    function getAttributes($attributeSet="default") {
        $response = $this->callDPO(
            AducidMethodName::READ,
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
     * Method writes user attributes. This method simplified
     * calling callDPO for WRITE operation.
     */
    function setAttributes($attributeSet,$attributes) {
        $response = $this->callDPO(
            AducidMethodName::WRITE,
            array(
                "personalObjectName" => $attributeSet,
                "personalObject" => $attributes
            )
        );
        return ( $response["statusAIM"] == "active" ) and ( $response["statusAuth"] == "OK" );
    }
    /**
     * Method returns userDatabaseIndex.
     */
    function getUserDatabaseIndex() {
        $result = $this->getResult(AducidAttributeSetName::ALL);
        return isset($result["userDatabaseIndex"]) ? $result["userDatabaseIndex"] : NULL;
    }
}


/**
 *
 * AducidSessionClient extends AducidClient of few functionalities.
 *   - handling authKey2
 *   - using session for autofilling authId, authKey, bindingId and bindingKey
 *   - caching getResult replies
 */
class AducidSessionClient extends AducidClient {
    private $cache;
    private $sessionPrefix;

    /**
     *
     * Parameter $AIM is address of aim server. It can be DNS name, IP address or URL.
     * Valid values are for example "aim.example.com", "10.0.0.42:8080" or
     * "https://aim.example.com:8443/AIM/services/R4".
     *
     * Paramerer sessionPrefix is string used to distinguish between instances od AducidSessionClient.
     * In some cases you might need more instances of this object for one user (For example one
     * instance for authentication and second for validating transaction). In such situation give
     * different value to the instances. If the parameter is NULL, prefix "aducid" is used.
     *
     * Up to four items are saved in session -- AuthId, AuthKey, BindingId, BindingKey -- all with
     * given prefix.
     *
     * Parameters authId authKey, bindingId and bindingKey are ADUCID credentials.
     * If they are NULL, constructor attempts to fill them from $_SESSION.
     * Parameters are also readed from $_SESSION when given paremater authId
     * is equal to the authId stored in $_SESSION.
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
        $this->sessionPrefix = ( $sessionPrefix == NULL || $sessionPrefix == "" ) ? "aducid" : $sessionPrefix ;
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
    /**
     * \brief Sets ADUCID credentials from http request.
     *
     * AducidSessionClient doesn't set ADUCID credentials from request automatically. This
     * allows creating more independent instances of this object. If You need to set ADUCID
     * credentials from http request, You can use this method.
     *
     * Example:
     *     $ac1 = new AducidSessionClient("http://aim.example.com");
     *     $ac2 = new AducidSessionClient("http://aim.example.com","second");
     *     $ac2->setFromRequest();
     */
    function setFromRequest() {
        parent::setFromRequest();
        $this->saveCredentials($this->authId,$this->authKey,$this->bindingId,$this->bindingKey);
    }
    /**
     * \brief Save credentials into object properties and into session.
     *
     * Method stores ADUCID credentials into object properties and $_SESSION
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
     * \brief Method closes AIM session.
     *
     * Method closes AIM session, created earlier (for example with method open())
     * and deletes credentials from $_SESSION.
     *
     * \return true if successfully closed.
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
     * \brief Method checks the status of ADUCID operation.
     * \param attributeSetName - name of requested set (default is AducidAttributeSetName::ALL)
     * \param authId - if NULL previously set value is used
     * \param authKey - if NULL previously set value is used
     * \param bindingId - if NULL previously set value is used
     *
     * Method returns an array with results or status. The content
     * of the array depends on $attributeSetName and status of the
     * current operation. Result is cached for next call.
     *
     * Example:
     *     $aducid = new AducidSessionClient($GLOBALS["aim"]);
     *     $aducid->setFromRequest();
     *     $result = $aducid->getResult(AducidAttributeSetName::ALL);
     */
    function getResult($attributeSetName=AducidAttributeSetName::ALL,$authId=NULL,$authKey=NULL,$bindingId=NULL) {
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
     * \brief Checks the ADUCID authentication result.
     * \return true if successfully authenticated.
     *
     * Method returns true, if authentication has been successfull.
     *
     * Example:
     *     $aducid = new AducidSessionClient($GLOBALS["aim"]);
     *     $aducid->setFromRequest();
     *     if( $aducid->verify ) {
     *         echo "OK\n";
     *     } else {
     *         echo "FAILED\n";
     *     }
     */
    function verify() {
        $result = $this->getResult(AducidAttributeSetName::ALL);
        if( isset($result["statusAuth"]) and isset($result["statusAIM"]) ) {
            if( $result["statusAuth"] == "OK" and $result["statusAIM"] == "active" ) {
                return true;
            }
        }
        return false;
    }
    /**
     * \brief Cleans internal cache
     *
     * Method cleans the cache of getResult calls
     */
    function cleanCache() {
        $cache = array();
    }
}
?>

