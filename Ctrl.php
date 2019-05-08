<?php
/*アクセストークンを「''」内に記述する*/
$aceessToken = 'Hh5cxnBD6LVhK2cHCyAUENwspppg+/jyzmb0N2CSbIMmd+nRhN6CsLS9Sk0gfF+VWw1kouEJGSFOylEE8Ped0e8ysn6g2zCCV2t5qamkgyaHtaZz3KKn/ye14UOaNjSpzK7SXzcZ9Nvg2uBCGBeNswdB04t89/1O/w1cDnyilFU=';

/*php://input(送られてきたデータが格納されているPHP固有の倉庫)から取り込む*/
$jsonString = file_get_contents('php://input');
error_log($jsonString);

/*json形式のデータをPHPが理解できるような形式にデコードする*/
$jsonObj = json_decode($jsonString);

/*デコードされたデータは連想配列（配列の中に配列が入ってる構造）になっているので、
 *取り込みたいデータを配列から指定して取り込む。*/
$message = $jsonObj->{"events"}[0]->{"message"};
$text = $message->{"text"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};
$line_source = $jsonObj->{"events"}[0]->{"source"};

/*自分がやりたい処理を書く(例として、オウム返しの処理を書く)*/

$messageData = [
    'type' => 'text',
    'text' => $text
];


/*返信用に処理されたデータを成型する*/
$response = [
    'replyToken' => $replyToken,
    'messages' => [$messageData]
];

/*エラーの場合にログが出るようにする処理*/
error_log('[[[TEST]]]'.json_encode($response));

/*成形されたデータを返信する処理（いじらなくていい）*/
$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
));
$result = curl_exec($ch);
error_log('[[[TEST]]]'.$result);