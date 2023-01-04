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
	$message = trim(mysqli_real_escape_string($connect, $_POST['message']));
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
	$post_category = 0;

	$check_post = mysqli_query($connect, "SELECT * FROM `posts` WHERE `id` = '$id' LIMIT 1");
	if (mysqli_num_rows($check_post) > 0) {
		$post = mysqli_fetch_assoc($check_post);
		$post_id = intval($post['id']);
		$post_user_id = intval($post['user_id']);
		$post_message = strval($post['message']);
		$post_category = intval($post['category']);
	} else {
		echo json_encode(array(
			"id" => "id_post_empty",
			"type" => "error", 
			"task" => "post:empty", 
			"camp" => "user", 
			"message" => 'There is no such post!',
			"error_value" => $id,
			"time" => $serverTIME
		), 128);
		exit();
	}

	$check_blacklist = mysqli_query($connect, "SELECT * FROM `black_list` WHERE `user_blocker` = '$post_user_id' AND `user_blocked` = '$user2_id' LIMIT 1");
	if (mysqli_num_rows($check_blacklist) > 0) {
		echo json_encode(array(
			"id" => "id_user_blacklist",
			"type" => "error", 
			"task" => "user:blocklist", 
			"camp" => "server", 
			"message" => 'You can\'t continue. The user has added you to the blacklist!',
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

	if ($post['commented'] == 0) {
		echo json_encode(array(
			"id" => "id_post_commented_off",
			"type" => "error", 
			"task" => "post:commented_off", 
			"camp" => "user", 
			"message" => 'It is forbidden to leave new comments here!',
			"error_value" => $id,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (!trim($message)) {
		echo json_encode(array(
			"id" => "id_comment_message_empty",
			"type" => "error", 
			"task" => "comment:new:message", 
			"camp" => "user", 
			"message" => 'The comment should not be empty!',
			"error_value" => $message,
			"time" => $serverTIME
		), 128);
		exit();
	}

	if (mb_strlen($message, 'utf8') < 5 or mb_strlen($message, 'utf8') > 150) {
		echo json_encode(array(
			"id" => "id_comment_message_characters",
			"type" => "error", 
			"task" => "comment:new:message", 
			"camp" => "user", 
			"message" => 'The comment must not exceed 150 or be less than 5 characters!',
			"error_value" => $message,
			"time" => $serverTIME
		), 128);
		exit();
	}

	$result = mysqli_query($connect, "INSERT INTO `comments`(`user_id`, `post_id`, `message`, `date_public`) VALUES ('$user2_id', '$post_id', '$message', '$serverTIME')");

	if ($result) {
		echo json_encode(array(
			"id" => "id_comment_success",
			"type" => "success", 
			"task" => "comment:new:success", 
			"camp" => "server", 
			"message" => 'The comment is published!',
			"error_value" => $id,
			"time" => $serverTIME
		), 128);

		if ($post_user_id == $user2_id) {} else {
			mysqli_query($connect, "INSERT INTO `notifications`(`user_id`, `sender_id`, `type`, `category`, `message`, `message2`, `message3`, `date_public`) VALUES ('$post_user_id', '$user2_id', 'post', 'comment', '$post_id', '$post_message', '$message', '$serverTIME')");
		}

		$check_post_category = mysqli_query($connect, "SELECT * FROM `post_category_favourites` WHERE `uid` = '$user2_id' AND `category` = '$post_category' LIMIT 1");
		if (mysqli_num_rows($check_post_category) > 0) {} else {
			if ($post_category == 0 or $post_category == 999) {} else {
				mysqli_query($connect, "INSERT INTO `post_category_favourites`(`uid`, `category`, `time`) VALUES ('$user2_id', '$post_category', '$timeUSER')");
			}
		}

		// CHECK USERS
		if (preg_match_all('~@+\S+~', $message, $user_link)) {
			$limit_user_noted = 1;
			foreach($user_link[0] as $userNewLink) {
				if ($limit_user_noted !== 0) {
					$nameLink = str_replace('@', '', $userNewLink);
					$messageLink = str_replace($userNewLink.' ', '', $message);
					$check_user_link = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$nameLink' LIMIT 1");
					if (mysqli_num_rows($check_user_link) > 0) {
						$link_user = mysqli_fetch_assoc($check_user_link);
						$limit_user_noted = $limit_user_noted - 1;
						$link_user_id = intval($link_user['id']);
						if ($link_user_id == $user2_id) {} else {
							mysqli_query($connect, "INSERT INTO `notifications`(`user_id`, `sender_id`, `type`, `category`, `message`, `message2`, `message3`, `date_public`) VALUES ('$link_user_id', '$user2_id', 'post', 'comment-reply', '$post_id', '$post_message', '$messageLink', '$serverTIME')");
						}
					}
				}
			}
		}

		exit();
	} else {
		echo json_encode(array(
			"id" => "id_comment_error",
			"type" => "error", 
			"task" => "comment:new:error", 
			"camp" => "server", 
			"message" => 'We were unable to publish your comment!',
			"error_value" => $id,
			"time" => $serverTIME
		), 128);
		exit();
	}
?>