<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');

$action = '';
$data = [];

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Google\GenerativeAI\GenerativeAI;


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

$yourApiKey = '';
$serp_api_key = '';
$epass = '';
$access_key = '';


$host ="";
$user ="";
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

    else if (isset($data['resetData'])){
        $data = $data['resetData'];
    }

    else if (isset($data['feedback']) || isset($data['rating'])) {

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

function searchAPI($productType, $siteQuery) {
    
    global $serp_api_key;

    $normalizedProductType = trim(str_replace('-', ' ', $productType));
    $searchTerm = strpos(strtolower($productType), 'rice') !== false 
    ? "SRP of $normalizedProductType in the Philippines 2025"
    : "Current price of $normalizedProductType in the Philippines 2025";

    $query = [
        "q" => "$searchTerm $siteQuery",
        "hl" => "en",
        "gl" => "ph",
        "google_domain" => "google.com",
        "num" => 10,
        "api_key" => $serp_api_key
    ];

    $url = "https://serpapi.com/search.json?" . http_build_query($query);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

function extractLatestPrice($results) {   
    if (!isset($results['organic_results'])) return null;

    $latest = null;
    $pricePattern = '/(?:PHP|₱|P)\s*([\d,]+\.?\d*)/i';

    foreach ($results['organic_results'] as $result) {

        $content = $result['title'] . ' ' . ($result['snippet'] ?? '');

        if (preg_match($pricePattern, $content, $match)) {
                
            $price = [
                'amount' => (float) str_replace(',', '', $match[1]),
                'currency' => '₱',
                'formatted' => '₱' . $match[1],
                'date' => extractDate($result['snippet'] ?? ''),
                'title' => $result['title'] ?? 'Source',
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

function AIsummarizer($productName, $value){
    
    global $yourApiKey;

    try {
        $client = Gemini::client($yourApiKey);
        $model = $client->generativeModel('gemini-2.0-flash');

        $priceStr = implode(', ', $value);
        $prompt = "Give a very short and brief information about $productName. Summarize price information for $productName with price in Philippine Peso: $priceStr amd give future price prediction avoid giving disclaimers.";

        $result = $model->generateContent($prompt);
        
        return $result->text();
    }catch(Exception $e){
        error_log("AI Summarizer Error: " . $e->getMessage());
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

    global $epass;
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
        $mail->Password   = $epass;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->addEmbeddedImage('Logo.png', 'logo','Logo.png');

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
        <img src="cid:logo" alt="Logo" style="max-width: 350px; width: 100%; height: auto;"> 
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
            //echo msg has been sent
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
     
    $sql = "SELECT feedback, rating FROM Feedbacks ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result) {
        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = [
                'username' => 'Anonymous User', // Default 
                'rating' => (int)$row['rating'],
                'comment' => $row['feedback'] 
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
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

}elseif ($action === 'fetchPrices'){

    $columnsMap = [];
    $productList = [];

    $checkSql = "SHOW COLUMNS FROM `Price-GOV`";
    $result = $conn->query($checkSql);

    if (!$result || $result->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Failed to get table columns"]);
        exit;
    }

    //columns map 
    while ($row = $result->fetch_assoc()) {
        $field = $row['Field'];

        if ($field === 'id' || $field === 'Added-at') {
            continue;
        }

        if (preg_match('/^(.*)-([A-Za-z0-9]+)$/', $field, $matches) && stripos($field, 'Source-') === false) {
            $baseName = $matches[1]; 
            $suffix = $matches[2];

            $sourceColumn = "Source-$suffix";

            $sourceCheck = $conn->query("SHOW COLUMNS FROM `Price-GOV` LIKE '$sourceColumn'");
            if ($sourceCheck->num_rows === 0) {
                continue; 
            }

            $normalizedKey = strtolower(str_replace(['-', '_', ' '], '', $baseName));
            
            $columnsMap[$normalizedKey] = [
                'price' => $field,
                'source' => $sourceColumn,
                'originalName' => $baseName
            ];
            $productList[$baseName] = $baseName;
        }
    }

    $result->free();

    if (empty($productList)) {
        echo json_encode(["success" => false, "error" => "Product list is empty."]);
        return;
    }

    try {
        $columns = ['`Added-at`'];
        $values = ['NOW()'];
        $updateCount = 0;
        foreach ($productList as $typeKey => $typeName) {

            $normalizedKey = strtolower(str_replace(['-', '_', ' '], '', $typeKey));

            if (!isset($columnsMap[$normalizedKey])) {
                continue;
            }

            $govPrice = null;
            $newsPrice = null;
    
            //gov
            $govPrice = searchAPI($typeKey, 'site:.gov.ph OR site:psa.gov.ph');
            $govPrice = $govPrice ? extractLatestPrice($govPrice) : null;
            
            $newsQuery = strpos(strtolower($typeKey), 'rice') !== false
            ? 'site:inquirer.net OR site:gmanetwork.com/news'
            : 'site:inquirer.net OR site:manilatimes.net';

            $newsPrice = searchAPI($typeKey, $newsQuery);
            $newsPrice = $newsPrice ? extractLatestPrice($newsPrice) : null;

            $latestPrice = null;
            if ($govPrice && $newsPrice) {
                $govDate = $govPrice['date'] !== 'N/A' ? strtotime($govPrice['date']) : 0;
                $newsDate = $newsPrice['date'] !== 'N/A' ? strtotime($newsPrice['date']) : 0;
                
                $latestPrice = ($govDate > $newsDate) ? $govPrice : $newsPrice;
            } else {
                $latestPrice = $govPrice ?: $newsPrice;
            }

            if ($latestPrice) {
                $priceColumn = $columnsMap[$normalizedKey]['price'];
                $sourceColumn = $columnsMap[$normalizedKey]['source'];
                
                $columns[] = "`$priceColumn`";
                $values[] = $latestPrice['amount'];
            
                $columns[] = "`$sourceColumn`";
                //source column
                $values[] = "'" . addslashes($latestPrice['link']) . "'";
            
                $updateCount++;
            } else {
                //NULL 
                $columns[] = "`{$columnsMap[$normalizedKey]['price']}`";
                $values[] = 'NULL';
                $columns[] = "`{$columnsMap[$normalizedKey]['source']}`";
                $values[] = 'NULL';
            }
        } 
        if ($updateCount > 0) {
            $updateSql = "INSERT INTO `Price-GOV` (" . implode(', ', $columns) . ") 
                         VALUES (" . implode(', ', $values) . ")";
            
            if ($conn->query($updateSql)) {
                echo json_encode([
                    "success" => true,
                    "message" => "All prices updated in single row",
                    "updated" => $updateCount
                ]);
            } else {
                throw new Exception("Database error: " . $conn->error);
            }
        } else {
            echo json_encode(["success" => false, "message" => "No new prices found"]);
        }

    }catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error updating prices: " . $e->getMessage()]);
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

            $sql1 = "ALTER TABLE `Price-GOV` ADD COLUMN `$item` FLOAT NOT NULL";
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
    $postData = json_decode(file_get_contents('php://input'), true);
    
    //inputs
    if (empty($postData['itemName']) || !isset($postData['newPrice'])) {
        echo json_encode(["success" => false, "error" => "Missing required fields"]);
        exit;
    }

    $itemName = $conn->real_escape_string($postData['itemName']);
    $newPrice = floatval($postData['newPrice']);
    $sourceUrl = isset($postData['sourceUrl']) ? $conn->real_escape_string($postData['sourceUrl']) : '/addedadmin';

    //find column
    $parts = explode("-", $dynamicColumnName);
    $dynamicSuffix = end($parts); 

    $sourceColumn = "Source-" . $dynamicSuffix;

    $checkSql = "SHOW COLUMNS FROM `Price-GOV` LIKE '$itemName%'";
    $result = $conn->query($checkSql);

    if ($result && $result->num_rows > 0) {
        //base name
        $row = $result->fetch_assoc();
        $dynamicColumnName = $row['Field']; //Potatoes-JQY

        //source column
        $sourceColumn = "Source-" . end(explode("-", $dynamicColumnName));

        $updateSql = "UPDATE `Price-GOV` 
                     SET `$dynamicColumnName` = $newPrice, 
                         `$sourceColumn` = '$sourceUrl'
                     WHERE `Added-at` = (SELECT MAX(`Added-at`) FROM (SELECT * FROM `Price-GOV`) AS temp)";

        if ($conn->query($updateSql)) {
            echo json_encode(["success" => true, "message" => "Price updated successfully"]);
        } else {
            throw new Exception("Update failed: " . $conn->error);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Item column does not exist"]);
    }
}elseif ($action === 'getCompare'){

    //clean input
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
            echo json_encode(["success" => true, "data" => $compare, "counter" => $id   ]);
        } else {
            echo json_encode(["success" => false, "error" => "No valid price data found", "counter" => $id]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No record found for ID: " . $id, "counter" => $id]);
    }

}elseif ($action === "delItem"){

    $postData = json_decode(file_get_contents('php://input'), true);
    $itemName = $conn->real_escape_string($postData['itemName']);

    $sql1 = "SHOW COLUMNS FROM `Price-GOV` LIKE '$itemName%'";
    $result = $conn->query($sql1);

    if ($result && $result->num_rows > 0) {
        //find column
        $row = $result->fetch_assoc();
        $dynamicColumnName = $row['Field']; //Potatoes-JQY

        //source column
        $parts = explode("-", $dynamicColumnName);
        $dynamicSuffix = end($parts); 
        $sourceColumn = "Source-" . $dynamicSuffix;

        $sql2 = "ALTER TABLE `Price-GOV` 
                DROP COLUMN `$dynamicColumnName`,
                DROP COLUMN `$sourceColumn`;";

        if ($conn->query($sql2)) {
            echo json_encode(["success" => true, "message" => "Item deleted successfully"]);
        } else {
            throw new Exception("Deleted failed: " . $conn->error);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Item column does not exist"]);
    }
    
}elseif( $action === 'sumarizeData'){

    echo json_encode(["success" => false, "error" => "wag gamitin toh: "]);

}elseif( $action === 'chartData'){

    $postData = json_decode(file_get_contents('php://input'), true);
    $itemName = $conn->real_escape_string($postData['itemName']);
    $searchPattern = str_replace(' ', '-', $itemName) . '%';

    $sql1 = "SHOW COLUMNS FROM `Price-GOV` LIKE '$searchPattern'";
    $result = $conn->query($sql1);

    if ($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $dynamicColumnName = $row['Field']; //Potatoes-JQY

        $sq2 = "SELECT `" . $dynamicColumnName . "` FROM `Price-GOV` ORDER BY id ASC";
        $result = $conn->query($sq2);

        $prices = [];

        while ($row = $result->fetch_assoc()) {
            if (isset($row[$dynamicColumnName]) && is_numeric($row[$dynamicColumnName])) {
                $prices[] = (float)$row[$dynamicColumnName];
            }
        }
        if (!empty($prices)) {
            $productName = preg_replace('/-[^-]+$/', '', $dynamicColumnName);
            $productName = str_replace('-', ' ', $productName);

            echo json_encode(["success" => true, "data" =>['RiceType' => $productName, 'Prices' => $prices], "message" => AIsummarizer($productName, $prices), "debug" => "blah blah " . implode(', ', $prices)]);
        } else {
            echo json_encode(["success" => false, "error" => "No valid price data found"]);
        }
    }

}elseif( $action === 'newsandUpdates'){

    global $access_key;
    
    $queryString = http_build_query([
        'access_key' => $access_key,
        'countries' => 'ph,us,cn',
        'languages' => 'en',
        'categories' => 'business',
        //'keywords' => 'economy,market,prices,goods,commodities', 
        'limit' => 10,
      ]);
      
    $ch = curl_init(sprintf('%s?%s', 'https://api.mediastack.com/v1/news', $queryString));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
    $json = curl_exec($ch);
    curl_close($ch);
      
    $apiResult = json_decode($json, true);

    if (empty($apiResult['data'])) {
        echo json_encode(["success" => true, "data" => []]); //empty array if no news
        exit;
    }

    echo json_encode(["success" => true, "data" => $apiResult]);
    exit;

}elseif ($action === 'getUsers'){
    $sql = "SELECT Email, created_at FROM Users ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'email' => $row['Email'], 
                'created' => $row['created_at'], 
            ];
        }
        echo json_encode(["success" => true, "users" => $users]);
    }else {
        echo json_encode(["success" => false, "error" => "Failed to fetch users"]);
    }

}elseif ($action === 'deleteUsers'){

    $postData = json_decode(file_get_contents('php://input'), true);
    $User = $conn->real_escape_string($postData['User']);

    $sql1 = "SELECT Email FROM Users WHERE Email = '$User'";
    $result = $conn->query($sql1);

    if ($result && $result->num_rows > 0) {

        $sql2 = "DELETE FROM Users WHERE Email = '$User'";

        if ($conn->query($sql2)) {
            echo json_encode(["success" => true, "message" => "User deleted successfully"]);
        } else {
            throw new Exception("Deleted failed: " . $conn->error);
        }
    } else {
        echo json_encode(["success" => false, "error" => "User does not exist"]);
    }

}elseif ($action === 'remindUser') {

    global $epass;
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
        $mail->Password   = $epass;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->addEmbeddedImage('Logo.png', 'logo','Logo.png');

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
        <img src="cid:logo" alt="Logo" style="max-width: 350px; width: 100%; height: auto;"> 
        <br>
        <h2>This is an automatic Email informing you to update your prices<h2>
        <br>
        <p>Autorities will be notified</p>
        </center>
        <br>
        ';

        $mail->AltBody = 'Enter this code to reset your password: ' .$reset;

        if($mail->send()){
            //echo msg has been sent
            echo json_encode(["success" => true,]);
        }
        else{
            echo json_encode(["success" => false]);
        }

    }else{
        //echo error no email found
        echo json_encode(["success" => false]);
    }

}

else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
}
?>
