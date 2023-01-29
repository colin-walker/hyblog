<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('config.php');

$target_dir = dirname(__FILE__);
$auth = file_get_contents($target_dir . '/session.php');
$date = $_GET['date'];
$year = date('Y', strtotime($date));
$month = date('m', strtotime($date));
$day = date('d', strtotime($date));
$posts = file_get_contents($target_dir.'/posts/'.$year.'/'.$month.'/'.$date.'.md');

// update posts

if (isset($_POST['content']) && $_POST['content'] != '') {
	if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {
		$newcontent = $_POST['content'];
		
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
		
		header("location: ".BASE_URL."?date=".$date);
		exit();
	}
}

?>
	<iframe id="upload_frame" scrolling="no" loading="lazy" src="uploader.php" style="display: inline;"></iframe>
	<form name="form" method="post" action="update.php?date=<?php echo $_GET['date']; ?>">
		<textarea rows="10" id="content" name="content" class="text"><?php echo $posts; ?></textarea>
		<a href="hyblog.php?date=<?php echo $_GET['date']; ?>"><img  loading="lazy" style="width: 20px; float: left; position: relative; top: -1px; cursor: pointer;" alt="cancel" src="../images/cancel.png" /></a>
		<input type="submit" style ="float: right;" value="update" />
	</form>