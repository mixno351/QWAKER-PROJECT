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
	$id = trim(mysqli_real_escape_string($connect, $_POST['id']));

	$checkSESSION = mysqli_query($connect, "SELECT * FROM `user_sessions` WHERE `sid` = '$token' LIMIT 1");
	if (mysqli_num_rows($checkSESSION) > 0) {
		$session = mysqli_fetch_assoc($checkSESSION);
		$sessionUTOKEN = $session['utoken'];
		$check_u = mysqli_query($connect, "SELECT * FROM `users` WHERE `token_public` = '$sessionUTOKEN' LIMIT 1");
		if (mysqli_num_rows($check_u) > 0) {
			$sUSER = mysqli_fetch_assoc($check_u);
			$token = $sUSER['token'];
			if ($id == '') {
				$id = $sUSER['login'];
			}
		}
	}
?>
<?php
	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$id' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		$user = mysqli_fetch_assoc($check_user);
		$user_id = intval($user['id']);
	} else {
		$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$id' LIMIT 1");
		if (mysqli_num_rows($check_user) > 0) {
			$user = mysqli_fetch_assoc($check_user);
			$user_id = intval($user['id']);
		} else {
			$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `token` = '$id' LIMIT 1");
			if (mysqli_num_rows($check_user) > 0) {
				$user = mysqli_fetch_assoc($check_user);
				$user_id = intval($user['id']);
			}
		}
	}
?>
<?php
	$check_user2 = mysqli_query($connect, "SELECT * FROM `users` WHERE `token` = '$token' LIMIT 1");
	if (mysqli_num_rows($check_user2) > 0) {
		$user2 = mysqli_fetch_assoc($check_user2);
		$user2_id = intval($user2['id']);

		mysqli_query($connect, "UPDATE `users` SET `online`='$timeUSER' WHERE `id`='$user2_id'");
	}
?>
<?php
	$userLogin = "unknown";
	$userAvatar = "NaN";
	$userName = "Unknown Name";
	$userId = 0;
	$userRang = 1;
	$userTimeOnline = 1023235200;

	if (mysqli_num_rows($check_user) > 0) {
		$userLogin = $user['login'];
		$userAvatar = strval($user['avatar']);
		$userName = $user['name'];
		$userId = $user['id'];
		$userRang = $user['rang'];
		if ($user['private'] == 0 or $user['show_online'] == 1) {
			$userTimeOnline = $user['online'];
		}
	}

	echo json_encode(array(
		"id" => intval($userId),
		"login" => strval($userLogin),
		"name" => strval($userName),
		"avatar" => $defaultDOMAINSTORAGE_URL.'/preview.php?url='.str_replace('https://', 'http://', strval($userAvatar)).'&scale=80',
		"avatar_400" => $defaultDOMAINSTORAGE_URL.'/preview.php?url='.str_replace('https://', 'http://', strval($userAvatar)).'&scale=400',
		"rang" => intval($userRang),
		"timeOnline" => intval($userTimeOnline)
	), 128);
?>