<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');

$action = '';
$data = [];

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('JWT_SECRET_KEY', 'market');
define('JWT_EXPIRE_TIME', 3600);

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    header('Content-Type: application/json');
}

$action = isset($data['action']) ? $data['action'] : '';

$host ="rusiann7.helioho.st";
$user ="rusiann7_rusiann";
$password="";
$dbname="";

$conn = new mysqli($host, $user, $password, $dbname);

if($conn -> connect_error) {
    die("Connection Failed" . $conn -> connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if data is structured with feedbackData property
    if (isset($data['feedbackData'])) {
        $data = $data['feedbackData'];
    }
    // Check for reset data
    else if (isset($data['resetData'])){
        $data = $data['resetData'];
    }
    // If direct feedback data is sent
    else if (isset($data['feedback']) || isset($data['rating'])) {
        // Use the data as is
    }

    $action = isset($data['action']) ? $data['action'] : '';

    error_log("Received action: " . $action);
    error_log("Received data: " . json_encode($data));
}

$n = 10;
function getRandomString($n)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}

function verifyToken() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        return false;
    }

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);

    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}

if ($action === "feedback"){

    $feedback = $conn->real_escape_string($data['feedback']);
    $rating = (int)$data['rating'];

        if($rating >= 1){
            $sql= "INSERT INTO Feedbacks (feedback, rating)
            VALUES ('$feedback', '$rating')";

                if ($conn->query($sql) === TRUE) { //lagyan ng identifier na nag ok
                    echo json_encode(["success" => true]);
                }else {
                echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
                }

        }else{
            echo json_encode(["success" => false]);
        }

}elseif ($action === 'reset'){
        //get the incoming email
    $email = $data['email'];

    $sql = "SELECT * FROM Users WHERE Email = '$email';";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $dataReset = $result->fetch_assoc();
        $reset = $dataReset['reset'];

        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'systemmailer678@gmail.com';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('systemmailer678@gmail.com', 'Market AutoMailer');
        $mail->addAddress($email);     //Add a recipient
        $mail->addAddress($email);     //Name is optional
        $mail->addReplyTo('systemmailer678@gmail.com', 'Information');
        $mail->addCC('systemmailer678@gmail.com');
        $mail->addBCC('systemmailer678@gmail.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset';
        $mail->Body    =
        '
        <br>
        <center>
        <br>
        <h2>Enter this code to reset your password<h2>
        <h1>' . htmlspecialchars($reset, ENT_QUOTES, 'UTF-8') . '</h1>
        <br>
        <p>Do not share with anyone</p>
        </center>
        <br>
        ';

        $mail->AltBody = 'Enter this code to reset your password: ' .$reset;

        if($mail->send()){
            //echo 'Message has been sent';
            echo json_encode(["success" => true,]);
        }
        else{
            echo json_encode(["success" => false]);
        }

    }else{
        //echo error no email found
        echo json_encode(["success" => false]);
    }

}elseif ($action === "getFeedbacks") {
    
    if (!verifyToken()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit;
    }
    
    $sql = "SELECT feedback, rating FROM Feedbacks ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result) {
        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = [
                'username' => 'Anonymous User', // Default anonymous value
                'rating' => (int)$row['rating'],
                'comment' => $row['feedback'] // Map feedback to comment for Vue
            ];
        }
        echo json_encode(["success" => true, "feedbacks" => $feedbacks]);
    }else {
        echo json_encode(["success" => false, "error" => "Failed to fetch feedbacks"]);
    }

}elseif($action === 'code'){
    $email = $data['email'];
    $code = $data['code'];

    $sql = "SELECT * FROM Users WHERE Email = '$email' AND reset = '$code';";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo json_encode(["success" => true,]);
    } else{
        echo json_encode(["success" => false]);
    }

} elseif ($action === 'password'){
    $email = $data['email'];
    $password = $data['initPassword'];
    $conpassword = $data['conPassword'];

       if($password === $conpassword){

           $hash = password_hash($password, PASSWORD_DEFAULT); //hash
           $newReset = getRandomString(10);

           $sql="UPDATE Users
           SET Password = '$hash', reset = '$newReset'
           WHERE Email = '$email'
           ";

           $result = $conn->query($sql);

           if ($conn->query($sql) === TRUE) {
               echo json_encode(["success" => true]);
           } else{
               echo json_encode(["success" => false]);
           }

       } else{
        echo json_encode(["success" => false]);
       }
    
} elseif( $action === "login") {

    $emaill=$data['email'];
    $passwordl=$data['password'];

    $sql = "SELECT * FROM Users WHERE Email = '$emaill';";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $dataLogin = $result->fetch_assoc();

            if(password_verify($passwordl,$dataLogin['Password'])){ //decrypt
                
                $tokenPayload=[
                    'user_id' => $dataLogin['id'],
                    'email' => $dataLogin['Email'],
                    'iat' => time(),
                    'exp' => time() + JWT_EXPIRE_TIME
                ];
                
                $token = JWT::encode($tokenPayload, JWT_SECRET_KEY, 'HS256');

                echo json_encode([ //ito yung meta data
                    "success"=> true,
                    "message" => "Logged In",
                    "token"=> $token,
                    "userData" => [
                        "ID" => $dataLogin['id'],
                        "email" => $dataLogin['Email'],
                        "reset" => $dataLogin['reset'],
                    ]]);
                exit;
            }else{ //lagyan ng error
                echo json_encode(["success"=> false,"error" => "Password error"]);
            }
        } else {
            //error yan pag ganyan wag mo pilitin
            echo json_encode(["success"=> false,"error" => "Email not found"]);
        }

}elseif($action === 'signup'){

    if (!verifyToken()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit;
    }

    $email=$data['email'];
    $password=$data['initpassword'];
    $conpassword=$data['conpassword'];

    if($password === $conpassword){

        $hash = password_hash($password, PASSWORD_DEFAULT); //hash

        $sql = "SELECT * FROM Users Where Email = '$email';";
        $result  = $conn->query($sql);

        //sign up
        
        if ($result && $result->num_rows > 0) {
            //meron nang email na gamit
            echo json_encode(["success"=> false,"error" => "Email already in use."]);
            exit;
        } else {

            $randomString = getRandomString(10);

            $sql1 = "INSERT INTO Users (Email, Password, reset)
            VALUES ('$email', '$hash', '$randomString')";

            if ($conn->query($sql1) === TRUE) { //lagyan ng identifier na nag ok
                echo json_encode(["success" => true, "message" => "New record created successfully"]);
            } else {
            echo json_encode(["error" => "Error: " . $sql1 . "<br>" . $conn->error]);
            }
        }
    }else{
        echo json_encode(["success" => false]);
    }

}elseif ($action === 'getPrices') {
    $sql = "SELECT * FROM `Price-GOV` ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $allColumns = array_keys($row);

        $items = [];
        $sources = [];
        $prices = [];

        foreach ($allColumns as $colName) {

            if (
                preg_match('/^([a-zA-Z-]+)-(\d+|[a-zA-Z0-9]+)$/', $colName, $matches) &&
                strpos($colName, 'Source-') !== 0
            ) {
            
                $baseName = $matches[1];
                $suffix = $matches[2];
                
                if (isset($row[$colName]) && $row[$colName] !== null && $row[$colName] !== '') {
                    $items[] = [
                        'baseName' => $baseName,
                        'suffix' => $suffix,
                        'value' => is_numeric($row[$colName]) ? (float)$row[$colName] : $row[$colName]
                    ];
                }
            }

            if (preg_match('/^Source-(\d+|[a-zA-Z0-9]+)$/', $colName, $matches)) {
                $sources[$matches[1]] = $row[$colName] ?? '';
            }
        }

        foreach ($items as $item) {
            $suffix = $item['suffix'];
            $riceType = str_replace('-', ' ', $item['baseName']);
            
            if (isset($sources[$suffix])) {
                $prices[] = [
                    'RiceType' => $riceType,
                    'Price' => $item['value'],
                    'Source' => $sources[$suffix],
                ];
            }
        }
        if (!empty($prices)) {
            echo json_encode(["success" => true, "data" => $prices]);
        } else {
            echo json_encode(["success" => false, "error" => "No valid price data found"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]); // Added specific error message
    }

}elseif ($action === 'fetchPrices'){
    $serp_api_key = '';
    $cache_time = 3600; // 1 hour cache

    $riceTypes = [
        'regular_milled' => 'Regular Milled Rice',
        'well_milled' => 'Well Milled Rice',
        'premium' => 'Premium Rice'
    ];

    // Initialize price storage with source tracking
    $srpPrices = [
        'regular_milled' => ['government' => null, 'news' => null],
        'well_milled' => ['government' => null, 'news' => null],
        'premium' => ['government' => null, 'news' => null]
    ];





    function searchAPI($riceType, $siteQuery) {
        global $serp_api_key;
        
        $query = [
            "q" => "SRP of $riceType rice in the Philippines 2025 $siteQuery",
            "hl" => "en",
            "gl" => "ph",
            "google_domain" => "google.com",
            "num" => 3,
            "api_key" => $serp_api_key
        ];
        
        $url = "https://serpapi.com/search.json?" . http_build_query($query);
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    function extractLatestPrice($results) {
        if (!isset($results['organic_results'])) return null;
        
        $latest = null;
        foreach ($results['organic_results'] as $result) {
            if (preg_match('/(?:PHP|₱|P)\s*([\d,]+\.?\d*)/i', $result['snippet'] ?? '', $match)) {
                $price = [
                    'amount' => (float) str_replace(',', '', $match[1]),
                    'currency' => '₱',
                    'formatted' => '₱' . $match[1],
                    'date' => extractDate($result['snippet'] ?? ''),
                    'title' => $result['title'] ?? 'Unknown',
                    'link' => $result['link'] ?? '#'
                ];
                
                if (!$latest || ($price['date'] !== 'N/A' && strtotime($price['date']) > strtotime($latest['date']))) {
                    $latest = $price;
                }
            }
        }
        return $latest;
    }
    function extractDate($text) {
        $patterns = [
            '/(\d{1,2}\s+\w+\s+\d{4})/i',
            '/(\d{4}-\d{1,2}-\d{1,2})/',
            '/(\d{1,2}\/\d{1,2}\/\d{4})/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $match)) {
                return date('Y-m-d', strtotime($match[1]));
            }
        }
        return 'N/A';
    }
}elseif ($action === 'addItems') {

    $addItem = $conn->real_escape_string($data['itemName']);
    $toTable = 'Price-GOV';

    $sql = "SHOW COLUMNS FROM `Price-GOV` LIKE '$addItem'";
    $result = $conn->query($sql);

    
    if ($result && $result->num_rows > 0) {
        echo json_encode(["success"=> false,"error" => "Item already existed"]);
        exit;
    } else {

        $randomString = getRandomString(3);
        $item = $addItem . "-" . $randomString;
        $source = "Source-" . $randomString;

        try{

            $sql1 = "ALTER TABLE `Price-GOV` ADD COLUMN `$item` INT(3) NOT NULL";
            if (!$conn->query($sql1)) {
                throw new Exception("First ALTER failed: " . $conn->error);
            }

            $sql2 = "ALTER TABLE `Price-GOV` ADD COLUMN `$source` VARCHAR(65535) NOT NULL";
            if (!$conn->query($sql2)) {
                $conn->query("ALTER TABLE `Price-GOV` DROP COLUMN `$item`");
                throw new Exception("Failed to add source column: " . $conn->error);
            }

            echo json_encode(["success"=> true, "message" => "Item added successfully"]);

        }catch (Exception $e) {
            return false;
        }
    }
}elseif ($action === 'changePrice') {
    if (!verifyToken()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit;
    }


}elseif ($action === 'getCompare'){

    if (!isset($data['counter']) || !is_numeric($data['counter'])) {
        echo json_encode(["success" => false, "error" => "Invalid counter value"]);
        exit;
    }

    $id = (int)$data['counter'];

    $sql = "SELECT * FROM `Price-GOV` WHERE id='$id'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $allColumns = array_keys($row);

        $items = [];
        $sources = [];
        $compare = [];

        foreach ($allColumns as $colName) {

            if (
                preg_match('/^([a-zA-Z-]+)-(\d+|[a-zA-Z0-9]+)$/', $colName, $matches) &&
                strpos($colName, 'Source-') !== 0
            ) {
            
                $baseName = $matches[1];
                $suffix = $matches[2];
                
                if (isset($row[$colName]) && $row[$colName] !== null && $row[$colName] !== '') {
                    $items[] = [
                        'baseName' => $baseName,
                        'suffix' => $suffix,
                        'value' => is_numeric($row[$colName]) ? (float)$row[$colName] : $row[$colName]
                    ];
                }
            }

            if (preg_match('/^Source-(\d+|[a-zA-Z0-9]+)$/', $colName, $matches)) {
                $sources[$matches[1]] = $row[$colName] ?? '';
            }
        }

        foreach ($items as $item) {
            $suffix = $item['suffix'];
            $riceType = str_replace('-', ' ', $item['baseName']);
            
            if (isset($sources[$suffix])) {
                $compare[] = [
                    'RiceType' => $riceType,
                    'Price' => $item['value'],
                    'Source' => $sources[$suffix],
                ];
            }
        }
        if (!empty($compare)) {
            echo json_encode(["success" => true, "data" => $compare, "counter" => $id   ]); // Return counter for verification
        } else {
            echo json_encode(["success" => false, "error" => "No valid price data found", "counter" => $id]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No record found for ID: " . $id, "counter" => $id]);
    }

}


else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
}
