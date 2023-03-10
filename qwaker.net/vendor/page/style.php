<?php
	include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
?>
<!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
<!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
<!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"> -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" type="text/css" href="/assets/css/theme.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="/assets/fonts/fontfamily.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>default.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>alert.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>popup-alert.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>alert-box.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>toast.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>progress-bar.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>auth.css?v=<?php echo time(); ?>">
<link href="/assets/uicons-regular-rounded/css/uicons-regular-rounded.css" rel="stylesheet">
<?php if (isMobile()) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>menu.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $default_theme_site; ?>mobile.css?v=<?php echo time(); ?>">
<?php } ?>