<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	# if user is replying.
	if( $_GET['r'] ) {
		
		$replyID = $core->clean( $_GET['r'] );
		
		# find the reply title.
		$queryI = $db->query( "SELECT * FROM `personal_messages` WHERE `id` = '$replyID' AND `user` = '{$user->data['id']}'" );
		$arrayI = $db->arr( $queryI );
		$numI   = $db->num( $queryI );
		
		if( $numI != 1 ) {
			# jack
		}
		else {
		
			# set the title.
			$data['title'] = $arrayI['title'];
		
			# find replied sender.
			$queryJ = $db->query( "SELECT * FROM users WHERE id = '{$arrayI['from']}'" );
			$arrayJ = $db->arr( $queryJ );
			
			# set the target user.
			$data['user'] = $arrayJ['username'];
		
		}
		
	}

?>
					<h2>Compose PM</h2>
<?php

			if( $_POST['submit'] ) {

				try {
					
					$title   = $core->clean( $_POST['title'] );
					$body    = $core->clean( $_POST['body'] );
					$for     = $core->clean( $_POST['user'] );
					$sender  = $user->data['id'];
					$date    = date( "jS F Y" );
					
					$userQ   = $db->query( "SELECT * FROM users WHERE username = '{$for}'" );
					$userA   = $db->arr( $userQ );
					$userNum = $db->num( $userQ );
					
					$query   = $db->query( "SELECT * FROM users WHERE username = '{$for}'" );
					$array   = $db->assoc( $query );
					
					if( $userNum === 0 ) {
						
						throw new Exception( "Sorry, that user doesn't exist." );
						
					}
					/*elseif( $userA['id'] == $sender ) {
					
						throw new Exception( "You can't send a message to yourself!" );						
						
					}
					elseif( strlen( $title ) < 1 or strlen( $title ) > 25 ) {
						
						throw new Exception( "Title must be 1 to 25 charatcers." );
						
					}*/
					elseif( !$title or !$body ) {

						throw new Exception( "All fields are required." );

					}
					else {
						
						$queryX = $db->query( "SELECT * FROM users WHERE username = '{$for}'" );
						$arrayX = $db->arr( $queryX );
						$userPM = $arrayX['id'];
						
						$db->query( "INSERT INTO personal_messages VALUES (NULL, '{$title}', '{$body}', '{$userPM}', '{$sender}', '0', '{$date}');" );
						
						echo "<div class=\"alert alert-block alert-success fade in\">";
						echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
						echo "<strong>Success!</strong><br />";
						echo "Message sent!";
						echo "</div>";
						
						$email   = $array['email'];
						$headers = 'From:  <noreply@jiajiann.com>' . "\r\n";
						$subject = "New Private Message - $title";
$message = "Hello $for,
You have received a new private message from {$user->data['simpleUsername']} here at JJHome.

This is the message:
----------
$body
----------

To reply to this private message, click on the following link:
http://internal.extranet.jiajiann.com/core.msg_center

Do NOT reply to this email.

Regards,
System Administrator

Please do not reply to this email as it is sent form an unmonitored inbox, if you have any queries please foward it to me@jiajiann.com";

mail("$email", $subject, $message, $headers);

					}

				}
				catch( Exception $e ) {
					echo "<div class=\"alert alert-block alert-error fade in\">";
					echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
					echo "<strong>Oh snap! You got an error</strong><br />";
					echo $e->getMessage();
					echo "</div>";

				}

			}

		?>
						<form class="form-horizontal well" action="" method="post">
							<div class="control-group">
								<label class="control-label" for="title">Title</label>
								<div class="controls">
								<?php
									if( $replyID ) {
								?>
									<div class="input-prepend">
										<span class="add-on">Re:</span><input type="text" class="span3" placeholder="PM Subject" name="title" value="<?php echo $data['title']; ?>">
									</div>
								<?php
									}
									else {
								?>
										<input type="text" class="span3" placeholder="PM Subject" name="title" value="<?php echo $data['title']; ?>">
								<?php
									}
								?>
									<p class="help-block">PM subject, duh!</p>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="user">Recepient</label>
								<div class="controls">
									<input type="text" class="span3" placeholder="Username" name="user" value="<?php echo $data['user']; ?>">
									<p class="help-block">The username.</p>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="body">Message</label>
								<div class="controls">
									<textarea class="input-xlarge" id="textarea" rows="5" name="body"></textarea>
								</div>
							</div>
							
							<div class="form-actions">
								<button type="submit" class="btn btn-primary" name="submit" value="Send!">Send!</button>
							</div>
						</form>