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
	$comment = trim(mysqli_real_escape_string($connect, $_POST['comment']));
	$type = trim(mysqli_real_escape_string($connect, $_POST['type']));
	$category = trim(mysqli_real_escape_string($connect, $_POST['category']));
	$content = trim(mysqli_real_escape_string($connect, $_POST['content']));

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
	$check_user2 = mysqli_query($connect, "SELECT * FROM `users` WHERE `token` = '$token' LIMIT 1");
	if (mysqli_num_rows($check_user2) > 0) {
		$user2 = mysqli_fetch_assoc($check_user2);
		$user2_id = intval($user2['id']);

		mysqli_query($connect, "UPDATE `users` SET `online`='$timeUSER' WHERE `id`='$user2_id'");
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

	if ($user2['banned'] == 1) {
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

	if ($user2['reported'] == 0) {
		echo json_encode(array(
			"id" => "id_user_reported_off",
			"type" => "error", 
			"task" => "report:off", 
			"camp" => "server", 
			"message" => 'You have been banned from sending reports.',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>
<?php
	if ($type == "user" or $type == "post" or $type == "comment") {} else {
		echo json_encode(array(
			"id" => "id_user_reported_unknown_type",
			"type" => "error", 
			"task" => "report:unknown:type", 
			"camp" => "server", 
			"message" => 'You have chosen the wrong type of report.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($type == "user") {
		$content = intval($content);
		if (mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$content' LIMIT 1")) > 0) {} else {
			echo json_encode(array(
				"id" => "id_user_reported_user_error",
				"type" => "error", 
				"task" => "report:error", 
				"camp" => "user", 
				"message" => 'This user does not exist.',
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	if ($type == "post") {
		$content = intval($content);
		if (mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `posts` WHERE `id` = '$content' AND `archive` = 0 LIMIT 1")) > 0) {} else {
			echo json_encode(array(
				"id" => "id_user_reported_post_error",
				"type" => "error", 
				"task" => "report:error", 
				"camp" => "user", 
				"message" => 'This post does not exist.',
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	if ($type == "comment") {
		$content = intval($content);
		if (mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `comments` WHERE `id` = '$content' LIMIT 1")) > 0) {} else {
			echo json_encode(array(
				"id" => "id_user_reported_comment_error",
				"type" => "error", 
				"task" => "report:error", 
				"camp" => "user", 
				"message" => 'This comment does not exist.',
				"time" => $serverTIME
			), 128);
			exit();
		}
	}
?>
<?php
	if (
		$category == "spam" or 
		$category == "scam" or 
		$category == "violence_human" or 
		$category == "violence_animal" or 
		$category == "18plus" or 
		$category == "offense" or 
		$category == "harassment" or 
		$category == "ads" or 
		$category == "propaganda" or 
		$category == "lgbt" or 
		$category == "maral"
	) {} else {
		echo json_encode(array(
			"id" => "id_user_reported_unknown_category",
			"type" => "error", 
			"task" => "report:unknown:category", 
			"camp" => "user", 
			"message" => 'Select the correct complaint category.',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>
<?php
	if (mysqli_query($connect, "INSERT INTO `reports`(`sender`, `category`, `comment`, `time`, `content`, `type`) VALUES ('$user2_id', '$category', '$comment', '$timeUSER', '$content', '$type')")) {
		echo json_encode(array(
			"id" => "id_user_reported_success",
			"type" => "success", 
			"task" => "report:success", 
			"camp" => "server", 
			"message" => 'Report sended!',
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_user_reported_error",
			"type" => "error", 
			"task" => "report:error", 
			"camp" => "server", 
			"message" => 'Failed sending...',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>
<?php
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