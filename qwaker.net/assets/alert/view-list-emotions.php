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
?>
<div class="qak-alert-container" id="qak-alert-container">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_post_emotions']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container').remove()">close</span>
		</h2>
		<ul class="tablayout-qak-emotions">
			<li id="emotion-all" onclick="loadEmotions('all')" title="<?php echo $string['tooltip_post_emotion_all']; ?>"><?php echo $string['action_show_all_post_emotions']; ?></li>
			<space>
			<li id="emotion-like" onclick="loadEmotions('like')" title="<?php echo $string['tooltip_post_emotion_like']; ?>"><img src="/assets/icons/emotions/like.png"></li>
			<space>
			<li id="emotion-dislike" onclick="loadEmotions('dislike')" title="<?php echo $string['tooltip_post_emotion_dislike']; ?>"><img src="/assets/icons/emotions/dislike.png"></li>
			<space>
			<li id="emotion-heart" onclick="loadEmotions('heart')" title="<?php echo $string['tooltip_post_emotion_heart']; ?>"><img src="/assets/icons/emotions/heart.png"></li>
			<space>
			<li id="emotion-respect" onclick="loadEmotions('respect')" title="<?php echo $string['tooltip_post_emotion_respect']; ?>"><img src="/assets/icons/emotions/respect.png"></li>
			<space>
			<li id="emotion-shit" onclick="loadEmotions('shit')" title="<?php echo $string['tooltip_post_emotion_shit']; ?>"><img src="/assets/icons/emotions/shit.png"></li>
		</ul>
		<div id="qak-alert-list-archive">

		</div>

		<script type="text/javascript">

			loadDataEmotions();
			loadEmotions('all');

			function loadEmotions(argument) {
				$("#qak-alert-list-archive").empty();
				$("#qak-alert-list-archive").append('<h2 class="qak-alert-message">'+stringOBJ['message_please_wait']+'</h2>');
				selectTab(argument);
				$.ajax({type: "POST", url: "/assets/content/list-post-emotions.php", data: {id: '<?php echo $id; ?>', type: argument}, success: function(result) {
						$("#qak-alert-list-archive").empty();
						$("#qak-alert-list-archive").append(result);
					}
				});
			}

			function loadDataEmotions() {
				$.ajax({
					type: "POST", 
					url: "<?php echo $default_api; ?>/post/emotion/data.php", 
					data: {token: '<?php echo $_COOKIE['USID'] ?>', id: '<?php echo $id; ?>'}, 
			    	success: function(result){
						// console.log(result);
						var jsonOBJ = JSON.parse(result);
						document.getElementById('emotion-all').textContent = stringOBJ['action_show_all_post_emotions'].replace('%1s', jsonOBJ['all']);
						document.getElementById('emotion-like').innerHTML += jsonOBJ['like'];
						document.getElementById('emotion-dislike').innerHTML += jsonOBJ['dislike'];
						document.getElementById('emotion-heart').innerHTML += jsonOBJ['heart'];
						document.getElementById('emotion-respect').innerHTML += jsonOBJ['respect'];
						document.getElementById('emotion-shit').innerHTML += jsonOBJ['shit'];
					}
				});
			}

			function selectTab(argument) {
				document.getElementById('emotion-all').classList.remove('selected');
				document.getElementById('emotion-like').classList.remove('selected');
				document.getElementById('emotion-dislike').classList.remove('selected');
				document.getElementById('emotion-heart').classList.remove('selected');
				document.getElementById('emotion-respect').classList.remove('selected');
				document.getElementById('emotion-shit').classList.remove('selected');
				document.getElementById('emotion-' + argument).classList.add('selected');
			}
		</script>
	</div>
</div>