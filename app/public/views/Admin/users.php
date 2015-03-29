<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

<div class="admin admin-users">

	<nav id="sidebar">		
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li><a href="/admin">Dashboard</a></li>
			<li class="active"><a href="/admin/users">Users</a></li>
			<li><a href="/admin/roles">Roles</a></li>
			<li><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
			<li><a href="/admin/settings">Settings</a></li>
		</ul>
	</nav>
	
	<main>
		
	<!-- Chart -->
		<canvas id="user-chart" style="width: 450px; height: 300px;"></canvas>
		
	<!-- Add user -->
		<section class="pull-right" id="user-addnew">
			
			<p><i class="fa fa-user-plus fa-3x"></i></p>
			
			<form id="adduser-form">
			
				<!-- Username -->
					<div class="form-group">
						<input type="text" class="form-control" id="adduser-username" placeholder="Username">
					</div>
					
				<!-- E-mail -->
					<div class="form-group">
						<input type="text" class="form-control" id="adduser-email" placeholder="Email">
					</div>
					
				<!-- Password -->
					<div class="form-group">
						<input type="text" class="form-control" id="adduser-password" placeholder="Password">
					</div>
					
				<!-- Submit -->
					<div class="form-group pull-right">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-adduser">
							Add user
						</button>
					</div>
				
			</form>
		
		</section>
		
	<!-- List users -->
		<section class="section-well">
		
			<table id="users-table" class="table stripe hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>E-mail</th>
						<th>Created</th>
					</tr>
				</thead>
			</table>
			
		</section>
	</main>	

</div>

	
<script type="text/javascript" src="/assets/js/Chart.min.js"></script> <!-- Chart.js -->

<script type="text/javascript">

	$(document).ready(function()
	{	
		var users = <?= $usersTable; ?>;		
		 
		// Bind the mouse events
		bindMouseEvents();
		
		// Initialize datatable
		var table = $('.admin #users-table').DataTable({
			data: users,
			"columns": [
				{ "data": "name" },
				{ "data": "email" },
				{ "data": "created_at" },
			]
		});

		var chartData = {
			labels: ["January", "February", "March", "April", "May", "June", "July"],
			datasets: [
				{
					label: "Number of users",
					fillColor: "rgba(19,19,21,.5)",
					strokeColor: "rgba(242,98,34,.9)",
					pointColor: "rgba(242,98,34,1)",
					pointStrokeColor: "rgba(0,0,0,.5)",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: [28, 48, 40, 19, 86, 27, 90]
				}
			]
		};
		
		var ctx = $("#user-chart").get(0).getContext("2d");
		var myNewChart = new Chart(ctx).Line(chartData);
		
		// Add event listener for opening and closing details
		$('.admin #users-table tbody').on('click', 'tr:not(".row-child")', function () {
			var row = table.row( this );
	 
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				$(this).removeClass('shown');
			}
			else {
				// Open this row
				row.child(format(row.data()), 'row-child').show();
				$(this).addClass('shown');
			}
			
			$('[data-toggle="tooltip"]').tooltip(); // Enable tooltips on buttons
		
			enableEdit(); // Enable in-place editing
		
		} );
		
	} );
	
	function enableEdit()
	{	
		var roles = <?= $rolesList; ?>;	
		
		$('.edit-name').editable({
		   name: 'name',
		   url:  editUser,
		});		
		$('.edit-email').editable({
		   name: 'email',
		   url: editUser,
		});	
		$('.edit-password').editable({
		   name: 'password',
		   url: editUser,
		});		
		$('.edit-roles').editable({
			name: 'roles', 
			source: roles,
			url: editUser
		});
	}
	
	function bindMouseEvents()
	{
		// Confirm add user
		$("#modal-adduser-confirm").click(addUser);
		
		// Delete confirm
		$(".admin #users-table").on("click", ".user-control-delete", function(){
			$('#modal-deleteuser').modal('show');
			$('#modal-deleteuser-confirm').data('user', $(this).data("user"));
		});
		$("#modal-deleteuser-confirm").click(deleteUser);
		
		// Reset password confirm
		$(".admin #users-table").on("click", ".user-control-reset", function(){
			$('#modal-resetpass').modal('show');
			$('#modal-resetpass-confirm').data('user', $(this).data("user"));
		});
		$("#modal-resetpass-confirm").click(sendPassword);
		
	}
	
	function format ( d ) {
				
		return 	'<aside id="user-details-' + d.id + '">' +
					'<figure>' +
						'<i class="fa fa-user fa-5x"></i>' +
					'</figure>' +
					'<dl class="dl-horizontal">' +
						'<dt>Name</dt>' +
						'<dd><span class="edit-name" data-type="text" data-pk="' + d.id + '" data-title="Enter username">' + d.name + '</span></dd>' +
						'<dt>E-mail</dt>' +
						'<dd><span class="edit-email" data-type="email" data-pk="' + d.id + '" data-title="Enter email">' + d.email + '</span></dd>' +
						'<dt>Password</dt>' +
						'<dd><span class="edit-password" data-type="text" data-pk="' + d.id + '" data-title="Enter password">enter new password...</span></dd>' +
						'<dt>Roles</dt>' +
						'<dd><span class="edit-roles" data-type="checklist" data-value="' + d.roles.toString() + '" data-pk="' + d.id + '" data-title="Select roles">Select roles</span></dd>' +
					'</dl>' +
				'</aside>' +
				'<div class="controls">' +
					'<button data-user="' + d.id + '" class="user-control-ban btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ban user"><i class="fa fa-ban"></i></button>' +
					'<button data-user="' + d.id + '" class="user-control-delete btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Delete user"><i class="fa fa-trash fa-fw"></i></button>' +
					'<button data-user="' + d.id + '" class="user-control-reset btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Send new password"><i class="fa fa-key"></i></button>' +
					'<button data-user="' + d.id + '" class="user-control-send btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Send message"><i class="fa fa-envelope fa-fw"></i></button>' +
				'</div>';
	}
				
	function addUser()
	{		
		var params   = { 
				name        : $("#adduser-username").val(),
				email       : $("#adduser-email").val(),
				password    : $("#adduser-password").val(),
			};
		
		DGL_nav_ajaxRequest('UsersController', 'addUser', params)
			.done(function(d) {
				if (d.success) DGL_nav_reload();
			});	
			
		$("#adduser-form").trigger("reset");
	}
	
	function deleteUser()
	{
		var id = $(this).data("user");
		
		$('#modal-deleteuser').modal('hide');
		$('.modal-backdrop').remove();
		
		DGL_nav_ajaxRequest('UsersController', 'deleteUser', { id : id })
			.done(DGL_nav_reload);	
	}

	function editUser(params)
	{
		var d = new $.Deferred;
		params.controller =  'UsersController';
		params.action = 'editUser';
			
		$.post("/AppController.php", params,
			function(data) 
			{
				if (data.success)
				{
					d.resolve();
					DGL_nav_reload(); // Reload page
				}
			},
			'json');
			
		return d.promise();
	}
	
	function sendPassword()
	{
		var id = $(this).data('user');
		
		if (id)
		{
		
			DGL_nav_ajaxRequest('EmailController', 'sendPassword', { id : id })
				.done(function(d) { 
					//if (d.success) noty({ text: 'E-mail successfully sent to ' + d.name });
				});		
		}
	}
</script>

<?php include "/elements/admin_modals.php"; ?>