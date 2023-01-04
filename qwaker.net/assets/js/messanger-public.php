<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
?>
<script type="text/javascript">
	function createNewChat(token, name, description, private, members) {
		showProgressBar();
		$.ajax({
			type: "POST", 
			url: "<?php echo $default_api; ?>/messanger/new-chat.php", 
			data: {token: token, name: name, desc: description, private: private, members: members}, 
	    	success: function(result) {
				console.log(result);
				hideProgressBar();
				var jsonOBJ = JSON.parse(result);
				
				toast(apiDecodeTextMessage(jsonOBJ['id']));
				if (jsonOBJ['type'] == 'success') {
					try {
						document.getElementById('qak-alert-container').remove();
					} catch (exx) {}
					try {
						loadChats();
					} catch (exx) {}
				}
			}
		});
	}
</script>