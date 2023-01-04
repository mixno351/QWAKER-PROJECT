<?php
	header('Access-Control-Allow-Origin: *');
	header('Vary: Accept-Encoding, Origin');
	header('Keep-Alive: timeout=2, max=99');
	header('Access-Control-Allow-Methods: GET');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 604800');
	header('Connection: Keep-Alive');
	header('Content-Type: image/png');


?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/classSimpleImage.php'; ?>
<?php
	$url = $_GET['url'];
	$w = intval($_GET['width']);
	$h = intval($_GET['height']);
	$s = intval($_GET['scale']);

	if ($w < 50) {
		$w = intval(50);
	} if ($h < 50) {
		$h = intval(50);
	} if ($s < 220) {
		$s = intval(220);
	}
?>
<?php
	$parse = parse_url($url);
	if ($parse['host'] == $_SERVER['SERVER_NAME']) {} else {
		return;
	}
	$image = new SimpleImage();
	$image->load($url);
	$image->resizeToWidth($s);
	$image->output();
?>