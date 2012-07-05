<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
?>
					<div class="page-header">
						<h1>Home</h1>
					</div>
					<?php
						$username  = $user->data['simpleUsername'];
						$query = $db->query( "SELECT * FROM users WHERE username = '$username'" );
						$num   = $db->num ( $query );
						$array = $db->assoc( $query );
					?>
						
					Welcome, <strong><?php echo $user->data['fullUsername']; ?></strong>!<br /><br />
					Your last activity is about <?php echo $parser->get_elapsedtime( $user->data['timestamplast'] ); ?><br />
					buildField is working! :D
					<form class="form-horizontal well" action="" method="post">
						<?php
							echo $core->buildField( "text",
										"required",
										"input01",
										"Input01",
										"Your input!",
										"",
										"",
										"Your input, of course!" );
							
							echo $core->buildField( "textarea",
										"required",
										"message",
										"Message",
										"Anything?",
										"",
										"",
										"" );
						?>
						<div class="form-actions">
							<button type="submit" class="btn" name="submit" value="G!">Let me in!</button>
						</div>
					</form>
	