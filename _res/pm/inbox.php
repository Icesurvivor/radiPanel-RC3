							
<?php
	
	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
	
?>
					<h2>Inbox</h2>
						
							
					
					
					<?php
		
						$query = $db->query( "SELECT * FROM `personal_messages` WHERE `user` = '{$user->data['id']}'" );
						$num   = $db->num( $query );
							
						if( $num === 0 ) {
								
							echo "<div class=\"square bad\" style=\"margin-bottom: 0px;\">";
							echo "<strong>Sorry</strong>";
							echo "<br />";
							echo "You have no messages.";
							echo "</div>";
								
						}
						else {
					
					?>
					
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered bootstrap-datatable" id="datatable">
						<thead>
							<tr>
								<th>Status</th>
								<th>Title</th>
								<th>Dated</th>
								<th>From</th>
								<th>Actions</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
							while( $array = $db->arr( $query ) ) {
						
								$query1 = $db->query( "SELECT * FROM `users` WHERE `id` = '{$array['from']}'" );
								$array1 = $db->arr( $query1 );
					
							
								echo "<tr id=\"pm_{$array['id']}\">";
									echo "<td class=\"center\">";
										# check read staus.
										if( $array['read'] != 0 ) {
											echo "<i class=\"icon icon-folder-open\"></i> Read";
										}
										else {	
											echo "<i class=\"icon icon-folder-close\"></i> Unread";
										}
											
									echo "</td>";
									echo "<td class=\"center\">";
											
										echo "<a href=\"core.msg_center&do=view?id={$array['id']}\">";
										echo $array['title']; 
										echo "</a>";
											
									echo "</td>";
									echo "<td class=\"center\">";
										
										echo date( "g:ia d F Y", $array['date'] );
											
									echo "</td>";
									echo "<td class=\"center\">";
										
										echo $array1['username'];
											
									echo "</td>";
									echo "<td class=\"center\">";
										echo "<a class=\"btn btn-success\" href=\"core.msg_center?do=newMsg&r={$array['id']}&u={$array1['username']}\">";
										echo "<i class=\"icon-share icon-white\"></i>";
										echo " Reply";
										echo "</a> ";
										
										echo "<a class=\"btn btn-danger\" onclick=\"Radi.deletePM('{$array['id']}');\">";
										echo "<i class=\"icon-trash icon-white\"></i>";
										echo " Delete";
										echo "</a>";
											
									echo "</td>";
								echo "</tr>";
							}
						}
						?>
						</tbody>
					</table>