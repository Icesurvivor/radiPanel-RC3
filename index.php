<?php
	require_once( "_inc/glob.php" );
	
	/*
	 * constants. can be scrapped soon
	 */
	define( "TPL_PATH", "assets" );
	define( "PANEL_PATH", "http://jiajiann.com/extranet2/" );
	
	/*
	 * Edited.
	 * For smoother login only. You can remove this and stick to the old core->redirect() function
	 */
	if( $_POST['submit'] ) {
	
		try {
			$username = $core->clean( $_POST['username'] );
			$password = $core->clean( $_POST['password'] );
			$iplast   = $_SERVER['REMOTE_ADDR']; 
			$timestamplast = time();
										
			if( preg_match( "^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$^", $username ) ) {
										
				$user->emailLogin( $username, $password );
				$db->query( "UPDATE users SET timestamplast = '{$timestamplast}', iplast = '{$iplast}' WHERE email = '$username'" );
				header( "location: ?" );
											
			}
			else {
											
				$user->login( $username, $password );
				$db->query( "UPDATE users SET timestamplast = '{$timestamplast}', iplast = '{$iplast}' WHERE username = '$username'" );
				header( "location: ?" );
					
			}
		}
		catch( UserException $e ) {
		
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		
		<title>radiPanel</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Le css -->
		<?php
			require_once( TPL_PATH . "/css.txt" );
		?>
		
	</head>
	
	<body data-spy="scroll" data-target=".subnav" data-offset="50">
	
	
	<?php
		if( $user->loggedIn ) {
		
	?>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#">radiPanel</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li class="active">
								<a href="#">Home</a>
							</li>
							<li class="dropdown">
								<?php
									$q = $db->query( "SELECT * FROM `personal_messages` WHERE `user` = '{$user->data['id']}' AND `read` = 0" );
									$n = $db->num( $q );
								?>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Messages
									<?php
										if( $n > 0 ) {
											echo "<span class=\"badge badge-important\">{$n}</span>";
										}
									?>
									 
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#"><i class="icon-inbox"></i> Message Center</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#">Separated link</a>
									</li>
								</ul>
							</li>
							
						</ul>
						
						<form class="navbar-search pull-left" action="">
							<input type="text" class="search-query span2" placeholder="Search">
						</form>
					</div>
					
					<!-- Right side -->
					<div class="btn-group pull-right" id="signin">
						<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user icon-white"></i> <span id="username"><?php echo $user->data['username']; ?></span>
							<span class="caret"></span>
						</a>
						
						<ul class="dropdown-menu">
							<li>
								<a id="member-profile" href="#">Profile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="#"><i class="icon-edit"></i> Settings</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="core.logout">Sign Out</a>
							</li>
						</ul>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="container">
			<header class="jumbotron subhead" id="overview">
				<h1>Welcome!</h1>
				<p class="lead">What do you want to do for today?</p>
				
				<div class="subnav">
					<ul class="nav nav-pills">
						<li>
							<a href="#home">Home</a>
						</li>
					</ul>
				</div>
				
			</header>
			<section id="home">
				
				<div class="row">
				
					<div class="span3">
						<!-- well sidebar-nav not needed -->
						<div class="well" style="padding: 8px 0;">
							<ul class="nav nav-list">
							
								<?php
									/*
									 * Sidebar navigation.									 *
									 */
		
									$url = $_GET['url'] ? $core->clean( $_GET['url'] ) : 'core.home';
					
									$query3 = $db->query( "SELECT * FROM menu WHERE url = '{$url}'" );
									$array3 = $db->assoc( $query3 );
				
									if( !$array3['usergroup'] ) {
									
										$array3['usergroup'] = "invalid";
									
									}
				
									$query = $db->query( "SELECT * FROM usergroups WHERE weight >=0 ORDER BY weight ASC" );
				
									while( $array = $db->assoc( $query ) ) {
				
										if( in_array( $array['id'], $user->data['uGroupArray'] ) ) {
					
								?>
								
								<li class="nav-header" style="color: #<?php echo $array['colour']; ?>;"><?php echo $array['name']; ?></li>
								
								<?php
										$query2 = $db->query( "SELECT * FROM menu WHERE usergroup = '{$array['id']}' AND hidden != 1 ORDER BY weight ASC" );
									
											while( $array2 = $db->assoc( $query2 ) ) {
												if( $_GET['url'] == $array2['url'] ) {
											
													echo "<li class=\"active\">";
											
												}
												else {
													echo "<li>";
												}
												
												echo "<a href=\"" . PANEL_PATH . "{$array2['url']}\">{$array2['text']}</a><li>\n";
											}
										}
									}
								?>
								
								
							</ul>
						</div>
					</div>
					
					
					
					<div class="span9">
					
						<?php
							/*
							 * Gets the specified resource file.
							 */
							 
							if( !in_array( $array3['usergroup'], $user->data['uGroupArray'] ) ) {
								echo "<div class=\"page-header\"><h1>Error!</h></div>";
								echo "Permission denied.";
						
							}
							elseif( !@include_once( $array3['resource'] ) ) {
								echo "<div class=\"page-header\"><h1>Error!</h></div>";
								echo "Error has occurred looking for " . $array3['resource'];
							
							}
						?>
						
					</div>
				</div>
			</section>
			
			<br />

			<footer class="footer">
				<p>
					&copy; JiaJian, 2012.
				</p>
				
				<p class="pull-right">
					<a href="#">Back to top</a>
				</p>
			</footer>
		</div>
		
	<?php
		}
		else {
	?>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#">radiPanel</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li class="active">
								<a href="#">Home</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Something else here</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#">Separated link</a>
									</li>
								</ul>
							</li>
							
						</ul>
						
						<form class="navbar-search pull-left" action="">
							<input type="text" class="search-query span2" placeholder="Search">
						</form>
					</div>
					
					<!-- Right side -->
					<div class="btn-group pull-right" id="signin">
						<a class="btn btn-danger" data-toggle="dropdown" href="#login">
							<i class="icon-user icon-white"></i> <span id="username">Sign in</span>
						</a>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="container">
		
			<header class="jumbotron subhead" id="overview">
				<h1>radiPanel</h1>
				<p class="lead">Welcome to SITE_NAME!</p>
				
				<div class="subnav">
					<ul class="nav nav-pills">
						<li>
							<a href="#login">Login</a>
						</li>
					</ul>
				</div>
				
			</header>
			
			<section id="about">
				<div class="page-header">
					<h1>Login</h1>
				</div>
				
				<div class="row">
					<div class="span12">
						<?php
							if( $_POST['submit'] ) {
		
								try {
		
									$username = $core->clean( $_POST['username'] );
									$password = $core->clean( $_POST['password'] );
									$iplast   = $_SERVER['REMOTE_ADDR']; 
									$timestamplast = time();
									
									if( preg_match( "^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$^", $username ) ) {
									
										//$user->emailLogin( $username, $password );
										//$db->query( "UPDATE users SET timestamplast = '{$timestamplast}', iplast = '{$iplast}' WHERE email = '$username'" );
										
										
									}
									else {
									
										//$user->login( $username, $password );
										//$db->query( "UPDATE users SET timestamplast = '{$timestamplast}', iplast = '{$iplast}' WHERE username = '$username'" );
										
										
									}
		
								}
								catch( UserException $e ) {
		
									echo "<div class=\"alert alert-error\">";
									echo "<a class=\"close\">&times;</a><strong>Error</strong>";
									echo "<br />";
									echo $e->getMessage();
									echo "</div>";
		
								}
		
							}
						?>
						
						<!-- Login form -->
						<form class="form-horizontal well" action="" method="post">
						
							<div class="control-group">
								<label class="control-label" for="username">Username</label>
								<div class="controls">
									<input type="text" class="input-medium" placeholder="Username" name="username" required data-validation-required-message="Required!">
									<p class="help-block">Your username, duh!</p>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="password">Password</label>
								<div class="controls">
									<input type="password" class="input-medium" placeholder="Password" name="password" required data-validation-required-message="Required!">
									<p class="help-block">Forgot your password? Reset it %home.resetPw</p>
								</div>
							</div>
							
							<div class="form-actions">
								<button type="submit" class="btn" name="submit" value="Let me in!">Let me in!</button>
							</div>
						</form>
						<!-- //End Login form -->
					</div>
				</div>
			</section>
			
			<br />

			<footer class="footer">
				<p>
					&copy; SITE_NAME, 2012.
				</p>
				
				<p class="pull-right">
					<a href="#">Back to top</a>
				</p>
			</footer>
		</div>
		
		
		<?php
			}
			require_once( TPL_PATH . "/footer.txt" );
		?>
	</body>

</html>