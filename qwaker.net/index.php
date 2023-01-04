<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php 
	$title_page = $string['title_home'];

	$posts_type = strval($_GET['act']);
	// $posts_limit = intval($_GET['limit']);
	$posts_limit = intval(50);
	$posts_hashtag = strval($_GET['hashtag']);

	if (intval($posts_limit) < 10) {
		$posts_limit = intval(10);
	}

	if ($posts_type == 'sub' or $posts_type == 'rec') {} else {
		$posts_type = 'sub';
	}
?>
<!DOCTYPE html>
<html lang="<?php echo $langTAG; ?>" class="<?php echo $default_theme; ?>">
<head>
	<title><?php echo $string['title_home']; ?></title>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/style.php'; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>p.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>user.css?v=<?php echo time(); ?>">
	<link rel="shortcut icon" href="/assets/images/qak-favicon-new.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>index.css?v=3">
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/script.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/meta.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/js/u.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/js/p.php'; ?>
</head>
<body>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/auth_check.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/placeholder.php'; ?>
	

	<?php if (intval($_GET['view-post']) != '') { ?>
		<script type="text/javascript" id="goRUNALERT">
			goAlertPost(<?php echo intval($_GET['view-post']); ?>, <?php echo intval($_GET['comment']); ?>);
			document.getElementById('goRUNALERT').remove();
		</script>
	<?php } ?>

	<center style="margin-top: 0px;">
		<center class="home-sticky" id="home-sticky">
			<ul class="tablayout-qak">
				<li id="sub" onclick="openType('sub', posts_limit, '')"><?php echo $string['action_tab_follow']; ?></li>
				<li id="rec" onclick="openType('rec', posts_limit, '')"><?php echo $string['action_tab_recomendation']; ?></li>
			</ul>
		</center>

		<div id="container-data-index">
			<h2 class="qak-index-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>
	</center>

	<script type="text/javascript">
		var posts_type = 'rec';
		var posts_limit = <?php echo $posts_limit; ?>

		function openType(arguments, arguments2, arguments3) {
			try {
				event.stopPropagation();
			} catch (exx) {}
			try {
				event.preventDefault();
			} catch (exx) {}

			if (arguments == 'sub') {
				document.getElementById('sub').classList.add('active');
				document.getElementById('rec').classList.remove('active');
			} if (arguments == 'rec') {
				document.getElementById('rec').classList.add('active');
				document.getElementById('sub').classList.remove('active');
			}
			if (arguments.trim() == '') {
				arguments = 'rec';
			}

			if (arguments2 < 10) {
				arguments2 = 10;
			}

			posts_limit = arguments2;
			posts_type = arguments;
			posts_hashtag = arguments3;

			// if (posts_hashtag == '') {
			// 	try {
			// 		removeParam('hashtag');
			// 	} catch (exx) {}
			// } else {
			// 	updParam('hashtag', arguments3);
			// }
			updParam('act', arguments);
			// updParam('limit', arguments2);
			try {
				document.getElementById('container-data-index').style.opacity = '0.4';
			} catch (exx) {}
			showProgressBar();
			$.ajax({type: "GET", url:  '/post/'+arguments+'.php', data: {limit: posts_limit, hashtag: posts_hashtag}, success: function(result) {
					hideProgressBar();
					$('#container-data-index').empty();
					$('#container-data-index').append(result);
					try {
						document.getElementById('container-data-index').style.opacity = '1';
					} catch (exx) {}
				}
			});
		}
	</script>

	<script type="text/javascript">
		openType('<?php echo $posts_type; ?>', posts_limit, '');
	</script>	

	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/design/bar.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/design/holder.php'; ?>

</body>
</html>