<?php
	header('Access-Control-Allow-Origin: *');
	header('Vary: Accept-Encoding, Origin');
	header('Content-Length: 235');
	header('Keep-Alive: timeout=2, max=99');
	header('Access-Control-Allow-Methods: GET');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 604800');
	header('Connection: Keep-Alive');
	header('Content-Type: text/html; charset=utf-8');
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/data.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php'; ?>
<?php
	$token = trim(mysqli_real_escape_string($connect, $_GET['token']));
	$text = trim(mysqli_real_escape_string($connect, $_GET['text']));
	$limit = intval($_GET['limit']);

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
	$search_query = mysqli_query($connect, "SELECT * FROM users WHERE nickname LIKE '%$text%' AND `find_me` = 1 OR name LIKE '%$text%' AND `find_me` = 1 ORDER BY verification DESC LIMIT $limit");

	$num_finds = mysqli_num_rows($search_query);

	echo('[');
	while($row = mysqli_fetch_assoc($search_query)) {
		$num_finds = $num_finds - 1;

		$user_online = intval(1039554000);
		if ($row['show_online'] == 1) {
			$user_online = intval($row['online']);
		}

		$user_avatar = '';
		if ($row['banned'] == 0) {
			$user_avatar = $defaultDOMAINSTORAGE_URL.'/preview.php?url='.str_replace('https://', 'http://', strval($row['avatar'])).'&scale=80';
		}

		echo json_encode(array(
			"id" => intval($row['id']),
			"login" => strval($row['login']),
			"name" => strval($row['name']),
			"avatar" => strval($user_avatar),
			"login" => strval($row['login']),
			"online" => intval($user_online),
			"type" => strval($row['type']),
			"verification" => intval($row['verification'])
		), 128);

		if ($num_finds != 0) {
			echo(',');
		}
	}
	echo(']');
?>