<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('config.php');

$target_dir = dirname(__FILE__).'/pages/';

$pagenames = array();

foreach(glob($target_dir.'*.md') as $i=>$file) {
	$pagenames[$i] = pathinfo($file, PATHINFO_FILENAME);
}

$erroruri = end(explode('/', trim($_SERVER['REQUEST_URI'], '/')));

foreach($pagenames as $pagename) {
	if ($erroruri == $pagename) {
		header("Location: ".BASE_URL.'page.php?p='.$pagename);
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>404</title>
	<meta name="description" content="<?php echo DESCRIPTION; ?>">
  	<link rel="icon" type="image/png" href="<?php echo AVATAR; ?>">
  	<link rel="stylesheet" href="<?php echo BASE_URL; ?>style_min.css" type="text/css" media="all">
    <link rel="home alternate" type="application/rss+xml" title="hyblog feed" href="<?php echo BASE_URL; ?>hyblog.xml">
</head>

<body>
	<div id="wrapper" style="width: 100vw; position: absolute; left: 0px;">
	    <div id="page" class="hfeed h-feed site">
	        <header id="masthead" class="site-header">
	            <div class="site-branding">
	                <h1 class="site-title">
	                    <a href="<?php echo BASE_URL; ?>" rel="home">
	                        <span class="p-name">Page not found</span>
	                    </a>
	                </h1>
	            </div>
	        </header>
	        <div id="primary" class="content-area">
				<main id="main" class="site-main today-container">
					<br>
					<h2>Oops!</h2>
					<article>
						<div class="section">
							<div class="entry-content e-content">
								<p>
									That page can't be found.
								</p>
								<p>
									Go back <a href="<?php echo BASE_URL; ?>">home</a>.
								</p>
							</div>
						</div>
					</article>
				</main>
			</div>
<?php
	$pageDesktop = "157";
	$pageMobile = "207";
	include('footer.php');
?>