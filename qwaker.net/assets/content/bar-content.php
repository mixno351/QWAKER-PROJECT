<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
?>
<?php
	$usid = $_COOKIE['USID'];

	$url_user_bar = $default_api.'/user/data.php?token='.$usid;
	$result_user_bar = json_decode(file_get_contents($url_user_bar, false), true);
?>
<?php
	function getNumNotify($value='') {
		if ($value > 99) {
			return intval(99).'+';
		} else {
			return intval($value);
		}
	}
?>
<?php if ($_COOKIE['USID'] != '') { ?>
	<div class="qak-bar-menu-content">
		<script type="text/javascript" src="/assets/js/edit/avatar.js"></script>
		<?php if (isMobile() == false) { ?>
			<div class="qak-bar-menu-content-item" onclick="window.location = '/dialog.php'" data-tooltip="<?php echo str_replace('%1s', $result_user_bar['user_dialog_no_read'], $string['tooltip_bar_messages']); ?>">
				<img src="/assets/icons/icons8-secured-letter-30.png" class="qak-bar-menu-content-item">
				<span class="material-symbols-rounded">chat</span>
				<h1 class="qak-bar-menu-content-name"><?php echo $string['action_bar_messanger']; ?></h1>
				<?php if ($result_user_bar['user_dialog_no_read'] > 0) {  ?>
					<h2 class="qak-bar-menu-content-item v2"><?php echo getNumNotify($result_user_bar['user_dialog_no_read']); ?></h2>
				<?php } ?>
			</div>
			<div class="qak-bar-menu-content-item" id="bar-r-c" onclick="openBarTopAlert('followed_wait_toyou', '#bar-r-c')" data-tooltip="<?php echo str_replace('%1s', $result_user_bar['user_followed_toyou_wait'], $string['tooltip_bar_requests']); ?>">
				<!-- <img src="/assets/icons/icons8-followed_wait_toyou-30.png" class="qak-bar-menu-content-item"> -->
				<span class="material-symbols-rounded">person</span>
				<h1 class="qak-bar-menu-content-name"><?php echo $string['action_bar_request']; ?></h1>
				<?php if ($result_user_bar['user_followed_toyou_wait'] > 0) { ?>
					<h2 class="qak-bar-menu-content-item v2" id="qak-bar-top-follow-you-indicator"><?php echo getNumNotify($result_user_bar['user_followed_toyou_wait']); ?></h2>
				<?php } ?>
			</div>
			<div class="qak-bar-menu-content-item" id="bar-n-c" onclick="openBarTopAlert('notifications', '#bar-n-c')" data-tooltip="<?php echo str_replace('%1s', $result_user_bar['user_notifications_r'], $string['tooltip_bar_notifications']); ?>">
				<!-- <img src="/assets/icons/icons8-notifications-30.png" class="qak-bar-menu-content-item"> -->
				<span class="material-symbols-rounded">notifications</span>
				<h1 class="qak-bar-menu-content-name"><?php echo $string['action_bar_notofication']; ?></h1>
				<?php if ($result_user_bar['user_notifications_r'] > 0) { ?>
					<h2 class="qak-bar-menu-content-item v2" id="qak-bar-top-notification-indicator"><?php echo getNumNotify($result_user_bar['user_notifications_r']); ?></h2>
				<?php } ?>
			</div>
			<!-- <div class="qak-bar-menu-content-item" onclick="window.location = '/post/new'" data-tooltip="<?php echo $tooltip_new_post; ?>">
				<img src="/assets/icons/icons8-plus-30.png" class="qak-bar-menu-content-item">
				<h2 class="qak-bar-menu-content-item">0</h2>
			</div> -->
		<?php } ?>
	</div>

	<div class="qak-bar-find-action" id="find-all-bar" onclick="<?php if (isMobile()) { ?>openPageFullScreen('find')<?php } else { ?>openBarTopAlert('find-all', '#find-all-bar')<?php } ?>"></div>

	<div class="qak-bar-avatar-user" onclick="<?php if (isMobile()) { ?>openPageFullScreen('menu')<?php } else { ?>closePopups();popupWindow('bar-top-popup')<?php } ?>">
		<img src="<?php echo $result_user_bar['avatar_small']; ?>" class="qak-bar-avatar-user" id="qak-bar-avatar-user" draggable="false" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
		<!-- <div class="qak-bar-avatar-user-arrow"></div> -->
	</div>

	<ol class="popup arrow bar-top-popup" id="bar-top-popup" style="display: none;">
		<div class="content-user-container" id="content-user-top-popup-container" user-login="<?php echo $result_user_bar['login']; ?>" onclick="window.location = '/user.php?id='+this.getAttribute('user-login')" title="<?php echo $string['tooltip_click_to_go_profile_you']; ?>">
			<div class="c1" onclick="updateAvatarAlert();event.stopPropagation()" title="<?php echo $string['tooltip_click_for_upd_avatar']; ?>">
				<img src="<?php echo $result_user_bar['avatar_small']; ?>" id="content-user-top-popup-avatar" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
			</div>
			<div class="c2">
				<h5 id="content-user-top-popup-login"><?php echo $result_user_bar['login']; ?></h5>
				<h6 id="content-user-top-popup-name"><?php echo $result_user_bar['name']; ?></h6>
			</div>
			<span class="material-symbols-outlined">chevron_right</span>
		</div>
		<!-- <li onclick="window.location = '/user.php?id=<?php echo $result_user_bar['login']; ?>'"><?php echo $string['action_my_account']; ?></li> -->
		<li onclick="window.location = '/edit.php'"><?php echo $string['action_edit']; ?></li>
		<li onclick="openArchivePost()"><?php echo $string['action_list_archive']; ?></li>
		<li onclick="blackList()"><?php echo $string['action_black_list']; ?></li>
		<li onclick="deffredList()"><?php echo $string['action_deffred_posts']; ?></li>
		<li onclick="openAlertBar('view-my-reports')"><?php echo $string['action_my_reports']; ?></li>
		<hr>
		<li onclick="openAlertBar('view-sessions')"><?php echo $string['action_sessions']; ?></li>
		<li onclick="openAlertBar('view-settings')"><?php echo $string['action_settings']; ?></li>
		<li onclick="exitAccount()"><?php echo $string['action_sign_out']; ?></li>
		<?php if (intval($result_user_bar['urang']) >= 2) { ?>
			<hr>
			<li onclick="window.location = '/admin-panel/index.php'"><?php echo $string['action_admin_panel']; ?></li>
		<?php } ?>
	</ol>

	<?php if (isMobile()) { ?>
		<header class="qak-mobile-bar">
			<div class="bitem" onclick="window.location = '/'">
				<!-- <img src="/assets/icons/bar/home.png"> -->
				<span class="material-symbols-outlined">home</span>
			</div>
			<div class="bitem" onclick="openPageFullScreen('requests')">
				<!-- <img src="/assets/icons/bar/requests.png"> -->
				<span class="material-symbols-outlined">person</span>
				<?php if ($result_user_bar['user_followed_toyou_wait'] > 0) { ?>
					<bouble id="qak-bar-top-notification-indicator"><?php echo getNumNotify($result_user_bar['user_followed_toyou_wait']); ?></bouble>
				<?php } ?>
			</div>
			<div class="bitem" onclick="window.location = '/dialog.php'">
				<img src="/assets/icons/bar/messages.png">
				<span class="material-symbols-outlined">forum</span>
				<?php if ($result_user_bar['user_dialog_no_read'] > 0) { ?>
					<bouble id="qak-bar-top-notification-indicator"><?php echo getNumNotify($result_user_bar['user_dialog_no_read']); ?></bouble>
				<?php } ?>
			</div>
			<div class="bitem" onclick="openPageFullScreen('notifications')">
				<!-- <img src="/assets/icons/bar/notifications.png"> -->
				<span class="material-symbols-outlined">notifications</span>
				<?php if ($result_user_bar['user_notifications_r'] > 0) { ?>
					<bouble id="qak-bar-top-notification-indicator"><?php echo getNumNotify($result_user_bar['user_notifications_r']); ?></bouble>
				<?php } ?>
			</div>
		</header>
		<script type="text/javascript">
			document.body.style.paddingBottom = '50px';
		</script>
	<?php } ?>
<?php } else { ?>
	<button class="border qak-user-auth-button-border-null" onclick="openDialogAuth('in')"><?php echo $string['action_sign_in']; ?></button>

	<script type="text/javascript">
		function openDialogAuth(type) {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/view-auth.php', data: {type: type}, success: function(result) {
					hideProgressBar();
					$("body").append(result);
				}
			});
		}
	</script>
<?php } ?>