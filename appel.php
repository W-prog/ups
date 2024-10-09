<?php 
include('ups.class.php');
echo "<h1>Oauth client credentials (si 1 seul client UPS)</h1>";

echo '<h1>createToken</h1>';
$responseToken = createToken();
$responseToken  = json_decode($responseToken);
$_SESSION['token'] = $responseToken->access_token;
$token_type = $responseToken->token_type;
$issued_at = $responseToken->issued_at;
$client_id = $responseToken->client_id;
$expires_in = $responseToken->expires_in;
$status = $responseToken->status;

echo "token : ".$_SESSION['token']."<hr />";

if (!empty($_SESSION['token'])){
    
    echo '<h1>shipmentRequest</h1>';
    $result = shipmentRequest();
    $result = json_decode($result);
    print_r($result);
    
    echo '<h1>getAccessPoint</h1>';
    $result = getAccessPoint();
    
    $result = json_decode($result);
    print_r($result);
}

?>