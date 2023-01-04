<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php 
	$title_page = $title_user;
?>
<?php
	$id = $_GET['id'];
	$limit = 1000;
	$hashtag = $_GET['hashtag'];
?>
<?php
	$url_user = $default_api.'/user/data.php?id='.$id.'&token='.$_COOKIE['USID'];
	$result_user = json_decode(file_get_contents($url_user, false), true);

	// $result_text_follow = $action_following;
	// $result_class_follow = '';
	// if ($result_user['user_followed_you'] == 'followed') {
	// 	$result_text_follow = $action_followed;
	// 	$result_class_follow = 'border';
	// } if ($result_user['user_followed_you'] == 'follow_wait') {
	// 	$result_text_follow = $action_follow_wait;
	// 	$result_class_follow = '';
	// }

	// $result_text_blacklist = $action_block;
	// if ($result_user['m_banned'] == 1) {
	// 	$result_text_blacklist = $action_unblock;
	// }

	$result_user_post = $result_user['user_posts'];
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

	function declOfNum($num, $titles) {
	    $cases = array(2, 0, 1, 1, 1, 2);
	    return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
	}
?>
<div>
	<?php if ($result_user['id'] == 'id_user_data_private') { ?>
		<div class="qak-container-data">
			<h2 class="qak-user-no-post">
				<span class="material-symbols-outlined">lock</span>
				<?php echo $string['message_account_private']; ?>
			</h2>
		</div>
		<?php exit(); ?>
	<?php } ?>
	<div class="qak-container-data">
		<script type="text/javascript">
			document.getElementById('qak-user-post-size').textContent = '<?php echo intval($result_user_post); ?>';
		</script>
		<?php if (intval($result_user_post) != 0) { ?>
			


			<div id="d_slutskaya">
				<h2 class="qak-index-message"><?php echo $string['message_please_wait']; ?></h2>
			</div>

			<script type="text/javascript">
				loadPostsIndex(<?php echo $limit; ?>, '<?php echo $hashtag; ?>');

				function loadPostsIndex(posts_limit, posts_hashtag) {
					showProgressBar();
					try {
						document.getElementById('d_slutskaya').style.opacity = '.5';
					} catch (exx) {}
					$.ajax({type: "GET", url:  '/post/list.php', data: {limit: posts_limit, hashtag: posts_hashtag, type: 'list', id: '<?php echo $id; ?>', single: 1}, success: function(result) {
							hideProgressBar();
							$('#d_slutskaya').empty();
							$('#d_slutskaya').append(result);
							try {
								document.getElementById('d_slutskaya').style.opacity = '1';
							} catch (exx) {}
						}
					});
				}
			</script>



		<?php } else { ?>
			<?php if (intval($result_user['you']) == 1) { ?>
				<h2 class="qak-user-no-post"><?php echo $string['message_no_post_you']; ?></h2>
				<button class="border qak-button-post" onclick="document.getElementById('new-post-container-v2').style.display = 'block'"><?php echo $string['action_post_new']; ?></button>
			<?php } else { ?>
				<?php
					if ($_COOKIE['USID'] == '') {
						$r_f = str_replace('%1s', $result_user['login'], $string['message_post_hidden_for_privacy']);
					} else {
						$r_f = $string['message_post_null'];
					}
				?>
				<h2 class="qak-user-no-post">
					<span class="material-symbols-outlined">admin_panel_settings</span>
					<?php echo $r_f; ?>
				</h2>
			<?php } ?>
		<?php } ?>
	</div>
</div>