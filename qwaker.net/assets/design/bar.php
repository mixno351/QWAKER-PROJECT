<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
?>
<header class="qak-bar" id="qak-bar">
	<div class="bar-logo-container" onclick="window.location = '/'">
		<h5 class="title-bar">
			<font class="def-content">Q</font>
			<font class="dop-content">WAKER<small>.fun</small></font>
		</h5>
	</div>
	<!-- <tag>BETA</tag> -->
	<!-- <h6 class="find-user-bar" id="find-user-bar" onclick="openBarTopAlert('find-users', '#find-user-bar')"><?php echo $string_hint_find_users; ?></h6> -->
	<div style="width: -webkit-fill-available; width: -moz-available;"></div>
	
	<div id="content-bar">
		<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
		  <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
		    s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
		    c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
		  <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
		    C22.32,8.481,24.301,9.057,26.013,10.047z">
		    <animateTransform attributeType="xml"
		      attributeName="transform"
		      type="rotate"
		      from="0 20 20"
		      to="360 20 20"
		      dur="0.5s"
		      repeatCount="indefinite"/>
		    </path>
		  </svg>
	</div>

	<script type="text/javascript" id="script-bar-padding">
		document.body.style.paddingTop = 'var(--default-bar-size)';
		document.getElementById('script-bar-padding').remove();
	</script>
	<script type="text/javascript">
		loadBarContent();
		function loadBarContent() {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/content/bar-content.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$("#content-bar").empty();
					$("#content-bar").append(result);
				}
			});
		}

		function openBarTopAlert(argument, argument2) {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/top-bar/'+argument+'.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$(argument2).append(result);
				}
			});
		}

		function openAlertBar(argument) {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/'+argument+'.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$('body').append(result);
				}
			});
		}

		function openArchivePost() {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/view-archive.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$('body').append(result);
				}
			});
		}

		function exitAccount() {
			document.cookie = "USID=" + '' + "; path=/; domain=<?php echo $_SERVER['SERVER_NAME']; ?>; expires=Tue, <?php echo intval(date('d')); ?> <?php echo date('M'); ?> <?php echo intval(date('Y')+1); ?> 00:00:00 GMT";
			window.location.reload();
		}

		function blackList() {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/view-black-list.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$('body').append(result);
				}
			});
		}

		function deffredList() {
			showProgressBar();
			$.ajax({type: "GET", url:  '/assets/alert/view-deffred-list.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$('body').append(result);
				}
			});
		}

		function openPageFullScreen(argument) {
			showProgressBar();
			$.ajax({type: "GET", url:  '/'+argument+'.php', data: "req=ok", success: function(result) {
					hideProgressBar();
					$('body').append(result);
				}
			});
		}
	</script>
</header>