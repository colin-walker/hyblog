<?php

require_once('Parsedown.php');

$target_dir = $_SERVER['DOCUMENT_ROOT'];

$comment = $_GET['c'];
$date = $_GET['date'];
$post = $_GET['p'];
$year = date('Y', strtotime($date));
$month = date('m', strtotime($date));

$file = $target_dir.'/'.$year.'/'.$month.'/'.'comments'.$post.'-'.$date.'.md';
$comments = file_get_contents($file);

$explode = preg_split('/@@/', $comments, -1, PREG_SPLIT_NO_EMPTY);

unset($explode[$comment]);

if ( file_exists( $file ) ) {
	unlink( $file );
}
	
$commentfile = fopen($file, 'w');
foreach($explode as $comment) {
	fwrite($commentfile, "@@\n");
	fwrite($commentfile, $comment."\n");
}
fclose($commentfile);
			
$comments = file_get_contents($file);
$explode = array_filter(explode('@@', $comments),'strlen');
foreach ($explode as $comment) {
	$parts = explode('@!@',$comment);
	echo '<div style="text-indent: 20px; margin-bottom: -10px;"><b>'.$parts[0].'</b> says:</div>';
	$Parsedown = new Parsedown();
	$parts[1] = $Parsedown->text($parts[1]);
	echo '<div style="text-indent: 20px; margin-bottom: 25px;">'.$parts[1].'</div>';
}

echo '<form id="form'.$post.'" hx-target="#comment'.$post.'" hx-post="comment.php">';
echo '<input type="hidden" name="date" value="'.$date.'" />';
echo '<input type="hidden" name="post" value="'.$post.'" />';
echo '<input type="text" name="name" required class="commentInput" style="font-family: Helvetica, Arial, sans-serif; font-size: 15px; margin-bottom: 10px; padding: 5px 7px; color: #777; border: 1px solid #ccc; border-radius: 5px;" placeholder="Name" />';
echo '<textarea rows="3" name="comment" required class="comment_text commentInput" style="color: #777; border: 1px solid #ccc; border-radius: 5px; font-family: Helvetica, Arial, sans-serif; font-size: 15px; margin-bottom: 5px; padding: 6px 7px;" placeholder="Comment ..."></textarea>';
echo '<input style="float: right; margin-right: 15px;" type="submit" value="comment"/>';
echo '</form>';

?>