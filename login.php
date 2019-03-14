<?php
    header('Access-Control-Allow-Origin: *');
    header("content: application/json");
?>

<?php   


require_once('connect.php');

function base64urlEncode($data){
    $urlSafeData = strtr(base64_encode($data), '+/','-_');
    return rtrim($urlSafeData,'=');
}

$email = $_POST['email'];
$password =$_POST['password'];

    $sql = "SELECT * FROM tbl_acccount WHERE email='". $email . "' AND password = '".$password."'";
    $result = $con->query($sql);   
    if ($rows = $result->fetch_assoc()) {
       
        $secret = "verpayat";
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $header = base64urlEncode(json_encode($header));

        $payload = [
            "firstname" => $rows['firstname'],
            "date_added" => $rows['date_added'],
        ];
        $payload = base64urlEncode(json_encode($payload));

        
        $signature =hash_hmac("sha256",$header.".".$payload,$secret,true);     
        $signature2= base64urlEncode($signature);
        echo("$header.$payload.$signature2");

        // $acc_data = [
        //     "id" => $rows['id'],
        //     "firstname" => $rows['firstname'],
        //     "lastname" => $rows['lastname'],
        //     "email" => $rows['email'],
        //     "date_added" => $rows['date_added'],
        // ];

    }
    else print mysqli_error();

   
    
    ?>

    