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
	function startsWithNumber($string) {
	    return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
	}
?>
<?php
	$token = trim(mysqli_real_escape_string($connect, $_POST['token']));
	$login = trim(strtolower(mysqli_real_escape_string($connect, $_POST['login'])));

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

	if ($user['verification'] == 1) {
		echo json_encode(array(
			"id" => "id_user_verification_true",
			"type" => "error", 
			"task" => "user:vaerification:true", 
			"camp" => "server", 
			"message" => 'Ваш аккаунт верифицирован. Вам запрещено изменять эти данные!',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>
<?php
	$last_upd_login = intval($user['date_upd_login']);
	if ($user['date_upd_login'] == '') {
		$last_upd_login = intval(time());
	}

	$last_upd_login_new = intval(time() + 604800);
?>
<?php
	if ($last_upd_login > time()) {
		echo json_encode(array(
			"id" => "id_login_time_last_upd",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Вы совсем недавно меняли логин. Следующее изменение логина будет доступно: <b>'.date("d M Y H:i", $last_upd_login).'</b>',
			"error_value" => $login.' | '.$last_upd_login.' | '.$last_upd_login_new,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($login == 'login' or $login == 'unknown' or $login == 'admin' or $login == 'user') {
		echo json_encode(array(
			"id" => "id_login_unsupported",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Данный логин запрещен!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (!trim($login)) {
		echo json_encode(array(
			"id" => "id_login_empty",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Логин не может быть пустым!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (mb_strlen($login, 'utf8') < 3 or mb_strlen($login, 'utf8') > 15) {
		echo json_encode(array(
			"id" => "id_login_characters",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Логин не может быть короче 3 или длинее 15 символов!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (startsWithNumber($login)) {
		echo json_encode(array(
			"id" => "id_no_support_login_contains_first_number_value",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Логин не должен содержать первый символ в виде числа!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if(!preg_match("/^[a-z0-9\d_]+$/", $login)) {
		echo json_encode(array(
			"id" => "id_contains_symbols_no_support",
			"type" => "error", 
			"task" => "user:edit:login", 
			"camp" => "user", 
			"message" => 'Логин содержит запрещенные смволы. Разрешенные символы: [a-z0-9_]!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($user['login'] == $login) {
		$last_upd_login_new = time();
	} else {
		$check_user2 = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' LIMIT 1");
		if (mysqli_num_rows($check_user2) > 0) {
			echo json_encode(array(
				"id" => "id_login_used",
				"type" => "error", 
				"task" => "user:edit:login", 
				"camp" => "user", 
				"message" => "Текущий логин уже используется!",
				"error_value" => $login,
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	if (mysqli_query($connect, "UPDATE `users` SET `nickname`='$login', `login`='$login', `date_upd_login`='$last_upd_login_new' WHERE `id`='$user_id'")) {
		echo json_encode(array(
			"id" => "id_edit_success",
			"type" => "success", 
			"task" => "user:edit:success", 
			"camp" => "server", 
			"message" => 'Изменения успешно сохранены!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_edit_error",
			"type" => "error", 
			"task" => "user:edit:error", 
			"camp" => "server", 
			"message" => 'Нам не удалось сохранить изменения. Повторите попытку позже!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}
?>