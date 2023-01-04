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

	function detect_cyr_utf8($content) {
		return preg_match('/[^а-яА-ЯёЁa-zA-Z\s]/u', $content);
	}
?>
<?php
	$login = trim(strtolower(mysqli_real_escape_string($connect, $_POST['login'])));
	$name = trim(mysqli_real_escape_string($connect, $_POST['name']));
	$password = trim(mysqli_real_escape_string($connect, $_POST['password']));
	$invite = trim(mysqli_real_escape_string($connect, $_POST['invite']));

	$inviteMD5 = md5($invite);

	// Проверяем корректность пригласительного кода................................................
	// $check_invite = mysqli_query($connect, "SELECT * FROM `invites` WHERE `invite` = '$inviteMD5' AND `activated` = 0 LIMIT 1");
	// if (mysqli_num_rows($check_invite) > 0) {} else {
	// 	echo json_encode(array(
	// 		"id" => "id_invite_empty",
	// 		"type" => "error", 
	// 		"task" => "auth:invite:empty", 
	// 		"camp" => "user", 
	// 		"message" => 'There is no such invitation code or it has already been activated!',
	// 		"error_value" => $invite,
	// 		"time" => $serverTIME
	// 	), 128);
	// 	exit();
	// }

	// Проверяем является ли значение "login" пустым, если да - запрещаем дальнейшую проверку....................................................
	if (!trim($login)) {
		echo json_encode(array(
			"id" => "id_login_empty",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => 'The login cannot be empty!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($login == 'login' or $login == 'unknown' or $login == 'admin' or $login == 'user' or $login == 'qwaker') {
		echo json_encode(array(
			"id" => "id_login_unsupported",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => 'The login you entered is "'.$login.'" banned for registration!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	// Проверяем количество символов в значении "login", если значение не корректное - запрещаем дальнейшую проверку.............................
	if (mb_strlen($login, 'utf8') < 3 or mb_strlen($login, 'utf8') > 15) {
		echo json_encode(array(
			"id" => "id_login_characters",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => 'The login cannot be shorter than 3 or longer than 15 characters!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	// Проверяем начинается ли значение "login" с цифры, если да - запрещаем дальнейшую проверку.................................................
	if (startsWithNumber($login)) {
		echo json_encode(array(
			"id" => "id_no_support_login_contains_first_number_value",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => 'The login must not contain the first character as a number!',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	// Проверяем значение "login" на запрещённые символы, если такие имеются - запрещаем дальнейшую проверку.....................................
	if(!preg_match("/^[a-z0-9\d_]+$/", $login)) {
		echo json_encode(array(
			"id" => "id_contains_symbols_no_support",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => 'The login contains forbidden characters. Allowed characters: a-z0-9_',
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}

	// Проверяем пользователя, его существование в базе данных...................................................................................
	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		echo json_encode(array(
			"id" => "id_login_used",
			"type" => "error", 
			"task" => "auth:up:login", 
			"camp" => "user", 
			"message" => "The username you entered is already being used by someone. Please come up with another one.",
			"error_value" => $login,
			"time" => $serverTIME
		), 128);
		exit();
	}



	// Проверяем значение "name" если все ок - продолжаем........................................................................................
	if (trim($name) == '' and mb_strlen($name, 'utf8') == 0) {} else {
		if(detect_cyr_utf8($name)) {
			echo json_encode(array(
				"id" => "id_name_cyr_failed",
				"type" => "error", 
				"task" => "auth:up:name", 
				"camp" => "user", 
				"message" => 'The name must not contain forbidden characters. Available characters: A-Z-a-z-А-Я-а-я and a space.',
				"error_value" => $name,
				"time" => $serverTIME
			), 128);
			exit();
		}
		if (mb_strlen($name, 'utf8') < 2 or mb_strlen($name, 'utf8') > 20) {
			echo json_encode(array(
				"id" => "id_name_characters",
				"type" => "error", 
				"task" => "auth:up:name", 
				"camp" => "user", 
				"message" => 'The name cannot be shorter than 2 or longer than 20 characters!',
				"error_value" => $name,
				"time" => $serverTIME
			), 128);
			exit();
		}
	}



	// Проверяем значение "ip" если все ок - продолжаем........................................................................................
	$check_user_ip = mysqli_query($connect, "SELECT * FROM `users` WHERE `ip` = '$userIP' LIMIT 1");
	if (mysqli_num_rows($check_user_ip) > 0) {
		echo json_encode(array(
			"id" => "id_ip_used",
			"type" => "error", 
			"task" => "auth:up:ip", 
			"camp" => "user", 
			"message" => 'An account has already been registered to this IP address!',
			"error_value" => $userIP,
			"time" => $serverTIME
		), 128);
		exit();
	}



	$passwordMD5 = md5($password);



	// Проверяем является ли значение "password" пустым, если да - запрещаем дальнейшую проверку.................................................
	if (!trim($password)) {
		echo json_encode(array(
			"id" => "id_password_empty",
			"type" => "error", 
			"task" => "auth:up:password", 
			"camp" => "user", 
			"message" => 'The password cannot be empty!',
			"error_value" => $passwordMD5,
			"time" => $serverTIME
		), 128);
		exit();
	}

	// Проверяем количество символов в значении "password", если значение не корректное - запрещаем дальнейшую проверку..........................
	if (mb_strlen($password, 'utf8') < 6 or mb_strlen($password, 'utf8') > 50) {
		echo json_encode(array(
			"id" => "id_password_characters",
			"type" => "error", 
			"task" => "auth:up:password", 
			"camp" => "user", 
			"message" => 'The password cannot be shorter than 6 or longer than 50 characters!',
			"error_value" => $passwordMD5,
			"time" => $serverTIME
		), 128);
		exit();
	}



	$token = substr(str_shuffle(str_repeat("0123456789QWERTYUIOPASDFGHJKLZXCVBNMabcdefghijklmnopqrstuvwxyz", 70)), 0, 70);
	$tokenMD5 = md5($token);

	// $generateINVITECHARS = '0123456789QWERTYUIOPASDFGHJKLZXCVBNM';
	// $generateINVITE = substr(str_shuffle(str_repeat($generateINVITECHARS, 4)), 0, 4).'-'.substr(str_shuffle(str_repeat($generateINVITECHARS, 4)), 0, 4).'-'.substr(str_shuffle(str_repeat($generateINVITECHARS, 4)), 0, 4);



	// Регистрируем пользователя.................................................................................................................
	$registation_user = mysqli_query($connect, "INSERT INTO `users`(`nickname`, `login`, `password`, `name`, `language`, `date_registration`, `date_upd_login`, `token`, `token_public`, `online`, `date_last_extract`, `date_last_send_code`, `date_last_restore_password`) VALUES ('$login', '$login', '$passwordMD5', '$name', '$userLANGUAGE', '$serverTIME', '$timeUSER', '$token', '$tokenMD5', '$timeUSER', '$timeUSER', '$timeUSER', '$timeUSER')");
	if ($registation_user) {

		mysqli_query($connect, "UPDATE `invites` SET `date_activated` = '$timeUSER', `activated` = 1, `utoken` = '$tokenMD5' WHERE `invite` = '$inviteMD5'");

		echo json_encode(array(
			"id" => "id_auth_up_success",
			"type" => "success", 
			"task" => "auth:up:success", 
			"camp" => "auth", 
			"message" => 'Registration was successful!',
			"token" => "",
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_auth_up_error",
			"type" => "error", 
			"task" => "auth:up:error", 
			"camp" => "auth", 
			"message" => 'Registration error, please try again later...',
			"error_value" => $login." -> ".$passwordMD5." -> ".$name,
			"time" => $serverTIME
		), 128);
		exit();
	}
?>