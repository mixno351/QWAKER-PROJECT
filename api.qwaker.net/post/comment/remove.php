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
	$post_id = intval(trim(mysqli_real_escape_string($connect, $_POST['post_id'])));
	$id = intval(trim(mysqli_real_escape_string($connect, $_POST['id'])));

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
?>
<?php
	$check_cm1 = mysqli_query($connect, "SELECT * FROM `comments` WHERE `id` = '$id' AND `user_id` = '$user2_id' LIMIT 1");
	if (mysqli_num_rows($check_cm1) > 0) {} else {
		echo json_encode(array(
			"id" => "id_post_comment_remove_not_you_comment",
			"type" => "error", 
			"task" => "post:comment:remove", 
			"camp" => "user", 
			"message" => 'You can\'t delete this comment!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (mysqli_query($connect, "DELETE FROM `comments` WHERE `id` = '$id' AND `user_id` = '$user2_id'")) {
		mysqli_query($connect, "DELETE FROM `comments_likes` WHERE `cid` = '$id'");
		echo json_encode(array(
			"id" => "id_post_comment_remove_success",
			"type" => "success", 
			"task" => "post:comment:remove", 
			"camp" => "server", 
			"message" => 'The comment has been successfully deleted!',
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_post_comment_remove_error",
			"type" => "error", 
			"task" => "post:comment:remove", 
			"camp" => "server", 
			"message" => 'We were unable to delete the comment. Try again later...',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>