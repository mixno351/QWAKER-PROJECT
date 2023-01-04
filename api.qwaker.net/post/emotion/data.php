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
		}
	}
?>
<?php
	$check_post = mysqli_query($connect, "SELECT * FROM `posts` WHERE `id` = '$id' LIMIT 1");
	if (mysqli_num_rows($check_post) > 0) {
		$post = mysqli_fetch_assoc($check_post);
		$post_id = intval($post['id']);
		$post_user_id = intval($post['user_id']);
		$post_message = strval($post['message']);
		$post_category = intval($post['category']);
	} else {
		$check_post = mysqli_query($connect, "SELECT * FROM `posts` WHERE `post_id` = '$id' LIMIT 1");
		if (mysqli_num_rows($check_post) > 0) {
			$post = mysqli_fetch_assoc($check_post);
			$post_id = intval($post['id']);
			$post_user_id = intval($post['user_id']);
			$post_message = strval($post['message']);
			$post_category = intval($post['category']);
		}
	}

	if (mysqli_num_rows($check_post) > 0) {} else {
		echo "Post null";
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
		echo "User null";
		exit();
	}

	if ($user['banned'] == 1) {
		echo "User banned";
		exit();
	}
?>
<?php
	$emotions_all = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id'"));
	$emotions_like = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id' AND `type` = 'like'"));
	$emotions_dislike = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id' AND `type` = 'dislike'"));
	$emotions_heart = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id' AND `type` = 'heart'"));
	$emotions_respect = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id' AND `type` = 'respect'"));
	$emotions_shit = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `post_emotions` WHERE `pid` = '$post_id' AND `type` = 'shit'"));

	echo json_encode(array(
		"all" => intval($emotions_all),
		"like" => intval($emotions_like),
		"dislike" => intval($emotions_dislike),
		"heart" => intval($emotions_heart),
		"respect" => intval($emotions_respect),
		"shit" => intval($emotions_shit)
	), 128);
?>