<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

	if( $_GET['id'] ) {

		$id = $core->clean( $_GET['id'] );

		$query = $db->query( "SELECT * FROM users WHERE id = '{$id}'" );
		$data  = $db->assoc( $query );
		
		$data['ugroups'] = explode( ",", $data['usergroups'] );

		$editid = $data['id'];

	}

?>

					<div class="page-header">
						<h1>Add/manage users</h1>
					</div>
					
					
					<?php
						if( $editid ) {
					?>
					User created on <strong><?php echo date( "j F Y", $data['timestamp'] ) . " at " . date( "g:i A", $data['timestamp'] ); ?></strong><br />
					<?php
						if( $data['timestamplast'] ) {
							echo "Last activity was on <strong>" . date( "j F Y", $data['timestamplast'] ) . " at " . date( "g:i A", $data['timestamplast'] ) . "</strong>";
						}
						else {
							echo "This user has never logged in at all.";
						}
							
					?>
					Original IP associated is <strong><?php echo $data['ip']; ?></strong><br />
					Last logged IP was <strong><?php echo $data['iplast']; ?></strong><br />	
					<?php
						}

						if( $_POST['submit'] ) {
			
							try {
								
								
								$username = $core->clean( $_POST['username'] );
								$password = $core->clean( $_POST['password'] );
								$email    = $core->clean( $_POST['email'] );
								$dgroup   = $core->clean( $_POST['dgroup'] );
			                                        $banned   = $core->clean( $_POST['banned'] );
								$time     = time();
								$ip       = $_SERVER['REMOTE_ADDR'];
			
								$query    = $db->query( "SELECT * FROM usergroups" );
					
								while( $array = $db->assoc( $query ) ) {
								
									if( $_POST['ugroup-' . $array['id']] ) {
										
										$ugroups .= $array['id'] . ",";
										
									}
								
								}
								
								$password_enc = $core->encrypt( $password );
			
								if( !$username or ( !$password and !$editid ) or !$dgroup or !$ugroups ) {
			
									throw new Exception( "All fields are required." );
			
								}
								else {
			
									if( $editid ) {
			
										if( $password ) {
											
											$password = ", password = '{$password_enc}'";
											
										}
										else {
											
											unset( $password );
											
										}
			
										$db->query( "UPDATE users SET `username` = '{$username}'" . $password . ", `email` = '{$email}', `displaygroup` = '{$dgroup}', `usergroups` = '{$ugroups}' WHERE id = {$editid}" );
										
			
			
									}
									else {
									
										$db->query( "INSERT INTO users (id, username, password, email,timestamp, ip, displaygroup, usergroups) VALUES
										(NULL, '{$username}', '{$password_enc}', '{$email}', '$time', '$ip', '{$dgroup}', '{$ugroups}');" );
									
									}
									
									echo "<div class=\"alert alert-success\">";
									echo "<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
									echo "<strong>Success!</strong>";
									echo "<br />";
									echo $editid ? "The user's info has been edited!" : "You've added a new user!";
									echo "</div>";
			
								}
			
							}
							catch( Exception $e ) {
			
								echo "<div class=\"alert alert-block alert-error fade in\">";
								echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
								echo "<strong>Oh snap! You got an error</strong>";
								echo "<br />";
								echo $e->getMessage();
								echo "</div>";
			
							}
			
						}
			
					?>
					<form class="form-horizontal well" action="" method="post">
						<?php
							
							echo $core->buildField( "text",
										"required",
										"username",
										"Username",
										"The account's username.",
										$data['username'],
										"",
										"This will be the Login ID." );
							
							echo $core->buildField( "password",
										"",
										"password",
										"Password",
										"The account's password.",
										"",
										"",
										"" );
										
							echo $core->buildField( "email",
										"",
										"email",
										"Email",
										"",
										$data['email'],
										"",
										"" );
							
							echo $core->buildField( "select",
										"required",
										"article",
										"Article",
										"Your article.",
										"",
										"",
										"" );
						?>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" name="submit" value="publish">Publish</button>
						</div>
					</form>