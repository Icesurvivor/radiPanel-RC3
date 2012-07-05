<?php
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

	if( $_GET['id'] ) {
		
		$id = $core->clean( $_GET['id'] );
		
		$query = $db->query( "SELECT * FROM news WHERE id = '{$id}'" );
		$data  = $db->assoc( $query );
		
		$editid = $data['id'];
		
	}

?>

					<div class="page-header">
						<h1>Add news</h1>
					</div>
					Rich text editor/TinyMCE support coming soon.
					<?php

						if( $_POST['submit'] ) {
			
							try {
								
								
								$title	 = $core->clean( $_POST['title'] );
								$desc	 = $core->clean( $_POST['desc'] );
								$cat	 = $core->clean( $_POST['cat'] );
								$article = htmlspecialchars_decode( $_POST['article'] );
								$time	 = time();
			
								if( !$title or !$cat or !$article ) {
			
									throw new Exception( "All fields are required." );
			
								}
								else {
									
									if( $editid ) {
										
										$db->query( "UPDATE news SET title = '{$title}', category = '{$cat}',`desc` = '{$desc}', article = '{$article}' WHERE id = '{$editid}'");
										
									}
									else {
									
										$db->query( "INSERT INTO news VALUES (NULL, '{$cat}', '{$title}', '{$desc}', '{$article}', '{$user->data['id']}', '{$time}');" );
									
									}
									
									echo "<div class=\"alert alert-success\">";
									echo "<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
									echo "<strong>Success!</strong>";
									echo "<br />";
									echo "Your article was published!.";
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
						
							$query = $db->query( "SELECT * FROM news_categories" );
							
							while( $array = $db->assoc( $query ) ) {
								
								if( $array['admin'] != '1' or ( $user->hasGroup( '4' ) or $user->hasGroup( '5' ) ) ) {
								
									if( $array['id'] == $data['category'] ) {
									
										$cats[$array['id'] . "_active"] = $array['name'];
										
									}
									else {
									
										$cats[$array['id']] = $array['name'];
									
									}
								
								}
								
							}
							
							echo $core->buildField( "text",
										"required",
										"title",
										"Title",
										"The article's title.",
										$data['title'],
										"",
										"Article's title." );
							
							echo $core->buildField( "text",
										"",
										"desc",
										"Description",
										"Short description.",
										$data['desc'],
										"",
										"A short desccription of your article." );
										
							echo $core->buildField( "text",
										"required",
										"cat",
										"Category",
										"",
										$data['desc'],
										"",
										"" );
							
							echo $core->buildField( "textarea",
										"required",
										"article",
										"Article",
										"Your article.",
										stripslashes( $data['article'] ),
										"",
										"" );
						?>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" name="submit" value="publish">Publish</button>
						</div>
					</form>