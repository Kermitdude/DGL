<nav id="navbar-main" class="navbar navbar-inverse navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse navbar-inverse-collapse">
			<ul class="nav navbar-nav">
			
			<!-- Games -->
				<li id="navbar-games" class="navbar-hover">
					<a href="/games">Games <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
	
				<!-- Hover -->
					<nav class="container-fluid">
						<div class="row">
							<ul>
								<header>Upcoming Matches</header>
								Stuff goes here?
							</ul>
						</div>
					</nav>
				</li>
				
			<!-- Forum -->
				<li id="navbar-forum" class="navbar-hover">
					<a href="/forum">Forum <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
					
				<!-- Hover -->
					<nav class="container-fluid">
						<div class="row">
							<ul>
								<header>Recent Topics</header>
								<li>Sup</li>
								<li>Sup 2</li>
							</ul>
						</div>
					</nav>
				</li>
				
			<!-- Watch -->
				<li id="navbar-watch" class="navbar-hover">
					<a href="/watch">Watch <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
					
				<!-- Hover -->
					<nav class="container-fluid">
						<div class="row">
							<ul class="col-md-6">
								<header>Live Streams</header>
								<li>Sup</li>
								<li>Sup 2</li>
							</ul>
							<ul class="col-md-6">
								<header>Recent Videos</header>
								<li>Sup</li>
								<li>Sup 2</li>
							</ul>
						</div>
					</nav>
				</li>
				
			<!-- Login / User -->
				<?php if (isset($_SESSION['username'])): ?>
				
				<li id="navbar-user" class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= strtoupper($_SESSION['username']) ?> <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/profile">Profile</a></li>
						<li class="divider"></li>
						<li><a href="/logout">Logout</a></li>
					</ul>
				</li>
				
			
				<?php if (DigitalGaming\User::isAuthorized($_SESSION['userid'])): ?>
				<!-- Admin -->
			
				<li id="navbar-admin" class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/admin">Dashboard</a></li>
					</ul>
				</li>
				
				<?php endif; ?>
				
				<?php else:  ?>
				
					<li data-toggle="modal" data-target="#modal-login"><a href="#" id="login-button">Login</a></li>
					
				<?php endif; ?>
				
			</ul>
		
		<!-- Logo -->
			<a href="/"><img id="logo" src="/../assets/graphics/logo.svg" /></a>

		<!-- Social Media -->
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><i class="fa fa-facebook"></i></a></li>
				<li><a href="#"><i class="fa fa-twitter"></i></a></li>
				<li><a href="#"><i class="fa fa-twitch"></i></a></li>
				<li><a href="#"><i class="fa fa-rss"></i></a></li>
			</ul>
			
			
		<!-- Search -->
			<form class="navbar-form navbar-right">
				<input type="text" class="form-control col-lg-8" placeholder="Search">
			</form>
		</div>
	</div>
</nav>