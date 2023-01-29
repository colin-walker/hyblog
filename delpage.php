<?php

$target_dir = dirname(__FILE__).'/pages/';

if (isset($_GET['p'])) {
	$file = $target_dir.$_GET['p'].'.md';
	
	if ( file_exists( $file ) ) {
		unlink( $file );
	}
}

header("Location: managepages.php");

?>