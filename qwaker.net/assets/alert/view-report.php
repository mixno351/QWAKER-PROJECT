<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$type = $_GET['type'];
	$content = $_GET['content'];
?>
<div class="qak-alert-container" id="qak-alert-container-report">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_report']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container-report').remove()">close</span>
		</h2>
		<div class="qak-alert-container-data">
			<h4 class="info-alert lr tb">
				<span class="material-symbols-outlined">info</span>
				<?php echo $string['message_report_description']; ?>
			</h4>
			<input type="report" name="report" id="report" autocomplete="nope" placeholder="<?php echo $string['hint_report']; ?>" style="margin: 0 20px;margin-top: 10px;">
			<ul class="select-item">
				<li id="item-1" onclick="selectItem(this.id, 'spam')"><?php echo $string['report_spam']; ?></li>
				<li id="item-2" onclick="selectItem(this.id, 'scam')"><?php echo $string['report_scam']; ?></li>
				<li id="item-3" onclick="selectItem(this.id, 'violence_human')"><?php echo $string['report_violence_human']; ?></li>
				<li id="item-4" onclick="selectItem(this.id, 'violence_animal')"><?php echo $string['report_violence_animal']; ?></li>
				<li id="item-5" onclick="selectItem(this.id, '18plus')"><?php echo $string['report_18plus']; ?></li>
				<li id="item-6" onclick="selectItem(this.id, 'offense')"><?php echo $string['report_offense']; ?></li>
				<li id="item-7" onclick="selectItem(this.id, 'harassment')"><?php echo $string['report_harassment']; ?></li>
				<li id="item-8" onclick="selectItem(this.id, 'ads')"><?php echo $string['report_ads']; ?></li>
				<li id="item-9" onclick="selectItem(this.id, 'lgbt')"><?php echo $string['report_lgbt']; ?></li>
				<li id="item-10" onclick="selectItem(this.id, 'propaganda')"><?php echo $string['report_propaganda']; ?></li>
			</ul>
			<center style="margin-top: 10px;margin-bottom: 10px;" >
				<button onclick="sendReport()"><?php echo $string['action_report_send']; ?></button>
			</center>
		</div>
	</div>

	<script type="text/javascript">
		var report = '';

		function sendReport() {
			if (confirm(stringOBJ['message_report_description_are'])) {
				if (report == '') {
					alert(stringOBJ['message_report_decription_empty']);
				} else {
					showProgressBar();
					$.ajax({
						type: "POST",
						url: "<?php echo $default_api; ?>/report/new.php", 
						data: {token: '<?php echo $_COOKIE['USID']; ?>', type: '<?php echo $type; ?>', content: '<?php echo $content; ?>', category: report, comment: document.getElementById('report').value}, 
				    	success: function(result){
							console.log(result);
							hideProgressBar();
							var jsonOBJ = JSON.parse(result);
							alert(jsonOBJ['message']);
							if (jsonOBJ['type'] == 'success') {
								document.getElementById('qak-alert-container-report').remove();
							}
						}
					});
				}
			}
		}

		function selectItem(argument, argument2) {
			document.getElementById('item-1').classList.remove('selected');
			document.getElementById('item-2').classList.remove('selected');
			document.getElementById('item-3').classList.remove('selected');
			document.getElementById('item-4').classList.remove('selected');
			document.getElementById('item-5').classList.remove('selected');
			document.getElementById('item-6').classList.remove('selected');
			document.getElementById('item-7').classList.remove('selected');
			document.getElementById('item-8').classList.remove('selected');
			document.getElementById('item-9').classList.remove('selected');
			document.getElementById('item-10').classList.remove('selected');
			report = argument2;
			document.getElementById(argument).classList.add('selected');
		}
	</script>
</div>