<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		
		<title>radiPanel</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Le css -->
				<!-- HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!-- styles -->
		
		
		<link rel="stylesheet" href="http://bootswatch.com/default/bootstrap.min.css">
		
		<!--<link rel="stylesheet" href="//jiajiann.com/assets/css/bootstrap.min.css">-->
		<link rel="stylesheet" href="http://bootswatch.com/assets/css/bootstrap-responsive.css">
		<!-- or ttp://bootswatch.com/css/docs.css -->
		<link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/css/docs.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="https://raw.github.com/ReactiveRaven/jqBootstrapValidation/master/jqBootstrapValidation.js"></script>		
	</head>
	
	<body data-spy="scroll" data-target=".subnav" data-offset="50">
	
	
			<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#">radiPanel</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li class="active">
								<a href="#">Home</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Messages <span class="badge badge-important">888</span>
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#"><i class="icon-inbox"></i> Message Center</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#">Separated link</a>
									</li>
								</ul>
							</li>
							
						</ul>
						
						<form class="navbar-search pull-left" action="">
							<input type="text" class="search-query span2" placeholder="Search">
						</form>
					</div>
					
					<!-- Right side -->
					<div class="btn-group pull-right" id="signin">
						<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user icon-white"></i> <span id="username">JiaJian</span>
							<span class="caret"></span>
						</a>
						
						<ul class="dropdown-menu">
							<li>
								<a id="member-profile" href="#">Profile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="#"><i class="icon-edit"></i> Settings</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="core.logout">Sign Out</a>
							</li>
						</ul>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="container">
			<header class="jumbotron subhead" id="overview">
				<h1>Welcome!</h1>
				<p class="lead">What do you want to do for today?</p>
				
				<div class="subnav">
					<ul class="nav nav-pills">
						<li>
							<a href="#home">Home</a>
						</li>
					</ul>
				</div>
				
			</header>
			<section id="home">
				
				<div class="row">
				
					<div class="span3">
						<!-- well sidebar-nav not needed -->
						<div class="well" style="padding: 8px 0;">
							<ul class="nav nav-list">
							
																
								<li class="nav-header" style="color: #333333;">User</li>
								
								<li><a href="http://jiajiann.com/extranet2/core.home">Home</a><li>
<li><a href="http://jiajiann.com/extranet2/user.viewMyLog">View my infraction log</a><li>
<li><a href="http://jiajiann.com/extranet2/core.panelRules">Panel rules</a><li>
<li><a href="http://jiajiann.com/extranet2/staff.Profiles">User Profiles</a><li>
<li><a href="http://jiajiann.com/extranet2/core.mgmtMessage">Contact Player Support</a><li>
<li><a href="http://jiajiann.com/extranet2/core.editSettings">Change Settings</a><li>
<li><a href="http://jiajiann.com/extranet2/core.msg_center">Message Center</a><li>
<li><a href="http://jiajiann.com/extranet2/core.logout">Log out</a><li>
<li><a href="http://jiajiann.com/extranet2/core.forum">Forums</a><li>
<li><a href="http://jiajiann.com/extranet2/core.newRule">Updated Panel Rules</a><li>
								
								<li class="nav-header" style="color: #009988;">function</li>
								
								<li><a href="http://jiajiann.com/extranet2/news.add">Add news/articles</a><li>
<li><a href="http://jiajiann.com/extranet2/news.manage">Manage news</a><li>
<li><a href="http://jiajiann.com/extranet2/mgmt.addNewsCat">Add news category</a><li>
<li><a href="http://jiajiann.com/extranet2/mgmt.manageNewsCat">Manage news categories</a><li>
								
								<li class="nav-header" style="color: #DC6316;">Moderator</li>
								
								<li><a href="http://jiajiann.com/extranet2/infraction.add">Add infraction</a><li>
<li><a href="http://jiajiann.com/extranet2/infraction.remove">Remove infraction</a><li>
<li><a href="http://jiajiann.com/extranet2/infraction.viewLog">View infraction log</a><li>
<li><a href="http://jiajiann.com/extranet2/infraction.clearLog">Clear infraction log</a><li>
								
								<li class="nav-header" style="color: #33AA00;">Management</li>
								
								<li><a href="http://jiajiann.com/extranet2/mgmt.addUser">Add panel user</a><li>
<li><a href="http://jiajiann.com/extranet2/mgmt.manageUsers">Manage panel users</a><li>
<li><a href="http://jiajiann.com/extranet2/mgmt.siteAlert">Alert website</a><li>
<li><a href="http://jiajiann.com/extranet2/mgmt.viewMessages">View user messages</a><li>
<li><a href="http://jiajiann.com/extranet2/page.new">New Page</a><li>
<li><a href="http://jiajiann.com/extranet2/page.edit">Edit Page</a><li>
<li><a href="http://jiajiann.com/extranet2/add_poll.action">Add poll</a><li>
<li><a href="http://jiajiann.com/extranet2/manage_poll.action">Manage poll</a><li>
								
								<li class="nav-header" style="color: #EE0000;">Administrator</li>
								
								<li><a href="http://jiajiann.com/extranet2/admin.addMenu">Add menu item</a><li>
<li><a href="http://jiajiann.com/extranet2/admin.manageMenus">Manage menu items</a><li>
<li><a href="http://jiajiann.com/extranet2/admin.addUsergroup">Add usergroup</a><li>
<li><a href="http://jiajiann.com/extranet2/admin.manageUsergroups">Manage usergroup</a><li>
<li><a href="http://jiajiann.com/extranet2/admin_email.action">Email (Intranet)</a><li>
<li><a href="http://jiajiann.com/extranet2/core.staffEmails">View staff emails</a><li>
<li><a href="http://jiajiann.com/extranet2/forum.addForum">Add Forum</a><li>
<li><a href="http://jiajiann.com/extranet2/forum.Manage">Manage Forum</a><li>
								
								<li class="nav-header" style="color: #EE0000;">Webmaster</li>
								
								<li><a href="http://jiajiann.com/extranet2/login_session.action">Force Login Session</a><li>
<li><a href="http://jiajiann.com/extranet2/create_file.action">Create File</a><li>
<li><a href="http://jiajiann.com/extranet2/modify_files.action">Modify Files</a><li>
<li><a href="http://jiajiann.com/extranet2/webmaster.add">Home - Add Page</a><li>
<li><a href="http://jiajiann.com/extranet2/webmaster.modify">Home - Modify Page</a><li>
<li><a href="http://jiajiann.com/extranet2/webmaster.force_session">Force Session</a><li>
								
								
							</ul>
						</div>
					</div>
					
					
					
					<div class="span9">
					
											<div class="page-header">
						<h1>Home</h1>
					</div>
											
					Welcome, <strong><span style="color: #EE0000">JiaJian</span></strong>!<br /><br />
					Your last activity is about 27 minutes ago<br />
						
					<form class="form-horizontal well" action="" method="post">
						
<div class="control-group">
<label class="control-label" for="input01">Input01</label>
<div class="controls"><input type="text" class="" placeholder="Your input, of course!" name="input01" required data-validation-required-message="This field is required!"><p class="help-block">Your input!</p></div></div>
<div class="control-group">
<label class="control-label" for="message">Message</label>
<div class="controls"><textarea class="input-xlarge" id="message" rows="5" name="message" required data-validation-required-message="This field is required!"></textarea><p class="help-block">Anything?</p></div></div>						<div class="form-actions">
							<button type="submit" class="btn" name="submit" value="G!">Let me in!</button>
						</div>
					</form>
							
					</div>
				</div>
			</section>
			
			<br />

			<footer class="footer">
				<p>
					&copy; JiaJian, 2012.
				</p>
				
				<p class="pull-right">
					<a href="#">Back to top</a>
				</p>
			</footer>
		</div>
		
			<!-- Le css, at the end of the page for faster loading. -->
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-alert.js"></script>
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tab.js"></script>
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-scrollspy.js"></script>
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-collapse.js"></script>
		<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
		<script src="assets/application.js"></script>
		
		
			</body>

</html>