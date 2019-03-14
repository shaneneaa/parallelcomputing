<?php

    function userToken( $payload ){
        $secret = "verpayat";
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $header = str_replace(['+','/','='],['-','_',''],base64_encode(json_encode($header)));
        $idate = date_create();
       
            $payload =str_replace(['+','/','='],['-','_',''],base64_encode(json_encode($payload)));;
            
            $signature = hash_hmac("sha256",$header.".".$payload,$secret,true);
            
            $signature= str_replace(['+','/','='],['-','_',''],base64_encode($signature));
            $jwt="$header.$payload.$signature";

            return $jwt;
        }
        
    function login( $email, $password ){
        require 'connect.php';
     
        $sql = "SELECT * FROM tbl_acccount WHERE email='". $email . "' AND password = '".$password."'";

        $result = $con->query($sql);
        $iDate = date_create();
        if($result->num_rows > 0){
            $rows = $result->fetch_assoc();
            $payload= [
                "email" => $rows['email'],
                "id" => $rows['id'],
                "idate" => $iDate
            ];

            $jwt = userToken($payload);
            $date2 =addslashes(json_encode($iDate));
    
            $sql2 = "UPDATE tbl_acccount SET date_create = '$date2' WHERE email='". $email . "'";
            $result = $con->query($sql);
            echo $jwt;
            header("Location: pull.php?token=$jwt");
    
            
        }



    }

    function getData(){
        require 'connect.php';

        if(validate($_REQUEST['token'])){
            $sql = "SELECT * FROM tbl_acccount ";
            $result = $con->query($sql); 
            $data=[];
            while ($rows= $result->fetch_assoc()){
                $data[]=$rows;
            }
            echo json_encode($data);
        }        
    }

    function validate($userToken){
        // require 'connect.php';

        $sever= "localhost";
        $localhost="USERNAME";
        $database ="db_parallecomputing";
        $password="";
       
        $conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
        $token = explode(".", $userToken);

        $payload =json_decode(base64_decode($token[1]),true);
  
        $date = addslashes(json_encode($payload['idate']));
        $sql = "SELECT * FROM tbl_acccount WHERE id=". $payload['id'] . " AND email='".$payload['email']."'  ";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            // echo userToken($payload);
            if($userToken==userToken($payload)){
                return 1;
            }
            return 0;
        }

    }

    
?>