<?php 

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	$forumID = $core->clean( $_GET['id'] );	
	
	$queryX = $db->query( "SELECT * FROM forum WHERE id = '{$forumID}' ORDER BY weight ASC" );
	$numX   = $db->num( $queryX );
	
	if( $numX == 0 ) {
		echo "Invalid Forum ID!";
	}
    	
	$queryY = $db->query( "SELECT * FROM forum WHERE id = '{$forumID}'" );
	$arrayY = $db->arr( $queryY );

        // http://papermashup.com/easy-php-pagination/
	$targetpage	= "core.forumDisplay?id=" . $forumID;
	$limit		= 15;
	
	$query		= $db->query( "SELECT COUNT(*) as num FROM `forum_threads` WHERE forumid = '{$forumID}'" );
	$total_pages	= $db->arr( $query );
	$total_pages	= $total_pages[num];
	
	$stages		= 3;
	$page		= $core->clean( $_GET['page'] );
	
	if( $page ) {
		$start = ( $page - 1 ) * $limit;
	}
	else {
		$start = 0;
	}	
	
	$query2 = $db->query( "SELECT * FROM `forum_threads` WHERE forumid = '{$forumID}' ORDER BY timestamp DESC LIMIT $start, $limit " );
	
	if ( $page == 0 ) {
		$page = 1;
	}
	
	$prev		= $page - 1;	
	$next		= $page + 1;							
	$lastpage	= ceil( $total_pages / $limit );		
	$LastPagem1	= $lastpage - 1;					
	
	$paginate = "";
	
	if( $lastpage > 1 ) {	

		$paginate .= "<div class=\"pagination\">";
		$paginate .= "<ul>";
		
		if( $page > 1 ) {
			$paginate .= "<li><a href=\"{$targetpage}&page={$prev}\">&laquo;</a></li>";
		}
		else {
			$paginate .= "";
		}

		if( $lastpage < 7 + ( $stages * 2 ) ) {
		
			for( $i = 1; $i <= $lastpage; $i++ ) {
			
				if( $i == $page ) {
					$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li>";
				}
				else {
					$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li>";
				}
			}
		}
		elseif( $lastpage > 5 + ( $stages * 2 ) ) {
		
			if( $page < 1 + ( $stages * 2 ) ) {
				
				for ( $i = 1; $i < 4 + ( $stages * 2 ); $i++ ) {
				
					if( $i == $page ) {
						$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li>";
					}
					else{
						$paginate .= "<li><li><a href=\"$targetpage&page=$i\">$i</a></li>";
					}
				}
				$paginate .= "<li class=\"disabled\"><a href=\"#\">...</a></li>";
				$paginate .= "<li><a href=\"$targetpage?page=$LastPagem1\">$LastPagem1</a></li>";
				$paginate .= "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
			}
			else if( $lastpage - ( $stages * 2 ) > $page && $page > ( $stages * 2 ) ) {
			
				$paginate .= "<li><a href=\"$targetpage&page=1\">1</a></li>";
				$paginate .= "<li><a href=\"$targetpage&page=2\">2</a></li>";
				$paginate .= "<li class=\"disabled\"><a href=\"#\">...</a></li>";
				
				for( $i = $page - $stages; $i <= $page + $stages; $i++ ) {
				
					if( $i == $page ) {
						$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li>";
					}
					else {
						$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li>";
					}
				}
				
				$paginate .= "<li class=\"disabled\"><a href=\"#\">...</a></li>";
				$paginate .= "<li><a href=\"$targetpage&page=$LastPagem1\">$LastPagem1</a></li>";
				$paginate .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
			}
			else {
				$paginate .= "<li><a href=\"$targetpage&page=1\">1</a></li>";
				$paginate .= "<li><a href=\"$targetpage&page=2\">2</a></li>";
				$paginate .= "<li class=\"disabled\"><a href=\"#\">...</a></li>";
				
				for( $i = $lastpage - ( 2 + ( $stages * 2 ) ); $i <= $lastpage; $i++ ) {
				
					if ( $i == $page ) {
						$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li>";
					}
					else {
						$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li>";
					}
				}
			}
		}
		
		if ( $page < $i - 1 ) {
			$paginate .= "<li><a href=\"$targetpage&page=$next\">&raquo;</a></li>";
		}
		else {
			$paginate .= "";
		}
		
		$paginate .= "</ul>";
		$paginate .= "</div>";
	}
	echo "";

?>
					<div class="page-header">
						<h1>Forum</h1>
					</div>
					
					<?php echo $paginate; ?></span>
	
<?php
	
	while( $arrayX = $db->arr( $queryX ) ) {

?>
					
					
					<!-- Breadcrumbs -->
					<ul class="breadcrumb">
						<li><a href="#">Forums</a></li> <span class="divider">/</span>
						<li><?php echo $arrayX['name']; ?></a></li>
					</ul>
					
					<?php
						if( $arrayY['posting'] != 0 ) {
							echo "<a href=\"core.forumCreate?frm={$forumID}\" class=\"btn btn-primary\">";
							echo "<i class=\"icon-white icon-pencil\"></i> New Thread</a><br /><br />";
						}
					?>

					
					<table class="table table-striped table-bordered">
						<?php
						
							$subQuery	= $db->query( "SELECT * FROM forum WHERE parent = '{$arrayX['id']}' ORDER BY weight ASC" );
							$subNum		= $db->num( $subQuery );
							
								
							/* check for sub-forums. */
							if( $subNum == 0 ) {
							
							}
							else {
							
						?>
					
					
						<thead>
							<tr>
								<th>
									Forum
								</th>
								<th>
									Latest Post
								</th>
								<th>
									Threads
								</th>
								<th>
									Posts
								</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
							
							while( $subArr = $db->arr( $subQuery ) ) {
									
								$tQuery = $db->query( "SELECT * FROM `forum_threads` WHERE forumid = '{$subArr['id']}'" );
								$tNum   = $db->num( $tQuery );
								$tArray = $db->arr( $tQuery );
								
								$q = $db->query( "SELECT * FROM `forum_posts` WHERE thread = '{$tArray['id']}' ORDER BY timestamp DESC" );
								$a = $db->assoc( $q );
								
								$q2 = $db->query( "SELECT * FROM `users` WHERE id = '{$a['user']}'" );
								$a2 = $db->assoc( $q2 );
							
						?>
						
						
							<tr class="center">
								<td>
				                			<a href="core.forumDisplay?id=<?php echo $subArr['id']; ?>"><?php echo $subArr['name'];?></a><br />
				                			<span style="padding-left:3px;font-size:12px;"><?php echo $subArr['description'];?></span>
				                		</td>
								<td>
									%s by %s
								</td>
			                			<td>
			                				<center>
			                					---
			                				</center>
			                			</td>
			                			<td>
			                				<center>
			                					---
			                				</center>
			                			</td>
							</tr>
							
						<?php	
								}
				        	
				        		}
				        		
				        	?>
			        	
				        	<thead>
							<tr>
								<th>
									Thread
								</th>
								<th>
									Last Post
								</th>
								<th>
									Threads
								</th>
								<th>
									Views
								</th>
							</tr>
						</thead>
				
						<?php
				            		while( $row = $db->arr( $query2 ) ) {
				            			$q = $db->query( "SELECT * FROM `forum_posts` WHERE thread = '{$row['id']}' ORDER BY timestamp DESC" );
				            			$a = $db->assoc( $q );
				            			
				            			//$q2 = $db->query( "SELECT * FROM `forum_threads` WHERE id ={$a['thread']}" );
				            			//$a2 = $db->query( $q2 );
				            			
				            			//$q3 = $db->query( "SELECT * FROM users WHERE id = {$a['user']}" );
				            			//$a3 = $db->query( $q3 );
				            	?>
	            	       
						<tr>
							<td>
								<a href="core.forumThread?id=<?php echo $row['id']; ?>">
									<?php echo $row['title']; ?>
								</a>
							</td> 
							<td>
								<?php echo "<a href=\"core.forumThread?id={$a2['id']}\">{$a2['title']}</a> by {$a3['username']}"; ?>
							</td>
							<td>
								<center>
									---
								</center>
							</td>
							<td>
								<center>
									---
								</center>
							</td>          
			                	</tr>
	                	
						<?php
							}
						?>
					</table>
<?php
	}
?>