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
	$login = trim(mysqli_real_escape_string($connect, $_POST['login']));
?>
<?php
	if (trim($login) == '' and mb_strlen($login, 'utf8') == 0) {} else {
		if (mb_strlen($login, 'utf8') < 8 or mb_strlen($login, 'utf8') > 100) {
			echo json_encode(array(
				"id" => "id_email_characters",
				"type" => "error", 
				"task" => "auth:restore-pass:email", 
				"camp" => "user", 
				"message" => 'Почта не должна быть короче 8 или длинее 100 символов!',
				"error_value" => $email,
				"time" => $serverTIME
			), 128);
			exit();
		}
		if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(array(
				"id" => "id_email_unvalid",
				"type" => "error", 
				"task" => "auth:restore-pass:email", 
				"camp" => "user", 
				"message" => 'The email address is incorrect. Acceptable mail format - email@domain.com',
				"error_value" => $login,
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `email` = '$login' AND `type_auth` = 'site' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		$user = mysqli_fetch_assoc($check_user);
		$user_id = intval($user['id']);
	} else {
		echo json_encode(array(
			"id" => "id_user_unknown",
			"type" => "error", 
			"task" => "user:unknown", 
			"camp" => "user", 
			"message" => 'There is no account with the current email address.',
			"error_value" => $login,
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
			"message" => 'The account is blocked, the action cannot be performed!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$timeLast = $user['date_last_restore_password'] + 86400;
	if ($timeUSER < $timeLast) {
		echo json_encode(array(
			"id" => "id_secure_last_error",
			"type" => "error", 
			"task" => "user:secure:date-last", 
			"camp" => "user", 
			"message" => 'You have already received an email to recover your password - <b>'.date("d.m.Y H:i", $user['date_last_restore_password']).'</b>. A new password recovery email can be sent once a day. The following email will be available - <b>'.date("d.m.Y H:i", $timeLast).'</b>',
			"error_value" => null,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array(
			"id" => "id_secure_email_unvalid",
			"type" => "error", 
			"task" => "user:secure:unvalid_email", 
			"camp" => "server", 
			"message" => 'The owner of this account did not specify the main mail or specified incorrectly. It is impossible to restore access to the account.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($user['restore_password'] == 0) {
		echo json_encode(array(
			"id" => "id_secure_restore_off",
			"type" => "error", 
			"task" => "user:secure:restore-off", 
			"camp" => "server", 
			"message" => 'The account owner has disabled the ability to restore the password.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$code_generate = substr(str_shuffle(str_repeat("0123456789QWERTYUIOPASDFGHJKLZXCVBNMabcdefghijklmnopqrstuvwxyz", 30)), 0, 30);
	$code_generateMD5 = md5($code_generate);

	$to = $user['email'];
	$subject = 'Восстановление пароля';
	$link = 'https://'.str_replace('api.', '', $_SERVER['SERVER_NAME']).'/restore-password.php?code='.$code_generate;
	$message_result = '<html style="
				    padding: 20px;
				    margin: 0;
				    width: -webkit-fill-available;
				    background: lightgray;
				">
				<head>
					<meta name="viewport" content="width=device-width, initial-scale=1">
				</head>
				<body style="
				    padding: 20px;
				    margin: 0;
				    width: -webkit-fill-available;
				    background: white;
				    border-radius: 10px;
				    box-shadow: 0 10px 30px rgba(0,0,0,.1);
				"><font style="text-align:center;width: -webkit-fill-available;padding: 0;margin: 0;display: block;font-weight: 600;font-family: system-ui;font-size: 17px;">Your password recovery link<a href="%1s" style="
				    font-size: 13px;
				    font-weight: 500;
				    font-family: system-ui;
				    cursor: pointer;
				    margin: 10px;
				    display: block;
				">Click if you want to restore your password</a><font style="font-size: 12px;font-family: system-ui;font-weight: 500;opacity: .5;display: block;">An attempt was made to recover the password for the account "%2s", if it was not you, we recommend changing the mailbox. Do not tell anyone the password recovery code and the link!</font>
				    <font style="
				    display: block;
				    margin-top: 20px;
				    font-weight: 500;
				    font-family: system-ui;
				    font-size: 14px;
				    opacity: .8;
				">The link and the code are valid once</font>
				<font style="
				    font-size: 12px;
				    opacity: .4;
				    display: block;
				    margin-top: 20px;
				    padding-top: 20px;
				    border-top: 1px solid rgba(0,0,0,.2);
				">Sending time: %3s</font></font>
				</body></html>';

	$message = str_replace('%1s', $link, $message_result);
	$message = str_replace('%2s', $user['login'], $message);
	$message = str_replace('%3s', $serverTIME, $message);
	$headers = 'From: no-reply <' . $emailSENDER . ">\r\n" . 'Content-Type: text/html; charset=UTF-8';

	if (mail($to, $subject, $message, $headers, "-f" .$emailSENDER)) {
		mysqli_query($connect, "UPDATE `users` SET `date_last_restore_password`='$timeUSER' WHERE `id`='$user_id'");
		mysqli_query($connect, "UPDATE `users` SET `email_restore_password_code`='$code_generateMD5', `date_upd_restore_password`='$timeUSER' WHERE `id`='$user_id'");
		$user_email = $user['email'];
		$length = strpos($user_email, '@') - 2;
		$asterisk = '*';
		for ($i = 1; $i < $length; $i++) {
			$asterisk .= '*'; 
		}
		$user_email = substr_replace($user_email, $asterisk, 1, $length);
		echo json_encode(array(
			"id" => "id_user_secure_restorepass_success_email",
			"type" => "success", 
			"task" => "user:secure:restore-pass:success-email", 
			"camp" => "restore-pass", 
			"message" => 'To the mail "'.$user_email.'" a password recovery email has been sent!',
			"token" => 'null',
			"time" => $serverTIME
		), 128);
	} else {
		echo json_encode(array(
			"id" => "id_user_secure_restorepass_error_email",
			"type" => "error", 
			"task" => "user:secure:restore-pass:error-email", 
			"camp" => "restore-pass", 
			"message" => 'We were unable to send a password recovery email. Try again later...',
			"token" => 'null',
			"time" => $serverTIME
		), 128);
	}
	exit();
?>