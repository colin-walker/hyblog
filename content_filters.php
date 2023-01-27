<?php

if(!defined('APP_RAN')){ die(); }

function filters($content) {

// break

  $content = str_replace('[hr]', '<hr noshade width="33%" style="margin-bottom: 25px; margin-top: 25px;" size="1">' ,$content);


// webp images

	$open = '![';
	$close = ']]';
	$imgcount = substr_count($content, $open);
  
	$alt = $class = '';
	for ($i=0; $i < $imgcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 2 && $cpos) {
			$orig = substr($content, $opos, $len+2);
			$imgtext = substr($content, $opos+2, $len-2);
			$explode = explode(',', $imgtext);
			if(isset($explode[1])) { $class = $explode[1]; }
			if(isset($explode[2])) { $alt = $explode[2]; }
			$explode = explode('.', $explode[0]);
			$last = end ($explode);
			$extloc = strpos($imgtext, $last);
			$pathnoext = substr($imgtext, 0 , $extloc);
			$webp = $pathnoext . 'webp';
			if($last == 'png') {
				$type = 'png';
				$ext = 'png';
			} else {
				$type = 'jpeg';
				$ext = 'jpg';
			}
			$replace = '<picture><source srcset="'. $webp .'" type="image/webp"/><source srcset="' . $pathnoext . $ext . '" type="image/' . $type . '"/><img src="' . $pathnoext . $ext . '" alt="' . $alt . '" title="' . $alt . '"  class="' . $class . '"></picture>';
			if (strpos($imgtext,'http') == 0) {
			  $content = str_replace($orig, $replace , $content);
			}
		}
	}


// details/summary

	$open = '!!';
	$close = '>!';
	$linkcount = substr_count($content, $open);

	for ($i=0; $i < $linkcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 2 && $cpos) {
			$orig = substr($content, $opos, $len);
			$summary = substr($content, $opos+2, $len-2);
			$replace = '<details><summary style="outline: none;">' . $summary. '</summary>';
			$content = str_replace($orig, $replace, $content);
		}
	}

	$open = '>!';
	$close = '!<';
	$linkcount = substr_count($content, $open);

	for ($i=0; $i < $linkcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 2 && $cpos) {
			$orig = substr($content, $opos, $len+2);
			$details = substr($content, $opos+2, $len-2);
			$replace = '<div style="margin-left: 17px;">' . $details . '</div></details>';
			$content = str_replace($orig, $replace, $content);
		}
	}
	
// End details/summary


// strikethrough

	$strike = '~~';
	$check = '/~~/i';
	$strikecount = substr_count($content, $strike);

	$odd = 1;

	for ($i=0; $i < $strikecount; $i++) {
		if ($odd == 1) {
			$replace = '<del>';
			$odd = 0;
		} else {
			$replace = '</del>';
			$odd = 1;
		}
		$content = preg_replace($check, $replace, $content, 1);
	}
	
	
// underline

	$under = '~';
	$check = '/(\~(?<!\s)(.+?)(?!\s)\~)/i';
	$linkscount = substr_count($content, $under);

	$odd = 1;

	for ($i=0; $i < $linkscount; $i++) {
		if ($odd == 1) {
			$replace = '<span style="text-decoration: underline;">';
			$odd = 0;
		} else {
			$replace = '</span>';
			$odd = 1;
		}
		
		$replace = "<span style='text-decoration: underline;'>$2</span>";
		$content = preg_replace($check, $replace, $content, 1);
	}	
	
	
// superscript

	$super = '^';
	$check = '/(?<!(\[))\^(?!\s)(.*?)(?<!\s)\^/i';
	$linkscount = substr_count($content, $super);

	$odd = 1;

	for ($i=0; $i < $linkscount; $i++) {
		if ($odd == 1) {
			$replace = '<sup>';
			$odd = 0;
		} else {
			$replace = '</sup>';
			$odd = 1;
		}
		
		$replace = "<sup>$2</sup>";
		$content = preg_replace($check, $replace, $content, 1);
	}	
	
	
// badges

	GLOBAL $feed;
	
	if (!isset($feed)) {
	  $feed = '';
	}
	
	$badge = '%%';
	$check = '/%%/i';
	$badgecount = substr_count($content, $badge);
	$odd = 1;

	for ($i=0; $i < $badgecount; $i++) {
		if ($odd == 1) {
			$replace = '<span class="badge"><strong>';
			$odd = 0;
		} else {
			$replace = '</strong></span>' . $feed;
			$odd = 1;
		}
		$content = preg_replace($check, $replace, $content, 1);
	}
		
	
// embedded audio

	$open = '[a[';
	$close = ']a]';
	$linkcount = substr_count($content, $open);

	for ($i=0; $i < $linkcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 3 && $cpos) {
			$orig = substr($content, $opos, $len+4);
			$linktext = substr($content, $opos+3, $len-3);
			$a_explode = explode('/',$linktext);
			$a_count = count($a_explode);
			$file = $a_explode[$a_count-1];
			$replace = '<audio controls="controls" preload="metadata" src="' . $linktext . '">File: ' . $file . '</audio>';
			$content = str_replace($orig, $replace, $content);
		}
	}
	
	
// embedded video

	$open = '[v[';
	$close = ']v]';
	$linkcount = substr_count($content, $open);

	for ($i=0; $i < $linkcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 3 && $cpos) {
			$orig = substr($content, $opos, $len+4);
			$linktext = substr($content, $opos+3, $len-3);
			
			$replace = '<div class="aligncenter"><video width="90%" controls><source src="' . $linktext . '" type="video/mp4">Can\'t see the video? <a href="' . $linktext . '">Click here to watch...</a></video></div>';
			
			$content = str_replace($orig, $replace, $content);
		}
	}
	
	
// embedded YouTube

	$open = '[y[';
	$close = ']y]';
	$linkcount = substr_count($content, $open);

	for ($i=0; $i < $linkcount; $i++) {
		$opos = strpos($content, $open);
		$cpos = strpos($content, $close);
		$len = $cpos-$opos;
		if ($cpos - $opos != 3 && $cpos) {
			$orig = substr($content, $opos, $len+4);
			$linktext = substr($content, $opos+3, $len-3);
			
			$linktext_array = explode(' "', $linktext);
			if (isset($linktext_array[1]) && $linktext_array[1] != '') {
				$linktext_array[1] = '<strong>' . substr($linktext_array[1], 0 , -1) . '</strong><br/>';
			} else {
			  $linktext_array[1] = '';
			}
		
			$replace = '<div class="aligncenter" style="position: relative; width: 100%; padding-top: 56.25%"><iframe style="position: absolute; top: 10px; bottom: 0; left: 0; right: 0; width: 100%; height: 100%" src="https://www.youtube.com/embed/' . $linktext_array[0] . '" allowfullscreen="false" frameborder="0">' . $linktext_array[1] . '<a href="https://www.youtube.com/embed/' . $linktext_array[0] . '">Can\'t see the video? Click here to watch...</a></iframe></div>';
			
			$content = str_replace($orig, $replace, $content);
		}
	}
	
	
// highlight text

	$pattern = "/::(.*)::/i";
	$replace = "<span style='background-color: #f1fe19; color: #333; padding: 2px 5px; border-radius: 5px;'>$1</span>";
	$content = preg_replace($pattern,$replace,$content);


// mark text

	$mark = "/==(.*)==/i";
	$replace = "<mark>$1</mark>";
	$content = preg_replace($mark,$replace,$content);
	
	return $content;	
}

?>