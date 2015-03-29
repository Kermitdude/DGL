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
	
		<h5>Roles</h5>
		
		<div style="text-align: right;">
		
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				 <i class="fa fa-plus-square"></i> Add Role
			</button>
			
			<div class="collapse" id="collapseExample">
				<div class="well">
					...
				</div>
			</div>
		
		</div>
		
		<hr />
		
		<table id="roles-table" class="table stripe hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Users</th>
				</tr>
			</thead>
		</table>
		
	</main>	

</div>


<script type="text/javascript">
	$(document).ready(function()
	{	
		var data = <?= $rolesTable; ?>;
		
		// Bind the mouse events
		bindMouseEvents();
		
		// Initialize datatable
		var table = $('.admin #roles-table').DataTable({
			data: data,
			"columns": [
				{ "data": "name" },
				{ "data": "annotation" },
				{ "data": "users" },
			],
		});
		
		// Add event listener for opening and closing details
		$('.admin #roles-table tbody').on('click', 'tr:not(".row-child")', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	 
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format(row.data()) , 'row-child').show();
				tr.addClass('shown');
			}
				
			$('[data-toggle="tooltip"]').tooltip(); // Enable tooltips on buttons
		
			enableEdit(); // Enable in-place editing
		} );
		
	} );

	function bindMouseEvents()
	{
		
		// Delete confirm
		$(".admin #roles-table").on("click", ".role-control-delete", function(){
			$('#modal-deleterole').modal('show');
			$('#modal-deleterole-confirm').data('role', $(this).data("role"));
		});
		$("#modal-deleterole-confirm").click(deleteRole);
		
	}
	
	function enableEdit()
	{	
		$('.edit-name').editable({
		   name: 'name',
		   url:  editRole,
		});		
		$('.edit-description').editable({
		   name: 'annotation',
		   url: editRole,
		});	
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
					DGL_nav_reload(); // Reload page
				}
			},
			'json');
			
		return d.promise();
	}
		
	function deleteRole()
	{
		var id = $(this).data("role");
		
		$('#modal-deleterole').modal('hide');
		$('.modal-backdrop').remove();
		
		DGL_nav_ajaxRequest('UsersController', 'deleteRole', { id : id })
			.done(DGL_nav_reload);	
	}
	
	function format ( d ) {
		
		return 	'<aside id="roles-details-' + d.id + '">' +
					'<dl class="dl-horizontal">' +
						'<dt>Name</dt>' +
						'<dd><span class="edit-name" data-type="text" data-pk="' + d.id + '" data-title="Enter username">' + d.name + '</span></dd>' +
						'<dt>Description</dt>' +
						'<dd><span class="edit-description" data-type="text" data-pk="' + d.id + '" data-title="Edit description">' + d.annotation + '</span></dd>' +
					'</dl>' +
				'</aside>' +
				'<div class="controls">' +
					'<button data-role="' + d.id + '" class="role-control-delete btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Delete role"><i class="fa fa-trash fa-fw"></i></button>' +
				'</div>';
	}
</script>

<?php include "/elements/admin_modals.php"; ?>