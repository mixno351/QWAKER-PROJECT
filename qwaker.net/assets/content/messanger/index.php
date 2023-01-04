<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>

<div class="container-bar-messanger-holder">
	<h5><?php echo $string['title_messanger']; ?></h5>
	<div class="small-menu">
		<span class="material-symbols-outlined" title="<?php echo $string['tooltip_messanger_new_chat']; ?>" onclick="popupWindow('popup-new-chat')">add</span>
		<ol class="popup arrow" id="popup-new-chat" style="display: none;">
			<li onclick="newChatAlert()"><?php echo $string['action_messanger_new_chat']; ?></li>
		</ol>
	</div>
</div>

<div id="chats-content">
	<h3 class="message-comment"><?php echo $string['message_please_wait']; ?></h3>
</div>

<script type="text/javascript">
	function loadChats() {
		showProgressBar();
		$.ajax({type: "GET", url: "/assets/content/messanger/content/content-index.php", 
			success: function(result) {
				$("#chats-content").empty();
				$("#chats-content").append(result);
				hideProgressBar();
			}
		});
	}

	function newChatAlert() {
		showProgressBar();
		$.ajax({type: "GET", url: "/assets/alert/view-new-chat.php", success: function(result) {
				$("body").append(result);
				hideProgressBar();
			}
		});
	}

	loadChats();

	document.title = stringOBJ['action_bar_messanger'];
</script>