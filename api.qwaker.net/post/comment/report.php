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
	$id = intval($_POST['id']);
	$data = trim(mysqli_real_escape_string($connect, $_POST['data']));
	$message = trim(mysqli_real_escape_string($connect, $_POST['message']));

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
	$check_comment = mysqli_query($connect, "SELECT * FROM `comments` WHERE `id` = '$id' LIMIT 1");
	if (mysqli_num_rows($check_comment) > 0) {
		$comment = mysqli_fetch_assoc($check_comment);
		$comment_id = intval($comment['id']);
		$comment_user_id = intval($comment['user_id']);
		$comment_message = strval($comment['message']);
	} else {
		echo json_encode(array(
			"id" => "id_comment_empty",
			"type" => "error", 
			"task" => "comment:empty", 
			"camp" => "user", 
			"message" => 'There is no such comment!',
			"error_value" => $id,
			"time" => $serverTIME
		), 128);
		exit();
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

	if ($user['report_comments'] == 0) {
		echo json_encode(array(
			"id" => "id_user_report_comment",
			"type" => "error", 
			"task" => "user:report-comment", 
			"camp" => "server", 
			"message" => 'You are not allowed to send new complaints. Contact support!',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>
<?php
	$check_report = mysqli_query($connect, "SELECT * FROM `reports_comments` WHERE `user_id` = '$user_id' AND `comment_id` = '$comment_id' LIMIT 1");
	if (mysqli_num_rows($check_report) > 0) {
		echo json_encode(array(
			"id" => "id_comment_report_sended",
			"type" => "error", 
			"task" => "comment:report:sended", 
			"camp" => "server", 
			"message" => 'You have already complained about this comment!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($data == 'spam' or $data == 'scam' or $data == 'offense' or $data == 'harassment' or $data == 'ads' or $data == 'lgbt' or $data == 'maral') {} else {
		echo json_encode(array(
			"id" => "id_comment_report_data",
			"type" => "error", 
			"task" => "comment:report:data", 
			"camp" => "server", 
			"message" => 'The subject of the complaint was chosen incorrectly. Try to choose another one!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$public_report = mysqli_query($connect, "INSERT INTO `reports_comments`(`comment_id`, `user_id`, `message`, `data`, `date_reported`, `comment_message`) VALUES ('$comment_id', '$user_id', '$message', '$data', '$serverTIME', '$comment_message')");
	if ($public_report) {
		echo json_encode(array(
			"id" => "id_comment_report_success",
			"type" => "success", 
			"task" => "comment:report:success", 
			"camp" => "server", 
			"message" => 'The complaint has been successfully sent. Expect a response from the administration!',
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_comment_report_error",
			"type" => "error", 
			"task" => "comment:report:error", 
			"camp" => "server", 
			"message" => 'We were unable to send a complaint about this comment. Try again later...',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>