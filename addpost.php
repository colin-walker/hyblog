<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('config.php');

$target_dir = $_SERVER['DOCUMENT_ROOT'];
$auth = file_get_contents($target_dir . '/session.php');
$date = $_GET['date'];
$year = date('Y', strtotime($date));
$month = date('m', strtotime($date));
$day = date('d', strtotime($date));

if (isset($_POST['content']) && isset($_POST['content']) != '') {
	if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {
		$newcontent = $_POST['content'];
		$newcontent = '@@ '.$newcontent;
		$newcontent .= "\r\n\r\n!! ".date('H:i:s');
	}
	
	$file = $target_dir.'/posts/'.$year.'/'.$month.'/'.$date.'.md';
	
	file_put_contents($file, $newcontent, FILE_APPEND);
	
	include('rss.php');
	
	header("location: ".BASE_URL."?date=" . $_GET['date']);
	exit();
}

?>