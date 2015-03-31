<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

<div class="admin">

	<nav id="sidebar">		
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li><a href="/admin">Dashboard</a></li>
			<li><a href="/admin/users">Users</a></li>
			<li class="active"><a href="/admin/roles">Roles</a></li>
			<li><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
			<li><a href="/admin/settings">Settings</a></li>
		</ul>
	</nav>
	
	<main>		
	
	<!-- Chart -->
		<canvas id="role-chart" style="width: 450px; height: 300px;"></canvas>

	<!-- Add role -->
		<section class="pull-right">
		
			<form id="addrole-form" class="form-transparent">
			
				<p><i class="fa fa-users fa-3x"></i></p>
				
				<!-- Name -->
					<div class="form-group">
						<input type="text" class="form-control" id="addrole-name" placeholder="Name of role">
					</div>
					
				<!-- Description -->
					<div class="form-group">
						<input type="text" class="form-control" id="addrole-description" placeholder="Description of role">
					</div>
					
				<!-- Submit -->
					<div class="form-group pull-right">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-addrole">
							Add role
						</button>
					</div>
				
			</form>
		
		</section>
		
	<!-- List users -->
		<section class="section-well">
		
			<table id="roles-table" class="table stripe hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Users</th>
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
		var roles = <?= $rolesTable; ?>;
		
		// Bind the mouse events
		bindMouseEvents();
		
		// Initialize chart
		chartInit(roles);		
		
		// Initialize datatable
		var table = $('.admin #roles-table').DataTable({
			data: roles,
			"columns": [
				{ "data": "name" },
				{ "data": "annotation" },
				{ "data": "users" },
			],
		});
		
		// Add event listener for opening and closing details
		$('.admin #roles-table tbody').on('click', 'tr:not(".row-child")', function () {
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

	/* Show a bar graph */
	function chartInit(roles)
	{
		var roleNames = [];
		var roleUsers = [];
		var max = Math.min(roles.length, 20); // Allow only 20 options for chart
		
		for (var i = 0; i < max; i++)
		{
			roleNames[i] = roles[i].name;
			roleUsers[i] = roles[i].users;
		}
		
		var chartData = {
			labels: roleNames,
			datasets: [
				{
					label: "Users per Role",
					fillColor: "rgba(19,19,21,.5)",
					strokeColor: "rgba(242,98,34,.9)",
					pointColor: "rgba(242,98,34,1)",
					pointStrokeColor: "rgba(0,0,0,.5)",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: roleUsers
				}
			]
		};
		
		var ctx = $("#role-chart").get(0).getContext("2d");
		var myNewChart = new Chart(ctx).Bar(chartData);
	}
	
	function bindMouseEvents()
	{
		// Add role confirm
		$("#modal-addrole-confirm").click(addRole);
		
		// Delete role confirm
		$(".admin #roles-table").on("click", ".role-control-delete", function(){
			$('#modal-deleterole').modal('show');
			$('#modal-deleterole-confirm').data('role', $(this).data("role"));
		});
		$("#modal-deleterole-confirm").click(deleteRole);
		
	}
	
	function addRole()
	{		
		$('.modal-backdrop').remove();
		
		var params   = { 
				name        : $("#addrole-name").val(),
				annotation  : $("#addrole-description").val(),
			};
		
		DGL_nav_ajaxRequest('UsersController', 'addRole', params)
			.done(function(d) {
				if (d.success) DGL_nav_reload();
			});	
			
		$("#addrole-form").trigger("reset");
	}
	
	function enableEdit()
	{	
		var permissions = <?= $permsList; ?>;	
		
		$('.edit-name').editable({
		   name: 'name',
		   url:  editRole,
		});	
		
		$('.edit-description').editable({
		   name: 'annotation',
		   url: editRole,
		});	
		
		$('.edit-permissions').editable({
		   name: 'permissions',
		   source: permissions,
		   mode: 'inline',
		   url: editRole
		});	
	}
		
	function deleteRole()
	{
		var id = $(this).data("role");
		
		$('#modal-deleterole').modal('hide');
		$('.modal-backdrop').remove();
		
		DGL_nav_ajaxRequest('UsersController', 'deleteRole', { id : id })
			.done(DGL_nav_reload);	
	}
	
	function editRole(params)
	{
		var d = new $.Deferred;
		params.controller =  'UsersController';
		params.action = 'editRole';
			
		$.post("/AppController.php", params,
			function(data) 
			{
				if (data.success)
				{
					d.resolve();
				}
				else d.reject('Error!');
			},
			'json');
			
		return d.promise();
	}
	
	function format ( d ) 
	{
				
		return 	'<aside id="roles-details-' + d.id + '">' +
					'<dl class="dl-horizontal">' +
						'<dt>Name</dt>' +
						'<dd><span class="edit-name" data-type="text" data-pk="' + d.id + '" data-title="Enter username">' + d.name + '</span></dd>' +
						'<dt>Description</dt>' +
						'<dd><span class="edit-description" data-type="text" data-pk="' + d.id + '" data-title="Edit description">' + d.annotation + '</span></dd>' +
						'<dt>Permissions</dt>' + 
						'<dd><span class="edit-permissions" data-type="checklist" data-value="' + d.permissions.toString() + '" data-pk="' + d.id + '" data-title="Select permissions"></span></dd>' +
					'</dl>' +
				'</aside>' +
				'<div class="controls">' +
					'<button data-role="' + d.id + '" class="role-control-delete btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Delete role"><i class="fa fa-trash fa-fw"></i></button>' +
				'</div>';
	}
</script>

<?php include "/elements/admin_modals.php"; ?>