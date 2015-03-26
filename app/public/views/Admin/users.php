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
						<th> </th>
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
		var data = <?= $usersTable; ?>;		
		 
		// Bind the mouse events
		bindMouseEvents();
		
		// Initialize datatable
		var table = $('.admin #users-table').DataTable({
			data: data,
			order: [[1, 'desc']],
			"columns": [
				{
					"className":      'details-control dt-center control',
					"orderable":      false,
					"data":           null,
					"defaultContent": '',
					"width": "10px",
				},
				{ "data": "name" },
				{ "data": "email" },
				{ "data": "created_at" },
			],
		});
		
		// Add event listener for opening and closing details
		$('.admin #users-table tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	 
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child(format(row.data()), 'row-child').show();
				tr.addClass('shown');
			}
			
			$('[data-toggle="tooltip"]').tooltip(); // Enable tooltips on buttons
		
			enableEdit(); // Enable in-place editing
		
		} );

		var chartData = {
			labels: ["January", "February", "March", "April", "May", "June", "July"],
			datasets: [
				{
					label: "Number of users",
					fillColor: "rgb(19,19,21,1)",
					strokeColor: "rgba(242,98,34,.9)",
					pointColor: "rgba(242,98,34,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: [28, 48, 40, 19, 86, 27, 90]
				}
			]
		};
		
		var ctx = $("#user-chart").get(0).getContext("2d");
		var myNewChart = new Chart(ctx).Line(chartData);	
		
	} );
	
	function enableEdit()
	{
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
	}
	
	function bindMouseEvents()
	{
		// Confirm add user
		$("#modal-adduser-confirm").click(addUser);
		
		// Show delete confirm
		$(".admin #users-table").on("click", ".user-control-delete", function(){
			$('#modal-deleteuser').modal('show');
			$('#modal-deleteuser-confirm').data('user', $(this).data("user"))
		});
		
		// Confirm delete user
		$("#modal-deleteuser-confirm").click(deleteUser);
	}
	
	/* Formatting function for row details - modify as you need */
	function format ( d ) {
		
		// `d` is the original data object for the row
		return 	'<aside>' +
					'<figure>' +
						'<i class="fa fa-user fa-5x"></i>' +
					'</figure>' +
					'<dl class="dl-horizontal">' +
						'<dt>Name</dt>' +
						'<dd><span class="edit-name" data-type="text" data-pk="' + d.id + '" data-title="Enter username">' + d.name + '</span></dd>' +
						'<dt>E-mail</dt>' +
						'<dd><span class="edit-email" data-type="text" data-pk="' + d.id + '" data-title="Enter email">' + d.email + '</span></dd>' +
						'<dt>Password</dt>' +
						'<dd><span class="edit-password" data-type="text" data-pk="' + d.id + '" data-title="Enter password">enter new password...</span></dd>' +
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
		var name     = $("#adduser-username").val();
		var email    = $("#adduser-email").val();
		var password = $("#adduser-password").val();
		
		var params   = { 
				controller  : 'UsersController', 
				action      : 'addUser',
				name        : name,
				email       : email,
				password    : password,
			};
			
		$.post("/AppController.php", params,
			function(data) 
			{
				if (data.success)
				{
					// Reload page
					var parameters = { controller: "AdminController", action: "users" };
					DGL_nav_post("/AppController.php", parameters, "#content");
				}
			},
			'json');
			
		$("#adduser-form").trigger("reset");
	}
	
	function deleteUser()
	{
		var id     = $(this).data("user");
		
		$('#modal-deleteuser').modal('hide');
		$('.modal-backdrop').remove();
		
		var params   = { 
				controller  : 'UsersController', 
				action      : 'deleteUser',
				id          : id,
			};
			
		$.post("/AppController.php", params,
			function(data) 
			{
				if (data.success)
				{
					// Reload page
					var parameters = { controller: "AdminController", action: "users" };
					DGL_nav_post("/AppController.php", parameters, "#content");
				}
			},
			'json');
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
					
					// Reload page
					var parameters = { controller: "AdminController", action: "users" };
					DGL_nav_post("/AppController.php", parameters, "#content");
				}
			},
			'json');
			
		return d.promise();
	}
</script>

<?php include "/elements/admin_modals.php"; ?>