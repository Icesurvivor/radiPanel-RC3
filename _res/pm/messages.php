<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
?>
					<div class="page-header">
						<h1>Message Center</h1>
					</div>
					
					<a href="#" class="btn btn-primary" onClick="document.location.href='?do=inbox'">Inbox</a>
					<a href="#" class="btn btn-primary" onClick="document.location.href='?do=newMsg'">Compose</a>
					<a href="#" class="btn btn-primary" onClick="document.location.href='?do=sent'">Sent</a><br /><br />
				
					<?php
						if( $_GET['do'] ) {
							$page = $core->clean( $_GET['do'] );
							include_once( "_res/pm/" . $page . ".php" );
						}
						else {
							include_once( "_res/pm/inbox.php" );
						}
									
					?>