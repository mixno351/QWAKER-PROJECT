<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$limitChatName = intval(25);
	$limitChatDesc = intval(120);
?>

<div class="qak-alert-container" id="qak-alert-container">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_messanger_new_chat']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container').remove()">close</span>
		</h2>
		<div id="qak-alert-list-archive" class="new-chat">
			<h4 class="info-alert lr tb">
				<span class="material-symbols-outlined">info</span>
				<?php echo $string['message_create_new_chat_info']; ?>
			</h4>

			<input type="name-chat" name="chat" id="chat-name" placeholder="<?php echo $string['hint_enter_chat_name']; ?>" maxlength="<?php echo $limitChatName; ?>" oninput="document.getElementById('new-chat-name-limit').textContent = this.value.length + '/<?php echo $limitChatName; ?>'">
			<h5 class="limit-input" id="new-chat-name-limit">0/<?php echo $limitChatName; ?></h5>
			<input type="desc-chat" name="desc" id="chat-desc" placeholder="<?php echo $string['hint_enter_chat_desc']; ?>" maxlength="<?php echo $limitChatDesc; ?>" oninput="document.getElementById('new-chat-desc-limit').textContent = this.value.length + '/<?php echo $limitChatDesc; ?>'">
			<h5 class="limit-input" id="new-chat-desc-limit">0/<?php echo $limitChatDesc; ?></h5>

			<label class="checkbox-laber">
				<div>
					<font><?php echo $string['text_private_chat']; ?></font>
					<font class="mess"><?php echo $string['message_chat_private_new']; ?></font>
				</div>
				<input type="checkbox" name="" id="checkbox-private">
			</label>

			<center>
				<button onclick="createNewChat('<?php echo $_COOKIE['USID']; ?>', document.getElementById('chat-name').value, document.getElementById('chat-desc').value, document.getElementById('checkbox-private').checked)"><?php echo $string['action_messanger_new_chat']; ?></button>
			</center>
		</div>
	</div>
</div>