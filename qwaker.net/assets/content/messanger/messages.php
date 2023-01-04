<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$token = $_GET['id'];
?>

<div class="messages-screen">
	<div class="container-bar-messanger-holder" id="holder-chat">
		<h5 id="name-chat" class="back" onclick="window.history.back()"><?php echo $string['title_default_name_chat']; ?></h5>
		<h4 id="avatar-chat"><?php echo mb_substr($string['title_default_name_chat'], 0, 1, 'utf-8'); ?></h4>
	</div>

	<div id="messages-content">
		<h3 class="message-comment" id="messages-comment"><?php echo $string['message_please_wait']; ?></h3>
	</div>
</div>

<script type="text/javascript">
	var tokenChat = '<?php echo $token; ?>';

	loadData();

	function loadData() {
		showProgressBar();
		$.ajax({
			type: "POST", 
			url: "<?php echo $default_api; ?>/messanger/data.php", 
			data: {token: '<?php echo $_COOKIE['USID'] ?>', ctoken: tokenChat}, 
	    	success: function(result) {
				console.log(result);
				hideProgressBar();
				var jsonOBJ = JSON.parse(result);
				if (jsonOBJ['type'] == 'error') {
					document.getElementById('messages-comment').textContent = apiDecodeTextMessage(jsonOBJ['id']);
				} if (jsonOBJ['type'] == 'success') {
					try {
						document.getElementById('avatar-chat').textContent = jsonOBJ['data']['name'].substring(0, 1);
					} catch (exx) {}
					try {
						document.getElementById('name-chat').textContent = jsonOBJ['data']['name'];
					} catch (exx) {}
				}
			}
		});
	}
</script>