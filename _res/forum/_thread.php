<?php

	/*
	 * Includes _post.php
	 */
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	
	$threadID = $core->clean( $_GET['id'] );
	
	$queryX = $db->query("SELECT * FROM forum_threads WHERE id = '$threadID'");
	$numX   = $db->num( $queryX );
	
	
	if( $num === 0 ) {
		die( "Display in alert block: Invalid thread ID!" );
	}
	
	
	while( $arrayX = $db->arr( $queryX ) ) {
	
		// Bypapermashup.com/easy-php-pagination/
		$targetpage = "core.forumThread?id=" . $threadID;
	
		/*
		 * Number of posts per page. Default is set to 8.
		 */
		$limit = 8;
	
		$query = $db->query( "SELECT COUNT(*) as num FROM forum_posts WHERE thread = '{$threadID}'" );
		$total_pages = $db->arr( $query );
		$total_pages = $total_pages[num];
	
		$stages = 3;
		$page = $db->escape( $_GET['page'] );
	
		if( $page ) {
			$start = ( $page - 1 ) * $limit; 
		}
		else {
			$start = 0;	
		}
		
		// Get page data
		$result = $db->query( "SELECT * FROM forum_posts WHERE thread = '{$threadID}' ORDER BY id ASC LIMIT $start, $limit " );
	
		// Initial page num setup
		if( $page == 0 ) {
			$page = 1;
		}
		$prev = $page - 1;	
		$next = $page + 1;							
		$lastpage = ceil( $total_pages / $limit );		
		$LastPagem1 = $lastpage - 1;					
	
		$paginate = "";
	
		if( $lastpage > 1 ) {	

			$paginate .= "<div class=\"pagination\">";
			$paginate .= "<ul>";
			
			
			if( $page > 1) {
				$paginate .= "<li><a href=\"$targetpage&page=$prev\">&laquo;</a></li> ";
			}
			else {
				$paginate .= "";	
			}
	
			// Pages	
			if( $lastpage < 7 + ( $stages * 2 ) ) {	
				for( $i = 1; $i <= $lastpage; $i++ ) {
					if( $i == $page ) {
						$paginate .= "<li class=\"active\"><a href=\"#\">$i</a></li> ";
					}
					else {
						$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li> ";}
				}
			}
			elseif( $lastpage > 5 + ( $stages * 2 ) ) {
				// Beginning only hide later pages
				if( $page < 1 + ( $stages * 2 ) ) {
					
					for ( $i = 1; $i < 4 + ( $stages * 2 ); $i++ ) {
						if( $i == $page ) {
							$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li> ";
						}
						else {
							$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li> ";
						}
					}
					$paginate .= "<li class=\"disabled\">...</li>";
					$paginate .= "<li><a href=\"$targetpage?page=$LastPagem1\">$LastPagem1</a></li> ";
					$paginate .= "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li> ";		
				}
				// Middle hide some front and some back
				elseif( $lastpage - ( $stages * 2 ) > $page && $page > ( $stages * 2 ) ) {
				
					$paginate .= "<li><a href='$targetpage&page=1'>1</a></li> ";
					$paginate .= "<li><a href='$targetpage&page=2'>2</a></li>";
					$paginate .= "<li class=\"disabled\">...</li>";
					
					for( $i = $page - $stages; $i <= $page + $stages; $i++ ) {
					
						if( $i == $page ) {
							$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li> ";
						}
						else {
							$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li> ";
						}
					}
					$paginate .= "<li class=\"disabled\">...</li>";
					$paginate .= "<li><a href=\"$targetpage&page=$LastPagem1\">$LastPagem1</a></li> ";
					$paginate .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li> ";
				}
				// End only hide early pages
				else {
					$paginate .= "<li><a href=\"$targetpage&page=1\">1</a></li> ";
					$paginate .= "<li><a href=\"$targetpage&page=2\">2</a></li> ";
					$paginate .= "<li class=\"disabled\">...</li>";
					for ( $i = $lastpage - ( 2 + ( $stages * 2 ) ); $i <= $lastpage; $i++ ) {
						if( $i == $page ) {
							$paginate .= "<li class=\"active\"<a href=\"#\">>$i</a></li> ";
						}
						else {
							$paginate .= "<li><a href=\"$targetpage&page=$i\">$i</a></li> ";
						}
					}
				}
			}
						
			// Next
			if( $page < $i - 1 ) { 
				$paginate .= "<li><a href=\"$targetpage&page=$next\">&raquo;</a></li>";
			}
			else {
				$paginate .= "";
			}
			
			$paginate .= "</ul>";
			$paginate .= "</div>";
		}
		echo "";
		
		/*
		 * Queries for the forum's name where the thread is in.
		 */
		$queryF = $db->query( "SELECT * FROM `forum` WHERE id = '{$arrayX['forumid']}'" );
		$arrayF = $db->assoc( $queryF );
?>
					<div class="page-header">
						<h1>Forum</h1>
					</div>
					
					<!-- Breadcrumbs -->
					<ul class="breadcrumb">
						<li><a href="#">Forums</a></li> <span class="divider">/</span>
						<li><?php echo $arrayF['name']; ?></a></li> <span class="divider">/</span>
						<li><?php echo $arrayX['title']; ?></li>
					</ul>
					
					
					<?php echo $paginate; ?>
					
					<a href="#post" class="btn btn-primary"><i class="icon-white icon-pencil"></i> Quick reply</a><br /><br />
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="2"><?php echo $arrayX['title']; ?></th>
							</tr>
							<tr>
								<th style="width:200px;">Poster</th>
								<th style="width:500px;">Content</th>
							</tr>
						<tbody>
						<?php
							$postUsers = array();
							
							while( $post = $db->arr( $result ) ) {
							
								/*
								 * Faster search algorithm.
								 * @author original author is chad I think? from ClubHabbo.net
								 */
								// We only fetch user information ONCE. This lowers the amount of queries if a user has posted multiple times.
								if ( !isset( $postUsers[$post['user']] ) ) {
									$getUserInfo = $db->query( "SELECT * FROM users WHERE id = '{$post['user']}'" );
									$postUsers[$post['user']]['info'] = $db->arr( $getUserInfo );
									
									$getUserPosts = $db->query("SELECT * FROM forum_posts WHERE user = '{$post['user']}'");
									$array = $db->assoc( $getUserPosts );
									$postUsers[$post['user']]['numPosts']   = $db->num( $getUserPosts );
									
									$getUserGroup = $db->query("SELECT * FROM usergroups WHERE id = '{$postUsers[$post['user']]['info']['displaygroup']}'");
									$postUsers[$post['user']]['group'] = $db->assoc( $getUserGroup );
								}
								// $postUsers[$post['user']]['info']['username'], $postUsers[$post['user']]['displaygroup'] etc
								$forumUser = "<span style=\"color: #{$postUsers[$post['user']]['group']['colour']}\">" . $postUsers[$post['user']]['info']['username'] . "</span>";
						?>
							<tr>
								<td>
									<center>
										<strong><?php echo $forumUser; ?></strong><br />
										<img style="height:128px;width:128px;" src="https://si0.twimg.com/profile_images/1665717976/large_235897_reasonably_small.png" /><br />
										<?php echo $postUsers[$post['user']]['numPosts']; ?> posts<br />
									
									</center>
									<?php
										// Moderation Tools
										if( $user->data['displaygroup'] > 4 ) {
									?>
										<div class="btn-toolbar" style="margin-top: 5px;">
											<div class="btn-group">
												<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
													Action
													<span class="caret"></span>
												</a>
												<ul class="dropdown-menu">
													<li><a href="core.forumPostEdit?id=<?php echo $post['id']; ?>">Edit</a></li>
												</ul>
											</div>
											
											<div class="btn-group">
												<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
													Mod Info
													<span class="caret"></span>
												</a>
												<ul class="dropdown-menu">
													<li><a href="#">User Info</a></li>
													<li><a href="#">Mod</a></li>
												</ul>
											</div>
										</div>
									<?php
										}
									?>
									
								</td>
								<td>
									<div style="border-bottom: 1px solid #dddddd;padding-top:3px;padding-bottom:2px;">
										Posted <?php echo $parser->get_elapsedtime( $post['timestamp'] ); ?>
									</div><br />
									
									<?php echo nl2br( $post['post'] ); ?>
								</td>
                					</tr>
                					
						<?php
							}
						?>
						
					</tbody>
				</table>
				
				<?php echo $paginate; ?>
<?php
	}
	include_once( "_res/forum/_post.php" );
?>