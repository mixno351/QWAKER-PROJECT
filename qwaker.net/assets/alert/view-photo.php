<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	$url = $_GET['url'];
	$json = $_GET['json'];
	$jsonKEY = $_GET['key'];
	$type = $_GET['type'];
	if ($type == 'video' or $type == 'image') {} else {
		$type = 'image';
	}
?>
<div class="qak-alert-container preview-photo" id="qak-alert-container-photo">
	<script type="text/javascript">
		var titleDefViewPhoto = document.title;

		document.title = stringOBJ['title_preview_photo'];
	</script>
	<?php if (!trim($_COOKIE['USID'])) { ?>
		<h4 class="qak-alert-message-centered"><?php echo $string['message_no_crop_view_photo_token_empty']; ?></h4>
	<?php } ?>
	<?php if ($type == 'image') { ?>
		<img src="<?php echo $url; ?>" class="qak-alert-container-image-view <?php if (!trim($_COOKIE['USID'])) { echo 'no-crop'; }?>" id="qak-alert-image-preview" draggable="false">
	<?php } ?>
	<?php if ($type == 'video') { ?>
		<?php if (!trim($_COOKIE['USID'])) {} else { ?>
			<div id="video-container">
				<video 
					src="<?php echo $url; ?>" 
					class="qak-alert-video-player <?php if (!trim($_COOKIE['USID'])) { echo 'no-crop'; }?>" 
					id="qak-alert-video-player"
				></video>
			
				<div class="content-video-player-controls">
					<span class="material-symbols-outlined qak-alert-container-action-image-view action" id="control-video-play" onclick="togglePlay()" title="<?php echo $string['tooltip_play_pause']; ?>">play_arrow</span>
					<span class="material-symbols-outlined qak-alert-container-action-image-view small action" id="control-video-mute" onclick="toggleMute()" title="<?php echo $string['tooltip_mute_unmute']; ?>">volume_up</span>
					<span class="material-symbols-outlined qak-alert-container-action-image-view small action" id="control-video-repeat" onclick="toggleRepeat()" title="<?php echo $string['tooltip_repeat']; ?>">repeat</span>
					<div id="control-video-progress">
						<div class="bar" id="control-video-progress-bar"></div>
					</div>
					<h5 id="text-current" class="current qak-alert-container-action-image-view">0:00 / 0:00</h5>
					<span class="material-symbols-outlined qak-alert-container-action-image-view small action" id="control-video-full" onclick="toggleFullScreen()" title="<?php echo $string['tooltip_full']; ?>">fullscreen</span>
				</div>
			</div>

			<svg id="preload-video-player" style="display: none; position: absolute;" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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

			<script type="text/javascript">
				document.title = stringOBJ['title_preview_video'];
				document.getElementById('video-preview').pause();

				var videoPlayer = document.getElementById('qak-alert-video-player');
				var btnPlayPause = document.getElementById('control-video-play');
				var btnPlayMute = document.getElementById('control-video-mute');
				var btnPlayRepeat = document.getElementById('control-video-repeat');
				var progress = document.getElementById('control-video-progress');
				var progressBar = document.getElementById('control-video-progress-bar');
				var textCurrent = document.getElementById('text-current');
				var videoContainer = document.getElementById('video-container');

				videoPlayer.addEventListener("load", function(event) {
					console.log('video player loaded');
				});

				progress.addEventListener('click', function(event) {
					const pos = (event.pageX  - progress.offsetLeft - progress.offsetParent.offsetLeft) / progress.offsetWidth;
					videoPlayer.currentTime = pos * videoPlayer.duration;
				});

				videoPlayer.addEventListener("ended", function(event) {
					updPlayPause();
				});
				videoPlayer.addEventListener("playing", function(event) {
					updPlayPause();
				});
				videoPlayer.addEventListener("pause", function(event) {
					updPlayPause();
				});
				videoPlayer.addEventListener("timeupdate", handleProgress);

				function togglePlay() {
					if (videoPlayer.paused || videoPlayer.ended) {
						videoPlayer.play();
					} else {
						videoPlayer.pause();
					}
				}
				function toggleMute() {
					if (videoPlayer.muted) {
						videoPlayer.muted = false;
					} else {
						videoPlayer.muted = true;
					}
					updMuted();
				}
				function toggleRepeat() {
					if (videoPlayer.loop) {
						videoPlayer.loop = false;
					} else {
						videoPlayer.loop = true;
					}
					updRepeat();
				}

				function toggleFullScreen() {
					if (document.fullscreenElement) {
						document.exitFullscreen();
					} else if (document.webkitFullscreenElement) {
						document.webkitExitFullscreen();
					} else if (videoContainer.webkitRequestFullscreen) {
						videoContainer.webkitRequestFullscreen();
					} else {
						videoContainer.requestFullscreen();
					}
				}

				document.addEventListener("keydown", function(event) {
					if (event.code === "Space") {
						togglePlay();
					}
				});


				function updPlayPause() {
					btnPlayPause.textContent = videoPlayer.paused ? "play_arrow" : "pause";
				}
				function updMuted() {
					btnPlayMute.textContent = videoPlayer.muted ? "volume_off" : "volume_up";
				}
				function updRepeat() {
					btnPlayRepeat.textContent = videoPlayer.loop ? "repeat_one" : "repeat";
				}


				function handleProgress() {
					const progressPercentage = (videoPlayer.currentTime / videoPlayer.duration) * 100;
					progressBar.style.width = `${progressPercentage}%`;
					getTimesCurrent(videoPlayer.currentTime, videoPlayer.duration);
				}

				function getTimesCurrent(currentTime, duration) {
					textCurrent.textContent =  tiempo(currentTime) + " / " + duracion(duration);
				}

				function tiempo(currentTime) {
					totalNumberOfSeconds = Math.floor(currentTime)
					const hours = parseInt( totalNumberOfSeconds / 3600 );
					const minutes = parseInt( (totalNumberOfSeconds - (hours * 3600)) / 60 );
					const seconds = Math.floor((totalNumberOfSeconds - ((hours * 3600) + (minutes * 60))));
					const result = (hours < 10 ?  + hours : hours) + ":" + (minutes < 10 ? "0"  + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
					return result;
				}

				function duracion(duration) {
					totalNumberOfSeconds = Math.floor(duration)
					const hours = parseInt( totalNumberOfSeconds / 3600 );
					const minutes = parseInt( (totalNumberOfSeconds - (hours * 3600)) / 60 );
					const seconds = Math.floor((totalNumberOfSeconds - ((hours * 3600) + (minutes * 60))));
					const result = (hours < 10 ?  + hours : hours) + ":" + (minutes < 10 ? "0"  + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
					return result;
				}


			</script>
			<style>
				svg path, svg rect {
					fill: white;
				}
			</style>
		<?php } ?>
	<?php } ?>
	<div class="qak-alert-contaoner-view-photo-actions">
		<?php if (trim($_COOKIE['USID']) and $type == 'image') { ?>
			<span class="material-symbols-outlined action qak-alert-container-action-image-view" title="<?php echo $string['action_view_photo_download']; ?>" onclick="downloadPhoto()">file_download</span>
		<?php } ?>
		
		<span class="material-symbols-outlined action qak-alert-container-action-image-view" onclick="document.getElementById('qak-alert-container-photo').remove(); document.title=titleDefViewPhoto; <?php if ($type == 'video') { echo 'document.getElementById(\'video-preview\').play()'; } ?>" title="<?php echo $string['action_view_photo_close']; ?>">close</span>
	</div>

	<script type="text/javascript">
		$(".qak-alert-container").hover(function() {
			$(this).find(".qak-alert-container-action-image-view").fadeIn(0);
			$(this).find(".qak-alert-container-multiimage-num").fadeIn(0);
		}, function() {
			$(this).find(".qak-alert-container-action-image-view").fadeOut(200);
			$(this).find(".qak-alert-container-multiimage-num").fadeOut(200);
		});
	</script>
	
	<?php if (sizeof(json_decode($json)) > 1) { ?>
		<h3 class="qak-alert-container-multiimage-num" id="qak-alert-container-multiimage-num">1/<?php echo sizeof(json_decode($json)); ?></h3>
		
		<?php if (!trim($_COOKIE['USID'])) {} else { ?>
			<span class="material-symbols-outlined back qak-alert-container-action-image-view" title="<?php echo $string['action_view_photo_forward']; ?>" onclick="swipeImage('-')">arrow_back</span>
			<span class="material-symbols-outlined next qak-alert-container-action-image-view" title="<?php echo $string['action_view_photo_next']; ?>" onclick="swipeImage('+')">arrow_forward</span>
		<?php } ?>
		
		<script type="text/javascript">
			var jsonIMAGES = JSON.parse('<?php echo $json; ?>');
			var limitJSONIMAGES = <?php echo sizeof(json_decode($json)); ?>;
			var numJSONIMAGE = <?php echo $jsonKEY; ?>;

			var durationFadeImage = 100;

			document.getElementById('qak-alert-container-multiimage-num').textContent = numJSONIMAGE+1 + '/' + limitJSONIMAGES;

			function downloadPhoto() {
				$.fileDownload(document.getElementById('qak-alert-image-preview').src);
			}

			function swipeImage(argument) {
				if (argument == '+') {
					if (numJSONIMAGE == limitJSONIMAGES-1) {} else {
						numJSONIMAGE = numJSONIMAGE + 1;
						// document.getElementById('qak-alert-image-preview').src = jsonIMAGES[numJSONIMAGE];
						$('#qak-alert-image-preview').fadeOut(durationFadeImage, function() {
					        $('#qak-alert-image-preview').attr("src", jsonIMAGES[numJSONIMAGE]);
					        $('#qak-alert-image-preview').fadeIn(durationFadeImage);
					    });
						imageURL = jsonIMAGES[numJSONIMAGE];
						// document.getElementById('qak-alert-container-action-image-view-download').download = document.getElementById('qak-alert-image-preview').src;
						document.getElementById('qak-alert-container-multiimage-num').textContent = numJSONIMAGE+1 + '/' + limitJSONIMAGES;
					}
					return;
				} if (argument == '-') {
					if (numJSONIMAGE == 0) {} else {
						numJSONIMAGE = numJSONIMAGE - 1;
						// document.getElementById('qak-alert-image-preview').src = jsonIMAGES[numJSONIMAGE];
						$('#qak-alert-image-preview').fadeOut(durationFadeImage, function() {
					        $('#qak-alert-image-preview').attr("src", jsonIMAGES[numJSONIMAGE]);
					        $('#qak-alert-image-preview').fadeIn(durationFadeImage);
					    });
						imageURL = jsonIMAGES[numJSONIMAGE];
						// document.getElementById('qak-alert-container-action-image-view-download').download = document.getElementById('qak-alert-image-preview').src;
						document.getElementById('qak-alert-container-multiimage-num').textContent = numJSONIMAGE+1 + '/' + limitJSONIMAGES;
					}
					return;
				}
			}

			if (!viewPhotoRunOne) {
				document.addEventListener('keydown', function(e) {
				    switch (e.keyCode) {
				        case 37:
				            try {
				            	swipeImage('-');
				            } catch (exx) {}
				            break;
				        case 39:
				            try {
				            	swipeImage('+');
				            } catch (exx) {}
				            break;
				    }
				});
			}
		</script>
	<?php } ?>
	<?php if (trim($_COOKIE['USID']) and $type == 'image') { ?>
		<script type="text/javascript">
			var scale = 1;
			var imageURL = '<?php echo $url; ?>';

			$(document).ready(function(){
			    $("#qak-alert-container-photo").on("mousewheel DOMMouseScroll", function (e) {
				    e.preventDefault();
				    var delta = e.delta || e.originalEvent.wheelDelta;
				    var zoomOut;
				    if (delta === undefined) {
				    	delta = e.originalEvent.detail;
				    	zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
				    	zoomOut = !zoomOut;
				    } else {
				    	zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
				    }
				    var touchX = e.type === 'mousemove' ? e.changedTouches[0].pageX : e.pageX;
				    var touchY = e.type === 'mousemove' ? e.changedTouches[0].pageY : e.pageY;
				    // var translateX, translateY;
				    if (zoomOut) {
					    var offsetWidth = $("#qak-alert-image-preview")[0].offsetWidth;
					    var offsetHeight = $("#qak-alert-image-preview")[0].offsetHeight;

					    if (scale == 1 || scale < 1) {
					    	scale = 1;
					    	$("#qak-alert-image-preview")
						        // .css("transform-origin", touchX + 'px ' + touchY + 'px')
						        .css("transform", 'scale(' + scale + ')');
					    } else {
					      	scale = scale - 0.20;
					      	$("#qak-alert-image-preview")
						        // .css("transform-origin", touchX + 'px ' + touchY + 'px')
						        .css("transform", 'scale(' + scale + ')');
					    }

					    if (scale <= 1) {
					      	document.getElementById('qak-alert-image-preview').style.top = '';
					      	document.getElementById('qak-alert-image-preview').style.bottom = '';
					      	document.getElementById('qak-alert-image-preview').style.left = '';
					      	document.getElementById('qak-alert-image-preview').style.right = '';
					    }
				    } else {
					    var offsetWidth = $("#qak-alert-image-preview")[0].offsetWidth;
					    var offsetHeight = $("#qak-alert-image-preview")[0].offsetHeight;

					    if (scale > 7) {} else {
						    scale = scale + 0.20;
							$("#qak-alert-image-preview")
							   // .css("transform-origin", touchX + 'px ' + touchY + 'px')
							   .css("transform", 'scale(' + scale + ')');
					    }

					    if (scale <= 1) {
						    document.getElementById('qak-alert-image-preview').style.top = '';
						    document.getElementById('qak-alert-image-preview').style.bottom = '';
						    document.getElementById('qak-alert-image-preview').style.left = '';
						    document.getElementById('qak-alert-image-preview').style.right = '';
					    }
				    }
			    
			  	});


			});

			function addListeners() {
			    try {
					document.getElementById('qak-alert-image-preview').addEventListener('mousedown', mouseDown, false);
				} catch (exx) {}
			    window.addEventListener('mouseup', mouseUp, false);
			}

			function mouseUp() {
			    window.removeEventListener('mousemove', divMove, true);
			}

			function mouseDown(e) {
			    gMouseDownX = e.clientX;
			    gMouseDownY = e.clientY;

			    var div = document.getElementById('qak-alert-image-preview');

			    let leftPart = "";
			    if(!div.style.left)
			        leftPart+="0px";
			    else
			        leftPart = div.style.left;
			    let leftPos = leftPart.indexOf("px");
			    let leftNumString = leftPart.slice(0, leftPos);
			    gMouseDownOffsetX = gMouseDownX - parseInt(leftNumString,10);

			    let topPart = "";
			    if(!div.style.top)
			        topPart+="0px";
			    else
			        topPart = div.style.top;
			    let topPos = topPart.indexOf("px");
			    let topNumString = topPart.slice(0, topPos);
			    gMouseDownOffsetY = gMouseDownY - parseInt(topNumString,10);

			    window.addEventListener('mousemove', divMove, true);
			}

			function divMove(e){
			    if (scale > 1) {
			    	var div = document.getElementById('qak-alert-image-preview');
				    div.style.position = 'absolute';
				    let topAmount = e.clientY - gMouseDownOffsetY;
				    div.style.top = topAmount + 'px';
				    let leftAmount = e.clientX - gMouseDownOffsetX;
				    div.style.left = leftAmount + 'px';
			    }
			}

			addListeners();
		</script>
	<?php } ?>
	<script type="text/javascript">
		viewPhotoRunOne = true;
	</script>
</div>