<?php
/**
	Name: admin
**/

// Initialize the session
session_start();

define('APP_RAN', '');

$uname = $hash = $sitename = $subtitle = $description = $url = $mailto = $avatar = $nowns = '';

// Include config file
clearstatcache();
require('../config.php');

GLOBAL $root, $uname, $hash, $sitename, $subtitle, $description, $url, $mailto, $avatar, $nowns;

$root = dirname(__DIR__);
$auth = file_get_contents($root . '/session.php');
$pages = $root.'/pages/';

$file = $root.'/setup.php';
if ( file_exists( $file ) ) {
    unlink( $file );
}

$changeStr = '';

if (!isset($_SESSION['hauth']) || $_SESSION['hauth'] != $auth) {
  header("location: " . BASE_URL );
  exit;
}

$uname = UNAME;
$hash = HASH;
$sitename = NAME;
$subtitle = SUBTITLE;
$description = DESCRIPTION;
$url = BASE_URL;
$mailto	= MAILTO;
$avatar = AVATAR;
$dailyfeed = DAILYFEED;
$nowns = NOWNS;

if(empty(glob($pages.'*.md'))) {
	$nowns = '';
	changeConfig($nowns);
}

	
if (isset($_POST['update']) == 'true') {
	if ($uname != password_verify($_POST['username'], UNAME) && $_POST['username'] != '') {
		$uname = password_hash($_POST['username'], PASSWORD_DEFAULT);
		echo $_POST['username'];
		$changeStr .= 'Login name changed.<br/>';
	}
	
	if ($sitename != $_POST['sitename']) {
		$sitename = $_POST['sitename'];
		$changeStr .= 'Site name changed.<br/>';
	}
	
	if ($subtitle != $_POST['subtitle']) {
		$subtitle = $_POST['subtitle'];
		$changeStr .= 'Sub title changed.<br/>';
	}
	
	if ($description != $_POST['description']) {
		$description = $_POST['description'];
		$changeStr .= 'Description changed.<br/>';
	}
	
	if ($url != $_POST['url']) {
		$url = $_POST['url'];
		$changeStr .= 'URL changed.<br/>';
	}
	
	if ($mailto != $_POST['mailto']) {
		$mailto = $_POST['mailto'];
		$changeStr .= 'Email address changed.<br/>';
	}
	
	if ($avatar != $_POST['avatar']) {
		$avatar = $_POST['avatar'];
		$changeStr .= 'Avatar changed.<br/>';
	}
	
	if ($dailyfeed != $_POST['dailyfeed']) {
		$dailyfeed = $_POST['dailyfeed'];
		$changeStr .= 'Daily feed status changed.<br/>';
	}
	
	if ($nowns != $_POST['nowns']) {
		$nowns = $_POST['nowns'];
		$changeStr .= 'Now namespace page changed.<br/>';
		if ($nowns != '') {
			GLOBAL $rss;
			$rss = $nowns;
		} else {
			$rss = 'clear';
		}
	}
	
	if (substr($url,-1) != '/') {
		$url.='/';	
	}
	
	if ($changeStr != '') {
		changeConfig($rss);
	}
}

if ( isset($_POST['passcheck']) == 'true' ) {
	if ($hash != password_hash($_POST['password'], PASSWORD_DEFAULT) && $_POST['password'] != '') {
		$hash = password_hash($_POST['password']);
		$changeStr .= 'Password changed.<br/>';
		changeConfig();
	}
}

function changeConfig($rebuild = null) {
	GLOBAL $root, $uname, $hash, $sitename, $subtitle, $description, $url, $mailto, $avatar, $dailyfeed, $nowns, $rss;
	$config = $root.'/config.php';
		if ( file_exists( $config ) ) {
    	unlink( $config );
	}
	
	$createfile = fopen($config,'w');
	fwrite($createfile,'<?php'.PHP_EOL);
	fwrite($createfile,''.PHP_EOL);
	fwrite($createfile,'if(!defined("APP_RAN")){ die(); }'.PHP_EOL);
	fwrite($createfile,''.PHP_EOL);
	
	fwrite($createfile,'define("UNAME", \'' . $uname . '\');'.PHP_EOL);
	fwrite($createfile,'define("HASH", \'' . $hash . '\');'.PHP_EOL);
	fwrite($createfile,''.PHP_EOL);
	
	fwrite($createfile,'define("NAME", "' . $sitename . '");'.PHP_EOL);
	fwrite($createfile,'define("SUBTITLE", "' . $subtitle . '");'.PHP_EOL);
	fwrite($createfile,'define("DESCRIPTION", "' . $description . '");'.PHP_EOL);
	fwrite($createfile,'define("BASE_URL", "' . $url . '");'.PHP_EOL);
	fwrite($createfile,'define("MAILTO", "' . $mailto . '");'.PHP_EOL);
	fwrite($createfile,'define("AVATAR", "' . $avatar . '");'.PHP_EOL);
	fwrite($createfile,'define("DAILYFEED", "' . $dailyfeed . '");'.PHP_EOL);
	fwrite($createfile,'define("NOWNS", "' . $nowns . '");'.PHP_EOL);
		
	fwrite($createfile,'?>');	
	fclose($createfile);	
	gc_collect_cycles();
	
	if (!empty($rebuild) && $rebuild != 'clear') {
		header("Location: ../rss.php?p=".$rebuild);
	} elseif ($rebuild == 'clear') {
		header("Location: ../rss.php?p=clearnow");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="../style_min.css">
    
    <script>
		function togglePass() {
		    var pass = document.getElementById("password");
		    if (pass.type === "password") {
		        pass.type = "text";
		    } else {
		        pass.type = "password";
		    }
	  	}
  	</script>

</head>
<body class="login">

	<header id="masthead" class="site-header">
	    <div class="site-branding">
	        <h1 class="site-title">
	            <a href="<?php echo BASE_URL; ?>" rel="home">
	                <span class="p-name">Admin</span>
	            </a>
	        </h1>
	    </div>
	</header>
	
	<a href="<?php echo BASE_URL; ?>"><img style="
        position: absolute;
        top: 22px;
        right: calc(50vw - 198px);
        font-size: 23px;
        cursor: pointer;
        color: #333;
        z-index: 100;
        width: 23px;
        display: block;" src="../images/cancel.png" />
    </a>
    
    <?php
    if ((isset($_POST['update']) == 'true') || (isset($_POST['passcheck']) == 'true')) {
        if ($changeStr == '') {
            $changeStr = 'Nothing changed';
        }
    ?>
    
        <div class="adminwrapper">
            <div style="text-align: center; padding: 25px 0;">
                <strong>Done ✓</strong>
                <p>
                    <?php echo $changeStr; ?>
                </p>
                <br/>
                <a href="<?php echo BASE_URL; ?>admin/reset.html"><strong>More changes?</strong></a>
            </div>
        </div>
    <?php
    } else {    	
    ?> 
    
    <div class="adminwrapper" style="margin: 20px auto 10px; padding-top: 30px;">
    	<p>
    		<a href="../managepages.php"><b>Manage pages</b></a>
    	</p>
    	</br>
		<form id="admin_form" method="post">
            <div>
            	<input type="hidden" name="update" value="true">
            	<label>Username</label>
 				<input type="text" id="username" name="username" class="form-control" value="" placeholder="Leave blank to retain existing" autofocus>
            	<label>Avatar</label>
 				<input type="url" name="avatar" class="form-control" value="<?php echo AVATAR; ?>">
            	<label>Site name</label>
 				<input type="text" id="sitename" name="sitename" class="form-control" value="<?php echo NAME; ?>">
            	<label>Sub title</label>
 				<input type="text" id="subtitle" name="subtitle" class="form-control" value="<?php echo SUBTITLE; ?>">
            	<label>Description</label>
 				<textarea rows="3" name="description" class="form-control" style="height: 42px;"><?php echo DESCRIPTION; ?></textarea>
            	<label>URL</label>
 				<input type="url" name="url" class="form-control" value="<?php echo BASE_URL; ?>">
            	<label>Email</label>
 				<input type="text" id="mailto" name="mailto" class="form-control" value="<?php echo MAILTO; ?>">
 				<label>Use daily feed</label>
 				<select name="dailyfeed" class="form-control" style="width: 100%;">
 				  <option value="no"<?php if(DAILYFEED == 'no') { echo 'selected'; } ?>>no</option>
 				  <option value="yes" <?php if(DAILYFEED == 'yes') { echo 'selected'; } ?>>yes</option>
 				</select>
 				
<?php
				$pages = $root.'/pages/';
				
				if(!empty(glob($pages.'*.md'))) {
					$nowns = NOWNS;
					echo '<label>Now namespace page</label>'.PHP_EOL;
					echo '<select name="nowns" class="form-control" style="width: 100%;">'.PHP_EOL;
					if(count(glob($pages.'*.md'))) {
						echo '<option value=""></option>'.PHP_EOL;
					}
					foreach(glob($pages.'*.md') as $i=>$file) {
						$pagename = pathinfo($file, PATHINFO_FILENAME);
						echo '<option value="'.$pagename.'"';
						if($nowns == $pagename) { echo 'selected'; };
						echo '>'.$pagename.'</option>'.PHP_EOL;
			 		}
			 		echo '</select>'.PHP_EOL;
				} else {
					echo '<input type="hidden" name="nowns" value="">';	
				}
?>
 				<div style="text-align: right; margin-top: 12px; padding-right: 1px;"><input type="submit" value="Update" style="font-size: 14px; font-weight: bold;"></div>
            </div>
        </form>
    </div>
    <div class="wrapper2">
        <form  id="password_form" method="post">
            <div>
                <input type="hidden" name="passcheck" value="true">
                <label>Password</label>
 				<input type="password" id="password" name="password" class="form-control" value="" autocomplete="off">
 				<div style="text-align: right; padding-right: 0px; position: relative; top: -5px;">Show <input type="checkbox" onclick="togglePass()" style="transform: scale(1.3); position: relative; top: -1px;"></div>
 				<div style="text-align: right; margin-top: 12px; padding-right: 1px;"><input accesskey="p" type="submit" value="Change" style="font-size: 14px; font-weight: bold;" onClick="javascript: return confirm('Change password — are you sure?');"></div>
            </div>
        </form>
	</div>
	
	<?php
    }
    ?>

</body>
</html>