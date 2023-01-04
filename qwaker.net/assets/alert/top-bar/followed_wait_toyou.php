<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$usid = $_COOKIE['USID'];
?>

<ol class="popup arrow bar-top-alert request" id="bar-top-alert" onclick="event.stopPropagation()">
	<h3><?php echo $string['title_followed_requests']; ?></h3>
	<center>
		<ul class="tablayout-qak find" style="margin-top: 15px;">
			<li id="it-follow-in" onclick="changeFollowWait('in')"><?php echo $string['action_follow_wait_in']; ?></li>
			<li id="it-follow-to" onclick="changeFollowWait('to')"><?php echo $string['action_follow_wait_to']; ?></li>
		</ul>
	</center>
	<div class="container-followed-wait" id="qak-popup-container-followed-wait">
		<h2 class="message_popup"><?php echo $string['message_please_wait']; ?></h2>
	</div>
	<script type="text/javascript">
		var nmReq = 0;
		var resultNMReq = 0;
		try {
			nmReq = document.getElementById('qak-bar-top-follow-you-indicator').textContent;
		} catch (exx) {}
		var tpFollow = 'in';

		// toast(nmReq);

		function goFollowMath(argument) {
			showProgressBar();
			$.ajax({type: "POST", url: "<?php echo $default_api; ?>/user/follow.php", data: {id: argument, token: '<?php echo $_COOKIE['USID']; ?>'}, success: function(result) {
					// console.log(result);
					hideProgressBar();
					var jsonOBJ = JSON.parse(result);
					if (jsonOBJ['type'] == 'success') {
						changeFollowWait('to');
					} 
					toast(jsonOBJ['message']);
				}
			});
		}

		function goConfirmFollow(argument) {
			event.stopPropagation();
			event.preventDefault();
			$.ajax({type: "POST", url: "<?php echo $default_api; ?>/user/follows-req/confirm.php", data: {id: argument, token: '<?php echo $usid; ?>'}, success: function(result) {
					var jsonOBJ = JSON.parse(result);
					alert(jsonOBJ['message']);
					// console.log(result);
					if (jsonOBJ['type'] == 'success') {
						// document.getElementById('qak-popup-alert-item-'+argument).remove();
						changeFollowWait(tpFollow);
						document.getElementById('bar-r-c').setAttribute('data-tooltip', stringOBJ['tooltip_bar_requests'].replace("%1s", resultNMReq - 1));
						if (nmReq < 2) {
							document.getElementById('qak-bar-top-follow-you-indicator').remove();
						} else {
							document.getElementById('bar-r-c').textContent = resultNMReq - 1;
						}
						nmReq = nmReq - 1;
					}
				}
			});
		}

		function goRejectFollow(argument) {
			event.stopPropagation();
			event.preventDefault();
			if (confirm(stringOBJ['message_realy_reject_request'])) {
				$.ajax({type: "POST", url: "<?php echo $default_api; ?>/user/follows-req/reject.php", data: {id: argument, token: '<?php echo $usid; ?>'}, success: function(result) {
						var jsonOBJ = JSON.parse(result);
						alert(jsonOBJ['message']);
						// console.log(result);
						if (jsonOBJ['type'] == 'success') {
							// document.getElementById('qak-popup-alert-item-'+argument).remove();
							changeFollowWait(tpFollow);
							document.getElementById('bar-r-c').setAttribute('data-tooltip', stringOBJ['tooltip_bar_requests'].replace("%1s", resultNMReq - 1));
							if (nmReq < 2) {
								document.getElementById('qak-bar-top-follow-you-indicator').remove();
							} else {
								document.getElementById('bar-r-c').textContent = resultNMReq - 1;
							}
							nmReq = nmReq - 1;
						}
					}
				});
			}
		}

		function changeFollowWait(argument) {
			loadListFollowWait('list-followed-wait-'+argument+'-you');
			tpFollow = argument;

			document.getElementById('it-follow-in').classList.remove('active');
			document.getElementById('it-follow-to').classList.remove('active');
			if (argument == 'in') {
				document.getElementById('it-follow-in').classList.add('active');
			} if (argument == 'to') {
				document.getElementById('it-follow-to').classList.add('active');
			}
		}

		changeFollowWait('in');

		function loadListFollowWait(argument) {
			document.getElementById('qak-popup-container-followed-wait').style.opacity = ".5";
        	$.ajax({type: "GET", url: "/assets/content/"+argument+".php", data: {req: 'ok'}, success: function(result) {
					$("#qak-popup-container-followed-wait").empty();
					$("#qak-popup-container-followed-wait").append(result);
					document.getElementById('qak-popup-container-followed-wait').style.opacity = "1";
				}
			});
        }
	</script>
</ol>