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
	$login = trim(strtolower(mysqli_real_escape_string($connect, $_POST['login'])));
	$password = trim(mysqli_real_escape_string($connect, $_POST['password']));
	$code = intval($_POST['code']);

	// Проверяем является ли значение "login" пустым, если да - запрещаем дальнейшую проверку....................................................
	if (!trim($login)) {
		echo json_encode(array(
			"id" => "id_login_empty",
			"type" => "error", 
			"task" => "auth:in:login", 
			"camp" => "user", 
			"message" => 'The login cannot be empty!',
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
			"task" => "auth:in:login", 
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
			"task" => "auth:in:login", 
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
			"task" => "auth:in:login", 
			"camp" => "user", 
			"message" => 'The login contains forbidden characters. Allowed characters: a-z0-9_',
			"error_value" => $login,
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
			"task" => "auth:in:password", 
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
			"task" => "auth:in:password", 
			"camp" => "user", 
			"message" => 'The password cannot be shorter than 6 or longer than 50 characters!',
			"error_value" => $passwordMD5,
			"time" => $serverTIME
		), 128);
		exit();
	}




	// Проверяем пользователя, его существование в базе данных...................................................................................
	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$passwordMD5' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		$user = mysqli_fetch_assoc($check_user);
		$user_id = intval($user['id']);

		if (intval($user['banned']) == 1) {
			echo json_encode(array(
				"id" => "id_auth_user_banned",
				"type" => "error", 
				"task" => "auth:in:user-banned", 
				"camp" => "auth", 
				"message" => 'The account is blocked, it is impossible to log in to the account!',
				"token" => 'null',
				"time" => $serverTIME
			), 128);
			exit();
		}

		if (intval($user['email_authorization']) == 1) {
			if (md5($code) != $user['email_authorization_code']) {
				$timeLast = $user['date_last_send_code'] + 300;
				$user_email = $user['email'];
				$length = strpos($user_email, '@') - 2;
				$asterisk = '*';
				for ($i = 1; $i < $length; $i++) {
					$asterisk .= '*'; 
				}
				$user_email = substr_replace($user_email, $asterisk, 1, $length);
				if ($timeUSER < $timeLast) {
					echo json_encode(array(
						"id" => "id_auth_last_error",
						"type" => "success", 
						"task" => "auth:in:success-email", 
						"camp" => "user", 
						"message" => 'The new code can be received again in 5 minutes - <b>'.date("d M Y H:i", $timeLast).'</b>',
						"token" => 'null',
						"time" => $serverTIME
					), 128);
					exit();
				}
				// if (strlen(strval($user['email_authorization_code'])) > 1) {
				// 	echo json_encode(array(
				// 		"id" => "id_auth_code_sended_error",
				// 		"type" => "error", 
				// 		"task" => "auth:in:success-email", 
				// 		"camp" => "user", 
				// 		"message" => 'You have already received the authorization code by email - <b>'.$user_email.'</b>. If it\'s not there, check the Spam folder.',
				// 		"token" => 'null',
				// 		"time" => $serverTIME
				// 	)));
				// 	exit();
				// }

				$code_generate1 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate2 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate3 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate4 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate5 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate6 = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 1);
				$code_generate = $code_generate1.$code_generate2.$code_generate3.$code_generate4.$code_generate5.$code_generate6;
				$code_generateMD5 = md5($code_generate);

				$to = $user['email'];
				$subject = 'Код авторизации';
				$message_result = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/vendor/content/securecode-design.html', true);

				$message = str_replace('%username%', $user['login'], $message_result);
				$message = str_replace('%social%', 'QWAKER.fun', $message);
				$message = str_replace('%year%', date('Y'), $message);
				$message = str_replace('%num1%', $code_generate1, $message);
				$message = str_replace('%num2%', $code_generate2, $message);
				$message = str_replace('%num3%', $code_generate3, $message);
				$message = str_replace('%num4%', $code_generate4, $message);
				$message = str_replace('%num5%', $code_generate5, $message);
				$message = str_replace('%num6%', $code_generate6, $message);
				$message = str_replace('%code%', $code_generate, $message);
				$headers = 'From: no-reply <' . $emailSENDER . ">\r\n" . 'Content-Type: text/html; charset=UTF-8';

				if (mail($to, $subject, $message, $headers, "-f" .$emailSENDER)) {
					if (strlen(strval($code)) >= 1) {
						mysqli_query($connect, "UPDATE `users` SET `date_last_send_code`='$timeUSER' WHERE `id`='$user_id'");
					}
					mysqli_query($connect, "UPDATE `users` SET `email_authorization_code`='$code_generateMD5' WHERE `id`='$user_id'");
					
					echo json_encode(array(
						"id" => "id_auth_in_success_email",
						"type" => "success", 
						"task" => "auth:in:success-email", 
						"camp" => "auth", 
						"message" => 'An email with a code has been sent to <b>'.$user_email.'</b>!',
						"token" => 'null',
						"time" => $serverTIME
					), 128);
				} else {
					echo json_encode(array(
						"id" => "id_auth_in_error_email",
						"type" => "error", 
						"task" => "auth:in:error-email", 
						"camp" => "auth", 
						"message" => 'Two-step authorization is connected to the current account, an error occurred when sending the verification code. Please try again later...',
						"token" => 'null',
						"time" => $serverTIME
					), 128);
				}
				exit();
			}
		}

		mysqli_query($connect, "UPDATE `users` SET `online`='$timeUSER' WHERE `id`='$user_id'");
		mysqli_query($connect, "UPDATE `users` SET `email_authorization_code`=NULL WHERE `id`='$user_id'");
		mysqli_query($connect, "UPDATE `users` SET `email_restore_password_code`=NULL WHERE `id`='$user_id'");

		$uid = $user['id'];
		$utoken = md5($user['token']);
		$timeSession = $timeUSER;
		$timeSessionMax = intval($timeUSER+315300); 
		$sid = substr(str_shuffle(str_repeat("0123456789QWERTYUIOPASDFGHJKLZXCVBNMabcdefghijklmnopqrstuvwxyz", 70)), 0, 70);

		// удаляем все сессии пользователя и создаем одну.
		// mysqli_query($connect, "DELETE FROM `user_sessions` WHERE `uid` = '$uid' AND `utoken` = '$utoken'");
		// sleep(0.1);
		mysqli_query($connect, "INSERT INTO `user_sessions`(`uid`, `utoken`, `time`, `maxtime`, `lasttime`, `uagent`, `uip`, `type`, `sid`) VALUES ('$uid', '$utoken', '$timeSession', '$timeSessionMax', '$timeSession', '$useragent', '$userIP', 'site', '$sid')");

		echo json_encode(array(
			"id" => "id_auth_in_success",
			"type" => "success", 
			"task" => "auth:in:success", 
			"camp" => "auth", 
			"message" => 'You have successfully logged in. Welcome, '.$login.'!',
			"token" => $sid,
			"time" => $serverTIME
		), 128);

		$usAG = $login = trim(mysqli_real_escape_string($connect, $useragent));

		mysqli_query($connect, "INSERT INTO `notifications`(`user_id`, `sender_id`, `type`, `category`, `message`, `message2`, `date_public`) VALUES ('$user_id', 0, 'secure', 'sign-in', '$userIP', '$usAG', '$serverTIME')");
	} else {
		echo json_encode(array(
			"id" => "id_auth_in_error",
			"type" => "error", 
			"task" => "auth:in:error", 
			"camp" => "auth", 
			"message" => 'This user does not exist. Check your username/password and try again!',
			"error_value" => $login." -> ".$passwordMD5,
			"time" => $serverTIME
		), 128);
	}
?>