<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$id = strval($_GET['id']);
?>
<?php
	$url_post = $default_api.'/post/stat.php?id='.$id.'&token='.$_COOKIE['USID'];
	$result_post = json_decode(file_get_contents($url_post, false), true);
?>
<?php
	function getCategory($value=0) {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
		$value_generate = 'post_category_'.$value;
		$result = $string[$value_generate];
		return $result;
	}
?>
<div class="qak-alert-container" id="qak-alert-container-post-stat">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_post_stat']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container-post-stat').remove()">close</span>
		</h2>
		<div class="qak-alert-list-archive" id="qak-alert-list-archive">
			<h2 class="qak-alert-message" id="qak-alert-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>
		<?php if (sizeof($result_post) > 0) { ?>
			<script type="text/javascript">
				document.getElementById('qak-alert-list-archive').remove();
			</script>
			<ul class="qak-alert-container-data-post">
				<li title="<?php echo $string['tooltip_info_id_post']; ?>"><?php echo str_replace('%1s', $result_post['id'], $string['text_post_stat_id']); ?></li>
				<li title="<?php echo $string['tooltip_info_category']; ?>"><?php echo str_replace('%1s', getCategory($result_post['category']), $string['text_post_stat_category']); ?></li>
				<li title="<?php echo $string['tooltip_info_language']; ?>"><?php echo str_replace('%1s', $result_post['language'], $string['text_post_stat_language']); ?></li>
				<li title="<?php echo $string['tooltip_info_coverage']; ?>"><?php echo str_replace('%1s', $result_post['coverage'], $string['text_post_stat_coverage']); ?></li>
				<li title="<?php echo $string['tooltip_info_views']; ?>"><?php echo str_replace('%1s', $result_post['views'], $string['text_post_stat_views']); ?></li>
				<li title="<?php echo $string['tooltip_info_views_u']; ?>"><?php echo str_replace('%1s', $result_post['views_u'], $string['text_post_stat_views_u']); ?></li>
				<li title="<?php echo $string['tooltip_info_emotions']; ?>"><?php echo str_replace('%1s', $result_post['emotions'], $string['text_post_stat_emotions']); ?></li>
				<li title="<?php echo $string['tooltip_info_comments']; ?>"><?php echo str_replace('%1s', $result_post['comments'], $string['text_post_stat_comments']); ?></li>
				<li title="<?php echo $string['tooltip_info_likes_comments']; ?>"><?php echo str_replace('%1s', $result_post['comments_likes'], $string['text_post_stat_comments_likes']); ?></li>
			</ul>

			<h2 class="qak-alert-container-holder-title-bottom-message">
				<?php echo $string['message_post_stat_description']; ?>
			</h2>
		<?php } else { ?>
			<script type="text/javascript">
				document.getElementById('qak-alert-message').textContent = stringOBJ['message_post_stat_null'];
			</script>
		<?php } ?>
		
	</div>
</div>