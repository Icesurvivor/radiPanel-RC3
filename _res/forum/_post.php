<?php
	/*
	 * Included in _thread.php
	 */
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

?>
				<div id="post">
					<h3>Quick reply</h3>
						
					<?php

						if( $_POST['submit'] ) {
				
							try {
							
								$post	= $core->clean( $_POST['msg'] );
								$thread	= $threadID;
								$time	= time();
									
								if( !$post ) {
				
										throw new Exception( "Please enter a message!" );
				
								}
								elseif( strlen( $post ) < 5 ) {
									
									throw new Exception( "Minimum post length is 5 characters." );
									
								}
								else {
							
									$db->query( "INSERT INTO forum_posts VALUES (NULL, '{$post}', '{$thread}', '{$user->data['id']}', '{$time}')" );
									$newPost = mysql_insert_id();
									
									/* finally succeeded */
									echo $core->redirect( "core.forumThread?id=" . $thread . "&pst=" . $newPost );
			
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
							<label class="control-label" for="msg">Message</label>
							<div class="controls">
								<textarea class="input-xlarge" rows="5" placeholder="Click me to reply!" name="msg"></textarea>
							</div>
						</div>
						
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" name="submit" value="post">Post!</button>
						</div>
					</form>
				</div>