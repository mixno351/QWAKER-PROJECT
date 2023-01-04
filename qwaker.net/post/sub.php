<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$limit = $_GET['limit'];
	$hashtag = $_GET['hashtag'];
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/design/new-post.php'; ?>

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
		$.ajax({type: "GET", url:  '/post/list.php', data: {limit: posts_limit, hashtag: posts_hashtag, type: 'list-sub'}, success: function(result) {
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