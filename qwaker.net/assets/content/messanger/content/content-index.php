<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	function declOfNum($num, $titles) {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

	function httpPost($url, $data) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}

	$result_array = json_decode(httpPost($default_api.'/messanger/list.php', array("token" => $_COOKIE['USID'])), true);
?>
<?php $num_array = intval(sizeof($result_array)); ?>
<?php if (sizeof($result_array) == 0) { ?>
	<h3 class="message-comment"><?php echo $string['text_messanger_no_chats']; ?></h3>
<?php } else { ?>
	<div class="qak-messanger-chats-scroll scroll-new">
		<?php foreach($result_array as $key => $value) { ?>
			<a href="?id=<?php echo $value['token']; ?>" class="messanger-link">
				<div class="chat-item">
					<div class="c1">
						<h3><?php echo mb_substr($value['name'], 0, 1, 'utf-8'); ?></h3>
					</div>
					<div class="c2">
						<h3><?php echo $value['name']; ?></h3>
						<h4>
							<!-- <?php echo $value['description']; ?> -->
							<?php echo declOfNum(intval($value['members']), array($string['members_1'], $string['members_2'], $string['members_3'])); ?>
						</h4>
					</div>
				</div>
			</a>
		<?php } ?>
	</div>
<?php } ?>