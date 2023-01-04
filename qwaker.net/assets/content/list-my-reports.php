<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	function getStatusReport($value=1) {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
		if ($value == 1) {
			return $string['report_status_1'];
		} if ($value == 2) {
			return $string['report_status_2'];
		} if ($value == 3) {
			return $string['report_status_3'];
		}
		return $string['report_status_1'];
	}

	function getTypeReport($value='user') {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
		if ($value == 'user') {
			return $string['report_type_user'];
		} if ($value == 'comment') {
			return $string['report_type_comment'];
		} if ($value == 'post') {
			return $string['report_type_post'];
		}
		return $string['report_type_user'];
	}
?>
<?php
	function httpPost($url, $data) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}

	$result_array = json_decode(httpPost($default_api.'/report/list.php', array("token" => $_COOKIE['USID'])), true);
?>
<?php $num_array = intval(sizeof($result_array)); ?>
<?php if (sizeof($result_array) == 0) { ?>
	<h2 class="qak-alert-message"><?php echo $string['message_no_my_reports']; ?></h2>
<?php } else { ?>
	<?php foreach($result_array as $key => $value) { ?>
		<?php
			$comment_result = $value['comment'];
			if (strlen($comment_result) < 5) {
				$comment_result = '<no>'.$string['text_report_you_nocomment'].'</no>';
			}

			if ($value['type'] == 'post') {
				$result_click = 'showAlertPost('.$value['content'].')';
			} if ($value['type'] == 'user') {
				$result_click = 'showUserPopup('.$value['content'].', this.id)';
			}

			$string_m = str_replace("%1s", '<b title="id'.$value['content'].'" onclick="'.$result_click.'">'.getTypeReport($value['type']).'</b>', $string['text_report_report_data']);
			$string_m2 = str_replace("%2s", $string['report_'.$value['category']], $string_m);
		?>

		<div class="qak-item-report">
			<h3 class="title-report">
				<?php echo str_replace("%1s", $value['id'], $string['text_report_title_num']); ?>
				<font class="report-status status<?php echo $value['status']; ?>"><?php echo getStatusReport($value['status']); ?></font>
			</h3>
			<ul class="report-data">
				<li><?php echo str_replace("%1s", convertTimeRus(date('d.m.Y H:i:s', $value['time'])), $string['text_report_time_send']); ?></li>
				<li><?php echo str_replace("%1s", $comment_result, $string['text_report_you_comment']); ?></li>
				<li><?php echo $string_m2; ?></li>
			</ul>
		</div>
	<?php } ?>
<?php } ?>