<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('../config.php');

$target_dir = $_SERVER['DOCUMENT_ROOT'];
$auth = file_get_contents($target_dir . '/session.php');

$file = 'about.md';

if (file_exists($file)) {
	$about = file_get_contents($file);
} else {
	$about = '';	
}

if (isset($_POST['content']) && $_POST['content'] != '') {
	if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {
		$newcontent = $_POST['content'];
		if (file_exists($file)) {
		  unlink($file);
		}
		$aboutfile = fopen($file, 'w');
		fwrite($aboutfile, $newcontent);
		fclose($aboutfile);
		
		header("location: ".BASE_URL."about/");
		exit();
	}
}

?>

<form name="form" method="post" action="update.php">
	<textarea rows="10" id="content" name="content" class="text"><?php echo $about; ?></textarea>
	<a href="about.php"><img  loading="lazy" style="width: 20px; float: left; position: relative; top: -1px; cursor: pointer;" alt="cancel" src="../../images/cancel.png" /></a>
	<input type="submit" style ="float: right;" value="update" />
</form>