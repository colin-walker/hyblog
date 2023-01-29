<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('config.php');

$root = dirname(__FILE__);
$auth = file_get_contents($root . '/session.php');

if (!isset($_SESSION['hauth']) || $_SESSION['hauth'] != $auth) {
  header("location: " . BASE_URL );
  exit;
}

$target_dir = $root.'/pages/';

if (isset($_GET['p'])) {
	$file = $target_dir.$_GET['p'].'.md';
	
	if ( file_exists( $file ) ) {
		unlink( $file );
	}
}

header("Location: managepages.php");

?>