<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
	/*
	 * Included in _index.php
	 */	
	$query = $db->query( "SELECT * FROM forum WHERE category = '1' ORDER BY weight ASC" );
		
	while( $array = $db->arr( $query ) ) {
?>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th colspan="4"><a href="core.forumDisplay?id=<?php echo $array['id']; ?>"><?php echo $array['name']; ?></a></td>
							</tr>
							<tr>
								<th style="width:250px;">
									Forum
								</th>
								<th  style="width:200px;">
									Latest Post
								</th>
								<th style="width:30px;">
									Threads
								</th>
								<th style="width:30px;">
									Posts
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							
								$subQuery = $db->query( "SELECT * FROM forum WHERE parent = '{$array['id']}' ORDER BY weight ASC" );
								
								
								while( $subArr = $db->arr( $subQuery ) ) {
									
									$tQuery = $db->query( "SELECT * FROM forum_threads WHERE forumid = '{$subArr['id']}'" );
									$tArray = $db->arr( $tQuery );
									$tNum   = $db->num( $tQuery );
									
									$pQuery = $db->query( "SELECT * FROM forum_posts WHERE thread = '{$tArray['id']}' ORDER BY timestamp DESC" );
									$pArray = $db->arr( $pQuery );
									$pNum   = $db->num( $pQuery );
									
									$query3	= $db->query( "SELECT * FROM forum_threads WHERE id = '{$pArray['thread']}'" );
									$array3	= $db->assoc( $query3 );
									
									$query4	= $db->query( "SELECT * FROM users WHERE id = '{$pArray['user']}'" );
									$array4	= $db->assoc( $query4 );
							?>
							
							<tr>
								<td>
									<a href="core.forumDisplay?id=<?php echo $subArr['id']; ?>">
									<?php echo $subArr['name'];?></a><br />
									<?php echo $subArr['description'];?>
								</td>
								
								<td class="center">
									<?php
										if( $array3['title'] and $array4['username'] ) {
											echo "<a href=\"core.forumThread?id={$array3['id']}\">{$array3['title']}</a> by " . $array4['username'];
										}
										else {
											echo "No threads";
										}
									?>
								</td>
								<td class="center"><center><?php echo $tNum; ?></center></td>
								<td class="center"><center><?php echo $pNum; ?></center></td>
							</tr>
						
							<?php
								}
							?>
							
						</tbody>
					</table>
<?php
	}
?>							
					