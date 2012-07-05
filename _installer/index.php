<?php
	//require_once( "../_inc/glob.php" );
	
	
	define( "TPL_PATH", "../assets" );
	
	function clean( $input, $fordb = true ) {
			
		//is it an array?
		if( is_array( $input ) ) {
		
			//yes it is! let's clean each item individually.
			foreach( $input as $key => $value ) {
			
				$input[$key] = $this->clean( $value, $fordb );
			
			}
			
			return $input;
	
		}
		else {
		
			//okay, it isn't, so let's go and tidy it up.
			$input = trim( $input );
			$input = htmlentities( $input, ENT_COMPAT );	
					
			if( $fordb == true ) {
					
				$input = addslashes( $input );
					
			}
			
			return $input;
		
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
								<a href="#">Installer</a>
							</li>
							
						</ul>
					</div>
					
				</div>
			</div>
		</div>
		
		
		
		<div class="container">
			<header class="jumbotron subhead" id="overview">
				<h1>radiPanel Installer</h1>
				<p class="lead">Thank you for choosing radiPanel! Follow the steps and %MOREINFO</p>
				
				<div class="subnav">
					<ul class="nav nav-pills">
						<li>
							<a href="#install">Install</a>
						</li>
					</ul>
				</div>
				
			</header>
			
			<section id="install">
				<div class="page-headeR">
					<h1>Install</h1>
				</div>
				
				<div class="row">
					<div class="span12">
					
			<?php

				if( $_POST['submit'] ) {

					try {

						$sql_hostname   = clean( $_POST['sql_hostname'] );
						$sql_username   = clean( $_POST['sql_username'] );
						$sql_password   = clean( $_POST['sql_password'] );
						$sql_database   = clean( $_POST['sql_database'] );

						//$radio_ip       = clean( $_POST['radio_ip'] );
						//$radio_port     = clean( $_POST['radio_port'] );
						//$radio_password = clean( $_POST['radio_password'] );

						$user_username  = clean( $_POST['user_username'] );
						$user_password  = clean( $_POST['user_password'] );

						$misc_timeout   = clean( $_POST['misc_timeout'] );

						$salt1 = md5( mt_rand() );
						$salt1 = str_split( $salt1, 10 );
						$salt1 = $salt1[0];

						$salt2 = md5( mt_rand() );
						$salt2 = str_split( $salt2, 10 );
						$salt2 = $salt2[0];

						if( !$sql_hostname or !$sql_username or !$sql_database
							 or !$user_username or !$user_password
							 or !$misc_timeout ) {

							throw new Exception( "All fields are required!" );

						}
						elseif( !$conn = @mysql_connect( $sql_hostname, $sql_username, $sql_password ) ) {

							throw new Exception( "Your MySQL server information seems to be invalid." );

						}
						elseif( !@mysql_select_db( $sql_database, $conn ) ) {

							throw new Exception( "Your MySQL database doesn't exist." );

						}
						else {

							$config = file_get_contents( "glob.php" );

							$config = str_replace( "conf_hostname", $sql_hostname, $config );
							$config = str_replace( "conf_username", $sql_username, $config );
							$config = str_replace( "conf_password", $sql_password, $config );
							$config = str_replace( "conf_database", $sql_database, $config );

							$config = str_replace( "conf_salt1", $salt1, $config );
							$config = str_replace( "conf_salt2", $salt2, $config );

							$config = str_replace( "conf_timeout", $misc_timeout, $config );

							file_put_contents( "../_inc/glob.php", $config );

							$sql = file( "_inst.sql" );

							foreach( $sql as $sql_line ) {

								if( trim($sql_line ) != "" and strpos( $sql_line, "--" ) === false ) {

									//echo $sql_line . "<br />";
									mysql_query( $sql_line );

								}

							}

							$user_password = $core->encrypt( $user_password );

							mysql_query( "INSERT INTO users VALUES (NULL, '{$user_username}', '{$user_password}', '', '', '5', '1,2,3,4,5');" );
							//mysql_query( "INSERT INTO connection_info VALUES (NULL, '{$radio_ip}', '{$radio_port}', '{$radio_password}', '1', '{$_SERVER['REMOTE_ADDR']}');" );

							
							echo "<div class=\"alert alert-success\">";
							echo "<a class=\"close\">&times;</a><strong>Installed!</strong>";
							echo "<br />";
							echo "radiPanel has been successfully installed!<br /><br />Security Warning! Please delete the /_installer directory before using your panel.";
							echo "</div>";
							

						}

					}
					catch( Exception $e ) {

						echo "<div class=\"alert alert-error\">";
						echo "<a class=\"close\">&times;</a><strong>Error</strong>";
						echo "<br />";
						echo $e->getMessage();
						echo "</div>";

					}

				}
			?>
						<form class="form-horizontal well" action="" method="post">
						
							<!-- Database conf options -->
							<h3>Database Configuration - MySQL</h3>
							<div class="control-group">
								<label class="control-label" for="sql_hostname">MySQL hostname</label>
								<div class="controls">
									<input type="text" class="input-medium" placeholder="MySQL Hostname" name="sql_hostname" required>
									<p class="help-block">Your hostname, usually it should be localhost.</p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sql_username">MySQL username</label>
								<div class="controls">
									<input type="text" class="input-medium" placeholder="MySQL Username" name="sql_username" required>
									<p class="help-block">Your MySQL username.</p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sql_password">MySQL password</label>
								<div class="controls">
									<input type="password" class="input-medium" name="sql_password" required>
									<p class="help-block">Your MySQL password.</p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sql_password">Database name</label>
								<div class="controls">
									<input type="text" class="input-medium" name="sql_database" required>
									<p class="help-block">Your MySQL database name.</p>
								</div>
							</div>
							
							
							<!-- User conf -->
							<h3>User Configuration</h3>
							<div class="control-group">
								<label class="control-label" for="user_username">Username</label>
								<div class="controls">
									<input type="text" class="input-medium" name="user_username" required>
									<p class="help-block">Your account username, this will be given admin permissions!</p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="user_password">Password</label>
								<div class="controls">
									<input type="password" class="input-medium" name="user_password" required>
									<p class="help-block">Your password. Keep it safe!</p>
								</div>
							</div>
							
							
							<!-- Miscellaneous config options-->
							<h3>Miscellaneous Configuration</h3>
							<div class="control-group">
								<label class="control-label" for="misc_timeout">Login Timeout</label>
								<div class="controls">
									<select name="misc_timeout" id="misc_timeout">
										<option value="10 minutes">10 minutes</option>
										<option value="15 minutes">15 minutes</option>
										<option value="20 minutes">20 minutes</option>
										<option value="30 minutes">30 minutes</option>
										<option value="45 minutes">45 minutes</option>
										<option value="60 minutes">60 minutes</option>
										<option value="never">Never</option>
									</select>
								</div>
							</div>
							
							
							<!-- Form buttons -->
							<div class="form-actions">
								<p>Please ensure all information are correct!</p>
								<button type="submit" class="btn btn-success" name="submit" value="G!">Ready!</button>
							</div>
						</form>
							
					</div>
				</div>
			</section>
			
			<!-- Le footer -->
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
			
			require_once( TPL_PATH . "/footer.txt" );
		?>
	</body>
</html>