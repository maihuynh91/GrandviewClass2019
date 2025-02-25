<?php
function syncMailchimp($data) {
    $apiKey = MAILCHIMP_APIKEY_DEFAULT;
    $listId = MAILCHIMP_AUDIENCE_ID;
    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        'ip_signup'     => $ip_address,
        'merge_fields'  => [
            'FNAME'     => $data['firstname'],
            'LNAME'     => $data['lastname']
        ]
    ]);
    $httpCode = null;
    $httpCode = executeRequest($url, $json); echo("<pre>Result: ");print_r($httpCode);echo("</pre>");
        
    echo "request was executed, returned code:";
    var_dump ($httpCode);
    return $httpCode;
}
 function executeRequest($url, $json){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . MAILCHIMP_APIKEY_DEFAULT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
 }
 function printRequestInfo($url, $json){
    echo "<pre>";
    print_r($url);
    echo "</pre>";
    echo "<br/>\n";
    echo "<pre>";
    print_r($json);
    echo "</pre>"; 
}
