<?php

// Initialise session
session_start();

define('APP_RAN', '');

require_once('../config.php');
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo NAME; ?> — colophon</title>
	<meta name="description" content="<?php echo DESCRIPTION; ?>">
    <link rel="icon" type="image/png" href="<?php echo AVATAR; ?>">
	<link defer rel="stylesheet" href="../style_min.css" type="text/css" media="all">
</head>

<body>
    <div id="page" class="hfeed h-feed site">
        <header id="masthead" class="site-header">
            <div class="site-branding">
                <h1 class="site-title">
                    <a href="<?php echo BASE_URL; ?>" rel="home">
                        <span class="p-name">Colophon</span>
                    </a>
                </h1>
            </div>
        </header>

		<div id="primary" class="content-area">
			<main id="main" class="site-main today-container">
				<article>
					<h3 class="titleSpan" style="margin-bottom: 0px !important;">Hey there</h3>
					<div class="entry-content e-content pre-line">
						So, what is 'hyblog' I hear you ask? No? Well, I'm going to tell you anyway.
						
						The name is an amalgam of hybrid and blog and it is a different approach to a blog than the database driven (b)log-In partly inspired by Static Site Generators but with a dynamic approach.
						
						Rather than having to build the site after each change, it uses dynamic files which write/pull posts and comments to/from .md files.
						
						Hyblog uses Emanuil Rusev's <a href="https://github.com/erusev/parsedown">Parsedown</a> & <a href="https://github.com/erusev/parsedown-extra">ParsedownExtra</a> libraries for Markdown.
						
						It's still in very early stages of development and is primarily an experiment to see what can be done without a database.
						<br/>
						<br/>
						hyblog was created by <a href="https://github.com/colin-walker">Colin Walker</a> and can be found on GitHub <a href="https://github.com/colin-walker/hyblog">Here</a>.
				</article>
			</main>
		</div><!-- #primary -->
	</div><!-- #page -->


<?php
	$pageDesktop = "157";
	$pageMobile = "230";
	include('../footer.php');
?>
