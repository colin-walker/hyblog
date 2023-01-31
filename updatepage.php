<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('config.php');

$target_dir = dirname(__FILE__);
$auth = file_get_contents($target_dir . '/session.php');

if (isset($_GET['p'])) {
	$page = $_GET['p'];

	$file = $target_dir.'/pages/'.$page.'.md';
	$content = file_get_contents($file);
	
	if (isset($_POST['content']) && $_POST['content'] != '') {
		if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {
			$newcontent = $_POST['content'];
			if (file_exists($file)) {
			  unlink($file);
			}
			file_put_contents($file, $newcontent);
			
			if ($page == NOWNS) {
				include('rss.php');
			}
			
			header("location: ".BASE_URL.$page."/");
			exit();
		}
	}

?>

	<form name="form" method="post" action="<?php echo BASE_URL; ?>updatepage.php?p=<?php echo $page; ?>">
		<textarea rows="10" id="content" name="content" class="text"><?php echo $content; ?></textarea>
		<a href="<?php echo BASE_URL.$page; ?>/"><img  loading="lazy" style="width: 20px; float: left; position: relative; top: -1px; cursor: pointer;" alt="cancel" src="../../images/cancel.png" /></a>
		<input type="submit" style ="float: right;" value="update" />
	</form>
	
<?php
}
?>