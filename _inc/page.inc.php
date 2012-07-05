<?php
/*
 * @author Jia Jian
 * @version 2.0
 */
	class Page {
	
		/**
	  	 * Page builder for Page class - Builds a dynamic, HTML page.
		 * @param $page	- Required. Page to be converted.
		 * @more here		- more details.
		 * @return $page	- Returns the tidied up page.
		 */
		public function buildPage( $pgName ) {
		
			global $db, $core;
			
			$query	= $db->query( "SELECT * FROM _pages WHERE page = '{$pgName}'" );
			$array	= $db->assoc( $query );
			
			$result	= htmlspecialchars_decode( stripslashes( $array['content'] ) );
			
			return $result;
		
		}
		/**
	  	 * Page creator for Page class - Creates a PHP file.
		 * @param   $file	- Required. Document Root + Path name, it must include the filename too.
		 * @param   $pgName	- Required. The page name, include the .php too.
		 * @return $newFile	- It creates the file called $newFile with the buildPage() function inside it.
		 */
		public function createPage( $file, $pgName ) {
		
			global $db, $core;
			
			$newFile	= fopen( $file, "a+" );
			
			fwrite( $newFile, "<?php" . "\r\n" );
			fwrite( $newFile, "	if( !preg_match( \"/index.php/i\", \$_SERVER['PHP_SELF'] ) ) { die(); }" . "\r\n\r\n" );
			fwrite( $newFile, "	echo \$page->buildPage( \"$pgName\" );" . "\r\n" );
			fwrite( $newFile, "?>" . "\r\n" );
			
			fclose( $newFile );
			
			return true;
		}
		/**
	  	 * Page modifier for Page class - Builds a dynamic, HTML page.
		 * @param  $file	- Required. Document Root + Path name, it must include the filename too.
		 * @param  $pgName	- Required. The page name, include the .php too.
		 * @return $newFile	- It renames and overwrite the file with the appropriate parameters for buildPage()
		 */
		public function modifyPage( $file, $pgName ) {
		
			global $db, $core;
			
			$newFile	= fopen( $file, "w+" );
			
			fwrite( $newFile, "<?php" . "\r\n" );
			fwrite( $newFile, "	if( !preg_match( \"/index.php/i\", \$_SERVER['PHP_SELF'] ) ) { die(); }" . "\r\n\r\n" );
			fwrite( $newFile, "	echo \$page->buildPage( \"$pgName\" );" . "\r\n" );
			fwrite( $newFile, "?>" . "\r\n" );
			
			fclose( $newFile );
			
			return true;
		}
		
		/**
	  	 * Page deletion for Page class - Deletes the PHP page.
		 * @param  $file	- Required. Document Root + Path name, it must include the filename too.
		 */
		public function deletePage( $file ) {
		
			global $db, $core;
			
			unlink( $file );
			
			return true;
			
		}
		
	}
	
	$page	= new Page();
	
?>