<?php 
    include "./connect.php"; 
    define( "FB_ACCOUNT_KIT_APP_ID", "write your app id here" );
    define( "FB_ACCOUNT_KIT_APP_SECRET", "write your app secret here" );
    $code = $_POST['code'];
    $csrf = $_POST['csrf'];
    $auth = file_get_contents( 'https://graph.accountkit.com/v1.1/access_token?grant_type=authorization_code&code='.  $code .'&access_token=AA|'. FB_ACCOUNT_KIT_APP_ID .'|'. FB_ACCOUNT_KIT_APP_SECRET );
    $access = json_decode( $auth, true );
    if( empty( $access ) || !isset( $access['access_token'] ) ){
        return array( "status" => 2, "message" => "Unable to verify the phone number." );
    }
    $appsecret_proof= hash_hmac( 'sha256', $access['access_token'], FB_ACCOUNT_KIT_APP_SECRET ); 
    $ch = curl_init();
    // Set query data here with the URL
    curl_setopt($ch, CURLOPT_URL, 'https://graph.accountkit.com/v1.1/me/?access_token='. $access['access_token'].'&appsecret_proof='. $appsecret_proof ); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_TIMEOUT, '4');
    $resp = trim(curl_exec($ch));
    curl_close($ch);
    $info = json_decode( $resp, true );
    if( empty( $info ) || !isset( $info['phone'] ) || isset( $info['error'] ) ){
        return array( "status" => 2, "message" => "Unable to verify the phone number." );
    }
    $phoneNumber = $info['phone']['national_number'];
    $info["verify"] = "1";  
    echo json_encode( $info );
    //After this line write code for data insert into database


?>
