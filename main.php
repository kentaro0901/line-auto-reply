<?php
http_response_code(200);
require_once ('./utilities.php');
require_once ('./functions.php');
 
$channelToken = ''; // チャンネルトークン
$channelSecret = ''; // チャンネルシークレット
 
$jsonString = file_get_contents('php://input');
signature_validation($jsonString);
$jsonObject = json_decode($jsonString);
$replyToken = $jsonObject->{"events"}[0]->{"replyToken"};   
 
$messages = reply_type_select($jsonObject);
 
reply_exec($replyToken, $messages);