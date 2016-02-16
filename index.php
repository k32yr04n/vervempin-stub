<?php

$xmlUrl = "/api/v1/kmsg/stub";

$path = getCurrentUri();

if($path == $xmlUrl)
{
	return doKmsg();
}

/*
else if(false)
{
	return "";
}
*/

else
{
	return fault("10001","Invalid Endpoint");
}



function get_request_json_body(){
	$input = file_get_contents("php://input");
	$input = json_decode($input, TRUE);
	return $input;
}

function get_request_xml_body(){

	$input = file_get_contents("php://input");
	
	//$xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
	//$json = json_encode($xml);
	//$array = json_decode($json,TRUE);
	
	$xml = simplexml_load_string( $input , null , LIBXML_NOCDATA );
	$json = json_encode($xml);
	$array = json_decode($json,TRUE);

	Header('Content-type: text/xml');
	
	return $array;
}

function fault($code, $msg){
	Header('Content-type: text/xml');
	//header("HTTP/1.1 200 OK");
	http_response_code(401);
	$fault=<<<XML
		<Fault>
			<FaultCode>$code</FaultCode>
			<FaultString>$msg</FaultString>
		</Fault>
XML;

	$xml = new SimpleXMLElement($fault);
	echo $xml->asXML();
	exit();
}

function getCurrentUri()
{
	$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
	$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
	if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
	$uri = '/' . trim($uri, '/');
	return $uri;

	/*
	 *  $base_uri = getCurrentUri();
		$routes = array();
		$routes = explode('/', $base_url);
		foreach($routes as $route)
		{
			if(trim($route) != '')
				array_push($routes, $route);
		}
	*/
}

function doKmsg(){

	Header('Content-type: text/xml');
	http_response_code(200);

	$kmsg = <<<XML
		<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
			<S:Body>
				<ns2:KMsgResponse xmlns:ns2="http://payphone.services">
					<return>
						<ResponseCode>00</ResponseCode>
						<ResponseMessage>Transaction was processed successfully.</ResponseMessage>
						<TransactionId>2385285</TransactionId>
						<ActivationCode/>
						<AddInfo>
							<RechargeInfo>
								<RechargePinInfo>testing 123 testing 1238888888888888888888888888888888888888888888</RechargePinInfo></RechargeInfo></AddInfo><PaymentMethodUploadStatus/></return></ns2:KMsgResponse></S:Body></S:Envelope>

XML;

$xml = new SimpleXMLElement($kmsg);
echo $xml->asXML();
exit();

}
