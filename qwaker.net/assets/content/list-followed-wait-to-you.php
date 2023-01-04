<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	function convertTimePopupAlert($originalDate) {
		$day = date("d", strtotime($originalDate));
		$mounth = date("M", strtotime($originalDate));
		$year = date("Y", strtotime($originalDate));
		$hour = date("H", strtotime($originalDate));
		$minute = date("i", strtotime($originalDate));
		$second = date("s", strtotime($originalDate));

		return $day . ' ' . $mounth . ' ' . $year . ' ' . $hour . ':' . $minute;
	}
?>
<?php
	$usid = $_COOKIE['USID'];
	$url_follows_req = $default_api.'/user/follows-req/to-list.php?token='.$usid;
	$result_follows_req = json_decode(file_get_contents($url_follows_req, false), true);
?>
<div class="qak-popup-alert-container scroll-new">
	<?php if (sizeof($result_follows_req) > 0) { ?>
		<?php $num_follows = intval($result_user_alert['user_followed_toyou_wait']); ?>
		<?php foreach($result_follows_req as $key => $value) { ?>
			<?php $num_n = $num_n - 1; ?>

			<div class="qak-popup-alert-item" id="qak-popup-alert-item-<?php echo $value['user_id']; ?>">
				<img src="<?php echo $value['user_avatar']; ?>" class="qak-popup-alert-item-avatar" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
				<div class="qak-popup-alert-item-content">
					<h2 class="qak-popup-alert-item-title">
						<?php echo $string['title_request_new_followed']; ?>
						<!-- <?php if (intval($value['user_verification']) == 1) { ?>
							<verification-user></verification-user>
						<?php } ?> -->
						<divider-title-p-a></divider-title-p-a>
						<font><?php echo convertTimeRus($value['follow_date']); ?></font>
					</h2>
					<h2 class="qak-popup-alert-item-message"><?php echo str_replace('%1s', '@'.$value['user_login'], $string['message_user_follow_to_you']); ?></h2>
					<div class="qak-popup-alert-item-actions">
						<button onclick="goFollowMath(<?php echo $value['user_id']; ?>)"><?php echo $string['action_following_cancel']; ?></button>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
		<h2 class="message_popup"><?php echo $string['message_no_followed_wait_toyou']; ?></h2>
	<?php } ?>
</div>