<?php 
include('ups.class.php');

echo "<h1>Oauth Auth Code (si plusieurs client UPS)</h1>";

echo '<h1>autorizeClient</h1>';

echo '<div>https://wwwcie.ups.com/security/v1/oauth/authorize?client_id='.$clientId.'&redirect_uri='.$redirectUrl.'&response_type=code&state={state}&scope={scope} </div>';
$responseAutorizeClient = autorizeClient();
print_r($responseAutorizeClient);
/*
echo '<h1>generateToken</h1>';
$responseGenerateToken = generateToken();
print_r($responseGenerateToken);
// $_SESSION['token'] = $responseGenerateToken->access_token;
// echo "token : ".$_SESSION['token']."<hr />";
/*
echo '<h1>refreshToken</h1>';
$responseRefreshToken = refreshToken();
print_r($responseToken);
*/

if (!empty($_SESSION['token'])){
    /*
    echo '<h1>shipmentRequest</h1>';
    $result = shipmentRequest();
    $result = json_decode($result);
    print_r($result);
    */
    echo '<h1>getAccessPoint</h1>';
    $result = getAccessPoint();
    
    $result = json_decode($result);
    print_r($result);
}

?>