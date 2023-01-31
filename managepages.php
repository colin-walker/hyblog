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

if (isset($_POST['title'])) {
	$title = $_POST['title'];
	$name = $_POST['name'];
	$title = strtolower(str_replace(' ', '_', $title));
	$name = strtolower(str_replace(' ', '_', $name));
	if ($title !== $name) {
		$name = strtolower(str_replace(' ', '_', $name));
		$old_file = $target_dir.$name.'.md';
		unlink($old_file);
	}
	$content = $_POST['content'];
	$file = $target_dir.$title.'.md';
	file_put_contents($file, $content);
	
	if ($name == NOWNS) {
		include('rss.php');
	}
	
	header("location: " . BASE_URL . '/managepages.php' );
  	exit;
}

?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage pages</title>
	<meta name="description" content="<?php echo DESCRIPTION; ?>">
  	<link rel="icon" type="image/png" href="<?php echo AVATAR; ?>">
  	<link rel="stylesheet" href="<?php echo BASE_URL; ?>style_min.css" type="text/css" media="all">
  	<script src="htmx.min.js"></script>
</head>
<body>
	<div id="wrapper" style="width: 100vw; position: absolute; left: 0px;">
	    <div id="page" class="hfeed h-feed site">
	        <header id="masthead" class="site-header">
	            <div class="site-branding">
	                <h1 class="site-title">
	                    <a href="<?php echo BASE_URL; ?>" rel="home">
	                        <span class="p-name">Manage pages</span>
	                    </a>
	                </h1>
	            </div>
	        </header>
	        <div id="primary" class="content-area">
				<main id="main" class="site-main today-container">
					<div class="page-content">
						<br>
<?php

if (isset($_GET['p'])) {
	$page = $_GET['p'];
	$title = ucfirst(str_replace('_', ' ', $page));
	$file = $target_dir.$page.'.md';
	$content = file_get_contents($file);
	
?>
						<form name="form" method="post" action="managepages.php">
							<input type="hidden" name="updatepage" value="updatepage">
							<input type="hidden" name="name" value="<?php echo $title; ?>">
							<input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
							<textarea name="content" rows="10" class="form-control" style="height: 300px; font-family: sans-serif" required><?php echo $content; ?></textarea>
							<div style="width: 93%; margin: 0px auto;">
							<input type="submit" style ="float: right;" value="Update page" />
							</div>
						</form>
<?php
	
} else {

	if (!empty(glob($target_dir.'*.md'))) {
		echo '<p><a href="addpage.php"><b>Add a page</b></a></p>';
		echo '<h2>Edit or delete pages</h2>';
		echo '<br><p>';
		foreach(glob($target_dir.'*.md') as $file) {
			$pagename = rtrim(explode('/',$file)[5], '.md');
			$title = str_replace('_', ' ', $pagename);
			echo '<li style="float: left;"><a href="managepages.php?p='.$pagename.'"><b>'.ucfirst($title).'</b></a></li><img hx-target="body" hx-get="delpage.php?p='.$pagename.'" hx-confirm="Are you sure?" title="Delete page" src="../images/red-cross.png" style="width: 16px; float: right; cursor: pointer;"><br><br>';
		}
		echo '</p>';
	}
}
?>
					</div>
				</main>
			</div>
<?php
	$pageDesktop = "157";
	$pageMobile = "207";
	include('footer.php');
?>