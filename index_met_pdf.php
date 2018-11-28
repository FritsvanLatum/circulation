<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();

require './config/config.php';
require './OCLC/Auth/WSKey.php';
require './OCLC/User.php';

//TODO functions in een apart bestand

function get_auth_header($config) {
  $authorizationHeader = '';
	if (array_key_exists('wskey',$config) && array_key_exists('secret',$config)) {
		$options = array();
		if (array_key_exists('institution',$config) && array_key_exists('ppid',$config) && array_key_exists('ppid_namespace',$config)) {
			//uses OCLC provided programming to get an autorization header
			$user = new User($config['institution'], $config['ppid'], $config['ppid_namespace']);
			$options['user'] = $user;
		}
		$wskey = new WSKey($config['wskey'], $config['secret'], $options);
		$authorizationHeader = $wskey->getHMACSignature($config['auth_method'], $config['auth_url'], $options);
		//check??
		$authorizationHeader = 'Authorization: '.$authorizationHeader;
	}
	return $authorizationHeader;
}

function API_request($config) {
  $authorizationHeader = get_auth_header($config);
	array_push($config['auth_headers'],$authorizationHeader);

	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $config['url']);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $config['auth_headers']);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($curl, CURLOPT_, );
	//curl_setopt($curl, CURLOPT_, );

	$result = curl_exec($curl);
	$error_number = curl_errno($curl);
	//echo $error_number;
	if ($error_number) {
		$result = "Error: ".$error_number.": ".curl_error($curl)."\n".$result;
	}
	curl_close($curl);
	return $result;
}

//add authorization header to the headers in config
$json_result = API_request($config);
$result = json_decode ($json_result, TRUE);

?>
<?php
foreach($result["entries"] as $entry){
	$titel="<h2>". $entry["title"]."</h2>";
	$barcode="<h3>". $entry["content"]["pieceDesignation"]."</h3>";
	$signatuur="<h3>".$entry["content"]["callNumber"]["description"]."</h3><br>";
	$lenernaam="<h3>".$entry["content"]["patronName"]."</h3>";
	$aanvraagdatum="<h4>".$entry["content"]["requestDate"]."</h4><br><br><br>";
	$aanvraagId=$entry["content"]["requestId"];
	$logo0="<img src=./vogelaar1.jpg width='15mm'>";

	$mpdf->WriteHTML($logo0);
	$mpdf->WriteHTML($titel);
	$mpdf->WriteHTML($barcode);
	$mpdf->WriteHTML($signatuur);
	$mpdf->WriteHTML($lenernaam);
	$mpdf->WriteHTML($aanvraagdatum);

	$logo1="<img src=./vogelaar1.jpg width='15mm' align='right'>";
	
	$mpdf->WriteHTML($logo1);
	$mpdf->WriteHTML($titel);
	$mpdf->WriteHTML($barcode);
	$mpdf->WriteHTML($signatuur);
	$mpdf->WriteHTML($lenernaam);
	$mpdf->WriteHTML($aanvraagdatum);

	$mpdf->Output();
	$pdf_filenaam=$aanvraagId.".pdf";

	$mpdf->Output($pdf_filenaam);
$mpdf->AddPage();
$mpdf->WriteHTML('hallo');
$mpdf->Output();
}

?>
