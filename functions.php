<?php
require_once ('./wp-load.php');

function reply_type_select($jsonObject){
    $messageType = $jsonObject->{"events"}[0]->{"message"}->{"type"};
    $new = ['最新', 'さいしん'];
    $random = ['ランダム', 'らんだむ'];
    $weather = ['天気', 'てんき'];
 
    switch($messageType){
        case 'text':
            $input = $jsonObject->{"events"}[0]->{"message"}->{"text"};
            foreach($new as $value){
                if(strpos($input, $value) !== false) return reply_post(get_last_article());
            } 
            foreach($random as $value){
                if(strpos($input, $value) !== false) return reply_post(get_random_article());
            }
            foreach($weather as $value){
                if(strpos($input, $value) !== false) return reply_weather();
            } 
            return reply_functions();      
            break;
        case 'sticker':
            return reply_random_sticker();
            break;
        default: 
            return reply_undefined();
            break;
    }
}

function reply_functions(){
    $text = [
        'type'  => 'text',
        'text'  => "「最新」:最新記事\n「ランダム」:ランダム記事\n「天気」:福岡の天気\nスタンプ:ランダムスタンプ"
    ];
    $messages = [
        $text
    ];
    return $messages;
}

function reply_undefined(){
    $emojis = [
        [
            'index'     => 0,
            'productId' => "5ac21ba5040ab15980c9b441",
            'emojiId'   => "032"
        ],
        [
            'index'     => 1,
            'productId' => "5ac21ba5040ab15980c9b441",
            'emojiId'   => "019"
        ],
        [
            'index'     => 2,
            'productId' => "5ac21ba5040ab15980c9b441",
            'emojiId'   => "002"
        ],
        [
            'index'     => 3,
            'productId' => "5ac21ba5040ab15980c9b441",
            'emojiId'   => "057"
        ]
    ];
    $text = [
        'type'  => 'text',
        'text'  => '$$$$',
        'emojis' => $emojis
    ];
    $messages = [
        $text
    ];
    return $messages;
}

function reply_post($post){
    $uri = [
        'type' => 'uri',
        'uri' => esc_url(get_permalink($post->ID)),
        'label' => '記事を読む'
    ];
    $buttons = [
        'type'    => 'buttons',
        'thumbnailImageUrl' => get_the_post_thumbnail_url($post, 'medium'),
        'title'   => mb_substr($post->post_title, 0, 40, 'UTF-8'),
        'text'    => mb_substr(strip_tags($post->post_content), 0, 60, 'UTF-8'),
        'actions' => [
            $uri
        ]
    ];
    $template = [
        'type'     => 'template',
        'altText'  => '記事が投稿されました',
        'template' => $buttons
    ];
    $messages = [
        $template
    ];
    return $messages;
}

function reply_random_sticker(){
    $packageID = 11537 + mt_rand(0, 2);
    switch($packageID){
        case 11537: $stickerID = 52002734 + mt_rand(0, 39); break;
        case 11538: $stickerID = 51626494 + mt_rand(0, 39); break;
        case 11539: $stickerID = 52114110 + mt_rand(0, 39); break;
    }
    $sticker = [
        'type'      => 'sticker',
        'packageId' => strval($packageID),
        'stickerId' => strval($stickerID)
    ];
    $messages = [
        $sticker
    ];
    return $messages;
}

function reply_weather(){
    $fukuoka = 400010;
    $baseUrl = 'http://weather.livedoor.com/forecast/webservice/json/v1?city='.$fukuoka;
    $json = file_get_contents($baseUrl, true);
    $json = mb_convert_encoding($json, 'UTF-8');
    $obj = json_decode($json, true);
    $weather = $obj['forecasts'][0]['image']['title'];
    $text = weather_to_emoji($weather);
    $messages = [
        $text
    ];
    return $messages;
}

function weather_to_emoji($weather){
    $wez = ['晴', '曇', '雨', '雪'];
    $tra = ['のち', '時々', '一時'];
    $sunny = [
        'index'     => 0,
        'productId' => '5ac21184040ab15980c9b43a',
        'emojiId'   => '225'
    ];
    $cloudy = [
        'index'     => 1,
        'productId' => '5ac21184040ab15980c9b43a',
        'emojiId'   => '226'
    ];
    $rainny = [
        'index'     => 2,
        'productId' => '5ac21184040ab15980c9b43a',
        'emojiId'   => '231'
    ];
    $snowy = [
        'index'     => 3,
        'productId' => '5ac21184040ab15980c9b43a',
        'emojiId'   => '232'
    ];
    $emj = [
        '晴' => $sunny,
        '曇' => $cloudy,
        '雨' => $rainny,
        '雪' => $snowy,
        'のち' => '→',
        '時々' => '/',
        '一時' => '|'
    ];
    $txt = '';
    $emojis = [];

    foreach($wez as $value){
        if(strpos($weather, $value) == 0){
            $txt .= '$';
            $emojis[] = $emj[$value];
            $emojis[0]['index'] = 0;
            break;
        }
    } 
    foreach($tra as $value){
        if(strpos($weather, $value) > 0){
            $txt .= $emj[$value];
            break;
        }
    } 
    foreach($wez as $value){
        if(strpos($weather, $value) > 1){
            $txt .= '$';
            $emojis[] = $emj[$value];
            $emojis[1]['index'] = 2;
            break;
        }
    } 

    $text = [
        'type'  => 'text',
        'text'  => $txt,
        'emojis' => $emojis
    ];
    return $text;
}