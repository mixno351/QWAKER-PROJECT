<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$id = $_GET['id'];
?>
<div class="qak-alert-container" id="qak-alert-container">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title" id="alert-title-qak">
			<?php echo $string['title_follows']; ?>
			<font id="sum-follow"></font>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container').remove()">close</span>
		</h2>
		<div class="qak-alert-list-follows" id="qak-alert-list-follows">
			<h2 class="qak-alert-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>
	</div>

	<script type="text/javascript">
		loadListFollowers();
		function loadListFollowers() {
			$.ajax({type: "POST", url: "/assets/content/list-content-followers.php", data: {id: '<?php echo $id; ?>'}, success: function(result) {
					$("#qak-alert-list-follows").empty();
					$("#qak-alert-list-follows").append(result);
				}
			});
		}
	</script>
</div>