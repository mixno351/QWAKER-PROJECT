<?php
	header('Access-Control-Allow-Origin: *');
	header('Vary: Accept-Encoding, Origin');
	header('Content-Length: 235');
	header('Keep-Alive: timeout=2, max=99');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 604800');
	header('Connection: Keep-Alive');
	header('Content-Type: text/html; charset=utf-8');
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/data.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php'; ?>
<?php
	$token = trim(mysqli_real_escape_string($connect, $_POST['token']));

	$timeREQUEST = $serverTIME;

	$checkSESSION = mysqli_query($connect, "SELECT * FROM `user_sessions` WHERE `sid` = '$token' LIMIT 1");
	if (mysqli_num_rows($checkSESSION) > 0) {
		$session = mysqli_fetch_assoc($checkSESSION);
		$sessionUTOKEN = $session['utoken'];
		$check_u = mysqli_query($connect, "SELECT * FROM `users` WHERE `token_public` = '$sessionUTOKEN' LIMIT 1");
		if (mysqli_num_rows($check_u) > 0) {
			$sUSER = mysqli_fetch_assoc($check_u);
			$token = $sUSER['token'];
		}
	}
?>
<?php
	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `token` = '$token' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		$user = mysqli_fetch_assoc($check_user);
		$user_id = intval($user['id']);

		mysqli_query($connect, "UPDATE `users` SET `online`='$timeUSER' WHERE `id`='$user_id'");
	} else {
		echo json_encode(array(
			"id" => "id_user_token_empty",
			"type" => "error", 
			"task" => "token:empty", 
			"camp" => "user", 
			"message" => 'Токен должен быть действительным!',
			"error_value" => $token,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($user['banned'] == 1) {
		echo json_encode(array(
			"id" => "id_user_banned",
			"type" => "error", 
			"task" => "user:banned", 
			"camp" => "server", 
			"message" => 'Аккаунт заблокирован, действие не может быть выполненно!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$timeLast = $user['date_last_extract'] + 604800;
	if ($timeUSER < $timeLast) {
		echo json_encode(array(
			"id" => "id_date_last_error",
			"type" => "error", 
			"task" => "user:extract:date-last", 
			"camp" => "user", 
			"message" => 'Вы уже получили выписку <b>'.date("d.m.Y H:i", $user['date_last_extract']).'</b>. Новый запрос можно отправить не раньше чем раз в неделю. Следующий запрос будет доступен <b>'.date("d.m.Y H:i", $timeLast).'</b>',
			"error_value" => null,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array(
			"id" => "id_email_unvalid",
			"type" => "error", 
			"task" => "user:extract:email", 
			"camp" => "user", 
			"message" => 'Почта указана неверно. Формат почты должен быть таким: [mymail@domain.com]!',
			"error_value" => $user['email'],
			"time" => $serverTIME
		), 128);
		exit();
	}

	$filesU = '';
	$check_files = mysqli_query($connect, "SELECT * FROM `uploaded_files` WHERE `uid` = '$user_id'");
	if (mysqli_num_rows($check_files) > 0) {
		while($row = mysqli_fetch_assoc($check_files)) {
			$filesU = $filesU.'<h3 style="
		    opacity: .5;">[Ссылка: <a href="'.$row['full_url'].'">'.$row['full_url'].'</a>, Тип: '.$row['type'].', Время: '.$row['time'].', ID файла: '.$row['id'].'], </h3>';
		}
	} else {
		$filesU = 'Вы не загрузили ни одного файла. Вы заботитесь о своей безопасности :)';
	}

	$check_posts = mysqli_query($connect, "SELECT * FROM `posts` WHERE `user_id` = '$user_id'");
	$check_emotions = mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `uid` = '$user_id'");
	$check_comments = mysqli_query($connect, "SELECT * FROM `comments` WHERE `user_id` = '$user_id'");
?>
<?php
	$to = $user['email'];
	$subject = 'Выписка личной информации';
	$message = '
		<html style="
		    font-family: system-ui;
		    font-size: 12px;
		    font-weight: 500;
		"><head></head><body>
						<h1>Выписка личной информации</h1>
						<hr>
						<h3 style="
		    opacity: .5;
		">Данные аккаунта</h3>
						<h2>Логин: '.$user['login'].'</h2>
						<h2>Никнейм: '.$user['nickname'].'</h2>
						<h2>Почта для взаиомодействия с аккаунтом: '.$user['email'].'</h2>
						<h2>Язык устройства при регистрации: '.$user['language'].'</h2>
						<h3 style="
		    opacity: .5;
		">Личная информация/Контакты</h3>
						<h2>Почта: '.$user['url_email'].'</h2>
						<h2>Сайт: '.$user['url_site'].'</h2>
						<h2>Номер телефона: '.$user['url_phone'].'</h2>
						<h2>Другая соц. сеть: '.$user['url_social'].'</h2>
						<h3 style="
		    opacity: .5;
		">Ваши действия</h3>
						<h2>Последний онлайн: '.$user['online'].'</h2>
						<h2>Всего ваших публикаций: '.mysqli_num_rows($check_posts).'</h2>
						<h2>Всего оставлено эмоций под публикациями: '.mysqli_num_rows($check_emotions).'</h2>
						<h2>Всего оставлено комментариев под публикациями: '.mysqli_num_rows($check_comments).'</h2>
						<h3 style="
		    opacity: .5;
		">Время запроса: '.$timeREQUEST.'</h3>
						<h3 style="
		    opacity: .5;
		">Запрос отправлен: '.$serverTIME.'</h3>
						<h3 style="
		    opacity: .5;
		">IP отправки: '.$userIP.'</h3>
		<h1>Все загруженные файлы ('.mysqli_num_rows($check_files).'):</h1>'.$filesU.'

		<h5>Вы можете <a href="'.$defaultDOMAIN.'/remove-account.php">Удалить свой аккаунт</a>, тем самым удалив все свои данные из QWAKER.fun навсегда!</h5>
				
		</body></html>
	';
	$headers = 'From: no-reply <' . $emailSENDER . ">\r\n" . 'Content-Type: text/html; charset=UTF-8';

	sleep(1);

	if (mail($to, $subject, $message, $headers, "-f" .$emailSENDER)) {

		mysqli_query($connect, "UPDATE `users` SET `date_last_extract`='$timeUSER' WHERE `id`='$user_id'");

		$user_email = $user['email'];
		$length = strpos($user_email, '@') - 2;
		$asterisk = '*';
		for ($i = 1; $i < $length; $i++) {
			$asterisk .= '*'; 
		}
		$user_email = substr_replace($user_email, $asterisk, 1, $length);

		sleep(1);

		echo json_encode(array(
			"id" => "id_extract_success",
			"type" => "success", 
			"task" => "user:extract:success", 
			"camp" => "server", 
			"message" => 'Выписка успешно отправлена на почту "'.$user_email.'"!',
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_extract_error",
			"type" => "error", 
			"task" => "user:extract:error", 
			"camp" => "server", 
			"message" => 'Ошибка. Повторите попытку позже!',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>