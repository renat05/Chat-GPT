<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' ,content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta name='msapplication-TileColor' content='#2b5797'>
        <meta name='theme-color' content='#ffffff'>
        <link rel='stylesheet' href='assets/css/main.css'>
        <link rel='preconnect' href='https://fonts.googleapis.com'>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <title>ChatGPT</title>
</head>
<body>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['message'])) {
        $user_message = $_POST['message'];
        $response = gpt_get($user_message);

        $_SESSION['previous_response'] = $response;
        echo $response;
        exit();
    }
}

function gpt_get($textmsg) {
    $openai_api_key = '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';

    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [],
        'max_tokens' => 300, // Максимальное количество токенов в ответе
        'stop' => ['\n'], // Остановить генерацию после первого символа новой строки
    ];

    if (isset($_SESSION['previous_response'])) {
        $data['messages'][] = [
            'role' => 'system',
            'content' => $_SESSION['previous_response'],
        ];
    }

    $data['messages'][] = [
        'role' => 'user',
        'content' => $textmsg,
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer $openai_api_key\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
        die('Error sending request to OpenAI API');
    }

    $response = json_decode($result, true);

    if (!$response || !isset($response['choices'])) {
        die('Invalid response from OpenAI API');
    }
    
    $generated_text = $response['choices'][0]['message']['content'];
    $decoded_text = strip_tags($generated_text); // Remove HTML tags
    
    $_SESSION['previous_response'] = $decoded_text;
    
    return $decoded_text;
}

?>






<div class="chat-container">
        <section class="chat-wrapper hidden">
        <div class="chat-title">
            <svg onclick="chatWindowCall_Hidden()" class="arrow-to-back " version="1.0" xmlns="http://www.w3.org/2000/svg"
            width="25px" height="25px" viewBox="0 0 512.000000 512.000000"
            preserveAspectRatio="xMidYMid meet">

            <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
            fill="#D1F1D0" stroke="none">
            <path d="M2073 4680 c-22 -5 -58 -18 -80 -29 -56 -29 -1945 -1925 -1969 -1976
            -26 -55 -26 -175 0 -230 13 -27 324 -345 980 -1002 1055 -1057 997 -1006 1121
            -1005 98 0 142 25 278 160 149 147 162 170 162 282 0 63 -5 94 -19 120 -10 19
            -271 288 -579 597 l-562 563 1731 0 c1975 0 1797 -8 1895 89 81 82 92 122 87
            339 -3 154 -5 171 -28 215 -27 55 -70 96 -133 129 l-42 23 -1750 3 -1749 2
            551 553 c626 626 610 606 601 741 -3 44 -13 86 -26 111 -11 22 -76 94 -144
            161 -92 91 -134 125 -168 137 -61 21 -107 26 -157 17z"/>
            </g>
            </svg>

            <h1>Chatbot</h1>
        </div>
            <div id="chat-log">
                <div>Здравствуйте, чем могу быть вам полезен?</div>
            </div>

        <form id="chat-form" onsubmit="sendMessage(); return false;">
            <input class="chat-user-message" type="text" id="message-input" placeholder="Введите ваш вопрос..." autocomplete="off"/>
            <button class="chat-user-button" type="submit">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
            width="25" height="25" viewBox="0 0 512.000000 512.000000"
            preserveAspectRatio="xMidYMid meet">

            <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
            fill="#000000" stroke="none">
            <path d="M4655 4676 c-148 -57 -1233 -471 -2410 -921 -1177 -449 -2153 -824
            -2169 -833 -91 -53 -99 -183 -15 -244 14 -11 331 -137 705 -281 l679 -263 5
            -850 c5 -797 6 -852 23 -877 62 -95 207 -87 260 13 11 19 141 276 290 571
            l272 537 660 -490 c363 -270 680 -504 704 -519 75 -48 172 -33 215 33 8 13
            292 932 631 2043 489 1603 615 2028 612 2058 -6 47 -29 82 -70 107 -59 36
            -100 27 -392 -84z m-750 -615 c-17 -15 -2279 -1624 -2306 -1639 -24 -15 -48
            -7 -528 178 -277 107 -499 196 -495 197 5 2 756 289 1669 637 913 349 1662
            635 1665 635 2 1 0 -3 -5 -8z m660 -238 c-640 -2105 -890 -2920 -896 -2927 -4
            -4 -79 45 -167 110 -910 674 -1031 767 -1024 778 8 14 2152 2276 2157 2276 1
            0 -30 -107 -70 -237z m-856 -300 c-99 -103 -1491 -1574 -1551 -1638 -34 -38
            -82 -122 -191 -340 -80 -159 -162 -319 -181 -355 l-36 -65 0 516 0 516 637
            454 c351 250 807 574 1013 721 206 147 377 268 379 268 3 0 -29 -35 -70 -77z"/>
            </g>
            </svg>

            </button>
        </form>
    </section>
</div>

<div class="dialog-call-button-wrapper" onclick="chatWindowCall_Hidden()">
    <button class="dialog-call-button">
        <img width="65" height="65" src="assets/img/dialog-icon.svg" alt="Иконка вызова диалогового окна">
    </button>
</div>

<script>
const arrowToBack  = document.querySelector('.arrow-to-back ');
const chatWindowCall = document.querySelector('.dialog-call-button-wrapper');
const chatWrapper = document.querySelector('.chat-wrapper');

function chatWindowCall_Hidden() {
    chatWindowCall.classList.toggle('hidden');
    chatWrapper.classList.toggle('active');
    chatWrapper.classList.toggle('hidden');
}

function sendMessage() {

    var messageInput = document.getElementById('message-input');
    var message = messageInput.value.trim();
    messageInput.value = "";

    if (message !== '') {
        appendMessage(message);

        // Отправка сообщения на сервер
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var response = xhr.responseText;
                appendMessage(response);
                messageInput.value = '';
            }
        };
        xhr.send('message=' + encodeURIComponent(message));
    }
    event.preventDefault(); // Отменить стандартное действие формы
}

function appendMessage(message) {
    var chatLog = document.getElementById('chat-log');
    var messageElement = document.createElement('div');
    messageElement.innerHTML = message;

    if (chatLog.childElementCount % 2 === 1) {
        messageElement.classList.add("user-message");
    }

    chatLog.appendChild(messageElement);
}


</script>
</body>
</html>