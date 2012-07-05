<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	if( $_GET['id'] ) {
	
		$msgID = $core->clean( $_GET['id'] );			
		
	}
	
	$query = $db->query( "SELECT * FROM `personal_messages` WHERE `user` = '{$user->data['id']}' AND `id` = '{$msgID}'" );
	$array = $db->arr( $query );
	$num   = $db->num( $query );
	
	if( $num === 0 ) {
		
		die( "Permission denied!" );
	
	}
	
	$update = $db->query( "UPDATE `personal_messages` SET `read` = '1' WHERE `id` = '{$msgID}'" );
	
	$query1 = $db->query( "SELECT * FROM `users` WHERE `id` = '{$array['from']}'" );
	$array1 = $db->arr( $query1 );
	
?>
					<h2>View message</h2>
					<p>
					Work in progress.<br />
					<pre>
data-pm-sender=<?php echo $array1['username']; ?><br />
data-pm-title=<?php echo $array['title']; ?><br />
data-display-pic=https://si0.twimg.com/profile_images/1665717976/large_235897_reasonably_small.png<br /><br />
data-pm-body=<br />
<?php echo $array['body']; ?>
					</pre>
					</p>
					