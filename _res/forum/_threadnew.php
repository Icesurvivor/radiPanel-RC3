<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	$checkFrm = $core->clean( $_GET['frm'] );
	$queryFrm = $db->query( "SELECT * FROM forum WHERE id = '{$checkFrm}'" );
	$numFrm   = $db->num( $queryFrm );
	
	if( $numFrm === 0 ) {
		die( "<div class=\"alert alert-block alert-error fade in\"><strong>Oh snap! You got an error</strong><br />Invalid forum ID. Either it does not exist or you do not have permission to create a thread.</div>" );
	}

?>
					<div class="page-header">
						<h1>Create new thread</h1>
					</div>
					
					<?php

						if( $_POST['submit'] ) {
			
							try {
								
								$title	= $core->clean( $_POST['title'] );
								$post	= $core->clean( $_POST['post'] );
								$forum	= $core->clean( $_GET['frm'] );
			 					$userID	= $user->data['id'];
								$stamp	= time();
			
								if( !$title ) {
			
									throw new Exception( "Please have a thread title!" );
			
								}
								elseif( strlen( $title ) < 3 ) {
									
									throw new Exception( "Minimum title length is 3 characters." );
									
								}
								elseif( !$post ) {
									
								 	throw new Exception( "Please enter a message!" );	
									
								}
								elseif( strlen( $post ) < 5 ) {
								
									throw new Exception( "Minimum post length is 5 characters." );
									
								}
								else {
								
									$addQuery1 = $db->query( "INSERT INTO forum_threads VALUES (NULL, '{$title}', '{$userID}', '{$forum}', '{$stamp}' ) ");
									$newThread = mysql_insert_id();
									
									/* add the post! */
									$addQuery2 = $db->query( "INSERT INTO forum_posts VALUES (NULL, '{$post}', '{$newThread}', '{$userID}', '{$stamp}')" );
									
									echo $core->redirect( "core.forumThread?id=" . $newThread );
			
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
							<label class="control-label" for="title">Thread title</label>
							<div class="controls">
								<input type="text" placeholder="Thread title" name="title">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="post">Message</label>
							<div class="controls">
								<textarea class="input-xlarge" rows="7" placeholder="Your message." name="post"></textarea>
							</div>
						</div>
						
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" name="submit" value="create">Create!</button>
						</div>
					</form>
						
						
						
						