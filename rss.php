<?php

// Initialise session
session_start();

if(null === APP_RAN) {
	define('APP_RAN', '');
}

require_once('content_filters.php');
require_once('ping.php');
require_once('Parsedown.php');
require_once('ParsedownExtra.php');

$target_dir = $_SERVER['DOCUMENT_ROOT'];
$rss = $target_dir.'/hyblog.xml';

if ( file_exists( $rss ) ) {
	unlink( $rss );
}

$rssfile = fopen($rss, 'w');

fwrite($rssfile, '<?xml version="1.0" standalone="yes" ?>'.PHP_EOL);
fwrite($rssfile, '<rss xmlns:source="http://source.scripting.com/" version="2.0"'.PHP_EOL);
fwrite($rssfile, '>'.PHP_EOL);
fwrite($rssfile, '<channel>'.PHP_EOL);
fwrite($rssfile, '<title>hyblog</title>'.PHP_EOL);
fwrite($rssfile, '<description>Posts from hyblog</description>'.PHP_EOL);
fwrite($rssfile, '<link>'.BASE_URL.'</link>'.PHP_EOL);
fwrite($rssfile, '<lastBuildDate>' . gmdate('D, d M Y H:i:s') . ' GMT</lastBuildDate>'.PHP_EOL);
fwrite($rssfile, '<cloud domain="rpc.rsscloud.io" port="5337" path="/pleaseNotify" registerProcedure="" protocol="http-post"/>'.PHP_EOL);
fwrite($rssfile, '<generator>hyblog</generator>'.PHP_EOL);
fwrite($rssfile, '<source:account service="hyblog">Colin Walker</source:account>'.PHP_EOL);
fwrite($rssfile, '<language>en-GB</language>'.PHP_EOL);

$postfiles = glob($target_dir.'/*/*/*.md');
foreach($postfiles as $postfile) {
	if (substr(explode('/',$postfile)[6],0,1) != 'c') {
		$filedates[] = substr(explode('/',$postfile)[6],0,10);
	}
}
rsort($filedates);
array_splice($filedates,10);

$count = 0;
foreach($filedates as $file) {
	$year = date('Y', strtotime($file));
	$month = date('m', strtotime($file));
	$posts = file_get_contents($target_dir.'/'.$year.'/'.$month.'/'.$file.'.md');
	
	$explode = array_filter(explode('@@', $posts),'strlen');
    $explode = array_reverse($explode);
    $p = count($explode);
	foreach ($explode as $post) {
		$draft = false;
		$post_title = '';
		$content = trim($post);
		$post_array = explode("\n", $content);
	    $size = sizeof($post_array);
		if (substr($post_array[0], 0, 2) === "# ") {
			$length = strlen($post_array[0]);
			$required = $length - 3;
			$post_title = substr($post_array[0], 2, $required);
			$title_in_body = 'true';
			$content = substr($content,$length);
		}
		
		if (substr($post_array[0], 0, 2) === "! ") {
			$draft = true;
			$p-=1;
		}
		$post_parts = explode('!!', $content);
		if (isset($post_parts[1])) {
			$time = trim($post_parts[1]);	
		} else {
			$time = '';
		}
		$content = trim($post_parts[0]);
		$content = filters($content);
		$Parsedown = new ParsedownExtra();
		$content = $Parsedown->text($content);
		
		// display each post
		
		if (!$draft && $count<10) {
			fwrite($rssfile, '<item>'.PHP_EOL);
			
			fwrite($rssfile, '<link>'.BASE_URL.'?date='.$file.'#p'.$p.'</link>'.PHP_EOL);
	        fwrite($rssfile, '<guid isPermaLink="false">'.BASE_URL.'?date='.$file.'#p'.$p.'</guid>'.PHP_EOL);
	        fwrite($rssfile, '<pubDate>' . gmdate("D, d M Y H:i:s", strtotime($file.$time)) . ' GMT</pubDate>'.PHP_EOL);
        
			if ($post_title != '') {
	        	fwrite($rssfile, '<title>' . $post_title . '</title>'.PHP_EOL);
	        }
			fwrite($rssfile, '<description><![CDATA['.$content.']]></description>'.PHP_EOL);
        	fwrite($rssfile, '</item>'.PHP_EOL);
        	$p-=1;
		}
		$count++;
	}
}

fwrite($rssfile, '</channel>'.PHP_EOL);
fwrite($rssfile, '</rss>'.PHP_EOL);
fclose($rssfile);

$feedurl = BASE_URL.'hyblog.xml';

doPing($feedurl);

?>