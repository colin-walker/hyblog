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

$content = '';
$dupe = false;

//echo $target_dir;

if (isset($_POST['addpage']) && $_POST['addpage'] == 'add') {
	$title = $_POST['title'];
	$content = $_POST['content'];
	$filename = strtolower(str_replace(' ', '_', $title));

	if (!empty(glob($target_dir.'*.md'))) {
		foreach(glob($target_dir.'*.md') as $file) {
			$pagenames[$i] = pathinfo($file, PATHINFO_FILENAME);
			if ($pagename == $filename) {
				echo '</br><h2>Page name already used.</h2>';
				$dupe = true;
			}
		}
	}
	if($dupe === false) {
		$page = $target_dir.$filename.'.md';
		file_put_contents($page, $content);
		header("Location: managepages.php");
		exit;
	}	
}

?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add page</title>
	<meta name="description" content="<?php echo DESCRIPTION; ?>">
  	<link rel="icon" type="image/png" href="<?php echo AVATAR; ?>">
  	<link rel="stylesheet" href="<?php echo BASE_URL; ?>style_min.css" type="text/css" media="all">
</head>
<body>
	<div id="wrapper" style="width: 100vw; position: absolute; left: 0px;">
	    <div id="page" class="hfeed h-feed site">
	        <header id="masthead" class="site-header">
	            <div class="site-branding">
	                <h1 class="site-title">
	                    <a href="<?php echo BASE_URL; ?>" rel="home">
	                        <span class="p-name">Add page</span>
	                    </a>
	                </h1>
	            </div>
	        </header>
	        <div id="primary" class="content-area">
				<main id="main" class="site-main today-container">
					</br>
					<form name="form" method="post">
						<input type="hidden" name="addpage" value="add">
						<input type="text" name="title" class="form-control" placeholder="Title" required>
						<textarea name="content" rows="10" class="form-control" style="height: 300px; font-family: sans-serif" placeholder="Page content (Markdown)" required><?php echo $content; ?></textarea>
						<div style="width: 93%; margin: 0px auto;">
						<input type="submit" style ="float: right;" value="Add page" />
						</div>
					</form>
				</main>
			</div>
<?php
	$pageDesktop = "157";
	$pageMobile = "207";
	include('footer.php');
?>