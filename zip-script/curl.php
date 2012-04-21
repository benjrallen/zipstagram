<?php


$instagramClientID = '9110e8c268384cb79901a96e3a16f588';

$api = 'https://api.instagram.com/v1/tags/zipcar/media/recent?client_id='.$instagramClientID; //api request (edit this to reflect tags)

$response = get_curl($api); //change request path to pull different photos

?>

<?php
//Added curl for faster responce
function get_curl($url){
    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        $output = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return $output;
    }else{
        return file_get_contents($url);
    }

}

?>

<?php

print_r($response)

 ?>