<?php

require_once('Parsedown.php');

$target_dir = dirname(__FILE__); //$_SERVER['DOCUMENT_ROOT'];

if (isset($_POST['date']) && isset($_POST['post'])) {
	$date = $_POST['date'];
	$year = date('Y', strtotime($date));
	$month = date('m', strtotime($date));
	$day = date('d', strtotime($date));
	$post = $_POST['post'];
	$name = $_POST['name'];
	if (isset($_POST['website'])) {
		$website = $_POST['website'];
	}
	$comment = strip_tags($_POST['comment'],'<a><p><br><li><b><i><strong><em>');
	
	$file = $target_dir.'/posts/'.$year.'/'.$month.'/comments'.$post.'-'.$date.'.md';

	if ( file_exists( $file ) ) {
		$existing = file_get_contents($file);
		unlink( $file );
	}
	
	$commentfile = fopen($file, 'w');
	if (isset($existing) && strlen($existing)) {
		fwrite($commentfile, $existing."\n");
	}
	fwrite($commentfile, "@@");
	fwrite($commentfile, $name."<@>".$website."<@>".$comment."\n");
	fclose($commentfile);
	
	$comments = file_get_contents($file);
	$explode = array_filter(explode('@@', $comments),'strlen');
	foreach ($explode as $comment) {
		$parts = explode('<@>',$comment);
		if ($parts[1] == '') {
			echo '<div style="text-indent: 20px; margin-bottom: -10px;"><b>'.$parts[0].'</b> says:</div>';
		} else {
			echo '<div style="text-indent: 20px; margin-bottom: -10px;"><a class="website_link" href="'.$parts[1].'"><b>'.$parts[0].'</b></a> says:</div>';
		}
		$Parsedown = new Parsedown();
		$parts[2] = $Parsedown->text($parts[2]);
		echo '<div style="text-indent: 20px; margin-bottom: 25px;">'.$parts[2].'</div>';
	}
	
}

echo '<form id="form'.$post.'" hx-target="#comment'.$post.'" hx-post="comment.php">';
echo '<input type="hidden" name="date" value="'.$date.'" />';
echo '<input type="hidden" name="post" value="'.$post.'" />';
echo '<input type="hidden" name="email" value="" />';
echo '<input type="text" name="name" size="30" autocomplete="off" id="name'.$post.'" required class="commentInput" style="font-family: Helvetica, Arial, sans-serif; font-size: 15px; margin-bottom: 10px; padding: 5px 7px; color: #777; border: 1px solid #ccc; border-radius: 5px;" placeholder="Name" />';
echo '</br>';
echo '<input type="url" name="website" size="30" style="font-family: Helvetica, Arial, sans-serif; font-size: 15px; margin-bottom: 10px; padding: 5px 7px; color: #777; border: 1px solid #ccc; border-radius: 5px;" placeholder="Website" />';
echo '<textarea rows="3" name="comment" required class="comment_text commentInput" style="color: #777; border: 1px solid #ccc; border-radius: 5px; font-family: Helvetica, Arial, sans-serif; font-size: 15px; margin-bottom: 5px; padding: 6px 7px;" placeholder="Comment ..."></textarea>';
echo '<input style="float: right; margin-right: 15px;" type="submit" value="comment"/>';
echo '</form>';
?>

<a id="toggleComments<?php echo $post; ?>" hx-swap-oob="true" onclick="toggleComments<?php echo $post; ?>" class="toggleComments"><picture class="commenticonpicture"><source srcset="/images/hascommentdark.png" media="(prefers-color-scheme: dark)"><img class="commenticon" src="/images/hascomment.png"></picture></a>