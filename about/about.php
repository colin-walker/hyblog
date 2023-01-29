<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('../config.php');
require_once('../content_filters.php');
require_once('../Parsedown.php');
require_once('../ParsedownExtra.php');

$target_dir = dirname(__DIR__);
$auth = file_get_contents($target_dir . '/session.php');

?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>hyblog — about</title>
	<meta name="description" content="<?php echo constant('DESCRIPTION'); ?>">
    <link rel="icon" type="image/png" href="<?php echo AVATAR; ?>">
	<link defer rel="stylesheet" href="../style_min.css" type="text/css" media="all">
</head>

<body>
    <div id="page" class="hfeed h-feed site">
        <header id="masthead" class="site-header">
            <div class="site-branding">
                <h1 class="site-title">
                    <a href="<?php echo BASE_URL; ?>" rel="home">
                        <span class="p-name">About</span>
                    </a>
                </h1>
            </div>
        </header>

		<div id="primary" class="content-area">
			<main id="main" class="site-main today-container">
				<article>
					<div class="entry-content e-content page-content about-content" style="padding-bottom: 60px;">
				
<?php
	if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {	
?>
						<div hx-target="this" hx-trigger="dblclick" hx-get="update.php">
<?php
	} else {
?>
						<div>
<?php
	}
		
	if ( file_exists( 'about.md' ) ) {
		$about = file_get_contents('about.md');
		if ($about == '') {
			$about = 'Nothing here yet';
		}
		$Parsedown = new Parsedown();
		$about = $Parsedown->text($about);
		echo $about;
	} else {
		echo 'Nothing here yet';	
	}
?>
						</div>
					</div>
				</article>
			</main>
		</div><!-- #primary -->
	</div><!-- #page -->
	
	<script src="../htmx.min.js"></script>

<?php
	$pageDesktop = "157";
	$pageMobile = "227";
	include('../footer.php');
?>