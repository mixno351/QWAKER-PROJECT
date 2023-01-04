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
	$access_token = trim(mysqli_real_escape_string($connect, $_POST['access_token']));
	$id = intval($_POST['id']);

	$check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$id' AND `access_token` = '$access_token' LIMIT 1");
	if (mysqli_num_rows($check_user) > 0) {
		$user = mysqli_fetch_assoc($check_user);

		echo json_encode(array(
			"type" => "success",
			"time" => $serverTIME
		), 128);
		exit();
	} else {
		echo json_encode(array(
			"type" => "error",
			"time" => $serverTIME
		), 128);
		exit();
	}
?>