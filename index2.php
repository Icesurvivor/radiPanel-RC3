<?php
	require_once( "_inc/glob.php" );
	
	// Constant. Path to includes, without trailing /
	define( "CONST_PATH", "../assets/_inc" );

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		
		<title>Bootstrap, from Twitter</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		
		<!-- Le styles -->
		
		<?php
			@require_once( CONST_PATH . "/css.txt" );
		?>
		
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
			
		</style>
		
	</head>
	
	<body data-spy="scroll" data-target=".subnav" data-offset="50">
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#">Project name</a>
					<div class="btn-group pull-right">
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user"></i> Username
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="#">Profile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="#">Sign Out</a>
							</li>
						</ul>
					</div>
					<div class="nav-collapse">
						<ul class="nav">
							<li class="active">
								<a href="#">Home</a>
							</li>
							<li>
								<a href="#about">About</a>
							</li>
							<li>
								<a href="#contact">Contact</a>
							</li>
						</ul>
					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span3">
					<div class="well sidebar-nav">
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
												
												echo "<a href=\"{$array2['url']}\">{$array2['text']}</a><li>\n";
								?>
								<?php
											}
										}
									}
								?>
						</ul>
					</div>
					<!--/.well -->
				</div>
				<!--/span-->
				<div class="span9">
					<div class="hero-unit">
						<h1>Welcome boss!</h1>
						<p>So <?php echo $user->data['fullUsername']; ?>, what do you want to do today?</p>
					</div>
					
				</div>
				<!--/span-->
			</div>
			<!--/row-->
			<hr>
			<footer>
				<p>&copy; Company 2012</p>
			</footer>
		</div>
		<!--/.fluid-container-->
		
		
		
		<!-- Le javascript -->
    		<!-- Placed at the end of the document so the pages load faster -->
    		
		<?php
			@require_once( CONST_PATH . "/footer.txt" );
		?>
		
	</body>

</html>