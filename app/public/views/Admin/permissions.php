<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

<div class="admin">

	<nav id="sidebar">	
		<ul class="nav nav-pills nav-stacked" data-spy="affix" data-offset-top="50">
			<li><a href="/admin">Dashboard</a></li>
			<li><a href="/admin/users">Users</a></li>
			<li><a href="/admin/roles">Roles</a></li>
			<li class="active"><a href="/admin/permissions">Permissions</a></li>
			<li><a href="/admin/stats">Statistics</a></li>
			<li><a href="/admin/logs">Logs</a></li>
			<li><a href="/admin/settings">Settings</a></li>
		</ul>
	</nav>
	
	<main>
	
	<!-- Add permission -->
	
		<section class="pull-right">
		
			<form id="addpermission-form" class="form-transparent">
			
				<p><i class="fa fa-lock fa-3x"></i></p>
				
				<!-- Name -->
					<div class="form-group">
						<input type="text" class="form-control" id="addpermission-name" placeholder="Name of permission">
					</div>
					
				<!-- Description -->
					<div class="form-group">
						<input type="text" class="form-control" id="addpermission-description" placeholder="Description of permission">
					</div>
					
				<!-- Submit -->
					<div class="form-group pull-right">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-addpermission">
							Add permission
						</button>
					</div>
				
			</form>
		
		</section>
		
		<br style="clear: both" />
		
	<!-- List users -->
		<section class="section-well">
		
			<table id="permissions-table" class="table stripe hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Roles</th>
					</tr>
				</thead>
			</table>
			
		</section>
		
	</main>	

</div>

<script type="text/javascript">
	$(document).ready(function()
	{	
		var permissions = <?= $permissionsTable; ?>;
		
		// Bind the mouse events
		bindMouseEvents();
		
		// Initialize datatable
		var table = $('.admin #permissions-table').DataTable({
			data: permissions,
			"columns": [
				{ "data": "name" },
				{ "data": "annotation" },
				{ "data": "roles" },
			],
		});
		
		// Add event listener for opening and closing details
		$('.admin #permissions-table tbody').on('click', 'tr:not(".row-child")', function () {
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
		
		$('.edit-name').editable({
		   name: 'name',
		   url:  editPermission,
		});	
		
		$('.edit-description').editable({
		   name: 'annotation',
		   url: editPermission,
		});	
	}	
	
	function bindMouseEvents()
	{
		// Add permission confirm
		$("#modal-addpermission-confirm").click(addPermission);
		
		// Delete permission confirm
		$(".admin #permissions-table").on("click", ".permission-control-delete", function(){
			$('#modal-deletepermission').modal('show');
			$('#modal-deletepermission-confirm').data('permission', $(this).data("permission"));
		});
		$("#modal-deletepermission-confirm").click(deletePermission);
		
	}
		
	function addPermission()
	{		
		var params   = { 
				name        : $("#addpermission-name").val(),
				annotation  : $("#addpermission-description").val(),
			};
		
		DGL_nav_ajaxRequest('UsersController', 'addPermission', params)
			.done(function(d) {
				if (d.success) DGL_nav_reload();
			});	
			
		$("#addpermission-form").trigger("reset");
	}
	
	function deletePermission()
	{
		var id = $(this).data("permission");
		
		$('#modal-deletepermission').modal('hide');
		$('.modal-backdrop').remove();
		
		DGL_nav_ajaxRequest('UsersController', 'deletePermission', { id : id })
			.done(DGL_nav_reload);	
	}
	
	function editPermission(params)
	{
		var d = new $.Deferred;
		params.controller =  'UsersController';
		params.action = 'editPermission';
			
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
	
	function format ( d ) {
				
		return 	'<aside id="permissions-details-' + d.id + '">' +
					'<dl class="dl-horizontal">' +
						'<dt>Name</dt>' +
						'<dd><span class="edit-name" data-type="text" data-pk="' + d.id + '" data-title="Enter username">' + d.name + '</span></dd>' +
						'<dt>Description</dt>' +
						'<dd><span class="edit-description" data-type="text" data-pk="' + d.id + '" data-title="Edit description">' + d.annotation + '</span></dd>' +
					'</dl>' +
				'</aside>' +
				'<div class="controls">' +
					'<button data-permission="' + d.id + '" class="permission-control-delete btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Delete permission"><i class="fa fa-trash fa-fw"></i></button>' +
				'</div>';
	}
</script>

<?php include "/elements/admin_modals.php"; ?>