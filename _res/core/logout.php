<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	$user->destroySession();
?>

<div class="page-header">
	<h1>Logout</h1>
</div>

Logout successful!
