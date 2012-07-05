<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

?>
					<div class="page-header">
						<h1>Forum</h1>
					</div>
					
					<div class="well">
						User CP here...
					</div>
					
					<?php
						include_once( "_res/forum/_forum.php" );
						
						$ttQuery = $db->query( "SELECT * FROM forum_threads" );
						$ttArray = $db->arr( $trQuery );
						$ttNum   = $db->num( $ttQuery );
					
						$ptQuery = $db->query( "SELECT * FROM forum_posts" );
						$ptArray = $db->arr( $prQuery );
						$ptNum   = $db->num( $ptQuery );
					
					?>
					<!-- Le well, simple inset effect. -->
					<div class="well">
						<h3>Board Statistics</h3>
						<ul>
							<li>Total posts: <?php echo $ptNum; ?></li>
							<li>Total threads: <?php echo $ttNum; ?></li>
						</ul>
					</div>