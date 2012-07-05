<?php
	class Parser {
		/**
		 * ParseBB - Parse bulletin board codes in message.
		 * @param $message - required. input message.
		 * @return $message - replaces all bulletin board tags w/ html tags.
		 */
		public function parsebb( $message ) {
			$find = array (
				"@\n@",
				"/\[url\=(.+?)\](.+?)\[\/url\]/is",
				//"@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@is",
				"/\[b\](.+?)\[\/b\]/is",
				"/\[i\](.+?)\[\/i\]/is",
				"/\[u\](.+?)\[\/u\]/is",
				"/\[color\=(.+?)\](.+?)\[\/color\]/is",
				"/\[colour\=(.+?)\](.+?)\[\/colour\]/is",
				"/\[center\](.+?)\[\/center\]/is",
				"/\[right\](.+?)\[\/right\]/is",
				"/\[left\](.+?)\[\/left\]/is",
				"/\[email\](.+?)\[\/email\]/is"
			);
			$replace = array (
				"<br />",
				"<a href=\"$1\" target=\"_blank\">$2</a>",
				//"<a href=\"\\0\">\\0</a>",
				"<strong>$1</strong>",
				"<em>$1</em>",
				"<span style=\"text-decoration:underline;\">$1</span>",
				"<font color=\"$1\">$2</font>",
				"<font color=\"$1\">$2</font>",
				"<div style=\"text-align:center;\">$1</div>",
				"<div style=\"text-align:right;\">$1</div>",
				"<div style=\"text-align:left;\">$1</div>",
				"<a href=\"mailto:$1\" target=\"_blank\">$1</a>"
			);
			//$message = htmlspecialchars( $message );
			$message = preg_replace( $find, $replace, $message );
			
			return $message;
		}
		
		/**
		 * http://go.jiajiann.com/rcm9EJ
		 * @param $time - UNIX timestamp.
		 * @return date time - returns date and time.
		 */
		public function get_elapsedtime( $time ) {
		
			$gap = time() - $time;
			
			if ( $gap < 5 ) {
				return "less than 5 seconds ago";
			}
			else if ( $gap < 10 ) {
				return "less than 10 seconds ago";
			}
			else if ( $gap < 20 ) {
				return "less than 20 seconds ago";
			}
			else if ( $gap < 40 ) {
				return "half a minute ago";
			}
			else if ( $gap < 60 ) {
				return "less than a minute ago";
			}
			
			$gap = round( $gap / 60 );
			if ( $gap < 60 )  {
				return $gap . " minute" . ( $gap > 1 ? 's' : '' ) . " ago";
			}

			$gap = round( $gap / 60 );
			if ( $gap < 24 )  {
				return "about " . $gap . " hour" . ( $gap > 1 ? 's' : '' ) . " ago";		
			}
			
			return "on " . date('j M Y g:ia', $time);
		}
	}
	
	$parser = new Parser();
?>