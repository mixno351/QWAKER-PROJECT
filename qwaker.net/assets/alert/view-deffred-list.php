<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<div class="qak-alert-container" id="qak-alert-container">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_deffred_list']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container').remove()">close</span>
		</h2>

		<div id="qak-alert-list-archive">
			<h2 class="qak-alert-message"><?php echo $string['message_please_wait']; ?></h2>
		</div>

		<script type="text/javascript">
			loadListDeff();

			function loadListDeff() {
				$.ajax({type: "GET", url: "/assets/content/list-deffred.php", data: {ok: "ok"}, success: function(result) {
						$("#qak-alert-list-archive").empty();
						$("#qak-alert-list-archive").append(result);
					}
				});
			}

			function goEditPost(argument) {
				window.location = '/post/edit.php?id='+argument;
			}
			function goDeffredPostPublic(argument) {
				if (confirm(stringOBJ['message_deffred_post_public_are'])) {
					$.ajax({
						type: "POST", 
						url: "<?php echo $default_api; ?>/post/deffred-public.php", 
						data: {token: '<?php echo $_COOKIE['USID'] ?>', id: argument}, 
				    	success: function(result){
							// console.log(result);
							var jsonOBJ = JSON.parse(result);
							toast(jsonOBJ['message']);
							if (jsonOBJ['type'] == 'success') {
								document.getElementById('qak-alert-container').remove();
								try {
									openType(posts_type, posts_limit, '');
								} catch (exx) {}
								try {
									loadUserPosts();
								} catch (exx) {}
							}
						}
					});
				}
			}
			function goRemovePost(argument) {
				if (confirm(stringOBJ['message_remove_post_are'])) {
					$.ajax({
						type: "POST", 
						url: "<?php echo $default_api; ?>/post/remove.php", 
						data: {token: '<?php echo $_COOKIE['USID'] ?>', id: argument}, 
				    	success: function(result){
							// console.log(result);
							var jsonOBJ = JSON.parse(result);
							toast(jsonOBJ['message']);
							if (jsonOBJ['type'] == 'success') {
								try {
									document.getElementById('qak-alert-archive-item-'+argument).remove();
								} catch (exx) {}
							}
						}
					});
				}
			}
		</script>
	</div>
</div>