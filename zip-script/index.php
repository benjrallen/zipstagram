<?php
// Client ID for Instagram API
$instagramClientID = '9110e8c268384cb79901a96e3a16f588&count=50';

$api = 'https://api.instagram.com/v1/tags/zipcar/media/recent?client_id='.$instagramClientID; //api request (edit this to reflect tags)
$cache = './cache.json';

//if(/*file_exists($cache) && filemtime($cache) > time() - 60*60*/){
    // If a cache file exists, and it is newer than 1 hour, use it
    // $test = json_decode(file_get_contents($cache),true);
//}

//else{
    // Make an API request and create the cache file
    $response = get_curl($api); //change request path to pull different photos

           
        
        //file_put_contents($cache,json_encode($test));

$instagrams = json_decode($response)->data;

$features = array();
foreach ( $instagrams as $instagram ) {
    if ( !$instagram->location ) {
        // Images aren't required to have a location and this one doesn't have one
        // Now what? 
        continue; // Skip?
    }

    $features[] = array(
        'type' => 'Feature',
        'geometry' => array(
            'coordinates' => array(
                $instagram->location->longitude, 
                $instagram->location->latitude
            ),
            'type' => 'Point'
        ),
        'properties' => array(
            'longitude' => $instagram->location->longitude,
            'latitude' => $instagram->location->latitude,
            'title' => $instagram->location->name,
            'user' => $instagram->user->username,
             // id is image id, instagram_id is user id
            'id' => $instagram->id,
            'image' => $instagram->images->standard_resolution->url,
            'description' => $instagram->caption ? $instagram->caption->text : null,
            'instagram_id' => $instagram->id,
            'likes' => $instagram->likes->count,
            'profile_picture' => $instagram->user->profile_picture
        )
    );
}
//}

$results = array(
    'type' => 'FeatureCollection',
    'features' => $features,
);

file_put_contents($cache,json_encode($results));

print_r($results);
    



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