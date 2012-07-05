<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

?>
<h1>Contact Administrators</h1>

	<?php

		if( $_POST['submit'] ) {

			try {

				$subject = $core->clean( $_POST['subject'] );
				$dept    = $core->clean( $_POST['department'] );
				
				// @Override
				$dept = 1;
				$message = $core->clean( $_POST['message'] );
				$time    = time();

				if( !$subject or !$dept or !$message ) {

					throw new Exception( "All fields are required." );

				}
				else {

					$db->query( "INSERT INTO mgmt_messages(id, subject, message, user, dept, timestamp) VALUES (NULL, '$subject', '{$message}', '{$user->data['simpleUsername']}', '$dept', '$time'); " );

					$email = "jia_jian@me.com";
					$sub   = "[radiPanel] Message at jiajiann.com";
					$msg   = "Hello Admin,\r\rThis is an automated notification to inform you that there is a new Player Support Message awaiting your respond.\r\r";
					$msg  .= "Thank you and have a nice day!";
					$msg  .= "--System";
						
					$headers .= "From: radiPanel Notifications <noreply@jiajiann.com>";
					mail( $email, $sub, $msg, $headers );


					echo "<div class=\"alert alert-success\">";
					echo "<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
					echo "<strong>Success!</strong>";
					echo "<br />";
					echo "Your message has been sent to the management. We will contact you through your registered email as soon as possible.";
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
							<div class="control-group">
								<label class="control-label" for="subject">Subject</label>
								<div class="controls">
									<input type="text" class="span3" placeholder="Subject" name="subject">
									<p class="help-block">Email subject, duh!</p>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="department">Department</label>
								<div class="controls">
									<select id="department">
										<option>something</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="message">Message</label>
								<div class="controls">
									<textarea class="input-xlarge" id="textarea" rows="5" name="message"></textarea>
								</div>
							</div>
							
							<div class="form-actions">
								<button type="submit" class="btn btn-primary" name="submit" value="Send!">Send!</button>
							</div>
						</form>