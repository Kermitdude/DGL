/**
 *  @file dgl.js
 *  @author Dan Curry
 *  @copyright Copyright Dan Curry
 *  @date 2015
 *  @brief Contains global functions for use within any of the sub-pages of the website.
 *  
 *  Some functions that pertain to individual pages will not be contained
 *  herein, but will instead be found within the individual pages. 
 */
 
/******************************************************************************* Debug */	

var debugI = 1;
function debug(args)
{
	if (args instanceof Array)
	{
		$.each(args, function(i) {
			console.log("Tracer " + debugI + ": " + args[i]);
			debugI++;
		});
	}
	else
	{
		console.log("Tracer " + debugI + ": " + args);
		debugI++;
	}
}
	
/******************************************************************************* Initialize */	

/* Init */
$(document).ready(function()
{		
	DGL_nav_mapRoutes();
	DGL_init_bindMouse();
	
	// Prime modals
	$('#modal-login').on('shown.bs.modal', function () {
		$('#modal-login-username').focus();
	})
})

/**
 *  @brief DGL_init_bindMouse
 *  
 *  @details Binds the mouse events to particular actions
 */
function DGL_init_bindMouse()
{
	// Initiate Login
	$("#login-button").click(function(e) { e.preventDefault() });
	$("#modal-login-submit").click(DGL_Auth_Initiate);
	$("#modal-login-username").keyup(function(e)
		{
			if (e.keyCode == 13) DGL_Auth_Initiate();
		});	
	$("#modal-login-password").keyup(function(e)
		{
			if (e.keyCode == 13) DGL_Auth_Initiate();
		});	
		
	// Menu hovers
	$(".navbar-hover").hover(
		function () 
		{
			$('nav', this).show();
		},
		function () {
			$('nav', this).hide();
		});
}

/******************************************************************************* Navigation */

/**
 *  @brief DGL_nav_mapRoutes
 *  
 *  @return Maps all the URLs to the appropriate controller/methods
 */
function DGL_nav_mapRoutes()
{
	page('/logout', DGL_Auth_Logout);
	DGL_nav_setRoute('/', 'IndexController', 'index');
	DGL_nav_setRoute('/users', 'UsersController', 'index');
	DGL_nav_setRoute('/users/:user', 'UsersController', 'profile', '#navbar-user');
	DGL_nav_setRoute('/profile', 'UsersController', 'profile', '#navbar-user');
	DGL_nav_setRoute('/games', 'GamesController', 'index', '#navbar-games');
	DGL_nav_setRoute('/games/:game', 'GamesController', 'view', '#navbar-games');
	DGL_nav_setRoute('/games/:game/season/:season', 'GamesController', 'season', '#navbar-games');
	DGL_nav_setRoute('/watch', 'WatchController', 'index', '#navbar-watch');
	DGL_nav_setRoute('/watch/live/', 'WatchController', 'live', '#navbar-watch');
	DGL_nav_setRoute('/watch/recent/', 'WatchController', 'recent', '#navbar-watch');
	DGL_nav_setRoute('/watch/game/:game', 'WatchController', 'game', '#navbar-watch');
	DGL_nav_setRoute('/admin', 'AdminController', 'index', '#navbar-admin');
	DGL_nav_setRoute('/admin/users', 'AdminController', 'users', '#navbar-admin');
	DGL_nav_setRoute('*', null, null);
	page.start();
}

/**
 *  @brief DGL_nav_setRoute
 *  
 *  @param [in] route The URL path
 *  @param [in] controller The controller to access
 *  @param [in] action The method to use within the controller
 *  @param [in] highlight The element to highlight
 *  
 *  @details Creates the routes for each link
 */
function DGL_nav_setRoute(route, controller, action, highlight)
{
	page(route, 
		function(ctx, next) { DGL_nav_highlight(ctx, next, highlight) }, // Highlight the menu item, if appropriate
		function(ctx, next) { DGL_nav_controller(ctx, next, controller, action) }); // Navigate
}

/**
 *  @brief DGL_nav_highlight
 *  
 *  @param [in] ctx Context for Pages.js
 *  @param [in] next Calling this invokes the next route in line, if any
 *  @param [in] highlight The element to highlight
 *  
 *  @details Highlights the appropriate menu item, if supplied by the DGL_nav_setRoute function
 */
function DGL_nav_highlight(ctx, next, highlight)
{
	$('#navbar-main .active').removeClass('active');
	if (highlight) $(highlight).addClass('active');
	next();
}

/**
 *  @brief DGL_nav_controller
 *  
 *  @param [in] ctx Context for Pages.js
 *  @param [in] next Calling this invokes the next route in line, if any
 *  
 *  @details Routes to the AppController
 */
function DGL_nav_controller(ctx, next, controller, action)
{
	var parameters = { controller: controller, action: action, params: ctx.params };
	DGL_nav_post("/AppController.php", parameters, "#content");
}

function DGL_nav_post(file, parameters, div)
{
	$.post(file, parameters)
		.done(function(data) 
		{
			$(div).html(data);
		});
}

/**
 *  @brief DGL_nav_reload
 *  
 *  @details Reloads the current page by sending a new ajax request
 */
function DGL_nav_reload()
{
	var current = window.location.pathname;
	page(current);	
}

function DGL_nav_loadElement(element, div)
{
	var params = { 
			controller: 'ElementsController', 
			action    : element
		};
		
	$.post("/AppController.php", params)
		.done(function(data)
		{
			$(div).html(data);
		});
}

/******************************************************************************* Authentication */

function DGL_Auth_Initiate()
{
	var username = $("#modal-login-username").val();
	var password = $("#modal-login-password").val();
	var params = { 
			controller: 'LoginController', 
			action    : 'login', 
			username  : username, 
			password  : password 
		};
		
	$.post("/AppController.php", params,
		function(data) 
		{
			if (data.success) DGL_Auth_onLoginSuccess();
			else DGL_Auth_onLoginFail();
		},
		'json');
}

function DGL_Auth_Logout()
{	
	var params = { 
			controller: 'LoginController', 
			action    : 'logout'
		};
		
	$.post("/AppController.php", params)
		.done(function()
		{
			DGL_nav_loadElement('menu', 'header');
			page('/'); // go home
		});
}

/**
 *  @brief DGL_Auth_onLoginSuccess
 *  
 *  @details Processes a proper login
 */
function DGL_Auth_onLoginSuccess()
{
	// Close modal
	$("#modal-login-error").hide();
	$('#modal-login').modal('hide');
	
	// Reload current page
	DGL_nav_loadElement('menu', 'header');
	DGL_nav_reload();
}

/**
 *  @brief DGL_Auth_onLoginFail
 *  
 *  @details Processes a failed login
 */
function DGL_Auth_onLoginFail()
{
	$("#modal-login-error").html("<strong>Oops!</strong> The username/password combination failed, please try again.");
	$("#modal-login-error").show();
}

/******************************************************************************* Miscellaneous */

/* Scroll to the specified ID */
function scrollToID(id, speed)
{
	var offSet = 50;
	var targetOffset = $(id).offset().top - offSet;
	var mainNav = $('#main-nav');
	$('html,body').animate({scrollTop:targetOffset}, speed);
	if (mainNav.hasClass("open")) 
	{
		mainNav.css("height", "1px").removeClass("in").addClass("collapse");
		mainNav.removeClass("open");
	}
}

/* Returns a date ordinal */
function getDateOrdinal(num) {
	var end_num = num.charAt(num.length - 1);
	var start_num = num.charAt(0);
	var abbrev = 'th';
	if (end_num == '1' && start_num != '1') abbrev = 'st';
	if (end_num == '2' && start_num != '1') abbrev = 'nd';
	if (end_num == '3' && start_num != '1') abbrev = 'rd';
	return num + abbrev;
}