<?php 
/**
 * oAuth
 * Requires libcurl
 * // TODO fonction + classe
 */
include('config.php');


// const version = "v2403"; //https://developer.ups.com/api/reference/#tag/Shipping
const version = "v2";
/***  reqOption : Indicates the type of request. 
 * Valid values: 
 * 1-Locations (Drop Locations and Will call locations) 
 * 8-All available Additional Services 
 * 16-All available Program Types 
 * 24-All available Additional Services and Program types 
 * 32-All available Retail Locations 
 * 40-All available Retail Locations and Additional Services 
 * 48-All available Retail Locations and Program Types 
 * 56-All available Retail Locations, Additional Services and Program Types 
 * 64-Search for UPS Access Point Locations.
 * */
const reqOption = "8";

function getAccessPoint(){
	
	global $token, $urlUps, $accountNumber;
	
	$token = $_SESSION['token'];
	echo "getAccessPoint token : ".$token."<hr />";

	$query = array(
		"Locale" => "fr_FR"
	);

	$curl = curl_init();

	$payload = array(
		"LocatorRequest" => array(
			"Request" => array(
				/*
				"TransactionReference" => array(
					"CustomerContext" => ""
				),
				*/
				"RequestAction" => "Locator",
				"RequestOption" => "64"
			),
			"OriginAddress" => array(
				"AddressKeyFormat" => array(
					/*
					"AddressLine" => "414 boulevard Joseph Collomp",
					"PoliticalDivision2" => "",
					"PoliticalDivision1" => "",
					"PostcodePrimaryLow" => "83300",
					"PostcodeExtendedLow" => "",
					*/
					"CountryCode" => "FR",
					"singleLineAddress" => "75006 PARIS"
				),
				/*"MaximumListSize" => "10"*/
			),
			"Translate" => array(
				/*"LanguageCode" => "FRA", // "ENG"*/
				"Locale" => "fr_FR" // "en_US"
			),
			"UnitOfMeasurement" => array(
				"Code" => "KM"
			),
			"LocationSearchCriteria" => array(
				"AccessPointSearch"=> array(
					"AccessPointStatus"=> "01",
					"MaximumListSize"=> "10",
					"SearchRadius"=> "50"
				   
				),
				/*
				"SearchOption" => array(
					array(
						"OptionType" => array(
							"Code" => "01"
						),
						"OptionCode" => array(
							"Code" => "001"
						),
						"Relation" => array(
							"Code" => "01"
						)
					),
					array(
						"OptionType" => array(
							"Code" => "01"
						),
						"OptionCode" => array(
							array(
							"Code" => "001"
							),
							array(
							"Code" => "001"
							)
						),
						"Relation" => array(
							"Code" => "01"
						)
					)
				),
				"MaximumListSize" => "10",
				"SearchRadius" => "75",
				"ServiceSearch" => array(
					"Time" => "1030",
					"ServiceCode" => array(
					"Code" => "01"
					)
				)
				*/
			)
			/*
			,"SortCriteria" => array(
				"SortType" => "01"
			)
			*/
		)
	);

	curl_setopt_array($curl, [
		CURLOPT_HTTPHEADER => [
			"Authorization: Bearer <$token>",
			"Content-Type: application/json",
			"transId: 666",
			"transactionSrc: testing"
		],
		CURLOPT_POSTFIELDS => json_encode($payload),
		CURLOPT_URL => $urlUps."/api/locations/" . version . "/search/availabilities/" . reqOption . "?" . http_build_query($query),
		/*CURLOPT_URL=> "https://onlinetools.ups.com/api/locations/" . version . "/search/availabilities/" . reqOption,*/
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
	]);

	$response = curl_exec($curl);
	$error = curl_error($curl);

	curl_close($curl);

	if ($error) {
		echo "cURL Error #:" . $error;
	} else {
		return $response;
	}
}

/*** Récupère les points relais à partir d'une adresse donnée :  C'est n'importe quoi !!! */
function shipmentRequest(){
	
	global $token, $accountNumber, $urlUps;

	$token = $_SESSION['token'];

	$query = array(
		"additionaladdressvalidation" => "string"
	);

	$curl = curl_init();

	$payload = array(
		"ShipmentRequest" => array(
			"Request" => array(
				/*"SubVersion" => "1801",*/
				"RequestOption" => "non validate",
				/*
				"TransactionReference" => array(
					"CustomerContext" => ""
				)
				*/
			),
			"Shipment" => array(
				"Description" => "Livraison UPs en point relais test",
				"Shipper" => array(
					"Name" => "So Folk",
					"AttentionName" => "Renovation du cuir",
					/*"TaxIdentificationNumber" => "FR96828401950",*/
					"Phone" => array(
						"Number" => "0557346972",
						/*"Extension" => " "*/
					),
					"ShipperNumber" => $accountNumber,
					/*"FaxNumber" => "",*/
					"Address" => array(
						"AddressLine" => array(
							"13 route de Bordeaux", 
							"7 Lot. Les Vignes"
						),
						"City" => "CENAC",
						/*"StateProvinceCode" => "",*/
						"PostalCode" => "33360",
						"CountryCode" => "FR"
					)
				),
				"ShipTo" => array(
					"Name" => "Swendra Willy",
					"AttentionName" => "Swendra Willy",
					"Phone" => array(
						"Number" => "0625275419"
					),
					"Address" => array(
						"AddressLine" => array(
							"414, boulevard Joseph Collomp", 
							"Pavillon 11"
						),
						"City" => "Draguignan",
						/*"StateProvinceCode" => "",*/
						"PostalCode" => "83300",
						"CountryCode" => "FR"
					),
					"Residential" => " "
				),
				"ShipFrom" => array(
					"Name" => "So Folk",
					"AttentionName" => "Rénovation du cuir",
						"Phone" => array(
						"Number" => "0557346972"
					),
					"FaxNumber" => "",
					"Address" => array(
						"AddressLine" => array(
							"13 route de Bordeaux" // 7 Lot. Les Vignes
						),
					"City" => "CENAC",
					"StateProvinceCode" => "",
					"PostalCode" => "33360",
					"CountryCode" => "FR"
					)
				),
				/*
				"AlternateDeliveryAddress"=> array (
					"Name"=> "PRUNELLE OPTIQUE",
						"AttentionName"=> "PRUNELLE OPTIQUE",
						"UPSAccessPointID">= "U70514633",
						"Address"=> array(
							"AddressLine"=> [
								"46 RUE DE NAPLES",
								""
							],
							"City"=> "PARIS",
							"StateProvinceCode"=> "",
							"PostalCode"=> "75008",
							"CountryCode"=> "FR"
						)
				),
				*/
				/*
				"ShipmentIndicationType"=> [
					array(
						"Code"=> "01"
					)
				],
				*/
				"PaymentInformation" => array(
					"ShipmentCharge" => array(
						"Type" => "01",
						"BillShipper" => array(
							"AccountNumber" => $accountNumber
						)
					)
				),
				"Service" => array(
					"Code" => "11",
					"Description" => "UPS Standard"
				),
				"Package" => array(
					"Description" => " ",
					"Packaging" => array(
						"Code" => "02",
						"Description" => "Cuir"
					),
					"Dimensions" => array(
						"UnitOfMeasurement" => array(
							"Code" => "CM",
							"Description" => "Centimètres"
						),
						"Length" => "10",
						"Width" => "30",
						"Height" => "45"
					),
					"PackageWeight" => array(
						"UnitOfMeasurement" => array(
							"Code" => "KGS",
							"Description" => "KILOS"
						),
					"Weight" => "5"
					)
				),
				/*
				"ShipmentServiceOptions"=> array(
					"Notification"=> [
						array(
							"NotificationCode"=> "6",
							"EMail"=> array(
								"EMailAddress"=> [
										"test@ups.com"
								]
							)
						),
						array(
							"NotificationCode"=> "012",
							"EMail"=> array(
								"EMailAddress"=> [
									"test@ups.com"
								]
								),
							"Locale"=> array(
								"Language"=> "FRA",
								"Dialect"=> "97"
							)
						)
					]
				)
				*/
			),
			"LabelSpecification" => array(
				"LabelImageFormat" => array(
					"Code" => "GIF",
					"Description" => "GIF"
				),
				"HTTPUserAgent" => "Mozilla/4.5"
			)
		)
	);

	curl_setopt_array($curl, [
		CURLOPT_HTTPHEADER => [
			"Authorization: Bearer $token",
			"Content-Type: application/json",
			"transId: string",
			"transactionSrc: testing"
		],
		CURLOPT_POSTFIELDS => json_encode($payload),
		CURLOPT_URL => $urlUps."/api/shipments/" . version . "/ship?" . http_build_query($query),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
	]);

	$response = curl_exec($curl);
	$error = curl_error($curl);

	curl_close($curl);

	if ($error) {
		return "cURL Error #:" . $error;
	} else {
		return $response;
	}
}

/*** oAuth client credentials : Méthode si on ne gère qu'un compte client dans son compte devleoper UPS */
/*** Créé le token d'autorisation */
function createToken(){

	global $urlUps;
	global $clientId , $clientSecret;
	global $userName, $passWord;

	$curl = curl_init();

	$payload = "grant_type=client_credentials";

	curl_setopt_array($curl, [
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded",
			"x-merchant-id: $clientId",
			"Authorization: Basic " . base64_encode($clientId.":".$clientSecret)
		],
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_URL => $urlUps."/security/v1/oauth/token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
	]);

	$response = curl_exec($curl);
	$error = curl_error($curl);

	curl_close($curl);

	if ($error) {
		return "cURL Error #:" . $error;
	} else {
		return $response;
	}
}

/*** oAuth Auth Code : Méthode si on souhaite géré plusieurs compte client dans son compte devleoper UPS */
function autorizeClient(){
	
	global $urlUps, $clientId, $redirectUrl;

	$query = array(
		"client_id" => $clientId,
		"redirect_uri" => $redirectUrl,
		"response_type" => "code"
		/*
		,"state" => "string",
		"scope" => "string",
		"code_challenge" => "string"
		*/
	);
	
	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL => $urlUps."/security/v1/oauth/authorize?" . http_build_query($query),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "GET",
	]);
	
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	if ($error) {
		echo "cURL Error #:" . $error;
	} else {
		return $response;
	}
}


function generateToken(){


	global $clientId, $clientSecret, $urlUps;

	echo "clientId : ".$clientId."<br />";

	$curl = curl_init();

	$redirectUrl = "https://sofolk.w-prog.com/ups/appel.php";
	$payload = "grant_type=authorization_code&code=string&redirect_uri=".$redirectUrl."&code_verifier=string"; // code_verifier est renvoyé par la méhtode autorize à confirmer ?
	
	curl_setopt_array($curl, [
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Basic " . base64_encode($clientId.":".$clientSecret)
		],
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_URL => $urlUps."/security/v1/oauth/token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
	]);
	
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	if ($error) {
		return "cURL Error #:" . $error;
	} else {
		return $response;
	}
}


/*** Permet de rafraichir le token quand celui ci est expiré */
function refreshToken(){

	global $clientId, $clientSecret, $urlUps;
	global $token;
	$token = $_SESSION['token'];
	$curl = curl_init();

	$payload = "grant_type=refresh_token&refresh_token=".$token;

	curl_setopt_array($curl, [
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Basic " . base64_encode($clientId.":".$clientSecret)
		],
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_URL => $urlUps."/security/v1/oauth/refresh",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
	]);

	$response = curl_exec($curl);
	$error = curl_error($curl);

	curl_close($curl);

	if ($error) {
		return "cURL Error #:" . $error;
	} else {
		return $response;
	}
}