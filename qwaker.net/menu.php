<div class="qak-page-full-screnn" id="qak-page-full-screen-menu">
	<?php
		include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
	?>
	<?php 
		$title_page = $string['title_menu'];

		$usid = $_COOKIE['USID'];

		function httpPost($url, $data) {
		    $curl = curl_init($url);
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    $response = curl_exec($curl);
		    curl_close($curl);
		    return $response;
		}

		$result_user = json_decode(httpPost($default_api.'/user/data-small.php', array("token" => $usid)), true);
	?>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/auth_check.php'; ?>

	<?php if ($usid != '') { ?>
		<h1 class="qak-title-page" onclick="document.getElementById('qak-page-full-screen-menu').remove()">
			<span class="material-symbols-outlined">chevron_left</span>
			<?php echo $string['action_back']; ?>
		</h1>

		<div class="qak-container-user" onclick="window.location = '/user.php?id=<?php echo $result_user['login']; ?>'; event.stopPropagation();">
			<img src="<?php echo $result_user['avatar']; ?>" draggable="false" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
			<div class="other-content">
				<h1><?php echo $result_user['login']; ?></h1>
				<h2><?php echo $result_user['name']; ?></h2>
			</div>
		</div>

		<div class="btn-mn">
			<!-- <button class="menu" onclick="window.location = '/user.php?id=<?php echo $result_user['login']; ?>'; event.stopPropagation();">
				<?php echo $string['action_my_account']; ?>
			</button> -->
			<button class="menu" onclick="window.location = '/edit.php'; event.stopPropagation();">
				<?php echo $string['action_edit']; ?>
			</button>
		</div>

		<ul class="qak-menu-user-menu">
			<!-- <li onclick="window.location = '/edit.php'" title="<?php echo $string['action_edit']; ?>"></li> -->
			<li onclick="openAlertBar('view-archive')" title="<?php echo $string['action_list_archive']; ?>">
				<span class="material-symbols-outlined">archive</span>
			</li>
			<li onclick="openAlertBar('view-black-list')" title="<?php echo $string['action_black_list']; ?>">
				<span class="material-symbols-outlined">recent_actors</span>
			</li>
			<li onclick="openAlertBar('view-deffred-list')" title="<?php echo $string['action_deffred_posts']; ?>">
				<span class="material-symbols-outlined">event_note</span>
			</li>
			<li onclick="openAlertBar('view-my-reports')" title="<?php echo $string['action_my_reports']; ?>">
				<span class="material-symbols-outlined">flag</span>
			</li>
			<hr>
			<li onclick="openAlertBar('view-sessions')" title="<?php echo $string['action_sessions']; ?>">
				<span class="material-symbols-outlined">devices</span>
			</li>
			<li onclick="openAlertBar('view-settings')" title="<?php echo $string['action_settings']; ?>">
				<span class="material-symbols-outlined">settings</span>
			</li>
			<?php if (intval($result_user['rang']) >= 2) { ?>
				<hr>
				<li onclick="window.location = '/admin-panel/index.php'" title="<?php echo $string['action_admin_panel']; ?>">
					<span class="material-symbols-outlined">admin_panel_settings</span>
				</li>
				<hr>
			<?php } ?>
			<li onclick="exitAccount()" class="red" title="<?php echo $string['action_sign_out']; ?>">
				<span class="material-symbols-outlined">power_settings_new</span>
			</li>
		</ul>
	<?php } else { ?>
		<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/content/no-account.php'; ?>
	<?php } ?>
</div>