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
	$ctoken = trim(mysqli_real_escape_string($connect, $_POST['ctoken']));

	$arrayData = array();

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
			"id" => "id_data_messanger_unknown_user",
			"type" => "error",
			"message" => "Unknown user.",
			"data" => json_encode($arrayData, 128)
		), 128);
		exit();
	}

	if ($user['banned'] == 1) {
		echo json_encode(array(
			"id" => "id_data_messanger_acc_ban",
			"type" => "error",
			"message" => "You account banned.",
			"data" => json_encode($arrayData, 128)
		), 128);
		exit();
	}
?>
<?php
	$check_chat_member = mysqli_query($connect, "SELECT * FROM `chats_members` WHERE `uid` = '$user_id' AND `ctoken` = '$ctoken' LIMIT 1");
	if (mysqli_num_rows($check_chat_member) > 0) {} else {
		echo json_encode(array(
			"id" => "id_data_messanger_no_member",
			"type" => "error",
			"message" => "You are not a chat member.",
			"data" => json_encode($arrayData, 128)
		), 128);
		exit();
	}

	$chat = mysqli_query($connect, "SELECT * FROM `chats` WHERE `token` = '$ctoken' LIMIT 1");
	if (mysqli_num_rows($chat) > 0) {} else {
		echo json_encode(array(
			"id" => "id_data_messanger_no_chat",
			"type" => "error",
			"message" => "There is no such chat.",
			"data" => json_encode($arrayData, 128)
		), 128);
		exit();
	}

	$check_chat_members = mysqli_query($connect, "SELECT * FROM `chats_members` WHERE  `ctoken` = '$ctoken'");

	$data = mysqli_fetch_assoc($chat);

	$chatName = 'Without name';
	$chatDescription = 'Without simple description';

	if (strlen(strval($data['name'])) > 1) {
		$chatName = strval($data['name']);
	} if (strlen(strval($data['description'])) > 1) {
		$chatDescription = strval($data['description']);
	}

	$arrayData = array(
		"id" => intval($data['id']),
		"token" => strval($data['token']),
		"name" => strval($chatName),
		"members" => intval(mysqli_num_rows($check_chat_members)),
		"description" => strval($chatDescription),
		"color" => strval($data['color'])
	);

	echo json_encode(array(
		"id" => "id_data_messanger_success",
		"type" => "success",
		"message" => "Success.",
		"data" => json_decode(json_encode($arrayData, 128), true)
	), 128);
?>