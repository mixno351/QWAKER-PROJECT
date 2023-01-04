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
	function addMemberChat($uid='', $ctoken='', $conn, $rank=1, $time=1023235200) {
		mysqli_query($conn, "INSERT INTO `chats_members`(`ctoken`, `uid`, `rank`, `time`) VALUES ('$ctoken', '$uid', '$rank', '$time')");
	}
?>
<?php
	$token = trim(mysqli_real_escape_string($connect, $_POST['token']));
	$name = trim(mysqli_real_escape_string($connect, $_POST['name']));
	$description = trim(mysqli_real_escape_string($connect, $_POST['desc']));
	$private = trim(mysqli_real_escape_string($connect, $_POST['private']));
	$members = trim(mysqli_real_escape_string($connect, $_POST['$members']));

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
			"message" => 'The token must be valid!',
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
			"message" => 'The account is blocked, the action cannot be performed!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($user['chat_creating'] == 0) {
		echo json_encode(array(
			"id" => "id_new_chat_creating_error_fun_disable",
			"type" => "error", 
			"task" => "new-chat:creating:error", 
			"camp" => "server", 
			"message" => 'The chat creation feature is not available to you.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (strlen(strval($name)) > 1) {
		if (mb_strlen($name, 'utf8') > 25) {
			echo json_encode(array(
				"id" => "id_new_chat_name_characters",
				"type" => "error", 
				"task" => "new-chat:creating:error", 
				"camp" => "user", 
				"message" => 'The chat name can be empty, and should not exceed 25 characters!',
				"error_value" => $login,
				"time" => $serverTIME
			), 128);
			exit();
		}
	}
	if (strlen(strval($description)) > 1) {
		if (mb_strlen($description, 'utf8') > 120) {
			echo json_encode(array(
				"id" => "id_new_chat_description_characters",
				"type" => "error", 
				"task" => "new-chat:creating:error", 
				"camp" => "user", 
				"message" => 'The chat description can be empty, and should not exceed 120 characters!',
				"error_value" => $login,
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	$token_generate = substr(str_shuffle(str_repeat("0123456789QWERTYUIOPASDFGHJKLZXCVBNMabcdefghijklmnopqrstuvwxyz", 13)), 0, 13);

	if ($private == 'true') {
		$private = 1;
	} if ($private == 'false') {
		$private = 0;
	}

	$colorCHAT = substr(md5(mt_rand()), 0, 6);

	if (mysqli_query($connect, "INSERT INTO `chats`(`token`, `name`, `description`, `cuid`, `time`, `private`, `color`) VALUES ('$token_generate', '$name', '$description', '$user_id', '$timeUSER', '$private', '$colorCHAT')")) {
		addMemberChat($user_id, $token_generate, $connect, 3, $timeUSER);
		echo json_encode(array(
			"id" => "id_new_chat_creating_success",
			"type" => "success", 
			"task" => "new-chat:creating:success", 
			"camp" => "server", 
			"message" => 'A new chat has been successfully created.',
			"token" => $token_generate,
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_new_chat_creating_error",
			"type" => "error", 
			"task" => "new-chat:creating:error", 
			"camp" => "server", 
			"message" => 'We were unable to create a chat, please try again later, if the problem persists - contact the developer.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	echo json_encode(array(
		"id" => "id_unknown_error",
		"type" => "error", 
		"task" => "api:unknown-error", 
		"camp" => "server", 
		"message" => 'Unknown error.',
		"time" => $serverTIME
	), 128);
	exit();
?>