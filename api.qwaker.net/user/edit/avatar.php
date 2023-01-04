<?php
	header('Access-Control-Allow-Origin: *');
	header('Vary: Accept-Encoding, Origin');
	header('Keep-Alive: timeout=2, max=99');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 604800');
	header('Connection: Keep-Alive');
	header('Content-Type: text/html; charset=utf-8');
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/data.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/class/SimpleImage.php'; ?>
<?php
	$tp = strval($_POST['type']);
	$token = trim(mysqli_real_escape_string($connect, $_POST['token']));
	$file = $_FILES['file'];

	$maxIMAGESIZE = intval(1); // MB
	$minIMAGESIZEWH = intval(200); // PX
	$maxIMAGESIZEWH = intval(2500); // PX

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

	sleep(1);
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
			"message" => 'Токен должен быть действительным!',
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
			"message" => 'Аккаунт заблокирован, действие не может быть выполненно!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	sleep(1);
?>
<?php

	if ($tp == 'remove') {
		$user_avatar_data = $user['avatar'];
		if (mysqli_query($connect, "UPDATE `users` SET `avatar`=NULL WHERE `id`='$user_id'")) {
			echo json_encode(array(
				"id" => "id_edit_user_avatar_success",
				"type" => "success", 
				"task" => "edit:user:avatar:success", 
				"camp" => "server", 
				"message" => 'Фото профиля успешно удалено!',
				"result" => '',
				"time" => $serverTIME
			), 128);

			$check_ufile = mysqli_query($connect, "SELECT * FROM `uploaded_files` WHERE `full_url` = '$user_avatar_data' LIMIT 1");
			if (mysqli_num_rows($check_ufile)) {
				$ufile = mysqli_fetch_assoc($check_ufile);
				$result_path = str_replace('api.', 'sun.', $_SERVER['DOCUMENT_ROOT']);
				$result_upath = $result_path.$ufile['short_url'];
				if (unlink($result_upath)) {
					mysqli_query($connect, "DELETE FROM `uploaded_files` WHERE `full_url` = '$user_avatar_data'");
				}
			}
			exit();
		} else {
			echo json_encode(array(
				"id" => "id_edit_user_avatar_error",
				"type" => "error", 
				"task" => "edit:user:avatar:error", 
				"camp" => "server", 
				"message" => 'Нам не удалось удалить фото профиля. Попробуйте позже!',
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	$last_upd_avatar_new = intval(1241234);

	if ($user['verification'] == 1) {
		$last_upd_avatar = intval($user['date_upd_avatar']);
		if ($user['date_upd_avatar'] == '') {
			$last_upd_avatar = intval(time());
		}

		$last_upd_avatar_new = intval(time() + 604800);

		if (time() < intval($user['date_upd_avatar'])) {
			echo json_encode(array(
				"id" => "id_edit_user_avatar_error_date",
				"type" => "error", 
				"task" => "edit:user:avatar:error", 
				"camp" => "server", 
				"message" => 'Вы совсем недавно меняли фото профиля. Следующее изменение фото будет доступно: <b>'.date("d M Y H:i", $last_upd_avatar).'</b>',
				"time" => $serverTIME
			), 128);
			exit();
		}
	}

	$fileSIZE = $file['size'][0] / 1024 / 1024;

	$image = new SimpleImage();
	$image->load($file['tmp_name'][0]);
	if (intval($image->getWidth()) < $minIMAGESIZEWH or intval($image->getHeight()) < $minIMAGESIZEWH) {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_smallwh",
			"type" => "error", 
			"task" => "edit:user:avatar:smallwh", 
			"camp" => "user", 
			"message" => 'Высота и ширина изображения не должна быть меньше чем '.$minIMAGESIZEWH.'px.',
			"time" => $serverTIME
		), 128);
		exit();
	}
	if (intval($image->getWidth()) > $maxIMAGESIZEWH or intval($image->getHeight()) > $maxIMAGESIZEWH) {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_bigwh",
			"type" => "error", 
			"task" => "edit:user:avatar:bigwh", 
			"camp" => "user", 
			"message" => 'Высота и ширина изображения не должна быть больше чем '.$maxIMAGESIZEWH.'px.',
			"time" => $serverTIME
		), 128);
		exit();
	}

	sleep(1);

	if ($fileSIZE > $maxIMAGESIZE) {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_maxsize",
			"type" => "error", 
			"task" => "edit:user:avatar:maxsize", 
			"camp" => "user", 
			"message" => 'Изображение слишком тяжелое. Допустимый вес для изображения '.$maxIMAGESIZE.'МБ!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	if ($file['type'][0] == 'image/jpeg' or $file['type'][0] == 'image/jpg' or $file['type'][0] == 'image/png') {} else {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_type",
			"type" => "error", 
			"task" => "edit:user:avatar:type", 
			"camp" => "user", 
			"message" => 'Изображение содержит недопустимый формат!',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$result_path = str_replace($defaultDOMAINSTORAGE_START, $defaultDOMAINSTORAGE_END, $_SERVER['DOCUMENT_ROOT']);

	$newname = $user['login'].'-'.date('YmdHis', time()).rand(10000,999999).'.jpg';
	$newdir = '/avatars/'.$newname;
	$result_newdir = $defaultDOMAINSTORAGE_URL.$newdir;
	$makecryptavatar = md5($result_newdir);
	$cryptedavatar = $domain.'/content/get-avatar.php?i='.$makecryptavatar;

	if (move_uploaded_file($file['tmp_name'][0], $result_path.$newdir)) {} else {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_error",
			"type" => "error", 
			"task" => "edit:user:avatar:error", 
			"camp" => "server", 
			"message" => 'Нам не удалось загрузить новое изображение. Повторите попытку позже!.. [1]',
			"time" => $serverTIME
		), 128);
		exit();
	}

	$small_image_140 = $defaultDOMAINSTORAGE_URL.'/preview.php?url='.str_replace('https://', 'http://', strval($result_newdir)).'&scale=140';

	$user_avatar_data = $user['avatar'];
	if (mysqli_query($connect, "UPDATE `users` SET `avatar`='$result_newdir', `avatar_small`='$small_image_140' WHERE `id`='$user_id'")) {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_success",
			"type" => "success", 
			"task" => "edit:user:avatar:success", 
			"camp" => "server", 
			"message" => 'Фото профиля успешно обновлено!',
			"result" => $result_newdir,
			"result_small" => $small_image_140,
			"time" => $serverTIME
		), 128);
		if ($tp != 'remove') {
			mysqli_query($connect, "UPDATE `users` SET `date_upd_avatar`='$last_upd_avatar_new' WHERE `id`='$user_id'");
		}

		$check_ufile = mysqli_query($connect, "SELECT * FROM `uploaded_files` WHERE `full_url` = '$user_avatar_data' LIMIT 1");
		if (mysqli_num_rows($check_ufile)) {
			$ufile = mysqli_fetch_assoc($check_ufile);
			$result_path = str_replace('api.', 'sun.', $_SERVER['DOCUMENT_ROOT']);
			$result_upath = $result_path.$ufile['short_url'];
			if (unlink($result_upath)) {
				mysqli_query($connect, "DELETE FROM `uploaded_files` WHERE `full_url` = '$user_avatar_data'");
			}
		}

		mysqli_query($connect, "INSERT INTO `uploaded_files`(`crypt`,`uid`, `full_url`, `short_url`, `type`) VALUES ('$makecryptavatar','$user_id', '$result_newdir', '$newdir', 'avatar')");
		exit();
	} else {
		echo json_encode(array(
			"id" => "id_edit_user_avatar_error",
			"type" => "error", 
			"task" => "edit:user:avatar:error", 
			"camp" => "server", 
			"message" => 'Нам не удалось загрузить новое изображение. Повторите попытку позже!.. [2]',
			"time" => $serverTIME
		), 128);
		exit();
	}
?>