<?php

    /**
     * session_regenerate_id(); was removed due to old sessions being cached.
     */
    
    class UserException extends Exception { }
     
    class User {
         
        private $sessionID;
        public  $sessionData;
        public  $data;
        public  $loggedIn;
        
        /**
          * Constructor - forms session and loads session data.
          * @global $db
          * @global $core
          */
        public function __construct() {
        
            global $db, $core;
            
            $this->clearUpSessions();
            
            $this->sessionID = $core->encrypt( session_id() );
            
            $this->createSession();
            
            $query = $db->query( "SELECT * FROM sessions WHERE session_id = '{$this->sessionID}'" );
            $this->sessionData = $db->assoc( $query );
            
            if( $this->sessionData['user_id'] ) {
            
                $this->loggedIn = true;
                
                $query      = $db->query( "SELECT * FROM users WHERE id = '{$this->sessionData['user_id']}'" );
                $this->data = $db->assoc( $query );
                
                $this->data['uGroupArray'] = explode( ",", $this->data['usergroups'] );
                
                $query = $db->query( "SELECT * FROM usergroups WHERE id = '{$this->data['displaygroup']}'");
                $array = $db->assoc( $query );
                
                $this->data['usergroup'] = $array;
                
                $this->data['fullUsername'] = "<span style=\"color: #{$array['colour']}\">" . $this->data['username'] . "</span>";
                $this->data['simpleUsername'] = $this->data['username'];
                
            }
        
        }
        
        private function createSession() {
            
            global $db, $core;
            
            $query = $db->query( "SELECT * FROM sessions WHERE session_id = '{$this->sessionID}'" );
            $num   = $db->num( $query );
            $result = $db->assoc( $query );

            $oldID = $this->sessionID;
            $time  = time();

            if( $num == 0 ) {

                $time = time();

                $query2 = $db->query( "SELECT id FROM users WHERE `id` = '{$result['user_id']}'" );
                $num2 = $db->num( $query2 );

                if( $num2 == 0 ) {
                 
                    $db->query( "INSERT INTO sessions VALUES ( NULL, '{$this->sessionID}', '0', '{$time}' );" );

                }
                else {

                    //session_regenerate_id();
                    $newID = $core->encrypt( session_id() );
                 
                    $db->query( "UPDATE sessions SET session_id = '{$newID}', stamp = '{$time}' WHERE user_id = '{$result['user_id']}'" );
                
                    $this->sessionID = $newID;

                }

            }
            else {

                //session_regenerate_id();
                $newID = $core->encrypt( session_id() );

                $db->query( "UPDATE sessions SET session_id = '{$newID}', stamp = '{$time}' WHERE session_id = '{$oldID}'" );
                
                $this->sessionID = $newID;
                
            }
         
        }
         
        public function hasGroup( $id ) {
             
            if( in_array( $id, $this->data['uGroupArray'] ) ) {
                return true;
            }
            else {
                return false;
            }
             
        }
         
        private function clearUpSessions() {
        
            global $params, $db;
             
            $time = strtotime( "{$params['user']['timeout']} ago" );
                         
            $db->query( "DELETE FROM sessions WHERE stamp < '{$time}'" );
            
        }
        
        public function destroySession() {
        
            global $db;
            
            $db->query( "DELETE FROM sessions WHERE session_id = '{$this->sessionID}'" );
        
        }
        
        /*
         * Custom functions, to be modified or deleted
         */
        private function assignUser( $id ) {
             
            global $db;
             
            $db->query( "UPDATE sessions SET user_id = '{$id}' WHERE session_id = '{$this->sessionID}'" );
         
        }
        
        public function forceSession( $id ) {
        
            global $db;
            
            $db->query( "UPDATE sessions SET user_id = '{$id}' WHERE session_id = '{$this->sessionID}'" );
        }
        
        public function login( $username, $password ) { 
             
            global $core, $db; 
             
            $username     = $core->clean( $username ); 
            $password     = $core->clean( $password ); 
            $password_enc = $core->encrypt( $password ); 
            
            $query = $db->query( "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password_enc}'" );
            $array = $db->assoc( $query );
            $num   = $db->num( $query );
            
            if( !$username or !$password ) {
            
                throw new UserException( "All fields are required." );
            
            }
            elseif( $num != 1 ) { 
             
                throw new UserException( "Invalid username/password." ); 
             
            }
            /*
            elseif( $array['timestamp'] > 1295280000 ) {
            
                throw new UserException( "For accounts created after the 18th of November 2010, please use your email address to login. Otherwise, login with your username." );
            }*/
            elseif( $array['banned'] == "1" ) { 

                throw new UserException( "You have been banned. Please contact a member of the staff team or at %SITE_ADMIN.EMAIL. with the subject line as [radiPanel] Support." ); 
                
            } 
            /*
            elseif( $array['verified'] == "0" ) {
            
                throw new UserException( "Your account has not been verified yet! Please check your email inbox for the verification link or request for a new one <a href=\"resend.php\">here</a>." );
               
            }*/
            else {
            
                $this->assignUser( $array['id'] );
                
                $time  = time();
            	$ip    = $_SERVER['REMOTE_ADDR'];
           	$db->query( "INSERT INTO shoutbox VALUES (NULL, {$array['id']}, 'has just logged in.', '{$time}', '{$ip}', '1', '0');" );
           	
           	return true;
            
            }
        
        }
        public function emailLogin( $username, $password ) {
            
            global $core, $db;
            
            $username     = $core->clean( $username );
            $password     = $core->clean( $password );
            $password_enc = $core->encrypt( $password );
             
            $query = $db->query( "SELECT * FROM users WHERE email = '{$username}' AND password = '{$password_enc}'" );
            
            $array = $db->assoc( $query );
            $num   = $db->num( $query );
            
            if( !$username or !$password ) {
             
                throw new UserException( "All fields are required." );
             
            }
            elseif( $num != 1 ) {
            
                throw new UserException( "Invalid login ID/password." );
            
            }
            else if ($array['banned'] == "1") {
            
                throw new UserException( "You have been banned. Please contact a member of the staff team or at me[at]jiajiann.com. with the subject line as Banned Account - $username." ); 
            }
            else if ($array['verified'] == "0") {
            
                throw new UserException( "Your account has not been verified yet! Please check your email inbox for the verification link or request for a new one <a href=\"resend.php\">here</a>." );
            }
            else { 
             
                $this->assignUser( $array['id'] );
                
                $time  = time();
            	$ip    = $_SERVER['REMOTE_ADDR'];
            	$db->query( "INSERT INTO shoutbox VALUES (NULL, {$array['id']}, 'has just logged in.', {$time}, '{$ip}', '1', '0');" );
            	
                return true; 
             
            } 
         
        }
        public function register( $username, $password, $password_retype, $email, $email_retype, $time, $ip ) {
			
		global $core, $db;
			
		$username     		= $core->clean( $username );
		$password     		= $core->clean( $password );
		$password_retype 	= $core->clean( $password_retype );
		$email			= $core->clean( $email );
		$email_retype		= $core->clean( $email_retype );
		$time			= time();
		$ip                     = $_SERVER['REMOTE_ADDR'];
		$password_enc		= $core->encrypt( $password );
		$password_retype_enc	= $core->encrypt( $password_retype );
			
		$query_name	= $db->query("SELECT * FROM users WHERE username = '{$username}'");
		$num_name	= $db->num( $query_name );
			
		$query_email	= $db->query("SELECT * FROM users WHERE email = '{$email}'");
		$num_email 	= $db->num( $query_email );
			
		if( ( $password != $password_retype ) or ( $password_enc != $password_retype_enc ) ) {
			
			throw new UserException( "The passwords you entered does not match!" );
				
		}
		elseif( $email != $email_retype ) {
			
			throw new UserException( "The emails you entered does not match!" );
			
		}
		elseif( $num_name != 0 ) {
			
			throw new UserException( "The username is already in use." );
			
		}
		elseif( $num_email != 0 ) {
			
			throw new UserException( "There is already an account registered under this email." );
			
		}
		else {
			$db->query( "INSERT INTO users (id, username, password, email, timestamp, timestamplast, ip, displaygroup, usergroups, verified) VALUES (NULL, '{$username}', '{$password_enc}', '{$email}', '$time', '$time', '$ip', '1', '1,', '1')");
			$query = $db->query("SELECT * FROM users WHERE username = '{$username}'");
			$array = $db->assoc( $query );
			$this->assignUser( $array['id'] );
			return true;
			
		}
		
	}
	public function overrideReg( $username, $password, $email, $email_retype, $time, $ip ) {
			
			global $core, $db;
			
			$username     			= $core->clean( $username );
			$password     			= $core->clean( $password );
			$email				= $core->clean( $email );
			$email_retype			= $core->clean( $email_retype );
			$time				= time();
			$ip                             = $_SERVER['REMOTE_ADDR'];
			$password_enc			= $core->encrypt( $password );
			
			$query_name	= $db->query("SELECT * FROM users WHERE username = '{$username}'");
			$num_name	= $db->num( $query_name );
			
			$query_email	= $db->query("SELECT * FROM users WHERE email = '{$email}'");
			$num_email 	= $db->num( $query_email );
			
			if( $email != $email_retype ) {
			
				throw new UserException( "The emails you entered does not match!" );
			
			}
			elseif( $num_name != 0 ) {
			
				throw new UserException( "The username is already in use." );
			
			}
			elseif( $num_email != 0 ) {
			
				throw new UserException( "There is already an account registered under this email." );
			
			}
			else {
				$db->query("INSERT INTO users (id, username, password, email, timestamp, timestamplast, ip, displaygroup, usergroups, verified) VALUES (NULL, '{$username}', '{$password_enc}', '{$email}', '$time', '$time', '$ip', '13', '1,13,', '1')");
				$query = $db->query("SELECT * FROM users WHERE username = '{$username}'");
				$array = $db->assoc( $query );
				return true;
			
			}
		
		}
		
    }
    
    $user = new User();

?>