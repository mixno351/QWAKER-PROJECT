<?php
	header('Content-Type: text/html; charset=utf-8');

	$bot_token = '5594916787:AAFdATHfzIRa5NELilTfOflZi1xl4QVbKis';
	$data1 = file_get_contents('php://input');
	$data = json_decode($data1, true);

	$cityARRAY = array(
		'Витебск'=>'55.192681,30.206359', 
		'Минск'=>'53.900601,27.558972', 
		'Брест'=>'52.099651,23.763666', 
		'Гомель'=>'52.431339,30.99367', 
		'Гродно'=>'53.668763,23.822267', 
		'Могилев'=>'53.898066,30.332534',
		'Могилёв'=>'53.898066,30.332534'
	);
	
	$connect = mysqli_connect('localhost', 'u1534778_root', 'eX5yP6bZ4gzD1b', 'u1534778_bot');

	if (!empty($data['message']['text'])) {
		$chat_id = $data['message']['from']['id'];
		$text = trim($data['message']['text']);
		$language = trim($data['message']['from']['language_code']);
		$message_id = intval($data['message']['message_id']);

		if ($connect) {
			$startedBool = false;
			if (mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `weather` WHERE `tg_chat` = '$chat_id' AND `type` = 'start' LIMIT 1")) > 0) {
				$startedBool = true;
			} else {
				mysqli_query($connect, "INSERT INTO `weather`(`tg_chat`, `type`) VALUES ('$chat_id', 'start')"); // make start
			}

			mysqli_query($connect, "INSERT INTO `weather_log`(`tg_chat`, `log`, `msgid`) VALUES ('$chat_id', '$data1', '$message_id')"); // log...

			$keyboard = '';

			if ($text === '/start') {
				$name = trim($data['message']['from']['first_name']);
				if ($name === '') {
					$name = trim($data['message']['from']['username']);
				}

				$welcome_text = 'Привет';
				if ($startedBool == true) {
					$welcome_text = 'С возвращением';
				}
				$return_text = $welcome_text.', '.$name.'. Чтобы просмотреть погоду в городе - просто введите город в поле ниже и отправьте сообщение с введенным городом. Доступные страны для просмотра погоды: Беларусь.';
			}

			if (array_key_exists($text, $cityARRAY)) {
				$result = 'Был выбран город %1s.';

				$opts = array(
					'http'=>array(
						'method'=>"GET",
						'header'=>"X-Gismeteo-Token: 56b30cb255.3443075\r\n"
					)
				);
				  
				$context = stream_context_create($opts);

				$latlng = $cityARRAY[$text];
				$result_latlng = explode(',', $latlng, 2);
				$geoapi = file_get_contents('https://api.gismeteo.net/v2/weather/current/?latitude='.round($result_latlng[0], 3, PHP_ROUND_HALF_UP).'&longitude='.round($result_latlng[1], 3, PHP_ROUND_HALF_UP), false, $connect);
				// $geocode = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&sensor=false');
        		// $output = json_decode($geocode);

				if (mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `weather` WHERE `tg_chat` = '$chat_id' AND `type` = 'city' AND `city` = '$latlng' LIMIT 1")) < 1) {
					mysqli_query($connect, "INSERT INTO `weather`(`tg_chat`, `type`, `city`) VALUES ('$chat_id', 'city', '$latlng')"); // make start
				}
				
				$return_text = str_replace("%1s", $text, json_decode($geoapi));
			}


		} else {
			$return_text = 'Ошибка подключения к базе данных. Сейчас продолжить невозможно, пожалуйста, повторите попытку позже...';
		}

		message_to_telegram($bot_token, $chat_id, $return_text, $keyboard);
	}

	function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '') {
	    $ch = curl_init();
	    $ch_post = [
	        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
	        CURLOPT_POST => TRUE,
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_TIMEOUT => 10,
	        CURLOPT_POSTFIELDS => [
	            'chat_id' => $chat_id,
	            'parse_mode' => 'HTML',
	            'text' => $text,
	            'reply_markup' => $reply_markup,
	        ]
	    ];

	    curl_setopt_array($ch, $ch_post);
	    curl_exec($ch);
	}

	function contains($str, array $arr) {
	    foreach($arr as $a) {
	        if (stripos($str,$a) !== false) return true;
	    }
	    return false;
	}
?>