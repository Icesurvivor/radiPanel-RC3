<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

?>
					<div class="page-header">
						<h1>Manage news articles</h1>
					</div>
					
					Category:
					<select onchange="window.location = '?cat=' + this.value">
						<option>Please select...</option>
						<?php
							
							$clause = ( $user->hasGroup( '4' ) or $user->hasGroup( '5' ) ) ? '' : "WHERE admin != '1'";
							
							$query = $db->query( "SELECT * FROM news_categories {$clause}" );
							
							while( $array = $db->assoc( $query ) ) {
						
								echo "<option value=\"{$array['id']}\">{$array['name']}</option>";
						
							}
						
						?>
					</select>
					
					
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered bootstrap-datatable" id="datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Data</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
						
								if( $_GET['cat'] ) {
									
									$id = $core->clean( $_GET['cat'] );
									
									$clause = "WHERE category = '{$id}'";
									
								}
						
								$query = $db->query( "SELECT * FROM news {$clause}" );
								$num   = $db->num( $query );
						
						
								while( $array = $db->assoc( $query ) ) {
						
									echo "<tr id=\"news_{$array['id']}\">";
									
									$query2 = $db->query( "SELECT * FROM users WHERE id = '{$array['author']}'" );
									$array2 = $db->assoc( $query2 );
						
									$query3 = $db->query( "SELECT * FROM news_categories WHERE id = '{$array['category']}'" );
									$array3 = $db->assoc( $query3 );
									
									echo "<td class=\"center\">";
									echo $array['id'];
									echo "</td>";
									
									echo "<td class=\"center\">";
									echo "{$array['title']}<br /><span style=\"font-size: 10[x;\"><em>";
									echo "Posted by <strong>" . $array2['username'] . "</strong> on ";
									echo "<strong>" . date( "d/m/Y", $array['stamp'] ) . "</strong> in ";
									echo "<strong>" . $array3['name'] . "</strong></em>";
									echo "</span>";
									echo "</td>";
									
									echo "<td class=\"center\">";
									echo "<a class=\"btn btn-success\" href=\"news.add?id={$array['id']}\"><i class=\"icon-edit icon-white\"></i> Edit</a>";
									echo " <a class=\"btn btn-danger\" onclick=\"Radi.deletePM('4');\"><i class=\"icon-trash icon-white\"></i> Delete</a>";
									echo "</td>";
									
						
								}
						
								if( $num == 0 ) {
								
									echo "<div class=\"square bad\" style=\"margin-bottom: 0px;\">";
									echo "<strong>Sorry</strong>";
									echo "<br />";
									echo "There aren't any news articles in this category.";
									echo "</div>";
								
								}
						
							?>
						</tbody>
					</table>