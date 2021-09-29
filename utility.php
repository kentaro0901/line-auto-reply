<?php
 
// 署名検証
function signature_validation($body){
    global $channelSecret;
    $hash = hash_hmac('sha256', $body, $channelSecret, true);
    $sig = base64_encode($hash);
    $headers = getallheaders();
    $compSig = $headers["X-Line-Signature"];
    if ($sig != $compSig) {
        exit('bad request.');
    }
}
 
// 一斉送信
function broadcast_exec($messages){
    $ch = curl_init('https://api.line.me/v2/bot/message/broadcast');
    $line = [
        'messages' => $messages
    ];
    send_exec($ch, $line);
}
 
// 返信
function reply_exec($replyToken, $messages){
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    $line = [
        'replyToken' => $replyToken,
        'messages' => $messages
    ];
    send_exec($ch, $line);
}
 
// 共通処理
function send_exec($ch, $line){
    global $channelToken;
    $line = json_encode($line);  
    $headers = [
        'Authorization: Bearer ' . $channelToken,
        'Content-Type: application/json; charset=UTF-8',
    ];
    $options = [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HEADER => true,
        CURLOPT_POSTFIELDS => $line,
    ];
    curl_setopt_array($ch, $options); 
    curl_exec($ch);
    curl_close($ch);
}