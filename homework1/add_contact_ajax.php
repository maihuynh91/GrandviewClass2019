<?php

include_once('credentials.php');
$alert_message = '';
if (
    isset($_GET['action']) && $_GET['action'] == 'register_user' &&
    !empty($_GET['email']) && !empty($_GET['fname'] && !empty($_GET['lname']))
) {

    $data = [
        'email'     => htmlspecialchars($_GET['email']),
        'status'    => 'subscribed',
        'firstname' => htmlspecialchars($_GET['fname']),
        'lastname'  => htmlspecialchars($_GET['lname'])
    ];

    $dbResultString = addContactToDb($servername, $username, $password, $dbname, $data);
    syncMailchimp($data);
} else {
    $alert_message = "This request is missing data and could not be executed";
    create_alert("error", $alert_message);
}


function syncMailchimp($data)
{
    $apiKey = MAILCHIMP_APIKEY_DEFAULT;
    $listId = MAILCHIMP_AUDIENCE_ID;

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
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
    // printRequestInfo($url, $json);
    $httpCode = executeRequest($url, $json);

    return $httpCode;
}

function executeRequest($url, $json)
{

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . MAILCHIMP_APIKEY_DEFAULT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //https://mailchimp.com/developer/guides/get-started-with-mailchimp-api-3/#Actions
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}
function printRequestInfo($url, $json)
{
    echo "<pre>";
    print_r($url);
    echo "</pre>";

    echo "<br/>\n";
    echo "<pre>";
    print_r(print_r($json));
    echo "</pre>";
}
function addContactToDb($servername, $username, $password, $dbname, $data)
{
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        $alert_message = "Connection failed: " . mysqli_connect_error();
        create_alert("error", $alert_message);
        return;
    } 
    $firstN = htmlspecialchars($data["firstname"]);
    $email = htmlspecialchars($data["email"]);
    $lastN = htmlspecialchars($data["lastname"]);
    $visit = 1;

    $sql = $conn->prepare("SELECT * FROM millionCup WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();


    if ($result->num_rows < 1) {
        //insert into db
        $stmt = $conn->prepare("INSERT INTO millionCup(firstname, lastname, email, visit) VALUES (?,?,?,?);");
        $stmt->bind_param("sssi", $firstN, $lastN, $email, $visit);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $alert_message =  "Welcome to Million Cup! You registered sucessfully.";
    } else {
        while ($row = $result->fetch_assoc()) {
            $email = $row["email"];
            $visit = $row["visit"] + 1;
            $update_visit_stmt = "UPDATE millioncup SET visit = '$visit' WHERE email = '$email'";
            if ($conn->query($update_visit_stmt) === FALSE) {
                $alert_message = "Cannot update '$update_visit_stmt'";
                create_alert("error", $alert_message);
                return;
            } else {
                $alert_message = "Welcome Back " . $email . "!";
            }
        }
    };
    $conn->close();
    $alert_message .= "<br />You have visited $visit times";
    create_alert("success",$alert_message);
}
function printLine($line_to_print)
{
    echo "<pre>";
    print_r($line_to_print);
    echo "</pre>";
    echo "<br/>\n";
}
function create_alert($type, $message)
{
    $json = json_encode([
        'type' => $type,
        'text' => $message
        ]);

    echo $json;
}
