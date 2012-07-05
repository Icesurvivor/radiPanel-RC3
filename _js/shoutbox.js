/**
  * @author: HabbCrazy owner
  * @desc: idletime: 2 minutes
  */
idle = false;

var RadiShoutClass = Class.create( {
	
	initialize: function() {
		
		this.getShouts();						
		setInterval("RadiShout.getShouts()", 2000);
		
	},
	
	postShout: function() {
		var data = $( 'shoutbox' ).serialize( true );
		new Ajax.Request( '_res/ajax_shoutbox.php', { method: 'post', parameters: data } );
		$( 'shoutbox_message' ).value = "";
	},

	getShouts: function() {
		
		if( !idle ) {
			new Ajax.Updater( 'shoutbox_area', '_res/ajax_shoutbox.php', { method: 'post', parameters: { mode: 'getShouts' } } );
			new Ajax.Updater( 'shoutbox_announcement', '_res/ajax_shoutbox.php', { method: 'post', parameters: { mode: 'getAnnouncements' } } );
		}
		
	},
	
	adminCommand: function( type ) { 
	
		if( type == 1 ) {
			
			$( 'shoutbox_message' ).value = "/prune";
			RadiShout.postShout();
			
		}
		
		else if( type == 2 ) {
			
			var username = prompt( "Enter username to ban" );
			
			if( username ) {
				
				$( 'shoutbox_message' ).value = "/ban " + username;
				RadiShout.postShout();
								
			}
			
		}
		
		else if( type == 3 ) {
			
			var username = prompt( "Enter username to unban" );
			
			if( username ) {
				
				$( 'shoutbox_message' ).value = "/unban " + username;
				RadiShout.postShout();
								
			}	
					
		}
	
	},
	
} );

var Notifier = Class.create({
	
	_events: [[window, 'scroll'], [window, 'resize'], [document, 'mousemove'], [document, 'keydown']],
	_timer: null,
	_idleTime: null,
	
	initialize: function( time ) {
		
		this.time = time;
		
		this.initObservers();
		this.setTimer();
		
	},
	
	initObservers: function() {
		
		this._events.each( function( e ) {
			Event.observe( e[0], e[1], this.onInterrupt.bind( this ) )
		}.bind( this ) );
		
	},
	
	onInterrupt: function() {
		
		document.fire( 'state:active', { idleTime: new Date() - this._idleTime } );
		this.setTimer();
		
	},
	
	setTimer: function() {
		
		clearTimeout( this._timer );
		this._idleTime = new Date();
		this._timer = setTimeout( function() {
			document.fire( 'state:idle' );
		}, this.time );
		
	}
	
});

document.observe( 'dom:loaded', function() {
	
	idle_time = 2; // Number of minutes until idle is set
	idle_time = idle_time * 60 * 1000;
	
	new Notifier(idle_time);
	
	RadiShout = new RadiShoutClass();
	
	document.observe( 'state:idle', onIdle ).observe( 'state:active', onActive );
		
	function onIdle( e ) {
		
		idle = true;
		$( 'shoutbox_idle' ).show();
			
	}
	
	function onActive( e ) {
		
		idle = false;
		$( 'shoutbox_idle' ).hide();	
		
	}
	
	$( 'shoutbox' ).observe( 'submit', function( event ) { event.stop(); RadiShout.postShout(); });
		
});	