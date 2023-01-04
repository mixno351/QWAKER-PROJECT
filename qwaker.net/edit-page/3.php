<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';

	$url_user = $default_api.'/user/edit/data.php?token='.$_COOKIE['USID'];
	$result_user = json_decode(file_get_contents($url_user, false), true);
?>
<h3 class="qak-edit-title"><?php echo $string['edit_title_3']; ?></h3>
<div class="qak-container-data">

	<h2 class="message-category info" style="margin-bottom: 20px;">
		<span class="material-symbols-outlined">info</span>
		<?php echo $string['message_edit_account']; ?>
	</h2>

	<div class="qak-sign-container">
		<h4 class="qak-short-hint"><?php echo $string['hint_edit_login']; ?></h4>
		<input type="login" name="login" id="login" class="qak-input" autocomplete="off" placeholder="<?php echo $string['hint_edit_login']; ?>" value="<?php echo $result_user['login']; ?>">
		<h4 class="qak-short-hint message">
			<span class="material-symbols-outlined">info</span>
			<?php echo str_replace('%1s', $result_user['login'], $string['message_edit_login']); ?>
		</h4>
	</div>
	<div class="qak-cs">
		<div style="width: 100%;"></div>
		<button onclick="saveProf()"><?php echo $string['action_save']; ?></button>
	</div>
</div>
<!-- <div class="qak-container-data">
	<div class="qak-sign-container check">
		<div style="width: 100%;">
			<h4 class="qak-short-hint title"><?php echo $string['hint_edit_verification_account']; ?></h4>
			<h4 class="qak-short-hint message sub"><?php echo $string['message_edit_verification_account']; ?></h4>
		</div>
		<button class="go" onclick="verifyProfile()" title="<?php echo $string['action_verification_profile']; ?>">
			<span class="material-symbols-outlined">chevron_right</span>
		</button>
	</div>
</div> -->
<div class="qak-container-data">
	<div class="qak-sign-container check">
		<div style="width: 100%;">
			<h4 class="qak-short-hint title"><?php echo $string['setting_title_user_extract']; ?></h4>
			<h4 class="qak-short-hint message sub"><?php echo $string['setting_message_user_extract']; ?></h4>
		</div>
		<button class="go" id="ex-btn" onclick="getExtract(this.id)" title="<?php echo $string['setting_title_user_extract']; ?>">
			<span class="material-symbols-outlined">chevron_right</span>
		</button>
	</div>
</div>
<script type="text/javascript">
	function saveProf() {
		var login = document.getElementById('login').value;
		showProgressBar();
		$.ajax({
			type: "POST", 
			url: "<?php echo $default_api; ?>/user/edit/prof.php", 
			data: {token: '<?php echo $_COOKIE['USID'] ?>', login: login}, 
	    	success: function(result){
				// console.log(result);
				hideProgressBar();
				var jsonOBJ = JSON.parse(result);
				toast(jsonOBJ['message']);
				if (jsonOBJ['type'] == 'success') {
					// alert(jsonOBJ['message']);
					// name = jsonOBJ['name_new'];
					// email = jsonOBJ['email_new'];
					try {
						loadBarContent();
					} catch (exx) {}
				}
			}
		});
	}

	function getExtract(argument) {
		showProgressBar();
		$.ajax({
			type: "POST", 
			url: "<?php echo $default_api; ?>/user/extract.php", 
			data: {
				token: '<?php echo $_COOKIE['USID']; ?>'
			}, 
	    	success: function(result){
				var jsonOBJ = JSON.parse(result);
				hideProgressBar();
				alert(jsonOBJ['message']);
			}
		});
	}

	function verifyProfile() {
		window.location = '/verification.php';
	}
</script>