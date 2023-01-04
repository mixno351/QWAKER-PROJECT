<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$type = $_GET['type'];
	if ($type == 'in' or $type == 'up') {} else {
		$type = 'in';
	}
?>
<div class="qak-alert-container" id="qak-alert-container-auth">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<ul class="tablayout-qak-alert-title">
				<li id="alert-auth-in" onclick="openTabAuthAl('in')"><?php echo $string['action_sign_in'] ?></li>
				<li id="alert-auth-up" onclick="openTabAuthAl('up')"><?php echo $string['action_sign_up'] ?></li>
			</ul>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container-auth').remove()">close</span>
		</h2>

		<div id="qak-alert-auth-cont">
			<h2 class="qak-alert-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>

		<script type="text/javascript">
			openTabAuthAl('<?php echo $type; ?>');
			function openTabAuthAl(argument) {
				try {
					document.getElementById('qak-alert-auth-cont').style.opacity = '.5';
				} catch (exx) {}
				$.ajax({type: "GET", url:  '/assets/auth/'+argument+'.php', data: {}, success: function(result) {
						hideProgressBar();
						$('#qak-alert-auth-cont').empty();
						$('#qak-alert-auth-cont').append(result);
						try {
							document.getElementById('qak-alert-auth-cont').style.opacity = '1';
						} catch (exx) {}
						document.getElementById('alert-auth-in').classList.remove('selected');
						document.getElementById('alert-auth-up').classList.remove('selected');
						document.getElementById('alert-auth-' + argument).classList.add('selected');
					}
				});
			}
		</script>
	</div>
</div>