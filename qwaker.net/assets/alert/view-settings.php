<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
	include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
	function getLanguageSite() {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';

		switch ($_COOKIE['lang']) {
			case 'en':
				return $string['setting_language_en'];
			case 'ru':
				return $string['setting_language_ru'];
			case 'by':
				return $string['setting_language_by'];
			
			default:
				return $string['setting_language_en'];
		}
	}
?>
<?php
	function getColorSchemeSite() {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';

		switch ($_COOKIE['color-scheme']) {
			case 'theme-default-dark':
				return $string['setting_color_scheme_dark'];
			case 'theme-default-light':
				return $string['setting_color_scheme_light'];
			case 'auto':
				return $string['setting_color_scheme_auto'];
			
			default:
				return $string['setting_color_scheme_light'];
		}
	}
?>
<?php
	function getLoadDialogsBottom() {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';

		switch ($_COOKIE['load-dialogs-b']) {
			case 'true':
				return $string['text_true_on'];
			case 'false':
				return $string['text_false_off'];
			
			default:
				return $string['text_false_off'];
		}
	}

	function getHidePopupRecText() {
		include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';

		switch ($_COOKIE['hide-popup-rec']) {
			case 'true':
				return $string['text_false_off'];
			case 'false':
				return $string['text_true_on'];
			
			default:
				return $string['text_true_on'];
		}
	}
?>
<div class="qak-alert-container" id="qak-alert-container-settings">
	<div class="qak-alert-container-holder">
		<h2 class="qak-alert-container-holder-title">
			<?php echo $string['title_settings']; ?>
			<span class="material-symbols-outlined close" onclick="document.getElementById('qak-alert-container-settings').remove()">close</span>
		</h2>
		
		<div class="qak-alert-settings-container">

			<h4 class="info-alert lr tb">
				<span class="material-symbols-outlined">info</span>
				<?php echo $string['message_settings']; ?>
			</h4>
			
			<div class="qak-alert-container-settings-item">
				<div class="v1-settings">
					<h1><?php echo $string['setting_title_language']; ?></h1>
					<h2><?php echo $string['setting_message_language']; ?></h2>
				</div>
				<div class="v2-settings">
					<button id="settings-lang-content" class="border" onclick="showMenu('settings-lang-content-popup')">
						<?php echo getLanguageSite(); ?>
						<olx class="popup center" style="display: none;" id="settings-lang-content-popup">
							<div class="container">
								<li class="<?php if($_COOKIE['lang']=='ru'){echo'selected';} ?>" onclick="chooseLanguage('ru')"><?php echo $string['setting_language_ru']; ?></li>
								<!-- <li class="<?php if($_COOKIE['lang']=='en'){echo'selected';} ?>" onclick="chooseLanguage('en')"><?php echo $string['setting_language_en']; ?></li> -->
								<!-- <li class="<?php if($_COOKIE['lang']=='by'){echo'selected';} ?>" onclick="chooseLanguage('by')"><?php echo $string['setting_language_by']; ?></li> -->
							</div>
						</olx>
					</button>
				</div>
			</div>

			<hr class="qak-alert-archive-divider">

			<div class="qak-alert-container-settings-item">
				<div class="v1-settings">
					<h1><?php echo $string['setting_title_color_scheme']; ?></h1>
					<h2><?php echo $string['setting_message_color_scheme']; ?></h2>
				</div>
				<div class="v2-settings">
					<button id="settings-lang-content" class="border" onclick="showMenu('settings-lang-content-popup-2')">
						<?php echo getColorSchemeSite(); ?>
						<olx class="popup center" style="display: none;" id="settings-lang-content-popup-2">
							<div class="container">
								<li class="<?php if($_COOKIE['color-scheme']=='auto'){echo'selected';} ?>" onclick="chooseColorScheme('auto')"><?php echo $string['setting_color_scheme_auto']; ?></li>
								<li class="<?php if($_COOKIE['color-scheme']=='theme-default-dark'){echo'selected';} ?>" onclick="chooseColorScheme('theme-default-dark')"><?php echo $string['setting_color_scheme_dark']; ?></li>
								<li class="<?php if($_COOKIE['color-scheme']=='theme-default-light'){echo'selected';} ?>" onclick="chooseColorScheme('theme-default-light')"><?php echo $string['setting_color_scheme_light']; ?></li>
							</div>
						</olx>
					</button>
				</div>
			</div>

			<hr class="qak-alert-archive-divider">

			<div class="qak-alert-container-settings-item">
				<div class="v1-settings">
					<h1><?php echo $string['setting_title_popup_rec']; ?></h1>
					<h2><?php echo $string['setting_message_popup_rec']; ?></h2>
				</div>
				<div class="v2-settings">
					<button id="settings-extract-content" class="border" onclick="showMenu('settings-hidepopuprec-content-popup')">
						<?php echo getHidePopupRecText(); ?>
						<olx class="popup center" style="display: none;" id="settings-hidepopuprec-content-popup">
							<div class="container">
								<li class="<?php if($_COOKIE['hide-popup-rec']=='false'){echo'selected';} ?>" onclick="setHidePopupRec1(false)"><?php echo $string['text_true_on']; ?></li>
								<li class="<?php if($_COOKIE['hide-popup-rec']=='true'){echo'selected';} ?>" onclick="setHidePopupRec1(true)"><?php echo $string['text_false_off']; ?></li>
							</div>
						</olx>
					</button>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			function chooseLanguage(argument) {
				setLanguage(argument);
				window.location.reload();
			}

			function chooseColorScheme(argument) {
				setColorScheme(argument);
				document.getElementById('qak-alert-container-settings').remove();
			}

			function setHidePopupRec1(argument) {
				setHidePopupRec(argument);
				try {
					if (posts_type == 'rec') {
						openType('rec', posts_limit);
					}
				} catch (exx) {}
				document.getElementById('qak-alert-container-settings').remove();
			}
		</script>

		<!-- <h2 class="qak-alert-container-holder-title-bottom-message">
			<?php echo $string['message_settings']; ?>
		</h2> -->
	</div>
</div>