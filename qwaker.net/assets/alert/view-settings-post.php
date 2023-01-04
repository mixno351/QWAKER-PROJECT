<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	function convertTimePublicPost($originalDate) {
		$day = date("d", strtotime($originalDate));
		$mounth = date("M", strtotime($originalDate));
		$year = date("Y", strtotime($originalDate));
		$hour = date("H", strtotime($originalDate));
		$minute = date("i", strtotime($originalDate));
		$second = date("s", strtotime($originalDate));

		return $day . ' ' . $mounth . ' ' . $year . ' ' . $hour . ':' . $minute;
	}

	$id = $_GET['id'];

	function httpPost($url, $data) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}

	$result_array = json_decode(httpPost($default_api.'/post/edit/settings/data.php', array("token" => $_COOKIE['USID'], "id" => $id)), true);
?>
<div class="qak-alert-container" id="qak-alert-container">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_post_settings']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container').remove()">close</span>
		</h2>
		<div id="qak-alert-list-archive">
			<h4 class="info-alert lr tb">
				<span class="material-symbols-outlined">info</span>
				<?php echo $string['message_post_settings_info']; ?>
			</h4>

			<label class="checkbox-laber alert">
				<div>
					<font><?php echo $string['text_post_settings_commented']; ?></font>
					<font class="mess"><?php echo $string['message_post_settings_commented']; ?></font>
				</div>
				<input type="checkbox" name="" id="checkbox-private" <?php if(intval($result_array['settings']['comments'])==1){echo'checked';} ?>>
			</label>

			<label class="checkbox-laber alert">
				<div>
					<font><?php echo $string['text_post_settings_type_emotion']; ?></font>
					<font class="mess"><?php echo $string['message_post_settings_type_emotion']; ?></font>
				</div>
				<button class="border" onclick="popupWindow('popup-menu-alert')">
					<font id="title-popup-menu">Single</font>
					<ol id="popup-menu-alert" class="popup category scroll-new" style="display: none;">
						<li id="type-em-single" onclick="buildFontTypeEmotion('single')"><?php echo $string['text_type_emotion_single']; ?></li>
						<li id="type-em-multi" onclick="buildFontTypeEmotion('multi')"><?php echo $string['text_type_emotion_multi']; ?></li>
					</ol>
				</button>
			</label>
		</div>

		<script type="text/javascript">
			buildFontTypeEmotion('<?php echo $result_array['settings']['type_emotion']; ?>');
			function buildFontTypeEmotion(argument) {
				document.getElementById('type-em-single').classList.remove('selected');
				document.getElementById('type-em-multi').classList.remove('selected');
				document.getElementById('type-em-' + argument).classList.add('selected');
				document.getElementById('title-popup-menu').textContent = stringOBJ['text_type_emotion_' + argument];
				document.getElementById('title-popup-menu').setAttribute('title', stringOBJ['text_type_emotion_' + argument]); 
			}
		</script>
	</div>
</div>