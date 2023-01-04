<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$id = $_POST['id'];

	function httpPost($url, $data) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}
?>
<?php
	$result_user = json_decode(httpPost($default_api.'/user/data-small.php', array("id" => $id, "token" => $_COOKIE['USID'])), true);
?>
<img src="<?php echo $result_user['avatar']; ?>" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
<h2>
	<?php echo $result_user['login']; ?>
	
</h2>
<h3>
	<?php if (userOnline($result_user['timeOnline'])) { ?>
		<?php echo $string['text_user_online']; ?>
	<?php } else { ?>
		<?php echo str_replace('%1s', showDateOnlineUser($result_user['timeOnline']), $string['text_user_offline']); ?>
	<?php }  ?>
</h3>
<button class="border" onclick="window.location = '/user.php?id=<?php echo $result_user['login']; ?>'"><?php echo $string['action_go_to_profile']; ?></button>