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
		exit();
	}

	if ($user['banned'] == 1) {
		exit();
	}
?>
<?php
	$check_array = mysqli_query($connect, "SELECT * FROM `chats_members` WHERE `uid` = '$user_id' ORDER BY `time` DESC LIMIT 100");

	$num_array = mysqli_num_rows($check_array);

	echo '[';
	while($row = mysqli_fetch_assoc($check_array)) {
		$chatToken = $row['ctoken'];
		$chat = mysqli_query($connect, "SELECT * FROM `chats` WHERE `token` = '$chatToken' LIMIT 1");
		$members = mysqli_query($connect, "SELECT * FROM `chats_members` WHERE `ctoken` = '$chatToken'");

		$chatName = 'Without name';
		$chatDescription = 'Without simple description';
		$chatMembers = 0;
		$chatPrivate = 0;
		$chatColor = "f6f6f6";

		if (mysqli_num_rows($chat) > 0) {
			$chatDATA = mysqli_fetch_assoc($chat);
			if (strlen(strval($chatDATA['name'])) > 1) {
				$chatName = strval($chatDATA['name']);
			} if (strlen(strval($chatDATA['description'])) > 1) {
				$chatDescription = strval($chatDATA['description']);
			}
			$chatMembers = mysqli_num_rows($members);
			$chatPrivate = $chatDATA['private'];
			$chatColor = $chatDATA['color'];
		}

		echo json_encode(array(
			"id" => intval($row['id']),
			"token" => strval($chatDATA['token']),
			"name" => strval($chatName),
			"members" => intval($chatMembers),
			"description" => strval($chatDescription),
			"color" => strval($chatColor)
		), 128);

		$num_array = $num_array - 1;
		if ($num_array != 0) {
			echo(',');
		}
	}
	echo ']';
?>