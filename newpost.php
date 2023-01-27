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
		if (substr($newcontent,0,3) != '@@ ') {
		    $newcontent = '@@ '.$newcontent;
		}
		
		$post_array = explode("\n", $newcontent);
		$last = count($post_array)-1;
		if (strpos($post_array[$last], '!!') === false) {
			$newcontent .= "\n\n!! ".date('H:i:s');
		}
		
		$file = $target_dir.'/posts/'.$year.'/'.$month.'/'.$date.'.md';

		if ( file_exists( $file ) ) {
		  unlink( $file );
		}

		$postfile = fopen($file, 'w');
		fwrite($postfile, $newcontent);
		fclose($postfile);
		
		include('rss.php');
		
		header("location: ".BASE_URL."?date=" . $_GET['date']);
		exit();
	}
}

?>