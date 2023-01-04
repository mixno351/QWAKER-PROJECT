<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php 
	$chatID = strval($_GET['id']);
?>
<!DOCTYPE html>
<html lang="<?php echo $langTAG; ?>" class="<?php echo $default_theme; ?>">
<head>
	<title><?php echo $string['title_home']; ?></title>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/style.php'; ?>
	<link rel="shortcut icon" href="/assets/images/qak-favicon-new.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>chats.css?v=12">
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/script.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/meta.php'; ?>
</head>
<body>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/auth_check.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/vendor/page/placeholder.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/js/messanger-public.php'; ?>

	<script type="module">
		// import { initializeApp } from "https://www.gstatic.com/firebasejs/9.9.4/firebase-app.js";
		// const firebaseConfig = {
		// 	apiKey: "AIzaSyD-2tZAjPUb_S20dM3PByMtaH62PLXRRnA",
		// 	authDomain: "messanger-qwaker.firebaseapp.com",
		// 	projectId: "messanger-qwaker",
		// 	storageBucket: "messanger-qwaker.appspot.com",
		// 	messagingSenderId: "964693157987",
		// 	appId: "1:964693157987:web:6a6a4b819f7d682c85f056"
		// };

		// const app = initializeApp(firebaseConfig);
	</script>

	<div class="container-messanger-center">
		<div class="container-messanger-vertical">
			<div id="container-messanger-data">
				
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function openPageMessanger(page, data) {
			showProgressBar();
			$.ajax({type: "GET", url: "/assets/content/messanger/" + page + ".php", data: data, 
				success: function(result) {
					$("#container-messanger-data").empty();
					$("#container-messanger-data").append(result);
					hideProgressBar();
					try {
                        history.pushState({page: page, params: data}, '', '?id='+data['id']);
                    } catch (exx) {}
				}
			});
		}

		openPageMessanger('index');

		$(document).on('click','a.messanger-link', function(e) {
			e.preventDefault();
			var pageURL = $(this).attr('href');
			openPageMessanger('messages', {id: pageURL.replace("?id=", "")});
		});

		window.addEventListener('popstate', function(e) {
            console.log(window.location.search);
            if (window.location.search == '') {
            	openPageMessanger('index');
            } else {
            	openPageMessanger('messages', {id: window.location.search.replace("?id=", "")});
            }
        });
	</script>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/design/bar.php'; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/assets/design/holder.php'; ?>

</body>
</html>