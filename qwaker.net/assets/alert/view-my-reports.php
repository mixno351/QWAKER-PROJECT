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
?>
<div class="qak-alert-container" id="qak-alert-container-mrep">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_my_reports']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container-mrep').remove()">close</span>
		</h2>
		<div id="qak-alert-list-archive" class="scroll-new">
			<h2 class="qak-alert-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>

		<script type="text/javascript">

			loadMyReports();

			function loadMyReports() {
				$.ajax({type: "GET", url: "/assets/content/list-my-reports.php", data: {ok: 'ok'}, success: function(result) {
						$("#qak-alert-list-archive").empty();
						$("#qak-alert-list-archive").append(result);
					}
				});
			}
		</script>
	</div>
</div>