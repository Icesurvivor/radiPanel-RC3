<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	if( !$user->data['displaygroup'] > 4 ) { die(); } else {

		if( $_GET['id'] ) {
	
			$editid	= $core->clean( $_GET['id'] );
	
			$query	= $db->query( "SELECT * FROM forum_posts WHERE id = '{$editid}'" );
			$data	= $db->assoc( $query );
			$num	= $db->num( $query );
			
			$queryX = $db->query( "SELECT * FROM forum_threads WHERE id = '{$data['thread']}'" );
			$arrayX	= $db->assoc( $queryX );
			
		}
		
		if( $num === 0 ) {
			echo "Invalid post ID!";
		}

?>
					<div class="page-header">
						<h1>Forum - Edit Post</h1>
					</div>

		<?php

			if( $_POST['submit'] ) {

				try {
				
					$title	= $core->clean( $_POST['title'] );
					$post	= strip_tags( $_POST['post'] );
					$thread	= $core->clean( $data['thread'] );
					$user	= $core->clean( $data['user'] );
					$stamp	= $core->clean( $data['timestamp'] );
					
					
					if( !$post ) {

						throw new Exception( "Please enter a message." );

					}
					if( !$title ) {
					
						throw new Exception( "Please don't leave your thread title blank!" );
						
					}
					elseif( strlen( $post ) < 5 ) {
						
						throw new Exception( "Minimum post length is 5 characters." );
						
					}
					else {

						$db->query( "UPDATE forum_posts SET post = '{$post}', thread = '{$thread}', user = '{$user}', timestamp = '{$stamp}' WHERE id = '{$editid}'" );
						$db->query( "UPDATE forum_threads SET title = '{$title}' WHERE id = '{$thread}'" );
						
						
						echo $core->redirect( "core.forumThread?id=" . $thread . "&pst=" . $editid );
						
						die();

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
						if( $user->hasGroup( "5" ) ) {
					?>
						<div class="control-group">
							<label class="control-label" for="title">Title</label>
							<div class="controls">
								<input type="text" class="large" placeholder="Thread title" name="title" value="<?php echo $arrayX['title']; ?>" />
								<p class="helptext">You have com.jiajiann.ext.perms.forum.postEdit.* enabled.</p>
							</div>
						</div>
					<?php
						}
					?>
						<div class="control-group">
							<label class="control-label" for="post">Message</label>
							<div class="controls">
								<textarea class="input-xlarge" rows="7" placeholder="Your message" name="post"><?php echo $data['post']; ?></textarea>
							</div>
						</div>
						
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" name="submit" value="edit">Edit!</button>
						</div>
					</form>
<?php
	}
?>