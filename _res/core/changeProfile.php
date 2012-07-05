<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

	$do = $_GET['do'];
	
	if(  !$do or $do == "" ) { 
?>
					<div class="page-header">
						<h1>User Settings</h1>
					</div>
					
<?php
	}
	elseif( $do == "password" ) {
?>
					<div class="page-header">
						<h1>User Settings - Change Password</h1>
					</div>

<?php
	}
	elseif( $do == "email" ) {
?>
					<div class="page-header">
						<h1>User Settings - Update Email</h1>
					</div>

<?php
	}
?>